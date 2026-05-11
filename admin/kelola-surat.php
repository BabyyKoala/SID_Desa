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

// Fungsi clean sederhana jika belum ada di db.php
if (!function_exists('clean')) {
    function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}

$filter = $_GET['status'] ?? '';
$search = clean($_GET['q'] ?? '');
$where = "WHERE 1=1";
if($filter) $where .= " AND status='$filter'";
if($search) $where .= " AND (nama LIKE '%$search%' OR nik LIKE '%$search%' OR kode_pengajuan LIKE '%$search%')";

$surat = $conn->query("SELECT * FROM surat $where ORDER BY tanggal DESC");

require_once 'layout.php';

// Memperbaiki fungsi badgeStatus agar menggunakan class Tailwind bawaan
function badgeStatus($s) {
    $map = [
        'Diproses' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
        'Selesai'  => 'bg-green-100 text-green-700 border border-green-200',
        'Ditolak'  => 'bg-red-100 text-red-700 border border-red-200'
    ];
    return $map[$s] ?? 'bg-gray-100 text-gray-600 border border-gray-200';
}
?>

<?php if(isset($_GET['msg'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let msg = '<?= $_GET['msg'] ?>';
    let pesan = '';
    if(msg === 'updated') pesan = 'Status surat berhasil diperbarui!';
    else if(msg === 'deleted') pesan = 'Data pengajuan berhasil dihapus!';

    if(pesan) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: pesan,
            confirmButtonColor: '#059669',
            customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
        }).then(() => {
            if(window.location.search.includes('msg=')) {
                const url = new URL(window.location);
                url.searchParams.delete('msg');
                window.history.replaceState(null, null, url.pathname + url.search);
            }
        });
    }
});
</script>
<?php endif; ?>

<div class="flex flex-wrap gap-3 mb-6">
    <form method="GET" class="flex gap-2 flex-1 min-w-0">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>"
               placeholder="Cari nama, NIK, kode..."
               class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <?php if($filter): ?><input type="hidden" name="status" value="<?= $filter ?>"><?php endif; ?>
        <button type="submit" class="bg-green-600 text-white px-4 py-2.5 rounded-xl text-sm hover:bg-green-700 shadow-sm transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
    <div class="flex gap-2 flex-wrap">
        <?php foreach([''=>'Semua','Diproses'=>'Diproses','Selesai'=>'Selesai','Ditolak'=>'Ditolak'] as $v=>$l): ?>
        <a href="?status=<?= $v ?><?= $search ? '&q='.$search : '' ?>"
           class="px-4 py-2.5 rounded-xl text-xs font-semibold border transition shadow-sm
                  <?= $filter === $v ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
            <?= $l ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

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
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <i class="fas fa-envelope-open text-3xl mb-3 text-gray-300 block"></i>
                    Belum ada data surat pengajuan.
                </td>
            </tr>
            <?php else: foreach($rows as $row): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3">
                    <span class="font-mono text-xs text-green-700 font-semibold bg-green-50 px-2 py-1 rounded"><?= $row['kode_pengajuan'] ?></span>
                </td>
                <td class="px-5 py-3">
                    <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400 mt-0.5"><?= $row['nik'] ?></div>
                </td>
                <td class="px-5 py-3 text-gray-600 font-medium"><?= htmlspecialchars($row['jenis_surat']) ?></td>
                <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
                    <?= isset($row['tanggal']) ? date('d M Y, H:i', strtotime($row['tanggal'])) : '' ?>
                </td>
                <td class="px-5 py-3">
                    <form method="POST" action="kelola-surat.php" class="inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="update_status" value="1">
                        <select name="status" onchange="this.form.submit()" 
                                class="text-xs font-semibold px-3 py-1.5 rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-400 appearance-none <?= badgeStatus($row['status']) ?>">
                            <option value="Diproses" <?= $row['status']=='Diproses'?'selected':'' ?>>Diproses</option>
                            <option value="Selesai"  <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                            <option value="Ditolak"  <?= $row['status']=='Ditolak'?'selected':'' ?>>Ditolak</option>
                        </select>
                    </form>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <?php if($row['status'] == 'Selesai'): ?>
                        <a href="cetak-surat.php?id=<?= $row['id'] ?>" target="_blank" title="Cetak Surat PDF"
                           class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 border border-blue-200">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                        <?php endif; ?>
                        
                        <a href="#" 
                           onclick="konfirmasiHapus('?delete=<?= $row['id'] ?>', 'Pengajuan surat dari <?= addslashes(htmlspecialchars($row['nama'])) ?>'); return false;"
                           title="Hapus Data"
                           class="text-red-500 hover:text-red-700 text-xs font-semibold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition inline-flex items-center justify-center border border-red-100">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="md:hidden space-y-3">
    <?php if(empty($rows)): ?>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center text-gray-400">
            <i class="fas fa-envelope-open text-3xl mb-3 text-gray-300 block"></i>
            Belum ada data surat.
        </div>
    <?php else: foreach($rows as $row): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex items-start justify-between gap-2 mb-2">
            <div>
                <div class="font-mono text-xs text-green-700 font-bold bg-green-50 px-2 py-0.5 rounded inline-block mb-1"><?= $row['kode_pengajuan'] ?></div>
                <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                <div class="text-xs text-gray-400"><?= $row['nik'] ?></div>
            </div>
            <a href="#" onclick="konfirmasiHapus('?delete=<?= $row['id'] ?>', 'Pengajuan surat dari <?= addslashes(htmlspecialchars($row['nama'])) ?>'); return false;" class="text-red-400 hover:text-red-600 text-sm bg-red-50 p-2 rounded-lg">
                <i class="fas fa-trash"></i>
            </a>
        </div>
        <div class="text-sm text-gray-600 font-medium mb-1"><?= htmlspecialchars($row['jenis_surat']) ?></div>
        <div class="text-xs text-gray-400 mb-3"><?= isset($row['tanggal']) ? date('d M Y, H:i', strtotime($row['tanggal'])) : '' ?></div>
        
        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
            <form method="POST" action="kelola-surat.php" class="flex items-center gap-2">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="hidden" name="update_status" value="1">
                <select name="status" onchange="this.form.submit()" 
                        class="text-xs font-semibold px-3 py-1.5 rounded-full focus:outline-none <?= badgeStatus($row['status']) ?>">
                    <option value="Diproses" <?= $row['status']=='Diproses'?'selected':'' ?>>Diproses</option>
                    <option value="Selesai"  <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                    <option value="Ditolak"  <?= $row['status']=='Ditolak'?'selected':'' ?>>Ditolak</option>
                </select>
            </form>
            
            <?php if($row['status'] == 'Selesai'): ?>
            <a href="cetak-surat.php?id=<?= $row['id'] ?>" target="_blank"
               class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 border border-blue-200">
                <i class="fas fa-print"></i> Cetak
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; endif; ?>
</div>

<?php require_once 'layout-footer.php'; ?>