<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";

if(isset($_POST['paketid'])){

$rs = $db->prepare("SELECT * FROM paketler where id=:id ");
$rs->bindValue(':id', $_POST['paketid']);
$rs->execute();
$rssay = $rs->rowCount();
if($rssay){ 
$row = $rs->fetch(PDO::FETCH_ASSOC);
$paketucreti=$row['ucret'];

if($uyebakiye<$paketucreti && $paketucreti>0){
$fark=number_format(($paketucreti - $uyebakiye), 2, ',', '.');
header("Location: ?error=Bakiyeniz Yetersiz. Bu paketi satın alabilmek için en az ".$fark." TL bakiye yüklemelisiniz!");
exit();
}else{

if($paketucreti>0){
$song1 = $db->prepare("UPDATE uyeler SET bakiye = bakiye - '".$paketucreti."' where id=:uyeid ");
$song1->bindValue(':uyeid', $uyeid);
$song1->execute();
}else{
$rs1 = $db->query("SELECT count(id) FROM ucretler where uyeid=".$uyeid." and miktar=0 ")->fetchColumn();
if($rs1>0){
header("Location: ?error=Ücretsiz paketten sadece bir kez yararlanabilirsiniz!");
exit();
}
}

$siparisno=$uyeid.time();
$miktar=0-$paketucreti;
$aciklama=$_POST['aciklama'];
$para = $db->prepare("INSERT INTO ucretler SET uyeid = :uyeid, siparisno = :siparisno, miktar = :miktar, aciklama = :aciklama, paketid=:paketid, durum=1 ");
$para->bindValue(':uyeid', $uyeid);
$para->bindValue(':siparisno', $siparisno);
$para->bindValue(':miktar', $miktar);
$para->bindValue(':aciklama', $aciklama);
$para->bindValue(':paketid', $_POST['paketid']);
$para->execute();

header("Location: ?success=Paket hesabınıza tanımlandı.");
exit();
}
}}
?>



<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Paketler</title>
      <meta name="description" content="">
      <meta name="author" content="yahyaaydin.com.tr">

<?php include "script.php"; ?>


   </head>
   <body>
       
       
      <!-- Header Area Start -->
      <header class="jobguru-header-area stick-top forsticky page-header">
<?php include "menu.php"; ?>
      </header>
      <!-- Header Area End -->
       
       
       
      <!-- Breadcromb Area Start -->
      <section class="jobguru-breadcromb-area">
         <div class="breadcromb-bottom">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="breadcromb-box-pagin">
                        <ul>
                           <li><a href="/">Anasayfa</a></li>
                           <li class="active-breadcromb">Paketler</li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Breadcromb Area End -->
    
      <!-- Candidate Dashboard Area Start -->
      <section class="candidate-dashboard-area section_30">
         <div class="container">

<?php
$query = $db->prepare("SELECT * FROM paketler");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay == "0"){ ?>
<div class="alert alert-warning">Kayıt Yok!</div>
<?php }else{ ?>
<div class="row">
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<div class="col-md-4 mb-3" data-id="<?=$row['id']?>">
<ul class="list-group">
<li class="list-group-item text-center active"><b><?=$row['baslik']?></b><br><?=$row['altbaslik']?></li>
<?php
foreach($db->query("SELECT * FROM paketozellikleri order by id asc") as $ozb) {
?>
<li class="list-group-item text-center list-group-item-<?php echo strpos($row['ozellikler'],$ozb['ozellik'])===False ? 'danger':'success';?>"><i class="fa <?php echo strpos($row['ozellikler'],$ozb['ozellik'])===False ? 'fa-close':'fa-check';?>"></i> <?=$ozb['ozellik']?></li>
<?php }?>
<li class="list-group-item text-center list-group-item-info" style="font-size:24px;font-weight:700;"><?=$row['ucret']?> TL / <?php if($row['sure']>1 && $row['sure']<12){ echo $row['sure'].' Ay';}else if($row['sure']=='1'){echo 'Ay';}else if($row['sure']=='12'){echo 'Yıl';}?></li>
<li class="list-group-item active text-center">
<?php if($paketid!==$row['id']){?>
<form action="?" method="post">
<input type="hidden" name="paketid" value="<?=$row['id']?>">
<input type="hidden" name="tutar" value="<?=$row['ucret']?>">
<input type="hidden" name="aciklama" value="<?=$row['baslik']?> - Paket Ücreti">
<button type="submit" class="btn btn-primary"> SATIN AL </button>
</form>
<?php }else{ ?> 
<button type="button" class="btn btn-default btn-primary disabled" >HESABINIZDA TANIMLI</button>
<?php } ?> 
</li>
</ul>
</div>
<?php } ?>

</div>

<?php } ?>









        </div>
    </section>
 <!-- SAYFA BİTİR -->

 
 
 <?php include "alt.php"; ?>
 
 
 


 
 
</body>
</html>