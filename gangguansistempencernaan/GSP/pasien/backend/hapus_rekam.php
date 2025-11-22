<?php
session_start();
require_once '../../dbconnection/koneksi.php';

if (!isset($_GET['id'])) {
    $_SESSION['pesan'] = "ID rekam medis tidak ditemukan.";
    header("Location: ../rekam_medis.php");
    exit();
}

$id_medis = $_GET['id'];

// Hapus data rekam medis forward chaining
$query = $conn->prepare("DELETE FROM rekam_medis WHERE id_medis = ?");
$query->execute([$id_medis]);

if ($query->rowCount() > 0) {
    $_SESSION['pesan'] = "Rekam medis berhasil dihapus.";
} else {
    $_SESSION['pesan'] = "Gagal menghapus rekam medis.";
}

header("Location: ../rekam_medis.php");
exit();
?>
