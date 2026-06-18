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

$page_title = 'Pengaduan Masyarakat';
$success = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = clean($_POST['nama'] ?? '');
    $isi  = clean($_POST['isi'] ?? '');
    $foto = null;

    if(!$nama || !$isi) {
        $error = 'Nama dan isi laporan wajib diisi.';
    } else {
        // Logika Upload Foto
        if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            
            if(!in_array($ext, $allowed)) {
                $error = 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.';
            } elseif($_FILES['foto']['size'] > 5 * 1024 * 1024) {
                $error = 'Ukuran foto maksimal 5MB.';
            } else {
                if (!is_dir('../uploads/pengaduan')) { 
                    mkdir('../uploads/pengaduan', 0777, true); 
                }
                $foto = 'pengaduan_' . time() . '_' . uniqid() . '.' . $ext;
                if(!move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/pengaduan/' . $foto)) {
                    $error = 'Gagal mengunggah foto. Periksa izin folder.';
                }
            }
        }

        if(!$error) {
            $stmt = $conn->prepare("INSERT INTO pengaduan (nama, isi, foto) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $isi, $foto);
            
            if($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Kesalahan Database: ' . $conn->error;
            }
        }
    }
}

// AMBIL DATA PENGADUAN UNTUK FITUR TRANSPARANSI (Hanya tampilkan 15 terbaru agar rapi)
$query_transparansi = "SELECT * FROM pengaduan ORDER BY tanggal DESC LIMIT 15";
$result_transparansi = $conn->query($query_transparansi);

// Helper Badge Status untuk Transparansi
function badgeTransparansi($status) {
    $map = [
        'Masuk'    => 'bg-gray-100 text-gray-600 border-gray-200',
        'Diproses' => 'bg-amber-100 text-amber-700 border-amber-200',
        'Selesai'  => 'bg-green-100 text-green-700 border-green-200'
    ];
    $cls = $map[$status] ?? 'bg-gray-100 text-gray-600 border-gray-200';
    return "<span class='px-2.5 py-1 text-[10px] uppercase font-bold tracking-wider rounded border $cls'>$status</span>";
}

// Helper untuk format teks \r\n menjadi paragraf (br)
function format_isi_aduan($teks) {
    $teks = str_replace(['\r\n', '\r\n', '\r', '\n'], "\n", $teks);
    return nl2br(htmlspecialchars(trim($teks)));
}

require_once '../config/header.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if($success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Laporan Terkirim!',
        text: 'Terima kasih atas partisipasi Anda. Laporan akan segera kami verifikasi.',
        confirmButtonColor: '#f97316', // warna orange-500
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>

    <?php if($error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Ups!',
        text: '<?= $error ?>',
        confirmButtonColor: '#ef4444',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>
});
</script>

