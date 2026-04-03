<?php
session_start();
include 'db.php';

// Güvenlik: Giriş yapmamışsa login'e gönder
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// KOLEKSİLYONLARI TANIMLA (İsimlerin aşağıyla aynı olması şart)
$usersCollection = $db->kullanicilar;
$randevuCollection = $db->randevular; 

$user_id = $_SESSION['user_id'];

// Kullanıcı bilgilerini çek (Nav bar için)
$user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($user_id)]);

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTR | Randevu Al</title>
    <link rel="stylesheet" href="Randevu_al/randevu_al.css">
</head>
<body style="background:#f9fff5;">
    <div class="nav">
        <a href="../İndex/index.html" class="logo"> 
            <img src="../image/Logo.png" alt="Logo"> 
        </a> 
        <a href="../İndex/index.html" style="text-decoration: none;"> 
            <div class="logo_yazısı"> 
                <span class="ilk">Veteriner</span> 
                <span class="orta">Randevu</span> 
                <span class="son">Sistemi</span> 
            </div> 
        </a>

        <div class="right-links">
            <a href="logout.php"><button class="btn-çıkış">Çıkış Yap</button></a>
        </div>
    </div>

    <main>
        <div class="main-box top">
            <div class="top">
                <div class="box">
                    <p>Hoş Geldiniz, <b><?php echo htmlspecialchars($user['adSoyad']); ?>!</b></p>
                </div>
                <div class="box">
                    <button id="randevuAlBtn" onclick="openModal()" class="btn-randevu"><b>Randevu Al</b></button>
                </div>
                
                <div id="randevuAlModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h3>Mevcut Boş Randevular:</h3>
                        <div class="box form-box">
                            <form action="randevu_al.php" method="post">
                                <div class="field input">
                                    <label for="randevu">Randevu Seçin</label>
                                    <select name="randevu_id" id="randevu" required>
                                        <option value="" disabled selected>Bir seçenek seçin</option>
                                        <?php
                                        // 'durum' => 'mevcut' olan tüm randevuları bul
                                        $bosRandevular = $randevuCollection->find(['durum' => 'mevcut']);
                                        foreach ($bosRandevular as $br) {
                                            echo "<option value='".$br['_id']."' 
                                                  data-tarih='".$br['tarih']."' 
                                                  data-saat='".$br['saat']."' 
                                                  data-doktor='".$br['doktor']."'
                                                  data-klinik='".$br['klinik']."'>
                                                  ".$br['tarih']." - ".$br['saat']." - ".$br['doktor']." (".$br['klinik'].")
                                                  </option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <input type="hidden" name="tarih" id="tarih">
                                <input type="hidden" name="saat" id="saat">
                                <input type="hidden" name="doktor" id="doktor">
                                <input type="hidden" name="klinik" id="klinik">

                                <div class="field input">
                                    <input type="submit" class="btn" name="submit" value="Randevuyu Onayla">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom">
                <div class="box">
                    <h3>Randevularınız:</h3>
                    <div class="randevu-listesi">
                        <?php
                        // KULLANICIYA AİT RANDEVULARI LİSTELE
                        // Değişken ismini yukarıdakiyle aynı yaptık ($randevuCollection)
                        $user_randevulari = $randevuCollection->find(['user_id' => new MongoDB\BSON\ObjectId($user_id)]);
                        $randevularArray = iterator_to_array($user_randevulari);

                        if (empty($randevularArray)) {
                            echo "<p>Henüz alınmış bir randevunuz yok.</p>";
                        } else {
                            foreach ($randevularArray as $r) {
                                echo "<div style='border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; border-radius: 5px; background: white;'>
                                        <strong>Doktor:</strong> ".htmlspecialchars($r['doktor'])."<br>
                                        <strong>Klinik:</strong> ".htmlspecialchars($r['klinik'])."<br>
                                        <strong>Zaman:</strong> ".htmlspecialchars($r['tarih'])." / ".htmlspecialchars($r['saat'])."
                                      </div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Modal Açma/Kapama Fonksiyonları
        function openModal() { document.getElementById('randevuAlModal').style.display = 'block'; }
        function closeModal() { document.getElementById('randevuAlModal').style.display = 'none'; }

        // Select değişince hidden inputları doldur
        document.getElementById('randevu').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            document.getElementById('tarih').value = selectedOption.getAttribute('data-tarih');
            document.getElementById('saat').value = selectedOption.getAttribute('data-saat');
            document.getElementById('doktor').value = selectedOption.getAttribute('data-doktor');
            document.getElementById('klinik').value = selectedOption.getAttribute('data-klinik');
        });
    </script>
</body>
</html>