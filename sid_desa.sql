-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 02:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sid_desa`
--

-- --------------------------------------------------------

--
-- Table structure for table `apbdes`
--

CREATE TABLE `apbdes` (
  `id` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kategori` enum('Pendapatan','Pengeluaran') NOT NULL,
  `uraian` varchar(300) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `apbdes`
--

INSERT INTO `apbdes` (`id`, `tahun`, `kategori`, `uraian`, `jumlah`, `tanggal`) VALUES
(1, '2025', 'Pendapatan', 'Dana Desa', 850000000, '2026-04-25 11:39:55'),
(2, '2025', 'Pendapatan', 'Alokasi Dana Desa (ADD)', 320000000, '2026-04-25 11:39:55'),
(3, '2025', 'Pendapatan', 'Pendapatan Asli Desa', 45000000, '2026-04-25 11:39:55'),
(4, '2025', 'Pengeluaran', 'Bidang Penyelenggaraan Pemerintahan', 280000000, '2026-04-25 11:39:55'),
(5, '2025', 'Pengeluaran', 'Bidang Pembangunan Desa', 550000000, '2026-04-25 11:39:55'),
(6, '2025', 'Pengeluaran', 'Bidang Pembinaan Kemasyarakatan', 120000000, '2026-04-25 11:39:55'),
(7, '2025', 'Pengeluaran', 'Bidang Pemberdayaan Masyarakat', 265000000, '2026-04-25 11:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(300) NOT NULL,
  `isi` longtext NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `isi`, `gambar`, `tanggal`) VALUES
