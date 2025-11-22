<?php
session_start();
require_once '../dbconnection/koneksi.php'; // Pastikan path ini benar

// Ambil daftar gejala dari database, kecuali G11, G13, G22
$query = "SELECT * FROM gejala WHERE kode_gejala NOT IN ('G11', 'G13', 'G22')";
$gejala = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jawaban = $_POST['jawaban'];
    $_SESSION['jawaban_gejala'] = $jawaban; // Simpan jawaban di session
    header("Location: backend/proses_diagnosafc.php"); // Arahkan ke proses diagnosa
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-green-200 via-blue-200 to-purple-200 flex">
    <!-- Sidebar -->
    <?php include '../assets/sidebar/sidebar_pasien.php'; ?>
    
    <!-- Main Content -->
    <main class="ml-64 p-6 w-full">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-purple-800">Konsultasi</h1>
        </div>
        
        <!-- Form Konsultasi -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-purple-700 mb-6">Jawab Pertanyaan Berikut</h2>
            <form method="POST" action="">
                <div class="space-y-6">
                    <?php $no = 1; foreach ($gejala as $g) : ?>
                        <div class="bg-gradient-to-r from-green-300 to-blue-300 p-4 rounded-lg shadow-md">
                            <p class="text-lg font-medium text-gray-800 mb-4">
                                <?= $no++; ?>. [<?= htmlspecialchars($g['kode_gejala']); ?>] Apakah <?= htmlspecialchars($g['nama_gejala']); ?>?
                            </p>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jawaban[<?= $g['kode_gejala']; ?>]" value="iya" required class="hidden peer">
                                    <span class="px-6 py-3 border rounded-full text-green-600 border-green-600 peer-checked:bg-green-600 peer-checked:text-white cursor-pointer text-lg">Ya</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jawaban[<?= $g['kode_gejala']; ?>]" value="tidak" required class="hidden peer">
                                    <span class="px-6 py-3 border rounded-full text-red-600 border-red-600 peer-checked:bg-red-600 peer-checked:text-white cursor-pointer text-lg">Tidak</span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex justify-center">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-transform transform hover:scale-110">
                        Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
