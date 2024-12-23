<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
$link=$_GET["link"];
$katbak = $db->prepare("SELECT * FROM kategoriler WHERE link=:link");
$katbak->bindValue(':link', $link);
$katbak->execute();
if($katbak->rowCount()){
$katbilgi = $katbak->fetch(PDO::FETCH_ASSOC);
$baslik=$katbilgi['baslik'];
$katlink=$katbilgi['link'];
$katid=$katbilgi['id'];
$ustkatid=$katbilgi['ustkat'];
$resim=$katbilgi['resim'];
$title=$katbilgi['title'];
$description=$katbilgi['description'];
$icerik=$katbilgi['icerik'];

if($resim==''){
$resim='/assets/img/banner.jpg';
}

$il="";
$illink="";
if(isset($_GET["il"])){
$illink=$_GET["il"];
$ilbak = $db->prepare("SELECT * FROM iller WHERE link=:illink");
$ilbak->bindValue(':illink', $illink);
$ilbak->execute();
if($ilbak->rowCount()){
$ilbilgi = $ilbak->fetch(PDO::FETCH_ASSOC);
$il=$ilbilgi['il'];
$illink=$ilbilgi['link'];
}}

$ilce="";
$ilcelink="";
if(isset($_GET["ilce"])){
$ilcelink=$_GET["ilce"];
$ilcebak = $db->prepare("SELECT * FROM ilceler WHERE link=:ilcelink");
$ilcebak->bindValue(':ilcelink', $ilcelink);
$ilcebak->execute();
if($ilcebak->rowCount()){
$ilcebilgi = $ilcebak->fetch(PDO::FETCH_ASSOC);
$ilce=$ilcebilgi['ilce'];
$ilcelink=$ilcebilgi['link'];
}}

?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title><?php if(!empty($title)){ echo $title;}else{ ?><?=$il?> <?=$ilce?> <?=$baslik?> teklif al<?php } ?></title>
      <meta name="description" content="<?php if(!empty($description)){ echo $description;}else{ ?><?=$il?> <?=$ilce?> <?=$baslik?> için teklif almak istiyorsanız <?=$il?> <?=$ilce?> <?=$baslik?> alanında hizmet verenler burada<?php } ?>">
      
<?php include "script.php"; ?>

<style>
.icerik li{
	list-style-type: unset;
}
.icerik ul, .icerik ol {
    padding-left: 20px;
}
</style>

   </head>
   <body>
       
       
      <!-- Header Area Start -->
      <header class="jobguru-header-area stick-top forsticky page-header">
<?php include "menu.php"; ?>
      </header>
      <!-- Header Area End -->


       
      <!-- Single Candidate Start -->
      <section class="single-candidate-page bg-green" style="padding:100px 0;background:url(<?=$resim?>) no-repeat center center;background-size: cover;">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
					<div style="padding:40px;border:3px solid #090;background:#fff;max-width:600px;opacity:0.9">
                        <?php if($ustkatid>0){ ?>
						<h4 style="color:#000"><b><?=$il?> <?=$ilce?> <?=$baslik?> için <span class="d-inline-block">Fiyat Teklifleri Al</span></b></h4>
						<div class="row mt-3">
							<div class="col-md-7"><p class="font-weight-bold">Fiyatları Karşılaştır, Teklifi Onayla</p></div>
							<div class="col-md-5"><button type="button" class="btn btn-success font-weight-bold katsec" data-katid="<?=$katid?>" data-baslik="<?=$baslik?>">HEMEN TEKLİF AL</button></div>
						</div>
						<?php }else{ ?>
						<h4 style="color:#000"><b><?=$il?> <?=$ilce?> <?=$baslik?> Hizmetleri</b></h4>
						<?php } ?>
						
					</div>
               </div>
            </div>
         </div>
      </section>
      <!-- Single Candidate End -->
      <!-- Single Candidate Bottom Start -->
      <section class="single-candidate-bottom-area section_30">
         <div class="container">


<?php if($ustkatid=='0'){ ?><!-- Üst Kategori ise -->

						<h3><b>Popüler <?=$il?> <?=$ilce?> <?=$baslik?> Hizmetleri</b></h3>
                        <div class="row mt-5">
<?php
$rs2 = $db->prepare("SELECT * FROM kategoriler where ustkat=".$katid." order by hit desc, sira asc ");
$rs2->execute();
while($row2=$rs2->fetch(PDO::FETCH_ASSOC)) {
$uyesayisi=$db->query("SELECT count(*) FROM hizmetler where katid=".$row2['id']." ")->fetchColumn();
$talepsayisi=$db->query("SELECT count(*) FROM talepler where katid=".$row2['id']." ")->fetchColumn();
$yorumsayisi=$db->query("SELECT count(*) FROM talepler where katid=".$row2['id']." and yorumonay=1 ")->fetchColumn();
?>
               <div class="col-md-3 mt-4">
					<div class="card katlar" style="display:block">
					<a href="/teklif-al/<?=$row2['link']?>">
						<img src="<?=$row2['resim']?>" onerror="this.onerror=null;this.src='/assets/img/blog2.jpg';" class="img-fluid" alt="<?=$row2['baslik']?>">
						<div class="katikonlink">
						<p><i class="fa fa-users"></i> <?=$uyesayisi?> Üye</p>
						<p><i class="fa fa-pencil-square-o"></i> <?=$talepsayisi?> Talep</p>
						<p><i class="fa fa-comment-o"></i> <?=$yorumsayisi?> Değerlendirme</p>
						</div>
					</a>
					<div class="card-body">
						<p class="text-center font-weight-bold"><a href="/teklif-al/<?=$row2['link']?>"><?=$row2['baslik']?></a></p>
						<p class="text-center mt-3"><button type="button" class="btn btn-success btn-xs btn-block katsec" data-katid="<?=$row2['id']?>" data-baslik="<?=$row2['baslik']?>">TEKLİF AL</button></p>
					</div>
					</div>
               </div>
<?php } ?>
						</div>
<div class="mt-5 icerik"><?=$icerik?></div>



<?php }else{ ?><!-- Üst Kategori değil ise -->



<?php
$query = $db->prepare("SELECT * FROM uyeler where id in (SELECT uyeid FROM hizmetler where durum='Yayında' and katid=:katid and (bolge like :il or bolge like :ilce or bolge like '%Tüm Şehirler%')) order by id desc limit 10");
$query->bindValue(':katid', $katid);
$query->bindValue(':il', '%'.$il.'/%');
$query->bindValue(':ilce', '%'.$il.'/'.$ilce.'%');
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay){ ?>
<p><a href="/"><i class="fa fa-home"></i></a> <i class="fa fa-angle-double-right"></i> <a href="/teklif-al/<?=$katlink?>"><?=$baslik?></a><?php if($il!==''){?> <i class="fa fa-angle-double-right"></i> <a href="/teklif-al/<?=$illink?>/<?=$katlink?>"><?=$il?></a><?php if($ilce!==''){?> <i class="fa fa-angle-double-right"></i> <a href="/teklif-al/<?=$illink?>/<?=$ilcelink?>/<?=$katlink?>"><?=$ilce?></a><?php } ?><?php } ?></p>
<div class="row mt-3">
	<div class="col-md-8">
<h6 class="mb-4"><b>En iyi 10 <?=$il?> <?=$ilce?> <?=$baslik?> Hizmeti Veren Üyeler</b></h6>

<div class="pb-5">
<?php
while($rs=$query->fetch(PDO::FETCH_ASSOC)) {
$row = $db->query("SELECT * FROM hizmetler where uyeid=".$rs['id']." and katid=".$katid." ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);
?>

				<div class="mt-3 p-3" style="border:1px solid #ddd;background: white;">
					<div class="row" >
						<div class="col-md-3 mb-3" >
							<img src="<?=$row['kapak']?>" onerror="this.src='/assets/resimler/noimage.jpg'" alt="<?=$row['baslik']?>">
						</div>
                        <div class="col-md-9 mb-3" >
								<h3><a href="/profil/<?=$row['link']?>"><?=$row['baslik']?></a></h3>
                                <p><?=$kat1['baslik']?> / <?=$kat2['baslik']?></p>
                                <p class="company-state detaylar"> <span style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow:hidden;"><?=strip_tags($row['detaylar'])?></span> </p>
                                <p class="company-state"><i class="fa fa-money"></i> <span style="display:inline-block;min-width:200px;"> <b>Fiyatlandırma Aralığı :</b></span>  <span style="display:inline-block">₺ <?=$row['enaz']?> - ₺ <?=$row['encok']?></span></p>
                                <p class="company-state"><i class="fa fa-map-marker"></i> <span style="display:inline-block;min-width:200px;"> <b>Hizmet verebilceği yerler :</b></span> <span style="display:inline-block"><?=str_replace(","," ",$row['bolge'])?></span></p>
                                <p class="company-state"><i class="fa fa-calendar"></i> <span style="display:inline-block;min-width:200px;"> <b>Oluşturma :</b></span> <span style="display:inline-block"><?php echo date('d.m.Y',strtotime($row['olusturma'])); ?></span></p>
                        </div>
                    </div>
				</div>

<?php } ?>
</div>
<?php } ?>

<div class="mt-5 icerik"><?=$icerik?></div>

	</div>
	<div class="col-md-4">

<?php
$query = $db->prepare("SELECT * FROM talepler where yorumonay=1 and katid=".$katid." order by id desc limit 12");
$query->execute();
$verisay = $query->rowCount();
if($verisay){
?>
<h4 class="text-center mb-3"><b>Başlıca Yorumlar</b></h4>
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$baktim = $db->query("SELECT * FROM uyeler where id=".$row['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']."  ")->fetch(PDO::FETCH_ASSOC);
?>
<div class="card mt-2">
<div class="card-body">
<p class="text-center font-weight-bold"><i class="fa fa-user"></i> <?=gizle($baktim['isim'])?></p>
<p class="text-center"><small><?=$kat2['baslik']?> - <?=date("d/m/Y",strtotime($row['yorumtarihi']))?></small></p>
<p class="text-center"><i class="fa fa-star<?php echo $row['puan']>0 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>1 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>2 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>3 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>4 ? '':'-o';?>"></i> </p>
<p class="text-center"><?=$row['yorum']?></p>
</div>
</div>
<?php } ?>

	</div>
</div>
<?php } ?>




<?php } ?><!-- Üst Kategori ise Bitişi-->


         </div>
      </section>
      <!-- Single Candidate Bottom End -->



<?php include "alt.php"; ?>


<script>
$(".katsec").click(function(){
$("#teklifal").modal("show");
$("#katsonuc,#kat").val($(this).attr("data-baslik"));
$("#katsonuc,#kat").prop('readonly', true);
$("#katsonuc,#katmansetsonuc").html("");
$("#detaylar").show("slow").focus();
$("#butce").show("slow");
$("#gorseller").show("slow");
});
</script>



<?php } ?>