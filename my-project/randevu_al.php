<?php
session_start();

// Güvenlik kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';

// KRİTİK DÜZELTME: Koleksiyonları tanımlıyoruz
// db.php içinde $db değişkeninin tanımlı olduğunu varsayıyoruz
$randevuCollection = $db->randevular; 

if (isset($_POST['randevu_id'])) {
    $randevu_id = $_POST['randevu_id'];
    $user_id = $_SESSION['user_id'];
    
    // Formdan gelen diğer veriler (Opsiyonel: Eğer randevu dokümanında zaten varsa gerek kalmayabilir)
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];
    $doktor = $_POST['doktor'];
    $klinik = $_POST['klinik'];

    try {
        // 1. ADIM: Mevcut randevuyu güncelle (Kullanıcıya ata ve durumunu 'dolu' yap)
        $randevuCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($randevu_id)], 
            ['$set' => [
                'durum' => 'dolu',
                'user_id' => new MongoDB\BSON\ObjectId($user_id),
                // Eğer randevu dokümanında bu bilgiler eksikse diye ekliyoruz:
                'tarih' => $tarih,
                'saat' => $saat,
                'doktor' => $doktor,
                'klinik' => $klinik
            ]]
        );

        // Not: Eğer randevuları ayrı bir 'alinan_randevular' tablosunda tutmak istersen 
        // insertOne işlemini de buraya ekleyebilirsin ama genellikle tek tabloda 
        // user_id atamak yeterlidir.

        header("Location: home.php?mesaj=basarili"); 
        exit;

    } catch (Exception $e) {
        // Hata durumunda loglama veya mesaj
        die("Randevu alınırken bir hata oluştu: " . $e->getMessage());
    }
} else {
    // Eğer post verisi yoksa ana sayfaya dön
    header("Location: home.php");
    exit;
}
?>