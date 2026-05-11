<?php
session_start();
require_once '../config/db.php';

// Cek Keamanan
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
// Ambil data surat dan gabungkan dengan data penduduk untuk alamat yang lebih lengkap
$query = "SELECT s.*, p.alamat as alamat_lengkap, p.jenis_kelamin 
          FROM surat s 
          LEFT JOIN penduduk p ON s.nik = p.nik 
          WHERE s.id = $id";
$data = $conn->query($query)->fetch_assoc();

if (!$data) {
    die("Data surat tidak ditemukan.");
}

// Format Tanggal Indonesia
function tgl_indo($tanggal){
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak <?= $data['jenis_surat'] ?> - <?= $data['nama'] ?></title>
    <style>
        body { font-family: "Times New Roman", Times, serif; background-color: #ccc; padding: 20px; }
        .sheet { width: 210mm; height: 297mm; background: white; margin: 0 auto; padding: 20px 30px; box-sizing: border-box; box-shadow: 0 0 10px rgba(0,0,0,0.5); }
        
        /* KOP SURAT */
        .kop-surat { border-bottom: 3px double #000; padding-bottom: 5px; margin-bottom: 20px; text-align: center; position: relative; }
        .logo-desa { position: absolute; left: 0; top: 0; width: 80px; }
        .kop-text h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-text h1 { margin: 0; font-size: 22px; text-transform: uppercase; }
        .kop-text p { margin: 2px 0; font-size: 12px; font-style: italic; }

        /* ISI SURAT */
        .judul-surat { text-align: center; margin-bottom: 25px; }
        .judul-surat h3 { margin: 0; text-transform: uppercase; text-decoration: underline; font-size: 16px; }
        .judul-surat p { margin: 5px 0; font-size: 14px; }

        .isi-surat { font-size: 14px; line-height: 1.6; text-align: justify; }
        .identitas-table { margin: 15px 0 15px 40px; border-collapse: collapse; }
        .identitas-table td { padding: 2px 5px; vertical-align: top; }

        /* TANDA TANGAN */
        .ttd-container { margin-top: 50px; float: right; width: 250px; text-align: center; font-size: 14px; }
        .ttd-space { height: 80px; }

        /* ATURAN CETAK */
        @media print {
            body { background: none; padding: 0; }
            .sheet { box-shadow: none; margin: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom: 20px; text-align: center;">
    <button onclick="window.print()" style="padding: 10px 20px; background: #16a34a; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
        <i class="fas fa-print"></i> KLIK UNTUK CETAK SEKARANG
    </button>
    <p style="font-size: 12px; color: #666;">Gunakan browser Chrome/Edge, pilih 'Save as PDF' atau langsung ke Printer.</p>
</div>

<div class="sheet">
    <div class="kop-surat">
       <img src="../assets/logo-banyumas.png" class="logo-desa" alt="Logo Kabupaten Banyumas">
        <div class="kop-text">
            <h2>Pemerintah Kabupaten Banyumas</h2>
            <h2>Kecamatan Ajibarang</h2>
            <h1>Pemerintah Desa Darmakradenan</h1>
            <p>Alamat: Jl. Raya Darmakradenan No. 01, Kode Pos 53163</p>
        </div>
    </div>

    <div class="judul-surat">
        <h3><?= $data['jenis_surat'] ?></h3>
        <p>Nomor: 470 / <?= $data['id'] ?> / <?= date('Y') ?></p>
    </div>

    <div class="isi-surat">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa Darmakradenan, Kecamatan Ajibarang, Kabupaten Banyumas, menerangkan dengan sebenarnya bahwa:</p>
        
        <table class="identitas-table">
            <tr>
                <td width="150">Nama Lengkap</td>
                <td width="10">:</td>
                <td><strong><?= strtoupper($data['nama']) ?></strong></td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td><?= $data['nik'] ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?= $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= $data['alamat_lengkap'] ?: 'Warga Desa Darmakradenan' ?></td>
            </tr>
            <tr>
                <td>Keperluan</td>
                <td>:</td>
                <td><em><?= $data['keperluan'] ?></em></td>
            </tr>
        </table>

        <p>Orang tersebut di atas adalah benar-benar warga kami yang bertempat tinggal di Desa Darmakradenan. Sepanjang pengetahuan kami yang bersangkutan berkelakuan baik dan surat keterangan ini diberikan untuk memenuhi persyaratan administrasi yang bersangkutan.</p>
        
        <p>Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd-container">
        <p>Darmakradenan, <?= tgl_indo(date('Y-m-d')) ?></p>
        <p>Kepala Desa Darmakradenan</p>
        <div class="ttd-space"></div>
        <p><strong>( ............................................ )</strong></p>
    </div>
</div>

<script>
    // Otomatis memicu dialog print saat halaman dimuat
    // window.print();
</script>

</body>
</html>