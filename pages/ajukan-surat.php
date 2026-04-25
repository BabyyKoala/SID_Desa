<?php
require_once '../config/db.php';
$page_title = 'Ajukan Surat';
$success = false;
$kode = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = clean($_POST['nik'] ?? '');
    $nama = clean($_POST['nama'] ?? '');
    $jenis = clean($_POST['jenis_surat'] ?? '');
    $keperluan = clean($_POST['keperluan'] ?? '');

    if(!$nik || !$nama || !$jenis || !$keperluan) {
        $error = 'Semua kolom wajib diisi.';
    } elseif(strlen($nik) < 16) {
        $error = 'NIK harus 16 digit.';
    } else {
        $kode = generateKode();
        $stmt = $conn->prepare("INSERT INTO surat (nik, nama, jenis_surat, keperluan, kode_pengajuan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nik, $nama, $jenis, $keperluan, $kode);
        if($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Terjadi kesalahan sistem. Coba lagi.';
        }
    }
}

require_once '../config/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-10">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Ajukan Surat</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header Card -->
        <div class="hero-bg p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-signature text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">Pengajuan Surat Online</h1>
                    <p class="text-primary-100 text-sm">Isi form di bawah, kode pengajuan dikirim otomatis</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <?php if($success): ?>
            <!-- SUCCESS STATE -->
            <div class="text-center py-6">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-4xl text-primary-600"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-800 mb-2">Pengajuan Berhasil!</h2>
                <p class="text-gray-500 mb-6">Simpan kode pengajuan Anda untuk mengecek status.</p>
                
                <div class="bg-primary-50 border-2 border-primary-200 rounded-xl p-6 mb-6">
                    <div class="text-sm text-gray-500 mb-1">Kode Pengajuan Anda</div>
                    <div class="text-3xl font-extrabold text-primary-700 tracking-widest"><?= $kode ?></div>
                    <div class="text-xs text-gray-400 mt-2">Screenshot atau catat kode ini</div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="../pages/cek-status.php?kode=<?= $kode ?>" 
                       class="btn-primary text-white font-semibold px-6 py-3 rounded-xl flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Cek Status Sekarang
                    </a>
                    <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin+Desa+ABC,+saya+telah+mengajukan+surat+dengan+kode+<?= $kode ?>+mohon+ditindaklanjuti.+Terima+kasih." 
                       target="_blank"
                       class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-xl flex items-center justify-center gap-2 transition">
                        <i class="fab fa-whatsapp"></i> Konfirmasi via WA
                    </a>
                </div>
                <a href="ajukan-surat.php" class="block mt-4 text-sm text-primary-600 hover:underline">Ajukan surat lain</a>
            </div>

            <?php else: ?>

            <?php if($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <!-- NIK -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" maxlength="16" pattern="\d{16}"
                           value="<?= htmlspecialchars($_POST['nik'] ?? '') ?>"
                           placeholder="Masukkan 16 digit NIK"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent"
                           required>
                    <p class="text-xs text-gray-400 mt-1">Sesuai KTP, 16 digit angka</p>
                </div>

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           placeholder="Nama sesuai KTP"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent"
                           required>
                </div>

                <!-- Jenis Surat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_surat" 
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent bg-white"
                            required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="Surat Keterangan Domisili" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Domisili')?'selected':'' ?>>Surat Keterangan Domisili</option>
                        <option value="Surat Keterangan Tidak Mampu" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Tidak Mampu')?'selected':'' ?>>Surat Keterangan Tidak Mampu (SKTM)</option>
                        <option value="Surat Keterangan Usaha" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Usaha')?'selected':'' ?>>Surat Keterangan Usaha</option>
                        <option value="Surat Keterangan Kematian" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Kematian')?'selected':'' ?>>Surat Keterangan Kematian</option>
                        <option value="Surat Keterangan Kelahiran" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Kelahiran')?'selected':'' ?>>Surat Keterangan Kelahiran</option>
                        <option value="Surat Pengantar SKCK" <?= (($_POST['jenis_surat']??'')=='Surat Pengantar SKCK')?'selected':'' ?>>Surat Pengantar SKCK</option>
                        <option value="Surat Keterangan Belum Menikah" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Belum Menikah')?'selected':'' ?>>Surat Keterangan Belum Menikah</option>
                        <option value="Surat Keterangan Pindah" <?= (($_POST['jenis_surat']??'')=='Surat Keterangan Pindah')?'selected':'' ?>>Surat Keterangan Pindah</option>
                        <option value="Surat Izin Keramaian" <?= (($_POST['jenis_surat']??'')=='Surat Izin Keramaian')?'selected':'' ?>>Surat Izin Keramaian</option>
                        <option value="Lainnya" <?= (($_POST['jenis_surat']??'')=='Lainnya')?'selected':'' ?>>Lainnya</option>
                    </select>
                </div>

                <!-- Keperluan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" rows="3"
                              placeholder="Jelaskan keperluan pengajuan surat ini..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent resize-none"
                              required><?= htmlspecialchars($_POST['keperluan'] ?? '') ?></textarea>
                </div>

                <!-- Info -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 text-sm">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 shrink-0"></i>
                    <div class="text-blue-700">
                        Setelah submit, Anda akan mendapat <strong>kode pengajuan</strong>. Simpan kode tersebut untuk mengecek status surat Anda.
                    </div>
                </div>

                <button type="submit" 
                        class="w-full btn-primary text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2 text-base">
                    <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>
