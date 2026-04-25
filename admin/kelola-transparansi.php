<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Transparansi';

$tab = $_GET['tab'] ?? 'apbdes';

// ===== APBDes CRUD =====
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_apbdes'])) {
    $tahun    = (int)$_POST['tahun'];
    $kategori = in_array($_POST['kategori'], ['Pendapatan','Pengeluaran']) ? $_POST['kategori'] : 'Pendapatan';
    $uraian   = clean($_POST['uraian'] ?? '');
    $jumlah   = (int)str_replace(['.',',',' '], '', $_POST['jumlah'] ?? 0);
    $id       = (int)($_POST['id'] ?? 0);

    if($uraian && $jumlah && $tahun) {
        if($id) {
            $stmt = $conn->prepare("UPDATE apbdes SET tahun=?, kategori=?, uraian=?, jumlah=? WHERE id=?");
            $stmt->bind_param("issii", $tahun, $kategori, $uraian, $jumlah, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO apbdes (tahun, kategori, uraian, jumlah) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issi", $tahun, $kategori, $uraian, $jumlah);
        }
        $stmt->execute();
    }
    redirect("kelola-transparansi.php?tab=apbdes&msg=saved");
}

if(isset($_GET['del_apbdes'])) {
    $id = (int)$_GET['del_apbdes'];
    $conn->query("DELETE FROM apbdes WHERE id=$id");
    redirect("kelola-transparansi.php?tab=apbdes&msg=deleted");
}

// ===== Program Desa CRUD =====
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_program'])) {
    $nama   = clean($_POST['nama_program'] ?? '');
    $desk   = clean($_POST['deskripsi'] ?? '');
    $status = in_array($_POST['status'], ['Perencanaan','Berjalan','Selesai']) ? $_POST['status'] : 'Perencanaan';
    $id     = (int)($_POST['id'] ?? 0);

    if($nama) {
        if($id) {
            $stmt = $conn->prepare("UPDATE program_desa SET nama_program=?, deskripsi=?, status=? WHERE id=?");
            $stmt->bind_param("sssi", $nama, $desk, $status, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO program_desa (nama_program, deskripsi, status) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $desk, $status);
        }
        $stmt->execute();
    }
    redirect("kelola-transparansi.php?tab=program&msg=saved");
}

if(isset($_GET['del_program'])) {
    $id = (int)$_GET['del_program'];
    $conn->query("DELETE FROM program_desa WHERE id=$id");
    redirect("kelola-transparansi.php?tab=program&msg=deleted");
}

$apbdes  = $conn->query("SELECT * FROM apbdes ORDER BY tahun DESC, kategori, id");
$program = $conn->query("SELECT * FROM program_desa ORDER BY FIELD(status,'Berjalan','Perencanaan','Selesai'), id DESC");

// Edit data
$edit_apbdes  = null;
$edit_program = null;
if(isset($_GET['edit_apbdes'])) {
    $edit_apbdes = $conn->query("SELECT * FROM apbdes WHERE id=".(int)$_GET['edit_apbdes'])->fetch_assoc();
}
if(isset($_GET['edit_program'])) {
    $edit_program = $conn->query("SELECT * FROM program_desa WHERE id=".(int)$_GET['edit_program'])->fetch_assoc();
}

require_once 'layout.php';
?>

<?php if(isset($_GET['msg'])): ?>
<div class="bg-primary-50 border border-primary-200 text-primary-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
    <i class="fas fa-check-circle"></i>
    <?= $_GET['msg'] === 'saved' ? 'Data berhasil disimpan.' : 'Data berhasil dihapus.' ?>
</div>
<?php endif; ?>

<!-- Tabs -->
<div class="flex gap-3 mb-6">
    <a href="?tab=apbdes" class="px-5 py-2.5 rounded-xl text-sm font-semibold border transition
       <?= $tab === 'apbdes' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
        <i class="fas fa-coins mr-2"></i>APBDes
    </a>
    <a href="?tab=program" class="px-5 py-2.5 rounded-xl text-sm font-semibold border transition
       <?= $tab === 'program' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
        <i class="fas fa-tasks mr-2"></i>Program Desa
    </a>
</div>

