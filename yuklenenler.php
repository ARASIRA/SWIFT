<?php
include "panel/db.php";
include "kontrol.php";
?>


<?php 
$dosyano=addslashes(trim($_GET["dosyano"]));
$query = $db->prepare("SELECT * FROM galeri where dosyano = :dosyano ");
$query->execute([
':dosyano' =>  $dosyano
]);
if ( $query->rowCount() ){
     foreach( $query as $row ){
?>

<div class="dondur<?=$row['id']?>" style="float:left;width:160px;height: 180px;margin:10px;">
<center><img src="<?=$row['adres']?>?v=<?=time()?>" class="img-thumbnail" style="max-width:160px" onerror="this.src='/panel/upload/img/noresim.png'"></center>
<br>
<?php if(isset($_GET['kapaksec'])){ ?>
<label><input type="radio" name="kapak" value="<?=$row['adres']?>" <?php if(isset($_GET['kapak'])){ if($row['adres']==$_GET['kapak']){ ?>checked<?php }} ?> required> Kapak</label>
<?php } ?>
<button type="button" id="pdosya<?=$row['id']?>" class="btn btn-danger btn-xs pull-right pdosyasil" style="margin-left:10px;"><i class="fa fa-close"></i></button>
<button type="button" class="btn btn-info btn-xs pull-right dondur" id="dondur<?=$row['id']?>"><i class="fa fa-repeat"></i></button> 
</div>

<script type="text/javascript">
i<?=$row['id']?>=0; 
$('#dondur<?=$row['id']?>').click(function () {
i<?=$row['id']?>=i<?=$row['id']?>+1;
$.get("/resimdondur.php?resim=<?=$row['adres']?>");
$("#yuklenenler").load("/yuklenenler.php?dosyano=<?=$dosyano?>&v=<?=time()?>");
});
</script>

<?php } } ?>

<div style="clear:both;"></div>



<script>
$('.pdosyasil').click(function () {
var pdosyaid1 = $(this).attr("id");
var pdosyaid = pdosyaid1.replace("pdosya", "");
$.get("/base64yukle.php?silid="+pdosyaid);
$(".dondur"+pdosyaid).hide("slow");

});
</script>

<?php ob_end_flush(); ?>