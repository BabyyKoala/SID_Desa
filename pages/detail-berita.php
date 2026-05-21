<?php
require_once '../config/db.php';

/**
 * Fungsi pembantu untuk memformat tanggal ke gaya Indonesia
 */
if (!function_exists('formatTanggal')) {
    function formatTanggal($date) {
        if(!$date) return '-';
        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $d = date('Y-m-d', strtotime($date));
        $pecahkan = explode('-', $d);
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}

// Ambil ID dari URL dan pastikan keamanannya dengan casting ke integer
$id = (int)($_GET['id'] ?? 0);

// Prepared Statement untuk mengambil konten berita utama
$stmt = $conn->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$berita = $stmt->get_result()->fetch_assoc();

// Jika berita tidak ada, arahkan kembali ke halaman daftar informasi
if(!$berita) {
    header("Location: informasi.php");
    exit;
}

$page_title = htmlspecialchars($berita['judul']);
require_once '../config/header.php';

// Ambil data berita terbaru lainnya untuk sidebar (kecuali yang sedang dibaca)
$lainnya = $conn->prepare("SELECT id, judul, tanggal FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT 4");
$lainnya->bind_param("i", $id);
$lainnya->execute();
$lainnya = $lainnya->get_result();

// Validasi path gambar secara aman
$has_image = false;
if (!empty($berita['gambar'])) {
    $img_path = '../uploads/berita/' . $berita['gambar'];
    if (file_exists($img_path)) {
        $has_image = true;
    }
}
?>

<div class="max-w-6xl mx-auto px-4 py-8 lg:py-12 min-h-[75vh]">
    <nav class="text-sm text-gray-500 mb-8 flex items-center flex-wrap gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition font-medium">Beranda</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <a href="informasi.php" class="hover:text-primary-600 transition font-medium">Informasi</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-300"></i>
        <span class="text-gray-900 font-bold line-clamp-1"><?= htmlspecialchars($berita['judul']) ?></span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <article class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                
                <?php if($has_image): ?>
                <img src="<?= $img_path ?>" class="w-full h-64 md:h-[450px] object-cover" alt="<?= htmlspecialchars($berita['judul']) ?>">
                <?php else: ?>
                <div class="w-full h-48 md:h-64 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center border-b border-primary-50">
                    <i class="fas fa-newspaper text-7xl text-primary-200"></i>
                </div>
                <?php endif; ?>
                
                <div class="p-6 md:p-10">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="bg-primary-600 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-sm">
                            Kabar Desa
                        </span>
                        <span class="text-xs text-gray-400 flex items-center gap-2 font-semibold">
                            <i class="far fa-calendar-check text-primary-500"></i> <?= formatTanggal($berita['tanggal']) ?>
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-8 leading-tight tracking-tight">
                        <?= htmlspecialchars($berita['judul']) ?>
                    </h1>
                    
                    <div class="prose max-w-none text-gray-700 leading-loose text-base md:text-xl text-justify space-y-6">
                        <?= nl2br(htmlspecialchars($berita['isi'])) ?>
                    </div>

                    <div class="mt-12 pt-8 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-2">
                            <div class="w-1.5 h-6 bg-primary-500 rounded-full"></div>
                            <span class="font-bold text-gray-800">Bagikan Informasi:</span>
                        </div>
                        <div class="flex gap-3">
                            <a href="https://api.whatsapp.com/send?text=<?= urlencode($berita['judul'] . ' - Baca lengkapnya di: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" 
                               target="_blank" class="w-11 h-11 rounded-2xl bg-[#25D366] text-white flex items-center justify-center hover:scale-110 transition-all shadow-md shadow-green-100" title="WhatsApp">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>" 
                               target="_blank" class="w-11 h-11 rounded-2xl bg-[#1877F2] text-white flex items-center justify-center hover:scale-110 transition-all shadow-md shadow-blue-100" title="Facebook">
                                <i class="fab fa-facebook-f text-xl"></i>
                            </a>
                            <button onclick="salinTautan()" 
                                    class="w-11 h-11 rounded-2xl bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200 transition border border-gray-200 shadow-sm hover:shadow-md" title="Salin Link">
                                <i class="fas fa-link text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <a href="informasi.php" class="inline-flex items-center gap-3 px-6 py-3 bg-white border border-gray-200 text-gray-600 hover:text-primary-600 hover:border-primary-100 hover:bg-primary-50 rounded-2xl text-sm font-bold transition-all shadow-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
                </a>
            </div>
        </article>

        <aside class="space-y-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-black text-gray-900 text-lg mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                    Terbaru Lainnya
                </h3>
                <div class="space-y-6">
                    <?php if($lainnya->num_rows > 0): ?>
                        <?php while($b = $lainnya->fetch_assoc()): ?>
                        <a href="detail-berita.php?id=<?= $b['id'] ?>" class="group block">
                            <div class="text-[14px] font-bold text-gray-800 group-hover:text-primary-600 transition-colors line-clamp-2 leading-relaxed mb-2">
                                <?= htmlspecialchars($b['judul']) ?>
                            </div>
                            <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="far fa-calendar"></i> <?= formatTanggal($b['tanggal']) ?>
                            </div>
                        </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-xs text-gray-400 text-center py-8 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-100">
                            Tidak ada berita lainnya.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-3xl p-6 shadow-lg shadow-primary-100 text-white">
                <h3 class="font-black text-lg mb-4">Layanan Mandiri</h3>
                <p class="text-primary-100 text-xs mb-6 leading-relaxed">Butuh layanan administrasi desa? Ajukan secara daring lebih cepat dan mudah.</p>
                <div class="space-y-3">
                    <a href="ajukan-surat.php" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-2xl transition-all border border-white/10 group">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-primary-700 shrink-0 shadow-sm"><i class="fas fa-file-signature text-sm"></i></div>
                        <span class="text-sm font-bold">Persuratan Online</span>
                    </a>
                    <a href="cek-status.php" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-2xl transition-all border border-white/10 group">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-blue-600 shrink-0 shadow-sm"><i class="fas fa-tasks text-sm"></i></div>
                        <span class="text-sm font-bold">Cek Status Berkas</span>
                    </a>
                    <a href="pengaduan.php" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 p-3 rounded-2xl transition-all border border-white/10 group">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-orange-500 shrink-0 shadow-sm"><i class="fas fa-bullhorn text-sm"></i></div>
                        <span class="text-sm font-bold">Pusat Aduan Warga</span>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function salinTautan() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Tautan berita berhasil disalin ke papan klip.',
            showConfirmButton: false, 
            timer: 2000, 
            timerProgressBar: true,
            iconColor: '#22c55e',
            customClass: {
                popup: 'rounded-3xl shadow-xl border border-gray-100'
            }
        });
    }).catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan saat menyalin tautan.',
            confirmButtonColor: '#ef4444',
            customClass: {
                popup: 'rounded-3xl shadow-xl border border-gray-100',
                confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm'
            }
        });
    });
}
</script>

<?php require_once '../config/footer.php'; ?>