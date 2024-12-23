<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";
?>


<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="havaleyukle" ){

$miktar=$_POST['miktar'];

$siparisno=$uyeid.time();
$aciklama="Banka Havalesi ile Bakiye Yükleme (".$_POST['banka'].")";
$yeni = $db->prepare("INSERT INTO ucretler SET uyeid = :uyeid, aciklama = :aciklama, miktar = :miktar, siparisno=:siparisno, durum='0' ");
$yeni->bindValue(':uyeid', $uyeid);
$yeni->bindValue(':aciklama', $aciklama);
$yeni->bindValue(':miktar', $miktar);
$yeni->bindValue(':siparisno', $siparisno);
$yeni->execute();

$kisiid="1";
$mesaj="<a href=\"/panel/\">Havale bildirimi yapıldı.</a>";
$bildir = $db->prepare("INSERT INTO bildirimler (uyeid, mesaj) VALUES ('".$kisiid."','".$mesaj."') ");
$bildir->execute();

header("Location: ?success=Havale bildirimi yapıldı.");
}}
?>



<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Bakiyem</title>
      <meta name="description" content="">
      <meta name="keyword" content="">
      <meta name="author" content="yahyaaydin.com.tr">

<?php include "script.php"; ?>

<style>
.tab-pane p, .tab-pane input, .tab-pane button{margin-top:10px;}
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
                           <li class="active-breadcromb"><a href="#">Bakiye İşlemleri</a></li>
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
                           <h3 style="line-height:40px;">Bakiye İşlemleri <button type="button" class="btn btn-success btn-sm pull-right togglemenu" id="bakiyeyukle">Bakiye Yükle</button></h3>
                        </div>
						
						
<div class="bakiyeyukle" style="margin:30px 0 60px 0;display:none">
<hr>
<div role="tabpanel">
  <!-- Nav sekmeleri -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="kredi <?php if(strpos($odemeyontemi,'Kredi')===false){?>hide<?php }else{?> active<?php }?>"><a class="btn btn-default" href="#kredi" aria-controls="kredi" role="tab" data-toggle="tab">Kredi Kartı</a></li>
    <li role="presentation" class="havale <?php if(strpos($odemeyontemi,'Havale')===false){?>hide<?php }?> <?php if(strpos($odemeyontemi,'Kredi')===false && strpos($odemeyontemi,'Havale')!==false ){?>active<?php }?>"><a class="btn btn-default" href="#havale" aria-controls="havale" role="tab" data-toggle="tab">Banka Havalesi</a></li>
  </ul>
  <!-- Sekme çerçeveleri -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane <?php if(strpos($odemeyontemi,'Kredi')!==false){?>active<?php }?>" id="kredi">
	
<form action="/paytr" method="post">
<input type="hidden" name="aciklama" value="Kredi Kartı ile Bakiye Yükleme" required>
<p><b>Kredi Kartı ile Bakiye Yükle</b></p>
<div class="row">
    <div class="col-md-4 col-sm-8 col-xs-8">
	<input type="number" min="5" max="10000" step="1" class="form-control text-right" name="tutar" placeholder="0" >
	</div>
	<div class="col-md-3 col-sm-4 col-xs-4">
	<button type="submit" class="btn btn-success btn-block" > İleri </button>
	</div>
</div>
</form>

</div>
    <div role="tabpanel" class="tab-pane <?php if(strpos($odemeyontemi,'Kredi')===false && strpos($odemeyontemi,'Havale')!==false){?>active<?php }?>" id="havale">
	
	<form action="?islem=havaleyukle" method="post">
<input type="hidden" name="aciklama" value="Banka Havalesi ile Bakiye Yükleme" required>
<p><b>Banka Havalesi ile Bakiye Yükle</b></p>

<p class="mb-2">Aşağıdaki banka hesabına para yatırdıktan sonra banka seçimi yapıp, yatırdığınız tutarı girerek Ödeme Bildirimi yapınız.</p>


