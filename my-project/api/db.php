<?php
require '../vendor/autoload.php';

$client = new MongoDB\Client(uri: "mongodb://localhost:27017");

try {
	$db = $client->veteriner_randevu;
    $usersCollection = $db->kullanicilar;
    $resultsCollection = $db->lab_sonuclari;
	$randevuCollection = $db->mevcut_randevular;
	$randevularCollection = $db->alınan_randevular;

	/*$newRandevu = [
		'tarih' => '2025-01-17',
		'saat' => '10:00',
		'doktor' => 'Dr. Nisa Sevinç',
		'klinik' => 'Küçük Dostlar Merkezi',
		'durum' => 'mevcut'
	];
	
	// Koleksiyona ekleyin
	$result = $randevuCollection->insertOne($newRandevu);*/

} catch (Exception $e) {
    echo "MongoDB bağlantısı hatası: " . $e->getMessage();
    exit;
}
?>
