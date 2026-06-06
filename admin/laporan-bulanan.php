<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit;
}

$bulan_pilih = isset($_GET['bulan']) ? str_pad($_GET['bulan'], 2, "0", STR_PAD_LEFT) : date('m');
$tahun_pilih = isset($_GET['tahun']) ? (int)$_GET['tahun'] : (int)date('Y');

$nama_bulan = [
    '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April',
    '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus',
    '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
];

// ============================================
// HELPER: Bersihkan \r\n literal → paragraf
// ============================================
function format_isi($teks) {
    $teks = str_replace(['\r\n', '\r\n', '\r', '\n'], "\n", $teks);
    $teks = trim($teks);
    $paragraf = array_filter(array_map('trim', explode("\n", $teks)));
    $output = '';
    foreach ($paragraf as $p) {
        $output .= '<p style="margin:0 0 6px 0;">' . htmlspecialchars($p) . '</p>';
    }
    return $output ?: htmlspecialchars($teks);
}

// ============================================
// QUERY SURAT
// ============================================
$stmt = $conn->prepare("SELECT * FROM surat WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ? ORDER BY tanggal DESC");
$stmt->bind_param("si", $bulan_pilih, $tahun_pilih);
$stmt->execute();
$result_surat    = $stmt->get_result();
$total_surat     = $result_surat->num_rows;
$data_surat      = $result_surat->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ============================================
// QUERY PENGADUAN
// ============================================
$stmt = $conn->prepare("SELECT * FROM pengaduan WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ? ORDER BY tanggal DESC");
$stmt->bind_param("si", $bulan_pilih, $tahun_pilih);
$stmt->execute();
$result_pengaduan  = $stmt->get_result();
$total_pengaduan   = $result_pengaduan->num_rows;
$data_pengaduan    = $result_pengaduan->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ============================================
// QUERY PENGADUAN SELESAI
// ============================================
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM pengaduan WHERE status = 'Selesai' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?");
$stmt->bind_param("si", $bulan_pilih, $tahun_pilih);
$stmt->execute();
$pengaduan_selesai = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$stmt->close();

$page_title = 'Laporan Bulanan Desa';
require_once 'layout.php';
?>

