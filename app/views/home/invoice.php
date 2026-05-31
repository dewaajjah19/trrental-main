<?php

/** @var array $booking */

$isAdminView =
    (isset($_GET['from']) && $_GET['from'] === 'admin') ||
    isset($_SESSION['staff_id']) ||
    isset($_SESSION['staff_name']);

$bookingNumber = str_pad($booking['id_booking'], 4, '0', STR_PAD_LEFT);

$pickupMethod = $booking['metode_pengambilan'] === 'ambil_sendiri'
    ? 'Pick up at the office'
    : 'Delivery to your location';

$customerType = ($booking['tipe_customer'] ?? 'WNI') === 'WNA'
    ? 'Foreign Citizen'
    : 'Indonesian Citizen';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= $bookingNumber ?> - TR Rental</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/invoice.css?v=1">
</head>

<body>

    <div class="invoice-page">

        <div class="invoice-actions no-print">

            <?php if (!$isAdminView): ?>
                <a href="<?= BASE_URL ?>" class="btn-back-home">
                    <i class="fas fa-home mr-1"></i> Back to Home
                </a>
            <?php endif; ?>

            <button onclick="window.print()" class="btn-print <?= $isAdminView ? 'admin-print-only' : '' ?>">
                <i class="fas fa-download mr-1"></i> Download / Print Invoice
            </button>

        </div>

        <div class="invoice-card">

            <div class="invoice-header">
                <div>
                    <img src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="TR Rental" class="invoice-logo">
                    <p class="invoice-brand-desc">Motorbike & Car Rental in Bali</p>
                </div>

                <div class="invoice-title-box">
                    <h1>INVOICE</h1>
                    <p>#<?= $bookingNumber ?></p>
                </div>
            </div>

            <div class="invoice-alert">
                Please save this invoice and show it to our staff when picking up the vehicle or during delivery confirmation.
            </div>

            <div class="invoice-section">
                <h3>Customer Information</h3>

                <div class="info-grid">
                    <div>
                        <span>Name</span>
                        <strong><?= $booking['nama_cust'] ?></strong>
                    </div>

                    <div>
                        <span>WhatsApp</span>
                        <strong><?= $booking['no_tlp'] ?></strong>
                    </div>

                    <div>
                        <span>Country of Origin</span>
                        <strong><?= $booking['country_origin'] ?></strong>
                    </div>

                    <div>
                        <span>Customer Type</span>
                        <strong><?= $customerType ?></strong>
                    </div>

                    <div class="full">
                        <span>Address</span>
                        <strong><?= $booking['alamat'] ?></strong>
                    </div>
                </div>
            </div>

            <div class="invoice-section">
                <h3>Booking Details</h3>

                <table class="invoice-table">
                    <tr>
                        <td>Vehicle</td>
                        <td><?= $booking['nama_armada'] ?> - <?= $booking['tipe_armada'] ?></td>
                    </tr>

                    <tr>
                        <td>Rent Start</td>
                        <td>
                            <?= date('d F Y', strtotime($booking['tgl_pinjam'])) ?>
                            <?php if (!empty($booking['jam_pengambilan'])): ?>
                                at <?= date('H:i', strtotime($booking['jam_pengambilan'])) ?>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Rent Finish</td>
                        <td>
                            <?= date('d F Y', strtotime($booking['tgl_kembali'])) ?>
                            <?php if (!empty($booking['jam_pengembalian'])): ?>
                                at <?= date('H:i', strtotime($booking['jam_pengembalian'])) ?>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Long Lease</td>
                        <td><?= $booking['jumlah_hari'] ?> day(s)</td>
                    </tr>

                    <tr>
                        <td>Pickup Method</td>
                        <td><?= $pickupMethod ?></td>
                    </tr>

                    <?php if (!empty($booking['titik_jemput'])): ?>
                        <tr>
                            <td>Pickup Address</td>
                            <td><?= $booking['titik_jemput'] ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($booking['alamat_pengantaran'])): ?>
                        <tr>
                            <td>Drop Address</td>
                            <td><?= $booking['alamat_pengantaran'] ?></td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td>Payment Method</td>
                        <td><?= ucfirst($booking['metode_pembayaran']) ?></td>
                    </tr>

                    <tr>
                        <td>Status</td>
                        <td><?= ucfirst($booking['status_booking']) ?></td>
                    </tr>
                </table>
            </div>

            <div class="invoice-total">
                <span>Total Payment</span>
                <strong>Rp <?= number_format($booking['total_bayar'], 0, ',', '.') ?></strong>
            </div>

            <div class="invoice-footer-note">
                This invoice is generated automatically by TR Rental booking system.
            </div>

        </div>
    </div>

</body>

</html>