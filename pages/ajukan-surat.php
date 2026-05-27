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
        // Query Insert tetap menggunakan nik, nama, jenis_surat, keperluan, kode_pengajuan
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

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-700 to-green-600 p-6 md:p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-sm border border-white/20 shrink-0">
                    <i class="fas fa-file-signature text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight mb-1">Pengajuan Surat Online</h1>
                    <p class="text-green-100 text-sm">Isi form di bawah, kode resi akan dibuat otomatis</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <?php if($success): ?>
            <div class="text-center py-4">
                <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-5 shadow-sm border border-green-100">
                    <i class="fas fa-check-circle text-4xl text-green-600"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Simpan Kode Anda!</h2>
                <p class="text-gray-500 mb-6">Screenshot atau catat kode di bawah untuk mengecek status surat secara mandiri.</p>
                
                <div class="bg-green-50 border-2 border-green-200 rounded-2xl p-6 mb-8 relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(#22c55e_1px,transparent_1px)] [background-size:10px_10px] opacity-10"></div>
                    <div class="relative z-10">
                        <div class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-2">Kode Pengajuan</div>
                        <div class="text-3xl md:text-4xl font-extrabold text-green-700 tracking-widest selection:bg-green-200"><?= $kode ?></div>
                        <div class="text-xs text-green-600 mt-2 font-medium bg-green-100 inline-block px-3 py-1 rounded-full">Jangan sampai hilang</div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="cek-status.php?kode=<?= $kode ?>" 
                       class="bg-white border-2 border-gray-200 text-gray-700 hover:border-green-500 hover:text-green-600 font-bold px-6 py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all">
                        <i class="fas fa-search"></i> Cek Status Surat
                    </a>
                    <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+telah+mengajukan+surat+dengan+kode+*<?= $kode ?>*+mohon+ditindaklanjuti.+Terima+kasih." 
                       target="_blank"
                       class="bg-[#25D366] hover:bg-[#1ebe5b] text-white font-bold px-6 py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md shadow-green-200 hover:-translate-y-0.5">
                        <i class="fab fa-whatsapp text-xl"></i> Konfirmasi via WA
                    </a>
                </div>
                <a href="ajukan-surat.php" class="inline-block mt-8 text-sm text-green-600 font-bold hover:text-green-700 hover:underline transition">Buat pengajuan baru &rarr;</a>
            </div>

            <?php else: ?>
            <form method="POST" action="ajukan-surat.php" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="nik" id="nikInput" maxlength="16" pattern="\d{16}"
                               value="<?= htmlspecialchars($_POST['nik'] ?? '') ?>"
                               placeholder="Masukkan 16 digit NIK Anda..."
                               class="w-full border border-gray-200 rounded-xl px-4 py-3.5 text-base font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-shadow bg-gray-50/50 hover:bg-white"
                               onkeyup="cekNIK(this.value)" required autocomplete="off">
                        <div id="loadingIcon" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                            <i class="fas fa-spinner fa-spin text-green-500 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-[11px] font-medium text-gray-500 mt-2 flex items-center gap-1.5"><i class="fas fa-info-circle text-blue-500"></i> Ketik 16 digit NIK, sistem akan memverifikasi data otomatis.</p>
                </div>

                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 md:p-6 space-y-4 relative">
                    <div class="absolute top-0 left-6 -translate-y-1/2 bg-gray-100 text-gray-500 text-[10px] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full border border-gray-200">
                        Data Identitas Terverifikasi
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Lengkap Penduduk</label>
                            <input type="text" name="nama" id="namaInput"
                                   value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                                   placeholder="Terisi otomatis..."
                                   class="w-full border border-gray-200 bg-gray-100/50 text-gray-600 font-semibold rounded-xl px-4 py-3 text-sm cursor-not-allowed focus:outline-none"
                                   readonly required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">Jenis Kelamin</label>
                            <input type="text" name="jenis_kelamin" id="jenisKelaminInput"
                                   value="<?= htmlspecialchars($_POST['jenis_kelamin'] ?? '') ?>"
                                   placeholder="Terisi otomatis..."
                                   class="w-full border border-gray-200 bg-gray-100/50 text-gray-600 font-semibold rounded-xl px-4 py-3 text-sm cursor-not-allowed focus:outline-none"
                                   readonly>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">Alamat Lengkap</label>
                            <input type="text" name="alamat" id="alamatInput"
                                   value="<?= htmlspecialchars($_POST['alamat'] ?? '') ?>"
                                   placeholder="Terisi otomatis..."
                                   class="w-full border border-gray-200 bg-gray-100/50 text-gray-600 font-semibold rounded-xl px-4 py-3 text-sm cursor-not-allowed focus:outline-none"
                                   readonly>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Pilih Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_surat" 
                            class="w-full border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-gray-50/50 hover:bg-white transition cursor-pointer appearance-none"
                            required>
                        <option value="" disabled selected>-- Pilih Layanan Surat --</option>
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
                    <label class="block text-sm font-bold text-gray-800 mb-2">
                        Deskripsi Keperluan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" rows="3"
                              placeholder="Contoh: Digunakan sebagai syarat pendaftaran beasiswa pendidikan anak..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none bg-gray-50/50 hover:bg-white transition"
                              required><?= htmlspecialchars($_POST['keperluan'] ?? '') ?></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" id="btnSubmit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-extrabold py-4 rounded-xl flex items-center justify-center gap-2 text-base shadow-lg shadow-green-600/30 hover:shadow-green-600/40 hover:-translate-y-0.5 transition-all">
                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan Surat
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
let typingTimer;
const doneTypingInterval = 600;

