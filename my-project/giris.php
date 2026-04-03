<?php
session_start();

if (isset($_SESSION['user_id'])) {
    echo "Kullanıcı zaten giriş yapmış: " . $_SESSION['user_email'];
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>VTR | Randevu Al</title>
	<link rel="stylesheet" href="randevu_al.css">
</head>
<body>
	<div class="container">
		<div class="box form-box">
		<?php
		include 'db.php';
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
    		$kullanici_adi = $_POST['kullanıcı-adı'];
    		$sifre = $_POST['sifre'];
   			$user = $usersCollection->findOne(['adSoyad' => $kullanici_adi]);

		    if ($user && password_verify( $sifre,$user['sifre'])) {
        		$_SESSION['user_id'] = (string) $user['_id']; 
        		$_SESSION['user_email'] = $user['email'];
        		header("Location: home.php");
        		exit;
			} else {
        		echo "<div class='message'>
						<p>Geçersiz email veya şifre!</p>
					  </div> <br>";
				echo "<a href='giris.php'><button class='btn'>Geri Dön</button></a>";
    		}
		}else{
		?>

			<header>Giriş Yap</header>
			<form action="" method="post">
				<div class="fiead input">
					<label for="kullanıcı-adı">Kullanıcı Ad - Soyad</label>
					<input type="text" name="kullanıcı-adı" id="kullanıcı-adı" required>
				</div>
				<div class="fiead input">
					<label for="sifre">Şifre</label>
					<input type="password" name="sifre" id="sifre" required>
				</div>
				<div class="fiead input">
					<input type="submit" class="btn" name="submit" id="submit" value="Giriş" required>
				</div>
				<div class="links">
					Hesabınız yok mu? <a href="kayıt_ol.php">Kayıt Ol</a>
				</div>
			</form>
		</div>
		<?php } ?> 
	</div>
</body>
</html>