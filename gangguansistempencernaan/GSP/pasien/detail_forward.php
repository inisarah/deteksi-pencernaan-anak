<?php
session_start();

// Ambil data proses dari session
$proses_forward_chaining = $_SESSION['proses_forward_chaining'] ?? [];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Proses Forward Chaining</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Detail Proses Forward Chaining</h2>

        <?php if (empty($proses_forward_chaining)): ?>
            <div class="alert alert-warning text-center">
                <strong>Tidak ada proses yang terjadi.</strong>
            </div>
        <?php else: ?>
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Step</th>
                        <th>Rule yang Dieksekusi</th>
                        <th>Gejala Wajib</th>
                        <th>Gejala Opsional</th>
                        <th>Gejala Sebelum Rule</th>
                        <th>Fakta Baru</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proses_forward_chaining as $index => $step): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><strong><?= $step['id_rule']; ?></strong></td>
                            <td><?= implode(", ", $step['syarat_wajib']); ?></td>
                            <td><?= empty($step['syarat_opsional']) ? "-" : implode(", ", $step['syarat_opsional']); ?></td>
                            <td><?= implode(", ", $step['gejala_sebelum']); ?></td>
                            <td><strong><?= $step['kode_penyakit']; ?></strong></td>
                            <td class="text-success"><em><?= $step['keterangan']; ?></em></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="hasil_diagnosafc.php" class="btn btn-primary">Kembali ke Hasil Diagnosa</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
