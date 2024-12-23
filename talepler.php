<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";
?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Teklif Bekleyen İşler</title>
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
                           <li class="active-breadcromb"><a href="#">Teklif Bekleyen İşler</a></li>
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
				<?php include "menuprofil.php"; ?>
                <div class="col-lg-9 col-md-12">
                  <div class="dashboard-right">
                     <div class="manage-jobs">
                        <div class="manage-jobs-heading">
                           <h3>Teklif Bekleyen İşler</h3>
                        </div>
                        <div class="">


<?php
$isvarmi="";
$query = $db->prepare("SELECT * FROM talepler where durum='Teklifler Bekleniyor' order by id desc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay == "0"){ ?>

<?php }else{ ?>
                           <table class="table esnektablo istablosu">
                              <thead>
                                 <tr>
									<th>Talepler</th>
									<th style="width:150px;">Durum</th>  
									<th style="width:130px">Yayın Tarihi</th>
									<th style="width:130px"></th>
									</tr>
                              </thead>
                              <tbody>
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$teklifbak = $db->query("SELECT count(id) FROM teklifler where talepno=".$row['talepno']." and uyeid=".$uyeid." ")->fetchColumn();

$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);

$rs = $db->prepare("SELECT * FROM hizmetler where uyeid = :uyeid and katid = :katid order by id asc");
$rs->bindValue(':uyeid', $uyeid);
$rs->bindValue(':katid', $row['katid']);
$rs->execute();
$rssay = $rs->rowCount();
if($rssay){
$isvarmi="evet";
?>
<tr>
<td style="font-size:15px;">
<a href="/talep/<?=$row['talepno']?>"><b><?=$row['talepno']?></b> <?=$kat2['baslik']?></a>
<br>
<small><b><?=$row['ilce']?>/<?=$row['il']?></b></small>
</td>
<td>
<a href="/talep/<?=$row['talepno']?>">
<span class="<?php echo $row['durum']=='İş Verildi' ? 'pending':'expired';?>"> <?=$row['durum']?></span><?php if($teklifbak>0){echo '<i class="fa fa-check text-success"></i>';}?>
</a>
</td>
<td><?php echo date('d.m.Y',strtotime($row['olusturma']));?></td>
<td><a href="/talep/<?=$row['talepno']?>"><div class="btn btn-default btn-sm btn-block">Teklif Verin <span class="fa fa-chevron-right"></span></div></a></td>
</tr>
<?php }} ?>
                              </tbody>
                           </table>
<?php } ?>

<?php 
if($isvarmi==''){?>
<div class="alert alert-warning">Şu an teklif bekleyen size uygun bir iş yok. Olduğunda biz size bildireceğiz.</div>
<script>$(".istablosu").hide();</script>
<?php } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Candidate Dashboard Area End -->
       
       

<?php include "alt.php"; ?>


   </body>
</html>

