<?php
require '../vendor/autoload.php';

// MongoDB bağlantısını kurun
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->veteriner_randevu;
$collection = $db->veterinerler;

// Yeni veteriner verisini tanımlıyoruz
/*newVeteriner = [
    'adSoyad' => 'Dr. Mehmet Yılmaz',  // Veterinerin adı
    'status' => 'nöbetçi',         // Veterinerin nöbetçi durumu
    'telefon' => '5348675253',     // Veterinerin telefon numarası
    'lokasyon' => 'İstanbul',
	'klinik' => 'Dost Patiler'
];

$result = $collection->insertOne($newVeteriner);*/

$query = ['status' => 'nöbetçi']; // Sadece 'nöbetçi' olan veterinerleri aradım.
$cursor = $collection->find($query);

$veterinerler = [];
foreach ($cursor as $doc) {
    $veterinerler[] = [
        'adSoyad' => $doc['adSoyad'],
		'klinik' => $doc['klinik'],
		'lokasyon' => $doc['lokasyon'],
    ];
}

// JSON formatına döndürdüm.
echo json_encode($veterinerler);
?>
