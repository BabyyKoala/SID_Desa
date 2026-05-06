<?php
require_once 'config/db.php';
$page_title = 'Beranda';
require_once 'config/header.php';

// PERBAIKAN 1: Menggunakan nama kolom spesifik alih-alih SELECT * untuk performa
$berita = $conn->query("SELECT id, judul, isi, gambar, tanggal FROM berita ORDER BY tanggal DESC LIMIT 3");
$umkm = $conn->query("SELECT nama, deskripsi, foto, kontak FROM umkm ORDER BY tanggal DESC LIMIT 3");

// PERBAIKAN 2: Fungsi pembantu untuk statistik agar terhindar dari Fatal Error jika tabel kosong/belum ada
function getTotal($conn, $table) {
    $result = $conn->query("SELECT COUNT(*) as c FROM $table");
    return $result ? $result->fetch_assoc()['c'] : 0;
}
?>

<!-- HERO SECTION -->
<section class="hero-bg text-white py-16 md:py-24 relative overflow-hidden">
    <!-- Decorative circles (Ditambah pointer-events-none agar tidak menghalangi klik) -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
    
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <div class="inline-flex items-center gap-2 bg-primary-700 bg-opacity-60 text-primary-100 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 border border-white border-opacity-10 backdrop-blur-sm">
            <i class="fas fa-circle text-green-400 text-[10px] animate-pulse"></i> Sistem Aktif & Online
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
               class="bg-white text-primary-700 font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col items-center gap-2">
                <i class="fas fa-file-signature text-2xl text-primary-600"></i>
                <span class="text-sm">Ajukan Surat</span>
            </a>
            <a href="pages/cek-status.php" 
               class="bg-primary-600 bg-opacity-80 border-2 border-white border-opacity-30 text-white font-bold py-4 px-6 rounded-2xl hover:bg-opacity-100 transition transform hover:-translate-y-1 flex flex-col items-center gap-2 backdrop-blur-sm">
                <i class="fas fa-search text-2xl"></i>
                <span class="text-sm">Cek Status</span>
            </a>
            <a href="pages/pengaduan.php" 
               class="bg-primary-600 bg-opacity-80 border-2 border-white border-opacity-30 text-white font-bold py-4 px-6 rounded-2xl hover:bg-opacity-100 transition transform hover:-translate-y-1 flex flex-col items-center gap-2 backdrop-blur-sm">
                <i class="fas fa-comment-dots text-2xl"></i>
                <span class="text-sm">Lapor Pengaduan</span>
            </a>
        </div>
    </div>
</section>

<!-- STATISTIK SINGKAT -->
<section class="bg-white border-b shadow-sm relative z-20 -mt-4 md:-mt-0">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center divide-x divide-gray-100">
            <?php
            $stats = [
                ['icon'=>'fa-users','label'=>'Warga','value'=>'10.857','color'=>'text-blue-600'],
                ['icon'=>'fa-file-alt','label'=>'Surat Diproses','value'=>getTotal($conn, 'surat'),'color'=>'text-primary-600'],
                ['icon'=>'fa-comment-dots','label'=>'Pengaduan','value'=>getTotal($conn, 'pengaduan'),'color'=>'text-orange-500'],
                ['icon'=>'fa-store','label'=>'UMKM','value'=>getTotal($conn, 'umkm'),'color'=>'text-purple-600'],
            ];
            foreach($stats as $s): ?>
            <div class="p-3">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 mb-3 shadow-inner">
                    <i class="fas <?= $s['icon'] ?> text-xl <?= $s['color'] ?>"></i>
                </div>
                <div class="text-3xl font-extrabold text-gray-800"><?= $s['value'] ?></div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1"><?= $s['label'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- KABAR TERBARU -->
