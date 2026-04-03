<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTR | Kayıt Ol</title>
    <link rel="stylesheet" href="Randevu_al/randevu_al.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php
            require 'vendor/autoload.php';
            
            // 1. DOKUNUŞ: Bağlantıyı db.php'den çekmek daha profesyoneldir.
            // Ama şimdilik manuel kalsın dersen bu kod çalışır.
            $clientUri = "mongodb+srv://dbHayrunnisa:1377Nisa.@cluster0.ibqgzo4.mongodb.net/?appName=Cluster0";
            $manager = new MongoDB\Client($clientUri);
            $db = $manager->veteriner_randevu;
            $usersCollection = $db->kullanicilar;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // 2. DOKUNUŞ: trim() ekleyerek görünmez boşlukları temizliyoruz.
                // Login'deki hata genelde buradan kaynaklanır.
                $adSoyad = trim($_POST['kullanıcı-adı']);
                $email = trim($_POST['email']);
                $tel = trim($_POST['tel']);
                $sifre = trim($_POST['sifre']);

                $existingUser = $usersCollection->findOne(['email' => $email]);

                if ($existingUser) {
                    echo "<div class='message'>
                            <p>Bu kullanıcı zaten mevcut. Başka bir e-posta adresi deneyin.</p>
                          </div> <br>";
                    echo "<a href='kayıt_ol.php'><button class='btn'>Geri Dön</button></a>";
                    exit;
                }

                // Şifreyi hashliyoruz
                $hashed_sifre = $sifre;

                $result = $usersCollection->insertOne([
                    'adSoyad' => $adSoyad,
                    'email' => $email,
                    'tel' => $tel,
                    'sifre' => $hashed_sifre
                ]);

                if ($result->getInsertedCount() == 1) {
                    echo "<div class='message'>
                            <p>Kayıt Başarılı</p>
                          </div> <br>";
                    echo "<a href='login.php'><button class='btn'>Giriş Yap</button></a>";
                    exit;
                } else {
                    echo "Kayıt işlemi başarısız oldu.";
                }
            } else {
            ?>

            <header>Kayıt Ol</header>
            <form action="" method="post">
                <div class="fiead input">
                    <label for="kullanıcı-adı">Kullanıcı Ad - Soyad</label>
                    <input type="text" name="kullanıcı-adı" id="kullanıcı-adı" required>
                </div>
                <div class="fiead input">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="fiead input">
                    <label for="tel">Telefon Numarası</label>
                    <input type="text" name="tel" id="tel" required>
                </div>
                <div class="fiead input">
                    <label for="sifre">Şifre</label>
                    <input type="password" name="sifre" id="sifre" required>
                </div>
                <div class="fiead input">
                    <input type="submit" class="btn" name="submit" id="submit" value="Kayıt Ol">
                </div>
                <div class="links">
                    Mevcut bir hesabınız var mı? <a href="login.php">Giriş Yap</a>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</body>
</html>