<?php
require_once '../config/db.php';

// Fallback pelindung jika fungsi formatTanggal belum ada di db.php
if (!function_exists('formatTanggal')) {
    function formatTanggal($date) {
        return $date ? date('d M Y', strtotime($date)) : '-';
    }
}

// Fungsi pembersih karakter & yang error berulang dari database
if (!function_exists('cleanText')) {
    function cleanText($text) {
        $text = str_replace(['&amp;amp;amp;amp;', '&amp;amp;amp;', '&amp;amp;', '&amp;'], '&', $text);
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }
}

$page_title = 'Informasi Desa';
$tab = $_GET['tab'] ?? 'berita';

// Menjalankan query dengan aman
$berita_list  = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC");
$umkm_list    = $conn->query("SELECT * FROM umkm ORDER BY tanggal DESC");
$potensi_list = $conn->query("SELECT * FROM potensi ORDER BY kategori, tanggal DESC") ?: false;
$lembaga_list = $conn->query("SELECT * FROM lembaga ORDER BY urutan ASC");

require_once '../config/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10 min-h-[70vh]">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Informasi Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Informasi Desa</h1>
    <p class="text-gray-500 mb-8 leading-relaxed">Kabar terbaru, direktori UMKM, potensi unggulan, dan struktur perangkat Desa Darmakradenan.</p>

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
           class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold whitespace-nowrap transition shadow-sm
                  <?= $active ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-primary-50 hover:text-primary-700 border border-gray-200' ?>">
            <i class="fas <?= $t['icon'] ?>"></i> <?= $t['label'] ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php if($tab === 'berita'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if($berita_list && $berita_list->num_rows > 0): ?>
            <?php while($row = $berita_list->fetch_assoc()): ?>
            <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-all duration-300">
                
                <a href="detail-berita.php?id=<?= $row['id'] ?>" class="block h-48 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center overflow-hidden border-b border-gray-50 relative">
                    <?php if($row['gambar'] && file_exists('../uploads/berita/'.$row['gambar'])): ?>
                        <img src="../uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 absolute inset-0">
                    <?php else: ?>
                        <i class="fas fa-newspaper text-5xl text-primary-300"></i>
                    <?php endif; ?>
                </a>

                <div class="p-6 flex flex-col flex-grow">
                    <div class="text-xs text-primary-600 font-bold mb-2 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="far fa-calendar-alt"></i><?= formatTanggal($row['tanggal']) ?>
                    </div>
                    
                    <a href="detail-berita.php?id=<?= $row['id'] ?>">
                        <h3 class="font-bold text-gray-800 leading-snug mb-3 line-clamp-2 group-hover:text-primary-600 transition-colors">
                            <?= htmlspecialchars(cleanText($row['judul'])) ?>
                        </h3>
                    </a>
                    
                    <p class="text-gray-500 text-sm line-clamp-3 flex-grow leading-relaxed mb-4">
                        <?= strip_tags(cleanText($row['isi'])) ?>
                    </p>
                    
                    <a href="detail-berita.php?id=<?= $row['id'] ?>" class="text-primary-600 text-sm font-bold flex items-center gap-1 group/btn hover:text-primary-700 w-max mt-auto">
                        Baca selengkapnya <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-gray-100 border-dashed">
                <i class="fas fa-newspaper text-5xl mb-4 text-gray-300"></i>
                <h3 class="font-bold text-gray-700 mb-1">Belum Ada Kabar Desa</h3>
                <p class="text-gray-500 text-sm">Berita atau artikel terbaru belum ditambahkan oleh pengelola desa.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php elseif($tab === 'umkm'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if($umkm_list && $umkm_list->num_rows > 0): ?>
            <?php while($row = $umkm_list->fetch_assoc()): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="w-16 h-16 bg-primary-50 rounded-xl flex items-center justify-center mb-4 overflow-hidden shadow-inner border border-primary-100">
                    <?php if($row['foto'] && file_exists('../uploads/umkm/'.$row['foto'])): ?>
                        <img src="../uploads/umkm/<?= $row['foto'] ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-store text-primary-500 text-2xl"></i>
                    <?php endif; ?>
                </div>
                <h3 class="font-bold text-gray-800 mb-2 text-lg"><?= htmlspecialchars(cleanText($row['nama'])) ?></h3>
                <p class="text-gray-500 text-sm mb-6 line-clamp-3 flex-grow leading-relaxed"><?= htmlspecialchars(cleanText($row['deskripsi'])) ?></p>
                
                <div class="mt-auto flex gap-2 w-full">
                    <button onclick="openModalUmkm(<?= $row['id'] ?>)" class="flex-1 bg-primary-50 text-primary-700 hover:bg-primary-100 px-3 py-2.5 rounded-xl text-sm font-bold transition border border-primary-100 flex items-center justify-center gap-1.5">
                        <i class="fas fa-info-circle"></i> Detail
                    </button>
                    <?php if(!empty($row['kontak'])): ?>
                    <a href="https://wa.me/62<?= ltrim(preg_replace('/[^0-9]/', '', $row['kontak']), '0') ?>" target="_blank"
                       class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm text-green-700 bg-green-50 hover:bg-green-600 hover:text-white px-3 py-2.5 rounded-xl transition duration-200 font-bold border border-green-100">
                        <i class="fab fa-whatsapp text-lg"></i> WA
                    </a>
                    <?php else: ?>
                    <div class="flex-1 px-3 py-2.5 bg-gray-50 text-gray-400 text-center text-sm font-medium rounded-xl border border-gray-100 flex items-center justify-center">
                        Tanpa Kontak
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                if(!window.umkmData) window.umkmData = {};
                window.umkmData[<?= $row['id'] ?>] = {
                    nama: <?= json_encode(cleanText($row['nama'])) ?>,
                    deskripsi: <?= json_encode(nl2br(cleanText($row['deskripsi']))) ?>,
                    foto: <?= json_encode($row['foto'] && file_exists('../uploads/umkm/'.$row['foto']) ? '../uploads/umkm/'.$row['foto'] : '') ?>,
                    kontak: <?= json_encode(!empty($row['kontak']) ? "https://wa.me/62" . ltrim(preg_replace('/[^0-9]/', '', $row['kontak']), '0') : '') ?>
                };
            </script>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-16 bg-white rounded-2xl border border-gray-100 border-dashed">
                <i class="fas fa-store text-5xl mb-4 text-gray-300"></i>
                <h3 class="font-bold text-gray-700 mb-1">Belum Ada Data UMKM</h3>
                <p class="text-gray-500 text-sm">Direktori usaha warga desa akan segera diperbarui.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php elseif($tab === 'potensi'): ?>
    <?php
    $kategoris = [
        'Pariwisata Alam & Edukasi' => 'fa-mountain', 
        'Sektor Pertanian & Perkebunan' => 'fa-seedling', 
        'Ekonomi Kreatif & Kerajinan' => 'fa-paint-brush'
    ];
    $rows = [];
    
    if($potensi_list) {
        while($row = $potensi_list->fetch_assoc()) $rows[] = $row;
    }
    
    if(!empty($rows)):
        $ada_data_ditampilkan = false; 
        
        foreach($kategoris as $kat => $icon):
            $items = array_filter($rows, function($r) use ($kat) {
                $db_kategori = cleanText($r['kategori']);
                return trim($db_kategori) === $kat || trim($r['kategori']) === $kat;
            });
            
            if(empty($items)) continue; 
            $ada_data_ditampilkan = true;
    ?>
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center border border-primary-100">
                    <i class="fas <?= $icon ?> text-primary-600 text-xl"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-800"><?= $kat ?></h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <?php foreach($items as $item): 
                    $judul_bersih = cleanText($item['judul']);
                    $desc_bersih  = cleanText($item['deskripsi']);
                ?>
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition-all duration-300">
                    <div class="h-56 md:h-auto md:w-2/5 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center flex-shrink-0 border-r border-gray-50 overflow-hidden relative cursor-pointer" onclick="openModalPotensi(<?= $item['id'] ?>)">
                        <?php if($item['gambar'] && file_exists('../uploads/potensi/'.$item['gambar'])): ?>
                            <img src="../uploads/potensi/<?= $item['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 absolute inset-0">
                        <?php else: ?>
                            <i class="fas <?= $icon ?> text-5xl text-primary-300"></i>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 flex flex-col justify-between md:w-3/5">
                        <div>
                            <h3 class="font-bold text-gray-800 mb-3 text-xl leading-tight group-hover:text-primary-600 transition-colors cursor-pointer" onclick="openModalPotensi(<?= $item['id'] ?>)">
                                <?= htmlspecialchars($judul_bersih) ?>
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-4 mb-5">
                                <?= htmlspecialchars($desc_bersih) ?>
                            </p>
                        </div>
                        <button onclick="openModalPotensi(<?= $item['id'] ?>)" class="inline-flex items-center gap-1.5 text-primary-600 font-bold text-sm hover:text-primary-700 transition w-max bg-primary-50 px-4 py-2 rounded-lg">
                            Baca Selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </div>

                <script>
                    if(!window.potensiData) window.potensiData = {};
                    window.potensiData[<?= $item['id'] ?>] = {
                        judul: <?= json_encode($judul_bersih) ?>,
                        deskripsi: <?= json_encode(nl2br(htmlspecialchars($desc_bersih))) ?>,
                        gambar: <?= json_encode($item['gambar'] && file_exists('../uploads/potensi/'.$item['gambar']) ? '../uploads/potensi/'.$item['gambar'] : '') ?>,
                        kategori: <?= json_encode($kat) ?>,
                        icon: <?= json_encode($icon) ?>
                    };
                </script>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(!$ada_data_ditampilkan): ?>
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 border-dashed">
                <i class="fas fa-exclamation-triangle text-5xl mb-4 text-yellow-400"></i>
                <h3 class="font-bold text-gray-700 mb-1">Kategori Tidak Cocok</h3>
                <p class="text-gray-500 text-sm">Data ada, tapi kategori belum diperbarui. Silakan edit ulang di Admin.</p>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 border-dashed">
            <i class="fas fa-leaf text-5xl mb-4 text-gray-300"></i>
            <h3 class="font-bold text-gray-700 mb-1">Belum Ada Data Potensi</h3>
            <p class="text-gray-500 text-sm">Informasi mengenai potensi sumber daya desa belum ditambahkan.</p>
        </div>
    <?php endif; ?>

    <?php elseif($tab === 'lembaga'): ?>
    <div>
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 bg-primary-50 border border-primary-200 text-primary-700 text-xs font-bold px-4 py-1.5 rounded-full mb-4 uppercase tracking-wider">
                <i class="fas fa-sitemap"></i> Struktur Organisasi
            </div>
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-800">Perangkat Desa Darmakradenan</h2>
            <p class="text-gray-500 mt-3 text-lg">Daftar aparatur pemerintah desa yang bertugas memberikan pelayanan kepada masyarakat.</p>
        </div>

        <?php if($lembaga_list && $lembaga_list->num_rows > 0): ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                <?php while($row = $lembaga_list->fetch_assoc()): 
                    $isFirst = $row['urutan'] == 1;
                ?>
                <div class="<?= $isFirst ? 'col-span-2 md:col-span-3 lg:col-span-4' : '' ?> bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center hover:shadow-md transition-shadow <?= $isFirst ? 'max-w-md mx-auto w-full bg-gradient-to-b from-white to-primary-50/30' : '' ?>">
                    <div class="<?= $isFirst ? 'w-28 h-28' : 'w-20 h-20' ?> bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4 overflow-hidden border-4 border-white shadow-sm">
                        <?php if($row['foto'] && file_exists('../uploads/'.$row['foto'])): ?>
                            <img src="../uploads/<?= $row['foto'] ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user text-primary-400 <?= $isFirst ? 'text-5xl' : 'text-3xl' ?>"></i>
                        <?php endif; ?>
                    </div>
                    <div class="font-bold text-gray-800 <?= $isFirst ? 'text-xl' : 'text-sm' ?>"><?= htmlspecialchars(cleanText($row['nama'])) ?></div>
                    <div class="text-primary-600 mt-1.5 font-bold <?= $isFirst ? 'text-sm' : 'text-xs' ?> uppercase tracking-wider"><?= htmlspecialchars(cleanText($row['jabatan'])) ?></div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 border-dashed">
                <i class="fas fa-sitemap text-5xl mb-4 text-gray-300"></i>
                <h3 class="font-bold text-gray-700 mb-1">Struktur Belum Tersedia</h3>
                <p class="text-gray-500 text-sm">Data susunan perangkat desa sedang dalam tahap pembaruan.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<div id="modalUmkm" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity opacity-0" id="modalUmkmBackdrop" onclick="closeModal('modalUmkm')"></div>
    <div id="modalUmkmContent" class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col md:flex-row overflow-hidden relative z-10 transform scale-95 opacity-0 transition-all duration-300">
        <button onclick="closeModal('modalUmkm')" class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center backdrop-blur-md transition shadow-md">
            <i class="fas fa-times"></i>
        </button>
        <div class="w-full md:w-5/12 h-64 md:h-auto bg-gray-100 flex-shrink-0 relative">
            <img id="modalUmkmGambar" src="" class="w-full h-full object-cover hidden absolute inset-0">
            <div id="modalUmkmIconContainer" class="w-full h-full flex items-center justify-center hidden absolute inset-0 bg-gradient-to-br from-primary-50 to-primary-100">
                <i class="fas fa-store text-7xl text-primary-300"></i>
            </div>
        </div>
        <div class="w-full md:w-7/12 p-6 sm:p-10 overflow-y-auto bg-white flex flex-col">
            <div class="inline-flex items-center gap-2 bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wider w-max border border-purple-100">
                <i class="fas fa-store"></i> Direktori UMKM
            </div>
            <h2 id="modalUmkmJudul" class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-6 leading-tight"></h2>
            <div id="modalUmkmDeskripsi" class="text-gray-600 text-sm sm:text-base leading-relaxed space-y-4 flex-grow"></div>
            
            <div class="mt-8 pt-4 border-t border-gray-100" id="modalUmkmKontakContainer">
                <a href="#" id="modalUmkmKontak" target="_blank" class="inline-flex items-center justify-center gap-2 text-sm text-white bg-green-500 hover:bg-green-600 px-6 py-3 rounded-xl transition duration-200 font-bold w-full shadow-sm">
                    <i class="fab fa-whatsapp text-lg"></i> Hubungi Penjual via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<div id="modalPotensi" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity opacity-0" id="modalPotensiBackdrop" onclick="closeModal('modalPotensi')"></div>
    <div id="modalPotensiContent" class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col md:flex-row overflow-hidden relative z-10 transform scale-95 opacity-0 transition-all duration-300">
        <button onclick="closeModal('modalPotensi')" class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/40 hover:bg-black/60 text-white rounded-full flex items-center justify-center backdrop-blur-md transition shadow-md">
            <i class="fas fa-times"></i>
        </button>
        <div class="w-full md:w-5/12 h-64 md:h-auto bg-gray-100 flex-shrink-0 relative">
            <img id="modalPotensiGambar" src="" class="w-full h-full object-cover hidden absolute inset-0">
            <div id="modalPotensiIconContainer" class="w-full h-full flex items-center justify-center hidden absolute inset-0 bg-gradient-to-br from-primary-50 to-primary-100">
                <i id="modalPotensiIcon" class="fas fa-leaf text-7xl text-primary-300"></i>
            </div>
        </div>
        <div class="w-full md:w-7/12 p-6 sm:p-10 overflow-y-auto bg-white flex flex-col">
            <div class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wider w-max border border-primary-100">
                <i id="modalPotensiKategoriIcon" class="fas"></i> <span id="modalPotensiKategori"></span>
            </div>
            <h2 id="modalPotensiJudul" class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-6 leading-tight"></h2>
            <div id="modalPotensiDeskripsi" class="text-gray-600 text-sm sm:text-base leading-relaxed space-y-4 flex-grow"></div>
        </div>
    </div>
</div>

<script>
// Fungsi Buka Modal UMKM
function openModalUmkm(id) {
    const data = window.umkmData[id];
    if(!data) return;
    
    document.getElementById('modalUmkmJudul').innerText = data.nama;
    document.getElementById('modalUmkmDeskripsi').innerHTML = data.deskripsi;
    
    const kontakBtn = document.getElementById('modalUmkmKontak');
    if(data.kontak) {
        kontakBtn.href = data.kontak;
        kontakBtn.parentElement.classList.remove('hidden');
    } else {
        kontakBtn.parentElement.classList.add('hidden');
    }
    
    toggleGambarModal('modalUmkm', data.foto, null);
    showModal('modalUmkm');
}

// Fungsi Buka Modal Potensi
function openModalPotensi(id) {
    const data = window.potensiData[id];
    if(!data) return;
    
    document.getElementById('modalPotensiJudul').innerText = data.judul;
    document.getElementById('modalPotensiDeskripsi').innerHTML = data.deskripsi;
    document.getElementById('modalPotensiKategori').innerText = data.kategori;
    document.getElementById('modalPotensiKategoriIcon').className = 'fas ' + data.icon;
    
    toggleGambarModal('modalPotensi', data.gambar, data.icon);
    showModal('modalPotensi');
}

// --- FUNGSI HELPER MODAL ---
function toggleGambarModal(prefix, gambarSrc, defaultIcon) {
    const imgEl = document.getElementById(prefix + 'Gambar');
    const iconEl = document.getElementById(prefix + 'IconContainer');
    const iconI = document.getElementById(prefix + 'Icon');
    
    if(gambarSrc) {
        imgEl.src = gambarSrc;
        imgEl.classList.remove('hidden');
        iconEl.classList.add('hidden');
    } else {
        imgEl.src = '';
        imgEl.classList.add('hidden');
        iconEl.classList.remove('hidden');
        if(defaultIcon && iconI) iconI.className = 'fas ' + defaultIcon + ' text-7xl text-primary-300';
    }
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById(modalId + 'Backdrop');
    const content = document.getElementById(modalId + 'Content');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    void modal.offsetWidth; // Trigger reflow
    backdrop.classList.remove('opacity-0');
    content.classList.remove('opacity-0', 'scale-95');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById(modalId + 'Backdrop');
    const content = document.getElementById(modalId + 'Content');
    
    backdrop.classList.add('opacity-0');
    content.classList.add('opacity-0', 'scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }, 300);
}
</script>

<?php require_once '../config/footer.php'; ?>