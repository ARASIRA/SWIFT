<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";
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
$kisisongorulme=date('d.m.Y H:i',strtotime($veri['songiris']));
$kisitokenid=$veri['tokenid'];
}
}
?>


<!DOCTYPE html>
<html lang="tr">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">

	        <!-- Title -->
      <title>Mesajlar</title>
      <meta name="description" content="">
      <meta name="keyword" content="">
      
<?php include "script.php"; ?>


   </head>
   <body>
       
       
      <!-- Header Area Start -->
      <header class="jobguru-header-area stick-top forsticky page-header">
<?php include "menu.php"; ?>
      </header>
      <!-- Header Area End -->
       
       
       
      <!-- Breadcromb Area Start -->
      <section class="jobguru-breadcromb-area">
         <div class="breadcromb-bottom">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="breadcromb-box-pagin">
                        <ul>
                           <li><a href="/">Anasayfa</a></li>
                           <li class="active-breadcromb"><a href="#">Mesajlar</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Breadcromb Area End -->
       
       
      <!-- Candidate Dashboard Area Start -->
      <section class="candidate-dashboard-area section_30">
         <div class="container">
            <div class="row">
               <?php include "menuprofil.php"; ?>
			   <div class="col-lg-9 col-md-12">
                  <div class="dashboard-right message-page-box">
                     <div class="manage-jobs-heading">
                        <h3>Mesajlar</h3>
                     </div>
                     <div class="row">
                        <div class="col-lg-4 col-md-12">
                           <div class="chat-list-left">
                              
                              <!-- End Chat Search -->
                              <div class="chat-list-body">
                                 <ul class="chat-list">
<?php
$mesajsay1=0;
foreach($db->query("SELECT * FROM uyeler where id<>'.$uyeid.' ") as $row) {
$bak1 = $db->prepare('SELECT * FROM mesajlar WHERE (kimdenid='.$row['id'].' and kimeid='.$uyeid.') or (kimdenid='.$uyeid.' and kimeid='.$row['id'].') ');
$bak1->execute();
$mesajsay1=$bak1->rowCount();
if($mesajsay1>0){

$bak = $db->prepare('SELECT * FROM mesajlar WHERE kimdenid='.$row['id'].' and kimeid='.$uyeid.' and okundu="Hayır" ');
$bak->execute();
$mesajsay=$bak->rowCount();
?>
                                    <li class="clearfix">
                                       <a href="/mesajlar/<?=$row['id']?>">
                                          <div class="chat-avatar-img">
                                             <img src="<?=$row['resim']?>" onerror="this.src='/assets/resimler/noperson.png'" alt="avatar" />
                                          </div>
                                          <div class="chat-about">
                                             <h4><?=$row['isim']?> <?php if($mesajsay>0){?><span class="text-danger pull-right" ><b>(<?=$mesajsay?>)</b></span><?php } ?></h4>
                                             <small class="online"><?php echo date('d.m.Y H:i',strtotime($row['songiris']));?></small>
											 
                                          </div>
                                       </a>
                                    </li>
<?php }} ?>

