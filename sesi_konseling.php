<?php
include 'db.php';

// Tambah data sesi konseling
if (isset($_POST['create'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_konselor = $_POST['id_konselor'];
    $tanggal_sesi = $_POST['tanggal_sesi'];
    $topik = $_POST['topik'];
    $catatan = $_POST['catatan'];
    $tindakan_lanjut = $_POST['tindakan_lanjut'];

    $stmt = $conn->prepare("INSERT INTO sesi_konseling (id_siswa, id_konselor, tanggal_sesi, topik, catatan, tindakan_lanjut) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_siswa, $id_konselor, $tanggal_sesi, $topik, $catatan, $tindakan_lanjut]);

    header("Location: sesi_konseling.php");
}

// Hapus data sesi konseling
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM sesi_konseling WHERE id_sesi = ?");
    $stmt->execute([$id]);

    header("Location: sesi_konseling.php");
}

// Ambil data untuk edit
$edit_state = false;
if (isset($_GET['edit'])) {
    $edit_state = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM sesi_konseling WHERE id_sesi = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update data sesi konseling
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_siswa = $_POST['id_siswa'];
    $id_konselor = $_POST['id_konselor'];
    $tanggal_sesi = $_POST['tanggal_sesi'];
    $topik = $_POST['topik'];
    $catatan = $_POST['catatan'];
    $tindakan_lanjut = $_POST['tindakan_lanjut'];

    $stmt = $conn->prepare("UPDATE sesi_konseling SET id_siswa = ?, id_konselor = ?, tanggal_sesi = ?, topik = ?, catatan = ?, tindakan_lanjut = ? WHERE id_sesi = ?");
    $stmt->execute([$id_siswa, $id_konselor, $tanggal_sesi, $topik, $catatan, $tindakan_lanjut, $id]);

    header("Location: sesi_konseling.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Sesi Konseling - Bimbingan Konseling</title>
    <link rel="stylesheet" href="style.css">
    <head>
    <title>Kelola Sesi Konseling - Bimbingan Konseling</title>
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
    <h1>Kelola Data Sesi Konseling</h1>

    <form method="POST" action="sesi_konseling.php">
        <input type="hidden" name="id" value="<?= isset($row['id_sesi']) ? $row['id_sesi'] : '' ?>">
        <select name="id_siswa" required>
            <option value="" disabled>Pilih Siswa</option>
            <?php
            $stmt = $conn->query("SELECT * FROM siswa");
            while ($siswa = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $siswa['id_siswa'] ?>" <?= isset($row['id_siswa']) && $row['id_siswa'] == $siswa['id_siswa'] ? 'selected' : '' ?>><?= $siswa['nama_siswa'] ?></option>
            <?php endwhile; ?>
        </select>
        <select name="id_konselor" required>
            <option value="" disabled>Pilih Konselor</option>
            <?php
            $stmt = $conn->query("SELECT * FROM konselor");
            while ($konselor = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $konselor['id_konselor'] ?>" <?= isset($row['id_konselor']) && $row['id_konselor'] == $konselor['id_konselor'] ? 'selected' : '' ?>><?= $konselor['nama_konselor'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="date" name="tanggal_sesi" value="<?= isset($row['tanggal_sesi']) ? $row['tanggal_sesi'] : '' ?>" required>
        <input type="text" name="topik" placeholder="Topik" value="<?= isset($row['topik']) ? $row['topik'] : '' ?>" required>
        <textarea name="catatan" placeholder="Catatan" required><?= isset($row['catatan']) ? $row['catatan'] : '' ?></textarea>
        <textarea name="tindakan_lanjut" placeholder="Tindakan Lanjut" required><?= isset($row['tindakan_lanjut']) ? $row['tindakan_lanjut'] : '' ?></textarea>

        <?php if ($edit_state): ?>
            <button type="submit" name="update" class="btn">Update</button>
        <?php else: ?>
            <button type="submit" name="create" class="btn">Tambah</button>
        <?php endif ?>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Siswa</th>
                <th>Konselor</th>
                <th>Tanggal Sesi</th>
                <th>Topik</th>
                <th>Catatan</th>
                <th>Tindakan Lanjut</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT sesi_konseling.*, siswa.nama_siswa, konselor.nama_konselor FROM sesi_konseling 
                                  JOIN siswa ON sesi_konseling.id_siswa = siswa.id_siswa 
                                  JOIN konselor ON sesi_konseling.id_konselor = konselor.id_konselor");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id_sesi'] ?></td>
                    <td><?= $row['nama_siswa'] ?></td>
                    <td><?= $row['nama_konselor'] ?></td>
                    <td><?= $row['tanggal_sesi'] ?></td>
                    <td><?= $row['topik'] ?></td>
                    <td><?= $row['catatan'] ?></td>
                    <td><?= $row['tindakan_lanjut'] ?></td>
                    <td>
                        <a href="sesi_konseling.php?edit=<?= $row['id_sesi'] ?>" class="edit_btn">Edit</a>
                        <a href="sesi_konseling.php?delete=<?= $row['id_sesi'] ?>" class="del_btn" onclick="return confirm('Apakah Anda yakin ingin menghapus sesi ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

