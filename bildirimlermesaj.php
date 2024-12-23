<?php
include "panel/db.php";
include "kontrol.php";
?>


<a href="/mesajlar" >
<i class="fa fa-envelope-o"></i>
<?php
$bak = $db->prepare("SELECT * FROM mesajlar WHERE kimeid='$uyeid' and okundu='Hayır' ");
$bak->execute();
$okunmayanverisay=$bak->rowCount();
if($okunmayanverisay > 0){
?>
<span class="text-danger"><b><?=$okunmayanverisay?></b></span>
<?php } ?>
</a>

<?php if($okunmayanverisay > 0){ ?>
<audio autoplay="true" src="/panel/dist/notify.mp3" style="display:none;">
<?php } ?>