<section class="max-w-6xl mx-auto px-4 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Kabar Terbaru</h2>
            <p class="text-sm text-gray-500 mt-1">Update informasi dan kegiatan desa terkini</p>
        </div>
        <a href="pages/informasi.php" class="text-primary-600 text-sm font-bold hover:text-primary-700 transition flex items-center gap-2 group">
            Lihat semua <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- PERBAIKAN 3: Cek apakah ada data berita -->
        <?php if($berita && $berita->num_rows > 0): ?>
            <?php while($row = $berita->fetch_assoc()): ?>
            <a href="pages/detail-berita.php?id=<?= $row['id'] ?>" class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden block hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="h-48 bg-gray-100 relative overflow-hidden">
                    <!-- PERBAIKAN 4: Cek !empty() sebelum file_exists untuk cegah warning -->
                    <?php if(!empty($row['gambar']) && file_exists('uploads/berita/'.$row['gambar'])): ?>
                        <img src="uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?= htmlspecialchars($row['judul']) ?>">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
                            <i class="fas fa-newspaper text-5xl text-primary-200"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <div class="text-xs text-primary-600 font-bold mb-3 flex items-center gap-2 uppercase tracking-wide">
                        <i class="far fa-calendar-alt"></i> <?= formatTanggal($row['tanggal']) ?>
                    </div>
                    <h3 class="font-bold text-gray-800 leading-tight line-clamp-2 text-lg mb-2 group-hover:text-primary-600 transition-colors"><?= htmlspecialchars($row['judul']) ?></h3>
                    <p class="text-gray-500 text-sm line-clamp-3 leading-relaxed"><?= strip_tags($row['isi']) ?></p>
                </div>
            </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-3 text-center py-10 text-gray-400 italic bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                Belum ada berita terbaru.
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- UMKM UNGGULAN -->
<section class="bg-primary-50 py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">UMKM Unggulan</h2>
                <p class="text-sm text-gray-500 mt-1">Dukung produk lokal karya warga desa kami</p>
            </div>
            <a href="pages/informasi.php?tab=umkm" class="text-primary-600 text-sm font-bold hover:text-primary-700 transition flex items-center gap-2 group">
                Lihat semua <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Cek apakah ada data UMKM -->
            <?php if($umkm && $umkm->num_rows > 0): ?>
                <?php while($row = $umkm->fetch_assoc()): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-primary-100 p-6 hover:shadow-md transition-shadow duration-300 flex flex-col">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden shadow-inner">
                            <?php if(!empty($row['foto']) && file_exists('uploads/umkm/'.$row['foto'])): ?>
                                <img src="uploads/umkm/<?= $row['foto'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($row['nama']) ?>">
                            <?php else: ?>
                                <i class="fas fa-store text-primary-600 text-2xl"></i>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg leading-tight"><?= htmlspecialchars($row['nama']) ?></h3>
                    </div>
                    <p class="text-gray-500 text-sm line-clamp-3 mb-5 leading-relaxed flex-grow"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    
                    <?php if(!empty($row['kontak'])): ?>
                    <!-- PERBAIKAN 5: Sanitasi nomor WA menggunakan preg_replace agar spasi/tanda strip hilang -->
                    <a href="https://wa.me/62<?= ltrim(preg_replace('/[^0-9]/', '', $row['kontak']), '0') ?>" target="_blank"
                       class="inline-flex items-center justify-center w-full gap-2 text-sm font-bold text-green-700 bg-green-50 px-4 py-2.5 rounded-xl hover:bg-green-600 hover:text-white transition-all duration-200 shadow-sm border border-green-100 mt-auto">
                        <i class="fab fa-whatsapp text-lg"></i> Hubungi via WhatsApp
                    </a>
                    <?php endif; ?>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-10 text-gray-400 italic bg-white rounded-2xl border border-dashed border-gray-200">
                    Data UMKM belum tersedia.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- LAYANAN DESA SHORTCUT -->
<section class="max-w-6xl mx-auto px-4 py-16">
    <div class="text-center mb-10">
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Layanan Mandiri</h2>
        <p class="text-gray-500 mt-2">Akses cepat layanan publik Desa Darmakradenan</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <?php
        $layanan = [
            ['href'=>'pages/ajukan-surat.php','icon'=>'fa-file-signature','label'=>'Surat Keterangan','color'=>'bg-blue-50 text-blue-600 border-blue-100'],
            ['href'=>'pages/cek-status.php','icon'=>'fa-search','label'=>'Cek Status Surat','color'=>'bg-primary-50 text-primary-600 border-primary-100'],
            ['href'=>'pages/pengaduan.php','icon'=>'fa-comment-dots','label'=>'Pengaduan Warga','color'=>'bg-orange-50 text-orange-600 border-orange-100'],
            ['href'=>'pages/informasi.php?tab=lembaga','icon'=>'fa-sitemap','label'=>'Perangkat Desa','color'=>'bg-purple-50 text-purple-600 border-purple-100'],
        ];
        foreach($layanan as $l): ?>
        <a href="<?= $l['href'] ?>" class="group <?= $l['color'] ?> rounded-2xl p-8 flex flex-col items-center text-center gap-4 border shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fas <?= $l['icon'] ?> text-3xl"></i>
            </div>
            <span class="text-sm font-bold uppercase tracking-wide"><?= $l['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once 'config/footer.php'; ?>