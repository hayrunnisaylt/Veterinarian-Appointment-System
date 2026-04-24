<?php
include 'db.php'; // Veritabanı bağlantını buradan alıyoruz

$randevuCollection = $db->randevular;

// Eklenecek randevu verileri
$yeniRandevular = [
    [
        'doktor' => 'Ertuğrul Asaf',
        'klinik' => 'Dahiliye',
        'tarih'  => '2026-05-15',
        'saat'   => '10:00',
        'durum'  => 'mevcut',
        'user_id'=> null
    ],
    [
        'doktor' => 'Fatma Erten',
        'klinik' => 'Cerrahi',
        'tarih'  => '2026-07-20',
        'saat'   => '09:00',
        'durum'  => 'mevcut',
        'user_id'=> null
    ],
    [
        'doktor' => 'Emir Barut',
        'klinik' => 'Cerrahi',
        'tarih'  => '2026-04-02',
        'saat'   => '12:30',
        'durum'  => 'mevcut',
        'user_id'=> null
    ],
    [
        'doktor' => 'ÖFS',
        'klinik' => 'Cerrahi',
        'tarih'  => '2026-04-08',
        'saat'   => '14:45',
        'durum'  => 'mevcut',
        'user_id'=> null
    ]
];

try {
    // Toplu ekleme işlemi
    $result = $randevuCollection->insertMany($yeniRandevular);
    echo "Başarıyla " . $result->getInsertedCount() . " adet randevu slotu eklendi!";
} catch (Exception $e) {
    echo "Hata oluştu: " . $e->getMessage();
}
?>