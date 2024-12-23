<?php
include "panel/db.php";
include "kontrol.php";
include "kontroluyelik.php";

// Kullanıcı verisini çekme
$bak = $db->prepare("SELECT * FROM uyeler WHERE id = :id");
$bak->bindValue(':id', $uyeid);
$bak->execute();
if ($bak->rowCount()) {
    $veri = $bak->fetch(PDO::FETCH_ASSOC);

    if (isset($_GET['islem'])) {
        if ($_GET['islem'] == "kaydet") {

            // Formdan gelen verileri al
            $isim = $_POST['isim'];
            $tckimlikno = $_POST['tckimlikno'];
            $eposta = $_POST['eposta'];
            $il = $_POST['il'];
            $ilce = $_POST['ilce'];
            $unvan = $_POST['unvan'];
            $ozgecmis = $_POST['ozgecmis'];
            $adres = $_POST['adres'];
            $facebook = $_POST['facebook'];
            $twitter = $_POST['twitter'];
            $instagram = $_POST['instagram'];
            $linkedin = $_POST['linkedin'];
            $sifre = $_POST['sifre'];
            $sifre2 = $_POST['sifre2'];

            // T.C. Kimlik No doğrulama
            if (!preg_match('/^\d{11}$/', $tckimlikno)) {
                header("Location: ?error=T.C. Kimlik No 11 haneli rakam olmalıdır.");
                exit();
            }

            if (preg_match('/^(\d)\1{10}$/', $tckimlikno)) {
                header("Location: ?error=T.C. Kimlik No geçersiz.");
                exit();
            }

            // Veritabanını güncelle
            $yeni = $db->prepare("UPDATE uyeler SET 
                isim = :isim,
                tckimlikno = :tckimlikno,
                eposta = :eposta, 
                il = :il, 
                ilce = :ilce, 
                unvan = :unvan, 
                ozgecmis = :ozgecmis, 
                adres = :adres, 
                facebook = :facebook, 
                twitter = :twitter, 
                instagram = :instagram, 
                linkedin = :linkedin 
                WHERE id = :id");
            $yeni->bindValue(':isim', $isim);
            $yeni->bindValue(':tckimlikno', $tckimlikno);
            $yeni->bindValue(':eposta', $eposta);
            $yeni->bindValue(':il', $il);
            $yeni->bindValue(':ilce', $ilce);
            $yeni->bindValue(':unvan', $unvan);
            $yeni->bindValue(':ozgecmis', $ozgecmis);
            $yeni->bindValue(':adres', $adres);
            $yeni->bindValue(':facebook', $facebook);
            $yeni->bindValue(':twitter', $twitter);
            $yeni->bindValue(':instagram', $instagram);
            $yeni->bindValue(':linkedin', $linkedin);
            $yeni->bindValue(':id', $uyeid);
            $yeni->execute();

            // Şifre güncelleme
            if (!empty($sifre) && !empty($sifre2)) {
                if ($sifre === $sifre2) {
                    $sifre_hash = password_hash($sifre, PASSWORD_BCRYPT);
                    $yeni_sifre = $db->prepare("UPDATE uyeler SET sifre = :sifre WHERE id = :id");
                    $yeni_sifre->bindValue(':sifre', $sifre_hash);
                    $yeni_sifre->bindValue(':id', $uyeid);
                    $yeni_sifre->execute();
                } else {
                    header("Location: ?error=Şifre ve Şifre tekrarı aynı olmalıdır.");
                    exit();
                }
            }

            if ($yeni) {
                header("Location: ?success=Değişiklikler kaydedildi.");
                exit();
            }
        }

        if ($_GET['islem'] == "resimkaydet") {
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $file = 'panel/upload/resimler/' . $uyeid . '-' . time() . '.png';
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $file)) {
                    $yeni = $db->prepare("UPDATE uyeler SET resim = :resim WHERE id = :uyeid");
                    $yeni->bindValue(':uyeid', $uyeid);
                    $yeni->bindValue(':resim', "/" . $file);
                    $yeni->execute();

                    if ($yeni) {
                        echo "ok";
                        exit();
                    }
                }
            }
            echo "error";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Profilim</title>
    <?php include "script.php"; ?>
    <style>
        .single-input input[type="password"],
        .single-input input[type="text"],
        .single-input input[type="file"],
        .single-input select,
        .single-input textarea {
            display: block;
            width: 100%;
            border: 2px solid #e8ecec;
            padding: 7px 10px;
            border-radius: 5px;
            color: #666;
            margin-bottom: 15px;
        }
        .uyari {
            margin-bottom: 15px;
        }
    </style>
    <link href="assets/cropper/cropper.css" rel="stylesheet">
</head>
<body>
    <header class="jobguru-header-area stick-top forsticky page-header">
        <?php include "menu.php"; ?>
    </header>
    <section class="jobguru-breadcromb-area">
        <div class="breadcromb-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcromb-box-pagin">
                            <ul>
                                <li><a href="/">Anasayfa</a></li>
                                <li class="active-breadcromb"><a href="#">Profilim</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="candidate-dashboard-area section_30">
        <div class="container">
            <div class="row">
                <?php include "menuprofil.php"; ?>
                <div class="col-lg-9 col-md-12">
                    <div class="dashboard-right">
                        <div class="candidate-profile">
                            <div class="candidate-single-profile-info">
                                <form action="?islem=resimkaydet" method="post" enctype="multipart/form-data">
                                    <div class="single-resume-feild resume-avatar">
                                        <div class="resume-image">
                                            <img src="<?php echo htmlspecialchars($veri['resim']); ?>" onerror="this.src='/assets/resimler/noperson.png'" id="avatar" alt="Profil Resmi">
                                            <div class="resume-avatar-hover">
                                                <div class="resume-avatar-upload">
                                                    <input type="file" name="avatar" id="input" accept="image/*" required>
                                                    <p>
                                                        <i class="fa fa-pencil"></i>
                                                        Güncelle
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center"><button type="submit" class="btn btn-success yuklebuton2"><i class="fa fa-pencil"></i> Değişikliği Kaydet</button></p>
                                </form>
                            </div>
                            <div class="candidate-single-profile-info">
                                <form id="pform" action="?islem=kaydet" method="post">
                                    <div class="resume-box">
                                        <h3>Profilim</h3>
                                        <div class="single-resume-feild">
                                            <div class="single-input">
                                                <label for="isim">Ad Soyad:</label>
                                                <input type="text" name="isim" value="<?php echo htmlspecialchars($veri['isim']); ?>" id="isim" required>
                                            </div>
                                        </div>
                                        <div class="single-resume-feild feild-flex-2">
                                            <div class="single-input">
                                                <label for="tckimlikno">T.C. Kimlik No:</label>
                                                <input type="text" name="tckimlikno" value="<?php echo htmlspecialchars($veri['tckimlikno']); ?>" id="tckimlikno" required pattern="\d{11}" title="11 haneli T.C. Kimlik Numarası giriniz">
                                            </div>
                                            <div class="single-input">
                                                <label for="unvan">Unvan (Zorunlu değil):</label>
                                                <input type="text" name="unvan" value="<?php echo htmlspecialchars($veri['unvan']); ?>" placeholder="" id="unvan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="resume-box">
                                        <h3>İletişim Bilgileri</h3>
                                        <div class="single-resume-feild feild-flex-2">
                                            <div class="single-input">
                                                <label for="Phone">Telefon:</label>
                                                <input type="text" name="tel" value="<?php echo htmlspecialchars($veri['tel']); ?>" id="Phone" required readonly>
                                            </div>
                                            <div class="single-input">
                                                <label for="Phone">E-Posta:</label>
                                                <input type="text" name="tel" value="<?php echo htmlspecialchars($veri['eposta']); ?>" id="Phone" required readonly>
                                            </div>
                                        </div>
                                        <div class="single-resume-feild feild-flex-2">
                                            <div class="single-input">
                                                <label for="sifre">Şifre <small>(Değiştirmek istemiyorsanız boş bırakınız)</small>:</label>
                                                <input type="password" name="sifre" value="" id="sifre">
                                            </div>
                                            <div class="single-input">
                                                <label for="sifre2">Şifre (Tekrar) <small>(Değiştirmek istemiyorsanız boş bırakınız)</small>:</label>
                                                <input type="password" name="sifre2" value="" id="sifre2">
                                            </div>
                                        </div>
                                        <div class="single-resume-feild feild-flex-2">
                                            <div class="single-input">
                                                <label for="il">İl:</label>
                                                <select id="il" name="il" required>
                                                    <option value=""> İl </option>
                                                    <?php
                                                    $query = $db->prepare("SELECT * FROM iller ORDER BY il ASC");
                                                    $query->execute();
                                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    ?>
                                                        <option value="<?= htmlspecialchars($row['il']); ?>"><?= htmlspecialchars($row['il']); ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="single-input">
                                                <label for="ilce">İlçe:</label>
                                                <select id="ilce" name="ilce" required>
                                                    <option value=""> Önce İl Seçiniz </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="single-resume-feild">
                                            <div class="single-input">
                                                <label for="Address22">Adres:</label>
                                                <input type="text" name="adres" value="<?php echo htmlspecialchars($veri['adres']); ?>" id="Address22" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="display:none;">
                                        <div class="resume-box">
                                            <div class="single-resume-feild">
                                                <div class="single-input">
                                                    <label for="Bio">Kısa Özgeçmiş:</label>
                                                    <textarea id="Bio" name="ozgecmis" placeholder="Müşterilere kendinizi anlatın"><?php echo htmlspecialchars($veri['ozgecmis']); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="resume-box">
                                            <h3>Sosyal Medya Linkleri</h3>
                                            <div class="single-resume-feild feild-flex-2">
                                                <div class="single-input">
                                                    <label for="twitter">
                                                        <i class="fa fa-twitter twitter"></i>
                                                        Twitter
                                                    </label>
                                                    <input type="text" name="twitter" value="<?php echo htmlspecialchars($veri['twitter']); ?>" id="twitter">
                                                </div>
                                                <div class="single-input">
                                                    <label for="facebook">
                                                        <i class="fa fa-facebook facebook"></i>
                                                        Facebook
                                                    </label>
                                                    <input type="text" name="facebook" value="<?php echo htmlspecialchars($veri['facebook']); ?>" id="facebook">
                                                </div>
                                            </div>
                                            <div class="single-resume-feild feild-flex-2">
                                                <div class="single-input">
                                                    <label for="instagram">
                                                        <i class="fa fa-instagram google"></i>
                                                        Instagram
                                                    </label>
                                                    <input type="text" name="instagram" value="<?php echo htmlspecialchars($veri['instagram']); ?>" id="instagram">
                                                </div>
                                                <div class="single-input">
                                                    <label for="linkedin">
                                                        <i class="fa fa-linkedin linkedin"></i>
                                                        Linkedin
                                                    </label>
                                                    <input type="text" name="linkedin" value="<?php echo htmlspecialchars($veri['linkedin']); ?>" id="linkedin">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-resume">
                                        <p class="uyari text-danger text-center"></p>
                                        <button type="submit">Değişiklikleri Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="jobguru-hire-2-area section_70">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="hire-2-box">
                                <h2>Herhangi bir sektörde hizmet mi veriyorsunuz?<br>Hadi para kazanmaya başlayın.</h2>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="hire-box-2-btn">
                                <a href="/hizmetekle.php" class="jobguru-btn-2">Hizmet Profili Oluştur</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Profil Fotoğrafı Yükle</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="img-container">
                                <img class="img-fluid" id="image" src="<?php echo htmlspecialchars($veri['resim']); ?>" onerror="this.src='/assets/resimler/noperson.png'" >
                            </div>
                        </div>
                        <div class="modal-footer">
                            <span class="float-left">
                                <button type="button" class="btn btn-secondary" id="soladondur"><i class="fa fa-rotate-left"></i></button>
                                <button type="button" class="btn btn-secondary" id="sagadondur"><i class="fa fa-rotate-right"></i></button>
                            </span>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Vazgeç</button>
                            <button type="button" class="btn btn-primary" id="crop">Kaydet</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php include "alt.php"; ?>

            <script>
                $('#pform').submit(function(){
                    var tckimlikno = $('#tckimlikno').val();
                    var tckimliknoPattern = /^\d{11}$/;
                    if (!tckimliknoPattern.test(tckimlikno)) {
                        $('.uyari').html("Lütfen geçerli 11 haneli T.C. Kimlik No giriniz.");
                        return false;
                    }

                    if($('#sifre').val() !== ''){
                        if($('#sifre2').val() == ''){
                            $('.uyari').html("Şifre tekrarını doldurunuz!");
                            return false;
                        } else if($('#sifre').val() !== $('#sifre2').val()){
                            $('.uyari').html("Şifre ve Şifre tekrarı aynı olmalı!");
                            return false;
                        }
                    }

                    return true;
                });
            </script>

            <script src="/assets/cropper/cropper.js"></script>
            <script>
                window.addEventListener('DOMContentLoaded', function () {
                    var avatar = document.getElementById('avatar');
                    var image = document.getElementById('image');
                    var input = document.getElementById('input');
                    var $progress = $('.progress');
                    var $progressBar = $('.progress-bar');
                    var $alert = $('.alert');
                    var $modal = $('#modal');
                    var cropper;

                    $('[data-toggle="tooltip"]').tooltip();

                    input.addEventListener('change', function (e) {
                        var files = e.target.files;
                        var done = function (url) {
                            input.value = '';
                            image.src = url;
                            $alert.hide();
                            $modal.modal('show');
                        };
                        var reader;
                        var file;
                        var url;

                        if (files && files.length > 0) {
                            file = files[0];

                            if (URL) {
                                done(URL.createObjectURL(file));
                            } else if (FileReader) {
                                reader = new FileReader();
                                reader.onload = function (e) {
                                    done(reader.result);
                                };
                                reader.readAsDataURL(file);
                            }
                        }
                    });

                    $modal.on('shown.bs.modal', function () {
                        cropper = new Cropper(image, {
                            aspectRatio: 1,
                            viewMode: 3,
                            rotatable: true,
                            movable: true,
                            zoomable: true,
                            scalable: true
                        });
                    }).on('hidden.bs.modal', function () {
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }
                    });

                    $('#sagadondur').click(function(){
                        cropper.rotate(90);
                    });
                    $('#soladondur').click(function(){
                        cropper.rotate(-90);
                    });

                    document.getElementById('crop').addEventListener('click', function () {
                        var initialAvatarURL;
                        var canvas;

                        $modal.modal('hide');

                        if (cropper) {
                            canvas = cropper.getCroppedCanvas({
                                width: 160,
                                height: 160,
                            });
                            initialAvatarURL = avatar.src;
                            avatar.src = canvas.toDataURL();
                            $progress.show();
                            $alert.removeClass('alert-success alert-warning');
                            canvas.toBlob(function (blob) {
                                var formData = new FormData();
                                formData.append('avatar', blob);
                                $.ajax('?islem=resimkaydet', {
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,

                                    xhr: function () {
                                        var xhr = new XMLHttpRequest();

                                        xhr.upload.onprogress = function (e) {
                                            var percent = '0';
                                            var percentage = '0%';

                                            if (e.lengthComputable) {
                                                percent = Math.round((e.loaded / e.total) * 100);
                                                percentage = percent + '%';
                                                $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                                            }
                                        };

                                        return xhr;
                                    },

                                    success: function () {
                                        $alert.show().addClass('alert-success').text('Yükleme Başarılı');
                                    },

                                    error: function () {
                                        avatar.src = initialAvatarURL;
                                        $alert.show().addClass('alert-warning').text('Yükleme Başarısız');
                                    },

                                    complete: function () {
                                        $progress.hide();
                                    },
                                });
                            });
                        }
                    });
                });
            </script>

            <script type="text/javascript">
                $(function(){
                    $('#il').on('change', function () {
                        var il = $('#il option:selected').val();
                        $.post("/ilceler.php", {il: il}, function (cevap) {
                            $("#ilce").html(cevap);
                            $(".ilce").show("slow");
                        });
                    });

                    $("#il").val("<?php echo htmlspecialchars($veri['il']); ?>");
                    $.post("/ilceler.php", {il: '<?php echo htmlspecialchars($veri['il']); ?>'}, function (cevap) {
                        $("#ilce").html(cevap);
                        $(".ilce").show("slow");
                        $("#ilce").val("<?php echo htmlspecialchars($veri['ilce']); ?>");
                    });
                });
            </script>
        </section>
    </body>
</html>
<?php } ?>
