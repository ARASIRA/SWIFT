<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php //-----------------------------------------------FORM--------------------------------------------------------
if( isset($_GET['islem']) ){
if( $_GET['islem']=="formcek" ){
?>

<?php
$i=-1;
$q = $db->prepare("SELECT * FROM adimsecenekleri where katid=:katid order by id asc");
$q->bindValue(':katid', $_POST['katid']);
$q->execute();
if($q->rowCount()=='0'){
$ukat = $db->query("SELECT * FROM kategoriler where id=".$_POST['katid']." ")->fetch(PDO::FETCH_ASSOC);
if($ukat['ustkat']!=='0'){
$q = $db->prepare("SELECT * FROM adimsecenekleri where katid=:katid order by id asc");
$q->bindValue(':katid', $ukat['ustkat']);
$q->execute();
}
}

if($q->rowCount()){
while($row=$q->fetch(PDO::FETCH_ASSOC)) {
$i=$i+1;
?>
<div class="p-3 adim-sorular adim<?=$i?>" style="display:<?php echo $i===0 ? '':'none';?>" data-adim="<?=$i?>" data-tur="<?=$row['tur']?>">
<h5 class="font-weight-bold text-center pb-4 adim-soru"><?=$row['baslik']?></h5> <input type="hidden" name="secim[<?=$i?>][soru]" value="<?=$row['baslik']?>">
<?php if($row['tur']=='radio'){ ?>
	<?php foreach(explode("|",$row['secenekler']) as $key=>$value) { ?>
	<label class="adim-secenek"> <?=$value?> <input type="radio" name="secim[<?=$i?>][secenek][]" value="<?=$value?>"><span class="fa fa-check"></span></label>
	<?php } ?>
<?php }else if($row['tur']=='checkbox'){ ?>
	<?php foreach(explode("|",$row['secenekler']) as $key=>$value) { ?>
	<label class="adim-secenek"> <?=$value?> <input type="checkbox" name="secim[<?=$i?>][secenek][]" value="<?=$value?>"><span class="fa fa-check"></span></label>
	<?php } ?>
<?php }else if($row['tur']=='text'){ ?>
	<p class="adim-secenek"> <input type="text" class="form-control" name="secim[<?=$i?>][secenek][]"></p>
<?php }else if($row['tur']=='number'){ ?>
	<p class="adim-secenek"> <input type="number" class="form-control text-right" name="secim[<?=$i?>][secenek][]"></p>
<?php }else if($row['tur']=='date'){ ?>
	<p class="adim-secenek"> <input type="date" class="form-control" name="secim[<?=$i?>][secenek][]"></p>
<?php }else if($row['tur']=='textarea'){ ?>
	<p class="adim-secenek"> <textarea class="form-control" name="secim[<?=$i?>][secenek][]"></textarea></p>
<?php } ?>
</div>
<?php } ?>
<?php } ?>



<?php $i=$i+1; ?>
<div class="p-3 adim-sorular adim<?=$i?>" style="display:<?php echo $i===0 ? '':'none';?>" data-adim="<?=$i?>" data-tur="">
<h5 class="font-weight-bold text-center pb-4 adim-soru">Talebinizin detaylarını anlatın</h5>
<!---->
<textarea class="form-control" style="min-height:150px" name="detaylar" placeholder="Talebinize uygun teklifler alabilmek için talebinizin detaylarını yazın!" ></textarea>


<?php $galeri=rand(100,999).time();?>
<div id="yuklenenler"></div>
<div class="progress" id="upload-progress" style="display:none">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
</div>
<div class="product-upload">
    <p><i class="fa fa-upload"></i> Görseller Yükleyin</p>
    <input data-id="<?=$galeri?>" class="image-upload" id="w_screen" type="file" name="images[1]" accept="image/jpeg,image/jpg,image/png,image/gif" multiple>
</div>
<input type="hidden" value="<?=$galeri?>" name="galeri">

    <script type="text/javascript" src="/assets/base64/js/exif.js"></script>
    <script type="text/javascript" src="/assets/base64/js/ImageUploader.js"></script>
    <script type="text/javascript" src="/assets/base64/js/custom.js"></script>

<!---->
</div>



<?php $i=$i+1; ?>
<div class="p-3 adim-sorular adim<?=$i?>" style="display:<?php echo $i===0 ? '':'none';?>" data-adim="<?=$i?>" data-tur="">
<h5 class="font-weight-bold text-center pb-4 adim-soru">Nerede? (Hizmet istenen yer)</h5>
<!---->
<select class="form-control" id="til" name="il" required>
<option value=""> İl Seçiniz </option>
<?php
$query = $db->prepare("SELECT * FROM iller order by il asc ");
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<option value="<?=$row['il'];?>" > <?=$row['il'];?> </option>
<?php } ?>
</select>
<select class="form-control mt-2" id="tilce" name="ilce">
<option value=""> İlçe Seçiniz </option>
</select>
<!---->
</div>


<?php $i=$i+1; ?>
<div class="p-3 adim-sorular adim<?=$i?>" style="display:<?php echo $i===0 ? '':'none';?>" data-adim="<?=$i?>" data-tur="radio">
<h5 class="font-weight-bold text-center pb-4 adim-soru">Ne Zaman?</h5>
<!---->
<label class="adim-secenek active"> Belli bir zaman <input type="radio" name="nezaman" value="Belli bir zaman" checked><span class="fa fa-check"></span></label>
<div class="row mb-2 tarihsaat">
<div class="col-7">
<select class="form-control" name="tarih">
<?php for($k=21;$k>0;$k--){?>
<option value="<?=date('Y-m-d',strtotime('+'.$k.' days'))?>"> <?=date('d.m.Y',strtotime('+'.$k.' days'))?> </option>
<?php } ?>
</select>
</div><div class="col-5">
<select class="form-control" name="saat"><option value="00:00"> 00:00 </option><option value="01:00"> 01:00 </option><option value="02:00"> 02:00 </option><option value="03:00"> 03:00 </option><option value="04:00"> 04:00 </option><option value="05:00"> 05:00 </option><option value="06:00"> 06:00 </option><option value="07:00"> 07:00 </option><option value="08:00"> 08:00 </option><option value="09:00" selected> 09:00 </option><option value="10:00"> 10:00 </option><option value="11:00"> 11:00 </option><option value="12:00"> 12:00 </option><option value="13:00"> 13:00 </option><option value="14:00"> 14:00 </option><option value="15:00"> 15:00 </option><option value="16:00"> 16:00 </option><option value="17:00"> 17:00 </option><option value="18:00"> 18:00 </option><option value="19:00"> 19:00 </option><option value="20:00"> 20:00 </option><option value="21:00"> 21:00 </option><option value="22:00"> 22:00 </option><option value="23:00"> 23:00 </option></select>
</div>
</div>

<label class="adim-secenek"> 2 ay içinde <input type="radio" name="nezaman" value="2 ay içinde"><span class="fa fa-check"></span></label>
<label class="adim-secenek"> 6 ay içinde <input type="radio" name="nezaman" value="6 ay içinde"><span class="fa fa-check"></span></label>
<label class="adim-secenek"> Sadece fiyat bakıyorum <input type="radio" name="nezaman" value="Sadece fiyat bakıyorum"><span class="fa fa-check"></span></label>
<!---->
</div>


<?php if($uyeid==''){ ?>

<?php $i=$i+1; ?>
<div class="p-3 adim-sorular adim<?=$i?>" style="display:<?php echo $i===0 ? '':'none';?>" data-adim="<?=$i?>" data-tur="radio">
<h5 class="font-weight-bold text-center pb-4 adim-soru">Kişisel Bilgiler</h5>
<!---->

<div class="mt-4" id="girisbilgileri">
<label class="mt-1">Telefon Numaranız</label>
<input type="text" class="form-control zorunlu" placeholder="5xxxxxxx" name="tel" >
</div>
<!---->
</div>



<?php } ?>



<?php
}}
?>



