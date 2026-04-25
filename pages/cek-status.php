<?php
require_once '../config/db.php';
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

function badgeStatus($status) {
    $map = [
        'Diproses' => 'badge-diproses',
        'Selesai'  => 'badge-selesai',
        'Ditolak'  => 'badge-ditolak',
    ];
    $icons = [
        'Diproses' => 'fa-clock',
        'Selesai'  => 'fa-check-circle',
        'Ditolak'  => 'fa-times-circle',
    ];
    $cls = $map[$status] ?? 'bg-gray-100 text-gray-600';
    $ic  = $icons[$status] ?? 'fa-circle';
    return "<span class='inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold $cls'><i class='fas $ic'></i> $status</span>";
}
?>

<div class="max-w-2xl mx-auto px-4 py-10">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Cek Status Surat</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-search text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">Cek Status Pengajuan</h1>
                    <p class="text-blue-100 text-sm">Masukkan kode pengajuan atau NIK Anda</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Form Pencarian -->
            <form method="GET" class="flex gap-3 mb-6">
                <input type="text" name="kode"
                       value="<?= htmlspecialchars($_GET['kode'] ?? $_GET['nik'] ?? '') ?>"
                       placeholder="Kode (contoh: SRT-20250101-ABCD12) atau NIK"
                       class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl flex items-center gap-2 transition">
                    <i class="fas fa-search"></i>
                    <span class="hidden sm:inline">Cari</span>
                </button>
            </form>

            <!-- Results -->
            <?php if($searched): ?>
                <?php if($result && $result->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while($row = $result->fetch_assoc()): ?>
                        <div class="border border-gray-100 rounded-xl p-5 bg-gray-50">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div>
                                    <div class="font-mono text-primary-700 font-bold text-lg"><?= htmlspecialchars($row['kode_pengajuan']) ?></div>
                                    <div class="text-xs text-gray-400"><?= formatTanggal($row['tanggal']) ?></div>
                                </div>
                                <?= badgeStatus($row['status']) ?>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <div class="text-xs text-gray-400 mb-0.5">Nama</div>
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
                            <div class="mt-4 bg-primary-50 border border-primary-200 rounded-lg p-3 text-sm text-primary-700 flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Surat Anda sudah selesai! Silakan ambil di kantor desa atau hubungi admin.
                            </div>
                            <?php elseif($row['status'] == 'Ditolak'): ?>
                            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 flex items-center gap-2">
                                <i class="fas fa-times-circle"></i>
                                Pengajuan ditolak. Hubungi admin untuk informasi lebih lanjut.
                            </div>
                            <?php endif; ?>

                            <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin,+saya+ingin+menanyakan+status+surat+dengan+kode+<?= $row['kode_pengajuan'] ?>+atas+nama+<?= urlencode($row['nama']) ?>"
                               target="_blank"
                               class="mt-4 w-full flex items-center justify-center gap-2 text-sm bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-lg hover:bg-green-100 transition font-semibold">
                                <i class="fab fa-whatsapp"></i> Tanya Admin via WhatsApp
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                <div class="text-center py-10">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="font-bold text-gray-700 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 text-sm mb-5">Pastikan kode pengajuan atau NIK yang Anda masukkan benar.</p>
                    <a href="ajukan-surat.php" class="btn-primary text-white font-semibold px-6 py-3 rounded-xl inline-block">
                        Ajukan Surat Baru
                    </a>
                </div>
                <?php endif; ?>
            <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-8">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-receipt text-3xl text-blue-400"></i>
                </div>
                <p class="text-gray-500 text-sm mb-2">Masukkan kode pengajuan yang Anda dapatkan</p>
                <p class="text-gray-400 text-xs">Contoh: <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">SRT-20250615-ABC123</span></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Belum punya kode? -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500">Belum mengajukan surat?</p>
        <a href="ajukan-surat.php" class="text-primary-600 font-semibold text-sm hover:underline">Ajukan sekarang →</a>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>
