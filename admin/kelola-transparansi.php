<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Transparansi';

$tab = $_GET['tab'] ?? 'apbdes';

// FITUR RBAC: Cek apakah user yang login adalah Kepala Desa (Read-Only Mode)
$is_read_only = isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'kepala_desa';

// ==========================================
// BACKEND SECURITY: Mencegah eksekusi form jika Read-Only
// ==========================================
if (!$is_read_only) {
    
    // ===== APBDes CRUD =====
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_apbdes'])) {
        $tahun    = (int)$_POST['tahun'];
        $kategori = in_array($_POST['kategori'], ['Pendapatan','Pengeluaran']) ? $_POST['kategori'] : 'Pendapatan';
        $uraian   = trim($_POST['uraian'] ?? '');
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
        header("Location: kelola-transparansi.php?tab=apbdes&msg=saved");
        exit;
    }

    if(isset($_GET['del_apbdes'])) {
        $id = (int)$_GET['del_apbdes'];
        $conn->query("DELETE FROM apbdes WHERE id=$id");
        header("Location: kelola-transparansi.php?tab=apbdes&msg=deleted");
        exit;
    }

    // ===== Program Desa CRUD =====
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_program'])) {
        $nama   = trim($_POST['nama_program'] ?? '');
        $desk   = trim($_POST['deskripsi'] ?? '');
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
        header("Location: kelola-transparansi.php?tab=program&msg=saved");
        exit;
    }

    if(isset($_GET['del_program'])) {
        $id = (int)$_GET['del_program'];
        $conn->query("DELETE FROM program_desa WHERE id=$id");
        header("Location: kelola-transparansi.php?tab=program&msg=deleted");
        exit;
    }
}
// ==========================================

// LOGIKA FILTER APBDES
$filter_tahun_apbdes = $_GET['filter_tahun'] ?? '';
$filter_kategori_apbdes = $_GET['filter_kategori'] ?? '';
$where_apbdes = "WHERE 1=1";

if ($filter_tahun_apbdes) {
    $where_apbdes .= " AND tahun = " . (int)$filter_tahun_apbdes;
}
if ($filter_kategori_apbdes) {
    $where_apbdes .= " AND kategori = '" . $conn->real_escape_string($filter_kategori_apbdes) . "'";
}

// Menarik data tahun untuk dropdown filter secara dinamis
$years_result = $conn->query("SELECT DISTINCT tahun FROM apbdes ORDER BY tahun DESC");
$available_years = [];
if ($years_result) {
    while($y = $years_result->fetch_assoc()) $available_years[] = $y['tahun'];
}

$apbdes  = $conn->query("SELECT * FROM apbdes $where_apbdes ORDER BY tahun DESC, kategori, id");

// LOGIKA FILTER PROGRAM DESA
$filter_status_program = $_GET['filter_status'] ?? '';
$where_program = "WHERE 1=1";

if ($filter_status_program) {
    $where_program .= " AND status = '" . $conn->real_escape_string($filter_status_program) . "'";
}

$program = $conn->query("SELECT * FROM program_desa $where_program ORDER BY FIELD(status,'Berjalan','Perencanaan','Selesai'), id DESC");

// Edit data (Hanya berjalan jika bukan Read-Only)
$edit_apbdes  = null;
$edit_program = null;
if(!$is_read_only) {
    if(isset($_GET['edit_apbdes'])) {
        $edit_apbdes = $conn->query("SELECT * FROM apbdes WHERE id=".(int)$_GET['edit_apbdes'])->fetch_assoc();
    }
    if(isset($_GET['edit_program'])) {
        $edit_program = $conn->query("SELECT * FROM program_desa WHERE id=".(int)$_GET['edit_program'])->fetch_assoc();
    }
}

require_once 'layout.php';
?>

