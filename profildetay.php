<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
$hbak = $db->prepare("SELECT * FROM hizmetler where link=:link order by id asc");
$hbak->bindValue(':link', $_GET['link']);
$hbak->execute();
$verisay = $hbak->rowCount();
if($verisay){ 
$hbilgi=$hbak->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$hbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);
if(!isset($kat2['id'])){
$db->query("DELETE FROM hizmetler where link='".$_GET['link']."' ")->fetch(PDO::FETCH_ASSOC);
header("location: /?error=Kategori silinmiş!");
exit();
}
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);
if(!isset($kat1['id'])){
$db->query("DELETE FROM hizmetler where link='".$_GET['link']."' ")->fetch(PDO::FETCH_ASSOC);
header("location: /?error=Kategori silinmiş!");
exit();
}

$katlink=$kat2['link'];
$baslik=$kat2['baslik'];

$kisiid=$hbilgi['uyeid'];
$kisibak = $db->prepare("SELECT * FROM uyeler WHERE id=:kisiid ");
$kisibak->bindValue(':kisiid', $kisiid);
$kisibak->execute();
if($kisibak->rowCount()){
$kisibilgi = $kisibak->fetch(PDO::FETCH_ASSOC);
$kisiid=$kisibilgi['id'];
$kisi=$kisibilgi['isim'];
$resim=$kisibilgi['resim'];
$kisitel=$kisibilgi['tel'];
$il=$kisibilgi['il'];
$ilce=$kisibilgi['ilce'];
$kisipuan=$kisibilgi['puan'];

$illink=seflink($il);
$ilcelink=seflink($ilce);

$teklifsay = $db->query("SELECT count(id) FROM teklifler where uyeid=".$kisiid." ")->fetchColumn();
$kteklifsay = $db->query("SELECT count(id) FROM teklifler where uyeid=".$kisiid." and durum='Başarılı' ")->fetchColumn();

}
?>



<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Title -->

      <title> <?=$hbilgi['baslik']?> - <?=$kat2['baslik']?> </title>
      <meta name="description" content="<?=$kat2['baslik']?> - <?=$kat1['baslik']?> ">
      <meta name="keyword" content="<?=$kat2['baslik']?>,<?=$kat1['baslik']?>">
      
<?php include "script.php"; ?>

<style>
.profilkutusu{
	background: #ffffff;
    padding: 10px;
    border-radius: 5px;
	text-align:center;
}
.profilkutusu img{
	border-radius: 5px;
	width:100%;
}
.profilkutusu h3{
	margin:10px auto;
	text-align:center;
	color:#555;
	font-size:24px;
}
.profilkutusu p{
	margin:5px auto;
	text-align:center;
	color:#555;
	font-size:16px;
}
</style>

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
                           <li><a href="/" class="fa fa-home"></a></li>
                           <li><a href="/teklif-al/<?=$katlink?>"><?=$baslik?></a></li>
						   <?php if($il!==''){?>
						   <li><a href="/teklif-al/<?=$illink?>/<?=$katlink?>"><?=$il?></a> </li>
						   <?php if($ilce!==''){?>
						   <li><a href="/teklif-al/<?=$illink?>/<?=$ilcelink?>/<?=$katlink?>"><?=$ilce?></a></li>
						   <?php } ?>
						   <?php } ?>
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
            <div class="row">
               <div class="col-md-3">
			   <div class="profilkutusu">
			   <img src="<?=$resim?>" onerror="this.src='/assets/resimler/noperson.png'" class="img-responsive">
			   <h3><?=$kisi?></h3>
			   <p><?=$il?></p>
			   
			   <p class="<?php echo $kisipuan>0 ? '':'d-none';?>"><i class="fa fa-star<?php echo $kisipuan>0 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>1 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>2 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>3 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>4 ? '':'-o';?>"></i></p>
			   
			   <p class="<?php echo $kteklifsay>0 ? '':'d-none';?>"><?=$kteklifsay?>/<?=$teklifsay?> Teklif Başarısı</p>
			   <?php if($kisiid!==$uyeid){ ?>
			   <p><button type="button" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#uyeteklifal" > Hemen Teklif Al </button></p>
			   <?php } ?>
			


