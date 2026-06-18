<?php
session_start();
require_once '../config/db.php';

$data = null;
$akses_publik = false;

// LOGIKA KEAMANAN PINTAR (Bisa diakses Admin ATAU Warga yang punya Kode Resi)
if (isset($_SESSION['admin_name']) && isset($_GET['id'])) {
    // 1. Akses Admin menggunakan ID
    $id = (int)$_GET['id'];
    $query = "SELECT s.*, p.alamat as alamat_lengkap, p.jenis_kelamin 
              FROM surat s LEFT JOIN penduduk p ON s.nik = p.nik 
              WHERE s.id = $id";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) $data = $result->fetch_assoc();

} elseif (isset($_GET['kode'])) {
    // 2. Akses Publik (Warga) menggunakan Kode Pengajuan
    $kode = $conn->real_escape_string($_GET['kode']);
    // PASTIKAN: Warga hanya bisa melihat surat jika statusnya sudah 'Selesai'
    $query = "SELECT s.*, p.alamat as alamat_lengkap, p.jenis_kelamin 
              FROM surat s LEFT JOIN penduduk p ON s.nik = p.nik 
              WHERE s.kode_pengajuan = '$kode' AND s.status = 'Selesai'";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $akses_publik = true; // Tandai bahwa ini diakses oleh warga
    }
}

if (!$data) {
    die("<h2 style='text-align:center; margin-top:50px; font-family:sans-serif;'>Akses ditolak. Surat belum selesai diproses atau kode tidak valid.</h2>");
}

// Logika Jenis Kelamin
$jk_mentah = strtoupper(trim($data['jenis_kelamin'] ?? ''));
$jenis_kelamin_cetak = ($jk_mentah == 'L' || $jk_mentah == 'LAKI-LAKI') ? 'Laki-laki' : (($jk_mentah == 'P' || $jk_mentah == 'PEREMPUAN') ? 'Perempuan' : '-');