<?php if(isset($_GET['msg'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let msg = '<?= $_GET['msg'] ?>';
    let pesan = '';
    if(msg === 'saved') pesan = 'Data berhasil disimpan!';
    else if(msg === 'deleted') pesan = 'Data berhasil dihapus!';

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

<div class="flex gap-3 mb-6">
    <a href="?tab=apbdes" class="px-5 py-2.5 rounded-xl text-sm font-semibold border transition
       <?= $tab === 'apbdes' ? 'bg-green-600 text-white border-green-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
        <i class="fas fa-coins mr-2"></i>APBDes
    </a>
    <a href="?tab=program" class="px-5 py-2.5 rounded-xl text-sm font-semibold border transition
       <?= $tab === 'program' ? 'bg-green-600 text-white border-green-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' ?>">
        <i class="fas fa-tasks mr-2"></i>Program Desa
    </a>
</div>

<?php if($tab === 'apbdes'): ?>
<div class="grid grid-cols-1 <?= !$is_read_only ? 'lg:grid-cols-3' : '' ?> gap-6">
    
    <?php if(!$is_read_only): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 h-fit">
        <h3 class="font-bold text-gray-800 mb-4">
            <?= $edit_apbdes ? 'Edit Data APBDes' : 'Tambah Data APBDes' ?>
        </h3>
        <form method="POST" action="?tab=apbdes" class="space-y-4">
            <?php if($edit_apbdes): ?>
            <input type="hidden" name="id" value="<?= $edit_apbdes['id'] ?>">
            <?php endif; ?>
            <input type="hidden" name="save_apbdes" value="1">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Tahun <span class="text-red-500">*</span></label>
                <input type="number" name="tahun" min="2020" max="2030"
                       value="<?= $edit_apbdes['tahun'] ?? date('Y') ?>"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                    <option value="Pendapatan" <?= ($edit_apbdes['kategori']??'')==='Pendapatan'?'selected':'' ?>>Pendapatan</option>
                    <option value="Pengeluaran" <?= ($edit_apbdes['kategori']??'')==='Pengeluaran'?'selected':'' ?>>Pengeluaran</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Uraian <span class="text-red-500">*</span></label>
                <input type="text" name="uraian"
                       value="<?= htmlspecialchars($edit_apbdes['uraian'] ?? '') ?>"
                       placeholder="cth: Dana Desa, Bidang Pembangunan..."
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" min="0"
                       value="<?= $edit_apbdes['jumlah'] ?? '' ?>"
                       placeholder="cth: 850000000"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <?php if($edit_apbdes): ?>
                <a href="?tab=apbdes" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="<?= !$is_read_only ? 'lg:col-span-2' : '' ?> flex flex-col gap-4">
        
        <!-- FITUR FILTER APBDES -->
        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <form method="GET" action="kelola-transparansi.php" class="flex flex-wrap gap-2 w-full items-center">
                <input type="hidden" name="tab" value="apbdes">
                
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-gray-400 text-sm ml-1"></i>
                    <select name="filter_tahun" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-green-500 focus:outline-none bg-gray-50 hover:bg-white cursor-pointer transition" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        <?php foreach($available_years as $y): ?>
                            <option value="<?= $y ?>" <?= $filter_tahun_apbdes == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <select name="filter_kategori" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-green-500 focus:outline-none bg-gray-50 hover:bg-white cursor-pointer transition" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <option value="Pendapatan" <?= $filter_kategori_apbdes === 'Pendapatan' ? 'selected' : '' ?>>Pendapatan</option>
                    <option value="Pengeluaran" <?= $filter_kategori_apbdes === 'Pengeluaran' ? 'selected' : '' ?>>Pengeluaran</option>
                </select>

                <?php if($filter_tahun_apbdes || $filter_kategori_apbdes): ?>
                    <a href="?tab=apbdes" class="text-xs font-semibold text-red-500 hover:text-red-700 transition ml-auto flex items-center gap-1 bg-red-50 px-3 py-2 rounded-lg">
                        <i class="fas fa-times-circle"></i> Reset Filter
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Tahun</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Uraian</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <?php if(!$is_read_only): ?><th class="px-4 py-3 text-center">Aksi</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while($row = $apbdes->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-700"><?= $row['tahun'] ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs font-semibold <?= $row['kategori']==='Pendapatan'?'bg-green-100 text-green-700':'bg-orange-100 text-orange-700' ?>">
                                <?= $row['kategori'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs"><?= htmlspecialchars($row['uraian']) ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800 text-xs whitespace-nowrap"><?= isset($row['jumlah']) ? 'Rp '.number_format($row['jumlah'], 0, ',', '.') : '' ?></td>
                        
                        <?php if(!$is_read_only): ?>
                        <td class="px-4 py-3 text-center">
                            <div class="flex gap-2 justify-center">
                                <a href="?tab=apbdes&edit_apbdes=<?= $row['id'] ?>&filter_tahun=<?= $filter_tahun_apbdes ?>&filter_kategori=<?= $filter_kategori_apbdes ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition"><i class="fas fa-edit"></i></a>
                                <a href="#" onclick="konfirmasiHapus('?tab=apbdes&del_apbdes=<?= $row['id'] ?>', 'Data APBDes: <?= addslashes(htmlspecialchars($row['uraian'])) ?>'); return false;" class="bg-red-50 text-red-600 hover:bg-red-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endwhile; ?>
                    <?php if($apbdes->num_rows === 0): ?>
                    <tr>
                        <td colspan="<?= !$is_read_only ? '5' : '4' ?>" class="px-4 py-8 text-center text-gray-400 text-sm">
                            <i class="fas fa-box-open text-3xl mb-2 text-gray-300 block"></i>
                            Belum ada data APBDes yang sesuai dengan pencarian Anda.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php elseif($tab === 'program'): ?>
<div class="grid grid-cols-1 <?= !$is_read_only ? 'lg:grid-cols-3' : '' ?> gap-6">
    
    <?php if(!$is_read_only): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 h-fit">
        <h3 class="font-bold text-gray-800 mb-4"><?= $edit_program ? 'Edit Program' : 'Tambah Program' ?></h3>
        <form method="POST" action="?tab=program" class="space-y-4">
            <?php if($edit_program): ?>
            <input type="hidden" name="id" value="<?= $edit_program['id'] ?>">
            <?php endif; ?>
            <input type="hidden" name="save_program" value="1">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Program <span class="text-red-500">*</span></label>
                <input type="text" name="nama_program"
                       value="<?= htmlspecialchars($edit_program['nama_program'] ?? '') ?>"
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"><?= htmlspecialchars($edit_program['deskripsi'] ?? '') ?></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                    <option value="Perencanaan" <?= ($edit_program['status']??'')==='Perencanaan'?'selected':'' ?>>Perencanaan</option>
                    <option value="Berjalan"    <?= ($edit_program['status']??'')==='Berjalan'?'selected':'' ?>>Berjalan</option>
                    <option value="Selesai"     <?= ($edit_program['status']??'')==='Selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <?php if($edit_program): ?>
                <a href="?tab=program" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="<?= !$is_read_only ? 'lg:col-span-2' : '' ?> flex flex-col gap-4">
        
        <!-- FITUR FILTER PROGRAM -->
        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <form method="GET" action="kelola-transparansi.php" class="flex gap-2 w-full items-center">
                <input type="hidden" name="tab" value="program">
                <i class="fas fa-filter text-gray-400 text-sm ml-1"></i>
                <select name="filter_status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-green-500 focus:outline-none bg-gray-50 hover:bg-white cursor-pointer transition" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Perencanaan" <?= $filter_status_program === 'Perencanaan' ? 'selected' : '' ?>>Perencanaan</option>
                    <option value="Berjalan" <?= $filter_status_program === 'Berjalan' ? 'selected' : '' ?>>Berjalan</option>
                    <option value="Selesai" <?= $filter_status_program === 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
                
                <?php if($filter_status_program): ?>
                    <a href="?tab=program" class="text-xs font-semibold text-red-500 hover:text-red-700 transition ml-auto flex items-center gap-1 bg-red-50 px-3 py-2 rounded-lg">
                        <i class="fas fa-times-circle"></i> Reset Filter
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="space-y-3">
            <?php while($row = $program->fetch_assoc()): 
                $badge = [
                    'Perencanaan' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'Berjalan'    => 'bg-blue-100 text-blue-700 border-blue-200',
                    'Selesai'     => 'bg-green-100 text-green-700 border-green-200'
                ];
                $b = $badge[$row['status']] ?? 'bg-gray-100 text-gray-600 border-gray-200';
            ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-start justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($row['nama_program']) ?></span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $b ?>"><?= $row['status'] ?></span>
                    </div>
                    <?php if($row['deskripsi']): ?>
                    <p class="text-xs text-gray-500 leading-relaxed"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    <?php endif; ?>
                </div>
                
                <?php if(!$is_read_only): ?>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="?tab=program&edit_program=<?= $row['id'] ?>&filter_status=<?= $filter_status_program ?>" class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition"><i class="fas fa-edit"></i></a>
                    <a href="#" onclick="konfirmasiHapus('?tab=program&del_program=<?= $row['id'] ?>', 'Program: <?= addslashes(htmlspecialchars($row['nama_program'])) ?>'); return false;" class="bg-red-50 text-red-600 hover:bg-red-100 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition"><i class="fas fa-trash"></i></a>
                </div>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
            
            <?php if($program->num_rows === 0): ?>
            <div class="text-center py-16 text-gray-400 bg-white rounded-xl border border-gray-100 border-dashed">
                <i class="fas fa-tasks text-3xl mb-3 text-gray-300"></i>
                <p class="text-sm">Belum ada program desa yang sesuai dengan pencarian Anda.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'layout-footer.php'; ?>