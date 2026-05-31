<?php

/** @var string $content */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan agar data yang ditampilkan lebih aman dari karakter khusus.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}


// =====================================================
// NOTIFIKASI BOOKING ADMIN
// Mengambil booking baru yang masih berstatus "menunggu".
// Notifikasi akan hilang ketika admin mengubah status booking.
// =====================================================
$dbNotif = getDB();

$notifCount = 0;
$notifBookings = [];

// Hitung total booking yang masih menunggu
$countResult = $dbNotif->query("
    SELECT COUNT(*) AS total
    FROM booking
    WHERE status_booking = 'menunggu'
");

if ($countResult) {
    $notifCount = (int) ($countResult->fetch_assoc()['total'] ?? 0);
}

// Ambil daftar booking terbaru yang masih menunggu
$listResult = $dbNotif->query("
    SELECT 
        b.id_booking,
        b.created_at,
        b.status_booking,
        c.nama_cust,
        a.nama_armada
    FROM booking b
    JOIN cust c ON b.id_cust = c.id_cust
    JOIN armada a ON b.id_armada = a.id_armada
    WHERE b.status_booking = 'menunggu'
    ORDER BY b.created_at DESC
    LIMIT 5
");

if ($listResult) {
    $notifBookings = $listResult->fetch_all(MYSQLI_ASSOC);
}

// =====================================================
// PROFILE ADMIN LOGIN
// Mengambil data admin yang sedang login berdasarkan id_staff.
// Password tidak diambil dan tidak ditampilkan.
// =====================================================
$adminProfile = [
    'id_staff'   => $_SESSION['staff_id'] ?? null,
    'nama_staff' => $_SESSION['staff_name'] ?? 'Admin',
    'username'   => $_SESSION['staff_username'] ?? '-',
    'no_tlp'     => '-',
    'alamat'     => '-'
];

if (!empty($_SESSION['staff_id'])) {
    $stmtAdmin = $dbNotif->prepare("
        SELECT id_staff, nama_staff, username, no_tlp, alamat
        FROM staff
        WHERE id_staff = ?
        LIMIT 1
    ");

    $stmtAdmin->bind_param('i', $_SESSION['staff_id']);
    $stmtAdmin->execute();

    $resultAdmin = $stmtAdmin->get_result();
    $adminData = $resultAdmin->fetch_assoc();

    if ($adminData) {
        $adminProfile = $adminData;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <!-- =====================================================
         META & TITLE
    ====================================================== -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'TR Rental') ?> - Admin</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">


    <!-- =====================================================
         VENDOR CSS
    ====================================================== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">


    <!-- =====================================================
         GLOBAL ADMIN CSS
         Version dinaikkan agar browser mengambil CSS terbaru.
    ====================================================== -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/admin-main.css?v=4">
</head>

<body>

    <!-- =====================================================
         SIDEBAR ADMIN
         Area kiri berisi logo dan menu navigasi admin.
         Catatan: sidebar harus ditutup sebelum main-wrapper dimulai.
    ====================================================== -->
    <div class="sidebar">

        <!-- Logo Sidebar -->
        <div class="sidebar-logo">
            <img src="<?= BASE_URL ?>/public/assets/img/logo.png" alt="TR Rental">
        </div>


        <!-- Menu Sidebar -->
        <ul class="sidebar-menu">

            <!-- Menu Dashboard -->
            <li>
                <a href="<?= BASE_URL ?>/dashboard"
                    class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
            </li>


            <!-- Menu Data Armada -->
            <li>
                <a href="<?= BASE_URL ?>/armada"
                    class="<?= ($activePage ?? '') === 'armada' ? 'active' : '' ?>">
                    <i class="fas fa-car"></i> Data Armada
                </a>
            </li>


            <!-- Menu Data Customer -->
            <li>
                <a href="<?= BASE_URL ?>/customer"
                    class="<?= ($activePage ?? '') === 'customer' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Data Customer
                </a>
            </li>


            <!-- Menu Data Booking -->
            <li>
                <a href="<?= BASE_URL ?>/booking"
                    class="<?= ($activePage ?? '') === 'booking' ? 'active' : '' ?>">
                    <i class="fas fa-list-alt"></i> Data Booking
                </a>
            </li>


            <!-- Menu Laporan -->
            <li>
                <a href="<?= BASE_URL ?>/laporan"
                    class="<?= ($activePage ?? '') === 'laporan' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Laporan
                </a>
            </li>

        </ul>

    </div>
    <!-- END SIDEBAR -->


    <!-- =====================================================
         MAIN WRAPPER
         Area utama yang berisi topbar dan content halaman admin.
         Bagian ini HARUS berada di luar sidebar.
    ====================================================== -->
    <div class="main-wrapper">

        <!-- =================================================
             TOPBAR ADMIN
             Search bar atas sudah dihapus.
             Topbar hanya berisi notifikasi dan profile admin.
        ================================================== -->
        <div class="topbar topbar-no-search">

            <!-- Bagian kanan topbar -->
            <div class="topbar-right">

                <!-- =========================================
                     NOTIFICATION BELL
                     Menampilkan jumlah booking yang masih menunggu.
                ========================================== -->
                <div class="dropdown admin-notification-wrapper mr-3">

                    <!-- Tombol Lonceng -->
                    <button class="btn admin-notification-btn"
                        type="button"
                        id="bookingNotificationDropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <i class="fas fa-bell"></i>

                        <?php if ($notifCount > 0): ?>
                            <span class="admin-notification-badge">
                                <?= $notifCount > 99 ? '99+' : e($notifCount) ?>
                            </span>
                        <?php endif; ?>
                    </button>


                    <!-- Dropdown Notifikasi -->
                    <div class="dropdown-menu dropdown-menu-right admin-notification-menu"
                        aria-labelledby="bookingNotificationDropdown">

                        <!-- Header Dropdown Notifikasi -->
                        <div class="admin-notification-header">
                            <span>Notifications</span>
                            <small><?= e($notifCount) ?> new booking</small>
                        </div>


                        <!-- Isi Notifikasi Jika Ada Booking Menunggu -->
                        <?php if ($notifCount > 0): ?>

                            <?php foreach ($notifBookings as $notif): ?>
                                <a href="<?= BASE_URL ?>/booking/detail/<?= e($notif['id_booking']) ?>"
                                    class="dropdown-item admin-notification-item">

                                    <!-- Icon Notifikasi -->
                                    <div class="notif-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>

                                    <!-- Konten Notifikasi -->
                                    <div class="notif-content">
                                        <div class="notif-title">
                                            New booking from <?= e($notif['nama_cust']) ?>
                                        </div>

                                        <div class="notif-desc">
                                            <?= e($notif['nama_armada']) ?> needs confirmation.
                                        </div>

                                        <div class="notif-time">
                                            <?= !empty($notif['created_at'])
                                                ? date('d M Y, H:i', strtotime($notif['created_at']))
                                                : '' ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>


                            <!-- Link ke Booking Status Menunggu -->
                            <a href="<?= BASE_URL ?>/booking?status=menunggu"
                                class="dropdown-item text-center admin-notification-seeall">
                                <i class="fas fa-list mr-1"></i> See all pending bookings
                            </a>

                        <?php else: ?>

                            <!-- Tampilan Jika Tidak Ada Notifikasi -->
                            <div class="admin-notification-empty">
                                <i class="fas fa-check-circle mb-2"></i>
                                <p>No new booking notifications.</p>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>


                <!-- =========================================
                     ADMIN PROFILE DROPDOWN SIMPLE
                     Saat profile admin diklik, muncul menu Profile dan Log out.
                ========================================== -->
                <div class="dropdown admin-profile-wrapper">

                    <!-- Tombol Profile Admin -->
                    <button class="admin-profile-toggle"
                        type="button"
                        id="adminProfileDropdown"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">

                        <img src="<?= BASE_URL ?>/public/assets/img/undraw_profile.svg"
                            class="admin-avatar"
                            alt="Admin">

                        <div class="admin-profile-text">
                            <div class="admin-name">
                                <?= e($_SESSION['staff_name'] ?? 'Admin') ?>
                            </div>
                            <div class="admin-role">Admin</div>
                        </div>

                        <i class="fas fa-chevron-down admin-profile-arrow"></i>
                    </button>


                    <!-- Menu Dropdown Profile -->
                    <div class="dropdown-menu dropdown-menu-right admin-profile-simple-menu"
                        aria-labelledby="adminProfileDropdown">

                        <!-- Header Profile -->
                        <div class="admin-profile-dropdown-header">
                            <div class="admin-profile-dropdown-title">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </div>
                        </div>

                        <!-- Data Admin Login -->
                        <div class="admin-profile-dropdown-body">

                            <div class="admin-profile-row">
                                <span>Name</span>
                                <strong><?= e($adminProfile['nama_staff'] ?? '-') ?></strong>
                            </div>

                            <div class="admin-profile-row">
                                <span>Username</span>
                                <strong><?= e($adminProfile['username'] ?? '-') ?></strong>
                            </div>

                            <div class="admin-profile-row">
                                <span>No. Telp</span>
                                <strong><?= e($adminProfile['no_tlp'] ?? '-') ?></strong>
                            </div>

                            <div class="admin-profile-row">
                                <span>Alamat</span>
                                <strong><?= e($adminProfile['alamat'] ?? '-') ?></strong>
                            </div>

                        </div>

                        <div class="dropdown-divider my-0"></div>

                        <!-- Logout -->
                        <a href="<?= BASE_URL ?>/auth/logout" class="dropdown-item admin-profile-simple-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Log out
                        </a>

                    </div>
                </div>

            </div>
        </div>


        <!-- =================================================
             PAGE CONTENT
             Semua isi halaman admin akan dimasukkan lewat variabel $content.
        ================================================== -->
        <div class="page-content">
            <?= $content ?>
        </div>

    </div>
    <!-- END MAIN WRAPPER -->


    <!-- =====================================================
         VENDOR JAVASCRIPT
    ====================================================== -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


    <!-- =====================================================
         SCRIPT DARI MASING-MASING HALAMAN
         Contoh: DataTables, Chart.js, export laporan, dan lainnya.
    ====================================================== -->
    <?= $scripts ?? '' ?>


    <!-- =====================================================
         SWEETALERT GLOBAL
         Menampilkan alert success/error dan konfirmasi hapus data.
    ====================================================== -->
    <script>
        // =====================================================
        // SUCCESS MESSAGE DARI SESSION
        // =====================================================
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: <?= json_encode($_SESSION['success']) ?>,
                confirmButtonColor: '#5B2D8E',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>


        // =====================================================
        // ERROR MESSAGE DARI SESSION
        // =====================================================
        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: <?= json_encode($_SESSION['error']) ?>,
                confirmButtonColor: '#5B2D8E'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>


        // =====================================================
        // GLOBAL CONFIRM DELETE
        // Digunakan oleh tombol hapus di halaman admin.
        // =====================================================
        function confirmDelete(url, nama = 'data ini') {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: nama + ' akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#5B2D8E',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>

</body>

</html>