<div class="max-w-4xl mx-auto px-4 py-10 min-h-[70vh] relative">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Pengaduan Masyarakat</span>
    </div>

    <!-- Layout Grid: Form di kiri, Timeline Transparansi di kanan pada layar besar -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- BAGIAN KIRI: FORM PENGADUAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-fit">
            <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-6 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center shadow-sm shrink-0">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold">Kirim Pengaduan</h1>
                        <p class="text-orange-100 text-sm">Lapor kendala untuk perbaikan desa</p>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <?php if($success): ?>
                <div class="text-center py-4">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <i class="fas fa-check-circle text-4xl text-orange-600"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-800 mb-2">Terima Kasih!</h2>
                    <p class="text-gray-500 mb-6">Laporan Anda atas nama <strong><?= htmlspecialchars($_POST['nama']) ?></strong> telah masuk ke sistem kami.</p>
                    
                    <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+baru+saja+mengirim+laporan+pengaduan+melalui+website+atas+nama+*<?= urlencode($_POST['nama'] ?? '') ?>*.+Mohon+ditindaklanjuti.+Terima+kasih." 
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1ebe5b] text-white font-semibold px-6 py-3.5 rounded-xl transition shadow-sm mb-4 w-full">
                        <i class="fab fa-whatsapp text-lg"></i> Konfirmasi via WhatsApp
                    </a>
                    <br>
                    <a href="pengaduan.php" class="inline-block mt-2 text-sm text-primary-600 hover:text-primary-700 hover:underline transition font-medium">Buat laporan baru &rarr;</a>
                </div>

                <?php else: ?>

                <form method="POST" action="pengaduan.php" enctype="multipart/form-data" class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Pelapor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama"
                               value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                               placeholder="Boleh diisi Anonim jika ingin rahasia"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Isi Pengaduan / Keluhan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="isi" rows="4"
                                  placeholder="Detail keluhan (Misal: Jalan berlubang di RT 01 RW 02)..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none transition"
                                  required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Lampiran Bukti Foto <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-orange-300 hover:bg-orange-50 transition cursor-pointer" 
                             onclick="document.getElementById('foto').click()">
                            <i class="fas fa-camera text-2xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500 font-medium">Ketuk untuk pilih foto</p>
                            <p class="text-xs text-gray-400 mt-0.5">JPG/PNG/WEBP, Maks. 5MB</p>
                            <div id="foto-preview" class="hidden mt-4 pt-4 border-t border-gray-100">
                                <img id="preview-img" src="" class="max-h-32 rounded-lg mx-auto shadow-sm" alt="Preview">
                            </div>
                        </div>
                        <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 text-base transition shadow-md hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- BAGIAN KANAN: TIMELINE TRANSPARANSI -->
        <div class="bg-gray-50 rounded-2xl shadow-inner border border-gray-200/60 overflow-hidden h-fit">
            <div class="p-6 border-b border-gray-200/80 flex items-center justify-between bg-white relative z-10">
                <div>
                    <h2 class="text-base font-extrabold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-eye text-orange-500"></i> Pantauan Publik
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">Transparansi progres penanganan aduan.</p>
                </div>
            </div>
            
            <div class="p-6 max-h-[600px] overflow-y-auto">
                <?php if($result_transparansi && $result_transparansi->num_rows > 0): ?>
                    <div class="space-y-4">
                        <?php while($aduan = $result_transparansi->fetch_assoc()): 
                            // Persiapan data untuk modal
                            $nama_pelapor = htmlspecialchars(stripos($aduan['nama'], 'anonim') !== false ? 'Anonim' : $aduan['nama']);
                            $tgl_lapor = formatTanggal($aduan['tanggal'] ?? '');
                            $isi_laporan = format_isi_aduan($aduan['isi']);
                            $foto_url = !empty($aduan['foto']) ? '../uploads/pengaduan/' . htmlspecialchars($aduan['foto']) : '';
                            $status_badge_html = badgeTransparansi($aduan['status']);
                        ?>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-4 transition hover:border-orange-200 group">
                            <!-- Icon Kiri -->
                            <div class="shrink-0 mt-1">
                                <?php if($aduan['status'] == 'Selesai'): ?>
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="fas fa-check"></i></div>
                                <?php elseif($aduan['status'] == 'Diproses'): ?>
                                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center"><i class="fas fa-tools"></i></div>
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center"><i class="fas fa-inbox"></i></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Konten Kanan -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 mb-1">
                                    <div class="font-bold text-gray-800 text-sm truncate">
                                        <?= $nama_pelapor ?>
                                    </div>
                                    <?= $status_badge_html ?>
                                </div>
                                <div class="text-xs text-gray-400 mb-2"><i class="far fa-clock"></i> <?= $tgl_lapor ?></div>
                                <p class="text-sm text-gray-600 leading-relaxed line-clamp-2">
                                    "<?= htmlspecialchars(strip_tags($aduan['isi'])) ?>"
                                </p>
                                
                                <!-- TOMBOL LIHAT DETAIL -->
                                <button type="button" 
                                        class="text-xs font-bold text-orange-500 hover:text-orange-700 mt-2 flex items-center gap-1 opacity-80 group-hover:opacity-100 transition"
                                        onclick="bukaModalDetail(this)"
                                        data-nama="<?= htmlspecialchars($nama_pelapor) ?>"
                                        data-tanggal="<?= htmlspecialchars($tgl_lapor) ?>"
                                        data-status="<?= htmlspecialchars($status_badge_html) ?>"
                                        data-isi="<?= htmlspecialchars($isi_laporan) ?>"
                                        data-foto="<?= htmlspecialchars($foto_url) ?>">
                                    Baca detail <i class="fas fa-arrow-right text-[10px]"></i>
                                </button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-10 text-gray-400">
                        <i class="fas fa-clipboard-check text-4xl mb-3 opacity-30 block"></i>
                        <p class="text-sm">Belum ada aduan publik yang masuk.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DETAIL PENGADUAN (Disembunyikan)     -->
