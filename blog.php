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
      <title>Blog</title>
      <meta name="description" content="Blog">
      <meta name="author" content="<?=$siteurl?>">
	  <meta name="robots" content="index,follow">

<?php include "script.php"; ?>


   </head>
   <body>
       
       
      <!-- Header Area Start -->
      <header class="jobguru-header-area stick-top forsticky page-header">
<?php include "menu.php"; ?>
      </header>
      <!-- Header Area End -->


<?php
$query = $db->prepare("SELECT * FROM sayfalar where durum='Evet' and etiket like '%blog%' order by id desc");
$query->execute();
$verisay = $query->rowCount();
?>
<?php if($verisay){ ?>
      <!-- Blog Area Start -->
      <section class="single-candidate-bottom-area section_30" style="margin-top: 60px;">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="site-heading">
                     <h2>Tüm Yazılar</h2>
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
         </div>
      </section>
      <!-- Blog Area End -->



<?php include "alt.php"; ?>







</body>
</html>

<?php } ?>