<?php
// =====================================================
// START BUFFER CONTENT
// Semua output halaman dashboard ditampung dulu,
// lalu dikirim ke layout utama admin.
// =====================================================
ob_start();
?>

<?php
/** @var int $totalArmada */
/** @var int $totalBooking */
/** @var int $totalCustomer */
/** @var int $totalPendapatan */
/** @var array $bookingTerbaru */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Untuk mencegah karakter aneh / script masuk ke tampilan.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: FORMAT STATUS BOOKING
// Mengubah status database menjadi label dan warna badge.
// =====================================================
function dashboardStatusBadge($status)
{
    return match ($status) {
        'menunggu'     => 'warning',
        'dikonfirmasi' => 'info',
        'disewa'       => 'primary',
        'selesai'      => 'success',
        'dibatalkan'   => 'danger',
        default        => 'secondary'
    };
}

function dashboardStatusLabel($status)
{
    return match ($status) {
        'menunggu'     => 'Menunggu',
        'dikonfirmasi' => 'Dikonfirmasi',
        'disewa'       => 'On Progress',
        'selesai'      => 'Selesai',
        'dibatalkan'   => 'Dibatalkan',
        default        => '-'
    };
}
?>


<!-- =====================================================
     PAGE HEADING
     Judul utama halaman dashboard admin.
===================================================== -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>


<!-- =====================================================
     SECTION STATISTIC CARDS
     Menampilkan ringkasan utama:
     Total Armada, Total Pendapatan, Total Booking, Total Customer.
===================================================== -->
<div class="row">

    <!-- Card Total Armada -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Armada
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= e($totalArmada) ?>
                        </div>
                    </div>

                    <div class="col-auto">
                        <i class="fas fa-car fa-2x text-gray-300"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Pendapatan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Pendapatan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($totalPendapatan, 0, ',', '.') ?>
                        </div>
                    </div>

                    <div class="col-auto">
                        <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Booking -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Booking
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= e($totalBooking) ?>
                        </div>
                    </div>

                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Card Total Customer -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">

                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Customer
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= e($totalCustomer) ?>
                        </div>
                    </div>

                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>


<!-- =====================================================
     SECTION BOOKING TERBARU
     Menampilkan daftar booking terbaru agar admin cepat memantau transaksi.
===================================================== -->
<div class="card shadow mb-4">

    <!-- Header Card Booking Terbaru -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Booking Terbaru
        </h6>
    </div>

    <!-- Body Card Booking Terbaru -->
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered" id="dashboardBookingTable" width="100%" cellspacing="0">

                <!-- Header Tabel -->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Armada</th>
                        <th>Tipe</th>
                        <th>Tgl Pinjam</th>
                        <th>Jam</th>
                        <th>Tgl Kembali</th>
                        <th>Jam</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <!-- Isi Tabel -->
                <tbody>
                    <?php foreach ($bookingTerbaru as $i => $b): ?>
                        <?php
                        $badge = dashboardStatusBadge($b['status_booking'] ?? '');
                        $statusLabel = dashboardStatusLabel($b['status_booking'] ?? '');
                        $tipeCustomer = ($b['tipe_customer'] ?? 'WNI') === 'WNA' ? 'WNA' : 'WNI';
                        ?>
                        <tr>

                            <!-- Nomor Urut -->
                            <td><?= $i + 1 ?></td>

                            <!-- Nama Customer -->
                            <td><?= e($b['nama_cust']) ?></td>

                            <!-- Nama Armada -->
                            <td><?= e($b['nama_armada']) ?></td>

                            <!-- Tipe Customer WNI / WNA -->
                            <td><?= e($tipeCustomer) ?></td>

                            <!-- Tanggal Pinjam -->
                            <td>
                                <?= !empty($b['tgl_pinjam'])
                                    ? date('d M Y', strtotime($b['tgl_pinjam']))
                                    : '-' ?>
                            </td>

                            <!-- Jam Pengambilan -->
                            <td>
                                <?= !empty($b['jam_pengambilan'])
                                    ? date('H:i', strtotime($b['jam_pengambilan']))
                                    : '-' ?>
                            </td>

                            <!-- Tanggal Kembali -->
                            <td>
                                <?= !empty($b['tgl_kembali'])
                                    ? date('d M Y', strtotime($b['tgl_kembali']))
                                    : '-' ?>
                            </td>

                            <!-- Jam Pengembalian -->
                            <td>
                                <?= !empty($b['jam_pengembalian'])
                                    ? date('H:i', strtotime($b['jam_pengembalian']))
                                    : '-' ?>
                            </td>

                            <!-- Total Bayar -->
                            <td>
                                Rp <?= number_format($b['total_bayar'] ?? 0, 0, ',', '.') ?>
                            </td>

                            <!-- Status Booking -->
                            <td>
                                <span class="badge badge-<?= e($badge) ?>">
                                    <?= e($statusLabel) ?>
                                </span>
                            </td>

                            <!-- Tombol Detail -->
                            <td>
                                <a href="<?= BASE_URL ?>/booking/detail/<?= e($b['id_booking']) ?>"
                                    class="btn btn-sm btn-purple">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>

                    <!-- Jika Data Booking Kosong -->
                    <?php if (empty($bookingTerbaru)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted">
                                Belum ada data booking terbaru.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>


<?php
// =====================================================
// END BUFFER CONTENT
// =====================================================
$content = ob_get_clean();


// =====================================================
// SCRIPT HALAMAN DASHBOARD
// Mengaktifkan DataTables untuk tabel booking terbaru.
// =====================================================
$scripts = '
<script>
$(document).ready(function(){
    $("#dashboardBookingTable").DataTable({
        order: [],
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50]
    });
});
</script>
';


// =====================================================
// LOAD LAYOUT UTAMA ADMIN
// =====================================================
require_once BASE_PATH . '/app/views/layouts/main.php';
?>