<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Perangkat Desa';

// DELETE
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM lembaga WHERE id=$id");
    redirect('kelola-lembaga.php?msg=deleted');
}

$action = $_GET['action'] ?? 'list';
$edit_data = null;

if($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $edit_data = $conn->query("SELECT * FROM lembaga WHERE id=$id")->fetch_assoc();
    if(!$edit_data) redirect('kelola-lembaga.php');
}

// SAVE
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = clean($_POST['nama'] ?? '');
    $jabatan = clean($_POST['jabatan'] ?? '');
    $urutan  = (int)($_POST['urutan'] ?? 0);
    $id      = (int)($_POST['id'] ?? 0);

    if($nama && $jabatan) {
        $foto = $_POST['existing_foto'] ?? '';
        if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if(in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                $newname = 'lembaga_' . time() . '.' . $ext;
                if(move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/'.$newname)) {
                    $foto = $newname;
                }
            }
        }
        if($id) {
            $stmt = $conn->prepare("UPDATE lembaga SET nama=?, jabatan=?, foto=?, urutan=? WHERE id=?");
            $stmt->bind_param("sssii", $nama, $jabatan, $foto, $urutan, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO lembaga (nama, jabatan, foto, urutan) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $nama, $jabatan, $foto, $urutan);
        }
        $stmt->execute();
        redirect('kelola-lembaga.php?msg=saved');
    }
}

$lembaga = $conn->query("SELECT * FROM lembaga ORDER BY urutan ASC");
require_once 'layout.php';
?>

<?php if(isset($_GET['msg'])): ?>
<div class="bg-primary-50 border border-primary-200 text-primary-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle"></i>
    <?= $_GET['msg'] === 'saved' ? 'Data perangkat berhasil disimpan.' : 'Data perangkat berhasil dihapus.' ?>
</div>
<?php endif; ?>

<?php if($action === 'list'): ?>

<div class="flex items-center justify-between mb-6">
    <div class="text-sm text-gray-500"><?= $lembaga->num_rows ?> perangkat terdaftar</div>
    <a href="?action=add" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah Perangkat
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
            <tr>
                <th class="px-5 py-3 text-left">No</th>
                <th class="px-5 py-3 text-left">Nama</th>
                <th class="px-5 py-3 text-left">Jabatan</th>
                <th class="px-5 py-3 text-center">Urutan</th>
                <th class="px-5 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php $no = 1; while($row = $lembaga->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 text-gray-400"><?= $no++ ?></td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                            <?php if($row['foto'] && file_exists('../uploads/'.$row['foto'])): ?>
                            <img src="../uploads/<?= $row['foto'] ?>" class="w-9 h-9 object-cover rounded-full">
                            <?php else: ?>
                            <i class="fas fa-user text-primary-400 text-sm"></i>
                            <?php endif; ?>
                        </div>
                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></span>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($row['jabatan']) ?></td>
                <td class="px-5 py-3 text-center text-gray-500"><?= $row['urutan'] ?></td>
                <td class="px-5 py-3 text-center">
                    <div class="flex gap-2 justify-center">
                        <a href="?action=edit&id=<?= $row['id'] ?>"
                           class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus perangkat ini?')"
                           class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1 rounded-lg text-xs font-semibold transition">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php else: ?>

<div class="mb-5">
    <a href="kelola-lembaga.php" class="text-sm text-primary-600 hover:underline flex items-center gap-1 font-semibold">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-lg">
    <h2 class="font-bold text-gray-800 mb-5 text-lg"><?= $edit_data ? 'Edit Perangkat Desa' : 'Tambah Perangkat Desa' ?></h2>
    
    <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php if($edit_data): ?>
        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <input type="hidden" name="existing_foto" value="<?= $edit_data['foto'] ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama"
                   value="<?= htmlspecialchars($edit_data['nama'] ?? '') ?>"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jabatan <span class="text-red-500">*</span></label>
            <input type="text" name="jabatan"
                   value="<?= htmlspecialchars($edit_data['jabatan'] ?? '') ?>"
                   placeholder="cth: Kepala Desa, Sekretaris Desa..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Urutan Tampil</label>
            <input type="number" name="urutan" min="0"
                   value="<?= $edit_data['urutan'] ?? 0 ?>"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
            <p class="text-xs text-gray-400 mt-1">Angka kecil tampil lebih awal. Kepala Desa = 1</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Foto <span class="text-gray-400 font-normal">(opsional)</span></label>
            <?php if(!empty($edit_data['foto']) && file_exists('../uploads/'.$edit_data['foto'])): ?>
            <img src="../uploads/<?= $edit_data['foto'] ?>" class="h-16 w-16 rounded-full border object-cover mb-2">
            <?php endif; ?>
            <input type="file" name="foto" accept="image/*"
                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-primary-100 file:text-primary-700 file:text-xs file:font-semibold">
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-6 py-3 rounded-xl flex items-center gap-2 transition">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="kelola-lembaga.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl transition">Batal</a>
        </div>
    </form>
</div>

<?php endif; ?>
<?php require_once 'layout-footer.php'; ?>
