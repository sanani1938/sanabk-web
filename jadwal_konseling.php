<?php
include 'db.php';

// Tambah data jadwal konseling
if (isset($_POST['create'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_konselor = $_POST['id_konselor'];
    $tanggal_jadwal = $_POST['tanggal_jadwal'];
    $waktu_jadwal = $_POST['waktu_jadwal'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO jadwal_konseling (id_siswa, id_konselor, tanggal_jadwal, waktu_jadwal, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_siswa, $id_konselor, $tanggal_jadwal, $waktu_jadwal, $status]);

    header("Location: jadwal_konseling.php");
}

// Hapus data jadwal konseling
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM jadwal_konseling WHERE id_jadwal = ?");
    $stmt->execute([$id]);

    header("Location: jadwal_konseling.php");
}

// Ambil data untuk edit
$edit_state = false;
if (isset($_GET['edit'])) {
    $edit_state = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM jadwal_konseling WHERE id_jadwal = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update data jadwal konseling
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_siswa = $_POST['id_siswa'];
    $id_konselor = $_POST['id_konselor'];
    $tanggal_jadwal = $_POST['tanggal_jadwal'];
    $waktu_jadwal = $_POST['waktu_jadwal'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE jadwal_konseling SET id_siswa = ?, id_konselor = ?, tanggal_jadwal = ?, waktu_jadwal = ?, status = ? WHERE id_jadwal = ?");
    $stmt->execute([$id_siswa, $id_konselor, $tanggal_jadwal, $waktu_jadwal, $status, $id]);

    header("Location: jadwal_konseling.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Jadwal Konseling - Bimbingan Konseling</title>
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
    <h1>Kelola Data Jadwal Konseling</h1>

    <form method="POST" action="jadwal_konseling.php">
        <input type="hidden" name="id" value="<?= isset($row['id_jadwal']) ? $row['id_jadwal'] : '' ?>">
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
        <input type="date" name="tanggal_jadwal" value="<?= isset($row['tanggal_jadwal']) ? $row['tanggal_jadwal'] : '' ?>" required>
        <input type="time" name="waktu_jadwal" value="<?= isset($row['waktu_jadwal']) ? $row['waktu_jadwal'] : '' ?>" required>
        <select name="status" required>
            <option value="Dijadwalkan" <?= isset($row['status']) && $row['status'] == 'Dijadwalkan' ? 'selected' : '' ?>>Dijadwalkan</option>
            <option value="Selesai" <?= isset($row['status']) && $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="Dibatalkan" <?= isset($row['status']) && $row['status'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
        </select>

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
                <th>Tanggal Jadwal</th>
                <th>Waktu Jadwal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT jadwal_konseling.*, siswa.nama_siswa, konselor.nama_konselor FROM jadwal_konseling 
                                  JOIN siswa ON jadwal_konseling.id_siswa = siswa.id_siswa 
                                  JOIN konselor ON jadwal_konseling.id_konselor = konselor.id_konselor");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id_jadwal'] ?></td>
                    <td><?= $row['nama_siswa'] ?></td>
                    <td><?= $row['nama_konselor'] ?></td>
                    <td><?= $row['tanggal_jadwal'] ?></td>
                    <td><?= $row['waktu_jadwal'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="jadwal_konseling.php?edit=<?= $row['id_jadwal'] ?>" class="edit_btn">Edit</a>
                        <a href="jadwal_konseling.php?delete=<?= $row['id_jadwal'] ?>" class="del_btn" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

