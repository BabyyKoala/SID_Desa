<?php
// config/header.php - Layout Header untuk halaman publik

// Fallback jika BASE_URL belum didefinisikan di db.php
if (!defined('BASE_URL')) {
    define('BASE_URL', ''); 
}

$page_title = isset($page_title) ? $page_title . ' — SID Desa Darmakradenan' : 'SID Desa Darmakradenan';

// FITUR PROFESIONAL: Deteksi halaman aktif secara dinamis
$current_uri = $_SERVER['REQUEST_URI'];
function isMenuAktif($path) {
    global $current_uri;
    if ($path === 'index.php') {
        if (strpos($current_uri, 'index.php') !== false || substr($current_uri, -1) === '/' || substr($current_uri, -9) === 'sid_desa/') {
            return true;
        }
        return false;
    }
    return strpos($current_uri, $path) !== false;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="Sistem Informasi Desa Darmakradenan - Pelayanan administrasi desa secara digital">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: { 
                            50:'#f0fdf4', 100:'#dcfce7', 200:'#bbf7d0', 300:'#86efac', 400:'#4ade80',
                            500:'#22c55e', 600:'#16a34a', 700:'#15803d', 800:'#166534', 900:'#14532d' 
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Efek Garis Bawah Animasi untuk Menu Desktop yang Presisi */
        .nav-link { 
            position: relative;
            transition: color 0.2s ease-in-out; 
            height: 100%;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }
        .nav-link:hover { color: #16a34a; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -1px; /* Melayang tipis menutupi border bawah navbar */
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background-color: #16a34a;
            transition: width 0.3s ease;
            border-radius: 4px 4px 0 0;
        }
        .nav-link:hover::after { width: 70%; }
        .nav-link.active { color: #16a34a; font-weight: 700; }
        .nav-link.active::after { width: 100%; }
        
        /* Tombol Utama */
        .btn-primary { 
            background: linear-gradient(135deg, #16a34a, #15803d); 
            transition: all 0.3s ease; 
        }
        .btn-primary:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 4px 15px rgba(22,163,74,0.3); 
        }
        
        /* Animasi Mulus untuk Mobile Menu (Slide Down) */
        .mobile-menu { 
            max-height: 0; 
            overflow: hidden; 
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
        }
        .mobile-menu.open { 
            max-height: 500px; 
            opacity: 1;
        }
        
        /* Utility classes untuk badge status global */
        .badge-diproses { background:#fef3c7; color:#92400e; border: 1px solid #fde68a; }
        .badge-selesai { background:#dcfce7; color:#166534; border: 1px solid #bbf7d0; }
        .badge-ditolak { background:#fee2e2; color:#991b1b; border: 1px solid #fecaca; }
        .badge-masuk { background:#fee2e2; color:#b91c1c; border: 1px solid #fecaca; } 
        .badge-berjalan { background:#dbeafe; color:#1e40af; border: 1px solid #bfdbfe; }
        .badge-perencanaan { background:#fef3c7; color:#92400e; border: 1px solid #fde68a; }
        
        .hero-bg { background: linear-gradient(135deg, #14532d 0%, #15803d 50%, #16a34a 100%); }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="bg-primary-900 text-white py-2 hidden md:block">
    <div class="max-w-6xl mx-auto px-4 flex justify-between items-center text-[11px] font-semibold tracking-wide">
        <div class="flex items-center gap-5">
            <span class="flex items-center gap-1.5"><i class="fas fa-clock text-primary-400"></i> Jam Kerja: Sen - Jum (08:00 - 15:00 WIB)</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-primary-400"></i> pemdes@darmakradenan.desa.id</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-primary-300 transition-colors" title="Facebook Desa"><i class="fab fa-facebook-f text-sm"></i></a>
            <a href="#" class="hover:text-primary-300 transition-colors" title="Instagram Desa"><i class="fab fa-instagram text-sm"></i></a>
            <a href="#" class="hover:text-primary-300 transition-colors" title="YouTube Desa"><i class="fab fa-youtube text-sm"></i></a>
        </div>
    </div>
</div>

<nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-sm group-hover:bg-primary-700 transition-colors">
                    <i class="fas fa-landmark text-white text-sm"></i>
                </div>
                <div>
                    <div class="font-extrabold text-gray-800 text-sm md:text-base leading-tight group-hover:text-primary-700 transition-colors">SID Desa Darmakradenan</div>
                    <div class="text-[11px] text-gray-500 leading-tight hidden sm:block font-medium tracking-wide">Sistem Informasi Desa</div>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-1 h-full">
                <a href="<?= BASE_URL ?>/index.php" class="nav-link text-sm <?= isMenuAktif('index.php') ? 'active' : 'text-gray-600 font-medium' ?>">Beranda</a>
                <a href="<?= BASE_URL ?>/pages/informasi.php" class="nav-link text-sm <?= isMenuAktif('informasi.php') ? 'active' : 'text-gray-600 font-medium' ?>">Informasi</a>
                <a href="<?= BASE_URL ?>/pages/layanan.php" class="nav-link text-sm <?= isMenuAktif('layanan.php') ? 'active' : 'text-gray-600 font-medium' ?>">Layanan</a>
                <a href="<?= BASE_URL ?>/pages/transparansi.php" class="nav-link text-sm <?= isMenuAktif('transparansi.php') ? 'active' : 'text-gray-600 font-medium' ?>">Transparansi</a>
                <a href="<?= BASE_URL ?>/pages/kontak.php" class="nav-link text-sm <?= isMenuAktif('kontak.php') ? 'active' : 'text-gray-600 font-medium' ?>">Kontak</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="<?= BASE_URL ?>/pages/ajukan-surat.php" 
                   class="btn-primary text-white text-xs font-bold px-5 py-2.5 rounded-xl hidden sm:flex items-center gap-2 shadow-sm">
                    <i class="fas fa-file-signature"></i> Ajukan Surat
                </a>
                
                <button onclick="toggleMenu()" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-600 hover:bg-gray-100 hover:text-primary-600 transition focus:outline-none border border-gray-100">
                    <i class="fas fa-bars text-lg" id="menu-icon"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="mobile-menu md:hidden">
            <div class="flex flex-col py-3 gap-1 border-t border-gray-100">
                <?php
                // Array menu mobile untuk mempermudah deteksi aktif
                $mobile_menus = [
                    ['url' => 'index.php', 'label' => 'Beranda', 'icon' => 'fa-home'],
                    ['url' => 'informasi.php', 'label' => 'Informasi', 'icon' => 'fa-newspaper'],
                    ['url' => 'layanan.php', 'label' => 'Layanan', 'icon' => 'fa-hands-helping'],
                    ['url' => 'transparansi.php', 'label' => 'Transparansi', 'icon' => 'fa-chart-pie'],
                    ['url' => 'kontak.php', 'label' => 'Kontak', 'icon' => 'fa-address-book']
                ];
                
                foreach ($mobile_menus as $menu) {
                    $is_active = isMenuAktif($menu['url']);
                    $active_classes = $is_active ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-600 hover:bg-gray-50';
                    $url_prefix = ($menu['url'] === 'index.php') ? '' : 'pages/';
                    echo '<a href="'. BASE_URL . '/' . $url_prefix . $menu['url'] .'" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm transition mx-2 '. $active_classes .'">';
                    echo '<i class="fas '. $menu['icon'] .' w-5 text-center '. ($is_active ? 'text-primary-600' : 'text-gray-400') .'"></i> ' . $menu['label'] . '</a>';
                }
                ?>
                <div class="px-6 pt-3 pb-2 mt-1 border-t border-gray-50">
                    <a href="<?= BASE_URL ?>/pages/ajukan-surat.php" class="btn-primary text-white text-sm font-bold w-full py-3 rounded-xl flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-file-signature"></i> Ajukan Surat Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('menu-icon');
    menu.classList.toggle('open');
    
    // Smooth transition untuk rotasi icon
    if (menu.classList.contains('open')) {
        icon.style.transform = 'rotate(90deg)';
        setTimeout(() => { icon.className = 'fas fa-times text-lg text-primary-600'; icon.style.transform = 'rotate(0deg)'; }, 150);
    } else {
        icon.style.transform = 'rotate(-90deg)';
        setTimeout(() => { icon.className = 'fas fa-bars text-lg'; icon.style.transform = 'rotate(0deg)'; }, 150);
    }
}
</script>