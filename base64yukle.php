<?php
include "panel/db.php";
include "kontrol.php";
?>

<?php
if( isset($_GET['silid']) ){

$dosyabak = $db->prepare("SELECT * FROM galeri WHERE id=:id");
$dosyabak->bindValue(':id', $_GET['silid']);
$dosyabak->execute();
if($dosyabak->rowCount()){
$rbilgi = $dosyabak->fetch(PDO::FETCH_ASSOC);
$adres=$rbilgi['adres'];

unlink($_SERVER['DOCUMENT_ROOT'] .'/'. $adres);
echo "resim ve ";
}

$query = $db->prepare("DELETE FROM galeri WHERE id = :id");
$query = $query->execute(array(
'id' => $_GET['silid']
));
echo "kayıt silindi";
}





if( isset($_GET['action']) ){
if( $_GET['action']=="upload_image" ){
	define('UPLOAD_DIR', 'panel/upload/resimler/');
	$img = file_get_contents('php://input');
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR .$uyeid.'-'. uniqid() . '.png';
	$success = file_put_contents($file, $data);

$adres='/'.$file;

$yeni = $db->prepare("INSERT INTO galeri SET adres = :adres , dosyano = :dosyano  ");
$yeni->bindValue(':adres', $adres );
$yeni->bindValue(':dosyano', $_GET['id_image'] );
$yeni->execute();

	print $success ? $adres : 'Hata Oluştu!';
}
}
?>
