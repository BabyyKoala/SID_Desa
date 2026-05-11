<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Pengguna Sistem';

// KEAMANAN TINGKAT TINGGI: Hanya Super Admin yang boleh masuk halaman ini!
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'super_admin') {
    echo "<script>alert('PELANGGARAN HAK AKSES! Anda tidak diizinkan masuk ke halaman ini.'); window.location='dashboard.php';</script>";
    exit;
}

// PROSES TAMBAH / EDIT PENGGUNA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id = $_POST['id'] ?? '';
    $nama = trim($_POST['nama_lengkap'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? 'staf';
    $password = $_POST['password'] ?? '';

    if ($id) {
        // UPDATE DATA
        if (!empty($password)) {
            // Jika password diisi, update beserta password (Hash baru)
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, username=?, role=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $nama, $username, $role, $hash, $id);
        } else {
            // Jika password dikosongkan, biarkan password lama
            $stmt = $conn->prepare("UPDATE users SET nama_lengkap=?, username=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $nama, $username, $role, $id);
        }
        $stmt->execute();
        header("Location: kelola-admin.php?msg=updated");
        exit;
    } else {
        // INSERT DATA BARU
        if(empty($password)) $password = 'Desa2026!'; // Password default jika lupa diisi
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, role, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $username, $role, $hash);
        $stmt->execute();
        header("Location: kelola-admin.php?msg=added");
        exit;
    }
}

// PROSES HAPUS PENGGUNA
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Proteksi: Super Admin tidak boleh menghapus dirinya sendiri!
    if ($id === $_SESSION['admin_id']) {
        header("Location: kelola-admin.php?msg=error_self");
        exit;
    }
    
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: kelola-admin.php?msg=deleted");
    exit;
}

// AMBIL DATA PENGGUNA
$users = $conn->query("SELECT * FROM users ORDER BY role ASC, created_at DESC");

require_once 'layout.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');

    if(msg === 'added') {
        Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Pengguna baru berhasil ditambahkan.', confirmButtonColor: '#059669', customClass: { confirmButton: 'rounded-xl px-6 py-2' } }).then(() => { window.history.replaceState(null, null, window.location.pathname); });
    } else if(msg === 'updated') {
        Swal.fire({ icon: 'success', title: 'Diperbarui', text: 'Data pengguna berhasil diubah.', confirmButtonColor: '#059669', customClass: { confirmButton: 'rounded-xl px-6 py-2' } }).then(() => { window.history.replaceState(null, null, window.location.pathname); });
    } else if(msg === 'deleted') {
        Swal.fire({ icon: 'success', title: 'Dihapus', text: 'Pengguna berhasil dihapus dari sistem.', confirmButtonColor: '#059669', customClass: { confirmButton: 'rounded-xl px-6 py-2' } }).then(() => { window.history.replaceState(null, null, window.location.pathname); });
    } else if(msg === 'error_self') {
        Swal.fire({ icon: 'error', title: 'Akses Ditolak', text: 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif!', confirmButtonColor: '#ef4444', customClass: { confirmButton: 'rounded-xl px-6 py-2' } }).then(() => { window.history.replaceState(null, null, window.location.pathname); });
    }
});

function konfirmasiHapus(url, nama) {
    Swal.fire({
        title: 'Hapus Akses?',
        text: `Anda yakin ingin mencabut hak akses pengguna "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        customClass: { confirmButton: 'rounded-xl px-4 py-2', cancelButton: 'rounded-xl px-4 py-2' }
    }).then((result) => {
        if (result.isConfirmed) window.location.href = url;
    });
}

function editUser(id, nama, username, role) {
    document.getElementById('form_title').innerText = 'Edit Pengguna';
    document.getElementById('user_id').value = id;
    document.getElementById('nama_lengkap').value = nama;
    document.getElementById('username').value = username;
    document.getElementById('role').value = role;
    document.getElementById('password').required = false;
    document.getElementById('password_note').innerText = '*Kosongkan jika tidak ingin mengubah password';
    document.getElementById('btn_submit').innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
    document.getElementById('btn_cancel').classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form_title').innerText = 'Tambah Pengguna Baru';
    document.getElementById('user_id').value = '';
    document.getElementById('form_user').reset();
    document.getElementById('password').required = true;
    document.getElementById('password_note').innerText = '*Password wajib diisi untuk pengguna baru';
    document.getElementById('btn_submit').innerHTML = '<i class="fas fa-plus"></i> Tambah Pengguna';
    document.getElementById('btn_cancel').classList.add('hidden');
}
</script>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800">Manajemen Hak Akses</h1>
        <p class="text-sm text-gray-500">Kelola akun admin, staf, dan kepala desa</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit sticky top-24">
        <h3 id="form_title" class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3 flex items-center gap-2">
            <i class="fas fa-user-plus text-primary-500"></i> Tambah Pengguna Baru
        </h3>
        
        <form id="form_user" method="POST" action="kelola-admin.php" class="space-y-4">
            <input type="hidden" name="id" id="user_id">
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" required placeholder="Misal: Budi Santoso"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Username Login</label>
                <input type="text" name="username" id="username" required placeholder="Misal: staf_budi"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Hak Akses (Role)</label>
                <select name="role" id="role" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                    <option value="staf">Staf Pelayanan</option>
                    <option value="kepala_desa">Kepala Desa</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" id="password" required placeholder="Ketik password rahasia..."
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <p id="password_note" class="text-[10px] text-gray-400 mt-1">*Password wajib diisi untuk pengguna baru</p>
            </div>
            
            <div class="pt-2 flex gap-2">
                <button type="submit" name="simpan" id="btn_submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl transition shadow-sm text-sm">
                    <i class="fas fa-plus"></i> Tambah Pengguna
                </button>
                <button type="button" id="btn_cancel" onclick="resetForm()" class="hidden bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2.5 px-4 rounded-xl transition shadow-sm text-sm">
                    Batal
                </button>
            </div>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-users-cog text-gray-400 mr-2"></i> Daftar Pengguna Terdaftar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white">
                    <tr class="text-xs text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-5 py-3">Nama / Username</th>
                        <th class="px-5 py-3">Role Akses</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php while($row = $users->fetch_assoc()): 
                        // Visualisasi Badge Role
                        $bgRole = $row['role'] == 'super_admin' ? 'bg-purple-100 text-purple-700' : ($row['role'] == 'kepala_desa' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700');
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <div class="font-bold text-gray-800"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                            <div class="text-xs text-gray-400 font-mono mt-0.5"><i class="fas fa-user-circle mr-1"></i><?= htmlspecialchars($row['username']) ?></div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $bgRole ?>">
                                <?= str_replace('_', ' ', $row['role']) ?>
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button onclick="editUser(<?= $row['id'] ?>, '<?= addslashes($row['nama_lengkap']) ?>', '<?= addslashes($row['username']) ?>', '<?= $row['role'] ?>')" 
                                    class="bg-blue-50 text-blue-600 hover:bg-blue-100 p-2 rounded-lg transition mr-1" title="Edit Pengguna">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <?php if($row['id'] !== $_SESSION['admin_id']): // Sembunyikan tombol hapus untuk diri sendiri ?>
                            <button onclick="konfirmasiHapus('?delete=<?= $row['id'] ?>', '<?= addslashes($row['nama_lengkap']) ?>')" 
                                    class="bg-red-50 text-red-500 hover:bg-red-100 p-2 rounded-lg transition" title="Hapus Pengguna">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <?php else: ?>
                            <span class="text-[10px] text-gray-400 italic block mt-1">(Anda)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'layout-footer.php'; ?>