<?php
session_start();
require_once '../dbconnection/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_akun'])) {
    header("Location: login.php");
    exit();
}

$id_akun = $_SESSION['id_akun'];

// Ambil data rekam medis certainty factor berdasarkan id_akun yang login
$query = $conn->prepare("SELECT drm.*, a.nama_lengkap AS nama_pasien, a.jenis_kelamin, a.tanggal_lahir 
                        FROM detail_rekam_medis drm 
                        JOIN tabel_akun a ON drm.id_akun = a.id_akun
                        WHERE drm.id_akun = ?");
$query->execute([$id_akun]);
$rekam_medis_cf = $query->fetchAll(PDO::FETCH_ASSOC);

// Fungsi untuk mendapatkan nama penyakit berdasarkan kode
function getNamaPenyakit($kode_penyakit, $conn) {
    $kode_array = array_map('trim', explode(',', $kode_penyakit));
    $nama_penyakit_list = [];

    foreach ($kode_array as $kode) {
        $stmt = $conn->prepare("SELECT nama_penyakit FROM penyakit WHERE kode_penyakit = ?");
        $stmt->execute([$kode]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $nama_penyakit_list[] = htmlspecialchars($result['nama_penyakit']);
        }
    }

    return implode("<br>", $nama_penyakit_list);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    function printTable(id) {
    var table = document.getElementById(id).cloneNode(true);

    // Hapus kolom "Aksi" sebelum cetak
    var headerRow = table.querySelector("thead tr");
    headerRow.removeChild(headerRow.lastElementChild);

    var rows = table.querySelectorAll("tbody tr");
    rows.forEach(row => row.removeChild(row.lastElementChild));

    var printContents = `
       <div class="flex flex-col items-center p-5 border">
    <div class="flex justify-center mb-4">
        <img src='../assets/gambar/logo.png' alt="Logo HBS" class="w-20 h-20 object-contain" />
    </div>
    <div class="text-center">
        <h2 class="text-xl font-semibold my-1">PEMERINTAH PROVINSI SUMATERA BARAT</h2>
        <h2 class="text-xl font-semibold my-1">BADAN LAYANAN UMUM DAERAH</h2>
        <h1 class="text-lg font-bold my-2">RSUD DR. ACHMAD DARWIS</h1>
        <p class="text-sm my-1">Jalan tan Malaka no 1 Kecamatan Suliki Kabupaten Lima Puluh Kota</p>

    </div>
    <hr class="w-full border-black my-2">
    
    ` + table.outerHTML + `
    
    <div class="mt-8 w-full">
        <div class="text-right pr-12">
            <p>Padang, ` + new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + `</p>
            <p>Hormat Kami,</p>
            <div class="h-16"></div> <!-- Spacing for signature -->
            <br><br><br>
            <p class="font-bold">dr. Fakhrurrazi, Sp.A</p>
        </div>
    </div>
</div>
    `;

    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<style>
   @media print {
    
    .no-print {
        display: none !important;
    }

    @page {
        size: A4 portrait; /* Gunakan potret untuk cetak */
        margin: 1.5cm; /* Beri margin agar tidak terpotong */
    }

    body {
        font-size: 12px; /* Agar tetap terbaca dengan baik */
        color: black;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px; /* Sedikit kecil supaya tidak terpotong */
    }

    th, td {
        border: 1px solid black;
        padding: 6px;
        text-align: center;
    }

    .header-center {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .text-right {
        text-align: right !important;
    }
}

</style>

</head>
<body class="bg-orange-100 flex">
    <!-- Sidebar -->
    <?php include '../assets/sidebar/sidebar_pasien.php'; ?>
    
    <!-- Main Content -->
    <main class="ml-64 p-6 w-full">
    <h1 class="text-3xl font-bold text-orange-800 mb-6 no-print">Rekam Medis</h1>

        
        <!-- Rekam Medis Certainty Factor -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold text-orange-700 mb-4">Hasil Rekam Medis Pasien</h2>
            <button onclick="printTable('table_cf')" class="mb-4 px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700 transition">Cetak</button>
            
            <div class="overflow-x-auto">
                <table id="table_cf" class="table w-full border border-orange-300">
                    <thead>
                        <tr class="bg-orange-600 text-white">
                            <th class="border border-orange-300 p-3">No</th>
                            <th class="border border-orange-300 p-3">Nama Pasien</th>
                            <th class="border border-orange-300 p-3">Jenis Kelamin</th>
                            <th class="border border-orange-300 p-3">Tanggal Lahir</th>
                            <th class="border border-orange-300 p-3">Nama Penyakit</th>
                            <th class="border border-orange-300 p-3">Nilai CF</th>
                            <th class="border border-orange-300 p-3">Solusi</th>
                            <th class="border border-orange-300 p-3">Tanggal Konsultasi</th>
                            <th class="border border-orange-300 p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($rekam_medis_cf as $rm): ?>
                            <tr class="hover:bg-orange-100">
                                <td class="border border-orange-300 p-3 text-center"><?= $no++; ?></td>
                                <td class="border border-orange-300 p-3"><?= htmlspecialchars($rm['nama_pasien']); ?></td>
                                <td class="border border-orange-300 p-3 text-center"><?= htmlspecialchars($rm['jenis_kelamin']); ?></td>
                                <td class="border border-orange-300 p-3 text-center"><?= htmlspecialchars($rm['tanggal_lahir']); ?></td>
                                <td class="border border-orange-300 p-3"><?= getNamaPenyakit($rm['kode_penyakit'], $conn); ?></td>
                                <td class="border border-orange-300 p-3 text-center"><?= str_replace(',', '<br>', htmlspecialchars($rm['nilai_cf'])); ?></td>
                                <td class="border border-orange-300 p-3"><?= htmlspecialchars($rm['solusi']); ?></td>
                                <td class="border border-orange-300 p-3 text-center"><?= htmlspecialchars($rm['tanggal_konsultasi']); ?></td>
                                <td class="border border-orange-300 p-3 text-center">
                                    <a href="backend/hapus_rekammedis.php?id=<?= $rm['id_detail']; ?>" 
                                       class="btn btn-error btn-sm"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">
                                       Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
