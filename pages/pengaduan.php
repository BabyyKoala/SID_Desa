<?php
require_once '../config/db.php';
$page_title = 'Pengaduan Masyarakat';
$success = false;
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = clean($_POST['nama'] ?? '');
    $isi  = clean($_POST['isi'] ?? '');
    $foto = '';

    if(!$nama || !$isi) {
        $error = 'Nama dan isi laporan wajib diisi.';
    } else {
        // Upload foto opsional
        if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','gif','webp'];
            if(!in_array($ext, $allowed)) {
                $error = 'Format foto tidak didukung. Gunakan JPG, PNG, atau GIF.';
            } elseif($_FILES['foto']['size'] > 5 * 1024 * 1024) {
                $error = 'Ukuran foto maksimal 5MB.';
            } else {
                $foto = 'pengaduan_' . time() . '.' . $ext;
                move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/pengaduan/' . $foto);
            }
        }

        if(!$error) {
            $stmt = $conn->prepare("INSERT INTO pengaduan (nama, isi, foto) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $isi, $foto);
            if($stmt->execute()) {
                $success = true;
            } else {
                $error = 'Terjadi kesalahan. Coba lagi.';
            }
        }
    }
}

require_once '../config/header.php';
?>

<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Pengaduan Masyarakat</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-400 p-6 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-comment-dots text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold">Laporan Pengaduan</h1>
                    <p class="text-orange-100 text-sm">Suara Anda penting bagi kami</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <?php if($success): ?>
            <div class="text-center py-8">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-4xl text-primary-600"></i>
                </div>
                <h2 class="text-xl font-extrabold text-gray-800 mb-2">Laporan Terkirim!</h2>
                <p class="text-gray-500 mb-6">Terima kasih atas laporan Anda. Perangkat desa akan segera menindaklanjuti.</p>
                
                <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin+Desa+ABC,+saya+baru+saja+mengirim+laporan+pengaduan+atas+nama+<?= urlencode($_POST['nama'] ?? '') ?>+mohon+ditindaklanjuti." 
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-xl transition mb-4">
                    <i class="fab fa-whatsapp"></i> Konfirmasi via WhatsApp
                </a>
                <br>
                <a href="pengaduan.php" class="text-sm text-primary-600 hover:underline">Kirim laporan lain</a>
            </div>

            <?php else: ?>
            <?php if($error): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 flex items-center gap-2 text-sm">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
            <?php endif; ?>

            <p class="text-gray-500 text-sm mb-5">
                Isi form di bawah untuk menyampaikan pengaduan, masukan, atau saran kepada perangkat Desa Darmakradenan.
            </p>

            <form method="POST" enctype="multipart/form-data" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           placeholder="Nama lengkap Anda (boleh anonim)"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Isi Laporan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="isi" rows="5"
                              placeholder="Tuliskan laporan, pengaduan, atau saran Anda dengan jelas dan detail..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none"
                              required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Foto Pendukung <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-orange-300 transition cursor-pointer" 
                         onclick="document.getElementById('foto').click()">
                        <i class="fas fa-camera text-2xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-400">Klik untuk memilih foto</p>
                        <p class="text-xs text-gray-300">JPG, PNG, max 5MB</p>
                        <div id="foto-preview" class="hidden mt-3">
                            <img id="preview-img" src="" class="max-h-32 rounded-lg mx-auto" alt="">
                            <p id="foto-name" class="text-xs text-gray-500 mt-1"></p>
                        </div>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden"
                           onchange="previewFoto(this)">
                </div>

                <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 flex gap-3 text-sm">
                    <i class="fas fa-shield-alt text-orange-500 mt-0.5 shrink-0"></i>
                    <div class="text-orange-700">
                        Pengaduan Anda bersifat rahasia dan hanya dapat diakses oleh perangkat desa.
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-400 hover:from-orange-600 hover:to-orange-500 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2 text-base transition">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
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
            document.getElementById('foto-name').textContent = input.files[0].name;
            document.getElementById('foto-preview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../config/footer.php'; ?>
