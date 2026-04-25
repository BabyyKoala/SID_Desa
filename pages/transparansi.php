<?php
require_once '../config/db.php';
$page_title = 'Transparansi Desa';
$tab = $_GET['tab'] ?? 'apbdes';
$tahun = (int)($_GET['tahun'] ?? date('Y'));

$tahun_list = $conn->query("SELECT DISTINCT tahun FROM apbdes ORDER BY tahun DESC");
$apbdes_pendapatan = $conn->query("SELECT * FROM apbdes WHERE kategori='Pendapatan' AND tahun=$tahun ORDER BY id");
$apbdes_pengeluaran = $conn->query("SELECT * FROM apbdes WHERE kategori='Pengeluaran' AND tahun=$tahun ORDER BY id");
$total_p = $conn->query("SELECT SUM(jumlah) as t FROM apbdes WHERE kategori='Pendapatan' AND tahun=$tahun")->fetch_assoc()['t'] ?? 0;
$total_k = $conn->query("SELECT SUM(jumlah) as t FROM apbdes WHERE kategori='Pengeluaran' AND tahun=$tahun")->fetch_assoc()['t'] ?? 0;

$program_list = $conn->query("SELECT * FROM program_desa ORDER BY FIELD(status,'Berjalan','Perencanaan','Selesai'), tanggal DESC");

require_once '../config/header.php';

function badgeProgram($s) {
    $map = ['Perencanaan'=>'badge-perencanaan','Berjalan'=>'badge-berjalan','Selesai'=>'badge-selesai'];
    $ic  = ['Perencanaan'=>'fa-hourglass-start','Berjalan'=>'fa-spinner','Selesai'=>'fa-check-circle'];
    $cls = $map[$s] ?? 'bg-gray-100 text-gray-600';
    return "<span class='inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold $cls'><i class='fas {$ic[$s]}'></i> $s</span>";
}
?>

<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600">Beranda</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Transparansi Desa</span>
    </div>

    <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Transparansi Desa</h1>
    <p class="text-gray-500 mb-8">Informasi keuangan dan program desa secara terbuka</p>

    <!-- Tabs -->
    <div class="flex gap-3 mb-8">
        <a href="?tab=apbdes" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition
           <?= $tab === 'apbdes' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-primary-50' ?>">
            <i class="fas fa-coins"></i> APBDes
        </a>
        <a href="?tab=program" class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition
           <?= $tab === 'program' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-primary-50' ?>">
            <i class="fas fa-tasks"></i> Program Desa
        </a>
    </div>

    <?php if($tab === 'apbdes'): ?>
    <!-- Filter Tahun -->
    <div class="flex items-center gap-3 mb-6">
        <span class="text-sm font-semibold text-gray-600">Tahun:</span>
        <div class="flex gap-2">
            <?php while($t = $tahun_list->fetch_assoc()): ?>
            <a href="?tab=apbdes&tahun=<?= $t['tahun'] ?>" 
               class="px-4 py-1.5 rounded-lg text-sm font-semibold transition
                      <?= $tahun == $t['tahun'] ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' ?>">
                <?= $t['tahun'] ?>
            </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-primary-50 border border-primary-200 rounded-xl p-5">
            <div class="text-xs text-primary-600 font-semibold uppercase mb-1">Total Pendapatan</div>
            <div class="text-2xl font-extrabold text-primary-800"><?= formatRupiah($total_p) ?></div>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
            <div class="text-xs text-orange-600 font-semibold uppercase mb-1">Total Pengeluaran</div>
            <div class="text-2xl font-extrabold text-orange-800"><?= formatRupiah($total_k) ?></div>
        </div>
        <div class="<?= ($total_p - $total_k) >= 0 ? 'bg-blue-50 border-blue-200' : 'bg-red-50 border-red-200' ?> border rounded-xl p-5">
            <div class="text-xs <?= ($total_p - $total_k) >= 0 ? 'text-blue-600' : 'text-red-600' ?> font-semibold uppercase mb-1">Sisa Anggaran</div>
            <div class="text-2xl font-extrabold <?= ($total_p - $total_k) >= 0 ? 'text-blue-800' : 'text-red-800' ?>"><?= formatRupiah($total_p - $total_k) ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Pendapatan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-primary-600 text-white px-6 py-4 flex items-center gap-2">
                <i class="fas fa-arrow-down"></i>
                <h2 class="font-bold">Pendapatan <?= $tahun ?></h2>
            </div>
            <div class="p-0">
                <table class="w-full text-sm">
                    <tbody>
                        <?php while($row = $apbdes_pendapatan->fetch_assoc()): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($row['uraian']) ?></td>
                            <td class="px-5 py-3 font-semibold text-primary-700 text-right whitespace-nowrap"><?= formatRupiah($row['jumlah']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr class="bg-primary-50">
                            <td class="px-5 py-3 font-bold text-primary-800">Total</td>
                            <td class="px-5 py-3 font-extrabold text-primary-800 text-right"><?= formatRupiah($total_p) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-orange-500 text-white px-6 py-4 flex items-center gap-2">
                <i class="fas fa-arrow-up"></i>
                <h2 class="font-bold">Pengeluaran <?= $tahun ?></h2>
            </div>
            <div class="p-0">
                <table class="w-full text-sm">
                    <tbody>
                        <?php while($row = $apbdes_pengeluaran->fetch_assoc()): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-5 py-3 text-gray-600"><?= htmlspecialchars($row['uraian']) ?></td>
                            <td class="px-5 py-3 font-semibold text-orange-700 text-right whitespace-nowrap"><?= formatRupiah($row['jumlah']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr class="bg-orange-50">
                            <td class="px-5 py-3 font-bold text-orange-800">Total</td>
                            <td class="px-5 py-3 font-extrabold text-orange-800 text-right"><?= formatRupiah($total_k) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php elseif($tab === 'program'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <?php while($row = $program_list->fetch_assoc()): ?>
        <div class="card-hover bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between gap-3 mb-3">
                <h3 class="font-bold text-gray-800"><?= htmlspecialchars($row['nama_program']) ?></h3>
                <?= badgeProgram($row['status']) ?>
            </div>
            <?php if($row['deskripsi']): ?>
            <p class="text-gray-500 text-sm"><?= htmlspecialchars($row['deskripsi']) ?></p>
            <?php endif; ?>
            <div class="text-xs text-gray-400 mt-3"><i class="far fa-calendar mr-1"></i><?= formatTanggal($row['tanggal']) ?></div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once '../config/footer.php'; ?>
