<?php
require 'vendor/autoload.php';

// Render'a eklediğin Environment Variable'ı oku (En güvenli yöntem)
$mongoUri = getenv('MANGO_DB');

// Eğer Environment Variable yoksa (Local test için) manuel linkini buraya koyabilirsin
// Ama Render'da mutlaka ENV kullanmalısın!
if (!$mongoUri) {
    $mongoUri = "mongodb+srv://dbHayrunnisa:1277Nisa.@cluster0.ibqgzo4.mongodb.net/?appName=Cluster0";
}

try {
    $client = new MongoDB\Client($mongoUri);
    // Veritabanı adını Atlas'taki adıyla eşleştir (Örn: veteriner_db)
    $db = $client->selectDatabase('veteriner_db'); 
} catch (Exception $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>