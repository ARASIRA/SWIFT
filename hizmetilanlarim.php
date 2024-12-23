<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";
?>

<?php
if( isset($_GET['silid']) ){
$query = $db->prepare("DELETE FROM hizmetler WHERE uyeid=$uyeid and id = :silid");
$delete = $query->execute(array(
'silid' => $_GET['silid']
));
header("Location: hizmetilanlarim");
}
?>


<?php
if( isset($_GET['yayinlaid']) ){
$query = $db->prepare("UPDATE hizmetler SET durum='Yayında' WHERE id = :yayinlaid");
$delete = $query->execute(array(
'yayinlaid' => $_GET['yayinlaid']
));

header("Location: hizmetilanlarim");
}
?>

<?php
if( isset($_GET['durdurid']) ){
$query = $db->prepare("UPDATE hizmetler SET durum='Durduruldu' WHERE id = :durdurid");
$delete = $query->execute(array(
'durdurid' => $_GET['durdurid']
));

header("Location: hizmetilanlarim");
}
?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Hizmet İlanlarım</title>
      <meta name="description" content="">
      <meta name="keyword" content="">
      
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
                           <li class="active-breadcromb"><a href="#">Hizmet İlanlarım</a></li>
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
               <div class="col-md-12 col-lg-9">
                  <div class="dashboard-right">
                     <div class="manage-jobs manage-candidates">
                        <div class="manage-jobs-heading">
                           <h3>Hizmet İlanlarım <a class="btn btn-success btn-sm pull-right" href="/hizmetekle.php"><i class="fa fa-plus"></i></a></h3>
                        </div>
                     </div>
                     <div class="candidate-list-page">


<?php
$query = $db->prepare("SELECT * FROM hizmetler where uyeid=".$uyeid." order by id asc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay == "0"){ ?>
<div class="alert alert-warning">
Henüz hizmet profili oluşturmadınız!<br> 
İş Fırsatlarından haberdar olmak ve teklifler verebilmek için hemen bir hizmet profili oluşturun.<br>
<p class="text-center"><a class="btn btn-success" href="/hizmetekle.php"><i class="fa fa-plus"></i> Hizmet Profili Oluştur</a></p>
</div>
<?php }else{ ?>

<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
if(isset($kat2['id'])){
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$kat2['ustkat']." ")->fetch(PDO::FETCH_ASSOC);
?>

                        <div class="single-candidate-list" <?php if($row['durum']=='Durduruldu'){ ?>style="background:#fee"<?php } ?> >
                           <div class="main-comment">
                              <div class="candidate-text">
                                 <div class="candidate-info">
                                    <div class="candidate-title">
                                       <h3><a href="/profil/<?=$row['link']?>"><?=$row['baslik']?></a></h3>
                                    </div>
                                    <p><?=$kat1['baslik']?> / <?=$kat2['baslik']?></p>
                                 </div>
								 <p class="company-state"><i class="fa fa-money"></i> ₺ <?=$row['enaz']?> - ₺ <?=$row['encok']?></p>
                                 <p class="company-state"><i class="fa fa-map-marker"></i> <?=str_replace(","," ",$row['bolge'])?></p>
                                 <p class="company-state"><i class="fa fa-calendar"></i> <?php echo date('d.m.Y H:i',strtotime($row['olusturma'])); ?></p>
                                 <div class="candidate-text-bottom">
                                    <div class="candidate-text-box">
                                       <b><?=$row['durum']?></b>
                                    </div>
                                    <div class="candidate-action">
										<a href="/hizmetguncelle.php?id=<?=$row['id']?>" class="btn btn-warning">Güncelle</a>
									<?php if($row['durum']=='Durduruldu'){ ?>
                                       <a href="/hizmetilanlarim.php?yayinlaid=<?=$row['id']?>" class="btn btn-danger">Pasif</a>
									<?php }else if($row['durum']=='Yayında'){ ?>
                                       <a href="/hizmetilanlarim.php?durdurid=<?=$row['id']?>" class="btn btn-success">Aktif</a>
									<?php } ?>
                                    </div>
                                 </div>
                                 <div class="remove-icon">
                                    <a href="/hizmetilanlarim.php?silid=<?=$row['id']?>" onclick="return confirm('Silmek istiyor musunuz?')"><i class="fa fa-times"></i></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- End Single Candidate List -->

<?php }} ?>

<?php } ?>


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

