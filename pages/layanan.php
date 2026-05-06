<?php
require_once '../config/db.php';

// Fallback untuk nomor WhatsApp jika belum ada di db.php
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', '6282134655359'); 
}

$page_title = 'Layanan Desa';
require_once '../config/header.php';
?>

<div class="max-w-5xl mx-auto px-4 py-10 min-h-[70vh]">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Layanan Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Layanan Desa Darmakradenan</h1>
    <p class="text-gray-500 mb-10 text-lg">Akses semua layanan administrasi desa secara online, kapan saja dan dari mana saja.</p>

    <!-- Grid Layanan Utama -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- Ajukan Surat -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 p-6 flex flex-col group">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-signature text-blue-500 text-2xl"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-lg mb-2">Ajukan Surat</h2>
            <p class="text-gray-500 text-sm flex-1 mb-5 leading-relaxed">
                Ajukan berbagai surat keterangan desa secara online. Tidak perlu repot antre di kantor balai desa.
            </p>
            <div class="text-xs text-gray-500 mb-6 space-y-2">
                <div class="flex items-center gap-2"><i class="fas fa-check text-blue-500 w-3"></i> Surat Domisili</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-blue-500 w-3"></i> SKTM (Tidak Mampu)</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-blue-500 w-3"></i> Surat Keterangan Usaha</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-blue-500 w-3"></i> Pengantar SKCK & lainnya</div>
            </div>
            <a href="ajukan-surat.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl text-center text-sm flex items-center justify-center gap-2 transition shadow-sm mt-auto">
                <i class="fas fa-arrow-right"></i> Ajukan Sekarang
            </a>
        </div>

        <!-- Cek Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 p-6 flex flex-col group">
            <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-search text-primary-500 text-2xl"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-lg mb-2">Cek Status Surat</h2>
            <p class="text-gray-500 text-sm flex-1 mb-5 leading-relaxed">
                Pantau perkembangan pengajuan dokumen Anda secara realtime menggunakan kode pengajuan atau NIK.
            </p>
            <div class="text-xs text-gray-500 mb-6 space-y-2">
                <div class="flex items-center gap-2"><i class="fas fa-circle text-yellow-400 w-3 text-[10px]"></i> <b>Diproses</b> — Sedang ditindaklanjuti</div>
                <div class="flex items-center gap-2"><i class="fas fa-circle text-primary-500 w-3 text-[10px]"></i> <b>Selesai</b> — Siap diambil</div>
                <div class="flex items-center gap-2"><i class="fas fa-circle text-red-400 w-3 text-[10px]"></i> <b>Ditolak</b> — Perlu perbaikan</div>
            </div>
            <a href="cek-status.php" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl text-center text-sm flex items-center justify-center gap-2 transition shadow-sm mt-auto">
                <i class="fas fa-search"></i> Cek Status Sekarang
            </a>
        </div>

        <!-- Pengaduan -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition duration-300 p-6 flex flex-col group">
            <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                <i class="fas fa-comment-dots text-orange-500 text-2xl"></i>
            </div>
            <h2 class="font-extrabold text-gray-800 text-lg mb-2">Lapor Pengaduan</h2>
            <p class="text-gray-500 text-sm flex-1 mb-5 leading-relaxed">
                Sampaikan laporan, keluhan, atau saran untuk Desa Darmakradenan. Suara Anda akan kami tindak lanjuti.
            </p>
            <div class="text-xs text-gray-500 mb-6 space-y-2">
                <div class="flex items-center gap-2"><i class="fas fa-check text-orange-500 w-3"></i> Pengaduan infrastruktur</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-orange-500 w-3"></i> Keamanan lingkungan</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-orange-500 w-3"></i> Sosial kemasyarakatan</div>
                <div class="flex items-center gap-2"><i class="fas fa-check text-orange-500 w-3"></i> Saran & masukan</div>
            </div>
            <a href="pengaduan.php" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl text-center text-sm flex items-center justify-center gap-2 transition shadow-sm mt-auto">
                <i class="fas fa-paper-plane"></i> Kirim Laporan
            </a>
        </div>
    </div>

    <!-- Alur Proses Pengajuan -->
    <div class="bg-primary-50/50 rounded-2xl border border-primary-100 p-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-3 uppercase tracking-wider">
                <i class="fas fa-info-circle"></i> Panduan
            </div>
            <h2 class="font-extrabold text-gray-800 text-2xl">Alur Pengajuan Surat</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php
            $steps = [
                ['num'=>'1','title'=>'Isi Form Online','desc'=>'Lengkapi NIK, nama, jenis surat & keperluan pada form yang disediakan.','icon'=>'fa-edit','color'=>'bg-blue-100 text-blue-600'],
                ['num'=>'2','title'=>'Simpan Kode','desc'=>'Catat atau screenshot kode pengajuan otomatis yang muncul di layar.','icon'=>'fa-key','color'=>'bg-yellow-100 text-yellow-600'],
                ['num'=>'3','title'=>'Tunggu Proses','desc'=>'Perangkat desa akan memverifikasi dan memproses dalam 1-3 hari kerja.','icon'=>'fa-clock','color'=>'bg-orange-100 text-orange-600'],
                ['num'=>'4','title'=>'Ambil Surat','desc'=>'Jika status sudah selesai, ambil surat fisik di kantor balai desa.','icon'=>'fa-check-circle','color'=>'bg-green-100 text-green-600'],
            ];
            foreach($steps as $s): ?>
            <div class="text-center relative">
                <div class="w-16 h-16 <?= $s['color'] ?> rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm border border-white">
                    <i class="fas <?= $s['icon'] ?> text-2xl"></i>
                </div>
                <div class="font-bold text-gray-800 text-base mb-2"><?= $s['title'] ?></div>
                <div class="text-sm text-gray-500 leading-relaxed"><?= $s['desc'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Kontak Darurat WA -->
    <div class="mt-8 bg-gradient-to-r from-green-50 to-green-100 rounded-2xl border border-green-200 p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-sm">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm text-green-500">
                <i class="fab fa-whatsapp text-3xl"></i>
            </div>
            <div>
                <div class="font-extrabold text-gray-800 text-lg">Butuh bantuan langsung?</div>
                <div class="text-sm text-gray-600 mt-1">Jangan ragu, chat dengan perangkat desa via WhatsApp jika Anda mengalami kesulitan.</div>
            </div>
        </div>
        <a href="https://wa.me/<?= WA_NUMBER ?>?text=Halo+Admin+Desa+Darmakradenan,+saya+membutuhkan+bantuan+terkait+layanan+desa." 
           target="_blank"
           class="bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3.5 rounded-xl flex items-center gap-2 transition shadow-md hover:shadow-lg whitespace-nowrap w-full sm:w-auto justify-center">
            <i class="fab fa-whatsapp text-lg"></i> Chat Admin
        </a>
    </div>
</div>

<?php require_once '../config/footer.php'; ?>