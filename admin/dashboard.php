<?php
session_start();
require_once '../config/db.php';
$page_title = 'Command Center';
require_once 'layout.php';

$admin_role = $_SESSION['admin_role'] ?? 'staf';

// --- 1. DATA DEMOGRAFI DESA DARMAKRADENAN ---
$penduduk_total = 10560;
$penduduk_laki = 5150;
$penduduk_perempuan = 5410;
$penduduk_perantau = 500;

// --- 2. STATISTIK SISTEM WEB ---
$total_surat      = $conn->query("SELECT COUNT(*) as c FROM surat")->fetch_assoc()['c'] ?? 0;
$surat_diproses   = $conn->query("SELECT COUNT(*) as c FROM surat WHERE status='Diproses'")->fetch_assoc()['c'] ?? 0;
$surat_selesai    = $conn->query("SELECT COUNT(*) as c FROM surat WHERE status='Selesai'")->fetch_assoc()['c'] ?? 0;
$surat_ditolak    = $conn->query("SELECT COUNT(*) as c FROM surat WHERE status='Ditolak'")->fetch_assoc()['c'] ?? 0;

$total_pengaduan  = $conn->query("SELECT COUNT(*) as c FROM pengaduan")->fetch_assoc()['c'] ?? 0;
$pengaduan_baru   = $conn->query("SELECT COUNT(*) as c FROM pengaduan WHERE status='Masuk'")->fetch_assoc()['c'] ?? 0;

$total_berita     = $conn->query("SELECT COUNT(*) as c FROM berita")->fetch_assoc()['c'] ?? 0;
$total_umkm       = $conn->query("SELECT COUNT(*) as c FROM umkm")->fetch_assoc()['c'] ?? 0;

// --- 3. DATA TERBARU (RECENT DATA) ---
$surat_terbaru   = $conn->query("SELECT * FROM surat ORDER BY tanggal DESC LIMIT 5");
$pengaduan_baru_ = $conn->query("SELECT * FROM pengaduan ORDER BY tanggal DESC LIMIT 5");
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="bg-gradient-to-r from-primary-700 to-primary-500 rounded-2xl shadow-sm p-6 md:p-8 mb-6 text-white relative overflow-hidden">
    <div class="absolute -right-10 -top-10 opacity-10">
        <i class="fas fa-chart-pie text-[150px]"></i>
    </div>
    <div class="relative z-10">
        <h1 class="text-2xl md:text-3xl font-extrabold mb-2">Halo, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>! 👋</h1>
        <p class="text-primary-100 max-w-2xl text-sm leading-relaxed">
            Selamat datang di Command Center SID Darmakradenan. Pantau analitik demografi dan kelola pelayanan warga hari ini.
        </p>
    </div>
</div>

