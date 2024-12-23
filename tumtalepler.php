<?php
include "panel/db.php";
include "kontrol.php";




if(isset($_GET['katid'])){
$katid=$_GET['katid'];
}else{
$katid='0';
}

if(isset($_GET['durum'])){
$durum=$_GET['durum'];
}else{
$durum='Teklifler Bekleniyor';
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
      <title>Tüm Talepler</title>
      <meta name="description" content="">
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
                           <li class="active-breadcromb"><a href="#">Tüm Talepler</a></li>
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
                     <div class="manage-jobs">
                        <div class="manage-jobs-heading">
                           <h3>Tüm Talepler</h3>
                        </div>
                        
<form>
<div class="row">

<div class="col-md-3 col-sm-4">
<div class="form-group">
<label>Kategoriler</label>
<select class="form-control" name="katid">
<option value="0">Tüm Talepler</option>
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
<label>Durum</label>
<select class="form-control" name="durum">
<option value="0">Tümü</option>
<option value="Teklifler Bekleniyor" <?php echo $durum=='Teklifler Bekleniyor' ? 'selected':'';?>>Teklifler Bekleniyor</option>
<option value="İş Verildi" <?php echo $durum=='İş Verildi' ? 'selected':'';?>>İş Verildi</option>
<option value="İş Tamamlandı" <?php echo $durum=='İş Tamamlandı' ? 'selected':'';?>>İş Tamamlandı</option>
</select>
</div>
</div>

<div class="col-md-2 col-sm-4">
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

<div class="col-md-2 col-sm-4 ilce" style="display:none">
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
$isvarmi="";
$q = $db->prepare("SELECT * FROM talepler where (katid=:katid or '".$katid."'='0') and (durum=:durum or '".$durum."'='0') and (durum='Teklifler Bekleniyor' or durum='İş Verildi' or durum='İş Tamamlandı') and il like :il and (ilce like :ilce or '".$ilce."'='*') order by id desc");
$q->bindValue(':katid', $katid);
$q->bindValue(':durum', $durum);
$q->bindValue(':il', '%'.$il.'%');
$q->bindValue(':ilce', '%'.$ilce.'%');
$q->execute();
$toplamveri = $q->rowCount();
?>
<?php if($toplamveri == "0"){ ?>
<div class="alert alert-info">Talep Bulunamadı...</div>
<?php }else{ ?>
<div class="row">
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

$query = $db->prepare("SELECT * FROM talepler where (katid=:katid or '".$katid."'='0') and (durum=:durum or '".$durum."'='0') and (durum='Teklifler Bekleniyor' or durum='İş Verildi' or durum='İş Tamamlandı') and il like :il and (ilce like :ilce or '".$ilce."'='*') order by id desc limit $limit, $goster");
$query->bindValue(':katid', $katid);
$query->bindValue(':durum', $durum);
$query->bindValue(':il', '%'.$il.'%');
$query->bindValue(':ilce', '%'.$ilce.'%');
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$kat='';
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
if(!isset($kat2['id'])){
$db->query("DELETE FROM talepler where id=".$row['id']." ")->fetch(PDO::FETCH_ASSOC);
}else{
$kat=$kat2['baslik'];
$isvarmi='evet';
?>
<div class="col-md-6">
<div class="talepkutu">
<div class="row">
<div class="col-8">
<a class="mr-3" href="/talep/<?=$row['talepno']?>" style="font-size:15px;"><b><?=$kat?></b></a> <span class="text-muted"><b>#<?=$row['talepno']?></b></span> 
<br>
<span class="text-muted mr-3"><i class="fa fa-calendar"></i> <?php echo date('d.m.Y',strtotime($row['olusturma']));?></span>  <span class="text-muted d-inline-block"><i class="fa fa-map-marker"></i> <?=$row['ilce']?>/<?=$row['il']?></span>
</div><div class="col-4 text-right">
<a href="/talep/<?=$row['talepno']?>" class="btn btn-success btn-sm pt-3 pb-3"><?php echo $row['durum']=='Teklifler Bekleniyor' ? 'Teklif Ver':'İncele';?> <span class="fa fa-chevron-right"></span></a>
</div>
</div>
</div>
</div>
<?php }} ?>
</div>
						</div>

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
<li class="page-item"><a class="" href="?katid=<?=$katid?>&durum=<?=$durum?>&s=<?=$i?>"><?=$i?></a></li>
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

<?php 
if($isvarmi=='' && !isset($_GET)){?>
<div class="alert alert-warning">Şu an teklif bekleyen bir iş yok. Olduğunda biz size bildireceğiz.</div>
<script>$(".istablosu").hide();</script>
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

