<?php
session_start();
require_once '../../dbconnection/koneksi.php'; // Pastikan path benar

// Ambil jawaban dari session
$jawaban = $_SESSION['jawaban_gejala'] ?? [];

// Validasi jawaban
if (!is_array($jawaban) || empty($jawaban)) {
    $_SESSION['hasil_diagnosa'] = "Tidak ada gejala yang dipilih. Silakan ulangi konsultasi.";
    header("Location: ../konsultasi.php");
    exit;
}

// Simpan semua gejala yang dipilih user
$gejala_terpilih = array_keys(array_filter($jawaban, function ($val) {
    return strtolower($val) === 'iya';
}));

// Definisi aturan Forward Chaining dengan ID Rule
$rules = [
    ['id' => 'R1', 'result' => 'G11', 'syarat_wajib' => ['G01', 'G02', 'G04', 'G06', 'G07', 'G10', 'G17'], 'syarat_opsional' => []],
    ['id' => 'R2', 'result' => 'G13', 'syarat_wajib' => ['G11'], 'syarat_opsional' => []],
    ['id' => 'R3', 'result' => 'P1', 'syarat_wajib' => ['G01', 'G02', 'G03', 'G04', 'G05', 'G07', 'G08', 'G10', 'G12', 'G17'], 'syarat_opsional' => ['G09', 'G13']],
    ['id' => 'R4', 'result' => 'P2', 'syarat_wajib' => ['G03', 'G04', 'G05', 'G10', 'G14', 'G15', 'G16', 'G17', 'G18'], 'syarat_opsional' => ['G09', 'G20']],
    ['id' => 'R5', 'result' => 'G22', 'syarat_wajib' => ['G03', 'G05', 'G09', 'G13', 'G17', 'G21', 'G23'], 'syarat_opsional' => []],
    ['id' => 'R6', 'result' => 'P3', 'syarat_wajib' => ['G03', 'G04', 'G05', 'G06', 'G07', 'G09', 'G10', 'G12', 'G19', 'G21', 'G24'], 'syarat_opsional' => ['G22', 'G23']],
];

// Fungsi Forward Chaining dengan Fakta Baru
function forwardChaining($rules, &$gejala_terpilih) {
    $hasil_diagnosa = [];
    $processed = [];
    $proses_forward_chaining = []; // Array untuk menyimpan proses Forward Chaining

    do {
        $new_gejala = false;

        foreach ($rules as $rule) {
            $id_rule = $rule['id'];
            $kode_penyakit = $rule['result'];
            $syarat_wajib = $rule['syarat_wajib'];
            $syarat_opsional = $rule['syarat_opsional'];

            if (in_array($kode_penyakit, $processed)) continue;

            // Jika semua syarat wajib terpenuhi
            if (count(array_intersect($syarat_wajib, $gejala_terpilih)) === count($syarat_wajib)) {
                $gejala_baru = array_intersect($syarat_opsional, $gejala_terpilih);
                $fakta_baru = $kode_penyakit;
                
                // Tambahkan fakta baru ke gejala terpilih
                $gejala_terpilih = array_unique(array_merge($gejala_terpilih, [$fakta_baru], $gejala_baru));

                $hasil_diagnosa[$kode_penyakit] = [
                    'gejala' => array_merge($syarat_wajib, $gejala_baru),
                    'fakta_baru' => $fakta_baru,
                ];

                $processed[] = $kode_penyakit;
                $new_gejala = true;

                // Simpan proses yang terjadi di setiap langkah
                $proses_forward_chaining[] = [
                    'id_rule' => $id_rule,
                    'kode_penyakit' => $kode_penyakit,
                    'syarat_wajib' => $syarat_wajib,
                    'syarat_opsional' => $syarat_opsional,
                    'gejala_sebelum' => $gejala_terpilih, // Simpan gejala sebelum eksekusi rule
                    'keterangan' => 'Rule dieksekusi karena semua gejala wajib terpenuhi',
                ];
                
            }
        }
    } while ($new_gejala);

    // Simpan langkah-langkah proses ke session
    $_SESSION['proses_forward_chaining'] = $proses_forward_chaining;

    return $hasil_diagnosa;
}

// Jalankan Forward Chaining
$diagnosa = forwardChaining($rules, $gejala_terpilih);

// Pastikan `id_akun` tersedia
$id_akun = $_SESSION['id_akun'] ?? null;
if (!$id_akun) {
    $_SESSION['hasil_diagnosa'] = "Gagal menyimpan data, silakan login ulang.";
    header("Location: ../hasil_diagnosacf.php");
    exit;
}

// Jika tidak ada penyakit yang terdeteksi
if (empty($diagnosa)) {
    $kode_penyakit = "NONE";
    $solusi_akhir = "Hasil diagnosa tidak menunjukkan gangguan.";
} else {
    // Kumpulkan hasil diagnosa
    $kode_penyakit_terdeteksi = [];
    
    foreach ($diagnosa as $kode => $data) {
        if (strpos($kode, 'P') === 0) {
            $kode_penyakit_terdeteksi[] = $kode;
        }
    }

    $kode_penyakit = !empty($kode_penyakit_terdeteksi) ? implode(", ", $kode_penyakit_terdeteksi) : "NONE";
    $solusi_akhir = "Silakan konsultasikan hasil ini dengan dokter untuk analisis lebih lanjut.";
}

// Simpan hasil ke database
try {
    if (!$conn) {
        throw new PDOException("Koneksi database gagal.");
    }

    $stmt = $conn->prepare("INSERT INTO rekam_medis (id_akun, kode_penyakit, kode_gejala, solusi, tanggal_konsultasi) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$id_akun, $kode_penyakit, implode(", ", $gejala_terpilih), $solusi_akhir]);

    // Simpan hasil ke session
    $_SESSION['hasil_diagnosa'] = [
        'kode_penyakit' => $kode_penyakit,
        'gejala' => $gejala_terpilih,
        'solusi' => $solusi_akhir,
    ];

    header("Location: ../hasil_diagnosafc.php");
    exit;
} catch (PDOException $e) {
    $_SESSION['hasil_diagnosa'] = "Error saat menyimpan data: " . $e->getMessage();
    header("Location: ../hasil_diagnosafc.php");
    exit;
}
?>
