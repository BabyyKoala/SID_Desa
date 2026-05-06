<?php
require_once '../config/db.php';

// Fallback untuk nomor WhatsApp jika belum ada di db.php
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', '6282134655359'); 
}

$page_title = 'Kontak Desa';
require_once '../config/header.php';
?>

<div class="max-w-5xl mx-auto px-4 py-10 min-h-[70vh]">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Kontak Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Kontak Desa Darmakradenan</h1>
    <p class="text-gray-500 mb-10 leading-relaxed text-lg">Hubungi kami untuk informasi, pertanyaan, atau keperluan administrasi.</p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Info Kontak (Kiri) -->
        <div class="space-y-4">
            <?php
            $contacts = [
                ['icon'=>'fa-map-marker-alt','color'=>'bg-red-50 text-red-500','label'=>'Alamat','value'=>'Jl. Raya Darmakradenan No. 1, RT 01/RW 01<br>Kecamatan Ajibarang, Kabupaten Banyumas<br>Provinsi Jawa Tengah, 53165'],
                ['icon'=>'fa-phone','color'=>'bg-blue-50 text-blue-500','label'=>'Telepon','value'=>'(0271) 123456'],
                ['icon'=>'fab fa-whatsapp','color'=>'bg-green-50 text-green-500','label'=>'WhatsApp Admin','value'=>'0821-3465-5359'],
                ['icon'=>'fa-envelope','color'=>'bg-purple-50 text-purple-500','label'=>'Email','value'=>'info@darmakradenan.desa.id'],
                ['icon'=>'fa-clock','color'=>'bg-yellow-50 text-yellow-500','label'=>'Jam Pelayanan','value'=>'Senin – Jumat: 08.00 – 15.00 WIB<br>Sabtu: 08.00 – 12.00 WIB'],
            ];
            foreach($contacts as $c): ?>
            <div class="flex items-start gap-5 bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition duration-300">
                <div class="w-12 h-12 <?= $c['color'] ?> rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="<?= $c['icon'] ?> text-xl"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1.5"><?= $c['label'] ?></div>
                    <div class="text-gray-800 font-medium text-sm leading-relaxed"><?= $c['value'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Tombol WhatsApp CTA -->
            <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+ingin+menanyakan+informasi+tentang..." 
               target="_blank"
               class="flex items-center justify-center gap-4 bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-2xl text-base transition shadow-sm hover:shadow-lg mt-4 group">
                <i class="fab fa-whatsapp text-3xl group-hover:scale-110 transition-transform"></i>
                <div class="text-left">
                    <div class="text-lg">Chat Admin Sekarang</div>
                    <div class="text-xs font-normal text-green-100">Respon cepat via pesan WhatsApp</div>
                </div>
            </a>
        </div>

        <!-- Map & Layanan (Kanan) -->
        <div class="space-y-6">
            <!-- Peta Interaktif (Google Maps iframe) -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                <div class="w-full h-72 relative bg-gray-100">
                    <iframe 
                        src="https://maps.google.com/maps?q=Kantor+Kepala+Desa+Darmakradenan,+Ajibarang,+Banyumas&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        class="absolute top-0 left-0 w-full h-full border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="p-4 bg-white text-center border-t border-gray-50">
                    <a href="https://maps.app.goo.gl/H3okvikR5bsQQMj56" target="_blank" 
                       class="text-primary-600 text-sm font-bold hover:text-primary-700 transition flex items-center justify-center gap-2">
                        <i class="fas fa-directions"></i> Buka Rute di Google Maps
                    </a>
                </div>
            </div>

            <!-- Layanan Online Cepat -->
            <div class="bg-primary-50/50 rounded-2xl border border-primary-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2 text-lg">
                    <i class="fas fa-mobile-alt text-primary-500"></i> Layanan Online
                </h3>
                <p class="text-sm text-gray-500 mb-5 leading-relaxed">Tidak perlu antre ke balai desa! Gunakan layanan digital kami dari rumah Anda.</p>
                
                <div class="space-y-3">
                    <a href="ajukan-surat.php" class="flex items-center gap-3 bg-white p-4 rounded-xl hover:bg-primary-50 transition shadow-sm text-sm font-semibold text-gray-700 border border-gray-100 hover:border-primary-200 group">
                        <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition">
                            <i class="fas fa-file-alt"></i> 
                        </div>
                        Ajukan Surat Online
                        <i class="fas fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="cek-status.php" class="flex items-center gap-3 bg-white p-4 rounded-xl hover:bg-primary-50 transition shadow-sm text-sm font-semibold text-gray-700 border border-gray-100 hover:border-primary-200 group">
                        <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition">
                            <i class="fas fa-search"></i> 
                        </div>
                        Cek Status Pengajuan
                        <i class="fas fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
                    </a>
                    
                    <a href="pengaduan.php" class="flex items-center gap-3 bg-white p-4 rounded-xl hover:bg-primary-50 transition shadow-sm text-sm font-semibold text-gray-700 border border-gray-100 hover:border-primary-200 group">
                        <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition">
                            <i class="fas fa-comment-dots"></i> 
                        </div>
                        Kirim Laporan / Pengaduan
                        <i class="fas fa-chevron-right ml-auto text-xs text-gray-300 group-hover:text-primary-500 group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>