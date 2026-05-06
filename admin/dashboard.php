<?php
session_start();
require_once '../config/db.php';
$page_title = 'Dashboard';
require_once 'layout.php';

// Stats
$total_surat      = $conn->query("SELECT COUNT(*) as c FROM surat")->fetch_assoc()['c'];
$surat_diproses   = $conn->query("SELECT COUNT(*) as c FROM surat WHERE status='Diproses'")->fetch_assoc()['c'];
$total_pengaduan  = $conn->query("SELECT COUNT(*) as c FROM pengaduan")->fetch_assoc()['c'];
$pengaduan_baru   = $conn->query("SELECT COUNT(*) as c FROM pengaduan WHERE status='Masuk'")->fetch_assoc()['c'];
$total_berita     = $conn->query("SELECT COUNT(*) as c FROM berita")->fetch_assoc()['c'];
$total_umkm       = $conn->query("SELECT COUNT(*) as c FROM umkm")->fetch_assoc()['c'];

// Recent data
$surat_terbaru   = $conn->query("SELECT * FROM surat ORDER BY tanggal DESC LIMIT 5");
$pengaduan_baru_ = $conn->query("SELECT * FROM pengaduan ORDER BY tanggal DESC LIMIT 5");
?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php
    $cards = [
        ['label'=>'Total Surat','value'=>$total_surat,'sub'=>"$surat_diproses diproses",'icon'=>'fa-file-alt','color'=>'bg-blue-500','bg'=>'bg-blue-50'],
        ['label'=>'Pengaduan','value'=>$total_pengaduan,'sub'=>"$pengaduan_baru baru",'icon'=>'fa-comment-dots','color'=>'bg-orange-500','bg'=>'bg-orange-50'],
        ['label'=>'Berita','value'=>$total_berita,'sub'=>'artikel','icon'=>'fa-newspaper','color'=>'bg-purple-500','bg'=>'bg-purple-50'],
        // Ubah warna UMKM dari primary ke green
        ['label'=>'UMKM','value'=>$total_umkm,'sub'=>'usaha warga','icon'=>'fa-store','color'=>'bg-green-500','bg'=>'bg-green-50'],
    ];
    foreach($cards as $c): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-2xl font-extrabold text-gray-800"><?= number_format($c['value'], 0, ',', '.') ?></div>
                <div class="text-sm font-semibold text-gray-700 mt-0.5"><?= $c['label'] ?></div>
                <div class="text-xs text-gray-400 mt-0.5"><?= $c['sub'] ?></div>
            </div>
            <div class="w-10 h-10 <?= $c['bg'] ?> rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas <?= $c['icon'] ?> <?= str_replace('bg-','text-',$c['color']) ?>"></i>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <?php
    $actions = [
        ['href'=>'kelola-surat.php','label'=>'Kelola Surat','icon'=>'fa-file-alt','color'=>'bg-blue-600 hover:bg-blue-700'],
        ['href'=>'kelola-pengaduan.php','label'=>'Kelola Pengaduan','icon'=>'fa-comment-dots','color'=>'bg-orange-500 hover:bg-orange-600'],
        ['href'=>'kelola-berita.php?action=add','label'=>'Tambah Berita','icon'=>'fa-plus-circle','color'=>'bg-purple-600 hover:bg-purple-700'],
        // Ubah warna Tambah UMKM dari primary ke green
        ['href'=>'kelola-umkm.php?action=add','label'=>'Tambah UMKM','icon'=>'fa-store','color'=>'bg-green-600 hover:bg-green-700'],
    ];
    foreach($actions as $a): ?>
    <a href="<?= $a['href'] ?>" 
       class="<?= $a['color'] ?> text-white rounded-xl px-4 py-3 text-sm font-semibold flex items-center gap-2 justify-center shadow-sm transition">
        <i class="fas <?= $a['icon'] ?>"></i> <?= $a['label'] ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Surat Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i> Surat Terbaru
            </h3>
            <a href="kelola-surat.php" class="text-xs text-green-600 hover:underline font-semibold">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if($surat_terbaru->num_rows === 0): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pengajuan surat.</div>
            <?php else: while($row = $surat_terbaru->fetch_assoc()): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= htmlspecialchars($row['jenis_surat']) ?> · <span class="font-mono text-green-600"><?= $row['kode_pengajuan'] ?></span></div>
                </div>
                <?php
                // Perbaikan class badge
                $cls = [
                    'Diproses' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                    'Selesai'  => 'bg-green-100 text-green-700 border border-green-200',
                    'Ditolak'  => 'bg-red-100 text-red-700 border border-red-200'
                ];
                $badge = $cls[$row['status']] ?? 'bg-gray-100 text-gray-600 border border-gray-200';
                ?>
                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold <?= $badge ?> shrink-0 uppercase tracking-wider">
                    <?= $row['status'] ?>
                </span>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-comment-dots text-orange-500"></i> Pengaduan Terbaru
            </h3>
            <a href="kelola-pengaduan.php" class="text-xs text-green-600 hover:underline font-semibold">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php if($pengaduan_baru_->num_rows === 0): ?>
                <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pengaduan masyarakat.</div>
            <?php else: while($row = $pengaduan_baru_->fetch_assoc()): ?>
            <div class="px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= htmlspecialchars(substr($row['isi'], 0, 50)) ?>...</div>
                </div>
                <?php
                // Perbaikan class badge
                $cls2 = [
                    'Masuk'    => 'bg-red-100 text-red-700 border border-red-200',
                    'Diproses' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                    'Selesai'  => 'bg-green-100 text-green-700 border border-green-200'
                ];
                $badge2 = $cls2[$row['status']] ?? 'bg-gray-100 text-gray-600 border border-gray-200';
                ?>
                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold <?= $badge2 ?> shrink-0 uppercase tracking-wider">
                    <?= $row['status'] ?>
                </span>
            </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>

<?php require_once 'layout-footer.php'; ?>