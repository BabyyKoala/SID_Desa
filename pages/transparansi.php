<?php
require_once '../config/db.php';
$page_title = 'Transparansi Desa';
$tab = $_GET['tab'] ?? 'apbdes';
$tahun = (int)($_GET['tahun'] ?? date('Y'));

// Prepared Statement untuk keamanan SQL Injection
$stmt_p = $conn->prepare("SELECT * FROM apbdes WHERE kategori='Pendapatan' AND tahun=? ORDER BY id");
$stmt_p->bind_param("i", $tahun);
$stmt_p->execute();
$apbdes_pendapatan = $stmt_p->get_result();

$stmt_k = $conn->prepare("SELECT * FROM apbdes WHERE kategori='Pengeluaran' AND tahun=? ORDER BY id");
$stmt_k->bind_param("i", $tahun);
$stmt_k->execute();
$apbdes_pengeluaran = $stmt_k->get_result();

// Menghitung Total
$stmt_tp = $conn->prepare("SELECT SUM(jumlah) as t FROM apbdes WHERE kategori='Pendapatan' AND tahun=?");
$stmt_tp->bind_param("i", $tahun);
$stmt_tp->execute();
$total_p = $stmt_tp->get_result()->fetch_assoc()['t'] ?? 0;

$stmt_tk = $conn->prepare("SELECT SUM(jumlah) as t FROM apbdes WHERE kategori='Pengeluaran' AND tahun=?");
$stmt_tk->bind_param("i", $tahun);
$stmt_tk->execute();
$total_k = $stmt_tk->get_result()->fetch_assoc()['t'] ?? 0;

$tahun_list = $conn->query("SELECT DISTINCT tahun FROM apbdes ORDER BY tahun DESC");
$program_list = $conn->query("SELECT * FROM program_desa ORDER BY FIELD(status,'Berjalan','Perencanaan','Selesai'), tanggal DESC");

require_once '../config/header.php';

// Fungsi Badge yang dipercantik
function badgeProgram($s) {
    $map = [
        'Perencanaan' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
        'Berjalan'    => 'bg-blue-50 text-blue-700 border-blue-100',
        'Selesai'     => 'bg-green-50 text-green-700 border-green-100'
    ];
    $ic  = [
        'Perencanaan' => 'fa-hourglass-start',
        'Berjalan'    => 'fa-spinner fa-spin',
        'Selesai'     => 'fa-check-circle'
    ];
    $cls = $map[$s] ?? 'bg-gray-50 text-gray-600 border-gray-100';
    $icon = $ic[$s] ?? 'fa-info-circle';
    return "<span class='inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border $cls'><i class='fas $icon'></i> $s</span>";
}
?>

