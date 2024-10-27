<?php
include 'db.php';

// Tambah data masalah siswa
if (isset($_POST['create'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_masalah = $_POST['id_masalah'];
    $tanggal_ditemukan = $_POST['tanggal_ditemukan'];

    $stmt = $conn->prepare("INSERT INTO masalah_siswa (id_siswa, id_masalah, tanggal_ditemukan) VALUES (?, ?, ?)");
    $stmt->execute([$id_siswa, $id_masalah, $tanggal_ditemukan]);

    header("Location: masalah_siswa.php");
}

// Hapus data masalah siswa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM masalah_siswa WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: masalah_siswa.php");
}

// Ambil data untuk edit
$edit_state = false;
if (isset($_GET['edit'])) {
    $edit_state = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM masalah_siswa WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update data masalah siswa
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $id_siswa = $_POST['id_siswa'];
    $id_masalah = $_POST['id_masalah'];
    $tanggal_ditemukan = $_POST['tanggal_ditemukan'];

    $stmt = $conn->prepare("UPDATE masalah_siswa SET id_siswa = ?, id_masalah = ?, tanggal_ditemukan = ? WHERE id = ?");
    $stmt->execute([$id_siswa, $id_masalah, $tanggal_ditemukan, $id]);

    header("Location: masalah_siswa.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Masalah Siswa - Bimbingan Konseling</title>
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
    <h1>Kelola Masalah Siswa</h1>

    <form method="POST" action="masalah_siswa.php">
        <input type="hidden" name="id" value="<?= isset($row['id']) ? $row['id'] : '' ?>">
        <select name="id_siswa" required>
            <option value="" disabled>Pilih Siswa</option>
            <?php
            $stmt = $conn->query("SELECT * FROM siswa");
            while ($siswa = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $siswa['id_siswa'] ?>" <?= isset($row['id_siswa']) && $row['id_siswa'] == $siswa['id_siswa'] ? 'selected' : '' ?>><?= $siswa['nama_siswa'] ?></option>
            <?php endwhile; ?>
        </select>
        <select name="id_masalah" required>
            <option value="" disabled>Pilih Jenis Masalah</option>
            <?php
            $stmt = $conn->query("SELECT * FROM jenis_masalah");
            while ($masalah = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <option value="<?= $masalah['id_masalah'] ?>" <?= isset($row['id_masalah']) && $row['id_masalah'] == $masalah['id_masalah'] ? 'selected' : '' ?>><?= $masalah['nama_masalah'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="date" name="tanggal_ditemukan" value="<?= isset($row['tanggal_ditemukan']) ? $row['tanggal_ditemukan'] : '' ?>" required>

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
                <th>Jenis Masalah</th>
                <th>Tanggal Ditemukan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT masalah_siswa.*, siswa.nama_siswa, jenis_masalah.nama_masalah FROM masalah_siswa 
                                  JOIN siswa ON masalah_siswa.id_siswa = siswa.id_siswa 
                                  JOIN jenis_masalah ON masalah_siswa.id_masalah = jenis_masalah.id_masalah");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama_siswa'] ?></td>
                    <td><?= $row['nama_masalah'] ?></td>
                    <td><?= $row['tanggal_ditemukan'] ?></td>
                    <td>
                        <a href="masalah_siswa.php?edit=<?= $row['id'] ?>" class="edit_btn">Edit</a>
                        <a href="masalah_siswa.php?delete=<?= $row['id'] ?>" class="del_btn" onclick="return confirm('Apakah Anda yakin ingin menghapus masalah siswa ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

   
