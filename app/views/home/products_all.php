<?php

/** @var array $armada */
/** @var string $jenis */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan agar data dari database aman saat ditampilkan di HTML.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: URL GAMBAR ARMADA
// Digunakan agar halaman products_all bisa membaca gambar armada dari:
//
// 1. Localhost:
//    gambar_armada = armada_xxxxx.jpg
//    dibaca dari /public/assets/img/armada/
//
// 2. Vercel Blob:
//    gambar_armada = https://...blob.vercel-storage.com/...
//    langsung dipakai sebagai URL gambar.
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

$isMobil = strtolower($jenis) === 'mobil';
$pageTitle = $isMobil ? 'Choose Car' : 'Choose Motorbike';
$icon = $isMobil ? 'fa-car' : 'fa-motorcycle';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> - TR Rental</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/navbar.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/products-all.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/footer.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/responsive.css?v=1">
</head>

<body>

    <?php
    $activePage = 'products';
    require_once BASE_PATH . '/app/views/layouts/navbar-user.php';
    ?>

    <section class="products-all-section">
        <div class="circle circle-main"></div>
        <div class="circle circle-soft"></div>

        <div class="container">

            <div class="products-all-top">
                <a href="<?= BASE_URL ?>/home/products" class="back-link">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>

                <div class="all-search-wrapper">
                    <input type="text" id="allSearchInput" placeholder="Search vehicle...">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="row products-grid-row">
                <?php if (empty($armada)): ?>
                    <div class="col-12 text-center text-muted py-5">
                        Belum ada armada tersedia.
                    </div>
                <?php else: ?>
                    <?php foreach ($armada as $a): ?>
                        <div class="col-lg-4 col-md-6 vehicle-item"
                            data-search="<?= e(strtolower($a['nama_armada'] ?? '')) ?>">

                            <div class="vehicle-card" onclick="window.location='<?= BASE_URL ?>/home/booking/<?= e($a['id_armada']) ?>'">
                                <div class="vehicle-img-wrap">
                                    <?php if (!empty($a['gambar_armada'])): ?>
                                        <img src="<?= e(armadaImageUrl($a['gambar_armada'])) ?>"
                                            alt="<?= e($a['nama_armada']) ?>">
                                    <?php else: ?>
                                        <span class="no-img">
                                            <i class="fas <?= e($icon) ?>"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="vehicle-info">
                                    <h6><?= e($a['nama_armada']) ?> - <?= e($a['tipe_armada']) ?></h6>
                                    <span class="price-badge">
                                        Rp <?= number_format($a['harga_sewa_perhari'], 0, ',', '.') ?>/Day
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <?php require_once BASE_PATH . '/app/views/layouts/footer-user.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#allSearchInput').on('input', function() {
            const q = $(this).val().toLowerCase().trim();

            if (q === '') {
                $('.vehicle-item').show();
                $('.empty-search').remove();
                return;
            }

            $('.vehicle-item').each(function() {
                const keywords = ($(this).attr('data-search') || '').toLowerCase();
                $(this).toggle(keywords.includes(q));
            });

            const visibleItems = $('.vehicle-item:visible').length;

            $('.empty-search').remove();

            if (visibleItems === 0) {
                $('.products-grid-row').append(`
                <div class="col-12 empty-search text-center text-muted py-5">
                    No vehicles found.
                </div>
            `);
            }
        });
    </script>
</body>

</html>