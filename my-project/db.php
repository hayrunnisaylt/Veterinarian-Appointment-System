<?php
require 'vendor/autoload.php';

$uri = "mongodb+srv://dbHayrunnisa:1377Nisa.@cluster0.ibqgzo4.mongodb.net/?appName=Cluster0";

$client = new MongoDB\Client($uri);

$db = $client->selectDatabase('veteriner_randevu');
$usersCollection = $db->kullanicilar;
$randevuCollection = $db->randevular;
?>