<?php if($admin_role !== 'kepala_desa'): ?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <?php
    $actions = [
        ['href'=>'kelola-surat.php','label'=>'Kelola Surat','icon'=>'fa-file-alt','color'=>'bg-blue-600 hover:bg-blue-700'],
        ['href'=>'kelola-pengaduan.php','label'=>'Pengaduan','icon'=>'fa-comment-dots','color'=>'bg-orange-500 hover:bg-orange-600'],
        ['href'=>'kelola-berita.php?action=add','label'=>'Tulis Berita','icon'=>'fa-plus-circle','color'=>'bg-purple-600 hover:bg-purple-700'],
        ['href'=>'kelola-umkm.php?action=add','label'=>'Tambah UMKM','icon'=>'fa-store','color'=>'bg-green-600 hover:bg-green-700'],
    ];
    foreach($actions as $a): ?>
    <a href="<?= $a['href'] ?>" 
       class="<?= $a['color'] ?> text-white rounded-xl px-4 py-3 text-sm font-semibold flex items-center gap-2 justify-center shadow-sm transition hover:-translate-y-0.5">
        <i class="fas <?= $a['icon'] ?>"></i> <?= $a['label'] ?>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-primary-200 transition">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-2xl font-extrabold text-gray-800"><?= number_format($penduduk_total, 0, ',', '.') ?></div>
                <div class="text-sm font-semibold text-gray-700 mt-0.5">Total Warga</div>
                <div class="text-xs text-gray-400 mt-0.5"><?= $penduduk_perantau ?> jiwa merantau</div>
            </div>
            <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-users text-primary-500"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-blue-200 transition">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-2xl font-extrabold text-gray-800"><?= number_format($total_surat, 0, ',', '.') ?></div>
                <div class="text-sm font-semibold text-gray-700 mt-0.5">Total Surat</div>
                <div class="text-xs text-gray-400 mt-0.5"><?= $surat_diproses ?> sedang diproses</div>
            </div>
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-envelope-open-text text-blue-500"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-orange-200 transition">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-2xl font-extrabold text-gray-800"><?= number_format($total_pengaduan, 0, ',', '.') ?></div>
                <div class="text-sm font-semibold text-gray-700 mt-0.5">Pengaduan</div>
                <div class="text-xs text-red-500 font-medium mt-0.5"><?= $pengaduan_baru ?> butuh dicek</div>
            </div>
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-bullhorn text-orange-500"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-green-200 transition">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-2xl font-extrabold text-gray-800"><?= number_format($total_umkm, 0, ',', '.') ?></div>
                <div class="text-sm font-semibold text-gray-700 mt-0.5">UMKM Desa</div>
                <div class="text-xs text-gray-400 mt-0.5">+<?= $total_berita ?> Artikel Berita</div>
            </div>
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-store text-green-500"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-bold text-gray-800">Komposisi Penduduk</h3>
                <p class="text-xs text-gray-500">Berdasarkan Jenis Kelamin</p>
            </div>
            <div class="bg-gray-50 p-2 rounded-lg text-gray-400"><i class="fas fa-venus-mars"></i></div>
        </div>
        <div class="relative h-48 flex items-center justify-center">
            <canvas id="genderChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-bold text-gray-800">Statistik Pelayanan Surat</h3>
                <p class="text-xs text-gray-500">Real-time status pengajuan</p>
            </div>
            <div class="bg-gray-50 p-2 rounded-lg text-gray-400"><i class="fas fa-chart-bar"></i></div>
        </div>
        <div class="relative h-48 w-full">
            <canvas id="suratChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i> Surat Terbaru
            </h3>
            <?php if($admin_role !== 'kepala_desa'): ?>
            <a href="kelola-surat.php" class="text-xs text-primary-600 hover:underline font-semibold">Lihat semua</a>
            <?php endif; ?>
        </div>
        <div class="divide-y divide-gray-50 flex-1">
            <?php if($surat_terbaru->num_rows === 0): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pengajuan surat.</div>
            <?php else: while($row = $surat_terbaru->fetch_assoc()): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= htmlspecialchars($row['jenis_surat']) ?></div>
                </div>
                <?php
                $cls = ['Diproses' => 'bg-yellow-100 text-yellow-700 border-yellow-200', 'Selesai' => 'bg-green-100 text-green-700 border-green-200', 'Ditolak' => 'bg-red-100 text-red-700 border-red-200'];
                $badge = $cls[$row['status']] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                ?>
                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold border <?= $badge ?> shrink-0 uppercase tracking-wider">
                    <?= $row['status'] ?>
                </span>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-comment-dots text-orange-500"></i> Pengaduan Terbaru
            </h3>
            <?php if($admin_role !== 'kepala_desa'): ?>
            <a href="kelola-pengaduan.php" class="text-xs text-primary-600 hover:underline font-semibold">Lihat semua</a>
            <?php endif; ?>
        </div>
        <div class="divide-y divide-gray-50 flex-1">
            <?php if($pengaduan_baru_->num_rows === 0): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pengaduan masyarakat.</div>
            <?php else: while($row = $pengaduan_baru_->fetch_assoc()): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= htmlspecialchars(substr($row['isi'], 0, 50)) ?>...</div>
                </div>
                <?php
                $cls2 = ['Masuk' => 'bg-red-100 text-red-700 border-red-200', 'Diproses' => 'bg-yellow-100 text-yellow-700 border-yellow-200', 'Selesai' => 'bg-green-100 text-green-700 border-green-200'];
                $badge2 = $cls2[$row['status']] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                ?>
                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold border <?= $badge2 ?> shrink-0 uppercase tracking-wider">
                    <?= $row['status'] ?>
                </span>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctxGender = document.getElementById('genderChart').getContext('2d');
    new Chart(ctxGender, {
        type: 'doughnut',
        data: { labels: ['Laki-laki', 'Perempuan'], datasets: [{ data: [<?= $penduduk_laki ?>, <?= $penduduk_perempuan ?>], backgroundColor: ['#3b82f6', '#ec4899'], borderWidth: 0, hoverOffset: 4 }] },
        options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8, font: {size: 11} } } } }
    });

    const ctxSurat = document.getElementById('suratChart').getContext('2d');
    new Chart(ctxSurat, {
        type: 'bar',
        data: { labels: ['Diproses', 'Selesai', 'Ditolak'], datasets: [{ label: 'Jumlah Surat', data: [<?= $surat_diproses ?>, <?= $surat_selesai ?>, <?= $surat_ditolak ?>], backgroundColor: ['#eab308', '#22c55e', '#ef4444'], borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 }, grid: { borderDash: [4, 4] }, border: { display: false } }, x: { grid: { display: false }, border: { display: false } } } }
    });
});
</script>

<?php require_once 'layout-footer.php'; ?>