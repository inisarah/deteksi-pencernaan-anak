<?php
session_start();
require_once '../../dbconnection/koneksi.php';

if (!isset($_SESSION['id_akun'])) {
    header("Location: login.php");
    exit();
}

// Pastikan ID tersedia
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus rekam medis berdasarkan id_detail_rekam_medis
    $stmt = $conn->prepare("DELETE FROM detail_rekam_medis WHERE id_detail = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['pesan'] = "Rekam medis berhasil dihapus.";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus rekam medis.";
    }
}

header("Location: ../rekam_medis.php");
exit();