<?php
$query = $db->prepare("SELECT * FROM bankahesaplari order by id asc ");
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<label class="alert alert-info">
<input type="radio" name="banka" value="<?=$row['banka'];?>" required> 
<b><?=$row['isim'];?></b> - <?=$row['banka'];?>
<br>
<b><?=$row['iban'];?></b>
</label>
<?php } ?>

<div class="row">
	<div class="col-md-12">Yatırdığınız Tutar (TL)</div>
    <div class="col-md-6 col-sm-6 col-6">
		<input type="number" min="1" max="100000" step="1" class="form-control text-right" name="miktar" placeholder="0" required>
	</div>
	<div class="col-md-3 col-sm-6 col-6">
	<button type="submit" class="btn btn-success btn-block" > Ödeme Bildirimi </button>
	</div>
</div>
</form>
	
	
	</div>
  </div>
</div>
<script>
$('.nav-tabs a').click(function (e) {
  $('.nav-tabs li').removeClass("active");
  aktiftab = $(this).attr('aria-controls');
  $('.nav-tabs .'+aktiftab).addClass("active");
});
</script>

<br><br><br><br>
<hr>
</div>


<?php
$bak = $db->prepare("SELECT * FROM teklifler where uyeid=:uyeid");
$bak->bindValue(':uyeid',$uyeid);
$bak->execute();
$verisay = $bak->rowCount();

$bakt = $db->prepare("SELECT sum(teklif) as toplam FROM teklifler where uyeid=:uyeid and durum='Başarılı' ");
$bakt->bindValue(':uyeid',$uyeid);
$bakt->execute();
$row = $bakt->fetch(PDO::FETCH_ASSOC);
if($row['toplam']){
$toplam=$row['toplam'];
}else{
$toplam=0;
}
?>



					<div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12">
                           <div class="widget_card_page grid_flex  widget_bg_purple">
                              <div class="widget-icon">
                                 <i class="fa fa-money"></i>
                              </div>
                              <div class="widget-page-text">
                                 <h4>₺ <?=$uyebakiye?></h4>
                                 <h2>Bakiyem</h2>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                           <div class="widget_card_page grid_flex widget_bg_blue">
                              <div class="widget-icon">
                                 <i class="fa fa-gavel"></i>
                              </div>
                              <div class="widget-page-text">
                                 <h4><?=$verisay?> Teklif</h4>
                                 <h2>Tekliflerim</h2>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12">
                           <div class="widget_card_page grid_flex widget_bg_dark_red">
                              <div class="widget-icon">
                                 <i class="fa fa-line-chart"></i>
                              </div>
                              <div class="widget-page-text">
                                 <h4>₺ <?=$toplam?></h4>
                                 <h2>Kazancım</h2>
                              </div>
                           </div>
                        </div>
                     </div>





<?php
$query = $db->prepare("SELECT * FROM ucretler where uyeid=$uyeid order by id desc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay == "0"){ ?>
<div class="alert alert-info"  style="margin-top:30px" >Bakiyenizle ilgili işlemleriniz olduğunda buradan takip edebilirsiniz.</div>
<?php }else{ ?>
                           <table class="table esnektablo" style="margin-top:30px">
                              <thead>
                                 <tr>
                                    <th>Sipariş No</th>
                                    <th>Açıklama</th>
                                    <th>Miktar</th>
                                    <th>Tarih</th>
									<th>Durum</th>
									
                                 </tr>
                              </thead>
                              <tbody>
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
                                 <tr>
                                    <td><?=$row['siparisno']?></td>
                                    <td><?=$row['aciklama']?></td>
                                    <td><?=number_format($row['miktar'],2)?></td>
                                    <td><?php echo date('d.m.Y H:i',strtotime($row['tarih']));?></td>
                                    <td><span class="<?php echo $row['durum']=='1' ? 'pending':'expired';?>"><?php echo $row['durum']=='1' ? 'Onaylandı':'Bekliyor';?></span></td>
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
      </section>
      <!-- Candidate Dashboard Area End -->
       
       

<?php include "alt.php"; ?>


   </body>
</html>

