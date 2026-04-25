<?php
require_once '../config/db.php';
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

// Berita lainnya
$lainnya = $conn->prepare("SELECT id, judul, tanggal FROM berita WHERE id != ? ORDER BY tanggal DESC LIMIT 4");
$lainnya->bind_param("i", $id);
$lainnya->execute();
$lainnya = $lainnya->get_result();
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <a href="informasi.php" class="hover:text-primary-600">Informasi</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium line-clamp-1"><?= htmlspecialchars($berita['judul']) ?></span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Konten Utama -->
        <article class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <?php if($berita['gambar'] && file_exists('../uploads/berita/'.$berita['gambar'])): ?>
                <img src="../uploads/berita/<?= $berita['gambar'] ?>" class="w-full h-64 object-cover" alt="">
                <?php else: ?>
                <div class="w-full h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <i class="fas fa-newspaper text-6xl text-primary-300"></i>
                </div>
                <?php endif; ?>
                
                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-primary-100 text-primary-700 text-xs font-semibold px-3 py-1 rounded-full">Kabar Desa</span>
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="far fa-calendar"></i> <?= formatTanggal($berita['tanggal']) ?>
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 mb-5 leading-snug">
                        <?= htmlspecialchars($berita['judul']) ?>
                    </h1>
                    <div class="prose max-w-none text-gray-600 leading-relaxed text-sm md:text-base">
                        <?= nl2br(htmlspecialchars($berita['isi'])) ?>
                    </div>
                </div>
            </div>

            <a href="informasi.php" class="mt-6 inline-flex items-center gap-2 text-sm text-primary-600 hover:underline font-semibold">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita
            </a>
        </article>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-clock text-primary-600"></i> Berita Lainnya
                </h3>
                <div class="space-y-3">
                    <?php while($b = $lainnya->fetch_assoc()): ?>
                    <a href="detail-berita.php?id=<?= $b['id'] ?>" class="block group">
                        <div class="text-sm font-semibold text-gray-700 group-hover:text-primary-600 line-clamp-2 leading-snug"><?= htmlspecialchars($b['judul']) ?></div>
                        <div class="text-xs text-gray-400 mt-0.5"><?= formatTanggal($b['tanggal']) ?></div>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Layanan Cepat -->
            <div class="bg-primary-50 rounded-xl border border-primary-100 p-5">
                <h3 class="font-bold text-gray-800 mb-4">Layanan Cepat</h3>
                <div class="space-y-2">
                    <a href="ajukan-surat.php" class="flex items-center gap-3 text-sm text-primary-700 hover:text-primary-900 font-medium p-2 rounded-lg hover:bg-primary-100 transition">
                        <i class="fas fa-file-alt w-5"></i> Ajukan Surat
                    </a>
                    <a href="cek-status.php" class="flex items-center gap-3 text-sm text-primary-700 hover:text-primary-900 font-medium p-2 rounded-lg hover:bg-primary-100 transition">
                        <i class="fas fa-search w-5"></i> Cek Status
                    </a>
                    <a href="pengaduan.php" class="flex items-center gap-3 text-sm text-primary-700 hover:text-primary-900 font-medium p-2 rounded-lg hover:bg-primary-100 transition">
                        <i class="fas fa-comment-dots w-5"></i> Lapor Pengaduan
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>
