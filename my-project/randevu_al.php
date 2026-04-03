<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db.php';


if (isset($_POST['randevu_id'])) {
    $randevu_id = $_POST['randevu_id'];
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];
    $doktor = $_POST['doktor'];
	$klinik = $_POST['klinik'];

    $randevuCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($randevu_id)], 
        ['$set' => ['durum' => 'dolu']]
    );

    $user_id = $_SESSION['user_id'];
    $randevularCollection->insertOne([
        'user_id' => new MongoDB\BSON\ObjectId($user_id),
        'randevu_id' => new MongoDB\BSON\ObjectId($randevu_id),
        'tarih' => $tarih,
        'saat' => $saat,
        'doktor' => $doktor,
		'klinik' => $klinik
    ]);


    header("Location: home.php"); 
    exit;
}
?>
