<?php
include "panel/db.php";
include "kontrol.php";




if(isset($_GET['katid'])){
$katid=$_GET['katid'];
}else{
$katid='0';
}

if(isset($_GET['il'])){
$il=$_GET['il'];
}else{
$il='';
}
if(isset($_GET['ilce'])){
$ilce=$_GET['ilce'];
}else{
$ilce='';
}
?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Hizmet İlanları</title>
      <meta name="description" content="">
      <meta name="keyword" content="">
      <meta name="author" content="yahyaaydin.com.tr">


<?php include "script.php"; ?>

<style>
.talepkutu{
    margin-top: 10px;
    padding: 10px;
    border: 3px solid #8BC34A;
    border-radius: 10px;
    background: #f5ffe9;
}

.detaylar{
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical; 
    overflow:hidden;
	margin-bottom:20px;
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
                           <li><a href="/">Anasayfa</a></li>
                           <li class="active-breadcromb"><a href="#">Hizmet İlanları</a></li>
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
				<div class="col-md-12">
                  <div class="dashboard-right">
                     <div class="manage-jobs" style="background: #f7f7f7;">
                        <div class="manage-jobs-heading">
                           <h3>Hizmet İlanları</h3>
                        </div>
                        
<form>
<div class="row">

<div class="col-md-3 col-sm-4">
<div class="form-group">
<label>Kategoriler</label>
<select class="form-control" name="katid">
<option value="0">Tüm Kategoriler</option>
<?php foreach($db->query("SELECT * FROM kategoriler where ustkat=0 order by sira asc") as $rs1) { ?>
<optgroup label="<?=$rs1['baslik']?>">
<?php foreach($db->query("SELECT * FROM kategoriler where ustkat=".$rs1['id']." order by sira asc") as $rs2) { ?>
<option value="<?=$rs2['id']?>" <?php echo $katid==$rs2['id'] ? 'selected':'';?> ><?=$rs2['baslik']?></option>
<?php } ?>
</optgroup>
<?php } ?>
</select>
</div>
</div>

<div class="col-md-3 col-sm-4">
<div class="form-group">
<label>İl Seçimi</label>
<select class="form-control" id="il" name="il">
<option value=""> Tüm Şehirler </option>
<?php
$query = $db->prepare("SELECT * FROM iller order by il asc ");
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<option value="<?=$row['il'];?>" > <?=$row['il'];?> </option>
<?php } ?>
</select>
</div>
</div>

<div class="col-md-3 col-sm-4 ilce" style="display:none">
<div class="form-group">
<label>İlçe Seçimi</label>
<select class="form-control" id="ilce" name="ilce">
<option value=""> Tüm İlçeler </option>
</select>
</div>
</div>

<div class="col-md-2 col-sm-4 text-center">
	<button type="submit" class="btn btn-secondary" style="margin-top: 33px;">Filtrele</button>
</div>
</div>
</form>




