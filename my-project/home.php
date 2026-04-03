<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// Randevuları çekme
$randevular = $randevuCollection->find();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VTR | Randevu Al</title>
    <link rel="stylesheet" href="randevu_al.css">
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
            <?php
            require 'db.php';
            $user = $usersCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

            if (!$user) {
                session_unset();
                session_destroy();
                header("Location: giris.php");
                exit;
            }
            ?>

            <a href="logout.php"><button class="btn-çıkış">Çıkış Yap</button></a>
        </div>
    </div>
	</div>
    <main>
        <div class="main-box top">
            <div class="top">
                <div class="box">
                    <p>Hoş Geldiniz, <b><?php echo htmlspecialchars($user['adSoyad']); ?>!</b></p>
                </div>
                <div class="box">
                    <a href="#" ><button id="randevuAlBtn" onclick="openModal()" class="btn-randevu"><b>Randevu Al</b></button></a>
                </div>
				<div id="randevuAlModal" class="modal">
    				<div class="modal-content">
        				<span class="close">&times;</span>
        				<h3>Mevcut Randevular:</h3>
        				<div class="box form-box">
							<form action="randevu_al.php" method="post">
    						<div class="field input">
        						<label for="randevu">Randevu Seçin</label>
        						<select name="randevu_id" id="randevu" required>
            						<option value="" disabled selected>Bir seçenek seçin</option>
            					<?php
            					if (isset($_SESSION['user_id'])) {
                					$user_id = $_SESSION['user_id'];
                					
                					$randevular = $randevuCollection->find(['durum' => 'mevcut']);
                					$randevularArray = iterator_to_array($randevular);
                					if (empty($randevularArray)) {
                    					echo "<p>Şu anda boş randevu bulunmamaktadır.</p>";
                					} else {
                    					foreach ($randevularArray as $randevu) {
                        					$randevuTarihi = isset($randevu['tarih']) ? $randevu['tarih'] : 'Tarih mevcut değil';
                        					$randevuSaat = isset($randevu['saat']) ? $randevu['saat'] : 'Saat mevcut değil';
                        					$doktorAdi = isset($randevu['doktor']) ? $randevu['doktor'] : 'Doktor bilgisi mevcut değil';
											$KlinikAdi = isset($randevu['klinik']) ? $randevu['klinik'] : 'Klinik bilgisi mevcut değil';
                        					echo "<option value='".$randevu['_id']."' 
                               					data-tarih='$randevuTarihi' 
                               					data-saat='$randevuSaat' 
                               					data-doktor='$doktorAdi'
												data-klinik='$KlinikAdi'>
                               					$randevuTarihi - $randevuSaat - $doktorAdi - $KlinikAdi
                               					</option>";
                    						}
                					}
            					}
            					?>
        						</select>
    						</div>

    						<input type="hidden" name="tarih" id="tarih" required>
    						<input type="hidden" name="saat" id="saat" required>
   							<input type="hidden" name="doktor" id="doktor" required>
							<input type="hidden" name="klinik" id="klinik" required>

    						<div class="field input">
        						<input type="submit" class="btn" name="submit" id="submit" value="Randevuyu Al" required>
    						</div>
							</form>

							<script>
						    // JavaScript to handle the option selection and populate hidden fields
						    document.getElementById('randevu').addEventListener('change', function() {
        					var selectedOption = this.options[this.selectedIndex];
        					document.getElementById('tarih').value = selectedOption.getAttribute('data-tarih');
        					document.getElementById('saat').value = selectedOption.getAttribute('data-saat');
        					document.getElementById('doktor').value = selectedOption.getAttribute('data-doktor');
							document.getElementById('klinik').value = selectedOption.getAttribute('data-klinik');
    						});
						</script>

						</div>
            		</div>
				</div>
			</div>
            <div class="bottom">
    			<div class="box">
        			<h3>Randevularınız:</h3>
        			<div class="randevu-listesi">
            			<?php

            			$randevular = $randevularCollection->find(['user_id' => new MongoDB\BSON\ObjectId($_SESSION['user_id'])]);

            			if ($randevular->isDead()) {
                			echo "<p>Henüz almış bir randevunuz yok.</p>";
            			} else {
                		foreach ($randevular as $randevu) {

                    		$randevuTarihi = isset($randevu['tarih']) ? $randevu['tarih'] : 'Tarih mevcut değil';
                    		$randevuSaat = isset($randevu['saat']) ? $randevu['saat'] : 'Saat mevcut değil';
                    		$doktorAdi = isset($randevu['doktor']) ? $randevu['doktor'] : 'Doktor bilgisi mevcut değil';
							$KlinikAdi = isset($randevu['klinik']) ? $randevu['klinik'] : 'Klinik bilgisi mevcut değil';
                    
							echo "<p style='margin:10px'>
							$doktorAdi 
							<hr>
							$randevuTarihi / $randevuSaat - $KlinikAdi
							</p>";
							
                		}
						
            			}
            			?>
        			</div>
    			</div>
			</div>
		</div>
    </main>
	<script src="randevu_al.js"></script>
</body>
</html>

</body>

