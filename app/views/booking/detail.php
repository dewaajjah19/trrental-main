<?php

/** @var array $booking */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan agar data yang tampil di HTML aman dari karakter khusus.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: URL DOKUMEN CUSTOMER
// Digunakan agar dokumen booking bisa tampil dari 2 sumber:
//
// 1. Localhost:
//    foto_ktp / foto_sim / foto_tiket / foto_hotel berisi nama file.
//    File dibaca dari /public/assets/img/dokumen/
//
// 2. Vercel Blob:
//    field dokumen berisi URL lengkap.
//    URL langsung dipakai tanpa ditambah path lokal.
// =====================================================
if (!function_exists('dokumenUrl')) {
    function dokumenUrl($file)
    {
        if (empty($file)) {
            return '';
        }

        // Kalau file sudah berupa URL, berarti dari Vercel Blob
        if (preg_match('/^https?:\/\//i', $file)) {
            return $file;
        }

        // Kalau bukan URL, berarti file lokal localhost
        return BASE_URL . '/public/assets/img/dokumen/' . rawurlencode($file);
    }
}

// =====================================================
// HELPER: NAMA DOKUMEN CUSTOMER
// Digunakan agar kalau file berasal dari Vercel Blob,
// yang tampil hanya nama file terakhir, bukan URL panjang.
// =====================================================
if (!function_exists('dokumenName')) {
    function dokumenName($file)
    {
        if (empty($file)) {
            return '-';
        }

        if (preg_match('/^https?:\/\//i', $file)) {
            $path = parse_url($file, PHP_URL_PATH);
            return basename($path);
        }

        return $file;
    }
}

// =====================================================
// HELPER: URL GAMBAR ARMADA
// Digunakan agar gambar armada bisa tampil dari 2 sumber:
//
// 1. Localhost:
//    gambar_armada = armada_xxxxx.jpg
//    dibaca dari /public/assets/img/armada/
//
// 2. Vercel Blob:
//    gambar_armada = https://...blob.vercel-storage.com/...
//    langsung dipakai sebagai URL gambar.
//
// Ini fix utama agar gambar armada yang diupload dari admin Vercel
// tetap tampil di detail booking admin.
// =====================================================
if (!function_exists('armadaImageUrl')) {
    function armadaImageUrl($file)
    {
        if (empty($file)) {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $file)) {
            return $file;
        }

        return BASE_URL . '/public/assets/img/armada/' . rawurlencode($file);
    }
}

// =====================================================
// DATA FORMATTER / LABEL UTAMA
// =====================================================
$bookingNo = str_pad($booking['id_booking'], 3, '0', STR_PAD_LEFT);

$isForeignCustomer = ($booking['tipe_customer'] ?? 'WNI') === 'WNA';

$customerTypeLabel = $isForeignCustomer ? 'Foreign Citizen' : 'Indonesian Citizen';

$pickupMethodLabel = ($booking['metode_pengambilan'] ?? '') === 'ambil_sendiri'
    ? 'Pick up at the office'
    : 'Delivery to your location';

$paymentMethodLabel = ucfirst($booking['metode_pembayaran'] ?? '-');

if (($booking['metode_pembayaran'] ?? '') === 'tunai') {
    $paymentMethodLabel = 'Cash';
}

if (($booking['metode_pembayaran'] ?? '') === 'transfer') {
    $paymentMethodLabel = 'Transfer';
}

ob_start();
?>

<!-- =====================================================
     PAGE TITLE & BREADCRUMB
===================================================== -->
<div class="page-title">Detail Booking</div>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/booking">Booking</a>
        </li>
        <li class="breadcrumb-item active">Detail Booking</li>
    </ol>
</nav>


<!-- =====================================================
     FLASH MESSAGE SUCCESS
