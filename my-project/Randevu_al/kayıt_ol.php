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
				
			require '../vendor/autoload.php';

			$client = new MongoDB\Client("mongodb://localhost:27017");
			$db = $client->veteriner_randevu;
			$usersCollection = $db->kullanicilar;
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
    			$adSoyad = $_POST['kullanıcı-adı'];
	    		$email = $_POST['email'];
    			$tel = $_POST['tel'];
	    		$sifre = $_POST['sifre'];

		    	$existingUser = $usersCollection->findOne(['email' => $email]);

    			if ($existingUser) {
        		echo "<div class='message'>
            		    <p>Bu kullanıcı zaten mevcut. Başka bir e-posta adresi deneyin.</p>
            	  		</div> <br>";
        		echo "<a href='kayıt_ol.php'><button class='btn'>Geri Dön</button></a>";
        		exit;
    			}
    			$hashed_sifre = password_hash($sifre, PASSWORD_DEFAULT);

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
					echo "<a href='giris.php'><button class='btn'>Giriş Yap</button></a>";
        		exit;
    			} else {
        			echo "Kayıt işlemi başarısız oldu.";
    			}
			}else{
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
					<input type="submit" class="btn" name="submit" id="submit" value="Kayıt Ol" required>
				</div>
				<div class="links">
					Mevcut bir hesabınız var mı? <a href="giris.php">Giriş Yap</a>
				</div>
			</form>
		</div>
		<?php } ?>
	</div>
</body>
</html>