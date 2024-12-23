<?php
include "panel/db.php";
include "kontrol.php";
$sayfa="Mesajlar";
?>

<?php
if( isset($_GET['kisiid']) ){
$bak = $db->prepare("SELECT * FROM uyeler WHERE id=:id");
$bak->bindValue(':id', $_GET['kisiid']);
$bak->execute();
if($bak->rowCount()){
$veri = $bak->fetch(PDO::FETCH_ASSOC);
$kisiid=$veri['id'];
$kisi=$veri['isim'];
$kisiresim=$veri['resim'];
}
}
?>

<?php
if(isset($_GET['islem']) ){
if($_GET['islem']=='dosyagonder' && $kisiid!==$uyeid ){
 if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], 'panel/upload/mesajekleri/' . $uyeid .'-'.$_FILES['file']['name']);
$ekler='/panel/upload/mesajekleri/' . $uyeid .'-'.$_FILES['file']['name'];
$icerik='<a href=\'/panel/upload/mesajekleri/' . $uyeid .'-'.$_FILES['file']['name'].'\' class=\'btn btn-primary btn-sm btn-flat\' download><i class=\'fa fa-download\'></i> ' . $_FILES['file']['name'].'</a>';
$yeni = $db->prepare("INSERT INTO mesajlar SET kimden = :kimden, kimdenid = :kimdenid, kime = :kime, kimeid = :kimeid, icerik = :icerik, ekler = :ekler, okundu = 'Hayır', sil1 = 'Hayır', sil2 = 'Hayır' ");
$yeni->bindValue(':kimden', $isim );
$yeni->bindValue(':kimdenid', $uyeid );
$yeni->bindValue(':kime', $kisi );
$yeni->bindValue(':kimeid', $kisiid );
$yeni->bindValue(':icerik', $icerik);
$yeni->bindValue(':ekler', $ekler);
$yeni->execute();

	echo "Dosya başarıyla gönderildi.";
    }
}
}
?>



<?php
if(isset($_GET['mesaj']) ){
if($_GET['mesaj']=='yaz' && $kisiid!==$uyeid ){

$yeni = $db->prepare("INSERT INTO mesajlar SET kimden = :kimden, kimdenid = :kimdenid, kime = :kime, kimeid = :kimeid, icerik = :icerik, okundu = 'Hayır', sil1 = 'Hayır', sil2 = 'Hayır' ");
$yeni->bindValue(':kimden', $isim );
$yeni->bindValue(':kimdenid', $uyeid );
$yeni->bindValue(':kime', $kisi );
$yeni->bindValue(':kimeid', $kisiid );
$yeni->bindValue(':icerik', $_POST['icerik']);
$yeni->execute();
}
}


if(isset($_GET['mesaj']) ){
if($_GET['mesaj']=='sil'){
$yeni = $db->prepare("UPDATE mesajlar SET sil1 = 'Evet' where kimdenid='$uyeid' and kimeid='$kisiid' ");
$yeni->execute();
$yeni = $db->prepare("UPDATE mesajlar SET sil2 = 'Evet' where kimdenid='$kisiid' and kimeid='$uyeid' ");
$yeni->execute();
}
}


if(isset($_GET['islem']) ){
if($_GET['islem']=='bak'){
$bak = $db->prepare("SELECT * FROM mesajlar WHERE kimdenid='$kisiid' and kimeid='$uyeid' and okundu='Hayır' ");
$bak->execute();
$mesajsay=$bak->rowCount();
echo $mesajsay;
}
}
?>



<?php 
if(isset($_GET['islem']) ){
if($_GET['islem']=='oku'){
?>

<div style="height:100px;"></div>
<?php
foreach($db->query("SELECT * FROM mesajlar WHERE (kimdenid='$uyeid' and kimeid='$kisiid' and sil1<>'Evet') or (kimdenid='$kisiid' and kimeid='$uyeid' and sil2<>'Evet') ") as $row) {
$bak = $db->prepare("SELECT * FROM uyeler WHERE id=:id");
$bak->bindValue(':id', $row['kimdenid']);
$bak->execute();
if($bak->rowCount()){
$veri = $bak->fetch(PDO::FETCH_ASSOC);
$kisiresim=$veri['resim'];
}

?>
										  <li <?php if($uyeid==$row['kimdenid']){ ?> class="chat-list-right"<?php } ?>>
                                             <div class="chat-img">
                                                <a href="#">
                                                <img src="<?php echo $kisiresim;?>" onerror="this.src='/assets/resimler/noperson.png'" alt="<?php echo $row['kimden'];?>">
                                                </a>
                                             </div>
                                             <div class="chat-content">
                                                <div class="chat-text"><b><?php echo $row['kimden'];?></b><br>
                                                   <?php echo $row['icerik'];?>
                                                </div>
                                                <div class="chat-time"><?php echo date('d.m.Y H:i', strtotime($row['tarih']));?> <?php if($row['okundu']=='Evet' && $uyeid==$row['kimdenid']){ ?> <i class="glyphicon glyphicon-ok" data-toggle="tooltip" title="Okundu"> </i> <?php } ?></div>
                                             </div>
                                          </li>

<?php
}

$yeni = $db->prepare("UPDATE mesajlar SET okundu = 'Evet' where kimdenid='$kisiid' and kimeid='$uyeid' ");
$yeni->execute();

?>
<script>$("#mesajcek").scrollTop(1000000);</script>
<?php
}}
?>


