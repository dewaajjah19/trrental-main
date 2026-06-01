<?php
// =====================================================
// START BUFFER CONTENT
// Semua isi halaman ditampung dulu sebelum dipanggil ke layout admin.
// =====================================================
ob_start();
?>

<?php
/** @var array $customer */
/** @var array $bookings */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan agar data yang tampil lebih aman dari karakter khusus.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: URL DOKUMEN CUSTOMER
// Fungsi ini digunakan agar halaman customer/detail bisa membaca
// 2 jenis sumber dokumen:
//
// 1. Localhost:
//    Isi database hanya nama file, contoh:
//    foto_ktp_xxxxx.jpg
//    Maka URL akan diarahkan ke:
//    /public/assets/img/dokumen/foto_ktp_xxxxx.jpg
//
// 2. Vercel Blob:
//    Isi database sudah berupa URL lengkap, contoh:
//    https://xxxxx.public.blob.vercel-storage.com/...
//    Maka URL langsung dipakai tanpa ditambah BASE_URL.
//
// Ini memperbaiki error 404 di Vercel yang sebelumnya terjadi karena
// URL Blob malah ditambah path lokal public/assets/img/dokumen.
// =====================================================
if (!function_exists('customerDokumenUrl')) {
    function customerDokumenUrl($file)
    {
        if (empty($file)) {
            return '';
        }

        // Kalau file sudah berupa URL, berarti dokumen berasal dari Vercel Blob.
        if (preg_match('/^https?:\/\//i', $file)) {
            return $file;
        }

        // Kalau bukan URL, berarti file berasal dari folder lokal localhost.
        return BASE_URL . '/public/assets/img/dokumen/' . rawurlencode($file);
    }
}

// =====================================================
// HELPER: NAMA DOKUMEN CUSTOMER
// Fungsi ini digunakan untuk menampilkan nama file yang lebih rapi.
//
// Jika file dari localhost:
//    tampilkan nama file asli.
//
// Jika file dari Vercel Blob:
//    ambil nama file terakhir dari URL saja,
//    supaya halaman admin tidak menampilkan URL panjang.
// =====================================================
if (!function_exists('customerDokumenName')) {
    function customerDokumenName($file)
    {
        if (empty($file)) {
            return '-';
        }

        // Kalau file berupa URL Blob, ambil nama file dari path URL.
        if (preg_match('/^https?:\/\//i', $file)) {
            $path = parse_url($file, PHP_URL_PATH);
            return basename($path);
        }

        return $file;
    }
}

// =====================================================
// HELPER: FORMAT STATUS BOOKING
// Mengubah status database menjadi label dan warna badge.
// =====================================================
if (!function_exists('customerStatusBadgeClass')) {
    function customerStatusBadgeClass($status)
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
}

if (!function_exists('customerStatusBadgeLabel')) {
    function customerStatusBadgeLabel($status)
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
}

// =====================================================
// DATA CUSTOMER & BOOKING TERAKHIR
// Booking terakhir digunakan untuk menampilkan dokumen customer terbaru.
// =====================================================
$customerId = str_pad($customer['id_cust'], 3, '0', STR_PAD_LEFT);
$lastBooking = !empty($bookings) ? $bookings[0] : null;

$isForeignCustomer = ($lastBooking['tipe_customer'] ?? 'WNI') === 'WNA';

$customerTypeLabel = $isForeignCustomer ? 'Foreign Citizen' : 'Indonesian Citizen';
?>


<!-- =====================================================
     PAGE TITLE
     Judul halaman detail data customer.
===================================================== -->
<div class="page-title">Detail Data Customer</div>


<!-- =====================================================
     BREADCRUMB
     Navigasi kecil untuk menunjukkan posisi halaman admin.
===================================================== -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/customer">Data Customer</a>
        </li>
        <li class="breadcrumb-item active">Detail Data Customer</li>
    </ol>
</nav>


<!-- =====================================================
     ALERT SUCCESS
     Menampilkan pesan sukses jika ada perubahan data customer.
