<?php
session_start();
require_once '../dbconnection/koneksi.php'; // Pastikan path benar

$id_akun = $_SESSION['id_akun'] ?? null;
if (!$id_akun) {
    die("<script>alert('Anda harus login terlebih dahulu!'); window.location.href='../login.php';</script>");
}

// Mengambil seluruh gejala dari database
$query = $conn->prepare("SELECT * FROM gejala");
$query->execute();
$gejala_list = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 flex">    
    <!-- Sidebar -->
    <?php include '../assets/sidebar/sidebar_pasien.php'; ?>
    
    <!-- Main Content -->
    <main class="ml-64 p-6 w-full">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-green-800">Konsultasi</h1>
        </div>
        
        <!-- Form Konsultasi -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-green-700 mb-6">Form Konsultasi Presentase Penyakit</h2>
            <form action="backend/proses_diagnosacf.php" method="POST">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-green-200">
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">Kode Gejala</th>
                            <th class="border border-gray-300 px-4 py-2">Nama Gejala</th>
                            <th class="border border-gray-300 px-4 py-2">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($gejala_list) > 0): ?>
                            <?php $no = 1; foreach ($gejala_list as $row): ?>
                            <tr class="bg-white hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2 text-center"><?php echo $no++; ?></td>
                                <td class="border border-gray-300 px-4 py-2 text-center"><?php echo htmlspecialchars($row['kode_gejala']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">Apakah <?php echo htmlspecialchars($row['nama_gejala']); ?>?</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                <select name="bobot_gejala[<?php echo htmlspecialchars($row['kode_gejala']); ?>]" class="p-2 border border-gray-300 rounded" required>
                                        <option value="">-- Pilih Bobot --</option>
                                        <option value="0.2">Tidak Tahu</option>
                                        <option value="0.4">Sedikit Yakin</option>
                                        <option value="0.6">Cukup Yakin</option>
                                        <option value="0.8">Yakin</option>
                                        <option value="1.0">Sangat Yakin</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-2 text-center text-red-500">Tidak ada gejala dalam database.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="mt-6 text-center">
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">Submit Diagnosa</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
