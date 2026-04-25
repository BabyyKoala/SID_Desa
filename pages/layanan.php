<?php
require_once '../config/db.php';
$page_title = 'Layanan Desa';
require_once '../config/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Layanan Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Layanan Desa Darmakradenan</h1>
    <p class="text-gray-500 mb-10">Akses semua layanan administrasi desa secara online, kapan saja dan dari mana saja.</p>

    <!-- Grid Layanan Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- Ajukan Surat -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-file-signature text-blue-600 text-2xl"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-lg mb-2">Ajukan Surat</h2>
            <p class="text-gray-500 text-sm flex-1 mb-5">
                Ajukan berbagai surat keterangan desa secara online. Tidak perlu antre di kantor desa.
            </p>
            <div class="text-xs text-gray-400 mb-4 space-y-1">
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Surat Domisili</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> SKTM (Tidak Mampu)</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Surat Keterangan Usaha</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Pengantar SKCK & lainnya</div>
            </div>
            <a href="ajukan-surat.php" class="btn-primary text-white font-bold py-3 rounded-xl text-center text-sm flex items-center justify-center gap-2">
                <i class="fas fa-arrow-right"></i> Ajukan Sekarang
            </a>
        </div>

        <!-- Cek Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-search text-primary-600 text-2xl"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-lg mb-2">Cek Status Surat</h2>
            <p class="text-gray-500 text-sm flex-1 mb-5">
                Pantau perkembangan pengajuan surat Anda kapan saja menggunakan kode pengajuan atau NIK.
            </p>
            <div class="text-xs text-gray-400 mb-4 space-y-1">
                <div class="flex items-center gap-2"><i class="fas fa-circle text-yellow-400 w-3"></i> <b>Diproses</b> — Sedang ditindaklanjuti</div>
                <div class="flex items-center gap-2"><i class="fas fa-circle text-primary-500 w-3"></i> <b>Selesai</b> — Siap diambil</div>
                <div class="flex items-center gap-2"><i class="fas fa-circle text-red-400 w-3"></i> <b>Ditolak</b> — Perlu perbaikan</div>
            </div>
            <a href="cek-status.php" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl text-center text-sm flex items-center justify-center gap-2 transition">
                <i class="fas fa-search"></i> Cek Status
            </a>
        </div>

        <!-- Pengaduan -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fas fa-comment-dots text-orange-500 text-2xl"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-lg mb-2">Lapor Pengaduan</h2>
            <p class="text-gray-500 text-sm flex-1 mb-5">
                Sampaikan laporan, pengaduan, atau saran untuk Desa ABC. Suara Anda akan ditindaklanjuti.
            </p>
            <div class="text-xs text-gray-400 mb-4 space-y-1">
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Pengaduan infrastruktur</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Keamanan lingkungan</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Sosial kemasyarakatan</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-primary-500 w-3"></i> Saran & masukan</div>
            </div>
            <a href="pengaduan.php" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl text-center text-sm flex items-center justify-center gap-2 transition">
                <i class="fas fa-paper-plane"></i> Kirim Laporan
            </a>
        </div>
    </div>

    <!-- Alur Proses Pengajuan -->
    <div class="bg-primary-50 rounded-2xl border border-primary-100 p-6">
        <h2 class="font-extrabold text-gray-800 text-lg mb-5 flex items-center gap-2">
            <i class="fas fa-route text-primary-600"></i> Alur Pengajuan Surat
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $steps = [
                ['num'=>'1','title'=>'Isi Form','desc'=>'Isi NIK, nama, jenis surat & keperluan','icon'=>'fa-edit','color'=>'bg-blue-100 text-blue-600'],
                ['num'=>'2','title'=>'Simpan Kode','desc'=>'Catat kode pengajuan otomatis yang muncul','icon'=>'fa-key','color'=>'bg-yellow-100 text-yellow-600'],
                ['num'=>'3','title'=>'Tunggu Proses','desc'=>'Admin desa memproses dalam 1-3 hari kerja','icon'=>'fa-clock','color'=>'bg-orange-100 text-orange-600'],
                ['num'=>'4','title'=>'Ambil Surat','desc'=>'Surat selesai, ambil di kantor atau hubungi admin','icon'=>'fa-check-circle','color'=>'bg-primary-100 text-primary-600'],
            ];
            foreach($steps as $s): ?>
            <div class="text-center">
                <div class="w-12 h-12 <?= $s['color'] ?> rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas <?= $s['icon'] ?> text-xl"></i>
                </div>
                <div class="font-bold text-gray-800 text-sm mb-1"><?= $s['title'] ?></div>
                <div class="text-xs text-gray-500 leading-relaxed"><?= $s['desc'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Kontak Darurat WA -->
    <div class="mt-6 bg-green-50 rounded-2xl border border-green-200 p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fab fa-whatsapp text-green-600 text-2xl"></i>
            </div>
            <div>
                <div class="font-bold text-gray-800">Butuh bantuan?</div>
                <div class="text-sm text-gray-500">Chat langsung dengan admin desa via WhatsApp</div>
            </div>
        </div>
        <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+admin+Desa+ABC,+saya+membutuhkan+bantuan+layanan+desa." 
           target="_blank"
           class="bg-green-500 hover:bg-green-600 text-white font-bold px-6 py-3 rounded-xl flex items-center gap-2 transition whitespace-nowrap">
            <i class="fab fa-whatsapp"></i> Chat Admin
        </a>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>
