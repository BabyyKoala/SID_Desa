<?php // config/footer.php ?>
<!-- FOOTER -->
<footer class="bg-primary-900 text-white mt-16">
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Tentang -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-landmark text-white text-xs"></i>
                    </div>
                    <span class="font-bold text-lg">SID Desa Darmakradenan</span>
                </div>
                <p class="text-primary-200 text-sm leading-relaxed">
                    Sistem Informasi Desa Darmakradenan hadir untuk mempermudah pelayanan administrasi dan meningkatkan komunikasi antara warga dan perangkat desa.
                </p>
            </div>
            <!-- Link Cepat -->
            <div>
                <h4 class="font-semibold mb-4 text-primary-100">Layanan Cepat</h4>
                <ul class="space-y-2 text-sm text-primary-300">
                    <li><a href="<?= BASE_URL ?>/pages/ajukan-surat.php" class="hover:text-white flex items-center gap-2"><i class="fas fa-file-alt w-4"></i>Ajukan Surat</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/cek-status.php" class="hover:text-white flex items-center gap-2"><i class="fas fa-search w-4"></i>Cek Status</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/pengaduan.php" class="hover:text-white flex items-center gap-2"><i class="fas fa-comment-dots w-4"></i>Lapor Pengaduan</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/informasi.php" class="hover:text-white flex items-center gap-2"><i class="fas fa-newspaper w-4"></i>Informasi Desa</a></li>
                </ul>
            </div>
            <!-- Kontak -->
            <div>
                <h4 class="font-semibold mb-4 text-primary-100">Kontak Desa</h4>
                <ul class="space-y-2 text-sm text-primary-300">
                    <li class="flex items-start gap-2"><i class="fas fa-map-marker-alt w-4 mt-0.5"></i><span>Jl. Desa Darmakradenan, Kec. Ajibarang, Kab. Banyumas</span></li>
                    <li class="flex items-center gap-2"><i class="fas fa-phone w-4"></i><span>(0271) 123456</span></li>
                    <li class="flex items-center gap-2"><i class="fab fa-whatsapp w-4 text-green-400"></i><span>0812-3456-7890</span></li>
                </ul>
                <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin+Desa+ABC" target="_blank"
                   class="inline-flex items-center gap-2 mt-4 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    <i class="fab fa-whatsapp"></i> Hubungi via WA
                </a>
            </div>
        </div>
        <div class="border-t border-primary-700 mt-8 pt-6 text-center text-sm text-primary-400">
            <p>&copy; <?= date('Y') ?> SID Desa Darmakradenan</p>
        </div>
    </div>
</footer>

<!-- WhatsApp Float Button -->
<a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin+Desa+ABC" target="_blank"
   class="fixed bottom-6 right-6 z-50 w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center shadow-lg transition hover:scale-110"
   title="Chat via WhatsApp">
    <i class="fab fa-whatsapp text-2xl"></i>
</a>

</body>
</html>
