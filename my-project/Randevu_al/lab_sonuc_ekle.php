<?php
require 'db.php';

$kullaniciAdi = 'Hayrunnisa Yoltan';

$user = $usersCollection->findOne(['adSoyad' => $kullaniciAdi]);

if ($user) {
    $kullaniciId = $user['_id'];

    $tahliller = [
        [
            'kullanıcı_id' => $kullaniciId,
            'test_name' => 'Kan Tahlili',
            'sonuç' => 'Normal',
            'zaman' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp() * 1000)
        ],
        [
            'kullanıcı_id' => $kullaniciId,
            'test_name' => 'İdrar Tahlili',
            'sonuç' => 'Anormal',
            'zaman' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp() * 1000)
        ],
        [
            'kullanıcı_id' => $kullaniciId,
            'test_name' => 'Şeker Testi',
            'sonuç' => 'Yüksek',
            'zaman' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp() * 1000)
        ]
    ];

    try {
        $insertResult = $resultsCollection->insertMany($tahliller);
        echo $insertResult->getInsertedCount() . " laboratuvar sonucu başarıyla eklendi.";
    } catch (Exception $e) {
        echo "Hata oluştu: " . $e->getMessage();
    }
} else {
    echo "Belirtilen kullanıcı adıyla eşleşen bir kullanıcı bulunamadı.";
}
?>
