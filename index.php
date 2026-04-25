<?php
require_once 'config/db.php';
$page_title = 'Beranda';
require_once 'config/header.php';

// Ambil 3 berita terbaru
$berita = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3");

// Ambil 3 UMKM
$umkm = $conn->query("SELECT * FROM umkm ORDER BY tanggal DESC LIMIT 3");
?>

<!-- HERO SECTION -->
<section class="hero-bg text-white py-16 md:py-24 relative overflow-hidden">
    <!-- Decorative circles -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
    
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <div class="inline-flex items-center gap-2 bg-primary-700 bg-opacity-60 text-primary-100 text-xs font-semibold px-4 py-1.5 rounded-full mb-6">
            <i class="fas fa-circle text-green-400 text-xs animate-pulse"></i> Sistem Aktif & Online
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold mb-4 leading-tight">
            Selamat Datang di<br>
            <span class="text-primary-300">Desa Darmakradenan</span>
        </h1>
        <p class="text-primary-100 text-base md:text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
            Layanan administrasi desa kini lebih mudah dan cepat. 
            Ajukan surat, cek status, dan laporkan pengaduan dari mana saja.
        </p>

        <!-- CTA BESAR - 3 Tombol Utama -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto">
            <a href="pages/ajukan-surat.php" 
               class="bg-white text-primary-700 font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition hover:-translate-y-1 flex flex-col items-center gap-2">
                <i class="fas fa-file-signature text-2xl text-primary-600"></i>
                <span class="text-sm">Ajukan Surat</span>
            </a>
            <a href="pages/cek-status.php" 
               class="bg-primary-600 bg-opacity-80 border-2 border-white border-opacity-30 text-white font-bold py-4 px-6 rounded-xl hover:bg-opacity-100 transition hover:-translate-y-1 flex flex-col items-center gap-2">
                <i class="fas fa-search text-2xl"></i>
                <span class="text-sm">Cek Status</span>
            </a>
            <a href="pages/pengaduan.php" 
               class="bg-primary-600 bg-opacity-80 border-2 border-white border-opacity-30 text-white font-bold py-4 px-6 rounded-xl hover:bg-opacity-100 transition hover:-translate-y-1 flex flex-col items-center gap-2">
                <i class="fas fa-comment-dots text-2xl"></i>
                <span class="text-sm">Lapor Pengaduan</span>
            </a>
        </div>
    </div>
</section>

<!-- STATISTIK SINGKAT -->
<section class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <?php
            $stats = [
                ['icon'=>'fa-users','label'=>'Warga','value'=>'2.547','color'=>'text-blue-600'],
                ['icon'=>'fa-file-alt','label'=>'Surat Diproses','value'=>$conn->query("SELECT COUNT(*) as c FROM surat")->fetch_assoc()['c'],'color'=>'text-primary-600'],
                ['icon'=>'fa-comment-dots','label'=>'Pengaduan','value'=>$conn->query("SELECT COUNT(*) as c FROM pengaduan")->fetch_assoc()['c'],'color'=>'text-orange-500'],
                ['icon'=>'fa-store','label'=>'UMKM','value'=>$conn->query("SELECT COUNT(*) as c FROM umkm")->fetch_assoc()['c'],'color'=>'text-purple-600'],
            ];
            foreach($stats as $s): ?>
            <div class="p-3">
                <i class="fas <?= $s['icon'] ?> text-2xl <?= $s['color'] ?> mb-1"></i>
                <div class="text-2xl font-extrabold text-gray-800"><?= $s['value'] ?></div>
                <div class="text-xs text-gray-500"><?= $s['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- KABAR TERBARU -->
<section class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Kabar Terbaru</h2>
            <p class="text-sm text-gray-500 mt-1">Informasi dan kegiatan desa</p>
        </div>
        <a href="pages/informasi.php" class="text-primary-600 text-sm font-semibold hover:underline flex items-center gap-1">
            Lihat semua <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php while($row = $berita->fetch_assoc()): ?>
        <a href="pages/detail-berita.php?id=<?= $row['id'] ?>" class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden block">
            <div class="h-40 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                <?php if($row['gambar'] && file_exists('uploads/berita/'.$row['gambar'])): ?>
                    <img src="uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover" alt="">
                <?php else: ?>
                    <i class="fas fa-newspaper text-4xl text-primary-400"></i>
                <?php endif; ?>
            </div>
            <div class="p-5">
                <div class="text-xs text-primary-600 font-semibold mb-2 flex items-center gap-1">
                    <i class="far fa-calendar"></i> <?= formatTanggal($row['tanggal']) ?>
                </div>
                <h3 class="font-bold text-gray-800 leading-snug line-clamp-2"><?= htmlspecialchars($row['judul']) ?></h3>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2"><?= strip_tags(substr($row['isi'], 0, 100)) ?>...</p>
            </div>
        </a>
        <?php endwhile; ?>
    </div>
</section>

<!-- UMKM UNGGULAN -->
<section class="bg-primary-50 py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">UMKM Unggulan</h2>
                <p class="text-sm text-gray-500 mt-1">Produk dan usaha warga desa</p>
            </div>
            <a href="pages/informasi.php?tab=umkm" class="text-primary-600 text-sm font-semibold hover:underline flex items-center gap-1">
                Lihat semua <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php while($row = $umkm->fetch_assoc()): ?>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-primary-100 p-5">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                    <?php if($row['foto'] && file_exists('uploads/umkm/'.$row['foto'])): ?>
                        <img src="uploads/umkm/<?= $row['foto'] ?>" class="w-12 h-12 rounded-xl object-cover" alt="">
                    <?php else: ?>
                        <i class="fas fa-store text-primary-600 text-xl"></i>
                    <?php endif; ?>
                </div>
                <h3 class="font-bold text-gray-800 mb-1"><?= htmlspecialchars($row['nama']) ?></h3>
                <p class="text-gray-500 text-sm line-clamp-2 mb-3"><?= htmlspecialchars($row['deskripsi']) ?></p>
                <?php if($row['kontak']): ?>
                <a href="https://wa.me/62<?= ltrim($row['kontak'],'0') ?>" target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-50 px-3 py-1.5 rounded-full hover:bg-green-100 transition">
                    <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($row['kontak']) ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- LAYANAN DESA SHORTCUT -->
<section class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-extrabold text-gray-800 text-center mb-8">Layanan Desa</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $layanan = [
            ['href'=>'pages/ajukan-surat.php','icon'=>'fa-file-signature','label'=>'Surat Keterangan','color'=>'bg-blue-50 text-blue-600'],
            ['href'=>'pages/cek-status.php','icon'=>'fa-search','label'=>'Cek Status Surat','color'=>'bg-primary-50 text-primary-600'],
            ['href'=>'pages/pengaduan.php','icon'=>'fa-comment-dots','label'=>'Pengaduan Warga','color'=>'bg-orange-50 text-orange-600'],
            ['href'=>'pages/informasi.php?tab=lembaga','icon'=>'fa-sitemap','label'=>'Perangkat Desa','color'=>'bg-purple-50 text-purple-600'],
        ];
        foreach($layanan as $l): ?>
        <a href="<?= $l['href'] ?>" class="card-hover <?= $l['color'] ?> rounded-xl p-6 flex flex-col items-center text-center gap-3 border border-opacity-20">
            <i class="fas <?= $l['icon'] ?> text-3xl"></i>
            <span class="text-sm font-semibold"><?= $l['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'config/footer.php'; ?>
