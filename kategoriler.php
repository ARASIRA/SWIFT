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
      <title>Kategoriler</title>
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
                           <li><a href="/"><i class="fa fa-home"></i></a></li>
                           <li class="active-breadcromb"><a href="/kategoriler">Kategoriler</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Breadcromb Area End -->
       
	  <section class="jobguru-categories-area browse-category-page section_30">
         <div class="container">
            <div class="row">
<?php foreach($db->query("SELECT * FROM kategoriler where ustkat=0 order by sira asc") as $row1) { ?>
                     <div class="col-md-3 mb-5">
<div class="list-group">
  <a href="/teklif-al/<?=$row1['link']?>" class="list-group-item list-group-item-action active" style="background-color: #8bc34a;border-color: #4CAF50;"><?=$row1['baslik']?></a>
<?php foreach($db->query("SELECT * FROM kategoriler where ustkat=".$row1['id']." order by sira asc") as $row2) { ?>
	<a class="list-group-item list-group-item-action" href="/teklif-al/<?=$row2['link']?>" ><i class="fa fa-arrow-circle-o-right"></i> <?=$row2['baslik']?></a>
<?php } ?>
</div>

                     </div>
<?php } ?>
            </div>
         </div>
      </section>

<?php include "alt.php"; ?>



</body>
</html>

