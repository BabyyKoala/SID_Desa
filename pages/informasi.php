<?php
require_once '../config/db.php';
$page_title = 'Informasi Desa';
$tab = $_GET['tab'] ?? 'berita';

$berita_list = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC");
$umkm_list   = $conn->query("SELECT * FROM umkm ORDER BY tanggal DESC");
$potensi_list = $conn->query("SELECT * FROM potensi ORDER BY kategori, tanggal DESC");
$lembaga_list = $conn->query("SELECT * FROM lembaga ORDER BY urutan ASC");

require_once '../config/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Informasi Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Informasi Desa</h1>
    <p class="text-gray-500 mb-8">Kabar terbaru, UMKM, potensi, dan perangkat Desa Darmakradenan</p>

    <!-- TABS -->
    <div class="flex gap-2 overflow-x-auto pb-2 mb-8 scrollbar-hide">
        <?php
        $tabs = [
            ['id'=>'berita',   'icon'=>'fa-newspaper',  'label'=>'Kabar Desa'],
            ['id'=>'umkm',     'icon'=>'fa-store',      'label'=>'UMKM'],
            ['id'=>'potensi',  'icon'=>'fa-leaf',       'label'=>'Potensi Desa'],
            ['id'=>'lembaga',  'icon'=>'fa-sitemap',    'label'=>'Perangkat Desa'],
        ];
        foreach($tabs as $t): 
            $active = $tab === $t['id'];
        ?>
        <a href="?tab=<?= $t['id'] ?>" 
           class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition
                  <?= $active ? 'bg-primary-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-primary-50 hover:text-primary-700 border border-gray-200' ?>">
            <i class="fas <?= $t['icon'] ?>"></i> <?= $t['label'] ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- ======================== BERITA ======================== -->
    <?php if($tab === 'berita'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while($row = $berita_list->fetch_assoc()): ?>
        <a href="detail-berita.php?id=<?= $row['id'] ?>" class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden block">
            <div class="h-44 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center overflow-hidden">
                <?php if($row['gambar'] && file_exists('../uploads/berita/'.$row['gambar'])): ?>
                    <img src="../uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <i class="fas fa-newspaper text-5xl text-primary-300"></i>
                <?php endif; ?>
            </div>
            <div class="p-5">
                <div class="text-xs text-primary-600 font-semibold mb-2">
                    <i class="far fa-calendar mr-1"></i><?= formatTanggal($row['tanggal']) ?>
                </div>
                <h3 class="font-bold text-gray-800 leading-snug mb-2 line-clamp-2"><?= htmlspecialchars($row['judul']) ?></h3>
                <p class="text-gray-500 text-sm line-clamp-3"><?= strip_tags(substr($row['isi'], 0, 150)) ?>...</p>
                <div class="mt-3 text-primary-600 text-sm font-semibold flex items-center gap-1">
                    Baca selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                </div>
            </div>
        </a>
        <?php endwhile; ?>
        <?php if($berita_list->num_rows === 0): ?>
        <div class="col-span-3 text-center py-16 text-gray-400">
            <i class="fas fa-newspaper text-4xl mb-3"></i>
            <p>Belum ada berita</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- ======================== UMKM ======================== -->
    <?php elseif($tab === 'umkm'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while($row = $umkm_list->fetch_assoc()): ?>
        <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-4 overflow-hidden">
                <?php if($row['foto'] && file_exists('../uploads/umkm/'.$row['foto'])): ?>
                    <img src="../uploads/umkm/<?= $row['foto'] ?>" class="w-14 h-14 object-cover rounded-xl">
                <?php else: ?>
                    <i class="fas fa-store text-primary-600 text-2xl"></i>
                <?php endif; ?>
            </div>
            <h3 class="font-bold text-gray-800 mb-2"><?= htmlspecialchars($row['nama']) ?></h3>
            <p class="text-gray-500 text-sm mb-4 line-clamp-3"><?= htmlspecialchars($row['deskripsi']) ?></p>
            <?php if($row['kontak']): ?>
            <a href="https://wa.me/62<?= ltrim($row['kontak'],'0') ?>" target="_blank"
               class="inline-flex items-center gap-2 text-sm text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-lg transition font-semibold">
                <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($row['kontak']) ?>
            </a>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- ======================== POTENSI ======================== -->
    <?php elseif($tab === 'potensi'): ?>
    <?php
    $kategoris = ['Wisata' => 'fa-mountain', 'Pertanian' => 'fa-seedling', 'Kerajinan' => 'fa-paint-brush'];
    $currentKat = '';
    $rows = [];
    while($row = $potensi_list->fetch_assoc()) $rows[] = $row;
    
    foreach($kategoris as $kat => $icon):
        $items = array_filter($rows, fn($r) => $r['kategori'] === $kat);
        if(empty($items)) continue;
    ?>
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                <i class="fas <?= $icon ?> text-primary-600"></i>
            </div>
            <h2 class="text-xl font-extrabold text-gray-800"><?= $kat ?></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach($items as $item): ?>
            <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
                <div class="h-40 md:h-auto md:w-48 bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <?php if($item['gambar'] && file_exists('../uploads/'.$item['gambar'])): ?>
                        <img src="../uploads/<?= $item['gambar'] ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas <?= $icon ?> text-4xl text-primary-300"></i>
                    <?php endif; ?>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-800 mb-2"><?= htmlspecialchars($item['judul']) ?></h3>
                    <p class="text-gray-500 text-sm leading-relaxed"><?= htmlspecialchars($item['deskripsi']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- ======================== LEMBAGA ======================== -->
    <?php elseif($tab === 'lembaga'): ?>
    <div>
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 text-sm font-semibold px-4 py-2 rounded-full mb-4">
                <i class="fas fa-sitemap"></i> Struktur Organisasi
            </div>
            <h2 class="text-2xl font-extrabold text-gray-800">Perangkat Desa Darmakradenan</h2>
            <p class="text-gray-500 text-sm mt-2">Periode 2025 - 2027</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <?php while($row = $lembaga_list->fetch_assoc()): 
                $isFirst = $row['urutan'] == 1;
            ?>
            <div class="<?= $isFirst ? 'col-span-2 md:col-span-3 lg:col-span-4' : '' ?> card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center <?= $isFirst ? 'max-w-xs mx-auto w-full' : '' ?>">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-3 overflow-hidden">
                    <?php if($row['foto'] && file_exists('../uploads/'.$row['foto'])): ?>
                        <img src="../uploads/<?= $row['foto'] ?>" class="w-16 h-16 object-cover rounded-full">
                    <?php else: ?>
                        <i class="fas fa-user text-primary-400 text-2xl"></i>
                    <?php endif; ?>
                </div>
                <div class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($row['nama']) ?></div>
                <div class="text-xs text-primary-600 mt-1 font-semibold"><?= htmlspecialchars($row['jabatan']) ?></div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../config/footer.php'; ?>
