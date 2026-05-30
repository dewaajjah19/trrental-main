<?php ob_start(); ?>

<?php
/** @var string $title */
/** @var array $filter */
/** @var int $tahun */
/** @var int $totalPendapatan */
/** @var array $bookings */
/** @var array $armadaTersering */
/** @var array $pendapatanBulanan */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: FORMAT STATUS BOOKING
// =====================================================
function getStatusBadgeClass($status)
{
    return match ($status) {
        'menunggu'     => 'badge-menunggu',
        'dikonfirmasi' => 'badge-dikonfirmasi',
        'disewa'       => 'badge-disewa',
        'selesai'      => 'badge-selesai',
        'dibatalkan'   => 'badge-dibatalkan',
        default        => 'badge-menunggu'
    };
}

function getStatusBadgeLabel($status)
{
    return match ($status) {
        'menunggu'     => 'Menunggu',
        'dikonfirmasi' => 'Dikonfirmasi',
        'disewa'       => 'On Progress',
        'selesai'      => 'Done',
        'dibatalkan'   => 'Dibatalkan',
        default        => '-'
    };
}

// =====================================================
// HELPER: FORMAT CUSTOMER TYPE
// =====================================================
function getCustomerTypeLabel($type)
{
    return ($type ?? 'WNI') === 'WNA' ? 'WNA' : 'WNI';
}
?>


<!-- =====================================================
     PAGE TITLE
===================================================== -->
<div class="page-title">Laporan</div>
<div class="page-subtitle">Melihat performa bisnis penyewaan kendaraan.</div>


<!-- =====================================================
     SECTION FILTER LAPORAN
     Digunakan untuk memfilter data berdasarkan tanggal, tahun grafik, dan kegiatan
===================================================== -->
<div class="table-card mb-4">
    <h6 class="font-weight-700 mb-3" style="font-weight:700">Filter Laporan</h6>

    <form method="GET" action="<?= BASE_URL ?>/laporan">
        <div class="row align-items-end">

            <!-- Filter Tanggal Awal -->
            <div class="col-md-2">
                <label class="small font-weight-600">Tanggal Awal</label>
                <input type="date"
                    name="tgl_awal"
                    class="form-control form-control-sm"
                    value="<?= e($filter['tgl_awal'] ?? '') ?>">
            </div>

            <!-- Filter Tanggal Akhir -->
            <div class="col-md-2">
                <label class="small font-weight-600">Tanggal Akhir</label>
                <input type="date"
                    name="tgl_akhir"
                    class="form-control form-control-sm"
                    value="<?= e($filter['tgl_akhir'] ?? '') ?>">
            </div>

            <!-- Filter Tahun Grafik -->
            <div class="col-md-2">
                <label class="small font-weight-600">Tahun Grafik</label>
                <input type="number"
                    name="tahun"
                    class="form-control form-control-sm"
                    value="<?= e($tahun) ?>"
                    min="2020"
                    max="2030">
            </div>

            <!-- Filter Kegiatan -->
            <div class="col-md-4">
                <label class="small font-weight-600 d-block">Kegiatan</label>

                <div class="btn-group" role="group">
                    <?php foreach (['semua' => 'Semua', 'penyewaan' => 'Penyewaan', 'pendapatan' => 'Pendapatan'] as $val => $label): ?>
                        <button type="submit"
                            name="kegiatan"
                            value="<?= e($val) ?>"
                            class="btn btn-sm <?= ($filter['kegiatan'] ?? 'semua') === $val ? 'btn-purple' : 'btn-outline-secondary' ?>">
                            <?= e($label) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tombol Terapkan dan Reset -->
            <div class="col-md-2">
                <button type="submit" class="btn btn-purple btn-sm mr-2">
                    <i class="fas fa-filter"></i> Terapkan
                </button>

                <a href="<?= BASE_URL ?>/laporan" class="btn btn-sm btn-outline-secondary">
                    Reset
                </a>
            </div>

        </div>
    </form>
</div>


<!-- =====================================================
     SECTION RINGKASAN STATISTIK
     Menampilkan total pendapatan, total transaksi, dan rata-rata transaksi
