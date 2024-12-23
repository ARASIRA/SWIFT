<?php
$filename=$_GET['resim'];
$source = imagecreatefromjpeg($filename);
$rotate = imagerotate($source, 270, 0);
imagejpeg($rotate, $filename);
echo $filename.'?v='.time();
?>
