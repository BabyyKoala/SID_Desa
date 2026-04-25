<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Pengaduan';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int)$_POST['id'];
    $status = in_array($_POST['status'], ['Masuk','Diproses','Selesai']) ? $_POST['status'] : 'Masuk';
    $conn->query("UPDATE pengaduan SET status='$status' WHERE id=$id");
    header("Location: kelola-pengaduan.php?msg=updated");
    exit;
}

if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row_ = $conn->query("SELECT foto FROM pengaduan WHERE id=$id")->fetch_assoc();
    if($row_['foto'] && file_exists('../uploads/pengaduan/'.$row_['foto'])) {
        unlink('../uploads/pengaduan/'.$row_['foto']);
    }
    $conn->query("DELETE FROM pengaduan WHERE id=$id");
    header("Location: kelola-pengaduan.php?msg=deleted");
    exit;
}

$filter = $_GET['status'] ?? '';
$where = $filter ? "WHERE status='$filter'" : "";
$pengaduan = $conn->query("SELECT * FROM pengaduan $where ORDER BY tanggal DESC");

require_once 'layout.php';
?>

<?php if(isset($_GET['msg'])): ?>
<div class="bg-primary-50 border border-primary-200 text-primary-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle"></i>
    <?= $_GET['msg'] === 'updated' ? 'Status berhasil diperbarui.' : 'Data berhasil dihapus.' ?>
</div>
<?php endif; ?>

<!-- Filter -->
<div class="flex gap-2 mb-6 flex-wrap">
    <?php foreach([''=>'Semua','Masuk'=>'Baru','Diproses'=>'Diproses','Selesai'=>'Selesai'] as $v=>$l): ?>
    <a href="?status=<?= $v ?>"
       class="px-4 py-2 rounded-xl text-xs font-semibold border transition
              <?= $filter === $v ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
        <?= $l ?>
        <?php if($v): 
            $cnt = $conn->query("SELECT COUNT(*) as c FROM pengaduan WHERE status='$v'")->fetch_assoc()['c'];
            echo "<span class='ml-1 opacity-75'>($cnt)</span>";
        endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="space-y-4">
    <?php while($row = $pengaduan->fetch_assoc()): 
        $badge_map = ['Masuk'=>'badge-masuk','Diproses'=>'badge-diproses','Selesai'=>'badge-selesai'];
        $badge = $badge_map[$row['status']] ?? 'bg-gray-100';
    ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-orange-500 text-sm"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400"><?= formatTanggal($row['tanggal']) ?></div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge ?>"><?= $row['status'] ?></span>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus pengaduan ini?')" 
                   class="text-red-400 hover:text-red-600 text-sm">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>

        <div class="text-gray-700 text-sm leading-relaxed mb-4 bg-gray-50 rounded-lg p-4">
            <?= nl2br(htmlspecialchars($row['isi'])) ?>
        </div>

        <?php if($row['foto'] && file_exists('../uploads/pengaduan/'.$row['foto'])): ?>
        <div class="mb-4">
            <img src="../uploads/pengaduan/<?= $row['foto'] ?>" 
                 class="max-h-40 rounded-lg border cursor-pointer" 
                 onclick="window.open(this.src,'_blank')" alt="Foto pengaduan">
        </div>
        <?php endif; ?>

        <div class="flex flex-wrap items-center gap-3">
            <form method="POST" class="flex items-center gap-2">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="hidden" name="update_status" value="1">
                <label class="text-xs text-gray-500 font-semibold">Ubah Status:</label>
                <select name="status" onchange="this.form.submit()" 
                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 focus:outline-none bg-white">
                    <option value="Masuk"    <?= $row['status']=='Masuk'?'selected':'' ?>>Masuk</option>
                    <option value="Diproses" <?= $row['status']=='Diproses'?'selected':'' ?>>Diproses</option>
                    <option value="Selesai"  <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php require_once 'layout-footer.php'; ?>
