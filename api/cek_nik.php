<?php
// api/cek_nik.php
require_once '../config/db.php';

// Atur header agar merespons dalam format JSON (Standar API)
header('Content-Type: application/json');

$nik = $_GET['nik'] ?? '';

if (strlen($nik) === 16) {
    $stmt = $conn->prepare("SELECT nama FROM penduduk WHERE nik = ?");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        // NIK Ditemukan
        echo json_encode([
            'status' => 'success',
            'nama' => $row['nama']
        ]);
    } else {
        // NIK Tidak Ditemukan
        echo json_encode([
            'status' => 'error',
            'message' => 'NIK tidak terdaftar dalam data kependudukan desa.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Format NIK harus 16 digit.'
    ]);
}
?>