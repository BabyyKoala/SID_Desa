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
        ['label'=>'UMKM','value'=>$total_umkm,'sub'=>'usaha warga','icon'=>'fa-store','color'=>'bg-primary-500','bg'=>'bg-primary-50'],
    ];
    foreach($cards as $c): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-2xl font-extrabold text-gray-800"><?= $c['value'] ?></div>
                <div class="text-sm font-semibold text-gray-700 mt-0.5"><?= $c['label'] ?></div>
                <div class="text-xs text-gray-400 mt-0.5"><?= $c['sub'] ?></div>
            </div>
            <div class="w-10 h-10 <?= $c['bg'] ?> rounded-xl flex items-center justify-center">
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
        ['href'=>'kelola-umkm.php?action=add','label'=>'Tambah UMKM','icon'=>'fa-store','color'=>'bg-primary-600 hover:bg-primary-700'],
    ];
    foreach($actions as $a): ?>
    <a href="<?= $a['href'] ?>" 
       class="<?= $a['color'] ?> text-white rounded-xl px-4 py-3 text-sm font-semibold flex items-center gap-2 justify-center transition">
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
            <a href="kelola-surat.php" class="text-xs text-primary-600 hover:underline font-semibold">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php while($row = $surat_terbaru->fetch_assoc()): ?>
            <div class="px-5 py-3.5 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400"><?= htmlspecialchars($row['jenis_surat']) ?> · <?= $row['kode_pengajuan'] ?></div>
                </div>
                <?php
                $cls = ['Diproses'=>'badge-diproses','Selesai'=>'badge-selesai','Ditolak'=>'badge-ditolak'];
                $badge = $cls[$row['status']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge ?> shrink-0">
                    <?= $row['status'] ?>
                </span>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-comment-dots text-orange-500"></i> Pengaduan Terbaru
            </h3>
            <a href="kelola-pengaduan.php" class="text-xs text-primary-600 hover:underline font-semibold">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php while($row = $pengaduan_baru_->fetch_assoc()): ?>
            <div class="px-5 py-3.5 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400 truncate"><?= htmlspecialchars(substr($row['isi'], 0, 50)) ?>...</div>
                </div>
                <?php
                $cls2 = ['Masuk'=>'badge-masuk','Diproses'=>'badge-diproses','Selesai'=>'badge-selesai'];
                $badge2 = $cls2[$row['status']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge2 ?> shrink-0">
                    <?= $row['status'] ?>
                </span>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php require_once 'layout-footer.php'; ?>
