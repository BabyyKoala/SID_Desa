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

<footer class="bg-[#0b2815] text-gray-300 pt-16 pb-8 border-t-[6px] border-green-600 relative overflow-hidden mt-16">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-green-500 opacity-5 rounded-full translate-y-1/3 -translate-x-1/4 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12 mb-12">
            
            <div class="md:col-span-5 lg:col-span-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg text-white shrink-0">
                        <i class="fas fa-landmark text-xl"></i>
                    </div>
                    <h3 class="font-extrabold text-xl text-white tracking-tight">SID Darmakradenan</h3>
                </div>
                <p class="text-sm leading-relaxed text-gray-400 mb-6">
                    Sistem Informasi Desa terpadu untuk mempermudah pelayanan administrasi publik dan transparansi informasi bagi seluruh warga Desa Darmakradenan.
                </p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300" title="Facebook">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300" title="Instagram">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300" title="YouTube">
                        <i class="fab fa-youtube text-sm"></i>
                    </a>
                </div>
            </div>
            
            <div class="md:col-span-3 lg:col-span-4 lg:ml-8">
                <h4 class="font-bold text-white text-lg mb-6 flex items-center gap-2">
                    <i class="fas fa-link text-green-500 text-sm"></i> Akses Cepat
                </h4>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="<?= BASE_URL ?>/pages/ajukan-surat.php" class="inline-flex items-center gap-2 hover:text-green-400 transition-colors group">
                            <i class="fas fa-chevron-right text-[10px] text-gray-600 group-hover:text-green-400 group-hover:translate-x-1 transition-all"></i> Ajukan Surat Online
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/pages/cek-status.php" class="inline-flex items-center gap-2 hover:text-green-400 transition-colors group">
                            <i class="fas fa-chevron-right text-[10px] text-gray-600 group-hover:text-green-400 group-hover:translate-x-1 transition-all"></i> Cek Status Pengajuan
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/pages/pengaduan.php" class="inline-flex items-center gap-2 hover:text-green-400 transition-colors group">
                            <i class="fas fa-chevron-right text-[10px] text-gray-600 group-hover:text-green-400 group-hover:translate-x-1 transition-all"></i> Pusat Laporan Pengaduan
                        </a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/pages/informasi.php" class="inline-flex items-center gap-2 hover:text-green-400 transition-colors group">
                            <i class="fas fa-chevron-right text-[10px] text-gray-600 group-hover:text-green-400 group-hover:translate-x-1 transition-all"></i> Papan Informasi Desa
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="md:col-span-4 lg:col-span-4">
                <h4 class="font-bold text-white text-lg mb-6 flex items-center gap-2">
                    <i class="fas fa-headset text-green-500 text-sm"></i> Layanan Kontak
                </h4>
                <ul class="space-y-4 text-sm mb-6">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-gray-500"></i>
                        <span class="leading-relaxed">Jl. Desa Darmakradenan, Kec. Ajibarang, Kab. Banyumas, Jawa Tengah 53153</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-gray-500"></i>
                        <span>pemdes@darmakradenan.desa.id</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fab fa-whatsapp text-green-500 text-base"></i>
                        <span class="font-medium text-gray-200">0821-3465-5359</span>
                    </li>
                </ul>
                <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+ingin+bertanya+terkait+layanan+desa." target="_blank"
                   class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-green-900/50 hover:-translate-y-0.5">
                    <i class="fab fa-whatsapp text-lg"></i> Chat Admin Desa
                </a>
            </div>
        </div>

        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500 font-medium">
            <p>&copy; <?= date('Y') ?> Pemerintah Desa Darmakradenan. Hak Cipta Dilindungi.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-gray-300 transition-colors">Syarat & Ketentuan</a>
                <span class="text-gray-700">|</span>
                <a href="#" class="hover:text-gray-300 transition-colors">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer>

<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-2 group">
    <div class="bg-white text-gray-800 text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0 pointer-events-none whitespace-nowrap border border-gray-100">
        Butuh Bantuan?
    </div>
    <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan" target="_blank"
       class="w-14 h-14 bg-[#25D366] hover:bg-[#1ebe5b] text-white rounded-full flex items-center justify-center shadow-lg shadow-green-900/20 transition-all duration-300 hover:scale-110 focus:outline-none relative"
       title="Chat via WhatsApp">
        <span class="absolute inline-flex h-full w-full rounded-full bg-[#25D366] opacity-30 animate-ping"></span>
        <i class="fab fa-whatsapp text-3xl relative z-10"></i>
    </a>
</div>

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