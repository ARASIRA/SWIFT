<?php
include "panel/db.php";
include "kontrol.php";

if(isset($_GET["islem"])) {
if($_GET["islem"]=='girisyap') {

 $eposta = addslashes(trim($_POST["eposta"]));
 $sifre = addslashes(trim($_POST["sifre"]));
 
 if(empty($eposta) || empty($sifre)) {
 echo "Kullanıcı adı veya şifre boş bırakılamaz!";
 }else {
 
 $query = $db->prepare("SELECT * FROM uyeler WHERE eposta=:eposta AND sifre=:sifre ");
        $query->execute([
            ':eposta' =>  $eposta,
            ':sifre' =>  $sifre
        ]);
        if($query->rowCount() > 0){
        $uyeid = $query->fetch(PDO::FETCH_OBJ)->id;
		$song = $db->prepare("UPDATE uyeler SET songiris = '".date('Y-m-d H:i:s',time())."' where id=$uyeid ");
		$song->execute();

		$_SESSION['uyeid']=$uyeid;
		setcookie("uyeid", $uyeid, time()+86400 );
		
		echo "Giriş Başarılı...";
        }else{
		echo "Yanlış E-Posta veya Şifre!";
		}
 }
}}


if(isset($_GET["islem"])) {
if($_GET["islem"]=='uyeol') {

$isim = addslashes(trim($_POST["isim"]));
$eposta = addslashes(trim($_POST["eposta"]));
$sifre = addslashes(trim($_POST["sifre"]));
$tel = '0'.substr(addslashes(trim($_POST["tel"])),-10);

if(isset($_POST["sartlar"])) {
$sartlar = addslashes(trim($_POST["sartlar"]));
}else{
$sartlar = '0';
}

if(empty($isim) || empty($eposta) || empty($tel)) {
echo "Tüm alanları doldurunuz!";
}else if($sartlar!=='1'){
echo "Üyelik şartlarını kabul etmelisiniz.";
}else {


$query = $db->prepare("SELECT * FROM uyeler WHERE tel=:tel ");
        $query->execute([
            ':tel' =>  $tel
        ]);
        if($query->rowCount() > 0){
		echo "Bu Telefon numarası ile daha önce hesap oluşturulmuş! Giriş yapmak için <a href=\"/giris.php\">tıklayın</a>";
		}else{

if($tel!=='' && $sifre=='') {
$yenisifre=substr(time(),-4);
$smsmesaj=$siteurl.' için sms kodu: '.$yenisifre. ' olarak belirlenmiştir.';
$smsnumaralar=$tel;
include "panel/smsapi.php";

setcookie("yahya", $yenisifre, time()+600 );

echo "Telefonunuza gelen sms kodunu giriniz.";

 }else if($tel!=='' && $sifre!==''){

if($_COOKIE["yahya"]!==$sifre){
echo "Sms kodu yanlış girildi!";
}else{

$yeni = $db->prepare("INSERT INTO uyeler SET isim=:isim, tel=:tel, eposta=:eposta, sifre=:sifre, uyetipi='Müşteri', songiris=:songiris ");
$yeni->bindValue(':isim', $isim);
$yeni->bindValue(':tel', $tel);
$yeni->bindValue(':eposta', $eposta);
$yeni->bindValue(':sifre', $_COOKIE["yahya"]);
$yeni->bindValue(':songiris', date('Y-m-d H:i:s',time()));
$yeni->execute();
$uyeid = $db->lastInsertId();

$_SESSION['uyeid']=$uyeid;
setcookie("uyeid", $uyeid, time()+(86400*30) );

echo "uyeid=".$uyeid;

}
}
}
}
}}



if(isset($_GET["islem"])) {
if($_GET["islem"]=='sifrehatirlat') {

$tel = '0'.substr(addslashes(trim($_POST["tel"])),-10);
 
if(empty($tel) || strlen($tel)<11) {
echo "Telefon numaranızı doğru giriniz!";
}else {

$q = $db->prepare("SELECT * FROM uyeler WHERE tel=:tel");
$q->bindValue(':tel', $tel);
$q->execute();
if($q->rowCount()){
$row = $q->fetch(PDO::FETCH_ASSOC);
$uyeid = $row['id'];

$smssay=0;
if(isset($_SESSION['smssay'])){
$smssay=$_SESSION['smssay'];
}
$_SESSION['smssay']=$smssay + 1;

$smsmesaj=$siteurl.' için giriş bilgileriniz;\n E-Posta: \n '.$row['eposta']. '\nŞifre: \n '.$row['sifre'].'\n olarak kayıtlarımızda yer almaktadır.';
$smsnumaralar=$tel;
include "panel/smsapi.php";

		echo "Giriş Bilgileriniz SMS ile gönderildi.";
        }else{
		echo "Telefon kayıtlı değil";
		}
 }
}}



ob_end_flush(); // 
?>
