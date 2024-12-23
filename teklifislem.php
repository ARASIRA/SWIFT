<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
if($uyeid==''){
echo "Teklif verebilmek için önce üye girişi yapmalısınız!";
}else{
?>






<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="teklifver" ){

if( is_numeric($_POST['teklif'])==False ){
echo "Girdiğiniz değer sadece sayı olmalıdır!";
}else{

$talepno = $_POST['talepno'];
$teklif=$_POST['teklif'];

$rs = $db->prepare("SELECT * FROM teklifler where uyeid = :uyeid and talepno = :talepno order by id asc");
$rs->bindValue(':uyeid', $uyeid);
$rs->bindValue(':talepno', $talepno);
$rs->execute();
$rssay = $rs->rowCount();
if($rssay){ 
echo "Bu talep için daha önce teklif verdiniz!";
}else{

$talepbak = $db->prepare("SELECT * FROM talepler WHERE talepno=:talepno");
$talepbak->bindValue(':talepno', $talepno);
$talepbak->execute();
$talepbilgi = $talepbak->fetch(PDO::FETCH_ASSOC);

$kat2 = $db->query("SELECT * FROM kategoriler where id=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);

if($kat1['teklifucreti']!==''){
$teklifucreti=$kat1['teklifucreti'];
}
if($kat2['teklifucreti']!==''){
$teklifucreti=$kat2['teklifucreti'];
}
if(strpos($paketozellikleri,"Teklif")>0){
$teklifucreti=0;
}

if(strpos($teklifucreti,"%")>0){
$teklifucreti=$teklif/100 * (str_replace("%","",$teklifucreti));
}

if($uyebakiye<$teklifucreti){
$fark=number_format(($teklifucreti - $uyebakiye), 2, ',', '.');
echo "Bakiyeniz Yetersiz. Teklif verebilmek için en az ".$fark." TL daha bakiye yüklemelisiniz. <a href=\"/bakiyem.php\">Hemen Bakiye Yükle</a>";
}else{

if($teklifucreti>0){
$song1 = $db->prepare("UPDATE uyeler SET bakiye = bakiye - '".$teklifucreti."' where id=:uyeid ");
$song1->bindValue(':uyeid', $uyeid);
$song1->execute();

$siparisno=$uyeid.time();
$miktar=0-$teklifucreti;
$aciklama=$talepno." nolu talep teklif ücreti";
$para = $db->prepare("INSERT INTO ucretler SET uyeid = :uyeid, siparisno = :siparisno, miktar = :miktar, aciklama = :aciklama, durum=1 ");
$para->bindValue(':uyeid', $uyeid);
$para->bindValue(':siparisno', $siparisno);
$para->bindValue(':miktar', $miktar);
$para->bindValue(':aciklama', $aciklama);
$para->execute();
}

$yeni = $db->prepare("INSERT INTO teklifler SET uyeid = :uyeid, talepno = :talepno, teklif = :teklif, durum='Devam Ediyor' ");
$yeni->bindValue(':uyeid', $uyeid);
$yeni->bindValue(':talepno', $talepno);
$yeni->bindValue(':teklif', $teklif);
$yeni->execute();
$teklifid=$db->lastInsertId();

$talepbak = $db->prepare("SELECT * FROM talepler WHERE talepno=:talepno");
$talepbak->bindValue(':talepno', $talepno);
$talepbak->execute();
if($talepbak->rowCount()){
$talepbilgi = $talepbak->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);

$kisiid=$talepbilgi['uyeid'];
$kisibak = $db->prepare("SELECT * FROM uyeler WHERE id=:kisiid ");
$kisibak->bindValue(':kisiid', $kisiid);
$kisibak->execute();
if($kisibak->rowCount()){
$kisibilgi = $kisibak->fetch(PDO::FETCH_ASSOC);
$kisiid=$kisibilgi['id'];
$kisitokenid=$kisibilgi['tokenid'];
$kisitel=$kisibilgi['tel'];
$kisieposta=$kisibilgi['eposta'];
}
}

if($talepbilgi['ozelid']!=='0'){
$maxteklifsayisi=1;
}

$teksay = $db->prepare("SELECT * FROM teklifler where talepno = :talepno order by id asc");
$teksay->bindValue(':talepno', $talepno);
$teksay->execute();
$teklifsay = $teksay->rowCount();
if($teklifsay==$maxteklifsayisi){
$song3 = $db->prepare("UPDATE talepler SET durum = 'Kapandı' where talepno=:talepno ");
$song3->bindValue(':talepno', $talepno);
$song3->execute();
}

################################## BİLDİRİMLER ######################################

$alicilar=$kisiid;
$siteicimesaj="<a href=\"/talep/".$talepno."\">Talebinize yeni teklif geldi.</a>";
$smsmesaj='Talebinize yeni teklif geldi.';
$epostakonu="Yeni Teklif Geldi #".$talepno;
$epostamesaj="<p>".$kat2['baslik']." talebine yeni teklif verildi.</p>";
$epostamesaj=$epostamesaj."<br><br><div style='background: #eef1ed;border:1px solid #ddd;padding:5px;'>
<p>Teklif Sahibi: <b>".$isim."</b></p>
<p>Verdiği Teklif: <b>".number_format($teklif,2,',','.')." TL</b><br>Teklifi onaylamadan önce, işin detaylarını konuşmak için teklif sahibiyle iletişime geçmenizi tavsiye ederiz.<br></p>
<p><a href='".$siteurl."/talep/".$talepno."' style='text-decoration:none;display:inline-block;color:#ffffff;background-color:#583;border-radius:3px;border:1px solid #472;padding:8px;'>TEKLİFİ DEĞERLENDİR</a></p>";
if(!empty($talepbilgi['secim'])){
$epostamesaj=$epostamesaj."<p><b>Talebinizin Detayları</b>";
$secimler=json_decode($talepbilgi['secim'],true);
foreach($secimler as $key=>$value){ 
$epostamesaj=$epostamesaj."<p><b>".$secimler[$key]['soru'].": </b>";
foreach($secimler[$key]['secenek'] as $key2=>$value2){ $epostamesaj=$epostamesaj.$value2." "; }
$epostamesaj=$epostamesaj."</p>";
}
}
$epostamesaj=$epostamesaj."<p><b>Açıklama: </b>".$talepbilgi['detaylar']."</p>";
$epostamesaj=$epostamesaj."<p><b>Bölge: </b>".$talepbilgi['il']."/".$talepbilgi['ilce']."</p>";
$epostamesaj=$epostamesaj."<p><b>Hizmetin istendiği tarih: </b>";
if($talepbilgi['nezaman']=='Belli bir zaman (3 hafta içinde)'){
$epostamesaj=$epostamesaj.date('d.m.Y',$talepbilgi['tarih'])." ".$talepbilgi['saat'];
}else{
$epostamesaj=$epostamesaj.$talepbilgi['nezaman'];
}
$epostamesaj=$epostamesaj."</p>";
$epostamesaj=$epostamesaj."</div>";

include "panel/bildirimgonder.php";

################################## BİLDİRİMLER ######################################

echo "<h4 style=\"font-size:20px;\">Teklifiniz Başarıyla Kaydedildi</h4>";
echo "<p>Teklifinizi talep sahibine ilettik. </p>";
echo "<p>Teklifinizin talep sahibi tarafından onaylanması durumunda size bilgi vereceğiz.</p>";
echo "<p>Teklif verdiğiniz işleri <a href=\"/tekliflerim.php\"><b>\"Tekliflerim\"</b></a> sayfasından takip edebilirsiniz.</p>";

}
}
}

}}
?>





