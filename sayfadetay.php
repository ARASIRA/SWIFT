<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
$link=$_GET["link"];
$sbak = $db->prepare("SELECT * FROM sayfalar WHERE link=:link ");
$sbak->bindValue(':link', $link);
$sbak->execute();
if($sbak->rowCount()){
$sbilgi = $sbak->fetch(PDO::FETCH_ASSOC);
$id=$sbilgi['id'];

$song = $db->prepare("UPDATE sayfalar SET hit = hit + 1 where id=$id ");
$song->execute();
?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title><?php echo $sbilgi['baslik'];?></title>
      <meta name="description" content="<?php echo $sbilgi['ozet'];?>">
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

      <!-- Single Candidate Bottom Start -->
      <section class="single-candidate-bottom-area section_30" style="margin-top: 60px;">
         <div class="container">
            <div class="row">
               <div class="col-md-8">
                  <div class="single-candidate-bottom-left">
                     <div class="single-candidate-widget" style="background:#fff;padding:20px;">
<h3><?=$sbilgi['baslik'];?></h3>
<?php if($sbilgi['kapak']!==''){?> <img src="<?=$sbilgi['kapak']?>" style="margin:10px;"><?php } ?>

<div><?=$sbilgi['icerik'];?></div>

<?php
$query = $db->prepare("SELECT * FROM galeri where dosyano='".$sbilgi['galeri']."' and adres<>'".$sbilgi['kapak']."' order by id asc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay !== "0"){ ?>
<div class="row">
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<div class="col-sm-6 text-center">
<a href="<?=$row['adres']?>" class="example-image-link" data-lightbox="example-set" data-title="Görseller"  ><img src="<?=$row['adres']?>" style="margin:10px;"></a>
</div>
<?php } ?>
</div>
<?php } ?>



                     </div>
                  </div>
               </div>
			   <div class="col-md-4">
					<div class="single-candidate-bottom-left">
                      <div class="single-candidate-widget" style="background:#fff;padding:20px;">
                        <h3>Diğer Yazılar</h3>
<?php
$query = $db->prepare("SELECT * FROM sayfalar where etiket like '%sayfa%' and link<>'".$sbilgi['link']."' order by id desc");
$query->execute();
$verisay = $query->rowCount();
if($verisay){
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<p><a href="/sayfa/<?=$row['link']?>"><i class="fa fa-arrow-right"></i> <?=$row['baslik']?></a></p>

<?php }} ?>
					  </div>
					</div>
			   </div>
            </div>
         </div>
      </section>
      <!-- Single Candidate Bottom End -->



<?php include "alt.php"; ?>



<link rel="stylesheet" href="/panel/bower_components/lightbox/css/lightbox.css">
<script src="/panel/bower_components/lightbox/js/lightbox.js"></script>





</body>
</html>

<?php } ?>