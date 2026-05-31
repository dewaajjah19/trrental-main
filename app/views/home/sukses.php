<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success - TR Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/sukses.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/responsive.css?v=1">

</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="success-card">
                    <div class="icon-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3>Booking Successful!</h3>

                    <p>
                        Thank you! Your booking has been received. Our team will contact you via WhatsApp shortly to confirm your rental.
                    </p>

                    <p class="invoice-note">
                        Please download and save your invoice. This invoice will be used as your booking proof when picking up the vehicle.
                    </p>

                    <div class="success-actions">
                        <?php if (!empty($id_booking)): ?>
                            <a href="<?= BASE_URL ?>/home/invoice/<?= $id_booking ?>" class="btn-download-invoice">
                                <i class="fas fa-file-invoice mr-2"></i> Download Invoice
                            </a>
                        <?php endif; ?>

                        <!-- <a href="<?= BASE_URL ?>" class="btn-home-outline">
                            <i class="fas fa-home mr-2"></i> Back to Home
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>