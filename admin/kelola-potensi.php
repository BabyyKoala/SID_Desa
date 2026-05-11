<?php
// admin/kelola-potensi.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/db.php';

// Fungsi pembersih karakter &amp; yang error berulang dari database
if (!function_exists('cleanText')) {
    function cleanText($text) {
        $text = str_replace(['&amp;amp;amp;amp;', '&amp;amp;amp;', '&amp;amp;', '&amp;'], '&', $text);
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }
}

// Cek Keamanan Admin
$is_logged_in = false;
if (function_exists('isAdmin')) {
    $is_logged_in = isAdmin();
} else {
    $is_logged_in = isset($_SESSION['admin_name']);
}

if(!$is_logged_in) {
    header("Location: login.php");
    exit;
}

$page_title = 'Kelola Potensi Desa';
$success = '';
$error = '';

// Tangkap pesan sukses dari URL (menghindari resubmission form)
if(isset($_GET['pesan'])) {
    if($_GET['pesan'] == 'tambah') $success = "Data potensi desa berhasil ditambahkan.";
    if($_GET['pesan'] == 'edit') $success = "Data potensi desa berhasil diperbarui.";
    if($_GET['pesan'] == 'hapus') $success = "Data potensi desa berhasil dihapus.";
}

// 1. PROSES HAPUS DATA
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("SELECT gambar FROM potensi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    
    // Path gambar disesuaikan menjadi ../uploads/potensi/
    if($res && $res['gambar'] && file_exists('../uploads/potensi/' . $res['gambar'])) {
        unlink('../uploads/potensi/' . $res['gambar']); 
    }
    
    $stmt = $conn->prepare("DELETE FROM potensi WHERE id = ?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()) {
        header("Location: kelola-potensi.php?pesan=hapus");
        exit;
    } else {
        $error = "Gagal menghapus data potensi.";
    }
}

// 2. PROSES TAMBAH & EDIT DATA
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = htmlspecialchars(trim($_POST['kategori']));
    $judul = htmlspecialchars(trim($_POST['judul']));
    $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
    $gambar = null;

    if(!$kategori || !$judul || !$deskripsi) {
        $error = "Kategori, Judul, dan Deskripsi wajib diisi.";
    } else {
        // Logika Upload Gambar Baru
        if(isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            
            if(!in_array($ext, $allowed)) {
                $error = "Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.";
            } elseif($_FILES['gambar']['size'] > 5 * 1024 * 1024) {
                $error = "Ukuran gambar maksimal 5MB.";
            } else {
                // Folder upload diarahkan spesifik ke uploads/potensi
                if (!is_dir('../uploads/potensi')) { mkdir('../uploads/potensi', 0777, true); }
                $gambar = 'potensi_' . time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['gambar']['tmp_name'], '../uploads/potensi/' . $gambar);
            }
        }

        if(!$error) {
            if(isset($_POST['tambah'])) {
                $stmt = $conn->prepare("INSERT INTO potensi (kategori, judul, deskripsi, gambar, tanggal) VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("ssss", $kategori, $judul, $deskripsi, $gambar);
                if($stmt->execute()) {
                    header("Location: kelola-potensi.php?pesan=tambah");
                    exit;
                } else {
                    $error = "Terjadi kesalahan sistem saat menambah data.";
                }
            } elseif(isset($_POST['simpan_edit'])) {
                $id_edit = (int)$_POST['id'];
                $gambar_lama = $_POST['gambar_lama'];
                
                if($gambar) {
                    // Hapus gambar lama dari folder potensi
                    if($gambar_lama && file_exists('../uploads/potensi/' . $gambar_lama)) {
                        unlink('../uploads/potensi/' . $gambar_lama);
                    }
                } else {
                    $gambar = $gambar_lama; 
                }

                $stmt = $conn->prepare("UPDATE potensi SET kategori=?, judul=?, deskripsi=?, gambar=? WHERE id=?");
                $stmt->bind_param("ssssi", $kategori, $judul, $deskripsi, $gambar, $id_edit);
                if($stmt->execute()) {
                    header("Location: kelola-potensi.php?pesan=edit");
                    exit;
                } else {
                    $error = "Terjadi kesalahan sistem saat memperbarui data.";
                }
            }
        }
    }
}

