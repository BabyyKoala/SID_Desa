<?php
require_once '../config/db.php';

// Fallback pelindung jika fungsi clean belum ada
if (!function_exists('clean')) {
    function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}

// Fallback pelindung jika fungsi formatTanggal belum ada
if (!function_exists('formatTanggal')) {
    function formatTanggal($date) {
        return $date ? date('d M Y, H:i', strtotime($date)) : '-';
    }
}

// Fallback untuk nomor WhatsApp
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', '6282134655359'); 
}

$page_title = 'Cek Status Surat';
$result = null;
$searched = false;

if(isset($_GET['kode']) || isset($_GET['nik'])) {
    $searched = true;
    $input = clean($_GET['kode'] ?? $_GET['nik'] ?? '');
    if($input) {
        $stmt = $conn->prepare("SELECT * FROM surat WHERE kode_pengajuan = ? OR nik = ? ORDER BY tanggal DESC");
        $stmt->bind_param("ss", $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

require_once '../config/header.php';

// Memperbaiki badgeStatus agar menggunakan utility class Tailwind bawaan secara konsisten
function badgeStatus($status) {
    $map = [
        'Diproses' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
        'Selesai'  => 'bg-green-100 text-green-700 border border-green-200',
        'Ditolak'  => 'bg-red-100 text-red-700 border border-red-200',
    ];
    $icons = [
        'Diproses' => 'fa-clock',
        'Selesai'  => 'fa-check-circle',
        'Ditolak'  => 'fa-times-circle',
    ];
    $cls = $map[$status] ?? 'bg-gray-100 text-gray-600 border border-gray-200';
    $ic  = $icons[$status] ?? 'fa-circle';
    return "<span class='inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider $cls'><i class='fas $ic'></i> $status</span>";
}
?>

<?php if($searched && (!$result || $result->num_rows === 0)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'error',
        title: 'Tidak Ditemukan',
        text: 'Kode pengajuan atau NIK yang Anda masukkan tidak terdaftar dalam sistem kami. Silakan periksa kembali.',
        confirmButtonColor: '#3b82f6', // blue-500 menyesuaikan tema halaman ini
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
});
</script>
<?php endif; ?>

<div class="max-w-2xl mx-auto px-4 py-10 min-h-[70vh]">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Cek Status Surat</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-search text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">Cek Status Pengajuan</h1>
                    <p class="text-blue-100 text-sm">Masukkan kode pengajuan atau NIK Anda</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <form method="GET" action="cek-status.php" class="flex flex-col sm:flex-row gap-3 mb-6">
                <input type="text" name="kode"
                       value="<?= htmlspecialchars($_GET['kode'] ?? $_GET['nik'] ?? '') ?>"
                       placeholder="Kode (contoh: SRT-20250101-ABCD12) atau NIK"
                       class="flex-1 border border-gray-200 rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition"
                       required>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3.5 rounded-xl flex items-center justify-center gap-2 transition shadow-sm">
                    <i class="fas fa-search"></i>
                    <span>Cari Pengajuan</span>
                </button>
            </form>

            <?php if($searched): ?>
                <?php if($result && $result->num_rows > 0): ?>
                    <div class="space-y-4 mt-8">
                        <h3 class="text-gray-500 text-sm font-semibold mb-3 border-b pb-2">Hasil Pencarian:</h3>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <div class="border border-gray-100 rounded-xl p-5 bg-gray-50/50 hover:bg-gray-50 transition">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4">
                                <div>
                                    <div class="font-mono text-primary-700 bg-primary-50 px-2.5 py-1 rounded inline-block font-bold text-sm mb-1"><?= htmlspecialchars($row['kode_pengajuan']) ?></div>
                                    <div class="text-xs text-gray-400 mt-1"><i class="far fa-calendar-alt mr-1"></i> Diajukan: <?= formatTanggal($row['tanggal']) ?></div>
                                </div>
                                <div class="shrink-0 mt-2 sm:mt-0">
                                    <?= badgeStatus($row['status']) ?>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm bg-white p-4 rounded-lg border border-gray-100">
                                <div>
                                    <div class="text-xs text-gray-400 mb-0.5">Nama Lengkap</div>
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 mb-0.5">NIK</div>
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['nik']) ?></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 mb-0.5">Jenis Surat</div>
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['jenis_surat']) ?></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-400 mb-0.5">Keperluan</div>
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['keperluan']) ?></div>
                                </div>
                            </div>
                            
                            <?php if($row['status'] == 'Selesai'): ?>
                            <div class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4 sm:p-5 text-sm text-green-800 shadow-sm relative overflow-hidden">
                                <i class="fas fa-check-circle absolute -right-4 -bottom-4 text-7xl text-green-500 opacity-10"></i>
                                
                                <div class="flex items-start gap-3 mb-4 relative z-10">
                                    <div class="bg-green-500 text-white p-2 rounded-full mt-0.5 shadow-sm">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div>
                                        <strong class="text-base text-green-900">Surat Anda Telah Terbit!</strong><br>
                                        <span class="text-green-700">Silakan pilih metode pengambilan dokumen di bawah ini:</span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 relative z-10">
                                    <a href="../admin/cetak-surat.php?kode=<?= htmlspecialchars($row['kode_pengajuan']) ?>" target="_blank"
                                       class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-3.5 rounded-xl flex items-center justify-center gap-2 transition shadow hover:-translate-y-0.5">
                                        <i class="fas fa-file-pdf text-lg"></i> Download PDF (TTD Digital)
                                    </a>
                                    
                                    <div class="bg-white border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                                        <div class="bg-green-100 p-2.5 rounded-lg shrink-0 text-green-600">
                                            <i class="fas fa-building text-lg"></i>
                                        </div>
                                        <span class="text-xs font-medium leading-snug">Atau ambil fisik dokumen dengan <strong>stempel basah</strong> di Balai Desa saat jam kerja.</span>
                                    </div>
                                </div>
                            </div>

                            <?php elseif($row['status'] == 'Ditolak'): ?>
                            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3.5 text-sm text-red-700 flex items-start gap-2.5">
                                <i class="fas fa-times-circle mt-0.5"></i>
                                <div>
                                    <strong>Pengajuan ditolak.</strong><br>
                                    Kemungkinan ada ketidaksesuaian data. Hubungi admin untuk informasi lebih lanjut.
                                </div>
                            </div>
                            <?php endif; ?>

                            <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+ingin+menanyakan+status+surat+dengan+kode+*<?= htmlspecialchars($row['kode_pengajuan']) ?>*+atas+nama+*<?= urlencode($row['nama']) ?>*.+Terima+kasih."
                               target="_blank"
                               class="mt-4 w-full flex items-center justify-center gap-2 text-sm bg-white border border-green-200 text-green-600 px-4 py-3 rounded-lg hover:bg-green-50 hover:text-green-700 transition font-semibold shadow-sm">
                                <i class="fab fa-whatsapp text-lg"></i> Tanya Admin via WhatsApp
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-xl mt-6">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search-minus text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 text-sm mb-5 px-4">Pastikan kode pengajuan (misal: SRT-XXXX) atau 16 digit NIK yang Anda masukkan sudah benar.</p>
                    <a href="ajukan-surat.php" class="btn-primary text-white font-semibold px-6 py-3 rounded-xl inline-block shadow-sm">
                        Ajukan Surat Baru
                    </a>
                </div>
                <?php endif; ?>
            <?php else: ?>
            <div class="text-center py-10 mt-2">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-receipt text-3xl text-blue-400"></i>
                </div>
                <p class="text-gray-500 text-sm mb-2 font-medium">Masukkan kode pengajuan yang Anda dapatkan setelah mengisi form</p>
                <p class="text-gray-400 text-xs">Contoh: <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded">SRT-20250615-ABC123</span></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-8 text-center bg-gray-50 rounded-xl p-4 border border-gray-100">
        <p class="text-sm text-gray-500 inline-block mr-2">Belum mengajukan surat permohonan?</p>
        <a href="ajukan-surat.php" class="text-primary-600 font-bold text-sm hover:text-primary-700 hover:underline transition">Ajukan sekarang &rarr;</a>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>