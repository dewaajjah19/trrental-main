<?php

/** @var string $title */ ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/navbar.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/home.css?v=30">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/footer.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/responsive.css?v=1">
</head>

<body>
    <?php
    $activePage = 'about';
    require_once BASE_PATH . '/app/views/layouts/navbar-user.php';
    ?>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-text">
                    <h1>Easy Motorbike & Car Rental in Bali</h1>
                    <p>Rent motorbikes and cars easily and explore Bali with comfort and flexibility, all in one platform. Find the vehicle you need, choose your rental dates, and book in just a few simple steps.</p>
                    <a href="<?= BASE_URL ?>/home/products" class="btn btn-explore">
                        Explore Categories &rarr;
                    </a>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="<?= BASE_URL ?>/public/assets/img/hero.png"
                        class="img-fluid hero-img" alt="TR Rental">
                </div>
            </div>
        </div>
    </section>

    <!-- About -->
    <section class="section-about" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <img src="<?= BASE_URL ?>/public/assets/img/about.png"
                        class="img-fluid" style="max-height:280px" alt="About">
                </div>
                <div class="col-md-8">
                    <h2>Your Bali Motorbike & Car Rental in One Place</h2>
                    <p class="mt-3">Our platform makes motorbike and car rental in Bali simple and convenient. Browse vehicles, choose your rental dates, and book easily online. You can choose delivery to your location or pickup at our office, and receive a downloadable invoice after booking.</p>
                    <a href="<?= BASE_URL ?>/home/products">Simple Booking. Flexible Rental. Explore Bali with Ease.</a>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container">
            <h2>How It Works</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5>Choose Your Motorbike</h5>
                        <p>Browse available motorbikes and select the one that suits your needs.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5>Set Your Rental Schedule</h5>
                        <p>Choose pickup date and return date easily through the booking form.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5>Confirm Your Booking</h5>
                        <p>Download your invoice and wait for confirmation via WhatsApp.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Us -->
    <section class="why-us">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <h2>Why Rent with TRrental?</h2>
                    <p class="text-muted">We make motorbike rental in Bali simple, safe, and convenient. Book your ride easily and explore Bali with confidence.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="why-card">
                        <i class="fas fa-check-circle"></i>
                        <h6>Verified Motorbikes</h6>
                        <p>All motorbikes on our platform are checked to ensure they are in good condition and ready for a safe ride.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="why-card">
                        <i class="fas fa-tags"></i>
                        <h6>Transparent Pricing</h6>
                        <p>No hidden fees. Compare rental prices and choose the motorbike that fits your budget.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="why-card">
                        <i class="fab fa-whatsapp"></i>
                        <h6>Fast WhatsApp Confirmation</h6>
                        <p>After booking, our team will quickly confirm your rental through WhatsApp.</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="why-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h6>Flexible Pickup Options</h6>
                        <p class="why-desc">
                            We make motorbike rental in Bali simple, safe, and convenient.
                            Book your ride easily and explore Bali with confidence.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section">
        <div class="container">
            <h2>Need to Know</h2>
            <div class="col-lg-8 mx-auto px-0">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Do I need a passport?
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">Yes, a passport is required as a rental deposit during the rental period.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Can you deliver the motorbike?
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">Yes, we offer delivery to your hotel or location in Bali for an additional fee.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        How do I confirm my booking?
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">After submitting the booking form, our team will contact you via WhatsApp to confirm.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        Where can I pick up the motorbike?
                        <span class="faq-toggle"><i class="fas fa-plus"></i></span>
                    </div>
                    <div class="faq-answer">You can pick up at our office or request delivery to your location.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews -->
    <!-- dihide dulu ya bege -->
    <?php if (false): ?>
        <section class="reviews-section">
            <div class="container">
                <h2>Customer Reviews</h2>
                <div class="col-lg-8 mx-auto px-0">
                    <div class="review-box">
                        <button class="btn-write-review">Write Review</button>
                        <div class="clearfix"></div>
                        <div class="review-item">
                            <div class="stars">★★★★★</div>
                            <div class="review-text">"Very easy booking and the motorbike was in great condition."</div>
                            <div class="review-author">— Daniel, Australia</div>
                        </div>
                        <div class="review-item">
                            <div class="stars">★★★★★</div>
                            <div class="review-text">"Very easy booking and the motorbike was in great condition."</div>
                            <div class="review-author">— Daniel, Australia</div>
                        </div>
                        <div class="review-item">
                            <div class="stars">★★★★★</div>
                            <div class="review-text">"Very easy booking and the motorbike was in great condition."</div>
                            <div class="review-author">— Daniel, Australia</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php require_once BASE_PATH . '/app/views/layouts/footer-user.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFaq(el) {
            const answer = el.nextElementSibling;
            const icon = el.querySelector('.faq-toggle i');
            const isOpen = answer.style.display === 'block';
            // tutup semua
            document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
            document.querySelectorAll('.faq-toggle i').forEach(i => {
                i.classList.remove('fa-minus');
                i.classList.add('fa-plus');
            });
            if (!isOpen) {
                answer.style.display = 'block';
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            }
        }
    </script>
</body>

</html>