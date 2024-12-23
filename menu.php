
         <div class="menu-animation">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-2">
                     <div class="site-logo">
                        <a href="/">
                        <img src="<?=$logo?>" alt="jobguru" class="non-stick-logo" style="max-width:unset;height:40px;border-radius: 5px;" />
                        <img src="<?=$logo?>" alt="jobguru" class="stick-logo" style="max-width:unset;height:40px;border-radius: 5px;" />
                        </a>
                     </div>
                     <!-- Responsive Menu Start -->
                     <div class="jobguru-responsive-menu"></div>
                     <!-- Responsive Menu Start -->
                  </div>
                  <div class="col-md-8">
                     <div class="header-menu" style="width:100%">
                        <nav id="navigation">
                           <ul id="jobguru_navigation">
<?php
$menusay=0;
foreach($db->query("SELECT * FROM ustmenu where ustkat=0 order by sira asc, id asc") as $mn1) {
$menusay=$menusay+1;
$altmn = $db->prepare("SELECT * FROM ustmenu where ustkat=".$mn1['id']." order by sira asc, id asc ");
$altmn->execute();
?>
              <li class="<?php if($altmn->rowCount()){ ?>drop-down<?php } ?>"><a href="<?=$mn1['link']?>" <?php if($altmn->rowCount()){ ?>class="dropdown-toggle" id="dropdownMenu<?=$mn1['id']?>" data-toggle="dropdown" aria-expanded="true"<?php } ?>><?=$mn1['baslik']?></a>
			    <?php if($altmn->rowCount()){ ?>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu<?=$mn1['id']?>">
				  <?php while($mn2=$altmn->fetch(PDO::FETCH_ASSOC)) { ?>
                  <li><a href="<?=$mn2['link']?>"><?=$mn2['baslik']?></a></li>
				  <?php } ?>
                </ul>
				<?php } ?>
              </li>
<?php } ?>
			  <li style="border: 1px solid #8BC34A;background: #4CAF50;"><a href="/hizmetekle" style="color:#fff !important;">Hizmet Ver</a></li>
			  
                           </ul>
                        </nav>
                     </div>
                  </div>
                  <div class="col-md-3">
                     
                  </div>
               </div>
            </div>
         </div>
		 
		 

					 <div class="header-right-menu">
                        <ul>
                              <?php if($isim!==''){?>
							  <li class="bildirimler" ></li>
							  <li class="mesajlar" ></li>
							  <li class="dropdown"><a href="#"  class="dropdown-toggle" id="udropdownMenu1" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-user"></i> <?=$isim?></a>
								<ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="udropdownMenu1">
								<?php if($yetki=='99' || $yetki=='98' || $yetki=='11'){?>
								<li><a href="/panel/">Yönetim Paneli</a></li>
								<?php } ?>
								<li><a href="/profilim">Profilim</a></li>
								<?php if($uyetipi!=='Müşteri'){?>
								<li><a href="/hizmetilanlarim">Hizmet İlanlarım</a></li>
								<li><a href="/bakiyem">Bakiyem (<?=$uyebakiye?> TL)</a></li>
								<li><a href="/paketler">Avantajlı Paketler</a></li>
								<li style="border-top:1px solid #ddd;"></li>
								<li><a href="/talepler">Teklif Bekleyen İşler</a></li>
								<li><a href="/tekliflerim">Tekliflerim</a></li>
								<?php }?>
								<li style="border-top:1px solid #ddd;"></li>
								<li><a href="/taleplerim">Taleplerim</a></li>
								<li><a href="/cik">Güvenli Çıkış</a></li>
								</ul>
						      </li>
							  <?php }else{ ?>
							  <li class="girisbutonu"><a href="/giris"><i class="fa fa-lock"></i> Giriş Yap</a></li>
							  <?php } ?>
                        </ul>
                     </div>
