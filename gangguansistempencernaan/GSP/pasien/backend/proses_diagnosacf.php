<?php
session_start();
require_once '../../dbconnection/koneksi.php'; // Pastikan path benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bobot_gejala = $_POST['bobot_gejala'] ?? [];

    // Definisi aturan CF secara manual
    $rules = [
        ['rule_id' => 'R1', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08', 'G10', 'G12', 'G17'], 'cf_rule' => 0.84],
        ['rule_id' => 'R2', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G09', 'G10', 'G12', 'G17'], 'cf_rule' => 0.80],
        ['rule_id' => 'R3', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G10', 'G11', 'G12', 'G17'], 'cf_rule' => 0.83],
        ['rule_id' => 'R4', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G10', 'G12', 'G13', 'G17'], 'cf_rule' => 0.82],
        ['rule_id' => 'R5', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08', 'G09', 'G10', 'G12', 'G17' ], 'cf_rule' => 0.78],
        ['rule_id' => 'R6', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08', 'G10', 'G11', 'G12', 'G17' ], 'cf_rule' => 0.76],
        ['rule_id' => 'R7', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08', 'G10', 'G12', 'G13', 'G17' ], 'cf_rule' => 0.77],
        ['rule_id' => 'R8', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G09', 'G10', 'G11', 'G12', 'G17' ], 'cf_rule' => 0.71],
        ['rule_id' => 'R9', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G09', 'G10', 'G13', 'G12', 'G17' ], 'cf_rule' => 0.75],
        ['rule_id' => 'R10', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G13', 'G10', 'G11', 'G12', 'G17' ], 'cf_rule' => 0.72],
        ['rule_id' => 'R11', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08', 'G09', 'G10', 'G11', 'G17'], 'cf_rule' => 0.89],
        ['rule_id' => 'R12', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G06', 'G07', 'G08', 'G09', 'G10', 'G12', 'G13', 'G17' ], 'cf_rule' => 0.88],
        ['rule_id' => 'R13', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G09', 'G10', 'G11', 'G12', 'G13', 'G17' ], 'cf_rule' => 0.87],
        ['rule_id' => 'R14', 'kode_penyakit' => 'P1', 'gejala' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G10', 'G12', 'G17' ], 'cf_rule' => 0.86],
        ['rule_id' => 'R15','kode_penyakit' => 'P2', 'gejala' => ['G03', 'G04', 'G05', 'G09', 'G10','G14', 'G15', 'G16', 'G17', 'G18'], 'cf_rule' => 0.85],
        ['rule_id' => 'R16','kode_penyakit' => 'P2', 'gejala' => ['G03', 'G04', 'G05', 'G10', 'G14', 'G15', 'G16', 'G17', 'G18', 'G20'], 'cf_rule' => 0.86],
        ['rule_id' => 'R17','kode_penyakit' => 'P2', 'gejala' => ['G03', 'G04', 'G05', 'G10', 'G14', 'G15', 'G16', 'G17', 'G18', 'G20'], 'cf_rule' => 0.73],
        ['rule_id' => 'R18','kode_penyakit' => 'P3', 'gejala' => ['G22', 'G03', 'G04', 'G05', 'G06', 'G07', 'G09', 'G10', 'G12', 'G19', 'G21', 'G24'], 'cf_rule' => 0.72],
        ['rule_id' => 'R19','kode_penyakit' => 'P3', 'gejala' => ['G23', 'G03', 'G04', 'G05', 'G06', 'G07', 'G09', 'G10', 'G12', 'G19', 'G21', 'G24'], 'cf_rule' => 0.70],
        ['rule_id' => 'R20','kode_penyakit' => 'P3', 'gejala' => ['G03', 'G04', 'G05', 'G06', 'G07', 'G09', 'G10', 'G12', 'G19', 'G21', 'G24'], 'cf_rule' => 0.89],];    
    
     
     // Definisi solusi secara manual
     $solusi_penyakit = [
         'P1' => "1.	Mencegah timbulnya dehidrasi seperti meminum larutan oralit. 2.	Dianjurkan untuk meminum air kelapa.3.	Rutin mencuci tangan menggunakana air mengalir dan sabun.",
         'P2' => "1.	Mengkonsumsi maknan tinggi serat.
2.	Mencukupi kebutuhan cairan tubuh.
3.	Dianjurkan untuk meminum kafein.
4.	Hindari kebiasaan menahan BAB.
5.	Hindari minum alkohol(yang menyebabkan dehidrasi).
6.	Olahraga secara rutin.
.",
         'P3' => "1.Hindari anak makan dan minum yang dapat mengiritasi lambung seperti makan pedas, asam, berlemak.
2.	Dianjurkan untuk meminum kafein
3.	Berikan makanan yang mudah dicerna dan    lebih lembut, seperti nasi putih, kentang rebus atau sup kaldu.
4.	Pastikan anak makan dalam porsi kecil tetapi  sering
",
     ]; 

    $hasil_cf = [];
    $log_proses = [];
    $log_combine = []; // Log untuk proses CF Combine

    foreach ($rules as $rule) {
        $cf_values = [];
        $gejala_terpenuhi = [];

        foreach ($rule['gejala'] as $gejala) {
            if (isset($bobot_gejala[$gejala])) {
                $cf_values[] = floatval($bobot_gejala[$gejala]);
                $gejala_terpenuhi[$gejala] = floatval($bobot_gejala[$gejala]);
            } else {
                $cf_values[] = 0;
            }
        }

        $cf_evidence = min($cf_values);
        $cf_result = $cf_evidence * $rule['cf_rule'];

        $log_proses[] = [
            'rule_id' => $rule['rule_id'], // Simpan kode aturan
            'penyakit' => $rule['kode_penyakit'],
            'gejala' => $gejala_terpenuhi,
            'cf_evidence' => $cf_evidence,
            'cf_rule' => $rule['cf_rule'],
            'cf_result' => $cf_result
        ];
        

        if ($cf_result > 0) { // Hanya simpan jika CF > 0
            if (isset($hasil_cf[$rule['kode_penyakit']])) {
                $cf_old = $hasil_cf[$rule['kode_penyakit']];
                $cf_new = $cf_result;
                $cf_combine = $cf_old + $cf_new * (1 - $cf_old);

                $log_combine[] = [
                    'penyakit' => $rule['kode_penyakit'],
                    'cf_old' => $cf_old,
                    'cf_new' => $cf_new,
                    'cf_combine' => $cf_combine
                ];

                $hasil_cf[$rule['kode_penyakit']] = $cf_combine;
            } else {
                $hasil_cf[$rule['kode_penyakit']] = $cf_result;
            }
        }
    } // Tutup foreach

    if (!empty($hasil_cf)) {
        $filtered_hasil_cf = array_filter($hasil_cf, fn($cf) => $cf > 0);

        if (!empty($filtered_hasil_cf)) {
            $id_pasien = $_SESSION['id_akun'];
            $tanggal_konsultasi = date('Y-m-d');

            $kode_penyakit_list = implode(', ', array_keys($filtered_hasil_cf));
            $nilai_cf_list = implode(', ', array_map(fn($cf) => round($cf * 100, 2), $filtered_hasil_cf));
            $kode_gejala = implode(', ', array_keys($bobot_gejala));
            $solusi_list = implode('; ', array_map(fn($kode) => $solusi_penyakit[$kode] ?? "Solusi tidak ditemukan", array_keys($filtered_hasil_cf)));

            $stmt = $conn->prepare("INSERT INTO detail_rekam_medis (id_akun, kode_penyakit, nilai_cf, kode_gejala, solusi, tanggal_konsultasi) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_pasien, $kode_penyakit_list, $nilai_cf_list, $kode_gejala, $solusi_list, $tanggal_konsultasi]);

            $_SESSION['hasil_diagnosa'] = $filtered_hasil_cf;
            $_SESSION['log_proses'] = $log_proses; // Simpan log proses perhitungan CF dasar
            $_SESSION['log_proses_combine'] = $log_combine; // Simpan log CF Combine
            $_SESSION['gejala_terpilih'] = array_keys($bobot_gejala);

        } else {
            $_SESSION['hasil_diagnosa'] = [];
            $_SESSION['log_proses'] = [];
            $_SESSION['log_proses_combine'] = [];
            $_SESSION['gejala_terpilih'] = [];

        }
    } else {
        $_SESSION['hasil_diagnosa'] = [];
        $_SESSION['log_proses'] = [];
        $_SESSION['log_proses_combine'] = [];
        $_SESSION['gejala_terpilih'] = [];

    }

    header("Location: ../hasil_diagnosacf.php");
    exit;
}
?>
