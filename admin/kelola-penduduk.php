<?php
session_start();
require_once '../config/db.php';
$page_title = 'Kelola Penduduk';

$success = '';
$error = '';

// Proses Import CSV
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import'])) {
    if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file_csv']['tmp_name'];
        $file_name = $_FILES['file_csv']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if ($file_ext === 'csv') {
            // Buka file CSV
            if (($handle = fopen($file_tmp, "r")) !== FALSE) {
                $row = 0;
                $successCount = 0;
                
                // Mulai transaksi untuk mempercepat proses insert ribuan data
                $conn->begin_transaction();

                // Gunakan UPSERT (Insert, jika NIK sudah ada maka Update)
                $stmt = $conn->prepare("INSERT INTO penduduk (nik, nama, jenis_kelamin, alamat) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE nama=?, jenis_kelamin=?, alamat=?");
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $row++;
                    // Lewati baris pertama (Header kolom)
                    if ($row == 1) continue; 
                    
                    // Ambil data dari kolom CSV (sesuaikan urutan: NIK, Nama, JK, Alamat)
                    $nik = trim($data[0] ?? '');
                    $nama = trim($data[1] ?? '');
                    $jk = trim(strtoupper($data[2] ?? '')); // L atau P
                    $alamat = trim($data[3] ?? '');

                    // Validasi minimal ada NIK 16 digit dan Nama
                    if (strlen($nik) === 16 && !empty($nama)) {
                        $stmt->bind_param("sssssss", $nik, $nama, $jk, $alamat, $nama, $jk, $alamat);
                        $stmt->execute();
                        $successCount++;
                    }
                }
                
                fclose($handle);
                $conn->commit(); // Simpan semua perubahan ke database
                
                header("Location: kelola-penduduk.php?msg=success&count=" . $successCount);
                exit;
            } else {
                $error = "Gagal membaca file CSV.";
            }
        } else {
            $error = "Format file tidak didukung. Harap unggah file berektensi .csv";
        }
    } else {
        $error = "Terjadi kesalahan saat mengunggah file.";
    }
}

// Proses Hapus Semua Data (Opsional, untuk reset)
if(isset($_GET['action']) && $_GET['action'] == 'reset') {
    $conn->query("TRUNCATE TABLE penduduk");
    header("Location: kelola-penduduk.php?msg=reset");
    exit;
}

// Ambil data penduduk untuk ditampilkan (Limit 500 untuk menghindari lag jika data ribuan)
$penduduk = $conn->query("SELECT * FROM penduduk ORDER BY nama ASC LIMIT 500");
$total_penduduk = $conn->query("SELECT COUNT(*) as total FROM penduduk")->fetch_assoc()['total'];

require_once 'layout.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const count = urlParams.get('count');

    if(msg === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Import Berhasil!',
            text: `${count} data penduduk berhasil disimpan/diperbarui.`,
            confirmButtonColor: '#059669',
            customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    } else if(msg === 'reset') {
        Swal.fire({
            icon: 'success',
            title: 'Data Dikosongkan!',
            text: 'Seluruh data penduduk berhasil dihapus.',
            confirmButtonColor: '#059669',
            customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    }

    <?php if($error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '<?= $error ?>',
        confirmButtonColor: '#ef4444',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>
});

function konfirmasiReset() {
    Swal.fire({
        title: 'Reset Semua Data?',
        text: "Anda yakin ingin menghapus SELURUH data penduduk? Tindakan ini tidak bisa dibatalkan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-sm',
            cancelButton: 'rounded-xl px-4 py-2 font-bold shadow-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?action=reset';
        }
    });
}
</script>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800">Master Data Penduduk</h1>
        <p class="text-sm text-gray-500">Total terdaftar: <span class="font-bold text-primary-600"><?= number_format($total_penduduk, 0, ',', '.') ?> jiwa</span></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 h-fit">
        <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3 flex items-center gap-2">
            <i class="fas fa-file-import text-primary-500"></i> Import Data Excel/CSV
        </h3>
        
        <div class="bg-blue-50 text-blue-800 text-xs rounded-xl p-4 mb-5 leading-relaxed border border-blue-100">
            <strong>Aturan File CSV:</strong><br>
            Baris pertama (Header) akan diabaikan. Pastikan urutan kolom di Excel dari kiri ke kanan adalah:<br>
            <ol class="list-decimal ml-4 mt-1 space-y-1 font-medium">
                <li>NIK (16 digit teks)</li>
                <li>Nama Lengkap</li>
                <li>Jenis Kelamin (Isi L atau P)</li>
                <li>Alamat Lengkap</li>
            </ol>
            <a href="data:text/csv;charset=utf-8,NIK,Nama,Jenis_Kelamin,Alamat%0A3302011234560001,Budi Santoso,L,RT 01 RW 01 Darmakradenan%0A" download="template_penduduk.csv" class="inline-block mt-2 text-blue-600 hover:text-blue-800 font-bold underline">Download Template CSV</a>
        </div>

        <form method="POST" action="kelola-penduduk.php" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih File (.csv)</label>
                <input type="file" name="file_csv" accept=".csv" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition cursor-pointer">
            </div>
            <button type="submit" name="import" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition shadow-sm">
                <i class="fas fa-cloud-upload-alt"></i> Upload & Import
            </button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fas fa-users text-gray-400 mr-2"></i> Preview Data Penduduk</h3>
            <button onclick="konfirmasiReset()" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 font-semibold px-3 py-1.5 rounded-lg transition border border-red-100">
                <i class="fas fa-trash-alt mr-1"></i> Kosongkan Data
            </button>
        </div>
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white sticky top-0 shadow-sm">
                    <tr class="text-xs text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-5 py-3">NIK</th>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3 text-center">L/P</th>
                        <th class="px-5 py-3">Alamat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if($total_penduduk == 0): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-12 text-center text-gray-400">
                            <i class="fas fa-folder-open text-3xl mb-3 text-gray-300 block"></i>
                            Data penduduk masih kosong. Silakan import melalui form di samping.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php while($row = $penduduk->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3 font-mono text-xs text-gray-600 font-semibold"><?= htmlspecialchars($row['nik']) ?></td>
                            <td class="px-5 py-3 font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-bold <?= $row['jenis_kelamin']=='L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' ?>">
                                    <?= $row['jenis_kelamin'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs"><?= htmlspecialchars($row['alamat']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($total_penduduk > 500): ?>
        <div class="p-3 text-center text-xs text-gray-500 border-t border-gray-100 bg-gray-50">
            *Hanya menampilkan 500 data pertama untuk menghemat memori.
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layout-footer.php'; ?>