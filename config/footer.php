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
                    <a href="https://facebook.com/DesaDarmakradenan" target="_blank" class="w-9 h-9 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300" title="Facebook Desa Darmakradenan">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="https://instagram.com/desa_darmakradenan" target="_blank" class="w-9 h-9 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300" title="Instagram @desa_darmakradenan">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="https://youtube.com/@desadarmakradenan2192" target="_blank" class="w-9 h-9 rounded-full bg-white/5 hover:bg-green-600 flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300" title="YouTube @desadarmakradenan2192">
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
                <a href="javascript:void(0)" onclick="showSyaratKetentuan()" class="hover:text-gray-300 transition-colors">Syarat & Ketentuan</a>
                <span class="text-gray-700">|</span>
                <a href="javascript:void(0)" onclick="showKebijakanPrivasi()" class="hover:text-gray-300 transition-colors">Kebijakan Privasi</a>
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
        confirmButtonColor: '#ef4444', 
        cancelButtonColor: '#6b7280', 
        confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'rounded-xl px-4 py-2 font-bold shadow-sm',
            cancelButton: 'rounded-xl px-4 py-2 font-bold shadow-sm'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// Fungsi untuk memunculkan Modal Syarat & Ketentuan
function showSyaratKetentuan() {
    Swal.fire({
        title: '<strong class="text-gray-800">Syarat & Ketentuan</strong>',
        icon: 'info',
        html: `
            <div class="text-left text-sm text-gray-600 space-y-4 mt-2 max-h-80 overflow-y-auto pr-2">
                <p>Selamat datang di Sistem Informasi Desa (SID) Darmakradenan. Dengan menggunakan layanan ini, Anda menyetujui ketentuan berikut:</p>
                <ol class="list-decimal pl-5 space-y-2">
                    <li><strong>Akurasi Data:</strong> Warga wajib memberikan informasi dan data diri yang benar, asli, dan dapat dipertanggungjawabkan saat melakukan pengajuan surat atau pelaporan.</li>
                    <li><strong>Penggunaan Layanan:</strong> Layanan ini diperuntukkan khusus bagi warga Desa Darmakradenan untuk keperluan administrasi dan penyampaian aspirasi secara bijak.</li>
                    <li><strong>Larangan Penyalahgunaan:</strong> Segala bentuk penyalahgunaan sistem, pemalsuan identitas, atau pengiriman konten yang mengandung SARA, ujaran kebencian, dan melanggar hukum akan ditindak sesuai peraturan perundang-undangan.</li>
                    <li><strong>Hak Akses Admin:</strong> Admin desa berhak menolak pengajuan surat atau menghapus laporan pengaduan yang terindikasi tidak valid atau menyalahi aturan.</li>
                    <li><strong>Perubahan Ketentuan:</strong> Pemerintah Desa Darmakradenan berhak mengubah syarat dan ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya.</li>
                </ol>
            </div>
        `,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: '<i class="fas fa-check-circle"></i> Saya Mengerti',
        confirmButtonColor: '#16a34a',
        width: '600px',
        customClass: {
            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm'
        }
    });
}

// Fungsi untuk memunculkan Modal Kebijakan Privasi
function showKebijakanPrivasi() {
    Swal.fire({
        title: '<strong class="text-gray-800">Kebijakan Privasi</strong>',
        icon: 'success',
        html: `
            <div class="text-left text-sm text-gray-600 space-y-4 mt-2 max-h-80 overflow-y-auto pr-2">
                <p>Pemerintah Desa Darmakradenan berkomitmen tinggi untuk menghargai dan melindungi kerahasiaan data pribadi Anda. Berikut adalah kebijakan kami:</p>
                <ol class="list-decimal pl-5 space-y-2">
                    <li><strong>Pengumpulan Data:</strong> Kami mengumpulkan data NIK, Nama, nomor telepon, alamat, dan informasi terkait lainnya murni untuk keperluan verifikasi administrasi desa.</li>
                    <li><strong>Keamanan & Penyimpanan:</strong> Data Anda tersimpan secara aman di dalam *database* sistem kami dengan akses yang sangat terbatas dan hanya dapat dilihat oleh perangkat desa yang berwenang.</li>
                    <li><strong>Kerahasiaan Data:</strong> Kami <strong>tidak akan</strong> membagikan, menjual, menyewakan, atau menyebarkan data pribadi Anda kepada pihak ketiga manapun tanpa persetujuan tertulis dari Anda, kecuali diwajibkan oleh proses hukum.</li>
                    <li><strong>Keterbukaan Publik:</strong> Data yang ditampilkan pada statistik publik (seperti Demografi) hanyalah berupa angka akumulasi massal, tanpa mencantumkan identitas individu warga manapun.</li>
                </ol>
            </div>
        `,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: '<i class="fas fa-check-circle"></i> Tutup',
        confirmButtonColor: '#16a34a',
        width: '600px',
        customClass: {
            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm'
        }
    });
}
</script>

</body>
</html>