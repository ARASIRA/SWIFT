<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";

$id=$_GET['id'];

if( isset($_GET['islem']) ){
if( $_GET['islem']=="kaydet" ){

if( isset($_POST['belgeler']) ){
$dizin = 'panel/upload/resimler/';
foreach($_POST['belgeler'] as $key=>$val){
	$belgeler[$key]['baslik']=$val;
	$belgeler[$key]['dosya']=$_POST['ybelgeler'.$key];
	if(isset($_FILES['dosyalar'.$key])){
		$hata = $_FILES['dosyalar'.$key]['error'];
		if($hata != 0) {
			//$durum='Yüklenirken bir hata gerçekleşmiş.';
		} else {
			$boyut = $_FILES['dosyalar'.$key]['size'];
			if($boyut > (1024*1024*15)){
				$durum='Dosya 15MB den büyük olamaz.';
			} else {
				$tip = $_FILES['dosyalar'.$key]['type'];
				$isim = $_FILES['dosyalar'.$key]['name'];
				$dbol = explode('.', $isim);
				$uzanti = $dbol[1];
				$dosyaadi = $dbol[0];
				if($uzanti=='jpg' || $uzanti=='jpeg' || $uzanti=='png' || $uzanti=='pdf'){
					$orjdosya = $_FILES['dosyalar'.$key]['tmp_name'];
					$yenidosyaadi=$dizin . seflink($dosyaadi).'-'.$uyeid.time().$i.'.'.$uzanti;
					copy($orjdosya, $yenidosyaadi);
					$belgeler[$key]['baslik']=$val;
					$belgeler[$key]['dosya']='/'.$yenidosyaadi;
				}
			}
		}
    }
}
}

if( isset($_POST['kapak']) ){
$kapak=$_POST['kapak'];
}else{
$kapak='';
}

$yeni = $db->prepare("UPDATE hizmetler SET katid=:katid, baslik=:baslik, detaylar=:detaylar, enaz=:enaz, encok=:encok, bolge=:bolge, kapak=:kapak, belgeler=:belgeler where id=:id ");
$yeni->bindValue(':katid', $_POST['katid']);
$yeni->bindValue(':baslik', $_POST['baslik']);
$yeni->bindValue(':detaylar', $_POST['detaylar']);
$yeni->bindValue(':enaz', $_POST['enaz']);
$yeni->bindValue(':encok', $_POST['encok']);
$yeni->bindValue(':bolge', $_POST['bolge']);
$yeni->bindValue(':kapak', $kapak);
$yeni->bindValue(':belgeler', isset($belgeler) ? json_encode($belgeler,JSON_UNESCAPED_UNICODE):'');
$yeni->bindValue(':id', $id);
$yeni->execute();

header("Location: /hizmetilanlarim?success=İlan başarıyla eklendi.");
exit();
}}
?>