<div class="max-w-6xl mx-auto px-4 py-10 min-h-[70vh]">
    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="../index.php" class="hover:text-primary-600 transition">Beranda</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-gray-800 font-medium">Transparansi Desa</span>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Transparansi Desa</h1>
        <p class="text-gray-500 leading-relaxed">Wujud keterbukaan informasi publik terkait pengelolaan keuangan dan progres program kerja desa.</p>
    </div>

    <!-- Tab Navigation -->
    <div class="flex gap-2 overflow-x-auto pb-2 mb-8 scrollbar-hide">
        <a href="?tab=apbdes" class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition shadow-sm
            <?= $tab === 'apbdes' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-primary-50' ?>">
            <i class="fas fa-coins"></i> APBDes
        </a>
        <a href="?tab=program" class="flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition shadow-sm
            <?= $tab === 'program' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-primary-50' ?>">
            <i class="fas fa-tasks"></i> Program Desa
        </a>
    </div>

    <?php if($tab === 'apbdes'): ?>
        <!-- Filter Tahun -->
        <div class="flex flex-wrap items-center gap-4 mb-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-sm font-bold text-gray-700 flex items-center gap-2"><i class="fas fa-calendar-alt text-primary-500"></i> Pilih Tahun Anggaran:</span>
            <div class="flex gap-2">
                <?php if($tahun_list->num_rows > 0): ?>
                    <?php while($t = $tahun_list->fetch_assoc()): ?>
                    <a href="?tab=apbdes&tahun=<?= $t['tahun'] ?>" 
                       class="px-4 py-1.5 rounded-lg text-sm font-bold transition
                              <?= $tahun == $t['tahun'] ? 'bg-primary-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 border border-gray-100 hover:bg-gray-100' ?>">
                        <?= $t['tahun'] ?>
                    </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <span class="text-sm text-gray-400 italic">Data tahun belum tersedia</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white border-l-4 border-primary-500 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Pendapatan</div>
                    <div class="text-2xl font-black text-gray-800"><?= formatRupiah($total_p) ?></div>
                </div>
                <i class="fas fa-wallet text-3xl text-primary-100"></i>
            </div>
            <div class="bg-white border-l-4 border-orange-500 rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Total Pengeluaran</div>
                    <div class="text-2xl font-black text-gray-800"><?= formatRupiah($total_k) ?></div>
                </div>
                <i class="fas fa-shopping-cart text-3xl text-orange-100"></i>
            </div>
            <div class="bg-white border-l-4 <?= ($total_p - $total_k) >= 0 ? 'border-blue-500' : 'border-red-500' ?> rounded-2xl p-6 shadow-sm flex items-center justify-between">
                <div>
                    <div class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Sisa / Silpa</div>
                    <div class="text-2xl font-black <?= ($total_p - $total_k) >= 0 ? 'text-blue-600' : 'text-red-600' ?>"><?= formatRupiah($total_p - $total_k) ?></div>
                </div>
                <i class="fas fa-hand-holding-usd text-3xl <?= ($total_p - $total_k) >= 0 ? 'text-blue-50' : 'text-red-50' ?>"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Tabel Pendapatan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-primary-600 text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2"><i class="fas fa-arrow-circle-down"></i> Pendapatan Desa</h2>
                    <span class="text-xs bg-primary-700 px-2 py-1 rounded font-bold"><?= $tahun ?></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            <?php if($apbdes_pendapatan->num_rows > 0): ?>
                                <?php while($row = $apbdes_pendapatan->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($row['uraian']) ?></td>
                                    <td class="px-6 py-4 font-bold text-primary-600 text-right whitespace-nowrap"><?= formatRupiah($row['jumlah']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">Data pendapatan belum tersedia</td></tr>
                            <?php endif; ?>
                            <tr class="bg-primary-50/50">
                                <td class="px-6 py-4 font-black text-primary-800 uppercase text-xs tracking-wider">Total Pendapatan</td>
                                <td class="px-6 py-4 font-black text-primary-800 text-right"><?= formatRupiah($total_p) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Pengeluaran -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-orange-500 text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2"><i class="fas fa-arrow-circle-up"></i> Pengeluaran Desa</h2>
                    <span class="text-xs bg-orange-600 px-2 py-1 rounded font-bold"><?= $tahun ?></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            <?php if($apbdes_pengeluaran->num_rows > 0): ?>
                                <?php while($row = $apbdes_pengeluaran->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($row['uraian']) ?></td>
                                    <td class="px-6 py-4 font-bold text-orange-600 text-right whitespace-nowrap"><?= formatRupiah($row['jumlah']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">Data pengeluaran belum tersedia</td></tr>
                            <?php endif; ?>
                            <tr class="bg-orange-50/50">
                                <td class="px-6 py-4 font-black text-orange-800 uppercase text-xs tracking-wider">Total Pengeluaran</td>
                                <td class="px-6 py-4 font-black text-orange-800 text-right"><?= formatRupiah($total_k) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php elseif($tab === 'program'): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php if($program_list && $program_list->num_rows > 0): ?>
                <?php while($row = $program_list->fetch_assoc()): ?>
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <h3 class="font-bold text-gray-800 text-lg group-hover:text-primary-600 transition-colors"><?= htmlspecialchars($row['nama_program']) ?></h3>
                        <?= badgeProgram($row['status']) ?>
                    </div>
                    <?php if($row['deskripsi']): ?>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4"><?= htmlspecialchars($row['deskripsi']) ?></p>
                    <?php endif; ?>
                    <div class="flex items-center text-xs text-gray-400 font-medium pt-4 border-t border-gray-50">
                        <i class="far fa-calendar-alt mr-2 text-primary-400"></i>
                        Pelaksanaan: <?= formatTanggal($row['tanggal']) ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <i class="fas fa-tasks text-5xl text-gray-200 mb-4"></i>
                    <p class="text-gray-400 italic">Belum ada data program kerja desa yang dipublikasikan.</p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../config/footer.php'; ?>