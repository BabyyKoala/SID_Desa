<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Berita';

// DELETE
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row_ = $conn->query("SELECT gambar FROM berita WHERE id=$id")->fetch_assoc();
    if($row_['gambar'] && file_exists('../uploads/berita/'.$row_['gambar'])) {
        unlink('../uploads/berita/'.$row_['gambar']);
    }
    $conn->query("DELETE FROM berita WHERE id=$id");
    redirect('kelola-berita.php?msg=deleted');
}

$action = $_GET['action'] ?? 'list';
$edit_data = null;

if($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $edit_data = $conn->query("SELECT * FROM berita WHERE id=$id")->fetch_assoc();
    if(!$edit_data) redirect('kelola-berita.php');
}

// SAVE (add/edit)
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = clean($_POST['judul'] ?? '');
    $isi   = htmlspecialchars(trim($_POST['isi'] ?? ''));
    $id    = (int)($_POST['id'] ?? 0);

    if($judul && $isi) {
        // Handle upload
        $gambar = $_POST['existing_gambar'] ?? '';
        if(isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if(in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $newname = 'berita_' . time() . '.' . $ext;
                if(move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/berita/'.$newname)) {
                    if($gambar && file_exists('../uploads/berita/'.$gambar)) unlink('../uploads/berita/'.$gambar);
                    $gambar = $newname;
                }
            }
        }

        if($id) {
            $stmt = $conn->prepare("UPDATE berita SET judul=?, isi=?, gambar=? WHERE id=?");
            $stmt->bind_param("sssi", $judul, $isi, $gambar, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO berita (judul, isi, gambar) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $judul, $isi, $gambar);
        }
        $stmt->execute();
        redirect('kelola-berita.php?msg=saved');
    }
}

$berita = $conn->query("SELECT * FROM berita ORDER BY tanggal DESC");
require_once 'layout.php';
?>

<?php if(isset($_GET['msg'])): ?>
<div class="bg-primary-50 border border-primary-200 text-primary-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle"></i>
    <?= $_GET['msg'] === 'saved' ? 'Berita berhasil disimpan.' : 'Berita berhasil dihapus.' ?>
</div>
<?php endif; ?>

<?php if($action === 'list'): ?>

<!-- Header -->
<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-gray-500"><?= $berita->num_rows ?> berita tersimpan</div>
    <a href="?action=add" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah Berita
    </a>
</div>

<div class="space-y-3">
    <?php while($row = $berita->fetch_assoc()): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex gap-4 items-start">
        <div class="w-16 h-16 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden">
            <?php if($row['gambar'] && file_exists('../uploads/berita/'.$row['gambar'])): ?>
            <img src="../uploads/berita/<?= $row['gambar'] ?>" class="w-16 h-16 object-cover rounded-xl">
            <?php else: ?>
            <i class="fas fa-newspaper text-primary-400 text-xl"></i>
            <?php endif; ?>
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-bold text-gray-800 mb-0.5 line-clamp-1"><?= htmlspecialchars($row['judul']) ?></div>
            <div class="text-xs text-gray-400 mb-2"><?= formatTanggal($row['tanggal']) ?></div>
            <div class="text-sm text-gray-500 line-clamp-2"><?= strip_tags(substr($row['isi'], 0, 100)) ?>...</div>
        </div>
        <div class="flex gap-2 flex-shrink-0">
            <a href="?action=edit&id=<?= $row['id'] ?>" 
               class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus berita ini?')"
               class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
    <?php endwhile; ?>
    <?php if($berita->num_rows === 0): ?>
    <div class="text-center py-16 text-gray-400">
        <i class="fas fa-newspaper text-4xl mb-3"></i>
        <p>Belum ada berita. <a href="?action=add" class="text-primary-600 font-semibold">Tambah sekarang</a></p>
    </div>
    <?php endif; ?>
</div>

<?php else: // FORM ADD/EDIT ?>

<div class="mb-5">
    <a href="kelola-berita.php" class="text-sm text-primary-600 hover:underline flex items-center gap-1 font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar Berita
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-bold text-gray-800 mb-5 text-lg"><?= $edit_data ? 'Edit Berita' : 'Tambah Berita Baru' ?></h2>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
        <?php if($edit_data): ?>
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <input type="hidden" name="existing_gambar" value="<?= $edit_data['gambar'] ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Berita <span class="text-red-500">*</span></label>
            <input type="text" name="judul" 
                   value="<?= htmlspecialchars($edit_data['judul'] ?? '') ?>"
                   placeholder="Judul berita yang menarik..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Isi Berita <span class="text-red-500">*</span></label>
            <textarea name="isi" rows="8" 
                      placeholder="Tulis isi berita lengkap di sini..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"
                      required><?= htmlspecialchars($edit_data['isi'] ?? '') ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Gambar <span class="text-gray-400 font-normal">(opsional)</span></label>
            <?php if(!empty($edit_data['gambar']) && file_exists('../uploads/berita/'.$edit_data['gambar'])): ?>
            <div class="mb-3 flex items-center gap-3">
                <img src="../uploads/berita/<?= $edit_data['gambar'] ?>" class="h-20 rounded-lg border object-cover">
                <span class="text-xs text-gray-400">Gambar saat ini. Upload baru untuk mengganti.</span>
            </div>
            <?php endif; ?>
            <input type="file" name="gambar" accept="image/*"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-primary-100 file:text-primary-700 file:text-xs file:font-semibold">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-3 rounded-xl flex items-center gap-2 transition">
                <i class="fas fa-save"></i> <?= $edit_data ? 'Simpan Perubahan' : 'Publish Berita' ?>
            </button>
            <a href="kelola-berita.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">Batal</a>
        </div>
    </form>
</div>

<?php endif; ?>
<?php require_once 'layout-footer.php'; ?>
