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
    .hi {
        display: none;
    }
}
</style>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl print-area">
        <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">Hasil Diagnosa</h2>
        
        <?php if (!empty($hasil_diagnosa) && $user): ?>
            <table class="w-full border-collapse border border-gray-300 text-left">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Nama</th>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                </tr>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Jenis Kelamin</th>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['jenis_kelamin']); ?></td>
                </tr>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Tanggal Lahir</th>
                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($user['tanggal_lahir']); ?></td>
                </tr>
            </table>
            
            <h3 class="text-lg font-medium text-gray-700 mt-6">Gejala yang dipilih</h3>
            <table class="w-full border-collapse border border-gray-300 mt-2">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Kode Gejala</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Gejala</th>
                </tr>
                <?php foreach ($gejala_data as $gejala): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($gejala['kode_gejala']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($gejala['nama_gejala']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <h3 class="text-lg font-medium text-gray-700 mt-6">Kemungkinan Penyakit</h3>
            <table class="w-full border-collapse border border-gray-300 mt-2">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Kode Penyakit</th>
                    <th class="border border-gray-300 px-4 py-2">Nama Penyakit</th>
                    <th class="border border-gray-300 px-4 py-2">Tingkat Kemungkinan</th>
                </tr>
                <?php foreach ($penyakit_data as $penyakit): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($penyakit['kode_penyakit']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($penyakit['nama_penyakit']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?= round(floatval($hasil_diagnosa[$penyakit['kode_penyakit']]) * 100, 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <h3 class="text-lg font-medium text-gray-700 mt-6">Solusi</h3>
            <table class="w-full border-collapse border border-gray-300 mt-2">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Solusi</th>
                </tr>
                <?php foreach ($penyakit_data as $penyakit): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($penyakit['solusi'] ?? "Solusi tidak ditemukan"); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="text-center mt-6">
            <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-800 hi">🖨 Cetak</button>
<a href="hasil_diagnosacf.php" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-800 hi">Kembali</a>
</div>
        <?php else: ?>
            <p class="text-red-600 font-semibold text-center">Tidak ada hasil diagnosa ditemukan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