===================================================== -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-1"></i>
        <?= e($_SESSION['success']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<!-- =====================================================
     ACTION BUTTONS
     Tombol kembali, ubah data, dan hapus customer.
===================================================== -->
<div class="d-flex justify-content-between align-items-center mb-4">

    <!-- Tombol Kembali -->
    <a href="<?= BASE_URL ?>/customer" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Data Customer
    </a>

    <!-- Tombol Ubah dan Hapus -->
    <div>
        <a href="<?= BASE_URL ?>/customer/edit/<?= e($customer['id_cust']) ?>"
            class="btn btn-purple btn-sm mr-2">
            <i class="fas fa-edit mr-1"></i> Ubah Data
        </a>

        <a href="javascript:void(0)"
            class="btn btn-danger btn-sm"
            onclick="confirmDelete('<?= BASE_URL ?>/customer/delete/<?= e($customer['id_cust']) ?>', 'Customer <?= e($customer['nama_cust']) ?>')">
            <i class="fas fa-trash mr-1"></i> Hapus
        </a>
    </div>

</div>


<!-- =====================================================
     HEADER CUSTOMER
     Menampilkan nama customer dan ID customer.
===================================================== -->
<div class="table-card mb-4 d-flex justify-content-between align-items-center"
    style="background:#f0eaf8;">

    <!-- Nama Customer -->
    <h5 class="mb-0 font-weight-800">
        <?= e($customer['nama_cust']) ?>
    </h5>

    <!-- ID Customer -->
    <span class="text-muted small">
        ID Customer : <?= $customerId ?>
    </span>

</div>


<!-- =====================================================
     SECTION INFORMASI CUSTOMER DAN DOKUMEN TERBARU
===================================================== -->
<div class="row">

    <!-- =================================================
         CARD INFORMASI CUSTOMER
         Menampilkan data dasar customer.
    ================================================== -->
    <div class="col-md-4 mb-4">
        <div class="table-card h-100">

            <h6 class="font-weight-700 mb-4 text-center" style="font-weight:700">
                Informasi Customer
            </h6>

            <table class="table table-borderless mb-0">

                <!-- Nama Customer -->
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #f5f5f5; padding:10px 0">
                        <div style="color:#888; font-size:.82rem">Name</div>
                        <div style="font-weight:600">
                            <?= e($customer['nama_cust']) ?>
                        </div>
                    </td>
                </tr>

                <!-- Nomor WhatsApp -->
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #f5f5f5; padding:10px 0">
                        <div style="color:#888; font-size:.82rem">Whatsapp</div>
                        <div style="font-weight:600">
                            <?= e($customer['no_tlp']) ?>
                        </div>
                    </td>
                </tr>

                <!-- Negara Asal -->
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #f5f5f5; padding:10px 0">
                        <div style="color:#888; font-size:.82rem">Country of Origin</div>
                        <div style="font-weight:600">
                            <?= e($customer['country_origin']) ?>
                        </div>
                    </td>
                </tr>

                <!-- Tipe Customer dari Booking Terakhir -->
                <tr>
                    <td colspan="2" style="border-bottom:1px solid #f5f5f5; padding:10px 0">
                        <div style="color:#888; font-size:.82rem">Customer Type</div>
                        <div style="font-weight:600">
                            <?= $lastBooking ? e($customerTypeLabel) : '-' ?>
                        </div>
                    </td>
                </tr>

                <!-- Alamat Customer -->
                <tr>
                    <td colspan="2" style="padding:10px 0">
                        <div style="color:#888; font-size:.82rem">Address</div>
                        <div style="font-weight:600">
                            <?= e($customer['alamat']) ?>
                        </div>
                    </td>
                </tr>

            </table>

        </div>
    </div>


    <!-- =================================================
         SECTION DOKUMEN DARI BOOKING TERAKHIR
         WNI: Identity Card
         WNA: Driving License, Identity/Passport, Flight Ticket, Hotel Booking

         Catatan fix:
         Bagian ini sekarang memakai customerDokumenUrl() dan customerDokumenName().
         Tujuannya agar dokumen dari localhost dan Vercel Blob sama-sama bisa tampil.
    ================================================== -->
    <?php if ($lastBooking): ?>

        <?php
        if ($isForeignCustomer) {
            $docs = [
                [
                    'label' => 'Driving License',
                    'file'  => $lastBooking['foto_sim'] ?? null,
                    'icon'  => 'fa-id-card'
                ],
                [
                    'label' => 'Identity / Passport',
                    'file'  => $lastBooking['foto_ktp'] ?? null,
                    'icon'  => 'fa-address-card'
                ],
                [
                    'label' => 'Flight Ticket',
                    'file'  => $lastBooking['foto_tiket'] ?? null,
                    'icon'  => 'fa-plane'
                ],
                [
                    'label' => 'Hotel Booking',
                    'file'  => $lastBooking['foto_hotel'] ?? null,
                    'icon'  => 'fa-hotel'
                ],
            ];
        } else {
            $docs = [
                [
                    'label' => 'Identity Card',
                    'file'  => $lastBooking['foto_ktp'] ?? null,
                    'icon'  => 'fa-address-card'
                ],
            ];
        }
        ?>

        <?php foreach ($docs as $doc): ?>
            <?php
            if (empty($doc['file'])) {
                continue;
            }

            // URL dokumen yang akan dipakai untuk preview gambar dan tombol lihat.
            // Bisa berupa URL Blob atau URL file lokal.
            $docUrl = customerDokumenUrl($doc['file']);

            // Nama dokumen yang ditampilkan di card.
            // Kalau Blob URL, yang tampil hanya nama file terakhir agar tidak terlalu panjang.
            $docName = customerDokumenName($doc['file']);
            ?>

            <!-- Card Dokumen -->
            <div class="col-md-4 mb-4">
                <div class="table-card text-center h-100">

                    <!-- Icon Dokumen -->
                    <div style="
                        width:54px;
                        height:54px;
                        border-radius:50%;
                        background:#F3EEFF;
                        color:#5B2D8E;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        margin:0 auto 14px;
                        font-size:1.3rem;
                    ">
                        <i class="fas <?= e($doc['icon']) ?>"></i>
                    </div>

                    <!-- Judul Dokumen -->
                    <h6 class="font-weight-700 mb-2 text-center" style="font-weight:700">
                        <?= e($doc['label']) ?>
                    </h6>

                    <!-- Keterangan Dokumen -->
                    <p class="text-muted small mb-3">
                        Dokumen dari booking terakhir customer.
                    </p>

                    <!-- Preview Dokumen -->
                    <img src="<?= e($docUrl) ?>"
                        style="width:100%; height:130px; object-fit:cover; border-radius:10px; margin-bottom:10px">

                    <!-- Nama File -->
                    <div class="small text-muted mb-3" style="word-break:break-word;">
                        <?= e($docName) ?>
                    </div>

                    <!-- Tombol Lihat Dokumen -->
                    <a href="<?= e($docUrl) ?>"
                        target="_blank"
                        class="btn btn-purple btn-sm w-100">
                        <i class="fas fa-eye mr-1"></i> Lihat Dokumen
                    </a>

                </div>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>


<!-- =====================================================
     SECTION RIWAYAT BOOKING CUSTOMER
     Menampilkan semua riwayat booking dari customer tersebut.
===================================================== -->
<?php if (!empty($bookings)): ?>
    <div class="table-card mt-2">

        <!-- Judul Riwayat Booking -->
        <h6 class="font-weight-700 mb-4" style="font-weight:700">
            Riwayat Booking
        </h6>

        <div class="table-responsive">

            <table class="table" id="customerBookingHistory">

                <!-- Header Tabel Riwayat -->
                <thead>
                    <tr>
                        <th>No. Booking</th>
                        <th>Armada</th>
                        <th>Tipe</th>
                        <th>Tgl Pinjam</th>
                        <th>Jam</th>
                        <th>Tgl Kembali</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <!-- Isi Tabel Riwayat -->
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                        <?php
                        $badgeClass = customerStatusBadgeClass($b['status_booking']);
                        $badgeLabel = customerStatusBadgeLabel($b['status_booking']);
                        $tipeCustomer = ($b['tipe_customer'] ?? 'WNI') === 'WNA' ? 'WNA' : 'WNI';
                        ?>

                        <tr>

                            <!-- Nomor Booking -->
                            <td><?= str_pad($b['id_booking'], 3, '0', STR_PAD_LEFT) ?></td>

                            <!-- Nama Armada -->
                            <td><?= e($b['nama_armada']) ?></td>

                            <!-- Tipe Customer -->
                            <td><?= e($tipeCustomer) ?></td>

                            <!-- Tanggal Pinjam -->
                            <td><?= date('d F Y', strtotime($b['tgl_pinjam'])) ?></td>

                            <!-- Jam Pengambilan -->
                            <td>
                                <?= !empty($b['jam_pengambilan'])
                                    ? date('H:i', strtotime($b['jam_pengambilan']))
                                    : '-' ?>
                            </td>

                            <!-- Tanggal Kembali -->
                            <td><?= date('d F Y', strtotime($b['tgl_kembali'])) ?></td>

                            <!-- Jam Pengembalian -->
                            <td>
                                <?= !empty($b['jam_pengembalian'])
                                    ? date('H:i', strtotime($b['jam_pengembalian']))
                                    : '-' ?>
                            </td>

                            <!-- Total Bayar -->
                            <td>
                                Rp <?= number_format($b['total_bayar'], 0, ',', '.') ?>
                            </td>

                            <!-- Status Booking -->
                            <td>
                                <span class="status-badge <?= e($badgeClass) ?>">
                                    <?= e($badgeLabel) ?>
                                </span>
                            </td>

                            <!-- Tombol Detail Booking -->
                            <td>
                                <a href="<?= BASE_URL ?>/booking/detail/<?= e($b['id_booking']) ?>"
                                    class="btn-detail">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

<?php else: ?>

    <!-- Tampilan Jika Customer Belum Pernah Booking -->
    <div class="table-card mt-2 text-center text-muted">
        Customer ini belum memiliki riwayat booking.
    </div>

<?php endif; ?>


<?php
// =====================================================
// END BUFFER CONTENT
// =====================================================
$content = ob_get_clean();


// =====================================================
// SCRIPT DATATABLES RIWAYAT BOOKING
// Mengaktifkan tabel riwayat booking jika tabel tersedia.
// =====================================================
$scripts = '
<script>
$(document).ready(function(){
    if ($("#customerBookingHistory").length) {
        $("#customerBookingHistory").DataTable({
            order: [],
            pageLength: 5,
            lengthMenu: [5, 10, 25]
        });
    }
});
</script>
';


// =====================================================
// LOAD LAYOUT ADMIN
// =====================================================
require_once BASE_PATH . '/app/views/layouts/main.php';
?>