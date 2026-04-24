<?php
error_reporting(E_ALL & ~E_DEPRECATED);
ob_start();
session_start();
include 'db.php';

$usersCollection = $db->kullanicilar; 
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $kullanici_adi = trim($_POST['kullanıcı-adı']);
    $sifre = trim($_POST['sifre']);

    // Hata yönetimini güçlendirmek için try-catch bloğu
    try {
        $user = $usersCollection->findOne(['adSoyad' => $kullanici_adi]);

        if ($user) {
            if ($sifre === $user['sifre']) { 
                $_SESSION['user_id'] = (string) $user['_id']; 
                $_SESSION['username'] = $user['adSoyad'];
                header("Location: home.php");
                exit;
            } else {
                $error_message = "HATA! Şifrenizi kontrol ediniz.";
            }
        } else {
            $error_message = "Kullanıcı bulunamadı!";
        }
    } catch (Exception $e) {
        $error_message = "Sistem Hatası: Lütfen daha sonra tekrar deneyiniz.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTR | Giriş Yap</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
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
        }

        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
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

        .error-box {
            background: #fee2e2;
            color: #b91c1c;
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
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

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <header>VTR Giriş</header>
        <p class="sub-header">Lütfen hesabınıza erişmek için bilgilerinizi girin.</p>

        <?php if ($error_message): ?>
            <div class="error-box"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label>Ad Soyad</label>
                <input type="text" name="kullanıcı-adı" placeholder="Örn: Ahmet Yılmaz" required>
            </div>
            
            <div class="form-group">
                <label>Şifre</label>
                <input type="password" name="sifre" placeholder="••••••••" required>
            </div>

            <input type="submit" name="submit" class="btn" value="Giriş Yap">
        </form>

        <div class="footer-text">
            Hesabınız yok mu? <a href="kayıt_ol.php">Hemen Kayıt Olun</a>
        </div>
    </div>

</body>
</html>