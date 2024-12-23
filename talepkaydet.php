<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="kaydet" ){

$talep='0';

if( isset($_POST['sifremiz']) ){
$sifre=$_POST['sifremiz'];
}else{
$sifre='';
}

if($uyeid!=='' ){
$talep='kaydet';
}else if($uyeid=='' && $sifre==''){
$bak = $db->prepare("SELECT * FROM uyeler WHERE eposta=:eposta or tel=:tel");
$bak->bindValue(':eposta', $_POST['eposta']);
$bak->bindValue(':tel', '0'.substr($_POST['tel'],-10));
$bak->execute();
if($bak->rowCount()){
$veri = $bak->fetch(PDO::FETCH_ASSOC);
$uyeid=$veri['id'];

$yenisifre=substr(time(),-4);

if ( $_POST['tel']!=='' ){

$smsmesaj='Doğrulama için sms kodu: '.$yenisifre. ' olarak belirlenmiştir.';
$smsnumaralar='0'.substr($_POST['tel'],-10);
include "panel/smsapi.php";
}

$song = $db->prepare("UPDATE uyeler SET sifre =:sifre  where id=$uyeid ");
$song->bindValue(':sifre', $yenisifre);
$song->execute();

echo "Telefonunuza gelen sms kodunu girerek devam ediniz.<br><input type=\"password\" class=\"form-control\" style=\"margin-top:10px;\" placeholder=\"Sms Kodu\" name=\"sifremiz\" id=\"sifremiz\" >";

}else{

$yenisifre=substr(time(),-4);

if ( $_POST['tel']!=='' ){

$smsmesaj='Sms kodu: '.$yenisifre. ' olarak belirlenmiştir.';
$smsnumaralar='0'.substr($_POST['tel'],-10);
include "panel/smsapi.php";
}

$yeni = $db->prepare("INSERT INTO uyeler SET isim = :isim, eposta = :eposta, tel = :tel, il=:il, ilce=:ilce, adres=:adres, sifre = :sifre, uyetipi='Müşteri', yetki='11' ");
$yeni->bindValue(':isim', $_POST['isim']);
$yeni->bindValue(':eposta', $_POST['eposta']);
$yeni->bindValue(':tel', '0'.substr($_POST['tel'],-10));
$yeni->bindValue(':il', $_POST['il']);
$yeni->bindValue(':ilce', $_POST['ilce']);
$yeni->bindValue(':adres', $_POST['adres']);
$yeni->bindValue(':sifre', $yenisifre);
$yeni->execute();
$uyeid=$db->lastInsertId();

echo "Telefonunuza gelen sms kodunu girerek devam ediniz.<br><input type=\"password\" class=\"form-control\" style=\"margin-top:10px;\" placeholder=\"Sms Kodu\" name=\"sifremiz\" id=\"sifremiz\" >";


}
}else if($uyeid=='' && $sifre!==''){

$uyemi = $db->prepare("SELECT * FROM uyeler WHERE (eposta=:eposta or tel=:tel) and sifre=:sifre");
$uyemi->bindValue(':eposta', $_POST['eposta']);
$uyemi->bindValue(':tel', '0'.substr($_POST['tel'],-10));
$uyemi->bindValue(':sifre', $sifre);
$uyemi->execute();
if($uyemi->rowCount()){
$uyeveri = $uyemi->fetch(PDO::FETCH_ASSOC);
$uyeid=$uyeveri['id'];

$song = $db->prepare("UPDATE uyeler SET songiris =:songiris  where id=".$uyeid." ");
$song->bindValue(':songiris', date('Y-m-d H:i:s',time()));
$song->execute();
setcookie("uyeid", $uyeid, time()+86400);

$talep='kaydet';
}else{
echo "<p style=\"color:#900\">Sms kodu Hatalı! Lütfen tekrar deneyiniz</p><input type=\"password\" class=\"form-control\" style=\"margin-top:10px;\" placeholder=\"Sms Kodu\" name=\"sifremiz\" id=\"sifremiz\" >";
}
}

if($talep=='kaydet'){

$kat2=$_POST['kat'];
$katbak = $db->prepare("SELECT * FROM kategoriler WHERE baslik=:baslik");
$katbak->bindValue(':baslik', $_POST['kat']);
$katbak->execute();
if($katbak->rowCount()){
$katveri = $katbak->fetch(PDO::FETCH_ASSOC);
$ustkatid=$katveri['ustkat'];
$ustkatbak = $db->prepare("SELECT * FROM kategoriler WHERE id=:ustkatid");
$ustkatbak->bindValue(':ustkatid', $ustkatid);
$ustkatbak->execute();
if($ustkatbak->rowCount()){
$ustkatveri = $ustkatbak->fetch(PDO::FETCH_ASSOC);
$kat1=$ustkatveri['baslik'];
}
}

$kaydet = $db->prepare("INSERT INTO talepler SET uyeid=:uyeid, kat1=:kat1, kat2=:kat2, detaylar=:detaylar, galeri=:galeri, butce=:butce, il=:il, ilce=:ilce, adres=:adres, nezaman=:nezaman, tarih=:tarih, saat=:saat, isim = :isim, eposta = :eposta, tel = :tel, talepno = :talepno, durum='Onay Bekliyor' ");
$kaydet->bindValue(':uyeid', $uyeid);
$kaydet->bindValue(':kat1', $kat1);
$kaydet->bindValue(':kat2', $kat2);
$kaydet->bindValue(':detaylar', $_POST['detaylar']);
$kaydet->bindValue(':galeri', $_POST['galeri']);
$kaydet->bindValue(':butce', $_POST['butce']);
$kaydet->bindValue(':il', $_POST['il']);
$kaydet->bindValue(':ilce', $_POST['ilce']);
$kaydet->bindValue(':adres', $_POST['adres']);
$kaydet->bindValue(':nezaman', $_POST['nezaman']);
$kaydet->bindValue(':tarih', date('Y-m-d',strtotime($_POST['tarih'])));
$kaydet->bindValue(':saat', $_POST['saat']);
$kaydet->bindValue(':isim', $_POST['isim']);
$kaydet->bindValue(':eposta', $_POST['eposta']);
$kaydet->bindValue(':tel', '0'.substr($_POST['tel'],-10));
$kaydet->bindValue(':talepno', $_POST['dosyano']);
$kaydet->execute();

$kisiid=$yetkiliid;
$mesaj="<a href=\"/panel/talepyonet.php\">Onay bekleyen talep var</a>";
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$kisiid."','".$mesaj."') ");
$bildir->execute();

echo "<h4 style=\"font-size:20px;\">Talebiniz Başarıyla Kaydedildi</h4>";
echo "<p>Talebiniz onay sonrasında <b>".$_POST['kat']."</b> alanında hizmet veren üyelerimize bildirilecektir.</p>";
echo "<p>Siz şimdi arkanıza yaslanın ve teklifleri bekleyin.</p>";
echo "<p>Talebinizi <a href=\"/taleplerim.php\"><b>\"Taleplerim\"</b></a> sayfasından takip edebilirsiniz.</p>";
}

}}
?>

<?php ob_end_flush(); ?>