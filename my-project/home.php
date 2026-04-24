<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$usersCollection = $db->kullanicilar;
$randevuCollection = $db->randevular; 
$user_id = $_SESSION['user_id'];

$user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($user_id)]);

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTR | Dashboard</title>
    <link rel="stylesheet" href="Randevu_al/randevu_al.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Sayfaya Özel Ek Modern Dokunuşlar */
        body { font-family: 'Inter', sans-serif !important; background-color: #f8fafc !important; }
        
        .dashboard-container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        .welcome-section { 
            display: flex; justify-content: space-between; align-items: center; 
            background: white; padding: 30px; border-radius: 15px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); margin-bottom: 30px;
        }

        .welcome-text h2 { margin: 0; color: #1e293b; font-size: 1.5rem; }
        .welcome-text p { margin: 5px 0 0; color: #64748b; }

        .btn-main-action {
            background: #7ba85a; color: white; border: none; padding: 12px 24px;
            border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; gap: 8px;
        }
        .btn-main-action:hover { background: #699053; transform: translateY(-2px); }

        .appointment-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px; margin-top: 20px;
        }

        .appointment-card {
            background: white; border-radius: 12px; padding: 20px;
            border-left: 5px solid #7ba85a; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .appointment-card:hover { shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }

        .card-header { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .doc-name { font-weight: 700; color: #334155; }
        .clinic-tag { background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem; color: #475569; }
        
        .card-body { font-size: 0.9rem; color: #64748b; line-height: 1.6; }
        .date-row { display: flex; align-items: center; gap: 5px; color: #7ba85a; font-weight: 600; }

        /* Modal Tasarım İyileştirme */
        .modal-content { border-radius: 20px; border: none; padding: 40px !important; }
        select { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="nav">
        <div style="display: flex; align-items: center; max-width: 1200px; width: 100%; justify-content: space-between; padding: 0 20px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="../İndex/index.html" class="logo"> <img src="../image/Logo.png" alt="Logo"> </a> 
                <div class="logo_yazısı" style="padding-right: 0;"> 
                    <span class="ilk">Veteriner</span> 
                    <span class="orta">Randevu</span> 
                    <span class="son">Sistemi</span> 
                </div> 
            </div>
            <div class="right-links">
                <a href="logout.php"><button class="btn-çıkış">Çıkış Yap</button></a>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <section class="welcome-section">
            <div class="welcome-text">
                <h2>Merhaba, <?php echo htmlspecialchars($user['adSoyad']); ?>! 👋</h2>
                <p>Evcil dostunuzun sağlığı için randevularınızı buradan yönetebilirsiniz.</p>
            </div>
            <button onclick="openModal()" class="btn-main-action">
                <span>➕ Yeni Randevu Al</span>
            </button>
        </section>

        <section>
            <h3 style="color: #1e293b; margin-bottom: 20px;">Aktif Randevularınız</h3>
            <div class="appointment-grid">
                <?php
                $user_randevulari = $randevuCollection->find(['user_id' => new MongoDB\BSON\ObjectId($user_id)]);
                $randevularArray = iterator_to_array($user_randevulari);

                if (empty($randevularArray)) {
                    echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px; background: #fff; border-radius: 15px;'>
                            <p style='color: #94a3b8;'>Henüz bir randevu kaydınız bulunmuyor.</p>
                          </div>";
                } else {
                    foreach ($randevularArray as $r) {
                        echo "
                        <div class='appointment-card'>
                            <div class='card-header'>
                                <span class='doc-name'>Dr. ".htmlspecialchars($r['doktor'])."</span>
                                <span class='clinic-tag'>".htmlspecialchars($r['klinik'])."</span>
                            </div>
                            <div class='card-body'>
                                <div class='date-row'>
                                    📅 ".htmlspecialchars($r['tarih'])." | 🕒 ".htmlspecialchars($r['saat'])."
                                </div>
                                <p style='margin-top: 10px; font-size: 0.8rem;'>Dostunuz emin ellerde!</p>
                            </div>
                        </div>";
                    }
                }
                ?>
            </div>
        </section>
    </div>

    <div id="randevuAlModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 style="margin-bottom: 20px;">Uygun Randevu Seçin</h3>
            <form action="randevu_al.php" method="post">
                <select name="randevu_id" id="randevu" required>
                    <option value="" disabled selected>Mevcut saatleri görüntüle...</option>
                    <?php
                    $bosRandevular = $randevuCollection->find(['durum' => 'mevcut']);
                    foreach ($bosRandevular as $br) {
                        echo "<option value='".$br['_id']."' 
                              data-tarih='".$br['tarih']."' 
                              data-saat='".$br['saat']."' 
                              data-doktor='".$br['doktor']."'
                              data-klinik='".$br['klinik']."'>
                              ".$br['tarih']." - ".$br['saat']." | Dr. ".$br['doktor']."
                              </option>";
                    }
                    ?>
                </select>

                <input type="hidden" name="tarih" id="tarih">
                <input type="hidden" name="saat" id="saat">
                <input type="hidden" name="doktor" id="doktor">
                <input type="hidden" name="klinik" id="klinik">

                <button type="submit" name="submit" class="btn-main-action" style="width: 100%; justify-content: center;">
                    Randevuyu Onayla
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModal() { document.getElementById('randevuAlModal').style.display = 'flex'; }
        function closeModal() { document.getElementById('randevuAlModal').style.display = 'none'; }

        document.getElementById('randevu').addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            document.getElementById('tarih').value = opt.getAttribute('data-tarih');
            document.getElementById('saat').value = opt.getAttribute('data-saat');
            document.getElementById('doktor').value = opt.getAttribute('data-doktor');
            document.getElementById('klinik').value = opt.getAttribute('data-klinik');
        });

        // Modal dışına tıklayınca kapatma
        window.onclick = function(event) {
            let modal = document.getElementById('randevuAlModal');
            if (event.target == modal) { closeModal(); }
        }
    </script>
</body>
</html>