===================================================== -->
<div class="row mb-4">

    <!-- Total Pendapatan -->
    <div class="col-md-4">
        <div class="stat-card" style="background: var(--primary); color:#fff">
            <div style="font-size:.85rem; opacity:.85">Total Pendapatan (Filter)</div>
            <div style="font-size:1.6rem; font-weight:800; margin-top:8px">
                Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col-md-4">
        <div class="stat-card">
            <div style="font-size:.85rem; color:#999">Total Transaksi</div>
            <div style="font-size:1.6rem; font-weight:800; margin-top:8px; color:#333">
                <?= count($bookings) ?>
            </div>
        </div>
    </div>

    <!-- Rata-rata per Transaksi -->
    <div class="col-md-4">
        <div class="stat-card">
            <div style="font-size:.85rem; color:#999">Rata-rata per Transaksi</div>
            <div style="font-size:1.6rem; font-weight:800; margin-top:8px; color:#333">
                Rp <?= count($bookings) > 0 ? number_format($totalPendapatan / count($bookings), 0, ',', '.') : 0 ?>
            </div>
        </div>
    </div>

</div>


<!-- =====================================================
     SECTION GRAFIK DAN KENDARAAN PALING SERING DISEWA
===================================================== -->
<div class="row mb-4">

    <!-- Grafik Pendapatan Bulanan -->
    <div class="col-md-7">
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="font-weight-700 mb-0" style="font-weight:700">
                    Grafik Pendapatan <?= e($tahun) ?>
                </h6>
            </div>

            <canvas id="chartPendapatan" height="120"></canvas>
        </div>
    </div>

    <!-- Tabel Kendaraan Paling Sering Disewa -->
    <div class="col-md-5">
        <div class="table-card">
            <h6 class="font-weight-700 mb-4" style="font-weight:700">
                Kendaraan Paling Sering Disewa
            </h6>

            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Kendaraan</th>
                        <th>Total Disewa</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($armadaTersering as $i => $a): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= e($a['nama_armada']) ?></td>
                            <td><?= e($a['total_disewa']) ?>x</td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($armadaTersering)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Belum ada data
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>


<!-- =====================================================
     SECTION TABEL DATA BOOKING
     Menampilkan data booking terbaru dan tombol export PDF/Excel
===================================================== -->
<div class="table-card">

    <!-- Header Tabel + Tombol Export -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="font-weight-700 mb-0" style="font-weight:700">
            Data Booking Terbaru
        </h6>

        <div>
            <!-- Tombol Export PDF -->
            <button type="button" id="btnExportPDF" class="btn btn-sm btn-danger mr-2">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </button>

            <!-- Tombol Export Excel -->
            <button type="button" id="btnExportExcel" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="table-responsive">
        <table class="table" id="laporanTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Armada</th>
                    <th>Tipe</th>
                    <th>Tgl Pinjam</th>
                    <th>Jam</th>
                    <th></th>
                    <th>Tgl Kembali</th>
                    <th>Jam</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($bookings as $b): ?>
                    <?php
                    $statusClass = getStatusBadgeClass($b['status_booking']);
                    $statusLabel = getStatusBadgeLabel($b['status_booking']);
                    $customerType = getCustomerTypeLabel($b['tipe_customer'] ?? 'WNI');
                    ?>
                    <tr>
                        <!-- ID Booking -->
                        <td><?= str_pad($b['id_booking'], 3, '0', STR_PAD_LEFT) ?></td>

                        <!-- Nama Customer -->
                        <td><?= e($b['nama_cust']) ?></td>

                        <!-- Nama Armada -->
                        <td><?= e($b['nama_armada']) ?></td>

                        <!-- Tipe Customer WNI/WNA -->
                        <td><?= e($customerType) ?></td>

                        <!-- Tanggal Pinjam -->
                        <td><?= date('d F Y', strtotime($b['tgl_pinjam'])) ?></td>

                        <!-- Jam Pengambilan -->
                        <td>
                            <?= !empty($b['jam_pengambilan']) ? date('H:i', strtotime($b['jam_pengambilan'])) : '-' ?>
                        </td>

                        <!-- Panah -->
                        <td><i class="fas fa-arrow-right text-muted"></i></td>

                        <!-- Tanggal Kembali -->
                        <td><?= date('d F Y', strtotime($b['tgl_kembali'])) ?></td>

                        <!-- Jam Pengembalian -->
                        <td>
                            <?= !empty($b['jam_pengembalian']) ? date('H:i', strtotime($b['jam_pengembalian'])) : '-' ?>
                        </td>

                        <!-- Total Bayar -->
                        <td>
                            Rp <?= number_format($b['total_bayar'] ?? 0, 0, ',', '.') ?>
                        </td>

                        <!-- Status Booking -->
                        <td>
                            <span class="status-badge <?= e($statusClass) ?>">
                                <?= e($statusLabel) ?>
                            </span>
                        </td>

                        <!-- Tombol Detail -->
                        <td>
                            <a href="<?= BASE_URL ?>/booking/detail/<?= e($b['id_booking']) ?>"
                                class="btn-detail">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted">
                            Belum ada data booking.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </div>
