<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Surat';

// Update status
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int)$_POST['id'];
    $status = in_array($_POST['status'], ['Diproses','Selesai','Ditolak']) ? $_POST['status'] : 'Diproses';
    $conn->query("UPDATE surat SET status='$status' WHERE id=$id");
    header("Location: kelola-surat.php?msg=updated");
    exit;
}

// Delete
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM surat WHERE id=$id");
    header("Location: kelola-surat.php?msg=deleted");
    exit;
}

$filter = $_GET['status'] ?? '';
$search = clean($_GET['q'] ?? '');
$where = "WHERE 1=1";
if($filter) $where .= " AND status='$filter'";
if($search) $where .= " AND (nama LIKE '%$search%' OR nik LIKE '%$search%' OR kode_pengajuan LIKE '%$search%')";

$surat = $conn->query("SELECT * FROM surat $where ORDER BY tanggal DESC");

require_once 'layout.php';

function badgeStatus($s) {
    $map = ['Diproses'=>'badge-diproses','Selesai'=>'badge-selesai','Ditolak'=>'badge-ditolak'];
    return $map[$s] ?? 'bg-gray-100 text-gray-600';
}
?>

<?php if(isset($_GET['msg'])): ?>
<div class="bg-primary-50 border border-primary-200 text-primary-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle"></i>
    <?= $_GET['msg'] === 'updated' ? 'Status berhasil diperbarui.' : 'Data berhasil dihapus.' ?>
</div>
<?php endif; ?>

<!-- Filter & Search -->
<div class="flex flex-wrap gap-3 mb-6">
    <form method="GET" class="flex gap-2 flex-1 min-w-0">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
               placeholder="Cari nama, NIK, kode..."
               class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
        <?php if($filter): ?><input type="hidden" name="status" value="<?= $filter ?>"><?php endif; ?>
        <button type="submit" class="bg-primary-600 text-white px-4 py-2.5 rounded-xl text-sm hover:bg-primary-700">
            <i class="fas fa-search"></i>
        </button>
    </form>
    <div class="flex gap-2">
        <?php foreach([''=>'Semua','Diproses'=>'Diproses','Selesai'=>'Selesai','Ditolak'=>'Ditolak'] as $v=>$l): ?>
        <a href="?status=<?= $v ?><?= $search ? '&q='.$search : '' ?>"
           class="px-4 py-2.5 rounded-xl text-xs font-semibold border transition
                  <?= $filter === $v ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
            <?= $l ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Table (Mobile: Cards) -->
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-5 py-3 text-left">Kode</th>
                <th class="px-5 py-3 text-left">Nama / NIK</th>
                <th class="px-5 py-3 text-left">Jenis Surat</th>
                <th class="px-5 py-3 text-left">Tanggal</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php 
            $rows = [];
            while($row = $surat->fetch_assoc()) $rows[] = $row;
            if(empty($rows)): ?>
            <tr><td colspan="6" class="text-center py-10 text-gray-400">Belum ada data surat</td></tr>
            <?php else: foreach($rows as $row): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <span class="font-mono text-xs text-primary-700 font-semibold"><?= $row['kode_pengajuan'] ?></span>
                </td>
                <td class="px-5 py-3">
                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400"><?= $row['nik'] ?></div>
                </td>
                <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($row['jenis_surat']) ?></td>
                <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap"><?= formatTanggal($row['tanggal']) ?></td>
                <td class="px-5 py-3">
                    <form method="POST" class="inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="update_status" value="1">
                        <select name="status" onchange="this.form.submit()" 
                                class="text-xs font-semibold px-2.5 py-1 rounded-full border-0 cursor-pointer focus:ring-1 focus:ring-primary-300 <?= badgeStatus($row['status']) ?>">
                            <option value="Diproses" <?= $row['status']=='Diproses'?'selected':'' ?>>Diproses</option>
                            <option value="Selesai"  <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                            <option value="Ditolak"  <?= $row['status']=='Ditolak'?'selected':'' ?>>Ditolak</option>
                        </select>
                    </form>
                </td>
                <td class="px-5 py-3 text-center">
                    <a href="?delete=<?= $row['id'] ?>" 
                       onclick="return confirm('Hapus data surat ini?')"
                       class="text-red-500 hover:text-red-700 text-xs font-semibold">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<!-- Mobile Cards -->
<div class="md:hidden space-y-3">
    <?php 
    $surat2 = $conn->query("SELECT * FROM surat $where ORDER BY tanggal DESC");
    while($row = $surat2->fetch_assoc()): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex items-start justify-between gap-2 mb-2">
            <div>
                <div class="font-mono text-xs text-primary-700 font-bold"><?= $row['kode_pengajuan'] ?></div>
                <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                <div class="text-xs text-gray-400"><?= $row['nik'] ?></div>
            </div>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-400 hover:text-red-600 text-sm">
                <i class="fas fa-trash"></i>
            </a>
        </div>
        <div class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($row['jenis_surat']) ?></div>
        <form method="POST" class="flex items-center gap-2">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="hidden" name="update_status" value="1">
            <label class="text-xs text-gray-500">Status:</label>
            <select name="status" onchange="this.form.submit()" 
                    class="text-xs px-3 py-1.5 rounded-full border border-gray-200 focus:outline-none <?= badgeStatus($row['status']) ?>">
                <option value="Diproses" <?= $row['status']=='Diproses'?'selected':'' ?>>Diproses</option>
                <option value="Selesai"  <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                <option value="Ditolak"  <?= $row['status']=='Ditolak'?'selected':'' ?>>Ditolak</option>
            </select>
        </form>
    </div>
    <?php endwhile; ?>
</div>

<?php require_once 'layout-footer.php'; ?>
