<?php
require_once '../config/db.php';

// Fallback pelindung jika fungsi clean belum ada
if (!function_exists('clean')) {
    function clean($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}

// Fallback untuk nomor WhatsApp
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', '6282134655359'); 
}

$page_title = 'Pengaduan Masyarakat';
$success = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = clean($_POST['nama'] ?? '');
    $isi  = clean($_POST['isi'] ?? '');
    $foto = null;

    if(!$nama || !$isi) {
        $error = 'Nama dan isi laporan wajib diisi.';
    } else {
        // Logika Upload Foto
        if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            
            if(!in_array($ext, $allowed)) {
                $error = 'Format foto tidak didukung. Gunakan JPG, PNG, atau WEBP.';
            } elseif($_FILES['foto']['size'] > 5 * 1024 * 1024) {
                $error = 'Ukuran foto maksimal 5MB.';
            } else {
                if (!is_dir('../uploads/pengaduan')) { 
                    mkdir('../uploads/pengaduan', 0777, true); 
                }
                $foto = 'pengaduan_' . time() . '_' . uniqid() . '.' . $ext;
                if(!move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/pengaduan/' . $foto)) {
                    $error = 'Gagal mengunggah foto. Periksa izin folder.';
                }
            }
        }

        if(!$error) {
            $stmt = $conn->prepare("INSERT INTO pengaduan (nama, isi, foto) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $isi, $foto);
            
            if($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Kesalahan Database: ' . $conn->error;
            }
        }
    }
}

require_once '../config/header.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if($success): ?>
    Swal.fire({
        icon: 'success',
        title: 'Laporan Terkirim!',
        text: 'Terima kasih atas partisipasi Anda. Laporan akan segera kami verifikasi.',
        confirmButtonColor: '#f97316', // warna orange-500
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>

    <?php if($error): ?>
    Swal.fire({
        icon: 'error',
        title: 'Ups!',
        text: '<?= $error ?>',
        confirmButtonColor: '#ef4444',
        customClass: { confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-sm' }
    });
    <?php endif; ?>
});
</script>

<div class="max-w-2xl mx-auto px-4 py-10 min-h-[70vh]">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Pengaduan Masyarakat</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-comment-dots text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">Suara Masyarakat</h1>
                    <p class="text-orange-100 text-sm">Lapor setiap kendala untuk perbaikan desa</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <?php if($success): ?>
            <div class="text-center py-8">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="fas fa-check-circle text-4xl text-orange-600"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-800 mb-2">Terima Kasih!</h2>
                <p class="text-gray-500 mb-6">Laporan Anda atas nama <strong><?= htmlspecialchars($_POST['nama']) ?></strong> telah masuk ke sistem kami.</p>
                
                <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+baru+saja+mengirim+laporan+pengaduan+melalui+website+atas+nama+*<?= urlencode($_POST['nama'] ?? '') ?>*.+Mohon+ditindaklanjuti.+Terima+kasih." 
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3.5 rounded-xl transition shadow-sm mb-4 w-full sm:w-auto">
                    <i class="fab fa-whatsapp text-lg"></i> Konfirmasi via WhatsApp
                </a>
                <br>
                <a href="pengaduan.php" class="inline-block mt-4 text-sm text-primary-600 hover:text-primary-700 hover:underline transition font-medium">Buat laporan baru</a>
            </div>

            <?php else: ?>

            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                Silakan sampaikan pengaduan, kritik, atau saran Anda dengan jujur dan bertanggung jawab. Identitas Anda dapat kami rahasiakan.
            </p>

            <form method="POST" action="pengaduan.php" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama / Identitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           placeholder="Boleh isi nama asli atau Anonim"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 transition"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Isi Laporan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi" rows="5"
                              placeholder="Detail laporan (Lokasi, Kejadian, Harapan)..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none transition"
                              required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Lampiran Foto <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-orange-300 hover:bg-orange-50 transition cursor-pointer" 
                         onclick="document.getElementById('foto').click()">
                        <i class="fas fa-camera text-2xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500 font-medium">Ketuk untuk pilih foto</p>
                        <p class="text-xs text-gray-400 mt-0.5">JPG/PNG/WEBP, Maks. 5MB</p>
                        <div id="foto-preview" class="hidden mt-4 pt-4 border-t border-gray-100">
                            <img id="preview-img" src="" class="max-h-32 rounded-lg mx-auto shadow-sm" alt="Preview">
                        </div>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 text-base transition shadow-md">
                        <i class="fas fa-paper-plane"></i> Kirim Laporan
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function previewFoto(input) {
    if(input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('foto-preview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../config/footer.php'; ?>