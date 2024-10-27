<?php
include 'db.php';

// Tambah data konselor
if (isset($_POST['create'])) {
    $nama_konselor = $_POST['nama_konselor'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];
    $spesialisasi = $_POST['spesialisasi'];

    $stmt = $conn->prepare("INSERT INTO konselor (nama_konselor, nomor_telepon, email, spesialisasi) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama_konselor, $nomor_telepon, $email, $spesialisasi]);

    header("Location: konselor.php");
}

// Hapus data konselor
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM konselor WHERE id_konselor = ?");
    $stmt->execute([$id]);

    header("Location: konselor.php");
}

// Ambil data untuk edit
$edit_state = false;
if (isset($_GET['edit'])) {
    $edit_state = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM konselor WHERE id_konselor = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update data konselor
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_konselor = $_POST['nama_konselor'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];
    $spesialisasi = $_POST['spesialisasi'];

    $stmt = $conn->prepare("UPDATE konselor SET nama_konselor = ?, nomor_telepon = ?, email = ?, spesialisasi = ? WHERE id_konselor = ?");
    $stmt->execute([$nama_konselor, $nomor_telepon, $email, $spesialisasi, $id]);

    header("Location: konselor.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Konselor - Bimbingan Konseling</title>
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
    <h1>Kelola Data Konselor</h1>

    <form method="POST" action="konselor.php">
        <input type="hidden" name="id" value="<?= isset($row['id_konselor']) ? $row['id_konselor'] : '' ?>">
        <input type="text" name="nama_konselor" placeholder="Nama Konselor" value="<?= isset($row['nama_konselor']) ? $row['nama_konselor'] : '' ?>" required>
        <input type="text" name="nomor_telepon" placeholder="Nomor Telepon" value="<?= isset($row['nomor_telepon']) ? $row['nomor_telepon'] : '' ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= isset($row['email']) ? $row['email'] : '' ?>" required>
        <textarea name="spesialisasi" placeholder="Spesialisasi" required><?= isset($row['spesialisasi']) ? $row['spesialisasi'] : '' ?></textarea>

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
                <th>Nama Konselor</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Spesialisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM konselor");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id_konselor'] ?></td>
                    <td><?= $row['nama_konselor'] ?></td>
                    <td><?= $row['nomor_telepon'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['spesialisasi'] ?></td>
                    <td>
                        <a href="konselor.php?edit=<?= $row['id_konselor'] ?>" class="edit_btn">Edit</a>
                        <a href="konselor.php?delete=<?= $row['id_konselor'] ?>" class="del_btn" onclick="return confirm('Apakah Anda yakin ingin menghapus konselor ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
