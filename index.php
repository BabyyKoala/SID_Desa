<?php
require_once 'config/db.php';
$page_title = 'Beranda';
require_once 'config/header.php';

// Data Berita & UMKM
$berita = $conn->query("SELECT id, judul, isi, gambar, tanggal FROM berita ORDER BY tanggal DESC LIMIT 3");
$umkm = $conn->query("SELECT nama, deskripsi, foto, kontak FROM umkm ORDER BY tanggal DESC LIMIT 3");

// SINKRONISASI DEMOGRAFI (Berdasarkan Data Admin)
$penduduk_total = 10560;
$penduduk_laki = 5150;
$penduduk_perempuan = 5410;
$penduduk_perantau = 500;

// SINKRONISASI STATISTIK LAYANAN
// Surat Diproses
$q_surat = $conn->query("SELECT COUNT(*) as c FROM surat WHERE status='Diproses'");
$surat_diproses = $q_surat ? $q_surat->fetch_assoc()['c'] : 0;

// Pengaduan
$q_pengaduan_all = $conn->query("SELECT COUNT(*) as c FROM pengaduan");
$pengaduan_total = $q_pengaduan_all ? $q_pengaduan_all->fetch_assoc()['c'] : 0;

// UMKM
$q_umkm = $conn->query("SELECT COUNT(*) as c FROM umkm");
$umkm_total = $q_umkm ? $q_umkm->fetch_assoc()['c'] : 0;
?>

<section class="bg-gradient-to-br from-[#14532d] via-[#15803d] to-[#16a34a] text-white pt-12 pb-28 md:pb-32 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-white opacity-5 rounded-full -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>
    
    <div class="max-w-6xl mx-auto px-4 relative z-10 flex flex-col items-center md:items-start text-center md:text-left mt-4">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 bg-white/10 text-white text-xs font-semibold px-4 py-1.5 rounded-full mb-5 border border-white/20 backdrop-blur-sm">
                <i class="fas fa-circle text-green-400 text-[10px] animate-pulse shadow-[0_0_8px_#4ade80]"></i> Sistem Aktif & Online
            </div>
            
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight tracking-tight">
                Selamat Datang di<br>
                <span class="text-green-300">Desa Darmakradenan</span>
            </h1>
            
            <p class="text-green-50 text-sm md:text-base lg:text-lg mb-8 max-w-2xl leading-relaxed">
                Layanan administrasi desa kini lebih mudah dan cepat. 
                Ajukan surat, cek status, dan laporkan pengaduan dari mana saja tanpa antre.
            </p>

            <div class="flex flex-wrap justify-center md:justify-start gap-3 relative z-20">
                <a href="pages/ajukan-surat.php" 
                   class="bg-white text-green-700 font-bold py-3 px-5 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 group">
                    <i class="fas fa-file-signature text-green-600 group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm">Ajukan Surat</span>
                </a>
                <a href="pages/cek-status.php" 
                   class="bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold py-3 px-5 rounded-xl shadow-lg hover:bg-white/20 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 group">
                    <i class="fas fa-search group-hover:scale-110 transition-transform text-green-200"></i>
                    <span class="text-sm">Cek Status</span>
                </a>
                <a href="pages/pengaduan.php" 
                   class="bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold py-3 px-5 rounded-xl shadow-lg hover:bg-white/20 hover:-translate-y-1 transition-all duration-300 flex items-center gap-2 group">
                    <i class="fas fa-comment-dots group-hover:scale-110 transition-transform text-green-200"></i>
                    <span class="text-sm">Pengaduan</span>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 relative z-30 -mt-12 md:-mt-16 mb-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 hover:border-blue-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5 group-hover:text-blue-500 transition-colors">Total Warga</div>
                    <div class="text-2xl font-extrabold text-gray-800 leading-none"><?= number_format($penduduk_total, 0, ',', '.') ?></div>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-50">
                <span class="text-[10px] text-gray-500 font-medium">Tercatat di sistem administrasi</span>
            </div>
        </div>

        <a href="pages/cek-status.php" class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 hover:border-green-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5 group-hover:text-green-600 transition-colors">Surat Diproses</div>
                    <div class="text-2xl font-extrabold text-gray-800 leading-none"><?= $surat_diproses ?></div>
                </div>
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors shrink-0">
                    <i class="fas fa-file-alt text-lg"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-50 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[10px] text-green-600 font-bold">Sedang ditangani admin</span>
            </div>
        </a>

        <a href="pages/pengaduan.php" class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 hover:border-orange-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5 group-hover:text-orange-600 transition-colors">Total Pengaduan</div>
                    <div class="text-2xl font-extrabold text-gray-800 leading-none"><?= $pengaduan_total ?></div>
                </div>
                <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 group-hover:bg-orange-500 group-hover:text-white transition-colors shrink-0">
                    <i class="fas fa-comment-dots text-lg"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-50">
                <span class="text-[10px] text-gray-500 font-medium">Aspirasi layanan warga</span>
            </div>
        </a>
        
        <a href="pages/informasi.php?tab=umkm" class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 hover:border-purple-300 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between group">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5 group-hover:text-purple-600 transition-colors">UMKM Desa</div>
                    <div class="text-2xl font-extrabold text-gray-800 leading-none"><?= $umkm_total ?></div>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors shrink-0">
                    <i class="fas fa-store text-lg"></i>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-50">
                <span class="text-[10px] text-gray-500 font-medium">Katalog produk unggulan lokal</span>
            </div>
        </a>

    </div>
