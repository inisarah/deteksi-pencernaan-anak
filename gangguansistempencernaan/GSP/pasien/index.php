<?php
session_start();
require '../dbconnection/koneksi.php';

// Cek apakah user sudah login sebagai pasien
if (!isset($_SESSION['id_akun'])) {
    header("Location: ../login.php");
    exit;
}

$id_akun = $_SESSION['id_akun'];

// Ambil informasi penyakit dari database
$queryPenyakit = $conn->query("SELECT * FROM penyakit");
$penyakitList = $queryPenyakit->fetchAll(PDO::FETCH_ASSOC);

// Hitung jumlah riwayat konsultasi pasien berdasarkan id_akun
$queryRiwayat = $conn->prepare("SELECT COUNT(*) as total FROM rekam_medis WHERE id_akun = ?");
$queryRiwayat->execute([$id_akun]);
$riwayat = $queryRiwayat->fetch(PDO::FETCH_ASSOC);
$totalRiwayat = $riwayat['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">
    
    <!-- Sidebar -->
    <?php include '../assets/sidebar/sidebar_pasien.php'; ?>
    
    <!-- Main Content -->
    <main class="ml-64 p-6 w-full">
        <h1 class="text-2xl font-bold text-gray-700 mb-6">Dashboard Pasien</h1>
        
        <!-- Box Riwayat Konsultasi -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-700">Riwayat Konsultasi Anda</h2>
            <span class="bg-green-500 text-white text-xl font-bold px-4 py-2 rounded-lg"> <?= $totalRiwayat ?> Kali </span>
        </div>

        <!-- Informasi Penyakit -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Informasi Penyakit</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($penyakitList as $penyakit): ?>
                    <div class="border p-4 rounded-md shadow-md bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2"> <?= htmlspecialchars($penyakit['nama_penyakit']) ?> </h3>
                        <p class="text-gray-600"> <?= htmlspecialchars($penyakit['deskripsi']) ?> </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Tombol Konsultasi -->
        <div class="mt-6">
            <a href="konsultasifc.php" class="bg-blue-600 text-white font-medium px-6 py-3 rounded-lg shadow-md hover:bg-blue-700">
                Ayo Konsultasi
            </a>
        </div>
    </main>
</body>
</html>
