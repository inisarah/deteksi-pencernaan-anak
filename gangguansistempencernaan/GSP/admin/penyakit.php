<?php
session_start();
require '../dbconnection/koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data penyakit
$query = $conn->query("SELECT * FROM penyakit");
$penyakit = $query->fetchAll(PDO::FETCH_ASSOC);

// Tambah atau Edit Penyakit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_penyakit = isset($_POST['id_penyakit']) ? $_POST['id_penyakit'] : '';
    $kode_penyakit = htmlspecialchars($_POST['kode_penyakit']);
    $nama_penyakit = htmlspecialchars($_POST['nama_penyakit']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $solusi = htmlspecialchars($_POST['solusi']);

    if ($id_penyakit == "") {
        // Cek apakah kode penyakit sudah ada
        $cekKode = $conn->prepare("SELECT * FROM penyakit WHERE kode_penyakit = ?");
        $cekKode->execute([$kode_penyakit]);

        if ($cekKode->rowCount() > 0) {
            echo "<script>alert('Kode Penyakit sudah digunakan!');</script>";
        } else {
            $stmt = $conn->prepare("INSERT INTO penyakit (kode_penyakit, nama_penyakit, deskripsi, solusi) VALUES (?, ?, ?, ?)");
            $stmt->execute([$kode_penyakit, $nama_penyakit, $deskripsi, $solusi]);
            echo "<script>alert('Penyakit berhasil ditambahkan!'); window.location.href='penyakit.php';</script>";
        }
    } else {
        $stmt = $conn->prepare("UPDATE penyakit SET kode_penyakit=?, nama_penyakit=?, deskripsi=?, solusi=? WHERE id_penyakit=?");
        $stmt->execute([$kode_penyakit, $nama_penyakit, $deskripsi, $solusi, $id_penyakit]);
        echo "<script>alert('Penyakit berhasil diperbarui!'); window.location.href='penyakit.php';</script>";
    }
}

// Hapus Penyakit
if (isset($_GET['hapus'])) {
    $id_penyakit = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM penyakit WHERE id_penyakit=?");
    $stmt->execute([$id_penyakit]);
    echo "<script>alert('Penyakit berhasil dihapus!'); window.location.href='penyakit.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Data penyakit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-orange-100 flex">
    <?php include '../assets/sidebar/sidebar_admin.php'; ?>

    <main class="ml-64 p-6 w-full">
        <h1 class="text-2xl font-bold text-orange-700 mb-6"> Data penyakit</h1>

        <!-- Tabel penyakit -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-4">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Kode Penyakit</th>
                        <th class="p-3 text-left">Nama Penyakit</th>
                        <th class="p-3 text-left">Solusi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penyakit as $datapenyakit): ?>
                        <tr class="border-b hover:bg-orange-100">
                            <td class="p-3"><?php echo $datapenyakit['id_penyakit']; ?></td>
                            <td class="p-3"><?php echo $datapenyakit['kode_penyakit']; ?></td>
                            <td class="p-3"><?php echo $datapenyakit['nama_penyakit']; ?></td>
                            <td class="p-3"><?php echo $datapenyakit['solusi']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>