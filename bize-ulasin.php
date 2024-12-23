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
      <title>Bize Ulaşın</title>
      <meta name="description" content="Bize Ulaşın">
      <meta name="keyword" content="Bize,Ulaşın">
      
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
                           <li class="active-breadcromb"><a href="#">Bize Ulaşın</a></li>
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
		 
		 <?php
	if ( $_POST ){
	if ($_POST['guvenlik1']==$_POST['guvenlik2']){

$yetkilibilgi = $db->query("SELECT * FROM uyeler where id=".$yetkiliid." ")->fetch(PDO::FETCH_ASSOC);

		$adsoyad = htmlspecialchars(trim($_POST['adsoyad']));
		$konu = htmlspecialchars(trim($_POST['konu']));
		$eposta = htmlspecialchars(trim($_POST['eposta']));
		$mesaj = $adsoyad ."<br>".$eposta."<br>". $konu."<br>".trim($_POST['mesaj']);
		include 'panel/bower_components/phpmailer/class.phpmailer.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = $sitepostasunucu;
		$mail->Port = $sitesunucuportu;
		$mail->Username = $siteposta;
		$mail->Password = $sitepostasifre;
		$mail->SetFrom($mail->Username, $siteadi);
		$mail->AddBCC($yetkilibilgi['eposta'], $siteadi);
		$mail->CharSet = 'UTF-8';
		$mail->Subject = $konu;
		$mail->IsHTML(true); 
		$mail->Body = $mesaj;
		if($mail->Send()) {
			// e-posta başarılı ile gönderildi
			echo '<div class="alert alert-success">Mesajınız başarıyla gönderildi.</div>';
		} else {
			// bir sorun var, sorunu ekrana bastıralım
			echo '<div class="alert alert-danger">'.$mail->ErrorInfo.'</div>';
		}
	} else {
			// bir sorun var, sorunu ekrana bastıralım
			echo '<div class="alert alert-danger">Güvenlik Kodunu yanlış girdiniz!</div>';
	}
	}
?>


            <div class="row">
               <div class="col-md-7">
                  <div class="contact-right">
                     <h3>Bize Ulaşın</h3>
                     <form action="?" method="post">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="single-contact-field">
                                 <input type="text" placeholder="Adınız Soyadınız" name="adsoyad" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="single-contact-field">
                                 <input type="email" placeholder="E-Posta Adresiniz" name="eposta" required>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="single-contact-field">
                                 <input type="text" placeholder="Konu" name="konu" required>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="single-contact-field">
                                 <textarea placeholder="Mesajınız" name="mesaj" required></textarea>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="single-contact-field">
								 <input type="hidden" name="guvenlik1" value="<?=substr(time(), -3);?>" required>
                                 <input type="number" placeholder="Bu kaç? <?=substr(time(), -3);?>" name="guvenlik2" required>
                              </div>
                           </div>
						   <div class="col-md-6">
                              <div class="single-contact-field">
                                 <button type="submit"><i class="fa fa-paper-plane" required></i> Gönder</button>
                              </div>
                           </div>
						   
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Candidate Dashboard Area End -->
       
       

<?php include "alt.php"; ?>


   </body>
</html>

