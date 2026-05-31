<?php
$activePage = $activePage ?? 'about';

$instagramUrl = "https://www.instagram.com/trrental.bali/?utm_source=ig_web_button_share_sheet";
?>

<!-- Navbar User -->
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <img src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="TR Rental">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'about' ? 'btn-nav-active' : '' ?>"
                        href="<?= BASE_URL ?>">
                        About Us
                    </a>
                </li>

                <li class="nav-item ml-2">
                    <a class="nav-link <?= $activePage === 'products' ? 'btn-nav-active' : '' ?>"
                        href="<?= BASE_URL ?>/home/products">
                        Products
                    </a>
                </li>

                <li class="nav-item ml-2">
                    <a href="https://wa.me/6281558984828"
                        target="_blank"
                        rel="noopener noreferrer">
                        Contact Us
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>