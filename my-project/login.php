<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ob_start();
session_start();
include 'db.php';

$usersCollection = $db->kullanicilar; 
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $kullanici_adi = trim($_POST['kullanıcı-adı']);
    $sifre = trim($_POST['sifre']);

    $user = $usersCollection->findOne(['adSoyad' => $kullanici_adi]);

    if ($user) {
        // DİKKAT: password_verify'ı SİLDİK, direkt karşılaştırıyoruz
        if ($sifre === $user['sifre']) { 
            $_SESSION['user_id'] = (string) $user['_id']; 
            $_SESSION['username'] = $user['adSoyad'];
            header("Location: home.php");
            exit;
        } else {
            $error_message = "X HATA: Şifre eşleşmedi! DB'deki veri: " . $user['sifre'] . " | Senin girdiğin: " . $sifre;
        }
    } else {
        $error_message = "X HATA: Kullanıcı bulunamadı!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VTR | Giriş Testi</title>
    <link rel="stylesheet" href="Randevu_al/randevu_al.css">
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Giriş Yap (Test Modu)</header>
            <?php if ($error_message) echo "<p style='color:red'>$error_message</p>"; ?>
            <form action="" method="post">
                <div class="input"><label>Ad Soyad</label><input type="text" name="kullanıcı-adı" required></div>
                <div class="input"><label>Şifre</label><input type="password" name="sifre" required></div>
                <input type="submit" name="submit" class="btn" value="Giriş">
            </form>
        </div>
    </div>
</body>
</html>