function cekNIK(nikValue) {
    clearTimeout(typingTimer);
    
    const namaInput = document.getElementById('namaInput');
    const jenisKelaminInput = document.getElementById('jenisKelaminInput');
    const alamatInput = document.getElementById('alamatInput');
    const loadingIcon = document.getElementById('loadingIcon');

    if (nikValue.length === 16) {
        loadingIcon.classList.remove('hidden');
        
        typingTimer = setTimeout(function() {
            fetch(`../api/cek_nik.php?nik=${nikValue}`)
                .then(response => response.json())
                .then(data => {
                    loadingIcon.classList.add('hidden');
                    
                    if(data.status === 'success') {
                        namaInput.value = data.nama || '';
                        
                        // Menangkap berbagai kemungkinan format penamaan JSON dari API Anda
                        if(jenisKelaminInput) {
                            jenisKelaminInput.value = data.jenis_kelamin || data.jk || data.gender || data.kelamin || '-';
                        }
                        if(alamatInput) {
                            alamatInput.value = data.alamat || data.alamat_lengkap || data.address || '-';
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: `Data Ditemukan: ${data.nama}`,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        kosongkanFormPenduduk();
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Tidak Ditemukan',
                            text: data.message || 'NIK tidak terdaftar dalam database penduduk desa.',
                            confirmButtonColor: '#ef4444',
                            customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
                        });
                    }
                })
                .catch(error => {
                    loadingIcon.classList.add('hidden');
                    console.error('Error fetching API:', error);
                    kosongkanFormPenduduk();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gangguan Server',
                        text: 'Terjadi kesalahan saat menghubungi server.',
                        confirmButtonColor: '#ef4444'
                    });
                });
        }, doneTypingInterval);
    } else {
        loadingIcon.classList.add('hidden');
        kosongkanFormPenduduk();
    }
}

function kosongkanFormPenduduk() {
    const namaInput = document.getElementById('namaInput');
    const jenisKelaminInput = document.getElementById('jenisKelaminInput');
    const alamatInput = document.getElementById('alamatInput');

    if(namaInput) namaInput.value = '';
    if(jenisKelaminInput) jenisKelaminInput.value = '';
    if(alamatInput) alamatInput.value = '';
}
</script>

<?php require_once '../config/footer.php'; ?>