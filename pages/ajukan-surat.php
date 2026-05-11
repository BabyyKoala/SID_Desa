<?php
require_once '../config/db.php';

// Fallback jika WA_NUMBER belum didefinisikan di db.php
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', '6282134655359'); 
}

// Fallback untuk fungsi generateKode() jika tidak ada di db.php
if (!function_exists('generateKode')) {
    function generateKode() {
        return 'SRT-' . strtoupper(substr(uniqid(), -6));
    }
}

$page_title = 'Ajukan Surat';
$success = false;
$kode = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik       = trim(strip_tags($_POST['nik'] ?? ''));
    $nama      = trim(strip_tags($_POST['nama'] ?? ''));
    $jenis     = trim(strip_tags($_POST['jenis_surat'] ?? ''));
    $keperluan = trim(strip_tags($_POST['keperluan'] ?? ''));

    if(!$nik || !$nama || !$jenis || !$keperluan) {
        $error = 'Semua kolom wajib diisi.';
    } elseif(strlen($nik) < 16) {
        $error = 'NIK harus 16 digit angka.';
    } else {
        $kode = generateKode();
        $stmt = $conn->prepare("INSERT INTO surat (nik, nama, jenis_surat, keperluan, kode_pengajuan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nik, $nama, $jenis, $keperluan, $kode);
        
        if($stmt->execute()) {
            $success = true;
        } else {
            $error = 'Terjadi kesalahan sistem database. Coba lagi.';
        }
    }
}

require_once '../config/header.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if($success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Pengajuan Berhasil!',
        text: 'Surat Anda sedang diproses. Silakan simpan kode pengajuan Anda.',
        confirmButtonColor: '#059669',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>

    <?php if($error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal Mengirim',
        text: '<?= $error ?>',
        confirmButtonColor: '#ef4444',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>
});
</script>

<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Ajukan Surat</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="hero-bg p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-file-signature text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">Pengajuan Surat Online</h1>
                    <p class="text-primary-100 text-sm">Isi form di bawah, kode pengajuan dikirim otomatis</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <?php if($success): ?>
            <div class="text-center py-4">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm">
                    <i class="fas fa-check-circle text-4xl text-primary-600"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-800 mb-2">Simpan Kode Anda!</h2>
                <p class="text-gray-500 mb-6">Screenshot atau catat kode di bawah untuk mengecek status surat secara mandiri.</p>
                
                <div class="bg-primary-50 border-2 border-primary-200 rounded-xl p-6 mb-6">
                    <div class="text-sm text-gray-500 mb-1">Kode Pengajuan Anda</div>
                    <div class="text-3xl font-extrabold text-primary-700 tracking-widest selection:bg-primary-200"><?= $kode ?></div>
                    <div class="text-xs text-gray-400 mt-2 font-medium">Jangan sampai hilang</div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="cek-status.php?kode=<?= $kode ?>" 
                       class="btn-primary text-white font-semibold px-6 py-3 rounded-xl flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-search"></i> Cek Status Sekarang
                    </a>
                    <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+telah+mengajukan+surat+dengan+kode+*<?= $kode ?>*+mohon+ditindaklanjuti.+Terima+kasih." 
                       target="_blank"
                       class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-xl flex items-center justify-center gap-2 transition shadow-sm">
                        <i class="fab fa-whatsapp text-lg"></i> Konfirmasi via WA
                    </a>
                </div>
                <a href="ajukan-surat.php" class="inline-block mt-6 text-sm text-primary-600 hover:text-primary-700 hover:underline transition font-medium">Ajukan surat lainnya</a>
            </div>

            <?php else: ?>

            <form method="POST" action="ajukan-surat.php" class="space-y-5">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        NIK <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="nik" id="nikInput" maxlength="16" pattern="\d{16}"
                               value="<?= htmlspecialchars($_POST['nik'] ?? '') ?>"
                               placeholder="Masukkan 16 digit NIK..."
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition"
                               onkeyup="cekNIK(this.value)" required autocomplete="off">
                        <div id="loadingIcon" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                            <i class="fas fa-spinner fa-spin text-primary-500 text-lg"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5"><i class="fas fa-info-circle mr-1"></i> Ketik 16 digit NIK, sistem akan mengecek data otomatis.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="namaInput"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           placeholder="Nama akan terisi otomatis jika NIK valid"
                           class="w-full border border-gray-200 bg-gray-50 text-gray-500 rounded-xl px-4 py-3 text-sm cursor-not-allowed focus:outline-none transition"
                           readonly required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_surat" 
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white transition cursor-pointer"
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

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" rows="3"
                              placeholder="Jelaskan keperluan pengajuan surat ini secara detail..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none transition"
                              required><?= htmlspecialchars($_POST['keperluan'] ?? '') ?></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" id="btnSubmit"
                            class="w-full btn-primary text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 text-base shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
let typingTimer;
const doneTypingInterval = 600; // Menunggu user selesai mengetik 0.6 detik

function cekNIK(nikValue) {
    clearTimeout(typingTimer);
    
    const namaInput = document.getElementById('namaInput');
    const loadingIcon = document.getElementById('loadingIcon');
    const btnSubmit = document.getElementById('btnSubmit');

    // Hanya mencari jika input tepat 16 karakter
    if (nikValue.length === 16) {
        loadingIcon.classList.remove('hidden');
        
        typingTimer = setTimeout(function() {
            // Memanggil API cek_nik.php
            fetch(`../api/cek_nik.php?nik=${nikValue}`)
                .then(response => response.json())
                .then(data => {
                    loadingIcon.classList.add('hidden');
                    
                    if(data.status === 'success') {
                        // Jika Valid: Isi nama & tampilkan notifikasi toast
                        namaInput.value = data.nama;
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `Valid: ${data.nama}`,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        // Jika Tidak Valid: Kosongkan nama & munculkan error
                        namaInput.value = '';
                        Swal.fire({
                            icon: 'error',
                            title: 'NIK Tidak Ditemukan',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    loadingIcon.classList.add('hidden');
                    console.error('Error fetching API:', error);
                    Swal.fire('Error', 'Terjadi kesalahan pada server saat mengecek NIK.', 'error');
                });
        }, doneTypingInterval);
    } else {
        // Jika belum 16 digit, kosongkan input nama
        namaInput.value = '';
        loadingIcon.classList.add('hidden');
    }
}
</script>

<?php require_once '../config/footer.php'; ?>