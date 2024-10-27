<?php
include 'db.php';

// Tambah data jenis masalah
if (isset($_POST['create'])) {
    $nama_masalah = $_POST['nama_masalah'];

    $stmt = $conn->prepare("INSERT INTO jenis_masalah (nama_masalah) VALUES (?)");
    $stmt->execute([$nama_masalah]);

    header("Location: jenis_masalah.php");
}

// Hapus data jenis masalah
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM jenis_masalah WHERE id_masalah = ?");
    $stmt->execute([$id]);

    header("Location: jenis_masalah.php");
}

// Ambil data untuk edit
$edit_state = false;
if (isset($_GET['edit'])) {
    $edit_state = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM jenis_masalah WHERE id_masalah = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update data jenis masalah
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_masalah = $_POST['nama_masalah'];

    $stmt = $conn->prepare("UPDATE jenis_masalah SET nama_masalah = ? WHERE id_masalah = ?");
    $stmt->execute([$nama_masalah, $id]);

    header("Location: jenis_masalah.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Jenis Masalah - Bimbingan Konseling</title>
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
    <h1>Kelola Jenis Masalah</h1>

    <form method="POST" action="jenis_masalah.php">
        <input type="hidden" name="id" value="<?= isset($row['id_masalah']) ? $row['id_masalah'] : '' ?>">
        <input type="text" name="nama_masalah" placeholder="Nama Masalah" value="<?= isset($row['nama_masalah']) ? $row['nama_masalah'] : '' ?>" required>

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
                <th>Nama Masalah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM jenis_masalah");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id_masalah'] ?></td>
                    <td><?= $row['nama_masalah'] ?></td>
                    <td>
                        <a href="jenis_masalah.php?edit=<?= $row['id_masalah'] ?>" class="edit_btn">Edit</a>
                        <a href="jenis_masalah.php?delete=<?= $row['id_masalah'] ?>" class="del_btn" onclick="return confirm('Apakah Anda yakin ingin menghapus jenis masalah ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
