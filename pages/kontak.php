<?php
require_once '../config/db.php';
$page_title = 'Kontak Desa';
require_once '../config/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Kontak Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Kontak Desa Darmakradenan</h1>
    <p class="text-gray-500 mb-10">Hubungi kami untuk informasi, pertanyaan, atau keperluan administrasi</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Info Kontak -->
        <div class="space-y-5">
            <?php
            $contacts = [
                ['icon'=>'fa-map-marker-alt','color'=>'bg-red-100 text-red-600','label'=>'Alamat','value'=>'Jl. Desa Darmakradenan No. 1, RT 01/RW 01<br>Kecamatan Contoh, Kabupaten Contoh<br>Provinsi Contoh, 12345'],
                ['icon'=>'fa-phone','color'=>'bg-blue-100 text-blue-600','label'=>'Telepon','value'=>'(0271) 123456'],
                ['icon'=>'fab fa-whatsapp','color'=>'bg-green-100 text-green-600','label'=>'WhatsApp Admin','value'=>'0812-3456-7890'],
                ['icon'=>'fa-envelope','color'=>'bg-purple-100 text-purple-600','label'=>'Email','value'=>'desadarmakradenan@desadarmakradenan.go.id'],
                ['icon'=>'fa-clock','color'=>'bg-yellow-100 text-yellow-600','label'=>'Jam Pelayanan','value'=>'Senin – Jumat: 08.00 – 15.00 WIB<br>Sabtu: 08.00 – 12.00 WIB'],
            ];
            foreach($contacts as $c): ?>
            <div class="flex items-start gap-4 bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <div class="w-11 h-11 <?= $c['color'] ?> rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="<?= $c['icon'] ?>"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-400 font-semibold uppercase mb-1"><?= $c['label'] ?></div>
                    <div class="text-gray-800 font-medium text-sm leading-relaxed"><?= $c['value'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- WA CTA -->
            <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin+Desa+ABC,+saya+ingin+menanyakan+informasi+tentang..." 
               target="_blank"
               class="flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-xl text-base transition shadow-lg">
                <i class="fab fa-whatsapp text-2xl"></i>
                <div>
                    <div>Chat Admin Sekarang</div>
                    <div class="text-xs font-normal text-green-100">Respon cepat via WhatsApp</div>
                </div>
            </a>
        </div>

        <!-- Map & Layanan -->
        <div class="space-y-5">
            <!-- Map Placeholder -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-52 bg-gradient-to-br from-primary-100 to-blue-100 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-map-marker-alt text-4xl text-primary-400 mb-2"></i>
                        <p class="text-sm text-gray-500">Desa Darmakradenan, Kec. Contoh</p>
                        <p class="text-xs text-gray-400">Kab. Contoh, Provinsi Contoh</p>
                    </div>
                </div>
                <div class="p-4 text-center">
                    <a href="https://maps.google.com" target="_blank" 
                       class="text-primary-600 text-sm font-semibold hover:underline flex items-center justify-center gap-2">
                        <i class="fas fa-directions"></i> Buka di Google Maps
                    </a>
                </div>
            </div>

            <!-- Layanan Online -->
            <div class="bg-primary-50 rounded-xl border border-primary-100 p-5">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-mobile-alt text-primary-600"></i> Layanan Online
                </h3>
                <p class="text-sm text-gray-500 mb-4">Tidak perlu datang ke kantor desa! Gunakan layanan digital kami:</p>
                <div class="space-y-2">
                    <a href="ajukan-surat.php" class="flex items-center gap-3 bg-white p-3 rounded-lg hover:bg-primary-100 transition text-sm font-semibold text-primary-700">
                        <i class="fas fa-file-alt w-5 text-center"></i> Ajukan Surat Online
                        <i class="fas fa-chevron-right ml-auto text-xs text-gray-400"></i>
                    </a>
                    <a href="cek-status.php" class="flex items-center gap-3 bg-white p-3 rounded-lg hover:bg-primary-100 transition text-sm font-semibold text-primary-700">
                        <i class="fas fa-search w-5 text-center"></i> Cek Status Surat
                        <i class="fas fa-chevron-right ml-auto text-xs text-gray-400"></i>
                    </a>
                    <a href="pengaduan.php" class="flex items-center gap-3 bg-white p-3 rounded-lg hover:bg-primary-100 transition text-sm font-semibold text-primary-700">
                        <i class="fas fa-comment-dots w-5 text-center"></i> Kirim Pengaduan
                        <i class="fas fa-chevron-right ml-auto text-xs text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>
