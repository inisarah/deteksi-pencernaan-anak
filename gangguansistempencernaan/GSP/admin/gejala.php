<?php
session_start();
require '../dbconnection/koneksi.php';

// Cek apakah datagejala adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data gejala
$query = $conn->query("SELECT * FROM gejala");
$gejala = $query->fetchAll(PDO::FETCH_ASSOC);

// Tambah atau Edit Gejala
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_gejala = isset($_POST['id_gejala']) ? $_POST['id_gejala'] : '';
    $kode_gejala = htmlspecialchars($_POST['kode_gejala']);
    $nama_gejala = htmlspecialchars($_POST['nama_gejala']);

    if ($id_gejala == "") {
        // Cek apakah kode gejala sudah ada
        $cekKode = $conn->prepare("SELECT * FROM gejala WHERE kode_gejala = ?");
        $cekKode->execute([$kode_gejala]);

        if ($cekKode->rowCount() > 0) {
            echo "<script>alert('Kode Gejala sudah digunakan!');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO gejala (kode_gejala, nama_gejala) VALUES (?, ?)");
            $stmt->execute([$kode_gejala, $nama_gejala]);
            echo "<script>alert('Gejala berhasil ditambahkan!'); window.location.href='gejala.php';</script>";
        }
    } else {
        $stmt = $conn->prepare("UPDATE gejala SET kode_gejala=?, nama_gejala=? WHERE id_gejala=?");
        $stmt->execute([$kode_gejala, $nama_gejala, $id_gejala]);
        echo "<script>alert('Gejala berhasil diperbarui!'); window.location.href='gejala.php';</script>";
    }
}

// Hapus Gejala
if (isset($_GET['hapus'])) {
    $id_gejala = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM gejala WHERE id_gejala=?");
    $stmt->execute([$id_gejala]);
    echo "<script>alert('Gejala berhasil dihapus!'); window.location.href='gejala.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses gejala</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-orange-100 flex">
    <?php include '../assets/sidebar/sidebar_admin.php'; ?>

    <main class="ml-64 p-6 w-full">
        <h1 class="text-2xl font-bold text-orange-700 mb-6"> Data Gejala</h1>

        <!-- Tabel gejala -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-4">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Kode Gejala</th>
                        <th class="p-3 text-left">Nama Gejala</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gejala as $datagejala): ?>
                        <tr class="border-b hover:bg-orange-100">
                            <td class="p-3"><?php echo $datagejala['id_gejala']; ?></td>
                            <td class="p-3"><?php echo $datagejala['kode_gejala']; ?></td>
                            <td class="p-3"><?php echo $datagejala['nama_gejala']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>