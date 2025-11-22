<?php
session_start();
require_once '../dbconnection/koneksi.php'; // Pastikan path benar

$id_akun = $_SESSION['id_akun'] ?? null;
if (!$id_akun) {
    die("<script>alert('Anda harus login terlebih dahulu!'); window.location.href='../login.php';</script>");
}

// Ambil data pengguna berdasarkan id_akun
$query = $conn->prepare("SELECT nama_lengkap, jenis_kelamin, tanggal_lahir FROM tabel_akun WHERE id_akun = ?");
$query->execute([$id_akun]);
$user = $query->fetch(PDO::FETCH_ASSOC);

// Ambil hasil diagnosa dari session
$hasil_diagnosa = $_SESSION['hasil_diagnosa'] ?? null;

// Ambil daftar nama penyakit berdasarkan kode_penyakit
$kode_penyakit_arr = !empty($hasil_diagnosa['kode_penyakit']) ? explode(", ", $hasil_diagnosa['kode_penyakit']) : [];
$jumlah_penyakit = count($kode_penyakit_arr);

// Ambil gejala yang teridentifikasi
$gejala_teridentifikasi = $hasil_diagnosa['gejala'] ?? 'Tidak ada gejala yang teridentifikasi';
// Ambil solusi yang sesuai
$solusi_akhir = $hasil_diagnosa['solusi'] ?? '-';

// Ambil daftar nama penyakit dari database
$presentase_penyakit = [];
if ($jumlah_penyakit > 0 && $kode_penyakit_arr[0] !== "NONE") {
    foreach ($kode_penyakit_arr as $kode_penyakit) {
        $query = $conn->prepare("SELECT nama_penyakit FROM penyakit WHERE kode_penyakit = ?");
        $query->execute([$kode_penyakit]);
        $nama_penyakit = $query->fetchColumn() ?: 'Tidak ditemukan';
        $presentase_penyakit[] = "({$kode_penyakit}) $nama_penyakit";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Diagnosa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">
    <?php include '../assets/sidebar/sidebar_pasien.php'; ?>
    
    <main class="ml-64 p-6 w-full">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-xl mx-auto">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 text-center">Hasil Diagnosa</h2>
            
            <?php if ($hasil_diagnosa && $user): ?>
                <div class="p-6 border border-gray-300 rounded-lg shadow-md bg-white">
                    <p class="text-lg font-medium text-gray-700">Nama: <span class="font-semibold"> <?= htmlspecialchars($user['nama_lengkap']); ?> </span></p>
                    <p class="text-lg font-medium text-gray-700">Jenis Kelamin: <span class="font-semibold"> <?= htmlspecialchars($user['jenis_kelamin']); ?> </span></p>
                    <p class="text-lg font-medium text-gray-700">Tanggal Lahir: <span class="font-semibold"> <?= htmlspecialchars($user['tanggal_lahir']); ?> </span></p>
                    <hr class="my-4">
                    
                    <?php if ($jumlah_penyakit > 0 && $kode_penyakit_arr[0] !== "NONE"): ?>
                        <p class="text-lg font-medium text-gray-700">Anda didiagnosa dengan penyakit:</p>
                        <ul class="list-disc pl-5 text-green-600 font-semibold">
                            <?php foreach ($presentase_penyakit as $penyakit): ?>
                                <li><?= htmlspecialchars($penyakit); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-red-600 font-semibold text-center">Tidak ada penyakit yang terdeteksi.</p>
                    <?php endif; ?>

                    <!
                    <hr class="my-4">
                    <p class="text-lg font-medium text-gray-700">Gejala yang teridentifikasi:</p>
                    <p class="text-gray-600 font-semibold"><?= htmlspecialchars(implode(", ", (array) $gejala_teridentifikasi)); ?></p>


                    <hr class="my-4">
                    <p class="text-lg font-medium text-gray-700">Solusi:</p>
                    <p class="text-gray-600 font-semibold"><?= htmlspecialchars($solusi_akhir); ?></p>

                    <hr class="my-4">
                    <p class="text-lg font-medium text-gray-700">Tanggal Konsultasi: <span class="font-semibold"> <?= date('d-m-Y'); ?> </span></p>

                    <!-- Tombol Aksi -->
                    <div class="mt-6 flex justify-center gap-4">
                        <!-- Tombol Kembali -->
                        <a href="konsultasifc.php" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 transition">
                            Kembali ke Konsultasi
                        </a>
                        <a href="detail_forward.php" class="px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg shadow-md hover:bg-purple-600 transition">
                            Detail Forward Chaining
                        </a>
                        <a href="cetak_diagnosa.php" class="px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg shadow-md hover:bg-purple-600 transition">
                           Cetak Hasil
                        </a>
                        <a href="konsultasicf.php" class="px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg shadow-md hover:bg-purple-600 transition">
                            Cek Presentase Penyakit Anda
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-red-600 font-semibold text-center">Tidak ada hasil diagnosa ditemukan.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
