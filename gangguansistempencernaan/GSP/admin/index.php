<?php
session_start();
require '../dbconnection/koneksi.php'; // Hubungkan ke database

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Ambil data jumlah penyakit, gejala, pengguna, dan rekam medis
$queryPenyakit = $conn->query("SELECT COUNT(*) as total FROM penyakit");
$queryGejala = $conn->query("SELECT COUNT(*) as total FROM gejala");
$queryPengguna = $conn->query("SELECT COUNT(*) as total FROM tabel_akun");
$queryRekamMedis = $conn->query("SELECT COUNT(*) as total FROM rekam_medis");

$totalPenyakit = $queryPenyakit->fetch(PDO::FETCH_ASSOC)['total'];
$totalGejala = $queryGejala->fetch(PDO::FETCH_ASSOC)['total'];
$totalPengguna = $queryPengguna->fetch(PDO::FETCH_ASSOC)['total'];
$totalRekamMedis = $queryRekamMedis->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-blue-100 flex font-sans">

    <!-- Sidebar -->
    <?php include '../assets/sidebar/sidebar_admin.php'; ?>

    <!-- Main Content -->
    <main class="ml-64 p-6 w-full">
        <h1 class="text-3xl font-bold text-orange-700 mb-6 text-center">Dashboard Admin</h1>

        <!-- Informasi tentang Gangguan Pencernaan -->
        <div class="bg-white shadow-lg p-6 rounded-lg mb-6 flex items-center">
            <img src="../assets/gambar/gsp4.jpg" alt="Gangguan Pencernaan" class="w-20 h-20 mr-4">
            <div>
                <h2 class="text-xl font-semibold mb-2 text-orange-600">Apa itu Gangguan Pencernaan pada Anak?</h2>
                <p class="text-gray-700">
                Gangguan pencernaan pada anak adalah masalah yang memengaruhi saluran pencernaan (mulai dari mulut hingga anus), mengganggu proses penyerapan nutrisi, pencernaan makanan, atau pembuangan sisa metabolisme. Kondisi ini umum terjadi pada anak-anak karena sistem pencernaan mereka masih berkembang atau akibat faktor eksternal seperti infeksi atau pola makan.
                </p>
            </div>
        </div>
        <div class="bg-white shadow-lg p-6 rounded-lg mb-6 flex items-center">
            <img src="../assets/gambar/gsp3.jpg" alt="Gangguan Pencernaan" class="w-20 h-20 mr-4">
            <div>
                <h2 class="text-xl font-semibold mb-2 text-orange-600">Apa itu Gangguan Pencernaan pada Anak?</h2>
                <p class="text-gray-700">
                Gangguan pencernaan pada anak sering kali bisa diatasi dengan perawatan rumahan, tetapi perlu kewaspadaan terhadap gejala yang mengarah ke kondisi gawat darurat. Ayo Konsultasi  untuk diagnosis dan penanganan tepat!
                </p>
            </div>
        </div>

        <!-- Statistik dengan desain lucu -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Box Penyakit -->
            <div class="bg-yellow-300 text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                <i class="fas fa-virus fa-3x"></i>
                <h2 class="text-lg font-semibold mt-2">Total Penyakit</h2>
                <p class="text-3xl font-bold text-gray-800"><?php echo $totalPenyakit; ?></p>
            </div>

            <!-- Box Gejala -->
            <div class="bg-red-400 text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                <i class="fas fa-head-side-cough fa-3x"></i>
                <h2 class="text-lg font-semibold mt-2">Total Gejala</h2>
                <p class="text-3xl font-bold text-gray-800"><?php echo $totalGejala; ?></p>
            </div>

            <!-- Box Pengguna -->
            <div class="bg-green-400 text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                <i class="fas fa-users fa-3x"></i>
                <h2 class="text-lg font-semibold mt-2">Total Pengguna</h2>
                <p class="text-3xl font-bold text-gray-800"><?php echo $totalPengguna; ?></p>
            </div>

            <!-- Box Rekam Medis -->
            <div class="bg-blue-400 text-white p-6 rounded-lg shadow-lg flex flex-col items-center">
                <i class="fas fa-file-medical fa-3x"></i>
                <h2 class="text-lg font-semibold mt-2">Total Rekam Medis</h2>
                <p class="text-3xl font-bold text-gray-800"><?php echo $totalRekamMedis; ?></p>
            </div>
        </div>
    </main>

</body>
</html>