</div>


<?php
// =====================================================
// DATA GRAFIK PENDAPATAN DALAM FORMAT JSON
// =====================================================
$pendapatanJson = json_encode(array_values($pendapatanBulanan));

$periodeAwal  = e($filter['tgl_awal'] ?? '-');
$periodeAkhir = e($filter['tgl_akhir'] ?? '-');
$tahunGrafik  = e($tahun);


// =====================================================
// SCRIPT HALAMAN LAPORAN
// - DataTables
// - Chart.js
// - Export Excel
// - Export PDF
// =====================================================
$content = ob_get_clean();

$scripts = <<<SCRIPT
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Library Export Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Library Export PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script>
$(document).ready(function(){

    // =====================================================
    // INIT DATATABLES
    // =====================================================
    const laporanDataTable = $("#laporanTable").DataTable({
        order: [],
        pageLength: 10
    });


    // =====================================================
    // INIT CHART PENDAPATAN
    // =====================================================
    const ctx = document.getElementById('chartPendapatan').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: $pendapatanJson,
                borderColor: '#5B2D8E',
                backgroundColor: 'rgba(91,45,142,.1)',
                borderWidth: 2.5,
                pointBackgroundColor: '#5B2D8E',
                pointRadius: 4,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + (val / 1000) + 'k'
                    },
                    grid: { color: '#f0f0f0' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });


    // =====================================================
    // HELPER: AMBIL DATA TABEL UNTUK EXPORT
    // Mengambil semua data dari DataTables, bukan hanya halaman pagination aktif
    // =====================================================
    function getExportRows() {
        const rows = [];

        laporanDataTable.rows({ search: 'applied' }).every(function(){
            const rowNode = this.node();
            const cells = rowNode.querySelectorAll('td');

            if (cells.length < 12) return;

            rows.push([
                cells[0].innerText.trim(),   // ID
                cells[1].innerText.trim(),   // Customer
                cells[2].innerText.trim(),   // Armada
                cells[3].innerText.trim(),   // Tipe Customer
                cells[4].innerText.trim(),   // Tgl Pinjam
                cells[5].innerText.trim(),   // Jam Ambil
                cells[7].innerText.trim(),   // Tgl Kembali
                cells[8].innerText.trim(),   // Jam Kembali
                cells[9].innerText.trim(),   // Total
                cells[10].innerText.trim()   // Status
            ]);
        });

        return rows;
    }


    // =====================================================
    // EXPORT EXCEL
    // =====================================================
    $("#btnExportExcel").on("click", function(){
        const rows = getExportRows();

        if (rows.length === 0) {
            alert("Tidak ada data laporan untuk diexport.");
            return;
        }

        const worksheetData = [
            [
                "ID",
                "Customer",
                "Armada",
                "Tipe Customer",
                "Tanggal Pinjam",
                "Jam Ambil",
                "Tanggal Kembali",
                "Jam Kembali",
                "Total Bayar",
                "Status"
            ],
            ...rows
        ];

        const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
        const workbook = XLSX.utils.book_new();

        XLSX.utils.book_append_sheet(workbook, worksheet, "Laporan Booking");

        XLSX.writeFile(workbook, "laporan-booking-tr-rental.xlsx");
    });


    // =====================================================
    // EXPORT PDF
    // =====================================================
    $("#btnExportPDF").on("click", function(){
        const rows = getExportRows();

        if (rows.length === 0) {
            alert("Tidak ada data laporan untuk diexport.");
            return;
        }

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF("landscape");

        doc.setFontSize(16);
        doc.text("Laporan Booking TR Rental", 14, 15);

        doc.setFontSize(10);
        doc.text("Periode: {$periodeAwal} s/d {$periodeAkhir}", 14, 22);
        doc.text("Tahun Grafik: {$tahunGrafik}", 14, 28);
        doc.autoTable({
            startY: 35,
            head: [[
                "ID",
                "Customer",
                "Armada",
                "Tipe",
                "Tgl Pinjam",
                "Jam Ambil",
                "Tgl Kembali",
                "Jam Kembali",
                "Total",
                "Status"
            ]],
            body: rows,
            styles: {
                fontSize: 8,
                cellPadding: 3
            },
            headStyles: {
                fillColor: [91, 45, 142],
                textColor: 255
            },
            margin: {
                left: 10,
                right: 10
            }
        });

        doc.save("laporan-booking-tr-rental.pdf");
    });

});
</script>
SCRIPT;

require_once BASE_PATH . '/app/views/layouts/main.php';
?>