// Cek apakah sedang dalam mode Edit
$edit_mode = false;
$data_edit = null;
if(isset($_GET['edit'])) {
    $edit_mode = true;
    $id = (int)$_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM potensi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $data_edit = $stmt->get_result()->fetch_assoc();
}

// Ambil semua data potensi untuk tabel
$potensi_list = $conn->query("SELECT * FROM potensi ORDER BY tanggal DESC");

require_once 'layout.php'; 
?>

<?php if($success || $error): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if($success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?= $success ?>',
        confirmButtonColor: '#059669',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    }).then(() => {
        // Membersihkan URL dari parameter ?pesan=
        if(window.location.search.includes('pesan=')) {
            const url = new URL(window.location);
            url.searchParams.delete('pesan');
            window.history.replaceState(null, null, url.pathname + url.search);
        }
    });
    <?php endif; ?>
    
    <?php if($error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Kesalahan',
        text: '<?= $error ?>',
        confirmButtonColor: '#ef4444',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php if($edit_mode): ?>
<a href="kelola-potensi.php" class="text-primary-600 hover:text-primary-700 font-semibold mb-6 inline-flex items-center gap-2 transition">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Potensi
</a>
<?php endif; ?>

<?php if(!$edit_mode): ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800">Kelola Potensi Desa</h1>
        <p class="text-sm text-gray-500">Manajemen direktori potensi unggulan Desa Darmakradenan.</p>
    </div>
    <button onclick="toggleForm()" id="btn-tambah" class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition">
        <i class="fas fa-plus"></i> Tambah Data
    </button>
</div>
<?php endif; ?>

<div id="form-container" class="<?= $edit_mode ? 'block' : 'hidden' ?> bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h2 class="text-xl font-extrabold text-gray-800 mb-8">
        <?= $edit_mode ? 'Edit Data Potensi' : 'Tambah Data Potensi' ?>
    </h2>
    
    <form method="POST" action="kelola-potensi.php" enctype="multipart/form-data" class="space-y-6">
        <?php if($edit_mode): ?>
            <input type="hidden" name="id" value="<?= $data_edit['id'] ?>">
            <input type="hidden" name="gambar_lama" value="<?= $data_edit['gambar'] ?>">
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
            <?php $kategori_aktif = $edit_mode ? cleanText($data_edit['kategori']) : ''; ?>
            <select name="kategori" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Pariwisata Alam & Edukasi" <?= ($kategori_aktif == 'Pariwisata Alam & Edukasi') ? 'selected' : '' ?>>Pariwisata Alam & Edukasi</option>
                <option value="Sektor Pertanian & Perkebunan" <?= ($kategori_aktif == 'Sektor Pertanian & Perkebunan') ? 'selected' : '' ?>>Sektor Pertanian & Perkebunan</option>
                <option value="Ekonomi Kreatif & Kerajinan" <?= ($kategori_aktif == 'Ekonomi Kreatif & Kerajinan') ? 'selected' : '' ?>>Ekonomi Kreatif & Kerajinan</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Potensi <span class="text-red-500">*</span></label>
            <input type="text" name="judul" value="<?= $edit_mode ? htmlspecialchars(cleanText($data_edit['judul'])) : '' ?>" placeholder="Contoh: Waduk Sari Indah" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Lengkap <span class="text-red-500">*</span></label>
            <textarea name="deskripsi" rows="5" placeholder="Jelaskan secara detail mengenai potensi desa ini..." class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none" required><?= $edit_mode ? htmlspecialchars(cleanText($data_edit['deskripsi'])) : '' ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Foto Potensi <?= $edit_mode ? '<span class="text-gray-400 font-normal">(opsional)</span>' : '<span class="text-gray-400 font-normal">(opsional)</span>' ?>
            </label>
            
            <?php if($edit_mode && $data_edit['gambar'] && file_exists('../uploads/potensi/'.$data_edit['gambar'])): ?>
                <div class="mb-4 flex items-center gap-4">
                    <img src="../uploads/potensi/<?= $data_edit['gambar'] ?>" class="w-24 h-24 object-cover rounded-xl shadow-sm border border-gray-200">
                    <span class="text-sm text-gray-500">Foto saat ini. Upload baru untuk mengganti.</span>
                </div>
            <?php endif; ?>

            <input type="file" name="gambar" accept="image/jpeg, image/png, image/webp" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition cursor-pointer">
            <p class="text-xs text-gray-400 mt-2">Format JPG, PNG, WEBP. Maksimal 5MB.</p>
        </div>

        <div class="pt-4 flex gap-3">
            <button type="submit" name="<?= $edit_mode ? 'simpan_edit' : 'tambah' ?>" class="px-6 py-3 rounded-xl text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 shadow-sm flex items-center gap-2 transition">
                <i class="fas fa-save"></i> <?= $edit_mode ? 'Simpan Perubahan' : 'Simpan Data' ?>
            </button>
            <?php if($edit_mode): ?>
                <a href="kelola-potensi.php" class="px-6 py-3 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">Batal</a>
            <?php else: ?>
                <button type="button" onclick="toggleForm()" class="px-6 py-3 rounded-xl text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">Batal</button>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php if(!$edit_mode): ?>
<div id="table-container" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-sm">
                    <th class="py-4 px-5 font-semibold text-gray-600 w-12 text-center">No</th>
                    <th class="py-4 px-5 font-semibold text-gray-600 w-24">Gambar</th>
                    <th class="py-4 px-5 font-semibold text-gray-600 w-40">Kategori</th>
                    <th class="py-4 px-5 font-semibold text-gray-600">Judul & Deskripsi</th>
                    <th class="py-4 px-5 font-semibold text-gray-600 text-center w-44">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if($potensi_list && $potensi_list->num_rows > 0): ?>
                    <?php $no = 1; while($row = $potensi_list->fetch_assoc()): 
                        // Membersihkan text error &amp; agar rapi
                        $kategori_bersih = cleanText($row['kategori']);
                        $judul_bersih = cleanText($row['judul']);
                        $deskripsi_bersih = cleanText($row['deskripsi']);
                    ?>
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-5 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                        <td class="py-4 px-5">
                            <?php if($row['gambar'] && file_exists('../uploads/potensi/'.$row['gambar'])): ?>
                                <img src="../uploads/potensi/<?= $row['gambar'] ?>" class="w-16 h-12 object-cover rounded-lg shadow-sm border border-gray-100">
                            <?php else: ?>
                                <div class="w-16 h-12 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-200">
                                    <i class="fas fa-image text-gray-300"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-5 align-top">
                            <span class="bg-primary-50 text-primary-700 px-3 py-1.5 rounded-lg text-[10px] font-bold border border-primary-100 tracking-wide uppercase inline-block text-center leading-relaxed">
                                <?= htmlspecialchars($kategori_bersih) ?>
                            </span>
                        </td>
                        <td class="py-4 px-5 max-w-xs align-top">
                            <div class="font-bold text-gray-800 mb-1.5 text-base"><?= htmlspecialchars($judul_bersih) ?></div>
                            <div class="text-gray-500 text-xs line-clamp-2 leading-relaxed"><?= htmlspecialchars($deskripsi_bersih) ?></div>
                        </td>
                        <td class="py-4 px-5 text-center align-top">
                            <div class="flex items-center justify-center gap-2 mt-1">
                                <a href="?edit=<?= $row['id'] ?>" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition font-semibold text-sm" title="Edit Data">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="#" onclick="konfirmasiHapus('?delete=<?= $row['id'] ?>', '<?= addslashes($judul_bersih) ?>'); return false;" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-semibold text-sm" title="Hapus Data">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400">
                            <i class="fas fa-leaf text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada data potensi desa yang ditambahkan.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
function toggleForm() {
    const form = document.getElementById('form-container');
    const btnTambah = document.getElementById('btn-tambah');
    const table = document.getElementById('table-container');

    if(form.classList.contains('hidden')) {
        form.classList.remove('hidden');
        if(btnTambah) btnTambah.classList.add('hidden'); 
        if(table) table.classList.add('hidden'); 
    } else {
        form.classList.add('hidden');
        if(btnTambah) btnTambah.classList.remove('hidden'); 
        if(table) table.classList.remove('hidden'); 
    }
}
</script>

<?php require_once 'layout-footer.php'; ?>