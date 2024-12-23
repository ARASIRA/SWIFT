<?php
include "panel/db.php";
?>


<?php
$bak = $db->prepare("SELECT * FROM kategoriler where (ustkat>0 and baslik like :kat) or ustkat in (SELECT id FROM kategoriler where baslik like :kat) order by baslik asc");
$bak->bindValue(':kat','%'.$_POST['kat'].'%');
$bak->execute();
$verisay = $bak->rowCount();
while ($row = $bak->fetch(PDO::FETCH_ASSOC)){
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$row['ustkat']." ")->fetch(PDO::FETCH_ASSOC);
?>
<p class="sonuclar" style="width:100%;padding:5px;border-top:1px solid #ddd;cursor:pointer;color:#000;font-size:16px;" data-katid="<?=$row['id']?>" data-baslik="<?=$row['baslik']?>" data-ustbaslik="<?=$kat1['baslik']?>"><b><?=$row['baslik']?></b> <small style="display:inline-block"><?=$kat1['baslik']?></small></p>
<?php }?>
