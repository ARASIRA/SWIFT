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
      <title>Tekliflerim</title>
      <meta name="description" content="">
      <meta name="keyword" content="">
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
                           <li class="active-breadcromb"><a href="#">Tekliflerim</a></li>
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
                           <h3>Tekliflerim</h3>
                        </div>
                        <div class="">

<?php
$query = $db->prepare("SELECT * FROM teklifler where uyeid=$uyeid order by id desc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay == "0"){ ?>
<div class="alert alert-warning">Henüz bir talep için teklif vermediniz!</div>
<?php }else{ ?>
                           <table class="table esnektablo">
                              <thead>
                                 <tr>
                                    <th>Talep</th>
                                    <th>Tarih</th>
                                    <th>Teklifim</th>
                                    <th>Durum</th>
									
                                 </tr>
                              </thead>
                              <tbody>
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$talepbak = $db->prepare("SELECT * FROM talepler WHERE talepno=:talepno");
$talepbak->bindValue(':talepno', $row['talepno']);
$talepbak->execute();
if($talepbak->rowCount()){
$talepbilgi = $talepbak->fetch(PDO::FETCH_ASSOC);

$baktim = $db->query("SELECT * FROM uyeler where id=".$talepbilgi['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$talepbilgi['katid']." ")->fetch(PDO::FETCH_ASSOC);
?>
                                 <tr>
                                    <td><a href="/talep/<?=$talepbilgi['talepno']?>"><b><?=$talepbilgi['talepno']?></b> <?=$kat2['baslik']?></a></td>
                                    <td><?php if($talepbilgi['nezaman']=='Belli bir zaman (3 hafta içinde)'){ ?> <?php echo date('d.m.Y',$talepbilgi['tarih']);?> <?=$talepbilgi['saat']?> <?php }else{?> <?=$talepbilgi['nezaman']?> <?php } ?></td>
                                    <td>₺ <?=$row['teklif']?></td>
                                    <td><span class="<?php echo $row['durum']=='Başarılı' ? 'pending':'expired';?>"><?=$row['durum']?></span></td>
									
                                 </tr>
<?php }} ?>
                              </tbody>
                           </table>
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

