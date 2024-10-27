<?php
include 'db.php';

// Tambah data siswa
if (isset($_POST['create'])) {
    $nama_siswa = $_POST['nama_siswa'];
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO siswa (nama_siswa, nis, kelas, jenis_kelamin, tanggal_lahir, alamat, nomor_telepon, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nama_siswa, $nis, $kelas, $jenis_kelamin, $tanggal_lahir, $alamat, $nomor_telepon, $email]);

    header("Location: siswa.php");
}

// Hapus data siswa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM siswa WHERE id_siswa = ?");
    $stmt->execute([$id]);

    header("Location: siswa.php");
}

// Ambil data untuk edit
$edit_state = false;
if (isset($_GET['edit'])) {
    $edit_state = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM siswa WHERE id_siswa = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update data siswa
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_siswa = $_POST['nama_siswa'];
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE siswa SET nama_siswa = ?, nis = ?, kelas = ?, jenis_kelamin = ?, tanggal_lahir = ?, alamat = ?, nomor_telepon = ?, email = ? WHERE id_siswa = ?");
    $stmt->execute([$nama_siswa, $nis, $kelas, $jenis_kelamin, $tanggal_lahir, $alamat, $nomor_telepon, $email, $id]);

    header("Location: siswa.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Siswa - Bimbingan Konseling</title>
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
    <h1>Kelola Data Siswa</h1>

    <form method="POST" action="siswa.php">
        <input type="hidden" name="id" value="<?= isset($row['id_siswa']) ? $row['id_siswa'] : '' ?>">
        <input type="text" name="nama_siswa" placeholder="Nama Siswa" value="<?= isset($row['nama_siswa']) ? $row['nama_siswa'] : '' ?>" required>
        <input type="text" name="nis" placeholder="NIS" value="<?= isset($row['nis']) ? $row['nis'] : '' ?>" required>
        <input type="text" name="kelas" placeholder="Kelas" value="<?= isset($row['kelas']) ? $row['kelas'] : '' ?>" required>
        <select name="jenis_kelamin" required>
            <option value="" disabled>Pilih Jenis Kelamin</option>
            <option value="L" <?= isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= isset($row['jenis_kelamin']) && $row['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
        </select>
        <input type="date" name="tanggal_lahir" value="<?= isset($row['tanggal_lahir']) ? $row['tanggal_lahir'] : '' ?>" required>
        <textarea name="alamat" placeholder="Alamat" required><?= isset($row['alamat']) ? $row['alamat'] : '' ?></textarea>
        <input type="text" name="nomor_telepon" placeholder="Nomor Telepon" value="<?= isset($row['nomor_telepon']) ? $row['nomor_telepon'] : '' ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= isset($row['email']) ? $row['email'] : '' ?>" required>

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
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $conn->query("SELECT * FROM siswa");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id_siswa'] ?></td>
                    <td><?= $row['nama_siswa'] ?></td>
                    <td><?= $row['nis'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                    <td><?= $row['tanggal_lahir'] ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td><?= $row['nomor_telepon'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <a href="siswa.php?edit=<?= $row['id_siswa'] ?>" class="edit_btn">Edit</a>
                        <a href="siswa.php?delete=<?= $row['id_siswa'] ?>" class="del_btn" onclick="return confirm('Apakah Anda yakin ingin menghapus siswa ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