<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="teklifonayla" ){

$teklifid=$_POST['teklifid'];

$rs2 = $db->prepare("SELECT * FROM teklifler where id = :teklifid order by id asc");
$rs2->bindValue(':teklifid', $teklifid);
$rs2->execute();
$rs2say = $rs2->rowCount();
if($rs2say){
$rs2bak = $rs2->fetch(PDO::FETCH_ASSOC);
$talepno=$rs2bak['talepno'];

$rs = $db->prepare("SELECT * FROM teklifler where talepno = :talepno and id = :teklifid order by id asc");
$rs->bindValue(':talepno', $talepno);
$rs->bindValue(':teklifid', $teklifid);
$rs->execute();
$rssay = $rs->rowCount();
if($rssay){
$fbak = $rs->fetch(PDO::FETCH_ASSOC);
$firmaid=$fbak['uyeid'];
$teklifmiktari=$fbak['teklif'];

$bak = $db->prepare("SELECT * FROM uyeler WHERE id=:firmaid");
$bak->bindValue(':firmaid', $firmaid);
$bak->execute();
if($bak->rowCount()){
$firma = $bak->fetch(PDO::FETCH_ASSOC);
$kisitel=$firma['tel'];
$kisieposta=$firma['eposta'];

$song1 = $db->prepare("UPDATE teklifler SET durum = 'Başarısız' where talepno=:talepno ");
$song1->bindValue(':talepno', $talepno);
$song1->execute();

$song2 = $db->prepare("UPDATE teklifler SET durum = 'Başarılı' where id=:teklifid and talepno=:talepno ");
$song2->bindValue(':talepno', $talepno);
$song2->bindValue(':teklifid', $teklifid);
$song2->execute();

$song3 = $db->prepare("UPDATE talepler SET durum = 'İş Verildi', kazananid=:kazananid, kazanantarih=:kazanantarih, kazananteklif=:kazananteklif where talepno=:talepno ");
$song3->bindValue(':talepno', $talepno);
$song3->bindValue(':kazananid', $firmaid);
$song3->bindValue(':kazanantarih', date('Y-m-d H:i:s'));
$song3->bindValue(':kazananteklif', $teklifmiktari);
$song3->execute();



################################## BİLDİRİMLER ######################################
$mesaj="<a href=\"/talep/".$talepno."\">Tebrikler. Teklifiniz Kazandı.</a>";
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$firmaid."','".$mesaj."') ");
$bildir->execute();

if ( $kisitel!=='' ){
$smsmesaj='Tebrikler. Teklifiniz Kazandı. Hizmet vereceğiniz üye ile hemen iletişime geçin.';
$smsnumaralar=$kisitel;
include "panel/smsapi.php";
}

foreach($db->query("SELECT * FROM teklifler where talepno='".$talepno."' and durum = 'Başarısız' order by id asc") as $row) {
$kfirma = $db->query("SELECT * FROM uyeler where id=".$row['uyeid']." ")->fetch(PDO::FETCH_ASSOC);

$mesaj="<a href=\"/talep/".$talepno."\">Üzgünüz, Teklifiniz Kazanamadı.</a>";
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$kfirma['id']."','".$mesaj."') ");
$bildir->execute();

if ( $kfirma['tel']!=='' ){
$smsmesaj='Üzgünüz, Teklifiniz Kazanamadı. Başka işlerde görüşmek dileğiyle.';
$smsnumaralar=$kfirma['tel'];
include "panel/smsapi.php";
}

}
################################## BİLDİRİMLER ######################################


echo "<h4 style=\"font-size:20px;text-align:center;color:#090;\">Teklifi Onayladınız!</h4>";
echo "<p>İletişim bilgilerinizi teklif sahibine ilettik. Şimdi Teklif sahibinin sizinle iletişime geçmesini bekleyin. </p>";
echo "<p>Talep ettiğiniz hizmeti aldıktan sonra yine bu sayfada işi onaylamayı unutmayın!</p>";

}
}
}
}}
?>



