<?php
include "panel/db.php";
include "kontrol.php";
?>


<?php
$bak = $db->prepare("SELECT * FROM kategoriler where (ustkat>0 and baslik like :kat) or ustkat in (SELECT id FROM kategoriler where baslik like :kat) order by baslik asc");
$bak->bindValue(':kat','%'.$_POST['kat'].'%');
$bak->execute();
while ($row = $bak->fetch(PDO::FETCH_ASSOC)){
$kat1 = $db->query("SELECT * FROM kategoriler where id=".$row['ustkat']." ")->fetch(PDO::FETCH_ASSOC);

$hbak = $db->query("SELECT * FROM hizmetler where uyeid=".$uyeid." and katid=".$row['id']." ")->fetchColumn();
if($hbak=='0'){
?>
<p class="hsonuclar" style="width:100%;height:40px;padding:5px;border-top:1px solid #ddd;cursor:pointer;color:#000;font-size:16px;" data-katid="<?=$row['id']?>" data-baslik="<?=$row['baslik']?>" data-belgeler='<?=$row['belgeler']?>' ><b><?=$row['baslik']?></b> <small><?=$kat1['baslik']?></small></p>
<?php }}?>


<script>
i=0;
$(".hsonuclar").click(function(){
i=i+1;
$("#kathizmet").val("").hide();
$("#secilenhizmetler").append('<p style="margin-top:10px;font-size:18px;color:#090"><i class="fa fa-trash-o kaldir" style="cursor:pointer"></i> '+ $(this).attr("data-baslik") + '<input type="hidden" name="katid" value="'+ $(this).attr("data-katid") +'"><input type="hidden" name="kat2" value="'+ $(this).attr("data-baslik") +'"></p>' );
$("#baslik").attr("placeholder","ÖR: Uygun ücretlerle "+ $(this).attr("data-baslik") +" hizmeti veriyorum");
$(".belgeler").html('');
if($(this).attr("data-belgeler")!==''){
$(".belgeler").html('<div class="single-resume-feild"><div class="single-input"><label>Zorunlu Belgeler:</label></div></div>');
var belgeler=JSON.parse($(this).attr("data-belgeler"));
var i=-1;
$.each(belgeler, function(index, val) {
i++;
$(".belgeler .single-input").append('<div class="input-group input-group-sm" style="margin-bottom:10px"><span class="input-group-addon" style="width:200px">'+val+'</span><input type="hidden" name="belgeler['+i+']" value="'+ val +'"><input type="file" class="form-control zorunlu" name="dosyalar'+i+'" required></div>' );
});
}
$("#kathizmetsonuc").html("");
$(".hizmetdevam").removeClass("hide");
});
</script>