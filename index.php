<?php
include "panel/db.php";
include "kontrol.php";
?>


<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title><?=$siteadi?></title>
      <meta name="description" content="<?=$description?>">
      <meta name="author" content="<?=$siteurl?>">
      
<?php include "script.php"; ?>

<style>
@media (min-width: 0px) and (max-width: 992px) {
.hvbuton{text-align:center;}
}

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
      <header class="jobguru-header-area stick-top forsticky">
<?php include "menu.php"; ?>
      </header>
      <!-- Header Area End -->
       
       
      <!--Home Video Start-->
      <section class="home-video-banner">
		<video autoplay loop id="video-background" style="position: absolute;right: 0;bottom: 0;min-width: 100%;min-height: 100%;width: auto;height: auto;z-index: 0;}" muted plays-inline>
		<source src="/assets/resimler/slider.mp4" type="video/mp4">
		</video>
		<div class="banner-area">
            <div class="banner-caption container">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12 col-sm-12 content-home">
                        <div class="banner-welcome">
                           <h4><span>Hizmeti Seç</span> <p style="display:inline-block;color:#fff">Hemen Teklif Al</p></h4>
                           <div class="mansetform">
                              <div class="video-banner-input">
<?php 
$altkatlar = $db->query("SELECT baslik FROM kategoriler where ustkat>0 order by hit desc, sira asc ")->fetchAll(PDO::FETCH_COLUMN, 0); 
//print_r(json_encode($altkatlar,JSON_UNESCAPED_UNICODE));
?>
                                 <input type="text" id="katmanset" placeholder="Aradığınız hizmet nedir?" autocomplete="off" class="typewrite" data-period="2000" data-type='<?=json_encode($altkatlar,JSON_UNESCAPED_UNICODE)?>'>
								 <div id="katmansetsonuc"></div>
                              </div>
                              
                              <div class="video-banner-input katmansetbuton d-none">
                                 <button type="button" data-toggle="modal" data-target="#teklifal"><i class="fa fa-search"></i></button>
                              </div>
                           </div>
                           <div class="top-search-cat">
                              <p>Popüler Aramalar :</p>

<?php
$rs1 = $db->prepare("SELECT * FROM kategoriler where ustkat>0 order by hit desc, sira asc limit 3");
$rs1->execute();
while($row1=$rs1->fetch(PDO::FETCH_ASSOC)) {
?>
    <a href="#" class="katsec" data-katid="<?=$row1['id']?>" data-baslik="<?=$row1['baslik']?>"><?=$row1['baslik']?></a>
<?php } ?>
                              
                           </div>
						   <p class="mt-5 hvbuton"><a href="/hizmetekle" class="btn btn-success">Hizmet Vermek İstiyorum</a></p>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!--Home Video End-->
       

