<?php
session_start();
require_once '../config/db.php';

// Cek Keamanan
if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];

// SOLUSI: Mengembalikan ke p.alamat AS alamat_lengkap dan p.jenis_kelamin sesuai kolom fisik database Anda
$query = "SELECT s.*, p.alamat as alamat_lengkap, p.jenis_kelamin 
          FROM surat s 
          LEFT JOIN penduduk p ON s.nik = p.nik 
          WHERE s.id = $id";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    die("Data surat tidak ditemukan di dalam database.");
}

// Logika Jenis Kelamin Fleksibel (Mendukung format 'L', 'Laki-laki', 'P', maupun 'Perempuan')
$jk_mentah = strtoupper(trim($data['jenis_kelamin'] ?? ''));
$jenis_kelamin_cetak = '-';

if ($jk_mentah == 'L' || $jk_mentah == 'LAKI-LAKI') {
    $jenis_kelamin_cetak = 'Laki-laki';
} elseif ($jk_mentah == 'P' || $jk_mentah == 'PEREMPUAN') {
    $jenis_kelamin_cetak = 'Perempuan';
} else {
    $jenis_kelamin_cetak = $data['jenis_kelamin'] ?? '-'; // Fallback aman jika format berbeda
}

// Format Tanggal Indonesia
function tgl_indo($tanggal){
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}

// Format Nomor Surat (Menambahkan angka 0 di depan ID agar rapi, contoh: 001, 012)
$nomor_urut = str_pad($data['id'], 3, "0", STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat - <?= htmlspecialchars($data['nama']) ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* RESET & BASE SETUP */
        body { 
            font-family: "Times New Roman", Times, serif; 
            background-color: #e2e8f0; 
            margin: 0; 
            padding: 40px 20px; 
            font-size: 12pt;
            color: #000;
        }
        
        /* UKURAN KERTAS A4 (Standar Tata Naskah Dinas Resmi) */
        .sheet { 
            width: 210mm; 
            min-height: 297mm; 
            background: white; 
            margin: 0 auto; 
            padding: 2.5cm 3cm; /* Margin standar: Kiri/Kanan lebih lebar */
            box-sizing: border-box; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); 
            position: relative;
        }
        
        /* KOP SURAT */
        .kop-surat { 
            border-bottom: 4px double #000; 
            padding-bottom: 10px; 
            margin-bottom: 35px; 
            position: relative; 
            text-align: center; 
        }
        .logo-desa { 
            position: absolute; 
            left: 0; 
            top: 5px; 
            width: 90px; 
            height: auto;
        }
        .kop-text {
            padding: 0 90px; /* Menjaga teks tetap di tengah tanpa tertabrak logo */
        }
        .kop-text h2 { 
            margin: 0; 
            font-size: 14pt; 
            font-weight: normal; 
            text-transform: uppercase; 
            letter-spacing: 1px;
        }
        .kop-text h1 { 
            margin: 3px 0; 
            font-size: 18pt; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 1.5px;
        }
        .kop-text p { 
            margin: 0; 
            font-size: 11pt; 
        }

        /* JUDUL SURAT */
        .judul-surat { 
            text-align: center; 
            margin-bottom: 35px; 
        }
        .judul-surat h3 { 
            margin: 0 0 2px 0; 
            text-transform: uppercase; 
            text-decoration: underline; 
            font-size: 14pt; 
            font-weight: bold;
        }
        .judul-surat p { 
            margin: 0; 
            font-size: 12pt; 
        }

        /* ISI SURAT */
        .isi-surat p { 
            font-size: 12pt; 
            line-height: 1.5; 
            text-align: justify; 
            margin-top: 0;
            margin-bottom: 15px;
            text-indent: 40px; /* Paragraf menjorok ke dalam */
        }
        
        .identitas-table { 
            margin: 10px 0 20px 40px; 
            border-collapse: collapse; 
            width: 90%;
            font-size: 12pt;
        }
        .identitas-table td { 
            padding: 4px 5px; 
            vertical-align: top; 
            line-height: 1.4;
        }

        /* TANDA TANGAN */
        .ttd-container { 
            margin-top: 50px; 
            float: right; 
            width: 280px; 
            text-align: center; 
            font-size: 12pt; 
        }
        .ttd-container p {
            margin: 0 0 5px 0;
        }
        .ttd-space { 
            height: 90px; /* Ruang untuk stempel dan tanda tangan asli */
        }

        /* PANEL KONTROL (Hanya tampil di layar monitor) */
        .control-panel {
            max-width: 210mm;
            margin: 0 auto 20px auto;
            text-align: center;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .btn-print {
            padding: 12px 25px; 
            background: #16a34a; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: bold;
            font-size: 14px;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover { background: #15803d; }
        .help-text { font-size: 13px; color: #64748b; margin-top: 10px; margin-bottom: 0; }

        /* ATURAN SAAT DICETAK KE KERTAS / PDF */
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
        <i class="fas fa-print"></i> CETAK DOKUMEN RESMI
    </button>
    <p class="help-text">Gunakan browser Google Chrome atau Microsoft Edge. Tekan <strong>Ctrl+P</strong> dan pastikan pengaturan margin diset ke 'Default' atau 'None'.</p>
</div>

<div class="sheet">
    
    <div class="kop-surat">
       <img src="../assets/logo-banyumas.png" class="logo-desa" alt="Logo Kabupaten Banyumas">
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
            <tr>
                <td width="160">Nama Lengkap</td>
                <td width="15">:</td>
                <td><strong><?= strtoupper(htmlspecialchars($data['nama'])) ?></strong></td>
            </tr>
            <tr>
                <td>Nomor Induk Kependudukan</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['nik']) ?></td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td><?= $jenis_kelamin_cetak ?></td>
            </tr>
            <tr>
                <td>Alamat Lengkap</td>
                <td>:</td>
                <td><?= htmlspecialchars($data['alamat_lengkap'] ?: 'Desa Darmakradenan, Kec. Ajibarang') ?></td>
            </tr>
            <tr>
                <td>Keperluan Pengajuan</td>
                <td>:</td>
                <td><em><?= htmlspecialchars($data['keperluan']) ?></em></td>
            </tr>
        </table>

        <p>Orang tersebut di atas adalah benar-benar warga yang bertempat tinggal di wilayah Desa Darmakradenan. Sepanjang pengamatan dan pengetahuan kami, yang bersangkutan berkelakuan baik di tengah masyarakat. Surat keterangan ini diterbitkan guna memenuhi persyaratan administrasi yang bersangkutan.</p>
        
        <p>Demikian surat keterangan ini kami buat dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya oleh pihak yang berkepentingan.</p>
    </div>

    <div class="ttd-container">
        <p>Darmakradenan, <?= tgl_indo(date('Y-m-d')) ?></p>
        <p>Kepala Desa Darmakradenan</p>
        <div class="ttd-space"></div>
        <p><strong>( .................................................... )</strong></p>
    </div>
    
    <div style="clear: both;"></div>
</div>

</body>
</html>