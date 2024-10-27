<?php
include 'db.php';

// Ambil data untuk dashboard
$siswa_count = $conn->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
$konselor_count = $conn->query("SELECT COUNT(*) FROM konselor")->fetchColumn();
$sesi_count = $conn->query("SELECT COUNT(*) FROM sesi_konseling")->fetchColumn();
$jadwal_count = $conn->query("SELECT COUNT(*) FROM jadwal_konseling")->fetchColumn();
$masalah_count = $conn->query("SELECT COUNT(*) FROM jenis_masalah")->fetchColumn();
$masalah_siswa_count = $conn->query("SELECT COUNT(*) FROM masalah_siswa")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Bimbingan Konseling</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <a href="index.php">Dashboard</a>
    <a href="siswa.php">Kelola Siswa</a>
    <a href="konselor.php">Kelola Konselor</a>
    <a href="sesi_konseling.php">Kelola Sesi Konseling</a>
    <a href="jadwal_konseling.php">Kelola Jadwal Konseling</a>
    <a href="jenis_masalah.php">Kelola Jenis Masalah</a>
    <a href="masalah_siswa.php">Kelola Masalah Siswa</a>
</div>
                               
<div class="main-content">
    <h1>Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card">Siswa: <?= $siswa_count ?></div>
        <div class="card">Konselor: <?= $konselor_count ?></div>
        <div class="card">Sesi Konseling: <?= $sesi_count ?></div>
        <div class="card">Jadwal Konseling: <?= $jadwal_count ?></div>
        <div class="card">Jenis Masalah: <?= $masalah_count ?></div>
        <div class="card">Masalah Siswa: <?= $masalah_siswa_count ?></div>
    </div>
</div>

</body>
</html>