<?php
$q = $db->prepare("SELECT * FROM hizmetler WHERE id=:id");
$q->bindValue(':id', $id);
$q->execute();
if($q->rowCount()){
$row = $q->fetch(PDO::FETCH_ASSOC);
$dosyano=$row['galeri'];
$kapak=$row['kapak'];
$ybelgeler=$row['belgeler'];
$katbilgi = $db->query("SELECT * FROM kategoriler where id=".$row['katid']." ")->fetch(PDO::FETCH_ASSOC);
$belgeler=$katbilgi['belgeler'];
?>


<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Hizmet Profili Güncelle</title>
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
                           <li class="active-breadcromb"><a href="/hizmetilanlarim.php">Hizmet İlanlarım</a></li>
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
                     <div class="earnings-page-box manage-jobs">
                        <div class="manage-jobs-heading">
                           <h3>Hizmet Profili Güncelle <a class="btn btn-default btn-sm pull-right" href="/hizmetilanlarim.php"><i class="fa fa-reply"></i></a></h3></h3>
                        </div>
                        <div class="new-job-submission">
                           <form id="hizmetformu" action="?islem=kaydet&id=<?=$id?>" method="post" enctype="multipart/form-data">
                              <div class="resume-box">
                                 <div class="single-resume-feild feild-flex-1">
                                    <div class="single-input">
                                       <label for="katmanset">Hizmet Kategorisi:</label>
                                       <input type="text" class="hizmetara" id="kathizmet" placeholder="Ör: Tadilat,Özel Ders,Temizlik..." required>
									   <div id="kathizmetsonuc"></div>
									   <div id="secilenhizmetler">
									   <p style="margin-top:10px;font-size:18px;color:#090"> <?=$katbilgi['baslik']?><input type="hidden" name="katid" value="<?=$row['katid']?>"><input type="hidden" name="kat2" value="<?=$katbilgi['baslik']?>"></p>
									   </div>
                                    </div>
                                 </div>
<div class="hizmetdevam">
                                 <div class="single-resume-feild">
                                    <div class="single-input">
                                       <label for="baslik">Hizmet Başlığı:</label>
                                       <input type="text" class="zorunlu" id="baslik" name="baslik" value="<?=$row['baslik']?>" required>
                                    </div>
                                 </div>
								<div class="belgeler">
								<?php if($belgeler!==''){ ?>
								<div class="single-resume-feild">
									<div class="single-input"><label>Zorunlu Belgeler:</label>
									<?php
									$belgeler=json_decode($belgeler,true);
									$ybelgeler=$ybelgeler!=='' ? json_decode($ybelgeler,true):'';
									foreach($belgeler as $key=>$val){
									?>
										<div class="input-group input-group-sm" style="margin-bottom:10px">
											<span class="input-group-addon" style="width:200px"><?=!empty($ybelgeler[$key]['dosya']) ? '<a href="'.$ybelgeler[$key]['dosya'].'" download>'.$val.'</a>':$val?></span>
											<input type="hidden" name="belgeler[<?=$key?>]" value="<?=$val?>">
											<input type="file" class="form-control " name="dosyalar<?=$key?>" >
											<input type="hidden" name="ybelgeler<?=$key?>" value="<?=!empty($ybelgeler[$key]['dosya']) ? $ybelgeler[$key]['dosya']:''?>">
										</div>
									<?php } ?>
									</div>
								</div>
								<?php } ?>
								</div>
								 <div class="single-resume-feild">
                                    <div class="single-input">
                                       <label for="j_desc">Verdiğiniz hizmeti Müşterilere anlatın:</label>
                                       <textarea id="j_desc" class="zorunlu" name="detaylar" placeholder="Şu kadar yıldır bu hizmeti veriyorum. Verdiğim hizmete şunlar dahil değildir. Yaptığım hizmetin hep arkasındayım...vs." required><?=$row['detaylar']?></textarea>
                                    </div>
                                 </div>
                                 <div class="single-resume-feild feild-flex-2">
                                    <div class="single-input">
                                       <label for="mn_salary">Ücret Tarife Aralığı (En Az)<span>(₺)</span>:</label>
                                       <input type="number" class="zorunlu text-right" value="<?=$row['enaz']?>" id="mn_salary" name="enaz">
                                    </div>
                                    <div class="single-input">
                                       <label for="mx_salary">Ücret Tarife Aralığı (En Çok)<span>(₺)</span>:</label>
                                       <input type="number" class="zorunlu text-right" value="<?=$row['encok']?>" id="mx_salary" name="encok">
                                    </div>
                                 </div>
                                 
                                 <div class="row">
									<div class="col-sm-12 text-left">
									<label><b>Hizmeti verebileceğiniz yerler:</b></label>
									</div>  
                                    <div class="col-sm-4 single-input">
                                       <select id="il" name="il" >
                                          <option value="Tüm Şehirler"> Tüm Şehirler </option>
<?php
$query = $db->prepare("SELECT * FROM iller order by il asc ");
$query->execute();
while($row1=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<option value="<?=$row1['il'];?>" > <?=$row1['il'];?> </option>
<?php } ?>
									    </select>
                                    </div>
									<div class="col-sm-4 single-input ilce"  style="display:none">
                                       <select id="ilce" name="ilce" >
                                          <option value="Tüm Şehirler"> Tüm Şehirler </option>
									   </select>
                                    </div>
									<div class="col-sm-4 single-input text-left">
                                       <button type="button" class="btn btn-default" id="yerekle">Ekle</button>
                                    </div>
									<div class="col-sm-12 yerler text-left">
									<span></span>
									<?php foreach(explode(",",$row['bolge']) as $bolge){ if(trim($bolge)!==''){ ?>
										<div class="btn-group mt-2 mr-3"><button type="button" class="btn btn-light btn-sm"><?=$bolge?></button><button type="button" class="btn btn-danger btn-sm yersil" data-yer="<?=$bolge?>"><i class="fa fa-trash-o"></i></button></div>
									<?php }} ?>
									
									
									<input type="hidden" name="bolge" id="bolge" value="<?=$row['bolge']?>">
									</div> 
                                 </div>
								 <br>
                                 <div class="single-resume-feild upload_file">
								 <label for="External">Verdiğiniz hizmeti anlatan fotoğraflarla müşterilerin dikkatini çekin.<br> </label>
<div id="yuklenenler"></div>
<div class="progress" id="upload-progress" style="display:none">
  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
</div>

                                    <div class="product-upload">
                                       <p>
                                          <i class="fa fa-upload"></i>
                                          Görseller Yükleyin
                                       </p>
                                       <input data-id="<?=$row['galeri']?>" class="image-upload" id="w_screen" type="file" name="images[1]" accept="image/jpeg,image/jpg,image/png,image/gif" multiple>
                                    </div>
                                    
                                 </div>
                              </div>
</div>
<div class="hizmetdevam">
                              <div class="single-input submit-resume">
							  <p class="uyari"></p>
                                 <button type="button" id="kaydet">Hizmet Profilimi Kaydet</button>
                              </div>
</div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Candidate Dashboard Area End -->
       
       

<?php include "alt.php"; ?>

<script>
$("#kaydet").click(function(){
var bosmu="";
$("#hizmetformu .zorunlu").each(function(){
$(this).css("background","#fff");
if($(this).val()==''){
bosmu="evet";
$(this).css("background","#faa");
}
});

if(bosmu!==''){
$("#hizmetformu .uyari").html("Tüm Alanları doldurunuz!").show().delay("2000").hide("slow");
}else if($("#bolge").val()==''){
$("#hizmetformu .uyari").html("Hizmet verebileceğiniz en az bir yer ekleyiniz!").show().delay("2000").hide("slow");
}else{
$("#hizmetformu").submit();
}

});
</script>

<script type="text/javascript">
$(function(){	
	$('#kathizmet').on('keyup', function () {
	var kathizmet = $(this).val();
	if(kathizmet.length<2){
	$("#kathizmetsonuc").html("");
	}else{
		$.post("katcekhizmet.php", {
        kat: kathizmet
        }, function (cevap) {
            $("#kathizmetsonuc").html(cevap);
        });
	}
    });
	
	$("#kathizmet").click(function(){
	$("#kathizmet").val("");
	$("#kathizmetsonuc").val("");
	});
});

$("#kathizmet").hide();
$(document).on('click','.kaldir',function(e) {
$(this).closest("p").remove();
$(".hizmetdevam").hide();
$("#kathizmet").show();
});
</script>


    <script type="text/javascript" src="/assets/base64/js/exif.js"></script>
    <script type="text/javascript" src="/assets/base64/js/ImageUploader.js"></script>
    <script type="text/javascript" src="/assets/base64/js/custom.js"></script>
<script type="text/javascript">
$("#yuklenenler").load("/yuklenenler.php?dosyano=<?=$dosyano?>&kapak=<?=$kapak?>&kapaksec=evet");
</script>


<script type="text/javascript">
$(function(){
$('#il').on('change', function () {
	var il = $('#il option:selected').val();
    $.post("/hizmetekleilceler.php", {il: il}, function (cevap) {
        $("#ilce").html(cevap);
		$(".ilce").show();
		
    });
});

$("#il").val("<?=$uyeil?>");
$.post("/hizmetekleilceler.php", {il: '<?=$uyeil?>'}, function (cevap) {
        $("#ilce").html(cevap);
		$("#ilce").val("<?=$uyeilce?>");
		$(".ilce").show();
});

$(document).on("click","#yerekle",function(){
	var il = $('#il option:selected').val();
	var ilce = $('#ilce option:selected').val();
	var bolge = il+'/'+ilce;
	if($(".yerler #bolge").val().indexOf(bolge)=='-1'){
	$(".yerler span").append('<div class="btn-group mt-2 mr-3"><button type="button" class="btn btn-light btn-sm">'+bolge+'</button><button type="button" class="btn btn-danger btn-sm yersil" data-yer="'+bolge+'"><i class="fa fa-trash-o"></i></button></div>');
	$(".yerler #bolge").val($(".yerler #bolge").val() + bolge+', ');
	}
});

$(document).on("click",".yersil",function(){
	var bolge=$(this).attr("data-yer");
	$(".yerler #bolge").val(($(".yerler #bolge").val()).replace(bolge+', ',''));
	$(this).closest(".btn-group").remove();
});

});
</script>



</body>
</html>
<?php } ?>
