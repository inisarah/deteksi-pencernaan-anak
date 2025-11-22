<?php
session_start();
if (!isset($_SESSION['log_proses']) || empty($_SESSION['log_proses'])) {
    echo "<h2>Tidak ada data proses diagnosa.</h2>";
    exit;
}

// Filter hanya proses dengan cf_result > 0
$log_proses_filtered = array_filter($_SESSION['log_proses'], function ($log) {
    return $log['cf_result'] > 0;
});

// Filter hanya proses CF Combine yang memiliki hasil relevan
$log_combine_filtered = array_filter($_SESSION['log_proses_combine'] ?? [], function ($combine) {
    return $combine['cf_combine'] > 0;
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Diagnosa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Detail Proses Perhitungan CF</h2>

        <?php if (!empty($log_proses_filtered)) : ?>
            <?php foreach ($log_proses_filtered as $log) : ?>
                <div class="card mt-3">
                    <div class="card-header bg-primary text-white">
                        <h4>Penyakit: <?= htmlspecialchars($log['penyakit']) ?></h4>
                        <p>Rule yang Dieksekusi: <strong><?= htmlspecialchars($log['rule_id'] ?? 'Tidak Diketahui') ?></strong></p>
                    </div>
                    <div class="card-body">
                        <h5>Gejala yang terpenuhi:</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode Gejala</th>
                                    <th>Bobot Gejala</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($log['gejala'] as $gejala => $bobot) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($gejala) ?></td>
                                        <td><?= htmlspecialchars($bobot) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p><strong>Nilai CF Evidence (Min):</strong> <?= htmlspecialchars($log['cf_evidence']) ?></p>
                        <p><strong>CF Rule:</strong> <?= htmlspecialchars($log['cf_rule']) ?></p>
                        <p><strong>CF Result:</strong> <?= htmlspecialchars($log['cf_result']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="text-center text-danger">Tidak ada proses perhitungan CF yang relevan.</p>
        <?php endif; ?>

        <!-- Tampilkan proses CF Combine hanya jika ada -->
        <?php if (!empty($log_combine_filtered)) : ?>
            <h2 class="text-center mt-4">Proses CF Combine</h2>
            <div class="card mt-3">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Penyakit</th>
                                <th>CF Sebelumnya</th>
                                <th>CF Baru</th>
                                <th>CF Combine</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($log_combine_filtered as $combine) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($combine['penyakit']) ?></td>
                                    <td><?= htmlspecialchars($combine['cf_old']) ?></td>
                                    <td><?= htmlspecialchars($combine['cf_new']) ?></td>
                                    <td><?= htmlspecialchars($combine['cf_combine']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <p class="text-center text-danger mt-3">Tidak ada proses CF Combine yang relevan.</p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="hasil_diagnosacf.php" class="btn btn-secondary">Kembali ke Hasil Diagnosa</a>
        </div>
    </div>
</body>
</html>
