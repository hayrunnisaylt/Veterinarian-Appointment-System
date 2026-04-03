<?php
require 'vendor/autoload.php';

// BURASI ÇOK KRİTİK: 'localhost' yazısını tamamen silmelisin!
// Atlas'tan aldığın gerçek bağlantı linkini buraya yapıştır.
$uri = "mongodb+srv://dbHayrunnisa:1377Nisa.@cluster0.ibqgzo4.mongodb.net/?appName=Cluster0;

try {
    $client = new MongoDB\Client($uri);
    // Veritabanı adını Atlas'ta ne verdiysen onu yaz (Örn: veteriner_db)
    $db = $client->selectDatabase('veteriner_db'); 
} catch (Exception $e) {
    die("Bağlantı Hatası: " . $e->getMessage());
}
?>