<!-- ========================================== -->
<div id="modalDetailAduan" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity opacity-0">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-[90vh]">
        
        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="font-extrabold text-gray-800 flex items-center gap-2">
                <i class="fas fa-file-alt text-orange-500"></i> Detail Laporan
            </h3>
            <button onclick="tutupModalDetail()" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="p-6 overflow-y-auto">
            <div class="flex items-start justify-between gap-4 mb-4 pb-4 border-b border-gray-100">
                <div>
                    <div class="text-xs text-gray-400 mb-0.5">Dilaporkan oleh:</div>
                    <div id="modalNama" class="font-bold text-gray-900 text-base">Nama Pelapor</div>
                    <div id="modalTanggal" class="text-xs text-gray-500 mt-1"><i class="far fa-clock"></i> Tanggal</div>
                </div>
                <div id="modalStatus" class="shrink-0 mt-1">
                    <!-- Badge status akan disisipkan di sini -->
                </div>
            </div>

            <div>
                <div class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wider">Isi Pengaduan</div>
                <div id="modalIsi" class="text-gray-700 text-sm leading-relaxed bg-orange-50/50 p-4 rounded-xl border border-orange-100/50">
                    Isi laporan...
                </div>
            </div>

            <!-- Kontainer Foto Lampiran -->
            <div id="modalFotoContainer" class="hidden mt-5">
                <div class="text-xs text-gray-400 mb-2 font-semibold uppercase tracking-wider">Foto Lampiran</div>
                <img id="modalFoto" src="" class="w-full rounded-xl border border-gray-200 shadow-sm" alt="Bukti Lampiran">
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 text-right">
            <button onclick="tutupModalDetail()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold px-5 py-2 rounded-xl text-sm transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// Logic Preview Foto Form
function previewFoto(input) {
    if(input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('foto-preview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Logic Modal Transparansi
const modal = document.getElementById('modalDetailAduan');
const modalInner = modal.querySelector('div.transform');

function bukaModalDetail(btn) {
    // Ambil data dari atribut tombol yang diklik
    const nama = btn.getAttribute('data-nama');
    const tanggal = btn.getAttribute('data-tanggal');
    const status = btn.getAttribute('data-status');
    const isi = btn.getAttribute('data-isi');
    const foto = btn.getAttribute('data-foto');

    // Sisipkan data ke dalam elemen Modal
    document.getElementById('modalNama').textContent = nama;
    document.getElementById('modalTanggal').innerHTML = `<i class="far fa-clock"></i> ${tanggal}`;
    document.getElementById('modalStatus').innerHTML = status;
    document.getElementById('modalIsi').innerHTML = isi; // Gunakan innerHTML karena teks sudah diformat nl2br

    // Handle Lampiran Foto
    const fotoContainer = document.getElementById('modalFotoContainer');
    const fotoImg = document.getElementById('modalFoto');
    if (foto && foto !== '') {
        fotoImg.src = foto;
        fotoContainer.classList.remove('hidden');
    } else {
        fotoContainer.classList.add('hidden');
        fotoImg.src = '';
    }

    // Tampilkan Modal dengan Animasi
    modal.classList.remove('hidden');
    // Beri sedikit jeda agar transisi CSS berjalan lancar
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modalInner.classList.remove('scale-95');
        modalInner.classList.add('scale-100');
    }, 10);
}

function tutupModalDetail() {
    // Sembunyikan dengan animasi
    modal.classList.add('opacity-0');
    modalInner.classList.remove('scale-100');
    modalInner.classList.add('scale-95');
    
    // Tunggu animasi selesai baru hidden elemennya
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Menutup modal jika klik area luar modal (backdrop)
modal.addEventListener('click', function(e) {
    if(e.target === modal) {
        tutupModalDetail();
    }
});
</script>

<?php require_once '../config/footer.php'; ?>