<style>
    .print-only { display: none; }

    @media print {
        @page { size: A4 portrait; margin: 1.5cm 1cm 1.5cm 1cm; }

        body {
            background-color: white !important;
            font-size: 11pt !important;
            color: black !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #sidebar, .fixed.top-0, #action-buttons, .no-print, nav, footer {
            display: none !important;
        }

        .lg\:ml-64, .pt-14, .lg\:pt-0, .pb-12 {
            margin: 0 !important;
            padding: 0 !important;
        }

        .max-w-7xl  { max-width: 100% !important; }
        .print-only { display: block !important; }

        .shadow-sm, .shadow-md, .rounded-2xl, .rounded-xl, .rounded-lg {
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .bg-white { background-color: transparent !important; border: none !important; }

        .overflow-x-auto, .overflow-y-hidden, .overflow-hidden {
            overflow: visible !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            page-break-inside: auto !important;
            margin-bottom: 20px !important;
        }

        tr  { page-break-inside: avoid !important; page-break-after: auto !important; }

        td, th {
            border: 1px solid #333 !important;
            padding: 8px !important;
            background-color: white !important;
            color: black !important;
        }

        th {
            background-color: #f2f2f2 !important;
            font-weight: bold !important;
            text-align: center !important;
        }

        .isi-cell p { margin: 0 0 4px 0 !important; font-size: 10pt !important; }

        .section-title   { page-break-after: avoid !important; margin-bottom: 10px !important; }
        .page-break-before { page-break-before: always !important; }

        .badge-print {
            border: 1px solid #666 !important;
            padding: 2px 6px !important;
            border-radius: 4px !important;
            font-weight: bold !important;
            font-size: 10pt !important;
            display: inline-block !important;
            background: transparent !important;
            color: black !important;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .kop-surat h2 { font-size: 14pt; margin: 0; font-weight: normal; text-transform: uppercase; }
        .kop-surat h1 { font-size: 18pt; margin: 5px 0 0 0; font-weight: bold; text-transform: uppercase; }

        .grid-print {
            display: flex !important;
            justify-content: space-between !important;
            margin-bottom: 30px !important;
            border-bottom: 1px solid #ccc !important;
            padding-bottom: 20px !important;
        }
        .grid-item-print { text-align: center !important; flex: 1 !important; border-right: 1px solid #ccc !important; }
        .grid-item-print:last-child { border-right: none !important; }
        .grid-item-print .angka { font-size: 24pt !important; font-weight: bold !important; margin-top: 5px !important; }
        .grid-item-print .label { font-size: 10pt !important; text-transform: uppercase !important; color: #555 !important; }
    }
</style>

<div class="max-w-7xl mx-auto pb-12" id="main-container">

    <div class="no-print">
        <div id="action-buttons" class="flex flex-col xl:flex-row justify-between items-start xl:items-end mb-8 gap-5 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600">
                        <i class="fas fa-chart-line text-lg"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Laporan Terpadu</h1>
                </div>
                <p class="text-gray-500 text-sm">Rekapitulasi Pelayanan Bulan
                    <strong class="text-gray-700"><?= $nama_bulan[$bulan_pilih] ?> <?= $tahun_pilih ?></strong>
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                <form method="GET" action="" class="flex flex-wrap gap-2 items-center bg-gray-50 p-1.5 rounded-xl border border-gray-200">
                    <select name="bulan" class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer shadow-sm">
                        <?php foreach($nama_bulan as $angka => $nama): ?>
                            <option value="<?= $angka ?>" <?= ($bulan_pilih == $angka) ? 'selected' : '' ?>><?= $nama ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="tahun" class="bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer shadow-sm">
                        <?php for($t = (int)date('Y'); $t >= 2024; $t--): ?>
                            <option value="<?= $t ?>" <?= ($tahun_pilih == $t) ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-gray-900 transition shadow-sm">Terapkan</button>
                </form>

                <div class="h-8 w-px bg-gray-200 hidden xl:block mx-1"></div>

                <button onclick="window.print()" class="bg-red-600 text-white px-5 py-2 rounded-xl text-sm font-bold hover:bg-red-700 transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Download PDF / Print
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-6 shadow-sm border border-blue-100 flex items-center gap-5 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-blue-100 opacity-50"><i class="fas fa-file-signature text-8xl"></i></div>
                <div class="w-14 h-14 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-md shadow-blue-200 z-10"><i class="fas fa-file-signature"></i></div>
                <div class="z-10">
                    <div class="text-[11px] font-extrabold text-blue-600 uppercase tracking-widest mb-1">Surat Terbit</div>
                    <div class="text-4xl font-extrabold text-gray-900"><?= $total_surat ?></div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-white rounded-2xl p-6 shadow-sm border border-orange-100 flex items-center gap-5 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-orange-100 opacity-50"><i class="fas fa-bullhorn text-8xl"></i></div>
                <div class="w-14 h-14 bg-orange-500 text-white rounded-2xl flex items-center justify-center text-2xl shadow-md shadow-orange-200 z-10"><i class="fas fa-bullhorn"></i></div>
                <div class="z-10">
                    <div class="text-[11px] font-extrabold text-orange-600 uppercase tracking-widest mb-1">Laporan Masuk</div>
                    <div class="text-4xl font-extrabold text-gray-900"><?= $total_pengaduan ?></div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 shadow-sm border border-green-100 flex items-center gap-5 relative overflow-hidden">
                <div class="absolute -right-4 -top-4 text-green-100 opacity-50"><i class="fas fa-check-circle text-8xl"></i></div>
                <div class="w-14 h-14 bg-green-500 text-white rounded-2xl flex items-center justify-center text-2xl shadow-md shadow-green-200 z-10"><i class="fas fa-check-circle"></i></div>
                <div class="z-10">
                    <div class="text-[11px] font-extrabold text-green-600 uppercase tracking-widest mb-1">Aduan Selesai</div>
                    <div class="text-4xl font-extrabold text-gray-900"><?= $pengaduan_selesai ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="print-only kop-surat">
        <h2>Pemerintah Kabupaten Banyumas</h2>
        <h2>Kecamatan Ajibarang</h2>
        <h1>Desa Darmakradenan</h1>
        <p style="margin-top:10px;">Laporan Resmi Rekapitulasi Pelayanan Administrasi &amp; Pengaduan Warga</p>
        <p style="font-weight:bold;margin-top:5px;">Periode: <?= $nama_bulan[$bulan_pilih] ?> <?= $tahun_pilih ?></p>
    </div>

    <div class="print-only grid-print">
        <div class="grid-item-print"><div class="label">Surat Terbit</div><div class="angka"><?= $total_surat ?></div></div>
        <div class="grid-item-print"><div class="label">Pengaduan Masuk</div><div class="angka"><?= $total_pengaduan ?></div></div>
        <div class="grid-item-print"><div class="label">Pengaduan Selesai</div><div class="angka"><?= $pengaduan_selesai ?></div></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
        <div class="section-title bg-gray-50/80 px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div class="no-print w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-envelope-open-text text-sm"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-base uppercase tracking-wide">Log Pengajuan Surat</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white text-gray-500 border-b-2 border-gray-100 whitespace-nowrap">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-12 text-center">No</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-28 text-center">Tanggal</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-36 text-center">Kode Resi</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] min-w-[200px]">Nama Pemohon &amp; NIK</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px]">Jenis Layanan</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-32 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if ($total_surat > 0): $no = 1;
                    foreach ($data_surat as $row): 
                        $status_surat = $row['status'] ?? 'Menunggu';
                        $badge_color_surat = 'bg-gray-100 text-gray-600';
                        if (in_array($status_surat, ['Menunggu', 'Baru', 'Pending'])) $badge_color_surat = 'bg-red-50 text-red-700 border border-red-200';
                        if (in_array($status_surat, ['Diproses', 'Proses'])) $badge_color_surat = 'bg-amber-50 text-amber-700 border border-amber-200';
                        if (in_array($status_surat, ['Selesai', 'Disetujui', 'Siap Diambil', 'Tercetak']))  $badge_color_surat = 'bg-green-50 text-green-700 border border-green-200';
                        if (in_array($status_surat, ['Ditolak', 'Batal']))  $badge_color_surat = 'bg-gray-100 text-gray-500 border border-gray-200';
                    ?>
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4 text-gray-500 font-medium text-center align-middle"><?= $no++ ?></td>
                        
                        <td class="px-6 py-4 text-gray-800 font-semibold text-center align-middle whitespace-nowrap">
                            <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                        </td>
                        
                        <td class="px-6 py-4 text-center align-middle whitespace-nowrap">
                            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-3 py-1 rounded-md font-mono text-xs font-bold badge-print">
                                <?= htmlspecialchars($row['kode_pengajuan']) ?>
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 align-middle">
                            <div class="font-bold text-gray-900"><?= htmlspecialchars($row['nama']) ?></div>
                            <div class="text-xs text-gray-500 font-mono mt-0.5"><?= htmlspecialchars($row['nik'] ?? '-') ?></div>
                        </td>
                        
                        <td class="px-6 py-4 font-medium text-gray-600 align-middle"><?= htmlspecialchars($row['jenis_surat']) ?></td>
                        
                        <td class="px-6 py-4 align-middle text-center">
                            <span class="inline-block px-3 py-1.5 text-[11px] uppercase tracking-wider font-extrabold rounded-lg <?= $badge_color_surat ?> badge-print">
                                <?= htmlspecialchars($status_surat) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 font-medium">
                            Tidak ada transaksi pelayanan surat pada bulan ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="page-break-before bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="section-title bg-gray-50/80 px-6 py-5 border-b border-gray-200 flex items-center gap-3">
            <div class="no-print w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-bullhorn text-sm"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-base uppercase tracking-wide">Log Pengaduan Warga</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white text-gray-500 border-b-2 border-gray-100 whitespace-nowrap">
                    <tr>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-12 text-center">No</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-32 text-center">Tanggal</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] min-w-[180px]">Nama Pelapor</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] min-w-[250px]">Isi Pengaduan</th>
                        <th class="px-6 py-4 font-bold uppercase tracking-wider text-[11px] w-32 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if ($total_pengaduan > 0): $no = 1;
                    foreach ($data_pengaduan as $row):
                        $status = $row['status'];
                        $badge_color = 'bg-gray-100 text-gray-600';
                        if (in_array($status, ['Menunggu','Masuk'])) $badge_color = 'bg-red-50 text-red-700 border border-red-200';
                        if ($status === 'Diproses') $badge_color = 'bg-amber-50 text-amber-700 border border-amber-200';
                        if ($status === 'Selesai')  $badge_color = 'bg-green-50 text-green-700 border border-green-200';
                        if ($status === 'Ditolak')  $badge_color = 'bg-gray-100 text-gray-500 border border-gray-200';
                    ?>
                    <tr class="hover:bg-orange-50/30 transition-colors">
                        <td class="px-6 py-4 text-gray-500 font-medium align-top text-center"><?= $no++ ?></td>

                        <td class="px-6 py-4 text-gray-800 font-semibold align-top text-center whitespace-nowrap">
                            <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                        </td>

                        <td class="px-6 py-4 align-top">
                            <div class="font-bold text-gray-900"><?= htmlspecialchars($row['nama']) ?></div>
                        </td>

                        <td class="px-6 py-4 align-top isi-cell">
                            <div class="text-gray-800 leading-relaxed text-sm">
                                <?= format_isi($row['isi']) ?>
                            </div>
                        </td>

                        <td class="px-6 py-4 align-top text-center">
                            <span class="inline-block px-3 py-1.5 text-[11px] uppercase tracking-wider font-extrabold rounded-lg <?= $badge_color ?> badge-print">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-400 font-medium">
                            Tidak ada data pengaduan masuk pada bulan ini.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="print-only" style="margin-top:50px;width:100%;">
        <div style="float:right;width:250px;text-align:center;">
            <p style="margin:0;font-size:11pt;">
                Darmakradenan, <?= date('d') ?> <?= $nama_bulan[(string)date('m')] ?> <?= date('Y') ?>
            </p>
            <p style="margin:0;font-size:11pt;font-weight:bold;">Kepala Desa Darmakradenan</p>
            <div style="height:100px;"></div>
            <p style="margin:0;font-size:11pt;font-weight:bold;border-bottom:1px solid black;padding-bottom:5px;">
                ( .................................................. )
            </p>
        </div>
        <div style="clear:both;"></div>
    </div>

</div>

<?php require_once 'layout-footer.php'; ?>