<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="teklifireddet" ){

$teklifid=$_POST['teklifid'];

$rs2 = $db->prepare("SELECT * FROM teklifler where id = :teklifid order by id asc");
$rs2->bindValue(':teklifid', $teklifid);
$rs2->execute();
$rs2say = $rs2->rowCount();
if($rs2say){
$rs2bak = $rs2->fetch(PDO::FETCH_ASSOC);
$talepno=$rs2bak['talepno'];

$rs = $db->prepare("SELECT * FROM teklifler where talepno = :talepno and id = :teklifid order by id asc");
$rs->bindValue(':talepno', $talepno);
$rs->bindValue(':teklifid', $teklifid);
$rs->execute();
$rssay = $rs->rowCount();
if($rssay){
$fbak = $rs->fetch(PDO::FETCH_ASSOC);
$firmaid=$fbak['uyeid'];
$teklifmiktari=$fbak['teklif'];

$bak = $db->prepare("SELECT * FROM uyeler WHERE id=:firmaid");
$bak->bindValue(':firmaid', $firmaid);
$bak->execute();
if($bak->rowCount()){
$firma = $bak->fetch(PDO::FETCH_ASSOC);
$kisitel=$firma['tel'];
$kisieposta=$firma['eposta'];

$song1 = $db->prepare("UPDATE teklifler SET durum = 'Başarısız' where talepno=:talepno ");
$song1->bindValue(':talepno', $talepno);
$song1->execute();

$song3 = $db->prepare("UPDATE talepler SET durum = 'Kapandı', kazanantarih=:kazanantarih, kazananteklif=:kazananteklif where talepno=:talepno ");
$song3->bindValue(':talepno', $talepno);
$song3->bindValue(':kazanantarih', date('Y-m-d H:i:s'));
$song3->bindValue(':kazananteklif', $teklifmiktari);
$song3->execute();



################################## BİLDİRİMLER ######################################
$mesaj="<a href=\"/talep/".$talepno."\">Teklifiniz Reddedildi.</a>";
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$firmaid."','".$mesaj."') ");
$bildir->execute();

if ( $kisitel!=='' ){
$smsmesaj='Üzgünüz, Teklifiniz Reddedildi. Diğer fırsatlardan sizi haberdar edeceğiz.';
$smsnumaralar=$kisitel;
include "panel/smsapi.php";
}
################################## BİLDİRİMLER ######################################


echo "<h4 style=\"font-size:20px;text-align:center;color:#090;\">Teklifi Reddettiniz!</h4>";
echo "<p>Bu kararınızı teklif sahibine ilettik. Şimdi isterseniz ihtiyacınız için talebinizi oluşturabilirsiniz. </p>";

}
}
}
}}
?>





