<?php
session_start();
require '../../dbconnection/koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../../login.php");
    exit;
}

// Ambil kode penyakit dari URL
if (!isset($_GET['kode_penyakit'])) {
    echo "<script>alert('Kode Penyakit tidak ditemukan!'); window.location.href='../relasi.php';</script>";
    exit;
}

$kode_penyakit = $_GET['kode_penyakit'];

// Ambil data penyakit berdasarkan kode
$queryPenyakit = $conn->prepare("SELECT * FROM penyakit WHERE kode_penyakit = ?");
$queryPenyakit->execute([$kode_penyakit]);
$penyakit = $queryPenyakit->fetch(PDO::FETCH_ASSOC);

if (!$penyakit) {
    echo "<script>alert('Penyakit tidak ditemukan!'); window.location.href='../relasi.php';</script>";
    exit;
}

// Ambil daftar semua gejala
$gejalaList = $conn->query("SELECT kode_gejala, nama_gejala FROM gejala")->fetchAll(PDO::FETCH_ASSOC);

// Ambil gejala yang sudah terkait dengan penyakit
$queryRelasi = $conn->prepare("SELECT kode_gejala FROM relasi WHERE kode_penyakit = ?");
$queryRelasi->execute([$kode_penyakit]);
$relasiGejala = $queryRelasi->fetchAll(PDO::FETCH_COLUMN);

// Jika form dikirim (proses update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gejala_terpilih = isset($_POST['gejala']) ? $_POST['gejala'] : [];

    // Hapus semua relasi lama untuk penyakit ini
    $deleteStmt = $conn->prepare("DELETE FROM relasi WHERE kode_penyakit = ?");
    $deleteStmt->execute([$kode_penyakit]);

    // Tambahkan gejala yang baru dipilih
    foreach ($gejala_terpilih as $kode_gejala) {
        $insertStmt = $conn->prepare("INSERT INTO relasi (kode_penyakit, kode_gejala) VALUES (?, ?)");
        $insertStmt->execute([$kode_penyakit, $kode_gejala]);
    }

    echo "<script>alert('Relasi berhasil diperbarui!'); window.location.href='../relasi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Relasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    <main class="p-6 w-full max-w-2xl mx-auto bg-white rounded-lg shadow-md mt-10">
        <h1 class="text-2xl font-bold text-gray-700 mb-6">Edit Relasi Penyakit</h1>

        <!-- Informasi Penyakit -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Kode Penyakit:</label>
            <input type="text" value="<?= $penyakit['kode_penyakit']; ?>" readonly class="w-full p-2 border rounded-md bg-gray-200">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Nama Penyakit:</label>
            <input type="text" value="<?= $penyakit['nama_penyakit']; ?>" readonly class="w-full p-2 border rounded-md bg-gray-200">
        </div>

        <!-- Form Edit Gejala -->
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold">Pilih Gejala (Bisa lebih dari satu):</label>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach ($gejalaList as $g): ?>
                        <label class="flex items-center">
                            <input type="checkbox" name="gejala[]" value="<?= $g['kode_gejala']; ?>"
                                class="mr-2" <?= in_array($g['kode_gejala'], $relasiGejala) ? 'checked' : ''; ?>>
                            <?= $g['kode_gejala'] . " - " . $g['nama_gejala']; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow-md hover:bg-green-700">
                Simpan Perubahan
            </button>
            <a href="../relasi.php" class="bg-gray-500 text-white px-4 py-2 rounded shadow-md hover:bg-gray-600">Batal</a>
        </form>
    </main>
</body>
</html>