===================================================== -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= e($_SESSION['success']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<!-- =====================================================
     HEADER DETAIL BOOKING + FORM UBAH STATUS
===================================================== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-800 mb-0">
        Booking No. <?= $bookingNo ?>
    </h4>

    <form method="POST"
        action="<?= BASE_URL ?>/booking/detail/<?= e($booking['id_booking']) ?>"
        class="d-flex align-items-center gap-2">

        <label class="mr-2 mb-0 small font-weight-600">Ubah Status</label>

        <select name="status_booking" class="form-control form-control-sm mr-2" style="width:160px; border-radius:8px">
            <option value="menunggu" <?= $booking['status_booking'] === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
            <option value="dikonfirmasi" <?= $booking['status_booking'] === 'dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
            <option value="disewa" <?= $booking['status_booking'] === 'disewa' ? 'selected' : '' ?>>On Progress</option>
            <option value="selesai" <?= $booking['status_booking'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            <option value="dibatalkan" <?= $booking['status_booking'] === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
        </select>

        <button type="submit" class="btn btn-purple btn-sm">Simpan</button>
    </form>
</div>


<!-- =====================================================
     SECTION UTAMA: INFORMASI CUSTOMER & KONFIRMASI BOOKING
===================================================== -->
<div class="row">

    <!-- =================================================
         CARD KIRI: INFORMASI CUSTOMER
    ================================================== -->
    <div class="col-md-6">
        <div class="table-card h-100">
            <h6 class="font-weight-700 mb-4" style="font-weight:700; font-size:1rem">
                Informasi Customer
            </h6>

            <table class="table table-borderless mb-0">

                <!-- Nama Customer -->
                <tr>
                    <td style="width:40%; color:#888; font-size:.88rem; padding:10px 0">Name</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($booking['nama_cust']) ?>
                    </td>
                </tr>

                <!-- Nomor WhatsApp -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Whatsapp</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($booking['no_tlp']) ?>
                    </td>
                </tr>

                <!-- Negara Asal -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Country of Origin</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($booking['country_origin']) ?>
                    </td>
                </tr>

                <!-- Tipe Customer WNI / WNA -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Customer Type</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($customerTypeLabel) ?>
                    </td>
                </tr>

                <!-- Alamat Customer -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Address</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($booking['alamat']) ?>
                    </td>
                </tr>

                <!-- Metode Pengambilan -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Pickup Method</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($pickupMethodLabel) ?>
                    </td>
                </tr>

                <!-- Metode Pembayaran -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Payment Method</td>
                    <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                        <?= e($paymentMethodLabel) ?>
                    </td>
                </tr>

                <!-- Titik Jemput: hanya muncul jika delivery -->
                <?php if (!empty($booking['titik_jemput'])): ?>
                    <tr style="border-top:1px solid #f5f5f5">
                        <td style="color:#888; font-size:.88rem; padding:10px 0">Pickup Address</td>
                        <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                            <?= e($booking['titik_jemput']) ?>
                        </td>
                    </tr>
                <?php endif; ?>

                <!-- Alamat Pengantaran: hanya muncul jika delivery -->
                <?php if (!empty($booking['alamat_pengantaran'])): ?>
                    <tr style="border-top:1px solid #f5f5f5">
                        <td style="color:#888; font-size:.88rem; padding:10px 0">Drop Address</td>
                        <td style="font-weight:600; font-size:.92rem; padding:10px 0">
                            <?= e($booking['alamat_pengantaran']) ?>
                        </td>
                    </tr>
                <?php endif; ?>

            </table>
        </div>
    </div>


    <!-- =================================================
         CARD KANAN: KONFIRMASI BOOKING
    ================================================== -->
    <div class="col-md-6">
        <div class="table-card h-100">
            <h6 class="font-weight-700 mb-4" style="font-weight:700; font-size:1rem">
                Konfirmasi Booking
            </h6>

            <!-- Ringkasan Armada -->
            <div class="d-flex align-items-center mb-4 p-3"
                style="background:#f8f9fc; border-radius:12px;">

                <?php if (!empty($booking['gambar_armada'])): ?>
                    <img src="<?= e(armadaImageUrl($booking['gambar_armada'])) ?>"
                        style="width:90px; height:70px; object-fit:cover; border-radius:8px; margin-right:16px"
                        alt="<?= e($booking['nama_armada']) ?>">
                <?php else: ?>
                    <div style="width:90px; height:70px; background:#eee; border-radius:8px; margin-right:16px; display:flex; align-items:center; justify-content:center">
                        <i class="fas fa-car text-muted"></i>
                    </div>
                <?php endif; ?>

                <div>
                    <div style="font-size:.8rem; color:#999">
                        No. Booking: <?= $bookingNo ?>
                    </div>
                    <div style="font-weight:700; font-size:1.05rem">
                        <?= e($booking['nama_armada']) ?>
                    </div>
                </div>
            </div>

            <!-- Detail Booking -->
            <table class="table table-borderless mb-0">

                <!-- Waktu Pesanan Dibuat -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Booking Created</td>
                    <td style="font-weight:700; font-size:.92rem; padding:10px 0; text-align:right">
                        <?php if (!empty($booking['created_at'])): ?>
                            <?= date('d F Y', strtotime($booking['created_at'])) ?>
                            <br>
                            <small class="text-muted">
                                <?= date('H:i', strtotime($booking['created_at'])) ?> WITA
                            </small>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Nama Armada -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Type of Vehicle</td>
                    <td style="font-weight:700; font-size:.92rem; padding:10px 0; text-align:right">
                        <?= e($booking['nama_armada']) ?>
                    </td>
                </tr>

                <!-- Tanggal dan Jam Pengambilan -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Rent Start From</td>
                    <td style="font-weight:700; font-size:.92rem; padding:10px 0; text-align:right">
                        <?= date('d F Y', strtotime($booking['tgl_pinjam'])) ?>
                        <?php if (!empty($booking['jam_pengambilan'])): ?>
                            <br>
                            <small class="text-muted">
                                <?= date('H:i', strtotime($booking['jam_pengambilan'])) ?> WITA
                            </small>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Tanggal dan Jam Pengembalian -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Rent Finish</td>
                    <td style="font-weight:700; font-size:.92rem; padding:10px 0; text-align:right">
                        <?= date('d F Y', strtotime($booking['tgl_kembali'])) ?>
                        <?php if (!empty($booking['jam_pengembalian'])): ?>
                            <br>
                            <small class="text-muted">
                                <?= date('H:i', strtotime($booking['jam_pengembalian'])) ?> WITA
                            </small>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Durasi Sewa -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Long Lease</td>
                    <td style="font-weight:700; font-size:.92rem; padding:10px 0; text-align:right">
                        <?= e($booking['jumlah_hari']) ?> Hari
                    </td>
                </tr>

                <!-- Metode Pengambilan -->
                <tr style="border-top:1px solid #f5f5f5">
                    <td style="color:#888; font-size:.88rem; padding:10px 0">Pickup Method</td>
                    <td style="font-weight:700; font-size:.92rem; padding:10px 0; text-align:right">
                        <?= e($pickupMethodLabel) ?>
                    </td>
                </tr>

                <!-- Total Pembayaran -->
                <tr style="border-top:2px solid #f0f0f0">
                    <td style="font-weight:700; padding:12px 0">Total Payment</td>
                    <td style="font-weight:800; font-size:1rem; padding:12px 0; text-align:right; color:var(--primary)">
                        Rp <?= number_format($booking['total_bayar'], 0, ',', '.') ?>
                    </td>
                </tr>

            </table>
        </div>
    </div>

</div>


<!-- =====================================================
     SECTION DOKUMEN CUSTOMER
     WNI: hanya Identity Card
     WNA: Driving License, Identity/Passport, Flight Ticket, Hotel Booking
===================================================== -->
<?php if ($booking['foto_sim'] || $booking['foto_ktp'] || $booking['foto_tiket'] || $booking['foto_hotel']): ?>

    <!-- Judul Section Dokumen -->
    <div class="mt-4 mb-3">
        <h5 style="font-weight:800; margin-bottom:6px;">
            <?= $isForeignCustomer ? 'Foreign Customer Documents' : 'Indonesian Customer Document' ?>
        </h5>
        <p class="text-muted mb-0" style="font-size:.9rem;">
            Dokumen yang diunggah customer untuk kebutuhan verifikasi booking.
        </p>
    </div>

    <!-- Daftar Dokumen -->
    <div class="row">
        <?php
        if ($isForeignCustomer) {
            $docs = [
                [
                    'label' => 'Driving License',
                    'file'  => $booking['foto_sim'],
                    'icon'  => 'fa-id-card'
                ],
                [
                    'label' => 'Identity / Passport',
                    'file'  => $booking['foto_ktp'],
                    'icon'  => 'fa-passport'
                ],
                [
                    'label' => 'Flight Ticket',
                    'file'  => $booking['foto_tiket'],
                    'icon'  => 'fa-plane'
                ],
                [
                    'label' => 'Hotel Booking',
                    'file'  => $booking['foto_hotel'],
                    'icon'  => 'fa-hotel'
                ],
            ];
        } else {
            $docs = [
                [
                    'label' => 'Identity Card',
                    'file'  => $booking['foto_ktp'],
                    'icon'  => 'fa-address-card'
                ],
            ];
        }

        foreach ($docs as $doc):
            if (empty($doc['file'])) continue;
            $docUrl = dokumenUrl($doc['file']);
            $docName = dokumenName($doc['file']);
        ?>
            <div class="col-md-3 mb-4">
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

                    <!-- Nama Dokumen -->
                    <h6 class="mb-2" style="font-weight:800;">
                        <?= e($doc['label']) ?>
                    </h6>

                    <!-- Preview Dokumen -->
                    <img src="<?= e($docUrl) ?>"
                        style="width:100%; height:120px; object-fit:cover; border-radius:8px; margin-bottom:12px">

                    <!-- Nama File Dokumen -->
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
    </div>

<?php endif; ?>


<!-- =====================================================
     ACTION BUTTONS: KEMBALI, LIHAT INVOICE, HAPUS BOOKING
===================================================== -->
<div class="mt-2 mb-4">

    <!-- Tombol Kembali ke Data Booking -->
    <a href="<?= BASE_URL ?>/booking" class="btn btn-outline-secondary btn-sm mr-2">
        <i class="fas fa-arrow-left mr-1"></i> Kembali
    </a>

    <!-- Tombol Lihat Invoice -->
    <a href="<?= BASE_URL ?>/home/invoice/<?= e($booking['id_booking']) ?>?from=admin"
        target="_blank"
        class="btn btn-purple btn-sm mr-2">
        <i class="fas fa-file-invoice mr-1"></i> Lihat Invoice
    </a>

    <!-- Tombol Hapus Booking -->
    <a href="javascript:void(0)"
        class="btn btn-danger btn-sm"
        onclick="confirmDelete('<?= BASE_URL ?>/booking/delete/<?= e($booking['id_booking']) ?>', 'Booking No. <?= $bookingNo ?>')">
        <i class="fas fa-trash mr-1"></i> Hapus Booking
    </a>

</div>


<!-- =====================================================
     LOAD LAYOUT UTAMA ADMIN
===================================================== -->
<?php
$content = ob_get_clean();
require_once BASE_PATH . '/app/views/layouts/main.php';
?>