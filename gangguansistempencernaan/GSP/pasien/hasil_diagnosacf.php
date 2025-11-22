<?php
session_start();
require_once '../dbconnection/koneksi.php';
if (!isset($_SESSION['log_proses']) || empty($_SESSION['log_proses'])) {
    echo "<h2>Tidak ada data proses diagnosa.</h2>";
    exit;
}

$id_akun = $_SESSION['id_akun'] ?? null;
if (!$id_akun) {
    die("<script>alert('Anda harus login terlebih dahulu!'); window.location.href='../login.php';</script>");
}

// Ambil data pengguna berdasarkan id_akun
$query = $conn->prepare("SELECT nama_lengkap, jenis_kelamin, tanggal_lahir FROM tabel_akun WHERE id_akun = ?");
$query->execute([$id_akun]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Ambil hasil diagnosa terbaru dari sesi
$hasil_diagnosa = $_SESSION['hasil_diagnosa'] ?? [];
$gejala_terpilih = $_SESSION['gejala_terpilih'] ?? [];


// Ambil informasi penyakit dan solusi dari database
$penyakit_data = [];
if (!empty($hasil_diagnosa)) {
    $placeholders = implode(',', array_fill(0, count($hasil_diagnosa), '?'));
    $query = $conn->prepare("SELECT kode_penyakit, nama_penyakit, solusi FROM penyakit WHERE kode_penyakit IN ($placeholders)");
    $query->execute(array_keys($hasil_diagnosa));
    $penyakit_data = $query->fetchAll(PDO::FETCH_ASSOC);
}

// Ambil informasi gejala dari database
$gejala_data = [];
if (!empty($gejala_terpilih)) {
    $placeholders = implode(',', array_fill(0, count($gejala_terpilih), '?'));
    $query = $conn->prepare("SELECT kode_gejala, nama_gejala FROM gejala WHERE kode_gejala IN ($placeholders)");
    $query->execute($gejala_terpilih);    
    $gejala_data = $query->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-area, .print-area * {
                visibility: visible;
            }
            .print-area {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                width: 80%;
                max-width: 600px;
                padding: 20px;
                border: 1px solid #000;
                background: white;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex">
    <?php include '../assets/sidebar/sidebar_pasien.php'; ?>
    
    <main class="ml-64 p-6 w-full">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-xl mx-auto print-area">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">Hasil Diagnosa</h2>
            
            <?php if (!empty($hasil_diagnosa) && $user): ?>
                <div class="p-6 border border-gray-300 rounded-lg shadow-md bg-white">
                    <p class="text-lg font-medium text-gray-700">Nama: <span class="font-semibold"><?= htmlspecialchars($user['nama_lengkap']); ?></span></p>
                    <p class="text-lg font-medium text-gray-700">Jenis Kelamin: <span class="font-semibold"><?= htmlspecialchars($user['jenis_kelamin']); ?></span></p>
                    <p class="text-lg font-medium text-gray-700">Tanggal Lahir: <span class="font-semibold"><?= htmlspecialchars($user['tanggal_lahir']); ?></span></p>
                    <hr class="my-4">
                    
                    <p class="text-lg font-medium text-gray-700">Gejala yang dipilih:</p>
                    <ul class="list-disc pl-5 text-gray-600 font-semibold">
                        <?php foreach ($gejala_data as $gejala): ?>
                            <li><?= htmlspecialchars($gejala['kode_gejala']) . " - " . htmlspecialchars($gejala['nama_gejala']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr class="my-4">
                    
                    <p class="text-lg font-medium text-gray-700">Kemungkinan penyakit yang terdeteksi:</p>
                    <ul class="list-disc pl-5 text-green-600 font-semibold">
                        <?php foreach ($penyakit_data as $penyakit): ?>
                            <li><?= htmlspecialchars($penyakit['kode_penyakit']) . " - " . htmlspecialchars($penyakit['nama_penyakit']) . " (" . round(floatval($hasil_diagnosa[$penyakit['kode_penyakit']]) * 100, 2) . "%)"; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr class="my-4">
                    
                    <p class="text-lg font-medium text-gray-700 mt-4">Solusi:</p>
                    <ul class="list-disc pl-5 text-blue-600 font-semibold">
                        <?php foreach ($penyakit_data as $penyakit): ?>
                            <li><?= htmlspecialchars($penyakit['solusi'] ?? "Solusi tidak ditemukan"); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <hr class="my-4">
                    
                    <div class="flex justify-between mt-4">
                    <a href="cetak_hasil.php" class="bg-gray-600 text-white px-2 py-2 rounded-lg hover:bg-green-800">📋 Halaman Cetak</a>
                        <a href="perhitungan.php" class="bg-blue-600 text-white px-2 py-2 rounded-lg hover:bg-green-800">📋 Lihat Perhitungan</a>
                        <a href="rekam_medis.php" class="bg-green-600 text-white px-2 py-2 rounded-lg hover:bg-green-800">📋 Lihat Rekam Medis</a>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-red-600 font-semibold text-center">Tidak ada hasil diagnosa ditemukan.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>