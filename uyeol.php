<?php
include "panel/db.php";
include "kontrol.php";
?>


<?php
if(empty($_GET['hedef'])){
    $geldigiadres='/';
}else{
    $geldigiadres=$_GET['hedef'];
}
?>

<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

      <!-- Title -->
      <title>Üye Ol</title>
      <meta name="robots" content="noindex,nofollow">
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
      <section class="jobguru-login-area section_30">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="login-box">
                     <div class="login-title">
                        <h3>Üye Ol</h3>
                     </div>
                     <form id="form">
                        <div class="single-login-field">
                           <input type="text" class="zorunlu" placeholder="Adınız ve Soyadınız" name="isim" id="isim">
                           <div class="error-msg" id="isim-error" style="color:red;font-size:14px;"></div>
                        </div>
                        <div class="single-login-field">
                           <input type="email" class="zorunlu" placeholder="E-Posta Adresiniz" name="eposta" id="eposta">
                           <div class="error-msg" id="eposta-error" style="color:red;font-size:14px;"></div>
                        </div>
                        <div class="single-login-field">
                           <input type="tel" class="zorunlu" placeholder="Telefon Numaranız (Doğrulama kodu gönderilecek)" name="tel" id="tel">
                           <div class="error-msg" id="tel-error" style="color:red;font-size:14px;"></div>
                        </div>
                        <div class="single-login-field sifre" style="display:none">
                           <p>Telefonunuza gelen sms kodunu giriniz.</p>
                           <input type="text" placeholder="Sms Kodu" name="sifre" id="sifre">
                        </div>
                        
                        <div class="remember-row single-login-field clearfix">
                           <p class="checkbox remember">
                              <input class="checkbox-spin" type="checkbox" id="sartlar" name="sartlar" value="1">
                              <label for="sartlar"><span></span>Üyelik Şartlarını kabul ediyorum. <a href="/sayfa/uyelik-sartlari">İncele</a></label>
                           </p>
                        </div>
                        <p class="uyari"></p>
                        <div class="single-login-field">
                           <button type="button" id="girisyap">Kayıt Ol</button>
                        </div>
                     </form>
                     <div class="mt-4">
                        <a class="btn btn-outline-success btn-sm" href="/giris?hedef=<?=$geldigiadres?>">Zaten Üyeyim</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Login Area End -->

      <?php include "alt.php"; ?>

      <script type='text/javascript'>
      $(function(){
          $("#girisyap").click(function() { 
              // Önce hata alanlarını temizleyelim
              $(".error-msg").html("");
              $(".input-hata").removeClass("input-hata");

              var bosluk = '';
              var isim = $("#isim").val().trim();
              var eposta = $("#eposta").val().trim();
              var tel = $("#tel").val().trim();

              // Boş alan kontrolü
              $(".zorunlu").each(function() {
                  if($(this).val().trim() == ''){
                      bosluk='bos';
                      $(this).addClass("input-hata");
                  }
              });

              if(bosluk == 'bos'){
                  // Her bir alan boşsa kendi altına uyarı bastıralım
                  if(isim == ''){
                      $("#isim-error").html("Lütfen ad soyad giriniz.");
                  }
                  if(eposta == ''){
                      $("#eposta-error").html("Lütfen e-posta adresinizi giriniz.");
                  }
                  if(tel == ''){
                      $("#tel-error").html("Lütfen telefon numaranızı giriniz.");
                  }
                  return false;
              }

              // Ad Soyad kontrolü (en az iki kelime, her kelime en az 3 harf ve sayı yasak)
              var isimParcalari = isim.split(" ");
              if(isimParcalari.length < 2){
                  $("#isim-error").html("Lütfen ad soyad giriniz!");
                  $("#isim").addClass("input-hata");
                  return false;
              }

              var sadeceHarfRegex = /^[A-Za-zÇĞİÖŞÜçğıöşü]+$/;
              for(var i=0; i<isimParcalari.length; i++){
                  var kelime = isimParcalari[i].trim();
                  if(kelime.length < 3){
                      $("#isim-error").html("Geçersiz ad soyad");
                      $("#isim").addClass("input-hata");
                      return false;
                  }
                  if(!sadeceHarfRegex.test(kelime)){
                      $("#isim-error").html("Ad Soyad alanında sadece harf kullanılmalıdır. Rakam veya geçersiz karakter tespit edildi!");
                      $("#isim").addClass("input-hata");
                      return false;
                  }
              }

              // E-Posta format kontrolü
              var emailPattern = /^[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/i;
              if(!emailPattern.test(eposta)){
                  $("#eposta-error").html("Lütfen geçerli bir e-posta adresi giriniz!");
                  $("#eposta").addClass("input-hata");
                  return false;
              }

              // Telefon format kontrolü (Sadece rakam 10-15 hane)
              var phonePattern = /^[0-9]{10,15}$/;
              if(!phonePattern.test(tel)){
                  $("#tel-error").html("Lütfen geçerli bir telefon numarası giriniz!");
                  $("#tel").addClass("input-hata");
                  return false;
              }

              // Üyelik şartları onayı kontrolü
              if(!$("#sartlar").is(':checked')){
                  $(".uyari").html("Lütfen üyelik şartlarını kabul ediniz.");
                  return false;
              }

              // Ajax isteği
              $.ajax({
                  type: "POST",
                  url: "/uyeislem.php?islem=uyeol",             
                  data: $('#form').serialize(),                
                  success: function(gelenmesaj){
                      if(gelenmesaj.trim().indexOf("giriniz")>0){
                          $("#sifre").addClass("zorunlu");
                          $(".sifre").show("slow");
                      } else if(gelenmesaj.trim().indexOf("yeid")>0){
                          location.href = "/?"+gelenmesaj;
                      } else {
                          // Sunucu tarafında dönen bir hata mesajı varsa genel uyarı alanında göster
                          $('.uyari').html(gelenmesaj.trim());
                      }
                  }
              });
          });
      });
      </script>

      <script>
      $(".girisbutonu").hide();
      </script>

   </body>
</html>
