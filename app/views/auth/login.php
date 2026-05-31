<?php
// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan untuk menampilkan error dengan lebih aman.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <!-- =====================================================
         META & TITLE
    ====================================================== -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - TR Rental</title>

    <!-- =====================================================
         FONT AWESOME
    ====================================================== -->
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- =====================================================
         LOGIN PAGE STYLE
         Style dibuat langsung di file ini agar mudah kamu copy-paste.
    ====================================================== -->
    <style>
        :root {
            --primary: #5B2D8E;
            --primary-hover: #6E39A8;
            --bg-login: #CFE0FF;
            --text-dark: #222831;
            --text-muted: #7B8497;
            --border-soft: #E1E4EA;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--bg-login);
            color: var(--text-dark);
        }

        /* =========================
           LOGIN PAGE WRAPPER
        ========================= */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 70px;
        }

        .login-container {
            width: 100%;
            max-width: 1280px;
            display: grid;
            grid-template-columns: 1fr 0.95fr;
            align-items: center;
            gap: 70px;
        }

        /* =========================
           LEFT ILLUSTRATION
        ========================= */
        .login-illustration {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-illustration img {
            width: 100%;
            max-width: 620px;
            height: auto;
            display: block;
        }

        /* =========================
           RIGHT LOGIN CARD
        ========================= */
        .login-card {
            background: #ffffff;
            border-radius: 38px;
            padding: 58px 56px;
            box-shadow: 0 18px 45px rgba(91, 45, 142, 0.10);
        }

        .login-header {
            text-align: center;
            margin-bottom: 42px;
        }

        .login-title {
            font-size: 42px;
            font-weight: 900;
            color: #000;
            margin: 0 0 22px;
            letter-spacing: -0.5px;
        }

        .login-title .car-icon {
            font-size: 34px;
            margin-right: 8px;
            vertical-align: middle;
        }

        .login-subtitle {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-muted);
            margin: 0;
        }

        /* =========================
           ERROR ALERT
        ========================= */
        .login-alert {
            background: #FFECEC;
            color: #C0392B;
            border: 1px solid #F5B7B1;
            border-radius: 14px;
            padding: 13px 16px;
            margin-bottom: 24px;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
        }

        /* =========================
           FORM LOGIN
        ========================= */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 10px;
            color: #2D3340;
        }

        .input-wrap {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 64px;
            border: 1.7px solid var(--border-soft);
            border-radius: 12px;
            padding: 0 58px 0 26px;
            font-size: 22px;
            font-weight: 600;
            outline: none;
            color: #2D3340;
            background: #fff;
            transition: 0.2s ease;
        }

        .form-control::placeholder {
            color: #B9BFCC;
            font-weight: 700;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(91, 45, 142, 0.10);
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #999;
            font-size: 22px;
            cursor: pointer;
            padding: 0;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        /* =========================
           LOGIN BUTTON
        ========================= */
        .btn-login {
            width: 100%;
            height: 64px;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: #fff;
            font-size: 20px;
            font-weight: 800;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s ease;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* =========================
        FIX INPUT BACKGROUND
        ========================= */

        .form-control,
        .form-control:focus,
        .form-control:active {
            background-color: #ffffff !important;
            color: #222831 !important;
        }

        /* Fix warna autofill Chrome */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #222831 !important;
            caret-color: #222831 !important;
        }

        /* =========================
        FIX LOGIN NO SCROLL
        ========================= */

        html,
        body {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .login-page {
            height: 100vh;
            min-height: 100vh;
            padding: 24px 70px;
        }

        .login-container {
            max-width: 1180px;
            height: 100%;
            gap: 60px;
        }

        .login-illustration img {
            max-width: 560px;
            max-height: 82vh;
            object-fit: contain;
        }

        .login-card {
            padding: 44px 48px;
            border-radius: 34px;
            max-height: 82vh;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-title {
            font-size: 38px;
            margin-bottom: 16px;
        }

        .login-subtitle {
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 17px;
            margin-bottom: 8px;
        }

        .form-control {
            height: 58px;
            font-size: 20px;
        }

        .btn-login {
            height: 58px;
            margin-top: 8px;
        }

        /* =========================
           RESPONSIVE TABLET
        ========================= */
        @media (max-width: 992px) {
            .login-page {
                padding: 34px 28px;
            }

            .login-container {
                grid-template-columns: 1fr;
                gap: 26px;
                max-width: 620px;
            }

            .login-illustration img {
                max-width: 380px;
            }

            .login-card {
                padding: 44px 38px;
                border-radius: 30px;
            }
        }

        /* =========================
           RESPONSIVE MOBILE
        ========================= */
        @media (max-width: 576px) {

            html,
            body {
                overflow: auto;
            }

            .login-page {
                min-height: 100vh;
                height: auto;
                padding: 24px 18px;
            }

            .login-illustration {
                display: none;
            }

            .login-card {
                max-height: none;
                padding: 34px 24px;
                border-radius: 24px;
            }

            .login-title {
                font-size: 32px;
            }

            .login-title .car-icon {
                font-size: 28px;
            }

            .login-subtitle {
                font-size: 17px;
                line-height: 1.5;
            }

            .form-label {
                font-size: 15px;
            }

            .form-control {
                height: 56px;
                font-size: 16px;
                padding-left: 18px;
                padding-right: 50px;
            }

            .btn-login {
                height: 56px;
                font-size: 17px;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================================
         LOGIN PAGE
         Halaman utama login admin TR Rental.
    ====================================================== -->
    <main class="login-page">

        <div class="login-container">

            <!-- =================================================
                 LEFT SIDE: ILUSTRASI LOGIN
                 Gambar diambil dari public/assets/img/login.png
            ================================================== -->
            <section class="login-illustration">
                <img src="<?= BASE_URL ?>/public/assets/img/login.png" alt="Login Illustration TR Rental">
            </section>


            <!-- =================================================
                 RIGHT SIDE: LOGIN CARD
                 Card berisi title, pesan error, dan form login.
            ================================================== -->
            <section class="login-card">

                <!-- Header Login -->
                <div class="login-header">
                    <h1 class="login-title">
                        <span class="car-icon">🚗</span>TR Rental
                    </h1>
                    <p class="login-subtitle">
                        Login to access admin account!
                    </p>
                </div>


                <!-- Pesan Error Login -->
                <?php if (isset($error)): ?>
                    <div class="login-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>


                <!-- Form Login -->
                <form method="POST" action="<?= BASE_URL ?>/auth/login">

                    <!-- Input Username -->
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-wrap">
                            <input type="text"
                                name="username"
                                class="form-control"
                                placeholder="Masukkan Username"
                                required
                                autofocus>
                        </div>
                    </div>


                    <!-- Input Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrap">
                            <input type="password"
                                name="password"
                                id="passwordInput"
                                class="form-control"
                                placeholder="Masukkan Password"
                                required>

                            <button type="button"
                                class="password-toggle"
                                id="togglePassword"
                                aria-label="Show or hide password">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>


                    <!-- Tombol Submit -->
                    <button type="submit" class="btn-login">
                        Login
                    </button>

                </form>

            </section>

        </div>

    </main>


    <!-- =====================================================
         SCRIPT SHOW / HIDE PASSWORD
    ====================================================== -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isPassword = passwordInput.getAttribute('type') === 'password';

                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    </script>

</body>

</html>