<?php if($tab === 'apbdes'): ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form APBDes -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 mb-4">
            <?= $edit_apbdes ? 'Edit Data APBDes' : 'Tambah Data APBDes' ?>
        </h3>
        <form method="POST" class="space-y-4">
            <?php if($edit_apbdes): ?>
            <input type="hidden" name="id" value="<?= $edit_apbdes['id'] ?>">
            <?php endif; ?>
            <input type="hidden" name="save_apbdes" value="1">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun <span class="text-red-500">*</span></label>
                <input type="number" name="tahun" min="2020" max="2030"
                       value="<?= $edit_apbdes['tahun'] ?? date('Y') ?>"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                    <option value="Pendapatan" <?= ($edit_apbdes['kategori']??'')==='Pendapatan'?'selected':'' ?>>Pendapatan</option>
                    <option value="Pengeluaran" <?= ($edit_apbdes['kategori']??'')==='Pengeluaran'?'selected':'' ?>>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Uraian <span class="text-red-500">*</span></label>
                <input type="text" name="uraian"
                       value="<?= htmlspecialchars($edit_apbdes['uraian'] ?? '') ?>"
                       placeholder="cth: Dana Desa, Bidang Pembangunan..."
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" min="0"
                       value="<?= $edit_apbdes['jumlah'] ?? '' ?>"
                       placeholder="cth: 850000000"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <?php if($edit_apbdes): ?>
                <a href="?tab=apbdes" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel APBDes -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Tahun</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Uraian</th>
                    <th class="px-4 py-3 text-right">Jumlah</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php while($row = $apbdes->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold text-gray-700"><?= $row['tahun'] ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold <?= $row['kategori']==='Pendapatan'?'bg-primary-100 text-primary-700':'bg-orange-100 text-orange-700' ?>">
                            <?= $row['kategori'] ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-xs"><?= htmlspecialchars($row['uraian']) ?></td>
                    <td class="px-4 py-3 text-right font-semibold text-gray-800 text-xs whitespace-nowrap"><?= formatRupiah($row['jumlah']) ?></td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex gap-1 justify-center">
                            <a href="?tab=apbdes&edit_apbdes=<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 p-1"><i class="fas fa-edit text-xs"></i></a>
                            <a href="?tab=apbdes&del_apbdes=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-500 hover:text-red-700 p-1"><i class="fas fa-trash text-xs"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif($tab === 'program'): ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Program -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-bold text-gray-800 mb-4"><?= $edit_program ? 'Edit Program' : 'Tambah Program' ?></h3>
        <form method="POST" class="space-y-4">
            <?php if($edit_program): ?>
            <input type="hidden" name="id" value="<?= $edit_program['id'] ?>">
            <?php endif; ?>
            <input type="hidden" name="save_program" value="1">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Program <span class="text-red-500">*</span></label>
                <input type="text" name="nama_program"
                       value="<?= htmlspecialchars($edit_program['nama_program'] ?? '') ?>"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"><?= htmlspecialchars($edit_program['deskripsi'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 bg-white">
                    <option value="Perencanaan" <?= ($edit_program['status']??'')==='Perencanaan'?'selected':'' ?>>Perencanaan</option>
                    <option value="Berjalan"    <?= ($edit_program['status']??'')==='Berjalan'?'selected':'' ?>>Berjalan</option>
                    <option value="Selesai"     <?= ($edit_program['status']??'')==='Selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <?php if($edit_program): ?>
                <a href="?tab=program" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- List Program -->
    <div class="lg:col-span-2 space-y-3">
        <?php while($row = $program->fetch_assoc()): 
            $badge = ['Perencanaan'=>'badge-perencanaan','Berjalan'=>'badge-berjalan','Selesai'=>'badge-selesai'];
            $b = $badge[$row['status']] ?? 'bg-gray-100 text-gray-600';
        ?>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($row['nama_program']) ?></span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $b ?>"><?= $row['status'] ?></span>
                </div>
                <?php if($row['deskripsi']): ?>
                <p class="text-xs text-gray-500"><?= htmlspecialchars($row['deskripsi']) ?></p>
                <?php endif; ?>
            </div>
            <div class="flex gap-2 flex-shrink-0">
                <a href="?tab=program&edit_program=<?= $row['id'] ?>" class="text-blue-500 hover:text-blue-700 p-1.5"><i class="fas fa-edit text-xs"></i></a>
                <a href="?tab=program&del_program=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="text-red-400 hover:text-red-600 p-1.5"><i class="fas fa-trash text-xs"></i></a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php require_once 'layout-footer.php'; ?>