// Format Tanggal Indonesia
function tgl_indo($tanggal){
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

$nomor_urut = str_pad($data['id'], 3, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat - <?= htmlspecialchars($data['nama']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: "Times New Roman", Times, serif; background-color: #e2e8f0; margin: 0; padding: 40px 20px; font-size: 12pt; color: #000; }
        .sheet { width: 210mm; min-height: 297mm; background: white; margin: 0 auto; padding: 2.5cm 3cm; box-sizing: border-box; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); position: relative; }
        .kop-surat { border-bottom: 4px double #000; padding-bottom: 10px; margin-bottom: 35px; position: relative; text-align: center; }
        .logo-desa { position: absolute; left: 0; top: 5px; width: 90px; height: auto; }
        .kop-text { padding: 0 90px; }
        .kop-text h2 { margin: 0; font-size: 14pt; font-weight: normal; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text h1 { margin: 3px 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; }
        .kop-text p { margin: 0; font-size: 11pt; }
        .judul-surat { text-align: center; margin-bottom: 35px; }
        .judul-surat h3 { margin: 0 0 2px 0; text-transform: uppercase; text-decoration: underline; font-size: 14pt; font-weight: bold; }
        .judul-surat p { margin: 0; font-size: 12pt; }
        .isi-surat p { font-size: 12pt; line-height: 1.5; text-align: justify; margin-top: 0; margin-bottom: 15px; text-indent: 40px; }
        .identitas-table { margin: 10px 0 20px 40px; border-collapse: collapse; width: 90%; font-size: 12pt; }
        .identitas-table td { padding: 4px 5px; vertical-align: top; line-height: 1.4; }

        /* ======== TATA LETAK TTD DIGITAL ======== */
        .pengesahan-container { display: flex; justify-content: flex-end; margin-top: 50px; }
        
        .ttd-container { width: 280px; text-align: center; font-size: 12pt; position: relative; }
        .ttd-container p { margin: 0 0 5px 0; }
        
        /* Area tempat stempel dan TTD numpuk */
        .ttd-digital-area { position: relative; height: 110px; margin: 10px 0; }
        
        /* Gambar TTD asli (Pastikan file transparan PNG) */
        .img-ttd { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); width: 140px; z-index: 2; }
        
        /* Gambar Stempel asli (Pastikan file transparan PNG) */
        .img-stempel { position: absolute; left: 10px; top: 10px; width: 110px; z-index: 1; opacity: 0.85; }
        
        .nama-kades { font-weight: bold; text-decoration: underline; position: relative; z-index: 3; }

        .control-panel { max-width: 210mm; margin: 0 auto 20px auto; text-align: center; background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .btn-print { padding: 12px 25px; background: <?= $akses_publik ? '#2563eb' : '#16a34a' ?>; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; }
        .help-text { font-size: 13px; color: #64748b; margin-top: 10px; margin-bottom: 0; }

        @media print {
            body { background: none; padding: 0; }
            .sheet { box-shadow: none; margin: 0; padding: 2.5cm 3cm; width: 100%; height: 100%; }
            .no-print { display: none !important; }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

<div class="control-panel no-print">
    <button onclick="window.print()" class="btn-print">
        <i class="fas <?= $akses_publik ? 'fa-download' : 'fa-print' ?>"></i> 
        <?= $akses_publik ? 'DOWNLOAD / SIMPAN PDF' : 'CETAK DOKUMEN RESMI' ?>
    </button>
    <p class="help-text">
        <?= $akses_publik ? "Untuk mendownload, tekan tombol di atas, lalu ubah <strong>'Destination' (Tujuan)</strong> menjadi <strong>'Save as PDF' (Simpan sebagai PDF)</strong>." : "Gunakan browser Chrome/Edge. Tekan <strong>Ctrl+P</strong> dan pastikan margin diset ke 'Default'." ?>
    </p>
</div>

<div class="sheet">
    <div class="kop-surat">
       <img src="../assets/logo-banyumas.png" class="logo-desa" alt="Logo" onerror="this.style.display='none'">
        <div class="kop-text">
            <h2>Pemerintah Kabupaten Banyumas</h2>
            <h2>Kecamatan Ajibarang</h2>
            <h1>Pemerintah Desa Darmakradenan</h1>
            <p>Jalan Raya Darmakradenan No. 01, Kode Pos 53163</p>
        </div>
    </div>

    <div class="judul-surat">
        <h3><?= htmlspecialchars($data['jenis_surat']) ?></h3>
        <p>Nomor: 470 / <?= $nomor_urut ?> / <?= date('Y') ?></p>
    </div>

    <div class="isi-surat">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Darmakradenan, Kecamatan Ajibarang, Kabupaten Banyumas, menerangkan dengan sebenarnya bahwa:</p>
        
        <table class="identitas-table">
            <tr><td width="160">Nama Lengkap</td><td width="15">:</td><td><strong><?= strtoupper(htmlspecialchars($data['nama'])) ?></strong></td></tr>
            <tr><td>Nomor Induk Kependudukan</td><td>:</td><td><?= htmlspecialchars($data['nik']) ?></td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td><?= $jenis_kelamin_cetak ?></td></tr>
            <tr><td>Alamat Lengkap</td><td>:</td><td><?= htmlspecialchars($data['alamat_lengkap'] ?: 'Desa Darmakradenan, Kec. Ajibarang') ?></td></tr>
            <tr><td>Keperluan Pengajuan</td><td>:</td><td><em><?= htmlspecialchars($data['keperluan']) ?></em></td></tr>
        </table>

        <p>Orang tersebut di atas adalah benar-benar warga yang bertempat tinggal di wilayah Desa Darmakradenan. Sepanjang pengamatan dan pengetahuan kami, yang bersangkutan berkelakuan baik di tengah masyarakat. Surat keterangan ini diterbitkan guna memenuhi persyaratan administrasi yang bersangkutan.</p>
        <p>Demikian surat keterangan ini kami buat secara elektronik dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya oleh pihak yang berkepentingan.</p>
    </div>

    <div class="pengesahan-container">
        <div class="ttd-container">
            <p>Darmakradenan, <?= tgl_indo(date('Y-m-d')) ?></p>
            <p>Kepala Desa Darmakradenan</p>
            
            <div class="ttd-digital-area">
                <img src="../assets/stempel-desa.png" class="img-stempel" alt="Stempel" onerror="this.style.opacity='0'">
                <img src="../assets/ttd-kades.png" class="img-ttd" alt="TTD" onerror="this.style.opacity='0'">
            </div>
            
            <p class="nama-kades">KH. IMAM WS</p>
            <p style="margin:0; font-size: 10pt;">NIP. 19800101 201001 1 001</p>
        </div>
    </div>
    <div style="clear: both;"></div>
</div>

<?php if($akses_publik): ?>
<script>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 800); // Muncul otomatis setelah 0.8 detik
    }
</script>
<?php endif; ?>

</body>
</html>