<?php
session_start();
require '../../dbconnection/koneksi.php';

// Ambil daftar penyakit & gejala untuk form
$penyakitList = $conn->query("SELECT kode_penyakit, nama_penyakit FROM penyakit")->fetchAll(PDO::FETCH_ASSOC);
$gejalaList = $conn->query("SELECT kode_gejala, nama_gejala FROM gejala")->fetchAll(PDO::FETCH_ASSOC);

// Proses Tambah Relasi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_penyakit = $_POST['kode_penyakit'];
    $gejala_terpilih = $_POST['gejala'];

    foreach ($gejala_terpilih as $kode_gejala) {
        $stmt = $conn->prepare("INSERT INTO relasi (kode_penyakit, kode_gejala) VALUES (?, ?)");
        $stmt->execute([$kode_penyakit, $kode_gejala]);
    }

    echo "<script>alert('Relasi berhasil ditambahkan!'); window.location.href='../relasi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Relasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100 flex flex-col items-center min-h-screen">

    <main class="p-8 w-full max-w-md bg-white rounded-lg shadow-xl mt-10 text-center">
        <h1 class="text-3xl font-bold text-blue-600 mb-6">🩺 Tambah Relasi 🔗</h1>
        <form method="POST" class="space-y-4">
            <!-- Pilih Penyakit -->
            <div>
                <label class="block text-blue-700 font-semibold mb-2">Pilih Penyakit:</label>
                <select name="kode_penyakit" required class="w-full p-3 border-2 border-blue-400 rounded-lg focus:ring-4 focus:ring-blue-300">
                    <?php foreach ($penyakitList as $p): ?>
                        <option value="<?php echo $p['kode_penyakit']; ?>">
                            <?php echo "🦠 " . $p['kode_penyakit'] . " - " . $p['nama_penyakit']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pilih Gejala -->
            <div>
                <label class="block text-blue-700 font-semibold mb-2">Pilih Gejala 🏥 (Bisa lebih dari satu):</label>
                <div class="grid grid-cols-2 gap-3 bg-blue-50 p-4 rounded-lg shadow-inner">
                    <?php foreach ($gejalaList as $g): ?>
                        <label class="flex items-center space-x-2 text-blue-700">
                            <input type="checkbox" name="gejala[]" value="<?php echo $g['kode_gejala']; ?>" class="accent-blue-500 w-5 h-5">
                            <span class="text-sm">🔹 <?php echo $g['kode_gejala'] . " - " . $g['nama_gejala']; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-lg shadow-md text-lg font-semibold hover:bg-orange-600">✅ Simpan</button>
            <a href="../relasi.php" class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-md text-lg font-semibold hover:bg-red-600">❌ Batal</a>
        </form>
    </main>
</body>
</html>
