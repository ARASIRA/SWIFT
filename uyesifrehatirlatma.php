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
      <title>Şifre Hatırlatma</title>
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
                           <li class="active-breadcromb"><a href="#">Şifre Hatırlatma</a></li>
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
                        <h3>Şifre Hatırlatma</h3>
                     </div>
                        <form id="form">
                        <div class="single-login-field">
                           <input type="number" class="zorunlu" placeholder="Telefon Numaranız" name="tel">
                        </div>
                        <p class="uyari"></p>
                        <div class="single-login-field">
                           <button type="button" id="girisyap">Şifremi Sms ile Gönder</button>
                        </div>
                     </form>
                     <div class="dont_have">
                        <a href="giris?hedef=<?=$geldigiadres?>">Üye Girişi</a>
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

$(".zorunlu").each(function() {
if($(this).val()==''){
bosluk='bos';
$(this).addClass("input-hata");
}else{
bosluk='';
$(this).removeClass("input-hata");
}
});

if(bosluk=='bos'){
$('.uyari').html("Lütfen Tüm Alanları Doldurunuz!");
}else{
	$.ajax({
            type: "POST",
            url: "/uyeislem?islem=sifrehatirlat",             
            data: $('#form').serialize(),                
            success: function(gelenmesaj){
		if(gelenmesaj.trim().indexOf("gönderildi")>0){
			location.href = "/giris?success=Şifreniz sms ile gönderildi.&hedef=<?=$geldigiadres?>";
		}else{
			$('.uyari').html(gelenmesaj.trim());
		}
            }
        });
}
    });
});
</script>


   </body>
</html>

