<?php
include "panel/db.php";
include "kontrol.php";

$song = $db->prepare("UPDATE uyeler SET songiris = '".date('Y-m-d H:i:s',time())."' where id=".$uyeid." ");
$song->execute();
?>


<?php
if( isset($_GET['bild']) ){
if( $_GET['bild']=="temizle" ){
$query = $db->prepare("DELETE FROM bildirimler WHERE uyeid = :uyeid");
$delete = $query->execute(array(
'uyeid' => $uyeid
));
}
if( $_GET['bild']=="sustur" ){
$query = $db->prepare("UPDATE bildirimler Set durum=1 WHERE uyeid = :uyeid");
$delete = $query->execute(array(
'uyeid' => $uyeid
));
}
}
?>


<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
<i class="fa fa-bell-o"></i>
<?php
$bibak = $db->prepare("SELECT * FROM bildirimler WHERE uyeid=:uyeid and durum=0");
$bibak->execute([
            ':uyeid' =>  $uyeid
        ]);
		$okunmayanverisay = $bibak->rowCount();

$query = $db->prepare("SELECT * FROM bildirimler WHERE uyeid=:uyeid order by id desc");
        $query->execute([
            ':uyeid' =>  $uyeid
        ]);
		$verisay = $query->rowCount();

if($okunmayanverisay > 0){
?>
<span class="text-danger"><b><?=$okunmayanverisay?></b></span>
<?php } ?>

</a>
<ul class="dropdown-menu dropdown-menu-right pt-0 pb-0" style="min-width:250px;max-height:200px;overflow-y:auto;overflow-x:hidden;">
<?php if($verisay == 0){ ?>
<li style="background:#555;"><a href="#" style="font-size:12px;color:#fff;"><center>Bildiriminiz Bulunmamaktadır.</center></a></li>
<?php }else{ ?>
<li style="background:#0a0;"><a href="#" style="font-size:12px;color:#fff;"><center><i class="fa fa-warning text-yellow"></i> <b><?=$verisay?> Bildiriminiz Var!</b></center></a></li>
<?php
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<li style="font-size:12px;color:#333;<?php if($row['durum']==0){ ?>background-color:#efc5c5;<?php } ?>" ><?=$row['mesaj']?></li>
<?php } ?>
<li style="border-top:1px solid #ddd;background:#a00;text-align:center;"><a href="#" class="bildtemizle" style="font-size:12px;color:#fff;"><i class="fa fa-trash-o"></i> Temizle</a></li>
</ul>

<?php if($okunmayanverisay > 0){ ?>
<audio autoplay="true" src="/panel/dist/notify.mp3" style="display:none;">
<?php } ?>

<?php } ?>



<script>
$('.bildtemizle').click(function () {
$.get("/bildirimler.php?bild=temizle");
});
</script>

<?php
setcookie("uyeid", $uyeid, time()+(86400*30));
?>