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
      <title>Giriş Yap</title>
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
                           <li class="active-breadcromb"><a href="#">Üye Girişi</a></li>
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
                        <h3>Giriş Yap</h3>
                     </div>
                     <form id="form">
                        <div class="single-login-field">
                           <input type="email" class="zorunlu" placeholder="E-Posta Adresiniz" name="eposta" id="eposta">
                        </div>
                        <div class="single-login-field">
                           <input type="password" class="zorunlu" placeholder="Şifreniz" name="sifre" id="sifre">
                        </div>
						<p class="uyari"></p>
                        <div class="single-login-field">
                           <button type="button" id="girisyap">Giriş Yap</button>
                        </div>
                     </form>
                     <div class="mt-5">
					    <a class="btn btn-outline-success btn-sm" href="/uyesifrehatirlatma">Şifremi Unuttum</a>
                        <a class="btn btn-outline-success btn-sm float-right" href="/uyeol">Üye Olmak İstiyorum</a>
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
            url: "/uyeislem?islem=girisyap",             
            data: $('#form').serialize(),                
            success: function(gelenmesaj){
		if(gelenmesaj.trim().indexOf("Başarılı")>0){
			location.href = "<?php echo $geldigiadres; ?>";
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

