<?php
require_once '../config/db.php';

// Fallback pelindung jika fungsi formatTanggal belum ada di db.php
if (!function_exists('formatTanggal')) {
    function formatTanggal($date) {
        return $date ? date('d F Y', strtotime($date)) : '-';
    }
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$berita = $stmt->get_result()->fetch_assoc();

if(!$berita) {
    header("Location: informasi.php");
    exit;
}

$page_title = htmlspecialchars($berita['judul']);
require_once '../config/header.php';

// Berita lainnya (kecuali yang sedang dibaca)
$lainnya = $conn->prepare("SELECT id, judul, tanggal FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT 4");
$lainnya->bind_param("i", $id);
$lainnya->execute();
$lainnya = $lainnya->get_result();
?>

<div class="max-w-4xl mx-auto px-4 py-10 min-h-[70vh]">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <a href="informasi.php" class="hover:text-primary-600 transition">Informasi</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium line-clamp-1"><?= htmlspecialchars($berita['judul']) ?></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Konten Utama -->
        <article class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <?php if($berita['gambar'] && file_exists('../uploads/berita/'.$berita['gambar'])): ?>
                <img src="../uploads/berita/<?= $berita['gambar'] ?>" class="w-full h-64 md:h-80 object-cover" alt="<?= htmlspecialchars($berita['judul']) ?>">
                <?php else: ?>
                <div class="w-full h-48 md:h-64 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center border-b border-primary-200">
                    <i class="fas fa-newspaper text-6xl text-primary-300"></i>
                </div>
                <?php endif; ?>
                
                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1 rounded-full border border-primary-100 uppercase tracking-wider">Kabar Desa</span>
                        <span class="text-xs text-gray-400 flex items-center gap-1.5 font-medium">
                            <i class="far fa-calendar-alt"></i> <?= formatTanggal($berita['tanggal']) ?>
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-6 leading-snug">
                        <?= htmlspecialchars($berita['judul']) ?>
                    </h1>
                    
                    <!-- Konten Berita -->
                    <div class="prose max-w-none text-gray-600 leading-relaxed text-sm md:text-base text-justify">
                        <?= nl2br(htmlspecialchars($berita['isi'])) ?>
                    </div>
                </div>
            </div>

            <a href="informasi.php" class="mt-6 inline-flex items-center gap-2 text-sm text-primary-600 hover:text-primary-700 hover:underline font-semibold transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
            </a>
        </article>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <!-- Berita Lainnya -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 border-b border-gray-50 pb-3">
                    <i class="fas fa-clock text-primary-500"></i> Berita Lainnya
                </h3>
                <div class="space-y-4">
                    <?php if($lainnya->num_rows > 0): ?>
                        <?php while($b = $lainnya->fetch_assoc()): ?>
                        <a href="detail-berita.php?id=<?= $b['id'] ?>" class="block group">
                            <div class="text-sm font-semibold text-gray-700 group-hover:text-primary-600 transition line-clamp-2 leading-snug mb-1">
                                <?= htmlspecialchars($b['judul']) ?>
                            </div>
                            <div class="text-xs text-gray-400 font-medium">
                                <i class="far fa-calendar-alt mr-1"></i> <?= formatTanggal($b['tanggal']) ?>
                            </div>
                        </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-xs text-gray-400 text-center py-4 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                            Belum ada berita lain yang diterbitkan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Layanan Cepat -->
            <div class="bg-primary-50 rounded-xl border border-primary-100 p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Layanan Cepat</h3>
                <div class="space-y-2">
                    <a href="ajukan-surat.php" class="flex items-center gap-3 text-sm text-primary-700 hover:text-primary-900 font-semibold p-2.5 rounded-lg hover:bg-primary-100 transition border border-transparent hover:border-primary-200">
                        <i class="fas fa-file-alt w-5 text-center text-primary-500"></i> Ajukan Surat
                    </a>
                    <a href="cek-status.php" class="flex items-center gap-3 text-sm text-primary-700 hover:text-primary-900 font-semibold p-2.5 rounded-lg hover:bg-primary-100 transition border border-transparent hover:border-primary-200">
                        <i class="fas fa-search w-5 text-center text-primary-500"></i> Cek Status
                    </a>
                    <a href="pengaduan.php" class="flex items-center gap-3 text-sm text-primary-700 hover:text-primary-900 font-semibold p-2.5 rounded-lg hover:bg-primary-100 transition border border-transparent hover:border-primary-200">
                        <i class="fas fa-comment-dots w-5 text-center text-primary-500"></i> Lapor Pengaduan
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>