<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="isionayla" ){

$talepno=$_POST['talepno'];
$puan=$_POST['puan'];
$yorum=$_POST['yorum'];

$talepbak = $db->prepare("SELECT * FROM talepler WHERE talepno=:talepno");
$talepbak->bindValue(':talepno', $talepno);
$talepbak->execute();
$talepbilgi = $talepbak->fetch(PDO::FETCH_ASSOC);
$firmaid=$talepbilgi['kazananid'];
$kazananteklif=$talepbilgi['kazananteklif'];

$kat2 = $db->query("SELECT * FROM kategoriler where id=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);

if($kat1['komisyonucreti']!==''){
$komisyonucreti=$kat1['komisyonucreti'];
}
if($kat2['komisyonucreti']!==''){
$komisyonucreti=$kat2['komisyonucreti'];
}

$paketozellikleri='';
$paketbilgi1 = $db->query("SELECT * FROM ucretler where uyeid=".$firmaid." and paketid>0 and durum=1 order by id desc ")->fetch(PDO::FETCH_ASSOC);
if(isset($paketbilgi1['id'])){
$paketbilgi = $db->query("SELECT * FROM paketler where id=".$paketbilgi1['paketid']." ")->fetch(PDO::FETCH_ASSOC);
if(isset($paketbilgi['id'])){
$paketbaslangic = strtotime($paketbilgi1['tarih']);
$paketbitis = strtotime('+'.$paketbilgi['sure'].' Months', $paketbaslangic);
if($paketbitis>time()){
$paketid=$paketbilgi['id'];
$paket=$paketbilgi['baslik'];
$paketozellikleri=$paketbilgi['ozellikler'];
}
}
}

if(strpos($paketozellikleri,"Komisyon")>0){
$komisyonucreti=0;
}

if(strpos($komisyonucreti,"%")>0){
$komisyon=$kazananteklif/100 * (str_replace("%","",$komisyonucreti));
}else{
$komisyon=$komisyonucreti;
}


if($komisyon>0){
$song1 = $db->prepare("UPDATE uyeler SET bakiye = bakiye - '".$komisyon."' where id=:uyeid ");
$song1->bindValue(':uyeid', $firmaid);
$song1->execute();

$siparisno=$firmaid.time();
$miktar=0-$komisyon;
$aciklama=$talepno." nolu talep komisyon ücreti";
$para = $db->prepare("INSERT INTO ucretler SET uyeid = :uyeid, siparisno = :siparisno, miktar = :miktar, aciklama = :aciklama, durum=1 ");
$para->bindValue(':uyeid', $firmaid);
$para->bindValue(':siparisno', $siparisno);
$para->bindValue(':miktar', $miktar);
$para->bindValue(':aciklama', $aciklama);
$para->execute();
}

$song3 = $db->prepare("UPDATE talepler SET durum = 'İş Tamamlandı', puan=:puan, yorum=:yorum, yorumtarihi=:yorumtarihi where talepno=:talepno ");
$song3->bindValue(':talepno', $talepno);
$song3->bindValue(':puan', $puan);
$song3->bindValue(':yorum', $yorum);
$song3->bindValue(':yorumtarihi', date('Y-m-d H:i:s'));
$song3->execute();

$yenipuan = $db->query("SELECT avg(puan) FROM talepler where kazananid=".$firmaid." ")->fetchColumn();

$song1 = $db->prepare("UPDATE uyeler SET puan=:puan where id=:firmaid ");
$song1->bindValue(':puan', $yenipuan);
$song1->bindValue(':firmaid', $firmaid);
$song1->execute();

$mesaj="<a href=\"/talep/".$talepno."\">Müşteriniz, işi tamamladığınızı bildirdi.</a>";
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$firmaid."','".$mesaj."') ");
$bildir->execute();

$kisiid=$yetkiliid;
$mesaj='<a href="/panel/talepyonet.php?durum=İş Tamamlandı">Onay bekleyen değerlendirme var</a>';
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$kisiid."','".$mesaj."') ");
$bildir->execute();

echo "<h4 style=\"font-size:20px;text-align:center;color:#090;\">İşi Onayladınız!</h4>";
echo "<p>Başka işlerde görüşmek dileğiyle, İYİ GÜNLER...</p>";

}}
?>



<?php } ?>
<?php ob_end_flush(); ?>