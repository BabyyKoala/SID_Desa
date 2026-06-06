<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Pengaduan';

// ============================================
// HANDLE UPDATE STATUS (Prepared Statement)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id     = (int)$_POST['id'];
    $status = in_array($_POST['status'], ['Masuk', 'Diproses', 'Selesai']) ? $_POST['status'] : 'Masuk';

    $stmt = $conn->prepare("UPDATE pengaduan SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola-pengaduan.php?msg=updated");
    exit;
}

// ============================================
// HANDLE DELETE (Prepared Statement)
// ============================================
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    $stmt = $conn->prepare("SELECT foto FROM pengaduan WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row_ = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row_ && $row_['foto'] && file_exists('../uploads/pengaduan/' . $row_['foto'])) {
        unlink('../uploads/pengaduan/' . $row_['foto']);
    }

    $stmt = $conn->prepare("DELETE FROM pengaduan WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola-pengaduan.php?msg=deleted");
    exit;
}

// ============================================
// FETCH DATA (Filter + Prepared Statement)
// ============================================
$allowed_status = ['Masuk', 'Diproses', 'Selesai'];
$filter = (isset($_GET['status']) && in_array($_GET['status'], $allowed_status)) ? $_GET['status'] : '';

if ($filter) {
    $stmt = $conn->prepare("SELECT * FROM pengaduan WHERE status=? ORDER BY tanggal DESC");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $pengaduan = $stmt->get_result();
} else {
    $pengaduan = $conn->query("SELECT * FROM pengaduan ORDER BY tanggal DESC");
}

// Ambil semua data dulu untuk cek num_rows sebelum loop
$semua_pengaduan = $pengaduan->fetch_all(MYSQLI_ASSOC);
$total_pengaduan = count($semua_pengaduan);

require_once 'layout.php';

// ============================================
// HELPER: Bersihkan \r\n literal dari DB
// ============================================
function bersihkan_teks($teks) {
    // Tangani \r\n yang tersimpan sebagai literal string maupun newline asli
    $teks = str_replace(['\r\n', '\r\n', '\r', '\n'], "\n", $teks);
    return nl2br(htmlspecialchars(trim($teks)));
}
?>

<?php if (isset($_GET['msg'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let msg = '<?= htmlspecialchars($_GET['msg']) ?>';
    let pesan = '';
    if (msg === 'updated') pesan = 'Status pengaduan berhasil diperbarui!';
    else if (msg === 'deleted') pesan = 'Data pengaduan berhasil dihapus!';

    if (pesan) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: pesan,
            confirmButtonColor: '#059669',
            customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    }
});
</script>
<?php endif; ?>

<!-- FILTER TABS -->
<div class="flex gap-2 mb-6 flex-wrap">
    <?php foreach ([''=>'Semua', 'Masuk'=>'Baru', 'Diproses'=>'Diproses', 'Selesai'=>'Selesai'] as $v => $l): ?>
    <a href="?status=<?= $v ?>"
       class="px-4 py-2 rounded-xl text-xs font-semibold border transition shadow-sm
              <?= $filter === $v ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
        <?= $l ?>
        <?php if ($v):
            $stmt_cnt = $conn->prepare("SELECT COUNT(*) as c FROM pengaduan WHERE status=?");
            $stmt_cnt->bind_param("s", $v);
            $stmt_cnt->execute();
            $cnt = $stmt_cnt->get_result()->fetch_assoc()['c'];
            $stmt_cnt->close();
            echo "<span class='ml-1 opacity-75'>($cnt)</span>";
        endif; ?>
    </a>
    <?php endforeach; ?>
</div>

<!-- LIST PENGADUAN -->
<div class="space-y-4">
    <?php if ($total_pengaduan === 0): ?>
    <div class="text-center py-16 text-gray-400 bg-white rounded-xl border border-gray-100 border-dashed">
        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
        <p>Belum ada data pengaduan<?= $filter ? ' untuk status ini' : '' ?>.</p>
    </div>

    <?php else: ?>
    <?php foreach ($semua_pengaduan as $row):
        $badge_map = [
            'Masuk'    => 'bg-red-100 text-red-700 border-red-200',
            'Diproses' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'Selesai'  => 'bg-green-100 text-green-700 border-green-200'
        ];
        $badge = $badge_map[$row['status']] ?? 'bg-gray-100 text-gray-700 border-gray-200';
    ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <!-- Header: Nama + Badge + Tombol Hapus -->
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-orange-500 text-sm"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></div>
                    <div class="text-xs text-gray-400">
                        <?= isset($row['tanggal']) ? htmlspecialchars($row['tanggal']) : '' ?>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border <?= $badge ?>">
                    <?= htmlspecialchars($row['status']) ?>
                </span>
                <a href="#"
                   onclick="konfirmasiHapus('?delete=<?= $row['id'] ?>', '<?= addslashes(htmlspecialchars($row['nama'])) ?>'); return false;"
                   class="text-red-400 hover:text-red-600 text-sm bg-red-50 hover:bg-red-100 p-2 rounded-lg transition">
                    <i class="fas fa-trash"></i>
                </a>
            </div>
        </div>

        <!-- Isi Pengaduan: fix \r\n literal -->
        <div class="text-gray-700 text-sm leading-relaxed mb-4 bg-gray-50 rounded-lg p-4 border border-gray-100">
            <?= bersihkan_teks($row['isi']) ?>
        </div>

        <!-- Foto (jika ada) -->
        <?php if (!empty($row['foto']) && file_exists('../uploads/pengaduan/' . $row['foto'])): ?>
        <div class="mb-4">
            <img src="../uploads/pengaduan/<?= htmlspecialchars($row['foto']) ?>"
                 class="max-h-40 rounded-lg border cursor-pointer hover:opacity-90 transition"
                 onclick="window.open(this.src,'_blank')"
                 alt="Foto pengaduan dari <?= htmlspecialchars($row['nama']) ?>">
        </div>
        <?php endif; ?>

        <!-- Form Update Status -->
        <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-gray-100">
            <form method="POST" action="kelola-pengaduan.php" class="flex items-center gap-2 mt-2">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="hidden" name="update_status" value="1">
                <label class="text-xs text-gray-500 font-semibold">Ubah Status:</label>
                <select name="status" onchange="this.form.submit()"
                        class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white cursor-pointer hover:bg-gray-50">
                    <option value="Masuk"    <?= $row['status'] === 'Masuk'    ? 'selected' : '' ?>>Masuk</option>
                    <option value="Diproses" <?= $row['status'] === 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai"  <?= $row['status'] === 'Selesai'  ? 'selected' : '' ?>>Selesai</option>
                </select>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'layout-footer.php'; ?>