</section>

<section class="max-w-6xl mx-auto px-4 mb-10">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col md:flex-row items-center gap-8">
        
        <div class="w-full md:w-7/12">
            <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-[11px] font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider border border-blue-100">
                <i class="fas fa-chart-pie"></i> Transparansi Data
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight mb-3">Demografi Kependudukan</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                Rasio komposisi penduduk berdasarkan jenis kelamin dari total <strong><?= number_format($penduduk_total, 0, ',', '.') ?> jiwa</strong> yang tercatat secara resmi.
            </p>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0"><i class="fas fa-male"></i></div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Laki-laki</div>
                        <div class="text-lg font-extrabold text-gray-800"><?= number_format($penduduk_laki, 0, ',', '.') ?></div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-600 flex items-center justify-center shrink-0"><i class="fas fa-female"></i></div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Perempuan</div>
                        <div class="text-lg font-extrabold text-gray-800"><?= number_format($penduduk_perempuan, 0, ',', '.') ?></div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-3 col-span-2 sm:col-span-1">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center shrink-0"><i class="fas fa-briefcase"></i></div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase">Merantau</div>
                        <div class="text-lg font-extrabold text-gray-800"><?= number_format($penduduk_perantau, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full md:w-5/12 h-56 relative flex justify-center mt-2 md:mt-0">
            <canvas id="genderChartPublic"></canvas>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 mb-10">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kabar Terbaru</h2>
            <p class="text-gray-500 text-sm mt-1">Informasi dan kegiatan desa terkini</p>
        </div>
        <a href="pages/informasi.php" class="text-green-600 text-sm font-bold hover:text-green-800 transition flex items-center gap-1.5 group">
            Lihat Semua <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <?php if($berita && $berita->num_rows > 0): ?>
            <?php while($row = $berita->fetch_assoc()): ?>
            <a href="pages/detail-berita.php?id=<?= $row['id'] ?>" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex flex-col group">
                <div class="h-44 bg-gray-100 relative overflow-hidden shrink-0">
                    <?php if(!empty($row['gambar']) && file_exists('uploads/berita/'.$row['gambar'])): ?>
                        <img src="uploads/berita/<?= $row['gambar'] ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="<?= htmlspecialchars($row['judul']) ?>">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-green-50"><i class="fas fa-newspaper text-5xl text-green-200"></i></div>
                    <?php endif; ?>
                </div>
                <div class="p-5 flex flex-col flex-grow">
                    <div class="text-[11px] text-green-600 font-bold mb-2 flex items-center gap-1.5"><i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                    <h3 class="font-extrabold text-gray-800 text-base line-clamp-2 mb-2 group-hover:text-green-600 transition-colors leading-snug"><?= htmlspecialchars($row['judul']) ?></h3>
                    <p class="text-gray-500 text-sm line-clamp-2 mt-auto"><?= strip_tags($row['isi']) ?></p>
                </div>
            </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-10 text-gray-400 italic bg-gray-50 rounded-3xl border border-dashed border-gray-200 text-sm">Belum ada berita.</div>
        <?php endif; ?>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 mb-12">
    <div class="flex items-end justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">UMKM Desa</h2>
            <p class="text-gray-500 text-sm mt-1">Dukung produk lokal karya warga</p>
        </div>
        <a href="pages/informasi.php?tab=umkm" class="text-green-600 text-sm font-bold hover:text-green-800 transition flex items-center gap-1.5 group">
            Katalog UMKM <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <?php if($umkm && $umkm->num_rows > 0): ?>
            <?php while($row = $umkm->fetch_assoc()): ?>
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow flex flex-col group">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 shrink-0">
                        <?php if(!empty($row['foto']) && file_exists('uploads/umkm/'.$row['foto'])): ?>
                            <img src="uploads/umkm/<?= $row['foto'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="<?= htmlspecialchars($row['nama']) ?>">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-store text-gray-300 text-xl"></i></div>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-extrabold text-gray-800 text-base leading-tight group-hover:text-green-600 transition-colors"><?= htmlspecialchars($row['nama']) ?></h3>
                </div>
                <p class="text-gray-500 text-xs line-clamp-3 mb-4 flex-grow leading-relaxed"><?= htmlspecialchars($row['deskripsi']) ?></p>
                
                <?php if(!empty($row['kontak'])): ?>
                <a href="https://wa.me/62<?= ltrim(preg_replace('/[^0-9]/', '', $row['kontak']), '0') ?>" target="_blank" class="text-xs font-bold text-green-700 bg-green-50 px-3 py-2 rounded-xl hover:bg-green-600 hover:text-white transition-colors flex items-center justify-center gap-1.5 w-max mt-auto">
                    <i class="fab fa-whatsapp text-sm"></i> Hubungi Penjual
                </a>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-1 sm:col-span-2 md:col-span-3 text-center py-10 text-gray-400 italic bg-gray-50 rounded-3xl border border-dashed border-gray-200 text-sm">Data UMKM belum tersedia.</div>
        <?php endif; ?>
    </div>
</section>

<section class="bg-gray-50 py-12 border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-xl font-extrabold text-gray-900 text-center mb-6">Pusat Layanan Digital</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <?php
            $layanan = [
                ['href'=>'pages/ajukan-surat.php','icon'=>'fa-file-signature','label'=>'Ajukan Surat','color'=>'text-blue-600', 'bg'=>'bg-blue-50', 'hover'=>'hover:border-blue-300'],
                ['href'=>'pages/cek-status.php','icon'=>'fa-search','label'=>'Cek Status','color'=>'text-green-600', 'bg'=>'bg-green-50', 'hover'=>'hover:border-green-300'],
                ['href'=>'pages/pengaduan.php','icon'=>'fa-comment-dots','label'=>'Pengaduan','color'=>'text-orange-600', 'bg'=>'bg-orange-50', 'hover'=>'hover:border-orange-300'],
                ['href'=>'pages/informasi.php?tab=lembaga','icon'=>'fa-sitemap','label'=>'Perangkat Desa','color'=>'text-purple-600', 'bg'=>'bg-purple-50', 'hover'=>'hover:border-purple-300'],
            ];
            foreach($layanan as $l): ?>
            <a href="<?= $l['href'] ?>" class="bg-white rounded-2xl p-5 flex flex-col items-center text-center gap-3 border border-gray-200 shadow-sm <?= $l['hover'] ?> transition-all hover:-translate-y-1 group">
                <div class="w-12 h-12 <?= $l['bg'] ?> <?= $l['color'] ?> rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas <?= $l['icon'] ?> text-2xl"></i>
                </div>
                <span class="text-sm font-bold text-gray-800 <?= str_replace('text-', 'group-hover:text-', $l['color']) ?> transition-colors"><?= $l['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('genderChartPublic').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [<?= $penduduk_laki ?>, <?= $penduduk_perempuan ?>],
                backgroundColor: ['#3b82f6', '#ec4899'],
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed !== null) {
                                label += new Intl.NumberFormat('id-ID').format(context.parsed) + ' jiwa';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php require_once 'config/footer.php'; ?>