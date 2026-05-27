<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$nik = $_GET['nik'] ?? '';

if(strlen($nik) === 16) {
    // PASTIKAN SELECT MENGAMBIL jenis_kelamin DAN alamat
    $stmt = $conn->prepare("SELECT nama, jenis_kelamin, alamat FROM penduduk WHERE nik = ?");
    $stmt->bind_param("s", $nik);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'alamat' => $data['alamat']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'NIK tidak ditemukan']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Format NIK tidak valid']);
}
?>