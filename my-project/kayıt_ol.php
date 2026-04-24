<?php
error_reporting(E_ALL & ~E_DEPRECATED);
ob_start();
session_start();

// db.php dosyan varsa kullan, yoksa aşağıdaki bağlantı satırını aktif bırak
include 'db.php'; 

/* // Eğer db.php kullanmıyorsan bu bloğu aktif et:
require 'vendor/autoload.php';
$clientUri = "mongodb+srv://dbHayrunnisa:1377Nisa.@cluster0.ibqgzo4.mongodb.net/?appName=Cluster0";
$manager = new MongoDB\Client($clientUri);
$db = $manager->veteriner_randevu;
*/

$usersCollection = $db->kullanicilar;
$message = "";
$message_type = ""; // success veya error

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $adSoyad = trim($_POST['kullanıcı-adı']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $sifre = trim($_POST['sifre']);

    try {
        $existingUser = $usersCollection->findOne(['email' => $email]);

        if ($existingUser) {
            $message = "Bu e-posta adresi zaten kullanımda.";
            $message_type = "error";
        } else {
            // ÖNEMLİ: Gerçek projede password_hash($sifre, PASSWORD_BCRYPT) kullanmalısın
            $result = $usersCollection->insertOne([
                'adSoyad' => $adSoyad,
                'email' => $email,
                'tel' => $tel,
                'sifre' => $sifre 
            ]);

            if ($result->getInsertedCount() == 1) {
                $message = "Kayıt başarılı! Giriş yapabilirsiniz.";
                $message_type = "success";
                // İsteğe bağlı: 2 saniye sonra login'e yönlendir
                // header("refresh:2;url=login.php");
            }
        }
    } catch (Exception $e) {
        $message = "Bir hata oluştu. Lütfen tekrar deneyin.";
        $message_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTR | Kayıt Ol</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --bg: #f3f4f6;
            --text: #1f2937;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .register-card {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        header {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .sub-header {
            color: #6b7280;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            text-align: center;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #d1fae5;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: var(--text);
        }

        input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-sizing: border-box;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 1rem;
        }

        .btn:hover {
            background: var(--primary-hover);
        }

        .footer-text {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <header>Yeni Hesap</header>
        <p class="sub-header">VTR Randevu sistemine katılmak için formu doldurun.</p>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if ($message_type !== "success"): ?>
        <form action="" method="post">
            <div class="form-group">
                <label>Ad Soyad</label>
                <input type="text" name="kullanıcı-adı" placeholder="Adınız ve Soyadınız" required>
            </div>

            <div class="form-group">
                <label>E-posta</label>
                <input type="email" name="email" placeholder="ornek@mail.com" required>
            </div>

            <div class="form-group">
                <label>Telefon</label>
                <input type="text" name="tel" placeholder="05XX XXX XX XX" required>
            </div>
            
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="sifre" placeholder="••••••••" required>
            </div>

            <button type="submit" name="submit" class="btn">Kayıt Ol</button>
        </form>
        <?php else: ?>
            <a href="login.php" class="btn" style="display: block; text-align: center; text-decoration: none;">Şimdi Giriş Yap</a>
        <?php endif; ?>

        <div class="footer-text">
            Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a>
        </div>
    </div>

</body>
</html>