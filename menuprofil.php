				<div class="col-lg-3 col-md-12 dashboard-left-border">
                  <div class="dashboard-left">
                     <ul class="dashboard-menu">
                        <li><p class="togglemenu" id="yonetimpanelim"> <i class="fa fa-bars"></i> Yönetim Panelim </p></li>
                        <li class="yonetimpanelim" ><a href="/profilim"> <i class="fa fa-user"></i> Profilim </a></li>
                        <li class="yonetimpanelim" ><a href="/mesajlar"><i class="fa fa-envelope-open"></i> Mesajlar </a></li>
						<?php if($uyetipi!=='Müşteri'){?>
                        <li class="yonetimpanelim" ><a href="/hizmetilanlarim"><i class="fa fa-briefcase"></i> Hizmet İlanlarım</a></li>
                        <li class="yonetimpanelim" ><a href="/bakiyem"><i class="fa fa-money"></i> Bakiye İşlemleri </a></li>
						<li class="yonetimpanelim" style="border: 2px solid blue;"><a href="/paketler"><i class="fa fa-star"></i> Avantajlı Paketler</a></li>
						<li class="yonetimpanelim" ><a href="/talepler"><i class="fa fa-hourglass-2"></i>Teklif Bekleyen Talepler</a></li>
						<li class="yonetimpanelim" ><a href="/tekliflerim"><i class="fa fa-gavel"></i> Tekliflerim</a></li>
						<?php }?>
                        <li class="yonetimpanelim" ><a href="/taleplerim"><i class="fa fa-calendar-o"></i> Taleplerim</a></li>
                        <li class="yonetimpanelim" ><a href="/cik"><i class="fa fa-power-off"></i>Çıkış Yap</a></li>
                     </ul>
                  </div>
               </div>