<?php if($mesajsay1=='0'){?><li class="clearfix">Henüz mesajınız yok. </li><?php } ?>

                                    <!-- end list -->
                                 </ul>
                              </div>
                              <!-- End Chat List Body -->
                           </div>
                        </div>
                        <!-- Col End -->
						<?php if(!empty($kisiid)){ ?>
                        <div class="col-lg-8 col-md-12" id="mesajpanosu">
                           <div class="chat-board-right">
                              <div class="chat-board-header">
                                 <div class="navbar">
                                    <div class="user-details-nav">
                                       <div class="chat-user-details">
                                          <div class="pull-left chat-user-img">
                                             <a href="#">
                                             <img src="<?php echo $kisiresim;?>" onerror="this.src='/assets/resimler/noperson.png'" alt="<?php echo $kisi;?>">
                                             </a>
                                          </div>
                                       </div>
                                       <div class="user-info pull-left">
                                          <a href="#" title="<?php echo $kisi;?>">
                                             <h4><?php echo $kisi;?></h4>
                                          </a>
                                          <small class="online"><?php echo $kisisongorulme;?></small>
                                       </div>
                                    </div>
                                    <ul class="nav navbar-nav pull-right custom-menu">
                                       <li class="dropdown"><i class="fa fa-trash-o sil" style="cursor:pointer" title="Konuşmayı Sil"></i>
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                              <!-- End Chatbot Header -->
                              <div class="chat-board-content">
                                 <div class="chat-box-wrapper">
                                    <div class="chat-box-inner">
                                       <ul class="chat-list" id="mesajcek">
                                       </ul>
									   <p id="ekler" style="margin-left:20px;font-size:16px;color:red;font-weight:600;"></p>
                                    </div>
                                 </div>
                              </div>
                              <!-- End Chatbot COntent -->
                              <div class="chat-footer">
                                 <div class="message-bar">
                                    <div class="message-bar-inner">
                                       <div class="attach-icon">
                                          <p>
                                             <i class="fa fa-paperclip" id="yukle"></i>
                                          </p>
                                       </div>
                                       <div class="message-text-area">
                                             <input type="text" name="icerik" id="icerik" placeholder="Mesajınız..." class="form-control" autofocus="on" autocomplete="off">
                                             <button type="button" id="gonder"><i class="fa fa-send"></i></button>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <!-- End Chat Footer -->
                           </div>
                        </div>
                        <!-- Col Start -->
						<?php } ?>
                     </div>
                  </div>
               </div>
			   <input type="file" id="dosya" style="opacity:0">
			   
            </div>
         </div>
      </section>
      <!-- Candidate Dashboard Area End -->
       


<?php include "alt.php"; ?>





<?php if(!empty($kisiid)){ ?>

<script>
$([document.documentElement, document.body]).animate({
        scrollTop: $("#mesajpanosu").offset().top-60
}, 2000);
$("#mesajcek").load("/mesajcek.php?islem=oku&kisiid=<?php echo $kisiid; ?>");
$("#mesajcek").scrollTop(1000000);
$(document).ready(function() {
setInterval(function() {
		$.post("/mesajcek.php?kisiid=<?php echo $kisiid; ?>&islem=bak",{islem: "bak"},function (baktim) {
			if(baktim.trim()!=="0"){
            $("#mesajcek").load("/mesajcek.php?islem=oku&kisiid=<?php echo $kisiid; ?>");
			$("#mesajcek").scrollTop(1000000);
			}
        });
}, 3000);

$('.sil').click(function () {
	$.get("/mesajcek.php?islem=oku&kisiid=<?php echo $kisiid; ?>&mesaj=sil",function (gelen) {
	$("#mesajcek").load("/mesajcek.php?islem=oku&kisiid=<?php echo $kisiid; ?>");
	});
});

});
</script>



<script type="text/javascript">
var mesajsay = 0 ;
function mesajcek() {
		var icerik=$("#icerik").val();
		if(!!icerik){
			$("#gonder").prop('disabled', true);
		    $("#icerik").prop('disabled', true);
	        $.post("/mesajcek.php?islem=oku&kisiid=<?php echo $kisiid; ?>&mesaj=yaz", {
                icerik: icerik
            }, function (cevap) {
			mesajsay = mesajsay+1;
            $("#mesajcek").html(cevap);
			$("#icerik").val("");
			$("#icerik").prop('disabled', false);
			$("#icerik").focus();
			$("#gonder").prop('disabled', false);
<?php if($kisitokenid!==''){?>
if(mesajsay=='1'){
		var tokenid="<?=$kisitokenid?>";
		var baslik="1 Yeni Mesaj";
		var mesaj="<?=$isim?> size mesaj yazdı.";
        $.post("/mobilmesajpost.php", {tokenid: tokenid, baslik: baslik, mesaj: mesaj });
}
<?php } ?>
            });
		}
}

$('#icerik').keypress(function (e) {
 var key = e.which;
 if(key == 13)
  {
	mesajcek();
  }
});

$('#gonder').click(function () {
$("#gonder").prop('disabled', true);
	mesajcek();
});


</script>



<script>
$('#yukle').on('click', function() {
$('#dosya').click();
});
$('#dosya').change(function (){
$('#ekler').html("Yükleniyor...");  
    var file_data = $(this).prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);  
    $.ajax({
        url: '/mesajcek.php?islem=dosyagonder&kisiid=<?php echo $kisiid; ?>',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(cevap){
            $('#ekler').html(cevap);
			$("#mesajcek").load("/mesajcek.php?islem=oku&kisiid=<?php echo $kisiid; ?>");
        }
     });
});
</script>







<?php } ?>








</body>
</html>

