<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola UMKM';

// DELETE
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $row_ = $conn->query("SELECT foto FROM umkm WHERE id=$id")->fetch_assoc();
    if($row_['foto'] && file_exists('../uploads/umkm/'.$row_['foto'])) {
        unlink('../uploads/umkm/'.$row_['foto']);
    }
    $conn->query("DELETE FROM umkm WHERE id=$id");
    redirect('kelola-umkm.php?msg=deleted');
}

$action = $_GET['action'] ?? 'list';
$edit_data = null;

if($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $edit_data = $conn->query("SELECT * FROM umkm WHERE id=$id")->fetch_assoc();
    if(!$edit_data) redirect('kelola-umkm.php');
}

// SAVE
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = clean($_POST['nama'] ?? '');
    $deskripsi = clean($_POST['deskripsi'] ?? '');
    $kontak    = clean($_POST['kontak'] ?? '');
    $id        = (int)($_POST['id'] ?? 0);

    if($nama && $deskripsi) {
        $foto = $_POST['existing_foto'] ?? '';
        if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if(in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $newname = 'umkm_' . time() . '.' . $ext;
                if(move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/umkm/'.$newname)) {
                    if($foto && file_exists('../uploads/umkm/'.$foto)) unlink('../uploads/umkm/'.$foto);
                    $foto = $newname;
                }
            }
        }
        if($id) {
            $stmt = $conn->prepare("UPDATE umkm SET nama=?, deskripsi=?, foto=?, kontak=? WHERE id=?");
            $stmt->bind_param("ssssi", $nama, $deskripsi, $foto, $kontak, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO umkm (nama, deskripsi, foto, kontak) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $deskripsi, $foto, $kontak);
        }
        $stmt->execute();
        redirect('kelola-umkm.php?msg=saved');
    }
}

$umkm = $conn->query("SELECT * FROM umkm ORDER BY tanggal DESC");
require_once 'layout.php';
?>

<?php if(isset($_GET['msg'])): ?>
<div class="bg-primary-50 border border-primary-200 text-primary-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle"></i>
    <?= $_GET['msg'] === 'saved' ? 'Data UMKM berhasil disimpan.' : 'Data UMKM berhasil dihapus.' ?>
</div>
<?php endif; ?>

<?php if($action === 'list'): ?>

<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-gray-500"><?= $umkm->num_rows ?> UMKM terdaftar</div>
    <a href="?action=add" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah UMKM
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php while($row = $umkm->fetch_assoc()): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-3 overflow-hidden">
            <?php if($row['foto'] && file_exists('../uploads/umkm/'.$row['foto'])): ?>
            <img src="../uploads/umkm/<?= $row['foto'] ?>" class="w-14 h-14 object-cover rounded-xl">
            <?php else: ?>
            <i class="fas fa-store text-primary-400 text-2xl"></i>
            <?php endif; ?>
        </div>
        <h3 class="font-bold text-gray-800 mb-1"><?= htmlspecialchars($row['nama']) ?></h3>
        <p class="text-sm text-gray-500 line-clamp-2 mb-2"><?= htmlspecialchars($row['deskripsi']) ?></p>
        <?php if($row['kontak']): ?>
        <p class="text-xs text-green-700 mb-3 flex items-center gap-1">
            <i class="fab fa-whatsapp"></i> <?= htmlspecialchars($row['kontak']) ?>
        </p>
        <?php endif; ?>
        <div class="flex gap-2">
            <a href="?action=edit&id=<?= $row['id'] ?>"
               class="flex-1 text-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus UMKM ini?')"
               class="flex-1 text-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                <i class="fas fa-trash"></i> Hapus
            </a>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php if($umkm->num_rows === 0): ?>
<div class="text-center py-16 text-gray-400">
    <i class="fas fa-store text-4xl mb-3"></i>
    <p>Belum ada UMKM. <a href="?action=add" class="text-primary-600 font-semibold">Tambah sekarang</a></p>
</div>
<?php endif; ?>

<?php else: // FORM ADD/EDIT ?>

<div class="mb-5">
    <a href="kelola-umkm.php" class="text-sm text-primary-600 hover:underline flex items-center gap-1 font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar UMKM
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-bold text-gray-800 mb-5 text-lg"><?= $edit_data ? 'Edit Data UMKM' : 'Tambah UMKM Baru' ?></h2>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-5">
        <?php if($edit_data): ?>
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <input type="hidden" name="existing_foto" value="<?= $edit_data['foto'] ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Usaha <span class="text-red-500">*</span></label>
            <input type="text" name="nama"
                   value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>"
                   placeholder="Nama UMKM atau usaha..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi <span class="text-red-500">*</span></label>
            <textarea name="deskripsi" rows="4"
                      placeholder="Jelaskan produk/jasa usaha ini..."
                      class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"
                      required><?= htmlspecialchars($edit_data['deskripsi'] ?? '') ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp <span class="text-gray-400 font-normal">(opsional)</span></label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                    <i class="fab fa-whatsapp text-green-500"></i>
                </span>
                <input type="text" name="kontak"
                       value="<?= htmlspecialchars($edit_data['kontak'] ?? '') ?>"
                       placeholder="08123456789"
                       class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto Produk <span class="text-gray-400 font-normal">(opsional)</span></label>
            <?php if(!empty($edit_data['foto']) && file_exists('../uploads/umkm/'.$edit_data['foto'])): ?>
            <div class="mb-3 flex items-center gap-3">
                <img src="../uploads/umkm/<?= $edit_data['foto'] ?>" class="h-20 w-20 rounded-xl border object-cover">
                <span class="text-xs text-gray-400">Foto saat ini. Upload baru untuk mengganti.</span>
            </div>
            <?php endif; ?>
            <input type="file" name="foto" accept="image/*"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-primary-100 file:text-primary-700 file:text-xs file:font-semibold">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-3 rounded-xl flex items-center gap-2 transition">
                <i class="fas fa-save"></i> <?= $edit_data ? 'Simpan Perubahan' : 'Simpan UMKM' ?>
            </button>
            <a href="kelola-umkm.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">Batal</a>
        </div>
    </form>
</div>

<?php endif; ?>
<?php require_once 'layout-footer.php'; ?>