<?php
$q = $db->prepare("SELECT * FROM talepler WHERE durum='Teklifler Bekleniyor' order by id desc limit 12");
$q->execute();
if($q->rowCount()){
?>
      <!-- Video Area Start -->
      <section class="jobguru-blog-area" style="background:#eee;">
         <div class="container pt-5 pb-5">
			<h2 class="text-center pb-3">Teklif Bekleyen Talepler</h2>
<div class="row">
<?php 
while($row=$q->fetch(PDO::FETCH_ASSOC)) { 
$kat='';
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
if(!isset($kat2['id'])){
$db->query("DELETE FROM talepler where id=".$row['id']." ")->fetch(PDO::FETCH_ASSOC);
}else{
$kat=$kat2['baslik'];
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
<p class="text-right mt-3"><a href="/tumtalepler" class="btn btn-success btn-sm">Tüm Talepler</a></p>
         </div>
      </section>
      <!-- Video Area End -->
<?php } ?>




<section class="how-works-area section_30">
         <div class="container">
            <div class="row">
               <div class="col-md-4">
                  <div class="how-works-box box-1">
                     <img src="/assets/resimler/arrow-right-top.png" alt="works">
                     <div class="works-box-icon">
                        <i class="fa fa-user"></i>
                     </div>
                     <div class="works-box-text">
                        <p>Teklifleri Topla</p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="how-works-box box-2">
                     <img src="assets/resimler/arrow-right-bottom.png" alt="works">
                     <div class="works-box-icon">
                        <i class="fa fa-gavel"></i>
                     </div>
                     <div class="works-box-text">
                        <p>Teklifleri Değerlendir</p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="how-works-box box-3">
                     <div class="works-box-icon">
                        <i class="fa fa-thumbs-up"></i>
                     </div>
                     <div class="works-box-text">
                        <p>Uygun Teklife Karar Ver</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>




      <!-- Video Area Start -->
      <section class="jobguru-blog-area" style="background:#eee;">
         <div class="container pt-3 pb-3">
			<h2 class="text-center pb-3">Popüler Kategoriler</h2>
            <div class="row">
<?php
$rs2 = $db->prepare("SELECT * FROM kategoriler where ustkat>0 and resim<>'' order by hit desc, sira asc limit 8");
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
         </div>
      </section>
      <!-- Video Area End -->



	  <section class="jobguru-categories-area browse-category-page section_70">
         <div class="container">
		 <h2 class="text-center pb-5">Başlıca Kategoriler</h2>
            <div class="row">
<?php foreach($db->query("SELECT * FROM kategoriler where ustkat=0 order by sira asc limit 8") as $row1) { ?>
                     <div class="col-md-3 mb-5">
<div class="list-group">
  <a href="/teklif-al/<?=$row1['link']?>" class="list-group-item list-group-item-action active" style="background-color: #8bc34a;border-color: #4CAF50;"><?=$row1['baslik']?></a>
<?php foreach($db->query("SELECT * FROM kategoriler where ustkat=".$row1['id']." order by sira asc limit 3") as $row2) { ?>
	<a class="list-group-item list-group-item-action" href="/teklif-al/<?=$row2['link']?>" ><i class="fa fa-arrow-circle-o-right"></i> <?=$row2['baslik']?></a>
<?php } ?>
</div>
                     </div>
<?php } ?>
            </div>
			<p class="text-right mt-3"><a href="/kategoriler" class="btn btn-success btn-sm">Tüm Kategoriler</a></p>
         </div>
      </section>



      <!-- Video Area Start -->
      <section class="jobguru-video-area section_100">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="video-container">
                     <h2>İhtiyaçlarınız için zamanınızı <br> ve enerjinizi boşa harcamayın.</h2>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Video Area End -->







<?php
$query = $db->prepare("SELECT * FROM talepler where yorumonay=1 order by id desc limit 12");
$query->execute();
$verisay = $query->rowCount();
if($verisay){
?>
      <!-- Happy Freelancer Start -->
      <section class="jobguru-happy-freelancer-area section_70">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="site-heading">
                     <h2> Hesabını bilen <span> Müşteri Yorumları</span></h2>
                     <p>Sizden gelen değerlendirmeler, hepimiz için çok değerli</p>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                  <div class="happy-freelancer-slider owl-carousel">
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
$baktim = $db->query("SELECT * FROM uyeler where id=".$row['uyeid']." ")->fetch(PDO::FETCH_ASSOC);
$baktim2 = $db->query("SELECT * FROM uyeler where id=".$row['kazananid']." ")->fetch(PDO::FETCH_ASSOC);
$baktim22 = $db->query("SELECT * FROM hizmetler where uyeid=".$row['kazananid']." and katid=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
$kat2 = $db->query("SELECT * FROM kategoriler where id=".$row['katid']."  ")->fetch(PDO::FETCH_ASSOC);
?>
                     <div class="single-happy-freelancer">
                        <div class="happy-freelancer-img">
                           <img src="<?=$baktim['resim']?>" onerror="this.src='/assets/resimler/noperson.png'" alt="<?=$baktim['isim']?>" />
                        </div>
                        <div class="happy-freelancer-text">
                           <p>
						   <span class="text-muted"><i class="fa fa-star<?php echo $row['puan']>0 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>1 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>2 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>3 ? '':'-o';?>"></i><i class="fa fa-star<?php echo $row['puan']>4 ? '':'-o';?>"></i> </span>
						   <br><?=$row['yorum']?>
						   </p>
                           <div class="happy-freelancer-info">
                              <h4><?=gizle($baktim['isim'])?> <i class="fa fa-chevron-right"></i> <a href="<?php if(!empty($baktim22['link'])){ ?>/profil/<?=$baktim22['link']?><?php } ?>"><?=$baktim2['isim']?></a></h4>
                              <p class="mt-2"><?=$kat2['baslik']?></p>
                           </div>
                        </div>
                     </div>
<?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Happy Freelancer End -->
<?php } ?>







<?php
$query = $db->prepare("SELECT * FROM sayfalar where durum='Evet' and etiket like '%blog%' order by id desc limit 3");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay){ ?>
      <!-- Blog Area Start -->
      <section class="jobguru-blog-area section_70">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="site-heading">
                     <h2>Son Yazılar</h2>
                     <p></p>
                  </div>
               </div>
            </div>
            <div class="row">
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
               <div class="col-lg-4 col-md-12">
                  <a href="/blog/<?=$row['link']?>">
                     <div class="single-blog">
                        <div class="blog-image">
                           <img src="<?=$row['kapak']?>" alt="blog image" />
                        </div>
                        <div class="blog-text">
                           <h3><?=$row['baslik']?></h3>
                        </div>
                     </div>
                  </a>
               </div>
<?php } ?>
            </div>
			<p class="text-right mt-3"><a href="/blog" class="btn btn-success btn-sm">Tüm Yazılar</a></p>
         </div>
      </section>
      <!-- Blog Area End -->
<?php } ?>


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


<script>
var TxtType = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 10) || 1000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    };

    TxtType.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];

        if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.placeholder = this.txt;

        var that = this;
        var delta = 200 - Math.random() * 100;

        if (this.isDeleting) { delta /= 2; }

        if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 200;
        }

        setTimeout(function() {
        that.tick();
        }, delta);
    };

    window.onload = function() {
        var elements = document.getElementsByClassName('typewrite');
        for (var i=0; i<elements.length; i++) {
            var toRotate = elements[i].getAttribute('data-type');
            var period = elements[i].getAttribute('data-period');
            if (toRotate) {
              new TxtType(elements[i], JSON.parse(toRotate), period);
            }
        }
        // INJECT CSS
        var css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #fff}";
        document.body.appendChild(css);
    };
</script>


</body>
</html>

