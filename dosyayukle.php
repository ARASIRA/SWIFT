<?php
include "panel/db.php";
include "kontrol.php";
?>



<?php
if( isset($_GET['islem']) ){
if( $_GET['islem']=="resimyukle" ){

$dosyano=$_POST['dosyano'];

if($_POST['resimdata']!==""){
	define('UPLOAD_DIR', 'panel/upload/resimler/');
	$img = $_POST['resimdata'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . $dosyano .'-'. uniqid() .'-' .time().'.png';
	$success = file_put_contents($file, $data);
	$adres='/'. $file;

$yeni = $db->prepare("INSERT INTO galeri SET adres = :resim, dosyano=:dosyano ");
$yeni->bindValue(':resim', $adres);
$yeni->bindValue(':dosyano', $dosyano);
$yeni->execute();

if($yeni){
?>

<img src="<?php echo $adres;?>" style="width:50px;height:50px;margin:5px;cursor:pointer" class="yuklenen" >

<?php
}}
}}
?>