<?php //-----------------------------------------------ÜCRET ARALIĞI--------------------------------------------------------
if( isset($_GET['islem']) ){
if( $_GET['islem']=="ucretcek" ){

$aralik = $db->query("SELECT MIN(enaz) as deger1, MAX(encok) as deger2 FROM hizmetler where katid=".$_POST['katid']." ")->fetch(PDO::FETCH_ASSOC);

echo $aralik['deger1']." TL - ".$aralik['deger2']." TL";
}}
?>



<?php //-----------------------------------------------TALEP KAYDET--------------------------------------------------------
if( isset($_GET['islem']) ){
if( $_GET['islem']=="kaydet" ){

if($uyeid!==''){
if(isset($_POST['secim'])){
$secim=json_encode($_POST['secim'], JSON_UNESCAPED_UNICODE);
}else{
$secim="";
}

$talepno=$uyeid.time();
$kaydet = $db->prepare("INSERT INTO talepler SET uyeid=:uyeid, ozelid=:ozelid, katid=:katid, secim=:secim, detaylar=:detaylar, galeri=:galeri, il=:il, ilce=:ilce, nezaman=:nezaman, tarih=:tarih, saat=:saat, talepno = :talepno, durum='Onay Bekliyor' ");
$kaydet->bindValue(':uyeid', $uyeid);
$kaydet->bindValue(':ozelid', $_POST['ozelid']);
$kaydet->bindValue(':katid', $_POST['katid']);
$kaydet->bindValue(':secim', $secim);
$kaydet->bindValue(':detaylar', $_POST['detaylar']);
$kaydet->bindValue(':galeri', $_POST['galeri']);
$kaydet->bindValue(':il', $_POST['il']);
$kaydet->bindValue(':ilce', $_POST['ilce']);
$kaydet->bindValue(':nezaman', $_POST['nezaman']);
$kaydet->bindValue(':tarih', strtotime($_POST['tarih']));
$kaydet->bindValue(':saat', $_POST['saat']);
$kaydet->bindValue(':talepno', $talepno );
$kaydet->execute();

$talepbilgi = $db->query("SELECT * FROM talepler where talepno='".$talepno."' ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);

$alicilar=1;
$siteicimesaj='<a href="/panel/talepyonet.php">Yeni Talep Var</a>';
$smsmesaj="Yeni talep oluşturuldu.";
$epostakonu="Yeni Talep Var #".$talepno;
$epostamesaj="<p>".$isim." tarafından yeni talep oluşturuldu</p>";
$epostamesaj=$epostamesaj.'<div style="max-width: 500px;background: #daffe3;border: 1px solid #ddd;padding: 5px 15px;border-radius: 10px;font-size: 14px;">
<h3>Talep Detayları</h3>
<p><b>Kategori: </b> '.$kat2['baslik'].'</p>';
if(!empty($talepbilgi['secim'])){
$epostamesaj=$epostamesaj.'<p>';
$secimler=json_decode($talepbilgi['secim'],true);
foreach($secimler as $key=>$value){ 
$epostamesaj=$epostamesaj.'<p><b>'.$secimler[$key]['soru'].': </b>';
foreach($secimler[$key]['secenek'] as $key2=>$value2){ $epostamesaj=$epostamesaj.$value2.' '; }
$epostamesaj=$epostamesaj.'</p>';
}
}
$epostamesaj=$epostamesaj."<p><b>Açıklama: </b>".$talepbilgi['detaylar']."</p>";
$epostamesaj=$epostamesaj."<p><b>Bölge: </b>".$talepbilgi['il']."/".$talepbilgi['ilce']."</p>";
$epostamesaj=$epostamesaj."<p><b>Hizmetin istendiği tarih: </b>";
if($talepbilgi['nezaman']=='Belli bir zaman'){
$epostamesaj=$epostamesaj.date('d.m.Y',$talepbilgi['tarih'])." ".$talepbilgi['saat'];
}else{
$epostamesaj=$epostamesaj.$talepbilgi['nezaman'];
}
$epostamesaj=$epostamesaj."</p>";
$epostamesaj=$epostamesaj."</div>";

include "panel/bildirimgonder.php";


echo '<h4 style="font-size:20px;">Talebiniz Başarıyla Kaydedildi</h4>';
echo '<p>Talebiniz onay sonrasında ilgili üyelerimize bildirilecektir.</p>';
echo '<p>Siz şimdi arkanıza yaslanın ve teklifleri bekleyin.</p>';
echo '<p>Talebinizi <a href="/taleplerim"><b>Taleplerim</b></a> sayfasından takip edebilirsiniz.</p>';
}
}}
?>



<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="hizligiris" ){

if($uyeid==''){//1
if(empty($_POST['tel'])){//2 
?>
<label class="mt-1">Telefon Numaranız</label>
<input type="text" class="form-control zorunlu" placeholder="5xxxxxxx" name="tel" >
<?php 
}else if(empty($_POST['sifre']) && empty($_POST['kod'])){//2
$q = $db->prepare("SELECT * FROM uyeler where tel=:tel ");
$q->bindValue(':tel', '0'.substr(addslashes(trim($_POST["tel"])),-10));
$q->execute();
if($q->rowCount()>0){//3
?>
<label class="mt-1">Telefon Numaranız</label>
<input type="text" class="form-control zorunlu" placeholder="5xxxxxxx" name="tel" value="<?=$_POST['tel']?>" >

<label class="mt-1">Şifreniz</label>
<input type="password" class="form-control zorunlu mt-2" placeholder="*******" name="sifre" >
<?php 
}else{//3
$kod=substr(time(),-4);

$smsmesaj='Doğrulama için sms kodu: '.$kod. ' olarak belirlenmiştir.';
$smsnumaralar='0'.substr($_POST['tel'],-10);
include "panel/smsapi.php";

$gkod=($kod*3)+55;
setcookie("kod", $gkod, time()+600);
?>

<label class="mt-1">Telefon Numaranız</label>
<input type="text" class="form-control zorunlu mt-2" placeholder="Telefon Numaranız" name="tel" value="<?=$_POST['tel']?>">

<label class="mt-1">Adınız Soyadınız</label>
<input type="text" class="form-control zorunlu" placeholder="" name="isim">

<label class="mt-1">E-Posta Adresiniz</label>
<input type="email" class="form-control zorunlu mt-2" placeholder="" name="eposta">

<label class="mt-1">Doğrulama Kodu <small>(Sms ile gelen kod)</small></label>
<input type="text" class="form-control zorunlu mt-2" placeholder="" name="kod" >
<?php }//3 ?>

<?php }else{//2 
if(!empty($_POST['sifre'])){//4
$q = $db->prepare("SELECT * FROM uyeler where tel=:tel and sifre=:sifre ");
$q->bindValue(':tel', '0'.substr(addslashes(trim($_POST["tel"])),-10));
$q->bindValue(':sifre', $_POST['sifre']);
$q->execute();
if($q->rowCount()>0){//5
$uyebilgi = $q->fetch(PDO::FETCH_ASSOC);
$uyeid=$uyebilgi['id'];
$_SESSION['uyeid']=$uyeid;
setcookie("uyeid", $uyeid, time()+86400 );
echo "ok";
}else{//5
?>
<label class="mt-1">Telefon Numaranız</label>
<input type="text" class="form-control" placeholder="5xxxxxxx" name="tel" value="<?=$_POST['tel']?>" >

<label class="mt-1">Şifreniz</label>
<input type="password" class="form-control mt-2" placeholder="*******" name="sifre" >
<p class="text-danger text-center">Şifrenizi yanlış girdiniz! Tekrar deneyiniz.</p>
<?php 
}//5
}else if(!empty($_POST['kod'])){//4
if($_POST['kod']==(($_COOKIE["kod"]-55)/3)){//6

$yeni = $db->prepare("INSERT INTO uyeler SET isim = :isim, tel = :tel, eposta = :eposta, il = :il, ilce = :ilce, sifre = :sifre, uyetipi='Müşteri', songiris=:songiris ");
$yeni->bindValue(':isim', addslashes(trim($_POST["isim"])));
$yeni->bindValue(':tel', '0'.substr(addslashes(trim($_POST["tel"])),-10));
$yeni->bindValue(':eposta', addslashes(trim($_POST["eposta"])));
$yeni->bindValue(':il', addslashes(trim($_POST["il"])));
$yeni->bindValue(':ilce', addslashes(trim($_POST["ilce"])));
$yeni->bindValue(':sifre', (($_COOKIE["kod"]-55)/3));
$yeni->bindValue(':songiris', date('Y-m-d H:i:s',time()));
$yeni->execute();
$uyeid = $db->lastInsertId();
$_SESSION['uyeid']=$uyeid;
setcookie("uyeid", $uyeid, time()+(86400*30) );
echo "ok";
}else{//6
?>
<label class="mt-1">Adınız Soyadınız</label>
<input type="text" class="form-control zorunlu" placeholder="" name="isim" value="<?=$_POST['isim']?>">

<label class="mt-1">Telefon Numaranız</label>
<input type="text" class="form-control zorunlu mt-2" placeholder="Telefon Numaranız" name="tel" value="<?=$_POST['tel']?>">

<label class="mt-1">E-Posta Adresiniz</label>
<input type="email" class="form-control zorunlu mt-2" placeholder="" name="eposta" value="<?=$_POST['eposta']?>">

<label class="mt-1">Doğrulama Kodu <small>(Sms ile gelen kod)</small></label>
<input type="text" class="form-control zorunlu mt-2" placeholder="" name="kod" >
<p class="text-danger text-center">Sms kodunu yanlış girdiniz! Lütfen kontrol ederek tekrar deneyiniz.</p>
<?php }//6 ?>
<?php }//4 ?>
<?php }//2 ?>
<?php }//1 ?>
<?php }} ?>









<?php ob_end_flush(); ?>