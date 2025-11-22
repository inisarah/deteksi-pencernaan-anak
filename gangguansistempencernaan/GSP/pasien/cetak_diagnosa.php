<?php
session_start();
require_once '../dbconnection/koneksi.php';

$id_akun = $_SESSION['id_akun'] ?? null;
if (!$id_akun) {
    die("<script>alert('Anda harus login terlebih dahulu!'); window.location.href='../login.php';</script>");
}

// Ambil data pengguna
$query = $conn->prepare("SELECT nama_lengkap, jenis_kelamin, tanggal_lahir FROM tabel_akun WHERE id_akun = ?");
$query->execute([$id_akun]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Ambil hasil diagnosa dari session
$hasil_diagnosa = $_SESSION['hasil_diagnosa'] ?? null;
$kode_penyakit_arr = !empty($hasil_diagnosa['kode_penyakit']) ? explode(", ", $hasil_diagnosa['kode_penyakit']) : [];
$gejala_teridentifikasi = $hasil_diagnosa['gejala'] ?? 'Tidak ada gejala yang teridentifikasi';
$solusi_akhir = $hasil_diagnosa['solusi'] ?? '-';

// Ambil daftar nama penyakit dari database
$diagnosa_penyakit = [];
if (!empty($kode_penyakit_arr) && $kode_penyakit_arr[0] !== "NONE") {
    foreach ($kode_penyakit_arr as $kode_penyakit) {
        $query = $conn->prepare("SELECT nama_penyakit FROM penyakit WHERE kode_penyakit = ?");
        $query->execute([$kode_penyakit]);
        $nama_penyakit = $query->fetchColumn() ?: 'Tidak ditemukan';
        $diagnosa_penyakit[] = [
            'kode' => $kode_penyakit,
            'nama' => $nama_penyakit
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Hasil Diagnosa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-white p-10">
    <div class="max-w-4xl mx-auto border p-8 shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Hasil Diagnosa Pasien</h2>
        
        <table class="w-full border-collapse border border-gray-400 mb-6">
            <tr class="bg-gray-200">
                <th class="border border-gray-400 px-4 py-2 text-left">Nama</th>
                <td class="border border-gray-400 px-4 py-2"> <?= htmlspecialchars($user['nama_lengkap']); ?> </td>
            </tr>
            <tr>
                <th class="border border-gray-400 px-4 py-2 text-left">Jenis Kelamin</th>
                <td class="border border-gray-400 px-4 py-2"> <?= htmlspecialchars($user['jenis_kelamin']); ?> </td>
            </tr>
            <tr class="bg-gray-200">
                <th class="border border-gray-400 px-4 py-2 text-left">Tanggal Lahir</th>
                <td class="border border-gray-400 px-4 py-2"> <?= htmlspecialchars($user['tanggal_lahir']); ?> </td>
            </tr>
        </table>
        
        <h3 class="text-lg font-semibold mb-2">Gejala yang Teridentifikasi:</h3>
        <p class="border border-gray-400 p-2 mb-6"> <?= htmlspecialchars(implode(", ", (array) $gejala_teridentifikasi)); ?> </p>
        
        <h3 class="text-lg font-semibold mb-2">Hasil Diagnosa:</h3>
        <table class="w-full border-collapse border border-gray-400 mb-6">
            <tr class="bg-gray-200">
                <th class="border border-gray-400 px-4 py-2">Kode Penyakit</th>
                <th class="border border-gray-400 px-4 py-2">Nama Penyakit</th>
            </tr>
            <?php if (!empty($diagnosa_penyakit)): ?>
                <?php foreach ($diagnosa_penyakit as $penyakit): ?>
                    <tr>
                        <td class="border border-gray-400 px-4 py-2 text-center"> <?= htmlspecialchars($penyakit['kode']); ?> </td>
                        <td class="border border-gray-400 px-4 py-2"> <?= htmlspecialchars($penyakit['nama']); ?> </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="border border-gray-400 px-4 py-2 text-center text-red-600">Tidak ada penyakit yang terdeteksi</td>
                </tr>
            <?php endif; ?>
        </table>
        
        <h3 class="text-lg font-semibold mb-2">Solusi:</h3>
        <p class="border border-gray-400 p-2 mb-6"> <?= htmlspecialchars($solusi_akhir); ?> </p>
        
        <h3 class="text-lg font-semibold mb-2">Tanggal Konsultasi:</h3>
        <p class="border border-gray-400 p-2 mb-6"> <?= date('d-m-Y'); ?> </p>
        
        <div class="text-center mt-6 no-print">
            <a href="hasil_diagnosafc.php" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all">
                Kembali ke Konsultasi
            </a>    
            <button onclick="window.print();" class="px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition-all">
                Cetak Diagnosa
            </button>
        </div>
    </div>
</body>
</html>
