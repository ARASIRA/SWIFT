<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
$talepno=$_GET["talepno"];
$talepbak = $db->prepare("SELECT * FROM talepler WHERE talepno=:talepno");
$talepbak->bindValue(':talepno', $talepno);
$talepbak->execute();
if($talepbak->rowCount()){
$talepbilgi = $talepbak->fetch(PDO::FETCH_ASSOC);
$id=$talepbilgi['id'];

$song = $db->prepare("UPDATE talepler SET hit = hit + 1 where id=".$id." ");
$song->execute();

$talepuyebilgi = $db->query("SELECT * FROM uyeler where id=".$talepbilgi['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);


if($kat1['minteklif']!==''){
$minteklif=$kat1['minteklif'];
}
if($kat2['minteklif']!==''){
$minteklif=$kat2['minteklif'];
}

if($kat1['teklifucreti']!==''){
$teklifucreti=$kat1['teklifucreti'];
}
if($kat2['teklifucreti']!==''){
$teklifucreti=$kat2['teklifucreti'];
}
if(strpos($paketozellikleri,"Teklif")>0){
$teklifucreti=0;
}



?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title><?php echo $kat2['baslik'];?> <?php echo $talepbilgi['il'];?> <?php echo $talepbilgi['ilce'];?> - <?php echo $talepbilgi['talepno'];?></title>
      <meta name="description" content="<?php echo $talepbilgi['il'];?> <?php echo $talepbilgi['ilce'];?> bölgesinde <?php echo $kat2['baslik'];?> hizmeti istiyorum - <?php echo $talepbilgi['talepno'];?>">
      <meta name="robots" content="index">
      <meta name="author" content="<?=$siteurl?>">

<?php include "script.php"; ?>


   </head>
   <body>
       
       
      <!-- Header Area Start -->
      <header class="jobguru-header-area stick-top forsticky page-header">
<?php include "menu.php"; ?>
      </header>
      <!-- Header Area End -->


       
      <!-- Single Candidate Start -->
      <section class="single-candidate-page bg-green" style="padding:30px 0;">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                        <h4 style="color:#fff">Talep No: <b><?php echo $talepbilgi['talepno'];?></b></h4>
               </div>
            </div>
         </div>
      </section>
      <!-- Single Candidate End -->
      <!-- Single Candidate Bottom Start -->
      <section class="single-candidate-bottom-area section_50">
         <div class="container">
            <div class="row">
               <div class="col-md-8">
                  <div class="single-candidate-bottom-left">
                     <div class="single-candidate-widget" style="background:#fff;padding:20px;">
                        <h3>Talep Detayları</h3>
						
<?php if($talepbilgi['durum']=='Onay Bekliyor'){ ?>
<p class="alert alert-warning"><i class="fa fa-info-circle"></i> Bu iş talebi henüz onay aşamasında!</p>
<?php }else if($talepbilgi['durum']=='Kapandı' || $talepbilgi['durum']=='İş Verildi' || $talepbilgi['durum']=='İş Tamamlandı'){ ?>
<p class="alert alert-danger"><i class="fa fa-warning"></i> Bu iş için teklif süreci sona erdi!<?php if($talepbilgi['uyeid']!==$uyeid){ ?><br><a href="/talepler"> Hizmet profilinize uygun diğer işleri görmek için tıklayınız.</a><?php } ?></p>
<?php } ?>

                        <ul class="job-overview">
                           <li>
                              <h4><i class="fa fa-bars"></i> Kategori</h4>
                              <p><?php echo $kat2['baslik'];?></p>
                           </li>
						   <?php if(!empty($talepbilgi['secim'])){ ?>
                           <li>
                              <h4><i class="fa fa-bars"></i> Detaylar</h4>
							  <?php 
							  $secimler=json_decode($talepbilgi['secim'],true);
							  foreach($secimler as $key=>$value){ 
							  ?>
                              <p><b><?=$secimler[$key]['soru']?></b><br><?php foreach($secimler[$key]['secenek'] as $key2=>$value2){ echo $value2; } ?></p>
							  <?php } ?>
                           </li>
						   <?php } ?>
                           <li>
                              <h4><i class="fa fa-info"></i> Açıklama</h4>
                              <p><?php echo $talepbilgi['detaylar'];?></p>
<?php
if(!empty($talepbilgi['galeri'])){
$query = $db->prepare("SELECT * FROM galeri where dosyano='".$talepbilgi['galeri']."' order by id asc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay !== "0"){ ?>
<div class="row">
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<div class="col-6 col-sm-3 col-md-2" style="margin-bottom:5px;">
<a href="<?=$row['adres']?>" class="example-image-link" data-lightbox="example-set" data-title="Görseller"  ><img src="<?=$row['adres']?>" class="img-thumbnail"></a>
</div>
<?php } ?>
</div>
<?php }} ?>
                           </li>
                           <li>
                              <h4><i class="fa fa-map-marker"></i> Bölge</h4>
                              <p><?php echo $talepbilgi['il'];?>/<?php echo $talepbilgi['ilce'];?></p>
                           </li>
                           <li>
                              <h4><i class="fa fa-calendar-o"></i> Hizmetin istendiği tarih</h4>
                              <p><?php if($talepbilgi['nezaman']=='Belli bir zaman'){ ?> <?php echo date('d.m.Y',$talepbilgi['tarih']);?> <?=$talepbilgi['saat']?> <?php }else{?> <?=$talepbilgi['nezaman']?> <?php } ?></p>
                           </li>
                           <li>
                              <h4><i class="fa fa-clock-o"></i> Oluşturma</h4>
                              <p><?php echo date('d.m.Y H:i',strtotime($talepbilgi['olusturma']));?></p>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
			   <div class="col-md-4">
					<div class="single-candidate-bottom-left">
                      <div class="single-candidate-widget" style="background:#fff;padding:20px;">
                        <h3>Teklif Panosu</h3>
<!--------------- Talep Sahibi İse --------------->
<?php
if ($talepbilgi['uyeid']==$uyeid){

$rsbak = $db->prepare("SELECT * FROM teklifler where talepno='".$talepbilgi['talepno']."' order by id asc");
$rsbak->execute();
$rssay = $rsbak->rowCount();
if($rssay == "0"){ 
?><!--------------- if 1 --------------->
<p class="alert alert-warning">Henüz Teklif Gelmedi</p>
<?php 
}else{ 
?>

<?php if($talepbilgi['durum']=='Kapandı'){ ?>
<p><b>Yeterli teklif sayısına ulaşıldığı için bu talep tekliflere kapandı.</b></p>
<p class="font-weight-bold text-center text-danger">Şimdi yapmanız gereken<br>Uygun teklifi onaylamak</p>
<?php } ?>

<h3 class="text-bold text-center text-success">Gelen Teklifler</h3>
<table class="table">
<?php
while($rs=$rsbak->fetch(PDO::FETCH_ASSOC)) {
$uyebak = $db->query("SELECT * FROM uyeler where id=".$rs['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
$uyehizmet = $db->query("SELECT * FROM hizmetler where uyeid=".$rs['uyeid']." and katid=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);
?>
<tr class="<?php if($rs['durum']=='Başarılı'){ echo 'table-success'; }else if($rs['durum']=='Başarısız'){ echo 'table-danger';} ?>">
<td><span class="font-weight-bold pointer onayla" data-teklifid="<?=$rs['id']?>"><?=$uyebak['isim']?></span></td>
<td class="text-right"><span class="font-weight-bold pointer onayla" data-teklifid="<?=$rs['id']?>"><?=$rs['teklif']?> TL</span>

<div id="teklifdetay<?=$rs['id']?>" style="display:none;">
<h4 class="text-center"><a href="/profil/<?=$uyehizmet['link']?>"><b><?=$uyebak['isim']?></b></a> </h4>
<p  style="display:none;margin:30px 0px;" class="text-center"> <a  style="margin-top:60px;padding:15px;font-size:21px;background:green;border:unset;border-radius:15px;color:white" class="" href="/mesajlar/<?=$uyebak['id']?>"><i class="fa fa-envelope"></i> Mesaj Yaz</a> </p>

<?php if($talepbilgi['durum']=='Teklifler Bekleniyor' || $talepbilgi['durum']=='Kapandı'){ ?>

<p style="margin:20px 5px 0 5px;font-weight:600;font-size:16px;color:#090;">Sunduğu teklif : <span style="color:#090;font-size:20px;font-weight:700;"><?=$rs['teklif']?> TL</span><br>
İşi bu üyeye vermeyi <span style="display:inline-block">onaylıyor musunuz?</span></p>
<?php } ?>

</div>
</td>
<td style="width:100px;text-align:right"><button type="button" class="btn btn-xs btn-success onayla" data-teklifid="<?=$rs['id']?>">İncele</button></td>
</tr>
<?php } ?>
</table>



<?php if($talepbilgi['durum']=='İş Verildi'){ ?>
<p>Kazanan teklif sahibinden talep ettiğiniz hizmeti aldıysanız "İşi Onayla" butonu ile talebinizi sonlandırınız.</p>
<p><button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#isionaylapopup"><i class="fa fa-check"></i> İşi Onayla </button></p>


<div class="modal fade" id="isionaylapopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
	  <h4 class="modal-title"><i class="fa fa-check text-success"></i> İşi Onayla <button type="button" class="btn btn-default pull-right" data-dismiss="modal">X</button></h4><hr>
	  <div class="govde">
	    
		
<p style="margin:20px 5px;font-weight:600;font-size:16px;color:#090;">Aldığınız hizmeti değerlendirin.</p>

<div class="rating">
  <label>
    <input type="radio" name="stars" value="1" />
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="2" />
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="3" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>   
  </label>
  <label>
    <input type="radio" name="stars" value="4" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
  <label>
    <input type="radio" name="stars" value="5" />
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
    <span class="icon">★</span>
  </label>
</div>
<textarea class="form-control" style="height:100px;margin-bottom:10px;" id="yorum" placeholder="Aldığınız hizmeti değerlendirin."></textarea>
<p class="uyari"></p>
<p><button type="button" class="btn btn-success btn-lg btn-block" id="isionayla"><i class="fa fa-check"></i> İşi Onayla </button></p>
	  </div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$(':radio').change(function() {
  console.log('New star rating: ' + this.value);
});
</script>
<script type='text/javascript'>
$(function(){
    $("#isionayla").click(function() { 
$('#isionaylapopup .uyari').html("");
var yorum = $("#yorum").val();
var bosluk='bos';
var puan='';
$(".rating input[name=stars]:checked").each(function() {
if($(this).val()!==''){
bosluk='';
puan=$(this).val();
}
});
if(bosluk=='bos'){
	$('#isionaylapopup .uyari').html("Lütfen Yıldız Puan veriniz!");
}

if(yorum=='' && bosluk==''){
var bosluk='bos';
$('#isionaylapopup .uyari').html("Lütfen kısa bir değerlendirme yorumu yazınız!");
}

if(bosluk==''){
	$('#isionaylapopup .uyari').html("Kaydediliyor...");
	$.post("/teklifislem.php?islem=isionayla", {
        talepno: '<?=$talepbilgi['talepno']?>',
		firmaid: '<?=$talepbilgi['kazananid']?>',
		puan:puan,
		yorum:yorum
        }, function (cevap) {
			if(cevap.trim().indexOf("Onayladınız")>0){
            $("#isionaylapopup .govde").html(cevap);
			}else{
			$("#isionayla .uyari").html(cevap).show("slow");
			}
    });
}
    });
});
</script>
<?php } ?>



<?php } ?><!--------------- if 1x --------------->
<?php }else{ ?><!--------------- Talep Sahibi Değilse --------------->

<?php if($talepbilgi['durum']=='Onay Bekliyor'){ ?>
<p><button type="button" class="btn btn-warning btn-lg btn-block"><i class="fa fa-warning"></i> Onay Aşamasında!</button></p>

<?php }else if($talepbilgi['durum']=='Kapandı'){ ?>
<p><b>Yeterli teklif sayısına ulaşıldığı için bu talep tekliflere kapandı.</b></p>
<?php
$bak = $db->prepare("SELECT * FROM teklifler WHERE uyeid=:mid and talepno=:talepno");
$bak->bindValue(':mid', $uyeid);
$bak->bindValue(':talepno', $talepbilgi['talepno']);
$bak->execute();
if($bak->rowCount()){
$baktim = $bak->fetch(PDO::FETCH_ASSOC);
?>
<p><button type="button" class="btn btn-info btn-block" ><b><i class="fa fa-money"></i> <?=$baktim['teklif']?> TL</b> Teklif Verdiniz</button></p>

<?php if($baktim['durum']=='Devam Ediyor'){ ?>
<p>Talep sahibinin teklif onayı bekleniyor.</p>
<?php }else if($baktim['durum']=='Başarılı'){ ?>
<p><button type="button" class="btn btn-success btn-block" ><b>Teklifiniz Onaylandı</b></button></p>
<?php }else if($baktim['durum']=='Başarısız'){ ?>
<p><button type="button" class="btn btn-danger btn-block" ><b>Teklifiniz Reddedildi</b></button></p>
<?php } ?>


<div class="bg-success" style="color:#fff;text-align:center;border-radius:10px;padding:10px;">
<b>Talep Sahibinin Bilgileri;</b><br>
<span style="font-size:20px;"><?php echo $talepuyebilgi['isim'];?></span><br>
<?php echo $talepbilgi['il'];?>/<?php echo $talepbilgi['ilce'];?><br>
<a class="btn btn-success" href="/mesajlar/<?=$talepbilgi['uyeid']?>"><i class="fa fa-envelope"></i> Mesaj Yaz </a><br>
<span style="font-size:18px;"><?php echo $talepuyebilgi['tel'];?></span>
</div>


<?php } ?>



<?php }else if($talepbilgi['durum']=='Teklifler Bekleniyor'){ ?>
<?php
$bak = $db->prepare("SELECT * FROM teklifler WHERE uyeid=:mid and talepno=:talepno");
$bak->bindValue(':mid', $uyeid);
$bak->bindValue(':talepno', $talepbilgi['talepno']);
$bak->execute();
if($bak->rowCount()){
$baktim = $bak->fetch(PDO::FETCH_ASSOC);
?>
<p><button type="button" class="btn btn-info btn-block" ><b><i class="fa fa-money"></i> <?=$baktim['teklif']?> TL</b> Teklif Verdiniz</button></p>
<?php if($talepbilgi['ozelid']=='0'){ ?>
<p>Diğer Teklifler Bekleniyor...</p>
<?php } ?>
<div class="bg-success" style="color:#fff;text-align:center;border-radius:10px;padding:10px;">
<b>Talep Sahibinin Bilgileri;</b><br>
<span style="font-size:20px;"><?php echo $talepuyebilgi['isim'];?></span><br>
<?php echo $talepbilgi['il'];?>/<?php echo $talepbilgi['ilce'];?><br>
<a style="display:none" class="btn btn-success" href="/mesajlar/<?=$talepbilgi['uyeid']?>"><i class="fa fa-envelope"></i> Mesaj Yaz </a><br>
</div>

<?php
}else{
?>
<?php if($talepbilgi['ozelid']=='0' || $talepbilgi['ozelid']==$uyeid || empty($uyeid)){?>
<p><button type="button" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#teklifverpopup"><i class="fa fa-paper-plane-o"></i> Teklif Ver </button></p>
<?php } ?>

<?php } ?>


<?php }else if($talepbilgi['durum']=='İş Verildi'){ ?>
<?php if($talepbilgi['kazananid']==$uyeid){ ?>
<p><button type="button" class="btn btn-success btn-block" ><i class="fa fa-check"></i> İş Size Verildi</button></p>
<?php }else{ ?>
<p><button type="button" class="btn btn-warning btn-block" ><i class="fa fa-warning"></i> İş Başkasına Verildi</button></p>
<p>Üzgünüz, talep sahibi diğer tekliflerden birini onayladı.</p>
<?php } ?>

<?php if($talepbilgi['kazananid']==$uyeid){ ?>
<p>Tebrikler, bu talebin sahibi teklifinizi uygun bularak işi size vermeyi kabul etti ve sizden haber bekliyor.<br> 
<p>Şimdi müşteriyle iletişime geçerek iş hakkında detayları görüşün.</p>
<?php } ?>

<?php if($talepbilgi['kazananid']==$uyeid){ ?>
<div class="bg-success" style="color:#fff;text-align:center;border-radius:10px;padding:10px;">
<b>Talep Sahibinin Bilgileri;</b><br>
<span style="font-size:20px;"><?php echo $talepuyebilgi['isim'];?></span><br>
<?php echo $talepbilgi['il'];?>/<?php echo $talepbilgi['ilce'];?><br>
<a class="btn btn-success" href="/mesajlar/<?=$talepbilgi['uyeid']?>"><i class="fa fa-envelope"></i> Mesaj Yaz </a><br>
<span style="font-size:18px;"><?php echo $talepuyebilgi['tel'];?></span>
</div>
<?php } ?>


<?php }else if($talepbilgi['durum']=='İş Tamamlandı'){ ?>

<p><button type="button" class="btn btn-success btn-lg btn-block"><i class="fa fa-check"></i> İş Tamamlandı</button></p>

<?php if($talepbilgi['kazananid']==$uyeid){ ?>

<div class="bg-success" style="color:#fff;text-align:center;border-radius:10px;padding:10px;">
<b>Talep Sahibinin Bilgileri;</b><br>
<span style="font-size:20px;"><?php echo $talepuyebilgi['isim'];?></span><br>
<?php echo $talepbilgi['il'];?>/<?php echo $talepbilgi['ilce'];?><br>
<a class="btn btn-success" href="/mesajlar/<?=$talepbilgi['uyeid']?>"><i class="fa fa-envelope"></i> Mesaj Yaz </a><br>
<span style="font-size:18px;"><?php echo $talepuyebilgi['tel'];?></span>
</div>

<?php } ?>

<?php } ?>




<?php } ?>

<?php if($talepbilgi['durum']=='İş Tamamlandı' && $talepbilgi['yorumonay']=='1'){ ?>
<br><br>
<h3>Müşteri Hizmet Değerlendirmesi</h3>

<p class="text-center"><i class="fa fa-star<?php echo $talepbilgi['puan']>0 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $talepbilgi['puan']>1 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $talepbilgi['puan']>2 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $talepbilgi['puan']>3 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $talepbilgi['puan']>4 ? '':'-o';?>"></i></p>
<p class="text-center"><?=$talepbilgi['yorum']?></p>

<?php } ?>

					  </div>
					</div>
			   </div>
            </div>
         </div>
      </section>
      <!-- Single Candidate Bottom End -->



<?php include "alt.php"; ?>




<?php if($uyeid==''){ ?>

<div class="modal fade" id="teklifverpopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
	  <h4 class="modal-title"><i class="fa fa-warning text-warning"></i> Uyarı</h4><hr>
	  <div class="govde">
	    <p style="margin:20px 5px;font-weight:600;font-size:16px;color:#090;">Taleplere teklif verebilmek için üye girişi yapmanız gerekmektedir.</p>


					<form id="girisformu">
                        <div class="single-login-field">
                           <input type="text"  class="zorunlu" placeholder="E-Posta Adresiniz" name="eposta" id="eposta">
                        </div>
                        <div class="single-login-field sifre" >
							<p>Şifreniz</p>
                           <input type="password"  class="zorunlu" placeholder="Şifreniz" name="sifre" id="sifre">
                        </div>
						<p class="uyari"></p>
                        <div class="single-login-field">
                           <button type="button" id="girisyap">Giriş Yap</button>
                        </div>
                     </form>
                    <div class="dont_have">
                        <a href="/uyeol?hedef=/talep/<?=$talepno?>">Üye Olmak İstiyorum</a>
                    </div>
	  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type='text/javascript'>
$(function(){
    $("#girisyap").click(function() { 
$('#girisformu .uyari').html("");
$("#girisformu .zorunlu").each(function() {
if($(this).val()==''){
bosluk='bos';
$(this).addClass("input-hata");
}else{
bosluk='';
$(this).removeClass("input-hata");
}
});

if(bosluk=='bos'){
$('#girisformu .uyari').html("Lütfen E-Posta ve Şifrenizi Giriniz!");
}else{
	$.ajax({
            type: "POST",
            url: "/uyeislem?islem=girisyap",             
            data: $('#girisformu ').serialize(),                
            success: function(gelenmesaj){
		if(gelenmesaj.trim().indexOf("giriniz")>0){
			$("#sifre").addClass("zorunlu");
			$(".sifre").show("slow");
		}else if(gelenmesaj.trim().indexOf("Başarılı")>0){
			location.href = "/talep/<?php echo $talepbilgi['talepno'];?>/teklifver";
		}else{
			$('#girisformu .uyari').html(gelenmesaj.trim());
		}
            }
        });
}
    });
});
</script>



<?php }else if($uyeid!=='' && $talepbilgi['uyeid']!==$uyeid){?>
<div class="modal fade" id="teklifverpopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
	  <h4 class="modal-title">Teklif Ver</h4><hr>
	  <div class="govde">
	    <input type="number" step="0.01" min="0" class="form-control text-right" id="teklifmiktari" placeholder="Teklifiniz (TL)">
		<p class="komisyon text-info"  style="margin:10px 5px;"></p>
		
        <p style="margin:20px 5px;font-weight:600;font-size:16px;color:#090;">Bu hizmet talebini yukarıda belirttiğiniz ücret karşılığında üstlenmeyi onaylıyor musunuz?</p>
		
		<p style="margin:20px 5px;font-weight:600;font-size:14px;color:#900;" class="hide teklifuyari"></p>
		
		<p><button type="button" class="btn btn-success btn-lg btn-block" id="teklifveronay"><i class="fa fa-check"></i> Onayla </button></p>
		<input type="hidden" id="komisyon">
	  </div>
      </div>
      <div class="modal-footer">
	    <a href="/tekliflerim.php" class="btn btn-success hide teklifsonbuton">Tekliflerim</a>
        <button type="button" class="btn btn-default teklifvazgecbuton" data-dismiss="modal">Vazgeç</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
var komisyon=0;
$('#teklifmiktari').on("keyup",function () {
var teklif=$('#teklifmiktari').val();
if(teklif<<?=$minteklif?>){
$('.komisyon').html("Teklifiniz <span style=\"color:#a00;font-size:18px;font-weight:700;\" ><?=$minteklif?> TL</span> den az olamaz!");
}else{
var teklifucreti="<?=$teklifucreti?>";
if(teklifucreti.indexOf("%")>-1){
komisyon=teklif/100*parseFloat(teklifucreti.replace("%",""));
}else{
komisyon=parseFloat(teklifucreti);
}
kmsyn=komisyon.toFixed(2);
<?php if($talepbilgi['ozelid']=='0'){ ?>
$('#komisyon').val(komisyon.toFixed(2));
if(teklifucreti>0){
$('.komisyon').html("Bu teklif karşılığında bakiyenizden <span style=\"color:#a00;font-size:18px;font-weight:700;\" >"+ komisyon.toFixed(2) +" TL</span> düşülecektir.<br>Onaylıyor musunuz?");
}else{
$('.komisyon').html("");
}
<?php }else{ ?>
komisyon=0;
kmsyn=0;
<?php } ?>
}
});

$('#teklifveronay').click(function () {
$(".teklifuyari").html("").hide();
var teklif=$('#teklifmiktari').val();
var komisyon=kmsyn;
var butce=teklif;
if(teklif==''){
$(".teklifuyari").html("Teklif için sayısal bir değer girmelisiniz!").show("slow");
}else if( butce>50 && teklif<(butce/2) ){
$(".teklifuyari").html("Teklifiniz butçenin yarısından az olamaz!").show("slow");
}else{
		$.post("/teklifislem.php?islem=teklifver", {
        talepno: '<?php echo $talepbilgi['talepno'];?>',
		teklif:teklif,
		komisyon:komisyon
        }, function (cevap) {
			if(cevap.trim().indexOf("Kaydedildi")>0){
            $("#teklifverpopup .govde").html(cevap);
			$(".teklifvazgecbuton").hide();
			$(".teklifsonbuton").show();
			}else{
			$(".teklifuyari").html(cevap).show("slow");
			}
        });
}
});
</script>

<?php 
if(isset($_GET['teklif'])){ 
if($_GET['teklif']=='ver'){ 
?>
<script>
$('#teklifverpopup').modal("show");
</script>
<?php }}?>

<?php }else if($uyeid!=='' && $talepbilgi['uyeid']==$uyeid){?>

<?php if($talepbilgi['durum']=='Teklifler Bekleniyor' || $talepbilgi['durum']=='Kapandı'){?>
<div class="modal fade" id="teklifonaypopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
	  <h4 class="modal-title"><i class="fa fa-info text-success"></i> Teklif Onayı <button type="button" class="btn btn-default pull-right" data-dismiss="modal">X</button></h4><hr>
	  <div class="govde">
	    <p class="teklifdetay"></p>
		<p style="margin:0px 5px 10px 5px;"></p>
		<p class="hide uyari"></p>
		<p class="mt-2"><button type="button" class="btn btn-success btn-lg btn-block onaylason" ><i class="fa fa-check"></i> Onayla </button></p>
		<?php if($talepbilgi['ozelid']!=='0'){ ?>
		<p class="mt-1"><button type="button" class="btn btn-danger btn-lg btn-block teklifireddet" ><i class="fa fa-close"></i> Reddet </button></p>
		<?php } ?>
		<input type="hidden" class="teklifid">
	  </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$('.onayla').click(function () {
var teklifid=$(this).attr("data-teklifid");
var teklifdetay=$("#teklifdetay"+teklifid).html();
$('#teklifonaypopup .teklifid').val(teklifid);
$('#teklifonaypopup .teklifdetay').html(teklifdetay);
$('#teklifonaypopup').modal("show");
});

$('#teklifonaypopup .onaylason').click(function () {
$("#teklifonaypopup .uyari").html("").hide();
var teklifid=$('#teklifonaypopup .teklifid').val();
	$.post("/teklifislem.php?islem=teklifonayla", {
        talepno: '<?php echo $talepbilgi['talepno'];?>',
		teklifid:teklifid
        }, function (cevap) {
			if(cevap.trim().indexOf("ilettik")>0){
            $("#teklifonaypopup .govde").html(cevap);
			}else{
			$("#teklifonaypopup .uyari").html(cevap).show("slow");
			}
    });
});

$('#teklifonaypopup .teklifireddet').click(function () {
$("#teklifonaypopup .uyari").html("").hide();
var teklifid=$('#teklifonaypopup .teklifid').val();
	$.post("/teklifislem.php?islem=teklifireddet", {
        talepno: '<?php echo $talepbilgi['talepno'];?>',
		teklifid:teklifid
        }, function (cevap) {
			if(cevap.trim().indexOf("ilettik")>0){
            $("#teklifonaypopup .govde").html(cevap);
			}else{
			$("#teklifonaypopup .uyari").html(cevap).show("slow");
			}
    });
});
</script>

<?php }else if($talepbilgi['durum']=='İş Verildi'){?>

<div class="modal fade" id="teklifonaypopup">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
	  <h4 class="modal-title"><i class="fa fa-info text-success"></i> Teklif Detayı <button type="button" class="btn btn-default pull-right" data-dismiss="modal">X</button></h4><hr>
	  <div class="govde">
	    <p class="teklifdetay"></p>
	  </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$('.onayla').click(function () {
var teklifid=$(this).attr("data-teklifid");
var teklifdetay=$("#teklifdetay"+teklifid).html();
$('#teklifonaypopup .teklifdetay').html(teklifdetay);
$('#teklifonaypopup').modal("show");
});
</script>
<?php }?>



<?php }?>


<link rel="stylesheet" href="/panel/bower_components/lightbox/css/lightbox.css">
<script src="/panel/bower_components/lightbox/js/lightbox.js"></script>


</body>
</html>

<?php } ?>