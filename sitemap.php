<?php
include "panel/db.php";
header('Content-Type: text/xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
echo "<url><loc>".$siteurl . "</loc></url>";
echo "<url><loc>".$siteurl . "/bize-ulasin</loc></url>";
echo "<url><loc>".$siteurl . "/hizmetilanlari</loc></url>";
echo "<url><loc>".$siteurl . "/kategoriler</loc></url>";
$rs1 = $db->prepare("SELECT * FROM hizmetler where durum='Yayında' order by id desc ");
$rs1->execute();
while($row1=$rs1->fetch(PDO::FETCH_ASSOC)) {
echo "<url><loc>".$siteurl . "/profil/" . $row1['link'] . "</loc></url>";
}
$rs2 = $db->prepare("SELECT * FROM kategoriler where ustkat>0 order by baslik asc ");
$rs2->execute();
while($row2=$rs2->fetch(PDO::FETCH_ASSOC)) {
echo "<url><loc>".$siteurl . "/teklif-al/" . $row2['link'] . "</loc></url>";
}
$rs3 = $db->prepare("SELECT * FROM talepler order by id desc ");
$rs3->execute();
while($row3=$rs3->fetch(PDO::FETCH_ASSOC)) {
echo "<url><loc>".$siteurl . "/talep/" . $row3['talepno'] . "</loc></url>";
}
echo "</urlset>";
ob_end_flush();
?>