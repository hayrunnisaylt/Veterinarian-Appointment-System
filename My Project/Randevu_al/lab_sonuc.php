<?php
include 'db.php';

$username = $_POST['kullanıcı-adı'] ?? '';  
$password = $_POST['sifre'] ?? '';


if (empty($username) || empty($password)) {
    echo "<p style='text-transform:none'>Lütfen kullanıcı adı ve şifreyi girin.</p>";
	echo "<a href='../İndex/index.html' style='text-decoration: none;'>
            <div style='position: fixed; top: 170px; right: 400px; 
                        width: 30px; height: 30px; 
                        color: gray; 
                        text-align: center; line-height: 30px; 
                        font-size: 20px; border-radius: 50%; 
                        cursor: pointer;'>
                ×
            </div>
          </a>";
    exit;
}

$user = $usersCollection->findOne(['adSoyad' => $username]);

if ($user && password_verify($password, $user['sifre'])) {
    echo "<h3>Merhaba, " . htmlspecialchars($user['adSoyad']) . "!</h3>";
    echo "<p>Laboratuvar Sonuçlarınız:</p>";

    $labResults = $resultsCollection->find(['kullanıcı_id' => $user['_id']]);
    $labResultsArray = iterator_to_array($labResults);

    if (!empty($labResultsArray)) { 
        echo "<table border='1' style='width:100%; border-collapse: collapse; text-align: center;'>";
        echo "<thead>
                <tr>
                    <th>Test Adı</th>
                    <th>Sonuç</th>
                    <th>Tarih</th>
                </tr>
              </thead>";
        echo "<tbody>";

        foreach ($labResultsArray as $result) {
            $testName = htmlspecialchars($result['test_name']);
            $testResult = htmlspecialchars($result['sonuç']);
            
            if (isset($result['zaman'])) {
                $testDate = $result['zaman']->toDateTime()->format('Y-m-d H:i:s');
            } else {
                $testDate = "Bilinmiyor";
            }

            // Tablo satırı
            echo "<tr>
                    <td>{$testName}</td>
                    <td>{$testResult}</td>
                    <td>{$testDate}</td>
                  </tr>";
        }

        echo "</tbody>";
		echo "</table>";

    } else {
        echo "<p style='text-transform:none'>Henüz laboratuvar sonucu bulunmamaktadır.</p>";
    }
	echo "<a href='../İndex/index.html' style='text-decoration: none;'>
            <div style='position: fixed; top: 170px; right: 400px; 
                        width: 30px; height: 30px; 
                        color: gray; 
                        text-align: center; line-height: 30px; 
                        font-size: 20px; border-radius: 50%; 
                        cursor: pointer;'>
                ×
            </div>
          </a>";

} else {
    echo "<p style='text-transform:none'>Hatalı kullanıcı adı veya şifre. Lütfen tekrar deneyin.</p>";
	echo "<a href='../İndex/index.html' style='text-decoration: none;'>
            <div style='position: fixed; top: 170px; right: 400px; 
                        width: 30px; height: 30px; 
                        color: gray; 
                        text-align: center; line-height: 30px; 
                        font-size: 20px; border-radius: 50%; 
                        cursor: pointer;'>
                ×
            </div>
          </a>";
}

?>
