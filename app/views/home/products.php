<?php

/** @var array $armada */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - TR Rental</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/navbar.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/products.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/footer.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/responsive.css?v=1">
</head>

<body>

    <?php
    $activePage = 'products';
    require_once BASE_PATH . '/app/views/layouts/navbar-user.php';
    ?>

    <!-- Hero -->
    <section class="hero-products text-center">
        <div class="container">
            <h1>What We Bring</h1>
            <p>Making it easier to find rides you need, whenever you need them.</p>

            <div class="search-wrapper">
                <input type="text" id="searchInput" placeholder="Explore Vehicle">
                <i class="fas fa-search"></i>
            </div>

            <div id="emptySearchMessage" class="empty-search-message">
                <h5>No vehicles found.</h5>
                <p>Please try another keyword.</p>
            </div>
        </div>
    </section>
    </section>

    <div class="right-soft-circle"></div>

    <!-- Products -->
    <section class="category-section">
        <div class="container">

            <?php
            $cars = array_filter($armada, function ($a) {
                return strtolower($a['jenis_armada'] ?? '') === 'mobil';
            });

            $bikes = array_filter($armada, function ($a) {
                return strtolower($a['jenis_armada'] ?? '') === 'motor';
            });
            ?>

            <!-- Mobil -->
            <div class="category-header car-header">
                <button class="btn-category">
                    <i class="fas fa-car mr-2"></i>Choose Car
                </button>

                <a href="<?= BASE_URL ?>/home/productsAll/mobil" class="see-all">See All &rsaquo;</a>
            </div>

            <div class="row product-list" id="carList">
                <?php if (empty($cars)): ?>
                    <div class="col-12 text-center text-muted py-4">
                        Belum ada armada mobil tersedia.
                    </div>
                <?php else: ?>
                    <?php foreach ($cars as $a): ?>
                        <div class="col-lg-4 col-md-6 vehicle-item"
                            data-search="<?= htmlspecialchars(strtolower($a['nama_armada'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="vehicle-card" onclick="window.location='<?= BASE_URL ?>/home/booking/<?= $a['id_armada'] ?>'">
                                <div class="vehicle-img-wrap">
                                    <?php if ($a['gambar_armada']): ?>
                                        <img src="<?= BASE_URL ?>/public/assets/img/armada/<?= $a['gambar_armada'] ?>"
                                            alt="<?= $a['nama_armada'] ?>">
                                    <?php else: ?>
                                        <span class="no-img"><i class="fas fa-car"></i></span>
                                    <?php endif; ?>
                                </div>

                                <div class="vehicle-info">
                                    <h6><?= $a['nama_armada'] ?> - <?= $a['tipe_armada'] ?></h6>
                                    <span class="price-badge">
                                        Rp <?= number_format($a['harga_sewa_perhari'], 0, ',', '.') ?>/Day
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Motor -->
            <div class="motor-area">
                <span class="motor-circle-main"></span>
                <span class="motor-circle-soft"></span>

                <div class="category-header motor-header">
                    <button class="btn-category">
                        <i class="fas fa-motorcycle mr-2"></i>Choose Motorbike
                    </button>

                    <a href="<?= BASE_URL ?>/home/productsAll/motor" class="see-all">See All &rsaquo;</a>
                </div>

                <div class="row product-list" id="bikeList">
                    <?php if (empty($bikes)): ?>
                        <div class="col-12 text-center text-muted py-4">
                            Belum ada armada motor tersedia.
                        </div>
                    <?php else: ?>
                        <?php foreach ($bikes as $a): ?>
                            <div class="col-lg-4 col-md-6 vehicle-item"
                                data-search="<?= htmlspecialchars(strtolower($a['nama_armada'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <div class="vehicle-card" onclick="window.location='<?= BASE_URL ?>/home/booking/<?= $a['id_armada'] ?>'">
                                    <div class="vehicle-img-wrap">
                                        <?php if ($a['gambar_armada']): ?>
                                            <img src="<?= BASE_URL ?>/public/assets/img/armada/<?= $a['gambar_armada'] ?>"
                                                alt="<?= $a['nama_armada'] ?>">
                                        <?php else: ?>
                                            <span class="no-img"><i class="fas fa-motorcycle"></i></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="vehicle-info">
                                        <h6><?= $a['nama_armada'] ?> - <?= $a['tipe_armada'] ?></h6>
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
        </div>
    </section>
    <?php require_once BASE_PATH . '/app/views/layouts/footer-user.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#searchInput').on('input', function() {
            const q = $(this).val().toLowerCase().trim();

            // Reset dulu setiap kali user mengetik
            $('#emptySearchMessage').hide();

            // Penting: tampilkan section dulu sebelum filtering
            $('.category-section').show();
            $('.car-header, #carList').show();
            $('.motor-header, #bikeList').show();

            // Kalau input kosong, tampilkan semua data
            if (q === '') {
                $('.vehicle-item').show();
                $('#emptySearchMessage').hide();
                return;
            }

            let visibleCars = 0;
            let visibleBikes = 0;

            // Filter manual dan hitung hasil cocok
            $('.vehicle-item').each(function() {
                const keywords = ($(this).attr('data-search') || '').toLowerCase();

                if (keywords.includes(q)) {
                    $(this).show();

                    if ($(this).closest('#carList').length) {
                        visibleCars++;
                    }

                    if ($(this).closest('#bikeList').length) {
                        visibleBikes++;
                    }
                } else {
                    $(this).hide();
                }
            });

            const totalVisible = visibleCars + visibleBikes;

            // Tampilkan / sembunyikan kategori sesuai hasil
            $('.car-header, #carList').toggle(visibleCars > 0);
            $('.motor-header, #bikeList').toggle(visibleBikes > 0);

            // Kalau tidak ada hasil sama sekali
            if (totalVisible === 0) {
                $('.category-section').hide();
                $('#emptySearchMessage').show();
            } else {
                $('.category-section').show();
                $('#emptySearchMessage').hide();
            }
        });

        // Default saat halaman dibuka
        $('#emptySearchMessage').hide();
    </script>

</body>

</html>