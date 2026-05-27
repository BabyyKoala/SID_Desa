<?php
require_once '../config/db.php';

// Fallback untuk nomor WhatsApp jika belum ada di db.php
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', '6282134655359'); 
}

$page_title = 'Kontak Desa';
require_once '../config/header.php';
?>

<div class="max-w-6xl mx-auto px-4 py-10 min-h-[75vh]">
    <nav class="text-sm text-gray-500 mb-8 flex items-center gap-2 font-medium">
        <a href="../index.php" class="hover:text-primary-600 transition-colors">Beranda</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
        <span class="text-gray-900 font-bold">Kontak Kami</span>
    </nav>

    <div class="mb-12">
        <div class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1.5 rounded-full mb-4 uppercase tracking-wider border border-primary-100">
            <i class="fas fa-headset"></i> Pusat Bantuan
        </div>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Hubungi Pemerintah Desa</h1>
        <p class="text-gray-500 text-base md:text-lg max-w-2xl leading-relaxed">
            Kami siap melayani Anda. Silakan hubungi kami melalui saluran resmi di bawah ini atau kunjungi kantor balai desa pada jam kerja operasional.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <?php
                $contacts = [
                    ['icon'=>'fa-map-marker-alt','color'=>'text-red-500 bg-red-50','label'=>'Kantor Balai Desa','value'=>'Jl. Raya Darmakradenan No. 1, RT 01/RW 01<br>Kecamatan Ajibarang, Kabupaten Banyumas<br>Jawa Tengah, 53165'],
                    ['icon'=>'fa-phone','color'=>'text-blue-500 bg-blue-50','label'=>'Telepon Kantor','value'=>'(0271) 123456'],
                    ['icon'=>'fa-envelope','color'=>'text-purple-500 bg-purple-50','label'=>'Email Resmi','value'=>'pemdes@darmakradenan.desa.id'],
                    ['icon'=>'fa-clock','color'=>'text-yellow-600 bg-yellow-50','label'=>'Jam Pelayanan Publik','value'=>'Senin – Jumat: 08.00 – 15.00 WIB<br>Sabtu - Minggu: Libur'],
                ];
                foreach($contacts as $i => $c): 
                    $isLast = $i === count($contacts) - 1;
                ?>
                <div class="flex items-start gap-5 p-6 <?= !$isLast ? 'border-b border-gray-50' : '' ?> hover:bg-gray-50/50 transition duration-300 group">
                    <div class="w-12 h-12 <?= $c['color'] ?> rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-110 transition-transform duration-300">
                        <i class="<?= $c['icon'] ?> text-xl"></i>
                    </div>
                    <div>
                        <div class="text-[11px] text-gray-400 font-extrabold uppercase tracking-wider mb-1.5"><?= $c['label'] ?></div>
                        <div class="text-gray-800 font-medium text-sm leading-relaxed"><?= $c['value'] ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+ingin+menanyakan+informasi+tentang..." 
               target="_blank"
               class="flex items-center justify-center gap-4 bg-gradient-to-r from-[#25D366] to-[#1DA851] text-white font-bold py-4 px-6 rounded-3xl text-base transition-all duration-300 shadow-lg shadow-green-200 hover:shadow-xl hover:-translate-y-1 group">
                <div class="relative">
                    <i class="fab fa-whatsapp text-4xl relative z-10"></i>
                    <div class="absolute inset-0 bg-white/30 rounded-full blur animate-ping z-0"></div>
                </div>
                <div class="text-left">
                    <div class="text-lg tracking-wide">Chat Admin Desa</div>
                    <div class="text-[11px] font-medium text-green-50 uppercase tracking-wider">Fast Response via WhatsApp</div>
                </div>
            </a>

            <div class="bg-primary-900 rounded-3xl p-6 text-white text-center shadow-md">
                <div class="text-sm font-bold text-primary-200 uppercase tracking-wider mb-4">Ikuti Kami di Sosial Media</div>
                <div class="flex justify-center gap-4">
                    <a href="https://facebook.com/DesaDarmakradenan" target="_blank" class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center hover:bg-white/20 hover:text-white hover:-translate-y-1 transition-all" title="Facebook Desa Darmakradenan"><i class="fab fa-facebook-f text-xl"></i></a>
                    <a href="https://instagram.com/desa_darmakradenan" target="_blank" class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center hover:bg-white/20 hover:text-white hover:-translate-y-1 transition-all" title="Instagram @desa_darmakradenan"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="https://youtube.com/@desadarmakradenan2192" target="_blank" class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center hover:bg-white/20 hover:text-white hover:-translate-y-1 transition-all" title="YouTube @desadarmakradenan2192"><i class="fab fa-youtube text-xl"></i></a>
                </div>
            </div>

        </div>

        <div class="lg:col-span-7 space-y-6">
            
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col group">
                <div class="w-full h-80 relative bg-gray-200">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.2737664426544!2d109.02081911477526!3d-7.435035294636306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e656605e54d3c63%3A0xcab8383e390c889a!2sDarmakradenan%2C%20Ajibarang%2C%20Banyumas%20Regency%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1680000000000!5m2!1sen!2sid" 
                        class="absolute top-0 left-0 w-full h-full border-0 grayscale opacity-90 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="p-4 bg-white text-center border-t border-gray-50">
                    <a href="https://goo.gl/maps/example" target="_blank" 
                       class="text-primary-600 text-sm font-bold hover:text-primary-800 transition flex items-center justify-center gap-2">
                        <i class="fas fa-location-arrow"></i> Buka Petunjuk Arah di Aplikasi Google Maps
                    </a>
                </div>
            </div>

            <div class="bg-gradient-to-br from-primary-50 to-white rounded-3xl border border-primary-100 p-8 shadow-sm">
                <h3 class="font-extrabold text-gray-900 mb-2 flex items-center gap-2 text-xl">
                    <i class="fas fa-laptop-house text-primary-500"></i> Layanan Digital Mandiri
                </h3>
                <p class="text-sm text-gray-500 mb-6 leading-relaxed">Lebih efisien tanpa antre. Akses berbagai layanan administrasi desa langsung dari perangkat Anda.</p>
                
                <div class="grid sm:grid-cols-2 gap-4">
                    <a href="ajukan-surat.php" class="flex items-center gap-4 bg-white p-4 rounded-2xl hover:shadow-md transition-shadow border border-gray-100 hover:border-primary-200 group">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                            <i class="fas fa-file-signature"></i> 
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm group-hover:text-primary-600 transition-colors">Ajukan Surat</div>
                            <div class="text-[11px] text-gray-400">Pengajuan SKTM, Domisili, dll</div>
                        </div>
                    </a>
                    
                    <a href="cek-status.php" class="flex items-center gap-4 bg-white p-4 rounded-2xl hover:shadow-md transition-shadow border border-gray-100 hover:border-primary-200 group">
                        <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center group-hover:bg-primary-600 group-hover:text-white transition-colors shrink-0">
                            <i class="fas fa-search"></i> 
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm group-hover:text-primary-600 transition-colors">Cek Status</div>
                            <div class="text-[11px] text-gray-400">Lacak progres surat Anda</div>
                        </div>
                    </a>
                    
                    <a href="pengaduan.php" class="flex items-center gap-4 bg-white p-4 rounded-2xl hover:shadow-md transition-shadow border border-gray-100 hover:border-primary-200 group sm:col-span-2">
                        <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors shrink-0">
                            <i class="fas fa-bullhorn"></i> 
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-sm group-hover:text-primary-600 transition-colors">Pusat Pengaduan Warga</div>
                            <div class="text-[11px] text-gray-400">Sampaikan laporan infrastruktur atau layanan</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>