



<!-- Modal -->
<div class="modal fade" style="z-index:999999;" id="teklifal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
	<form id="talepformu">
	<input type="hidden" id="katid" name="katid" value="0">
	<input type="hidden" id="ozelid" name="ozelid" value="0">
	<div class="modal-header" style="display:block;padding:0px;box-shadow: 0px 3px 5px #ddd;">
        <p class="modal-title font-weight-bold p-2 text-center">
		<i class="fa fa-chevron-left float-left ml-1 mt-1 pointer geriadim" style="font-size: 20px;color:#ddd;"></i>
		<span class="baslik">Kategori</span>
        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</p>
		<hr class="ilerleme" style="margin:0;border:2px solid #090;min-width:5%;max-width:99%;width:0%">
		<p class="p-2 font-weight-bold" style="display:none;">Fiyat Aralığı: <span class="float-right text-info ucretler">0 TL</span></p>
		</div>
		<div class="modal-body" style="height:70vh;max-height:550px;overflow-y:auto;">
			<div id="adimlar"></div>
			<div class="text-center text-danger adimuyari" style="font-size:16px"></div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success btn-block ileriadim" >DEVAM ET</button>
			<button type="button" class="btn btn-success btn-block hizligiris" style="display:none;" >DEVAM ET</button>
			<button type="button" class="btn btn-danger btn-block talepkaydet" style="display:none;" >TALEBİMİ OLUŞTUR</button>
			<a href="/" class="btn btn-default btn-block sonbuton" style="display:none;" >ANASAYFAYA DÖN</a>
			
		</div>
	</form>
    </div>
  </div>
</div>


      <!-- Footer Area Start -->
      <footer class="jobguru-footer-area">
         <div class="footer-top section_50">
            <div class="container">
               <div class="row">
                  <div class="col-lg-4 col-md-6">
                     <div class="single-footer-widget">
                        <div class="footer-logo">
                           <a href="/">
                           <img src="<?=$logo?>"  style="width:200px;border-radius: 5px;" />
                           </a>
                        </div>
                        <p><?=$description?></p>
                        <ul class="footer-social">
                           <?php if($facebook!==''){ ?><li><a href="<?=$facebook?>" class="fb"><i class="fa fa-facebook"></i></a></li><?php } ?>
                           <?php if($twitter!==''){ ?><li><a href="<?=$twitter?>" class="twitter"><i class="fa fa-twitter"></i></a></li><?php } ?>
                           <?php if($instagram!==''){ ?><li><a href="<?=$instagram?>" class="instagram"><i class="fa fa-instagram"></i></a></li><?php } ?>
                        </ul>
                     </div>
                  </div>
                  
                  <div class="col-lg-4 col-md-6">
                     <div class="single-footer-widget">
                        <h3>Önemli Linkler</h3>
                        <ul>
<?php
$query = $db->prepare("SELECT * FROM sayfalar where etiket like '%sayfa%' order by id asc");
$query->execute();
$verisay = $query->rowCount();
if($verisay){
while($row=$query->fetch(PDO::FETCH_ASSOC)) {
?>
<li><a href="/sayfa/<?=$row['link']?>"><i class="fa fa-angle-double-right "></i> <?=$row['baslik']?></a></li>
<?php }} ?>

                        </ul>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                     <div class="single-footer-widget footer-contact">
                        <h3>Bize Ulaşın</h3>
                        <p><i class="fa fa-edit"></i> <a href="/bize-ulasin">Bize Yazın</a> </p>
						<?php if($sitetel!==''){?>
                        <p><i class="fa fa-phone"></i> <?=$sitetel?></p>
						<?php } ?>
                        <?php if($siteposta!==''){?>
                        <p><i class="fa fa-envelope-o"></i> <?=$siteposta?></p>
						<?php } ?>
                        
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer-copyright">
            <div class="container">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="copyright-left">
                        <p>Copyright &copy; 2020 Tüm hakları saklıdır.</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- Footer Area End -->

<input type="hidden" id="uyeid" value="<?=$uyeid?>">
<input type="hidden" id="uyeil" value="<?=$uyeil?>">
<input type="hidden" id="uyeilce" value="<?=$uyeilce?>">


      <!--Popper js-->
      <script src="/assets/js/popper.min.js?v=<?=$v?>"></script>
      <!--Bootstrap js-->
      <script src="/assets/js/bootstrap.min.js?v=<?=$v?>"></script>
      <!--Bootstrap Datepicker js-->
      <script src="/assets/js/bootstrap-datepicker.min.js?v=<?=$v?>"></script>
      <!--Perfect Scrollbar js-->
      <script src="/assets/js/jquery-perfect-scrollbar.min.js?v=<?=$v?>"></script>
      <!--Owl-Carousel js-->
      <script src="/assets/js/owl.carousel.min.js?v=<?=$v?>"></script>
      <!--SlickNav js-->
      <script src="/assets/js/jquery.slicknav.min.js?v=<?=$v?>"></script>
      <!--Magnific js-->
      <script src="/assets/js/jquery.magnific-popup.min.js?v=<?=$v?>"></script>
      <!--Select2 js-->
      <script src="/assets/js/select2.min.js?v=<?=$v?>"></script>
      <!--jquery-ui js-->
      <script src="/assets/js/jquery-ui.min.js?v=<?=$v?>"></script>
	  <!--Custom-Scrollbar js-->
      <script src="/assets/js/custom-scrollbar.js?v=<?=$v?>"></script>
      <!-- bootstrap datepicker -->
	  <script src="/panel/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js?v=<?=$v?>"></script>
	  <script src="/panel/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.tr.min.js?v=<?=$v?>"></script>
      <!--Main js-->
      <script src="/assets/js/main.js?v=<?=$v?>"></script>


<link href="/panel/bower_components/sweetalert2/sweetalert2.min.css" rel="stylesheet" />
<script src="/panel/bower_components/sweetalert2/sweetalert2.min.js"></script>

<?php if(isset($_GET['success'])){ ?>
<script>
Swal.fire({
  position: 'top-end',
  icon: 'success',
  title: '<?=$_GET['success']?>',
  showConfirmButton: false,
  timer: 2000
})
</script>
<?php } ?>

<?php if(isset($_GET['error'])){ ?>
<script>
Swal.fire({
  position: 'top-end',
  icon: 'error',
  title: '<?=$_GET['error']?>',
  showConfirmButton: false,
  timer: 4000
})
</script>
<?php } ?>





<?php
$db = null;
ob_end_flush(); 
?>