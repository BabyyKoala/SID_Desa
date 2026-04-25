<?php
// ============================================
// config/db.php - Koneksi Database
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sid_desa');
define('BASE_URL', 'http://localhost/sid-desa');
define('WA_NUMBER', '6281234567890'); // Ganti dengan nomor WA admin desa

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;padding:40px;text-align:center;color:#dc2626;">
        <h2>⚠️ Koneksi Database Gagal</h2>
        <p>Pastikan MySQL berjalan dan konfigurasi database sudah benar di <code>config/db.php</code></p>
        <p>Error: ' . $conn->connect_error . '</p>
    </div>');
}

$conn->set_charset("utf8mb4");

// Helper: Generate kode pengajuan unik
function generateKode() {
    return 'SRT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

// Helper: Format rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Helper: Format tanggal Indonesia
function formatTanggal($tanggal) {
    $bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $t = explode('-', date('Y-m-d', strtotime($tanggal)));
    return (int)$t[2] . ' ' . $bulan[(int)$t[1]] . ' ' . $t[0];
}

// Helper: Sanitize input
function clean($str) {
    global $conn;
    return htmlspecialchars(strip_tags($conn->real_escape_string(trim($str))));
}

// Helper: Check admin login
function isAdmin() {
    return isset($_SESSION['admin_id']);
}

// Helper: Redirect
function redirect($url) {
    header("Location: $url");
    exit;
}
?>
