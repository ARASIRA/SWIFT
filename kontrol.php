<?php
$v=1.9;
$uyeid='';
$uyeeposta='';
$isim='';
$uyeil='';
$uyeilce='';
$uyeadres='';
$uyetel='';
$uyetipi='Müşteri';
$yetki='';
$paketid=0;
$paketozellikleri='';
if(isset($_SESSION["uyeid"])){
$uyeid=$_SESSION["uyeid"];
}else if(isset($_COOKIE["uyeid"])){
$uyeid=$_COOKIE["uyeid"];
}

// Kullanıcı bilgilerini çekme ve atama işlemleri
if(!empty($uyeid)){
    $q = $db->prepare("SELECT * FROM uyeler WHERE id=:id");
    $q->bindValue(':id', $uyeid, PDO::PARAM_INT);
    $q->execute();
    if($q->rowCount() > 0){
        $uyebilgi = $q->fetch(PDO::FETCH_ASSOC);
        $uyeid = $uyebilgi['id'];
        $uyeeposta = $uyebilgi['eposta'];
        $isim = $uyebilgi['isim'];
        $uyeil = $uyebilgi['il'];
        $uyeilce = $uyebilgi['ilce'];
        $uyeadres = $uyebilgi['adres'];
        $uyetel = $uyebilgi['tel'];
        $uyetipi = $uyebilgi['uyetipi'];
        $uyebakiye = $uyebilgi['bakiye'];
        $yetki = $uyebilgi['yetki'];
        $tckimlikno = isset($uyebilgi['tckimlikno']) ? $uyebilgi['tckimlikno'] : '';

        // Aktif paket bilgilerini çekme
        // ... (Mevcut paket çekme kodunuz)

        // Profil Tamamlanmamış Kontrolü
        if(empty($tckimlikno) || empty($uyeilce)){
            echo '
            <div style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                background-color: #ff4d4d;
                color: #ffffff;
                text-align: center;
                padding: 15px 0;
                font-weight: bold;
                z-index: 1000;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            ">
                Profil bilgilerinizi tamamlamadan işlemlerinize devam edemezsiniz. 
                <a href="https://www.arasiraswift.com/profilim" style="color: #ffffff; text-decoration: underline; font-weight: bold;">Profilimi Tamamla</a>
            </div>
            ';
        }
    }
}
?>