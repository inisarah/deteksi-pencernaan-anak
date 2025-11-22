<?php
session_start();
require '../../dbconnection/koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../login.php");
    exit;
}

// Pastikan parameter kode penyakit dan kode gejala ada
if (!isset($_GET['kode_penyakit']) || !isset($_GET['kode_gejala'])) {
    echo "<script>alert('Data tidak valid!'); window.location.href='../relasi.php';</script>";
    exit;
}

$kode_penyakit = $_GET['kode_penyakit'];
$kode_gejala = $_GET['kode_gejala'];

// Hapus hanya gejala tertentu dari penyakit terkait
$stmt = $conn->prepare("DELETE FROM relasi WHERE kode_penyakit = ? AND kode_gejala = ?");
$stmt->execute([$kode_penyakit, $kode_gejala]);

echo "<script>alert('Gejala berhasil dihapus dari relasi penyakit!'); window.location.href='../relasi.php';</script>";
?>
