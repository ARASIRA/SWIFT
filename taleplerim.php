<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";
?>

<?php
if( isset($_GET['silid']) ){
$query = $db->prepare("DELETE FROM talepler WHERE uyeid=".$uyeid." and talepno = :talepno");
$delete = $query->execute(array(
'talepno' => $_GET['silid']
));


$query = $db->prepare("DELETE FROM teklifler WHERE uyeid=".$uyeid." and talepno = :talepno");
$delete = $query->execute(array(
'talepno' => $_GET['silid']
));

foreach($db->query("SELECT * FROM galeri where dosyano='".$_GET['silid']."' ") as $row) {
unlink($_SERVER['DOCUMENT_ROOT'] . $row['adres']);
}

header("Location: ?success=Talebiniz isteğiniz üzere kaldırıldı.");
}
?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Taleplerim</title>
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
                           <li class="active-breadcromb"><a href="#">Taleplerim</a></li>
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
                           <h3>Taleplerim</h3>
                        </div>
                        <div class="">


<?php
$query = $db->prepare("SELECT * FROM talepler where uyeid=$uyeid order by id desc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay == "0"){ ?>
<div class="alert alert-warning"><p>Henüz talep oluşturmadınız!</p></div>
<?php }else{ ?>
                           <table class="table esnektablo">
                              <thead>
                                 <tr>
                                    <th>Talepler</th>
                                    <th>Ne Zaman</th>
                                    <th>Oluşturma</th>
                                    <th>Durum</th>
									<th></th>
									
                                 </tr>
                              </thead>
                              <tbody>
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$baktim = $db->query("SELECT * FROM uyeler where id=".$row['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
?>
                                 <tr>
                                    <td><a href="/talep/<?=$row['talepno']?>"><b><?=$row['talepno']?></b> <?=$kat2['baslik']?></a></td>
									<td><?php if($row['nezaman']=='Belli bir zaman'){ ?> <?php echo date('d.m.Y',$row['tarih']);?> <?=$row['saat']?> <?php }else{?> <?=$row['nezaman']?> <?php } ?></td>
                                    <td><?php echo date('d.m.Y H:i',strtotime($row['olusturma']));?></td>
                                    <td><span class="<?php echo $row['durum']=='İş Verildi' ? 'pending':'expired';?>"><?=$row['durum']?></span></td>
									<td class="text-center"><?php if($row['durum']=='Onay Bekliyor' || $row['durum']=='Teklifler Bekleniyor'){ ?><a class="btn btn-danger btn-sm" href="?silid=<?=$row['talepno']?>" onclick="return confirm('Silmek istiyor musunuz?')"><i class="fa fa-trash-o"></i> Vazgeç</a><?php } ?></td>
                                 </tr>
<?php } ?>
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

