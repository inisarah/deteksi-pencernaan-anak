<?php
session_start();
require '../dbconnection/koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Ambil semua data relasi dengan JOIN ke penyakit dan gejala
$query = $conn->query("
    SELECT r.kode_penyakit, p.nama_penyakit, r.kode_gejala, g.nama_gejala 
    FROM relasi r
    JOIN penyakit p ON r.kode_penyakit = p.kode_penyakit
    JOIN gejala g ON r.kode_gejala = g.kode_gejala
    ORDER BY r.kode_penyakit
");
$relasi = $query->fetchAll(PDO::FETCH_ASSOC);

// Hitung jumlah gejala per penyakit
$penyakit_counts = [];
foreach ($relasi as $row) {
    $penyakit_counts[$row['kode_penyakit']] = isset($penyakit_counts[$row['kode_penyakit']]) ? $penyakit_counts[$row['kode_penyakit']] + 1 : 1;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Relasi - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex">

    <?php include '../assets/sidebar/sidebar_admin.php'; ?>

    <main class="ml-64 p-6 w-full">
        <h1 class="text-2xl font-bold text-gray-700 mb-6">Data Relasi</h1>

    
        <!-- Tabel Relasi -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-4">
            <h2 class="text-xl font-bold text-gray-700 mb-4">List Relasi</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-orange-500 text-white">
                        <th class="p-3 text-left w-1/6">Kode Penyakit</th>
                        <th class="p-3 text-left w-1/6">Nama Penyakit</th>
                        <th class="p-3 text-left w-1/6">Kode Gejala</th>
                        <th class="p-3 text-center w-2/6">Nama Gejala</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $last_kode_penyakit = null;
                    foreach ($relasi as $index => $item): 
                    ?>
                        <tr class="border-b hover:bg-gray-100">
                            <?php if ($item['kode_penyakit'] !== $last_kode_penyakit): ?>
                                <td class="p-3 text-left" rowspan="<?= $penyakit_counts[$item['kode_penyakit']]; ?>">
                                    <?php echo $item['kode_penyakit']; ?>
                                </td>
                                <td class="p-3 text-left" rowspan="<?= $penyakit_counts[$item['kode_penyakit']]; ?>">
                                    <?php echo $item['nama_penyakit']; ?>
                                </td>
                                <?php $last_kode_penyakit = $item['kode_penyakit']; ?>
                            <?php endif; ?>
                            
                            <td class="p-3"><?php echo $item['kode_gejala']; ?></td>
                            <td class="p-3 text-center"><?php echo $item['nama_gejala']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