<?php
$q = $db->prepare("SELECT * FROM hizmetler where (katid=:katid or '".$katid."'='0') and (bolge like :bolge1 or bolge like :bolge2 or bolge like '%Tüm Şehirler/*%') order by id desc");
$q->bindValue(':katid', $katid);
$q->bindValue(':bolge1', '%'.$il.'/%');
$q->bindValue(':bolge2', '%'.$il.'/'.$ilce.'%');
$q->execute();
$toplamveri = $q->rowCount();
?>
<?php if($toplamveri == "0"){ ?>
<div class="alert alert-warning">
Henüz bir hizmet profili oluşturulmadı!
</div>
<?php }else{ ?>

<?php
$goster=20;
$toplamSayfa = ceil($toplamveri / $goster);

if(!empty($_GET["s"])){
$sayfa = $_GET["s"];
}else{
$sayfa = 1;
}
if($sayfa < 1){ 
$sayfa = 1;
}
if($sayfa > $toplamSayfa){
$sayfa = $toplamSayfa;
}
$limit = ($sayfa - 1) * $goster;

$query = $db->prepare("SELECT * FROM hizmetler where (katid=:katid or '".$katid."'='0') and (bolge like :bolge1 or bolge like :bolge2 or bolge like '%Tüm Şehirler/*%') order by IF(kapak='', 0, 1) desc, id desc limit $limit, $goster");
$query->bindValue(':katid', $katid);
$query->bindValue(':bolge1', '%'.$il.'/%');
$query->bindValue(':bolge2', '%'.$il.'/'.$ilce.'%');
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$kisipuan=0;
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
if(isset($kat2['id'])){
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);
if(isset($kat1['id'])){
$kisibilgi = $db->query("SELECT * FROM uyeler where id=".$row['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
if(isset($kisibilgi['id'])){

$kisipuan=$kisibilgi['puan'];
$teklifsay = $db->query("SELECT count(id) FROM teklifler where uyeid=".$row['uyeid']." ")->fetchColumn();
$kteklifsay = $db->query("SELECT count(id) FROM teklifler where uyeid=".$row['uyeid']." and durum='Başarılı' ")->fetchColumn();
?>
				<div class="mt-3 p-3" style="border:1px solid #ddd;background: #ffffff;">
					<div class="row" >
						<div class="col-md-3 mb-3" >
							<img src="<?=$row['kapak']?>" onerror="this.src='/assets/resimler/noimage.jpg'" alt="<?=$row['baslik']?>">
							<p class="mt-2 mb-0 text-muted text-center <?php echo $kteklifsay>0 ? '':'d-none';?>"><?=$kteklifsay?>/<?=$teklifsay?> Teklif Başarısı</p>
							<p class="mt-1 mb-2 text-center <?php echo $kisipuan>0 ? '':'d-none';?>">
							<i class="fa fa-star<?php echo $kisipuan>0 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>1 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>2 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>3 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $kisipuan>4 ? '':'-o';?>"></i>
							 <a class="text-muted" href="/profil/<?=$row['link']?>">Yorumlar</a>
							</p>
							<p><a class="btn btn-success btn-block" href="/profil/<?=$row['link']?>">Hemen Teklif Al</a></p>
							
						</div>
                        <div class="col-md-9 mb-3" >
								<h3><a href="/profil/<?=$row['link']?>"><?=$row['baslik']?></a></h3>
                                <p><?=$kat1['baslik']?> / <?=$kat2['baslik']?></p>
                                <p class="company-state detaylar"> <span style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow:hidden;"><?=strip_tags($row['detaylar'])?></span> </p>
                                <p class="company-state"><i class="fa fa-money"></i> <span style="display:inline-block;min-width:200px;"> <b>Fiyatlandırma Aralığı :</b></span>  <span style="display:inline-block">₺ <?=$row['enaz']?> - ₺ <?=$row['encok']?></span></p>
                                <p class="company-state"><i class="fa fa-map-marker"></i> <span style="display:inline-block;min-width:200px;"> <b>Hizmet verebilceği yerler :</b></span> <span style="display:inline-block"><?=str_replace(","," ",$row['bolge'])?></span></p>
                                
                        </div>
                    </div>
				</div>
<?php }}}} ?>

<ul class="pagination justify-content-center">
<?php 
$nokta=0;
for($i = 1; $i<=$toplamSayfa;$i++){
?>
<?php
if($i=='1' || ($i>=$sayfa-3 && $i<=$sayfa+3) || $i==$toplamSayfa ){
$nokta=0;
?>
<?php 
if($i==$sayfa){
?>
<li class="page-item active" ><a class="" href="#"><?=$i?></a></li>
<?php 
}else{ 
?>
<li class="page-item"><a class="" href="?katid=<?=$katid?>&il=<?=$il?>&ilce=<?=$ilce?>&s=<?=$i?>"><?=$i?></a></li>
<?php } ?>
<?php 
}else{
if($nokta===0){
$nokta=1;
?>
<li class="page-item"><a class="" href="#">...</a></li>
<?php }}} ?>
</ul>
<?php } ?>
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Candidate Dashboard Area End -->
       
       

<?php include "alt.php"; ?>


<script type="text/javascript">
$(function(){
$('#il').on('change', function () {
	var il = $('#il option:selected').val();
    $.post("/hizmetekleilceler.php", {il: il}, function (cevap) {
        $("#ilce").html(cevap);
		$(".ilce").show();
		
    });
});

<?php if(!empty($il)){ ?>
$("#il").val("<?=$il?>");
$.post("/hizmetekleilceler.php", {il: '<?=$il?>'}, function (cevap) {
        $("#ilce").html(cevap);
		$("#ilce").val("<?=$ilce?>");
		$(".ilce").show();
});
<?php } ?>

});
</script>

   </body>
</html>

