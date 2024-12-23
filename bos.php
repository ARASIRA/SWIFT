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
      <title>Üye Ol</title>
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
         <div class="breadcromb-top section_100">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="breadcromb-box">
                        <h3>Üye Ol</h3>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="breadcromb-bottom">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="breadcromb-box-pagin">
                        <ul>
                           <li><a href="/">Anasayfa</a></li>
                           <li class="active-breadcromb"><a href="#">Üye Ol</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Breadcromb Area End -->
       
       
      <!-- Login Area Start -->
      <section class="jobguru-login-area section_70">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="login-box">
                     <div class="login-title">
                        <h3>Üye Ol</h3>
                     </div>
                        <form id="form">
                        <div class="single-login-field">
                           <input type="text" placeholder="Adınız">
                        </div>
                        <div class="single-login-field">
                           <input type="email" placeholder="E-Posta Adresiniz">
                        </div>
                        <div class="single-login-field">
                           <input type="password" placeholder="Şifre Belirleyin">
                        </div>
                        <div class="single-login-field">
                           <input type="password" placeholder="Şifre Tekrar">
                        </div>
                        <div class="remember-row single-login-field clearfix">
                           <p class="checkbox remember">
                              <input class="checkbox-spin" type="checkbox" id="Freelance">
                              <label for="Freelance"><span></span>Şartları kabul ediyorum.</label>
                           </p>
                        </div>
                        <div class="single-login-field">
                           <button type="submit">Kayıt Ol</button>
                        </div>
                     </form>
                     <div class="dont_have">
                        <a href="girisyap.php">Zaten Üyeyim</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Login Area End -->
       

<?php include "alt.php"; ?>


   </body>
</html>