<!-- Modal -->
<div class="modal fade" style="z-index:999999;" id="uyeteklifal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header" style="display:block;padding:0px;box-shadow: 0px 3px 5px #ddd;">
        <p class="modal-title font-weight-bold p-2 text-center">Başka Teklifler de Almak İster misin? <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></p>
		</div>
		<div class="modal-body">
		<div class="row">
			<div class="col-sm-6 mb-4">
				<button type="button" class="btn btn-warning btn-sm btn-block katsec" data-katid="<?=$kat2['id']?>" data-baslik="<?=$kat2['baslik']?>" >Evet, Herkesten Teklif Topla </button>
				<p>Talebinizle ilgilenecek tüm üyelerimizden gelen teklifleri size ulaştırırız.</p>
			</div>
			<div class="col-sm-6 mb-4">
				<button type="button" class="btn btn-success btn-sm btn-block katsec" data-katid="<?=$kat2['id']?>" data-baslik="<?=$kat2['baslik']?>" data-ozelid="<?=$kisiid?>" > Hayır, Sadece 1 Teklif Al </button>
				<p>Talebinizi sadece <?=$kisi?> görür ve teklif verebilir. </p>
			</div>
		</div>
		</div>
    </div>
  </div>
</div>




			   </div>
			   
			   </div>
               <div class="col-md-9">
                  <div class="dashboard-right">

                     <div class="candidate-list-page">
                        <div class="single-candidate-list" >
                           <div class="main-comment">
                              <div class="candidate-text">
                                 <div class="candidate-info">
                                    <div class="candidate-title">
                                       <h3><?=$hbilgi['baslik']?><small><i><br><?=$kat1['baslik']?> / <?=$kat2['baslik']?></i></small></h3>
                                    </div>
                                    
                                 </div>
								 <p class="company-state"> <?=nl2br($hbilgi['detaylar'])?> </p>

<?php
$query = $db->prepare("SELECT * FROM galeri where dosyano='".$hbilgi['galeri']."' order by id asc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay !== "0"){ ?>
<div class="row">
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<div class="col-sm-2 text-center">
<a href="<?=$row['adres']?>" class="example-image-link" data-lightbox="example-set" data-title="Görseller"  ><img src="<?=$row['adres']?>" style="margin:10px;"></a>
</div>
<?php } ?>
</div>
<?php } ?>

<p class="font-weight-bold mt-4 mb-2">Hizmet Verdiği Bölgeler</p>
<p>
<?php
$bolgeler=explode(",",$hbilgi['bolge']);
foreach($bolgeler as $bolge){
if(!empty(trim($bolge))){
$bolge=str_replace("/*","",$bolge);
?>
<span class="badge badge-info" style="font-size:12px"><?=$bolge?></span>
<?php }} ?>
</p>



                              </div>
                           </div>
                        </div>
                        <!-- End Single Candidate List -->

<br><br>

<?php
$query = $db->prepare("SELECT * FROM hizmetler where uyeid=:kisiid and durum='Yayında' and id<>:id order by id asc");
$query->bindValue(':id', $hbilgi['id']);
$query->bindValue(':kisiid', $kisiid);
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay){ ?>

                        <div class="manage-jobs-heading">
                           <h3>Diğer Hizmet İlanları </h3>
                        </div>

<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
                        <div class="single-candidate-list" >
                           <div class="main-comment">
                              <div class="candidate-text">
                                 <div class="candidate-info">
                                    <div class="candidate-title">
                                       <h3><a href="/profil/<?=$row['link']?>"><?=$row['baslik']?></a></h3>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- End Single Candidate List -->

<?php } ?>

<?php } ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Candidate Dashboard Area End -->
       
       

<?php include "alt.php"; ?>



<link rel="stylesheet" href="/panel/bower_components/lightbox/css/lightbox.css">
<script src="/panel/bower_components/lightbox/js/lightbox.js"></script>


   </body>
</html>

<?php } ?>
