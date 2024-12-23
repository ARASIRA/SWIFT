<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";
?>


<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme Sayfası</title>
</head>
<body>

<div style="width: 100%;margin: 0 auto;display: table;">

	<?php

	## 1. ADIM için örnek kodlar ##

	####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
	#
	## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
	#$merchant_id 	= ''; Db den çekiliyor
	#$merchant_key 	= ''; Db den çekiliyor
	#$merchant_salt	= ''; Db den çekiliyor
	#

$aciklama = $_POST['aciklama'];
$paketid = isset($_POST['paketid']) ? $_POST['paketid']:'0';
$toplamtutar=$_POST['tutar'];

if(empty($uyeid) || !isset($toplamtutar)){
echo "Sorun Oluştu! Lütfen yeniden giriş yaparak deneyiniz.";
}else{

$sipuyeid=$uyeid;
$sipisim=$isim;
$sipeposta=$siteposta;
$sipadres=$siteadres.' TR';
$siptel=$uyetel;
$tarih=date('Y.m.d H:i:s');

$siparisno=$uyeid.time();
$yeni = $db->prepare("INSERT INTO ucretler SET uyeid = :uyeid, aciklama = :aciklama, paketid=:paketid, miktar = :miktar, siparisno=:siparisno ");
$yeni->bindValue(':uyeid', $uyeid);
$yeni->bindValue(':aciklama', $aciklama);
$yeni->bindValue(':paketid', $paketid);
$yeni->bindValue(':miktar', $toplamtutar);
$yeni->bindValue(':siparisno', $siparisno);
$yeni->execute();



if($toplamtutar<0.1){

if($paketid=='0'){
$bak = $db->prepare("SELECT * FROM uyeler WHERE id=:uyeid");
$bak->bindValue(':uyeid', $uyeid);
$bak->execute();
if($bak->rowCount()){
$uyebilgi = $bak->fetch(PDO::FETCH_ASSOC);
$uyebakiye = $uyebilgi['bakiye'];
}

$yenibakiye = round(($uyebakiye + $miktar),2);
$song1 = $db->prepare("UPDATE uyeler SET bakiye = :yenibakiye where id=:uyeid ");
$song1->bindValue(':uyeid', $uyeid);
$song1->bindValue(':yenibakiye', $yenibakiye);
$song1->execute();
}

$song1 = $db->prepare("UPDATE ucretler SET durum = 1 where siparisno=:siparisno ");
$song1->bindValue(':siparisno', $siparisno);
$song1->execute();

header("Location: /bakiyem.php?odeme=ok");
exit();
}


$sepet=array($aciklama,$toplamtutar,1);

ob_end_flush(); 

	## Müşterinizin sitenizde kayıtlı veya form vasıtasıyla aldığınız eposta adresi
	$email = $sipeposta;
	#
	## Tahsil edilecek tutar.
	$payment_amount	= intval($toplamtutar*100); //9.99 için 9.99 * 100 = 999 gönderilmelidir.
	#
	## Sipariş numarası: Her işlemde benzersiz olmalıdır!! Bu bilgi bildirim sayfanıza yapılacak bildirimde geri gönderilir.
	$merchant_oid = $siparisno;
	#
	## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız ad ve soyad bilgisi
	$user_name = $sipisim;
	#
	## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız adres bilgisi
	$user_address = $sipadres;
	#
	## Müşterinizin sitenizde kayıtlı veya form aracılığıyla aldığınız telefon bilgisi
	$user_phone = $siptel;
	#
	## Başarılı ödeme sonrası müşterinizin yönlendirileceği sayfa
	## !!! Bu sayfa siparişi onaylayacağınız sayfa değildir! Yalnızca müşterinizi bilgilendireceğiniz sayfadır!
	## !!! Siparişi onaylayacağız sayfa "Bildirim URL" sayfasıdır (Bakınız: 2.ADIM Klasörü).
	$merchant_ok_url = $siteurl."/bakiyem.php?odeme=ok";
	#
	## Ödeme sürecinde beklenmedik bir hata oluşması durumunda müşterinizin yönlendirileceği sayfa
	## !!! Bu sayfa siparişi iptal edeceğiniz sayfa değildir! Yalnızca müşterinizi bilgilendireceğiniz sayfadır!
	## !!! Siparişi iptal edeceğiniz sayfa "Bildirim URL" sayfasıdır (Bakınız: 2.ADIM Klasörü).
	$merchant_fail_url = $siteurl."/bakiyem.php?odeme=hata";
	#
	## Müşterinin sepet/sipariş içeriği
	//$user_basket = "";
	#
	# ÖRNEK $user_basket oluşturma - Ürün adedine göre array'leri çoğaltabilirsiniz
	$user_basket = base64_encode(json_encode(array($sepet)));
	
	############################################################################################

	## Kullanıcının IP adresi
	if( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	} elseif( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} else {
		$ip = $_SERVER["REMOTE_ADDR"];
	}

	## !!! Eğer bu örnek kodu sunucuda değil local makinanızda çalıştırıyorsanız
	## buraya dış ip adresinizi (https://www.whatismyip.com/) yazmalısınız. Aksi halde geçersiz paytr_token hatası alırsınız.
	$user_ip=$ip;
	##

	## İşlem zaman aşımı süresi - dakika cinsinden
	$timeout_limit = "30";

	## Hata mesajlarının ekrana basılması için entegrasyon ve test sürecinde 1 olarak bırakın. Daha sonra 0 yapabilirsiniz.
	$debug_on = 1;

    ## Mağaza canlı modda iken test işlem yapmak için 1 olarak gönderilebilir.
	if($uyeid!=='1'){
    $test_mode = 1;
	}else{
	$test_mode = 1;
	}

	$no_installment	= 0; // Taksit yapılmasını istemiyorsanız, sadece tek çekim sunacaksanız 1 yapın

	## Sayfada görüntülenecek taksit adedini sınırlamak istiyorsanız uygun şekilde değiştirin.
	## Sıfır (0) gönderilmesi durumunda yürürlükteki en fazla izin verilen taksit geçerli olur.
	$max_installment = 0;

	$currency = "TL";

	####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
	$hash_str = $merchant_id .$user_ip .$merchant_oid .$email .$payment_amount .$user_basket.$no_installment.$max_installment.$currency.$test_mode;
	$paytr_token=base64_encode(hash_hmac('sha256',$hash_str.$merchant_salt,$merchant_key,true));
	$post_vals=array(
			'merchant_id'=>$merchant_id,
			'user_ip'=>$user_ip,
			'merchant_oid'=>$merchant_oid,
			'email'=>$email,
			'payment_amount'=>$payment_amount,
			'paytr_token'=>$paytr_token,
			'user_basket'=>$user_basket,
			'debug_on'=>$debug_on,
			'no_installment'=>$no_installment,
			'max_installment'=>$max_installment,
			'user_name'=>$user_name,
			'user_address'=>$user_address,
			'user_phone'=>$user_phone,
			'merchant_ok_url'=>$merchant_ok_url,
			'merchant_fail_url'=>$merchant_fail_url,
			'timeout_limit'=>$timeout_limit,
			'currency'=>$currency,
            'test_mode'=>$test_mode
		);
	
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1) ;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$result = @curl_exec($ch);

	if(curl_errno($ch))
		die("PAYTR IFRAME connection error. err:".curl_error($ch));

	curl_close($ch);
	
	$result=json_decode($result,1);
		
	if($result['status']=='success')
		$token=$result['token'];
	else
		die("PAYTR IFRAME failed. reason:".$result['reason']);
	#########################################################################

	?>

	<!-- Ödeme formunun açılması için gereken HTML kodlar / Başlangıç -->
    <script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
    <iframe src="https://www.paytr.com/odeme/guvenli/<?php echo $token;?>" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
	<script>iFrameResize({},'#paytriframe');</script>
	<!-- Ödeme formunun açılması için gereken HTML kodlar / Bitiş -->


<?php } ?>

</div>

<br><br>
</body>
</html>