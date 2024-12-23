<?php
include "panel/db.php";
?>

<option value="*"> Tüm İlçeler </option>
<?php
$query1 = $db->prepare("SELECT * FROM ilceler where il = :il ");
$query1->bindValue(':il', $_POST['il']);
$query1->execute();
if($row1=$query1->fetch(PDO::FETCH_ASSOC)) {

$query = $db->prepare("SELECT * FROM ilceler where ilid = :ilid order by ilce asc ");
$query->bindValue(':ilid', $row1['ilid']);
$query->execute();
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<option value="<?=$row['ilce']?>"><?=$row['ilce']?></option>
<?php }} ?>
