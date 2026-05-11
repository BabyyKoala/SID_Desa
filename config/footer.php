<?php 
// config/footer.php 

// Fallback untuk mencegah fatal error jika konstanta belum didefinisikan di file config
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}
if (!defined('WA_NUMBER')) {
    // Anda bisa mengganti default nomor ini sesuai nomor aslinya (gunakan format 62...)
    define('WA_NUMBER', '6282134655359'); 
}
?>
<footer class="bg-primary-900 text-white mt-16">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shadow-sm">
                        <i class="fas fa-landmark text-white text-xs"></i>
                    </div>
                    <span class="font-bold text-lg">SID Desa Darmakradenan</span>
                </div>
                <p class="text-primary-200 text-sm leading-relaxed">
                    Sistem Informasi Desa Darmakradenan hadir untuk mempermudah pelayanan administrasi dan meningkatkan komunikasi antara warga dan perangkat desa.
                </p>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4 text-primary-100">Layanan Cepat</h4>
                <ul class="space-y-2 text-sm text-primary-300">
                    <li><a href="<?= BASE_URL ?>/pages/ajukan-surat.php" class="hover:text-primary-400 transition flex items-center gap-2"><i class="fas fa-file-alt w-4"></i>Ajukan Surat</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/cek-status.php" class="hover:text-primary-400 transition flex items-center gap-2"><i class="fas fa-search w-4"></i>Cek Status</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/pengaduan.php" class="hover:text-primary-400 transition flex items-center gap-2"><i class="fas fa-comment-dots w-4"></i>Lapor Pengaduan</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/informasi.php" class="hover:text-primary-400 transition flex items-center gap-2"><i class="fas fa-newspaper w-4"></i>Informasi Desa</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4 text-primary-100">Kontak Desa</h4>
                <ul class="space-y-2 text-sm text-primary-300">
                    <li class="flex items-start gap-2"><i class="fas fa-map-marker-alt w-4 mt-0.5"></i><span>Jl. Desa Darmakradenan, Kec. Ajibarang, Kab. Banyumas</span></li>
                    <li class="flex items-center gap-2"><i class="fas fa-phone w-4"></i><span>(0271) 123456</span></li>
                    <li class="flex items-center gap-2"><i class="fab fa-whatsapp w-4 text-green-400"></i><span>0821-3465-5359</span></li>
                </ul>
                <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan" target="_blank"
                   class="inline-flex items-center gap-2 mt-4 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                    <i class="fab fa-whatsapp text-lg"></i> Hubungi via WA
                </a>
            </div>
        </div>
        <div class="border-t border-primary-800 mt-8 pt-6 text-center text-sm text-primary-400">
            <p>&copy; <?= date('Y') ?> SID Desa Darmakradenan. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan" target="_blank"
   class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg transition hover:scale-110 focus:outline-none focus:ring-4 focus:ring-green-300"
   title="Chat via WhatsApp">
    <i class="fab fa-whatsapp text-3xl"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Fungsi Global untuk Konfirmasi Hapus Data
function konfirmasiHapus(url, namaData) {
    Swal.fire({
        title: 'Hapus Data?',
        text: `Anda yakin ingin menghapus "${namaData}"? Data yang sudah dihapus tidak bisa dikembalikan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444', // Merah Tailwind untuk Hapus
        cancelButtonColor: '#6b7280', // Abu-abu Tailwind untuk Batal
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true, // Memindahkan tombol batal ke sebelah kanan
        customClass: {
            confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-sm',
            cancelButton: 'rounded-xl px-4 py-2 font-bold shadow-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika dikonfirmasi, redirect ke link penghapusan
            window.location.href = url;
        }
    });
}
</script>

</body>
</html>