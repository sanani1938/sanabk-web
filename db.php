<?php
$host = 'localhost';
$dbname = 'db_bimbingan_konseling';
$username = 'root';
$password = '';

// Membuat koneksi ke database
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>