(1, 'Warga Darmakradenan Kecewa, Mediasi dengan PT Star Semen Bima Belum Hasilkan Keputusan', 'Mediasi antara warga Desa Darmakradenan, Kecamatan Ajibarang, Jawa Tengah dengan pihak PT Star Semen Bima terkait sengketa lahan tambang kembali belum membuahkan hasil. Pertemuan yang difasilitasi oleh Camat Ajibarang bersama Komisi II DPRD Kabupaten Banyumas itu berlangsung di Pendopo Kecamatan Ajibarang, hari Rabu 12 Nopember 2025. Mediasi turut dihadiri oleh perwakilan Badan Pertanahan Nasional (BPN), Satpol PP Banyumas, dan pihak perusahaan.', 'berita_1778053923.jpg', '2026-04-25 11:39:55'),
(2, 'Bongkar Bukit, Inilah Penampakan Serbuan Teritorial Korem 071/Wijayakusuma', 'Bukit cadas yang tadinya berdiri tegak ditengah lingkungan masyarakat, kini berubah fungsi dan manfaat untuk kelangsungan hidup masyarakat khususnya di Desa Darmakradenan Kecamatan Ajibarang Kabupaten Banyumas.\r\n\r\nLahan seluas 227 Hektar dengan hamparan perbukitan milik TNI AD ini, digunakan 2 hektarnya untuk pemanfaatan lahan bagi masyarakat sekitarnya guna membantu pemulihan ekonomi masyarakat dimasa pandemi Covid-19 ini.\r\n\r\nTNI AD melalui Kodam IV/Diponegoro dan Korem 071/Wijayakusuma dengan kegiatan Karya Bakti dalam rangka Serbuan Teritorialnya, merubah bukit ini menjadi Sentra Ekonomi Masyarakat yang peruntukkannya bagi masyarakat setempat guna membantu meningkatkan perekonomian masyarakat Desa Darmakradenan.\r\n\r\nHingga kini, pembangunan sentra ekonomi sudah rampung 100 %, terdiri dari musholla, kios tempat berdagang, MCK, parkir yang luas dan tempat istirahat bagi para pengendara yang melintas di jalur Darmakradenan.', 'berita_1778501984.jpg', '2026-04-25 11:39:55'),
(3, 'Penanganan Darurat Pasca Longsor Tambang PT STAR, Penghuni 15 Rumah Terdampak Tak Langsung Diminta Mengungsi', 'Plt Kepala Pelaksana Badan Penanggulangan Bencana Daerah (BPBD) Kabupaten Banyumas, Andi Risdianto dalam rapat bersama warga terdampak longsor tambang PT STAR, jajaran manajemen PT STAR, Pemerintah desa dan Forkompincam Ajibarang menyatakan hal tersebut di Balai Desa Darmakradenan Kecamatan Ajibarang pada Jumat sore 31 Oktober 2025. \r\n\r\nImbauan untuk mengungsi ini dilaksanakan sebagai upaya bagian penyelamatan dan pengamanan warga terdampak langsung ataupun tak langsung saat proses penanganan pasca longsor tambang PT STAR tersebut.\r\n\r\n\"Sebagimana hasil kaji cepat bersama pihak terkait beberapa waktu lalu, dalam penanganan darurat ini aspek keselamatan dan keamanan menjadi hal yang pertama dilakukan. Selama proses penanganan darurat inilah, segala kebutuhan dasar warga terdampak akan menjadi tanggungjawab dari PT STAR,\" jelasnya.\r\n\r\nKepala Desa Darmakradenan, Kecamatan Ajibarang, Imam Wasingun menuturkan ditambah tiga keluarga terdampak langsung, total untuk warga yang diimbau untuk mengungsi ini ada sebanyak 18 kepala keluarga. Untuk 15 KK rumah tangga yang turut diminta mengungsi ini rumahnya masuk radius tak aman dari lokasi titik longsor.', 'berita_1778054531.webp', '2026-04-25 11:39:55'),
(5, 'MBG Dibagikan Melalui Posyandu di Desa Darmakradenan, Sasar 589 Penerima', 'Program pembagian Makanan Bergizi (MBG) bagi balita, ibu hamil, dan ibu menyusui kembali dilaksanakan melalui Posyandu di Desa Darmakradenan, Kecamatan Ajibarang, Kabupaten Banyumas. Kegiatan ini bertujuan untuk meningkatkan asupan gizi masyarakat, khususnya bagi kelompok rentan.\r\n\r\nPada pelaksanaan terbaru, pembagian MBG menjangkau 10 Posyandu yang tersebar di desa tersebut. Sebelumnya, program ini hanya dilaksanakan di tiga Posyandu, namun kini diperluas agar lebih banyak masyarakat dapat menerima manfaat.\r\n\r\nBerdasarkan data yang dihimpun, jumlah penerima MBG mencapai 589 orang. Rinciannya meliputi 401 balita, 40 ibu hamil, 90 ibu menyusui, serta 58 anak non-PAUD.\r\n\r\nPenyaluran MBG dilakukan melalui Posyandu sehingga memudahkan pemantauan kesehatan penerima, terutama balita dan ibu hamil. Selain menerima makanan bergizi, para penerima juga mengikuti pemeriksaan kesehatan rutin yang dilakukan oleh kader Posyandu.\r\n\r\nDiharapkan melalui program ini, kebutuhan gizi balita, ibu hamil, dan ibu menyusui di Desa Darmakradenan dapat terpenuhi dengan lebih baik sehingga dapat mendukung tumbuh kembang anak serta kesehatan ibu. Program pembagian MBG melalui Posyandu juga diharapkan terus berlanjut dan menjangkau lebih banyak penerima di masa mendatang.', 'berita_1778056384.webp', '2026-05-06 08:33:04'),
(6, 'Pelayanan Jemput Bola IKD Digelar di RW 10 Darmakradenan', 'Pelayanan pendaftaran Identitas Kependudukan Digital (IKD) di Desa Darmakradenan berlangsung lancar pada Selasa (24/02/2026) pekan lalu.\r\n\r\nKegiatan tersebut dilaksanakan di RW 10 Gerumbul Kalibeber untuk memfasilitasi warga yang ingin mengaktifkan identitas kependudukan digital melalui telepon genggam.\r\n\r\nPetugas entri data IKD Desa Darmakradenan, Kristianto, melayani langsung warga yang datang untuk melakukan pendaftaran dan aktivasi aplikasi IKD. Warga terlihat antusias mengikuti pelayanan tersebut karena prosesnya dapat dilakukan melalui ponsel masing-masing.\r\n\r\n“Pelayanan di RW 10 dilaksanakan atas permintaan warga setempat. Lokasi pelayanan dipilih untuk memudahkan akses masyarakat yang terkendala transportasi menuju kantor desa.” Terangnya.\r\n\r\nKristianto juga mengungkapkan, Pemerintah Desa Darmakradenan telah menginformasikan program pendaftaran IKD melalui surat pemberitahuan yang dibagikan kepada warga melalui grup WhatsApp (WAG).\r\n\r\n“Kami sudah memberitahukan melalui surat yang ditunjukan melalui RT RW bagi warga yang belum melakukan pendaftaran IKD.” Pungkasnya.\r\n\r\nMenurut Kristianto, hingga saat ini capaian pendaftaran IKD di Desa Darmakradenan telah mencapai sekitar 60 persen dari total warga yang wajib memiliki identitas kependudukan digital. Capaian tersebut juga menempatkan Desa Darmakradenan pada peringkat pertama di Kecamatan Ajibarang dalam pelaksanaan program IKD.\r\n\r\nAyah dua anak ini berharap, seluruh warga Darmakradenan segera melakukan pendaftaran melalui aplikasi IKD. Warga juga diminta menyiapkan telepon genggam, nomor telepon aktif, dan alamat email untuk mempermudah proses registrasi.', 'berita_1778056444.webp', '2026-05-06 08:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `lembaga`
--

CREATE TABLE `lembaga` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jabatan` varchar(200) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lembaga`
--

INSERT INTO `lembaga` (`id`, `nama`, `jabatan`, `foto`, `urutan`, `tanggal`) VALUES
(1, 'KH. IMAM WS', 'Kepala Desa', '', 1, '2026-04-25 11:39:55'),
(2, 'AHMAD MIFTAH, S.A.P', 'Sekretaris Desa', '', 2, '2026-04-25 11:39:55'),
(3, 'SUKRON ABIDIN', 'Kaur Keuangan', '', 3, '2026-04-25 11:39:55'),
(4, 'JULEHAH, S.A.P', 'Kaur Perencanaan', '', 5, '2026-04-25 11:39:55'),
(5, 'ENDRO KUNCORO', 'Kasi Pelayanan', '', 7, '2026-04-25 11:39:55'),
(6, 'TRI SUSANTI', 'Kasi Kesejahteraan', '', 8, '2026-04-25 11:39:55'),
(7, 'KRISTIANTO', 'Kaur TU & Umum', '', 4, '2026-05-06 08:40:20'),
(8, 'ARIES WAHYU WICAKSONO, S. IKom', 'Kasi Pemerintahan', '', 6, '2026-05-06 08:41:22');

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `nik` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penduduk`
--

INSERT INTO `penduduk` (`nik`, `nama`, `jenis_kelamin`, `alamat`) VALUES
('3302010000000010', 'Nita Permata', 'P', 'RT 02 RW 03 Darmakradenan'),
('3302011111110001', 'Ahmad Hidayat', 'L', 'RT 01 RW 01 Darmakradenan'),
('3302012222220002', 'Budi Santoso', 'L', 'RT 02 RW 01 Darmakradenan'),
('3302013333330003', 'Candra Wijaya', 'L', 'RT 03 RW 02 Darmakradenan'),
('3302014444440004', 'Dedi Kurniawan', 'L', 'RT 01 RW 03 Darmakradenan'),
('3302015555550005', 'Eko Prasetyo', 'L', 'RT 02 RW 03 Darmakradenan'),
('3302016666660006', 'Siti Aminah', 'P', 'RT 01 RW 01 Darmakradenan'),
('3302017777770007', 'Rina Melati', 'P', 'RT 02 RW 01 Darmakradenan'),
('3302018888880008', 'Dewi Lestari', 'P', 'RT 03 RW 02 Darmakradenan'),
('3302019999990009', 'Ayu Wandira', 'P', 'RT 01 RW 03 Darmakradenan');

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('Masuk','Diproses','Selesai') DEFAULT 'Masuk',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `nama`, `isi`, `foto`, `status`, `tanggal`) VALUES
(6, 'Budi Santoso', 'Terjadi bencana longsor pada sore hari', 'pengaduan_1778590012_6a03213cdac79.webp', 'Masuk', '2026-05-12 12:46:52'),
(7, 'Nita Permata', 'Pembagian jatah MBG yang terlambat tidak sesuai dengan jadwal yang diberikan', 'pengaduan_1778590118_6a0321a625f56.webp', 'Masuk', '2026-05-12 12:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `potensi`
--

CREATE TABLE `potensi` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `judul` varchar(300) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `potensi`
--

INSERT INTO `potensi` (`id`, `kategori`, `judul`, `deskripsi`, `gambar`, `tanggal`) VALUES
(1, 'Pariwisata Alam &amp; Edukasi', 'Eksplorasi Gugusan Gua (Karst)', 'Darmakradenan berada di kawasan karst (batuan kapur) yang menciptakan jaringan gua alami yang unik. Masing-masing gua memiliki karakteristik berbeda:\r\n- Gua Lawa &amp;amp;amp;amp;amp; Gua Lawang: Menjadi habitat alami ribuan kelelawar. Lorong-lorongnya menawarkan pemandangan stalaktit dan stalagmit yang masih aktif.\r\n- Gua Srewiti: Dikenal dengan aksesnya yang menantang namun indah, sering menjadi incaran para pegiat susur gua (caving).\r\n- Gua Kemit &amp;amp;amp;amp;amp; Gua Sumur: Memiliki struktur vertikal yang unik. Dinamakan Gua Sumur karena bentuk lubang masuknya yang menyerupai sumur dalam, memerlukan peralatan khusus untuk memasukinya.\r\n- Gua Damar: Memiliki legenda lokal yang kuat dan sering dikaitkan dengan sejarah masa lalu desa, memberikan nuansa wisata religi dan sejarah selain keindahan alamnya.', 'potensi_1778065430_69fb2016c8114.jpg', '2026-04-25 11:39:55'),
(2, 'Sektor Pertanian &amp; Perkebunan', 'Lahan Pertanian &amp; Sistem Irigasi', 'Ketahanan pangan desa ini sangat bergantung pada manajemen air yang terintegrasi:\r\n- Irigasi Kali Pecang, Sepira, dan Kesal: Pemerintah desa melakukan betonisasi dan normalisasi pada jalur irigasi ini untuk memastikan distribusi air tetap stabil meskipun musim kemarau. Hal ini memungkinkan petani melakukan pola tanam yang lebih produktif (padi-padi-palawija).\r\n- Pemandangan Lanskap: Hamparan sawah di kaki perbukitan menciptakan view hijau yang estetik, yang kini mulai dimanfaatkan sebagai jalur trekking dan sepeda santai bagi wisatawan.', 'potensi_1778061290_69fb0feab28b8.jpeg', '2026-04-25 11:39:55'),
(3, 'Ekonomi Kreatif &amp; Kerajinan', 'Pengerajin Jala Ikan', 'Merupakan warisan turun-temurun. Meskipun alat tangkap modern banyak tersedia, jala rajutan tangan dari Darmakradenan dikenal lebih kuat dan awet. Wisatawan seringkali tertarik melihat proses merajut jala yang memerlukan konsentrasi dan keahlian khusus ini.', 'potensi_1778067517_69fb283db67a8.jpg', '2026-04-25 11:39:55'),
(4, 'Pariwisata Alam &amp; Edukasi', 'Wisata Edukasi Lebah Klanceng (Prawita Garden)', 'Prawita Garden bukan sekadar taman, melainkan pusat pembelajaran ekosistem:\r\n- Budidaya Tanaman Pakan: Pengunjung diajak melihat bagaimana warga menanam bunga-bunga khusus (seperti air mata pengantin) yang menjadi sumber makanan lebah.\r\n- Panen Madu Langsung: Lebah Klanceng (Trigona sp.) tidak memiliki sengat, sehingga aman bagi anak-anak. Pengunjung bisa mencoba menyedot madu langsung dari sarangnya menggunakan sedotan kecil.\r\n- Manfaat Kesehatan: Wisatawan diberikan edukasi mengenai kandungan propolis pada madu klanceng yang lebih tinggi dibandingkan madu biasa, menjadikannya oleh-oleh premium khas desa.', 'potensi_1778065684_69fb2114ba4f2.jpg', '2026-05-06 11:08:04'),
(5, 'Sektor Pertanian &amp; Perkebunan', 'Potensi Komoditas Perkebunan', 'Selain padi, sektor perkebunan menjadi tabungan jangka panjang warga:\r\n- Hutan Jati &amp; Karet: Banyak terdapat di area perbukitan. Getah karet menjadi penghasilan harian bagi sebagian warga, sementara kayu jati menjadi komoditas ekonomi bernilai tinggi.\r\n- Kelapa: Pohon kelapa tumbuh subur di sepanjang pemukiman dan ladang. Selain buahnya, air kelapa dan nira juga menjadi bagian dari pendukung ekonomi rumah tangga di Darmakradenan.', 'potensi_1778066158_69fb22eec9936.jpg', '2026-05-06 11:15:58'),
(6, 'Ekonomi Kreatif &amp; Kerajinan', 'Kerajinan Bordir', 'Berbeda dengan bordir mesin massal, beberapa kelompok pengrajin di desa ini masih mempertahankan kualitas jahitan yang rapi dan motif yang khas, sering dipesan untuk pakaian seragam atau busana formal.', 'potensi_1778067434_69fb27eae183c.webp', '2026-05-06 11:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `program_desa`
--

CREATE TABLE `program_desa` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(300) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('Perencanaan','Berjalan','Selesai') DEFAULT 'Perencanaan',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_desa`
--

INSERT INTO `program_desa` (`id`, `nama_program`, `deskripsi`, `status`, `tanggal`) VALUES
(1, 'Pavingisasi Jalan RT 03', 'Pemasangan paving block di jalan lingkungan RT 03 sepanjang 500 meter', 'Berjalan', '2026-04-25 11:39:55'),
(2, 'Posyandu Remaja', 'Program kesehatan remaja bulanan dengan pemeriksaan gratis', 'Berjalan', '2026-04-25 11:39:55'),
(3, 'Digitalisasi Administrasi Desa', 'Pengembangan sistem informasi desa berbasis web untuk pelayanan masyarakat', 'Selesai', '2026-04-25 11:39:55'),
(4, 'Pelatihan UMKM Digital', 'Pelatihan pemasaran online untuk pelaku UMKM desa', 'Perencanaan', '2026-04-25 11:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `surat`
--

CREATE TABLE `surat` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `jenis_surat` varchar(100) NOT NULL,
  `keperluan` text NOT NULL,
  `kode_pengajuan` varchar(20) NOT NULL,
  `status` enum('Diproses','Selesai','Ditolak') DEFAULT 'Diproses',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surat`
--

INSERT INTO `surat` (`id`, `nik`, `nama`, `jenis_surat`, `keperluan`, `kode_pengajuan`, `status`, `tanggal`) VALUES
(9, '3302010000000010', 'Nita Permata', 'Surat Keterangan Domisili', 'Persayatan Pembuatan KTP', 'SRT-20260512-C5479E', 'Diproses', '2026-05-12 12:43:56'),
(10, '3302011111110001', 'Ahmad Hidayat', 'Surat Keterangan Usaha', 'Pengajuan Peminjaman Usaha Bank', 'SRT-20260512-C97372', 'Diproses', '2026-05-12 12:44:44'),
(11, '3302012222220002', 'Budi Santoso', 'Surat Izin Keramaian', 'Mengadakan Keramaian Malam Hari', 'SRT-20260512-5D9F78', 'Diproses', '2026-05-12 12:45:25'),
(12, '3302013333330003', 'Candra Wijaya', 'Surat Pengantar SKCK', 'Pengantar Pembuatan SKCK', 'SRT-20260512-66A864', 'Diproses', '2026-05-12 12:45:58');

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id`, `nama`, `deskripsi`, `foto`, `kontak`, `tanggal`) VALUES
(1, 'Prawita Garden', 'Prawita Garden merupakan sebuah kebun penghasil buah dan peternakan madu. Prawita Garden telah menghasilkan aneka buah mulai dari jambu, anggur, cherry, durian, tin, zaitun, bidara, delima, siwak dan ada 60 varietas buah lainnya. Selain hasil buah, lahannya juga digunakan untuk pengembangan bibit tanaman buah yang sudah dikelola yaitu bibit tanaman buah jambu kristal, mangga dan durian.\r\n\r\nDari peternakan lebah yang dikelolanya telah diproduksi madu kemasan berupa madu tawon (raw honey), madu klanceng (trigona), madu lebah hutan (tawon gung), bee pollen, propolis, royal jelly dan teh madu klanceng. \r\n\r\nMadu Klenceng merupakan salah satu jenis madu yang namanya meroket sewaktu pandemi Covid-19. Pengunjung dapat mencobanya di sini. Pengunjung juga dapat belajar banyak hal dari sini, seperti cara budidaya, cara memanen dan sebagainya.', 'umkm_1778054909.jpg', '081234567890', '2026-04-25 11:39:55'),
(2, 'Di Musim Kemarau, Seorang Pemuda di Banyumas Meraup Cuan dari Pembuatan Layang-Layang', 'Musim kemarau mendatangkan rezeki tersendiri bagi pemuda bernama Anggit (23) asal Desa Darmakeradenan Ajibarang Banyumas Jawa Tengah. emuda yang hanya lulusan sekolah menegah pertama (SMP) itu sudah membuat layang-layang sejak tahun 2015 lalu hingga sekarang. Kebiasaanya membuat layangan begitu piawai, apalagi dalam membentuk layangan berbagai bentuk serta ukuran. \"Permintaan layangan saat kemarau melonjak dan beragam jenis, karakter dalam sehari bisa memproduksi 10 layangan. Harga per layangan dimulai antara 15 ribu sampai 150 ribu per buah. Untuk bahan terdiri dari lem kayu, plastik, bambu dan tali rafia,\"kata Anggit, perajin layangan itu, hari Selasa 20 Agustus 2024.', 'umkm_1778588923.jpg', '082134678900', '2026-04-25 11:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','staf','kepala_desa') DEFAULT 'staf',
  `nama_lengkap` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama_lengkap`, `created_at`) VALUES
(1, 'admin', '$2y$10$6fI2hNoaNmYh2aEolP5UdONJo.iG7IkUT9xUMWZalKNBVsb1HSBW.', 'super_admin', 'Administrator Desa', '2026-04-25 11:39:55'),
(2, 'staf_desa', '$2y$10$ebcBkyA2dwWbTigfGLDrLOrSiNDs/XpVwNPW3G92V7gV4s/nhaYTW', 'staf', 'Staf Pelayanan Desa', '2026-05-11 15:08:28'),
(3, 'kades', '$2y$10$xMRiM6uZhLIQ333T5T9OGeGCTAtGzKd795S9.UNPaUB8Te1Yu/lvS', 'kepala_desa', 'Kepala Desa', '2026-05-11 15:08:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apbdes`
--
ALTER TABLE `apbdes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lembaga`
--
ALTER TABLE `lembaga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`nik`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `potensi`
--
ALTER TABLE `potensi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_desa`
--
ALTER TABLE `program_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pengajuan` (`kode_pengajuan`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apbdes`
--
ALTER TABLE `apbdes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lembaga`
--
ALTER TABLE `lembaga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `potensi`
--
ALTER TABLE `potensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `program_desa`
--
ALTER TABLE `program_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `surat`
--
ALTER TABLE `surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
