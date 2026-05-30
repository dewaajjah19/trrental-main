<?php
// =====================================================
// START BUFFER CONTENT
// Semua isi halaman ditampung dulu sebelum dipanggil ke layout admin.
// =====================================================
ob_start();
?>

<?php
/** @var array $customers */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan agar data customer yang tampil lebih aman.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>


<!-- =====================================================
     PAGE TITLE
     Judul utama halaman Data Customer.
===================================================== -->
<div class="page-title">Data Customer</div>


<!-- =====================================================
     ALERT SUCCESS
     Menampilkan pesan sukses jika ada proses berhasil.
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
     ALERT ERROR
     Menampilkan pesan error jika ada proses gagal.
===================================================== -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle mr-1"></i>
        <?= e($_SESSION['error']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<!-- =====================================================
     TABLE DATA CUSTOMER
     Search custom dihapus.
     Search yang digunakan adalah search bawaan DataTables di kanan.
===================================================== -->
<div class="table-card">
    <div class="table-responsive">

        <table class="table" id="customerTable">

            <!-- Header Tabel -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Whatsapp</th>
                    <th>Country of Origin</th>
                    <th>Address</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <!-- Isi Tabel -->
            <tbody>
                <?php foreach ($customers as $c): ?>
                    <tr>

                        <!-- ID Customer -->
                        <td><?= str_pad($c['id_cust'], 3, '0', STR_PAD_LEFT) ?></td>

                        <!-- Nama Customer -->
                        <td><?= e($c['nama_cust']) ?></td>

                        <!-- Nomor WhatsApp -->
                        <td><?= e($c['no_tlp']) ?></td>

                        <!-- Negara Asal -->
                        <td><?= e($c['country_origin']) ?></td>

                        <!-- Alamat Customer -->
                        <td><?= e($c['alamat']) ?></td>

                        <!-- Tombol Detail -->
                        <td>
                            <a href="<?= BASE_URL ?>/customer/detail/<?= e($c['id_cust']) ?>"
                                class="btn-detail">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <!-- Jika Data Customer Kosong -->
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Belum ada data customer.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>

    </div>
</div>


<?php
// =====================================================
// END BUFFER CONTENT
// =====================================================
$content = ob_get_clean();


// =====================================================
// SCRIPT DATATABLES
// Mengaktifkan pagination, sorting, dan search bawaan di kanan.
// =====================================================
$scripts = '
<script>
$(document).ready(function(){
    $("#customerTable").DataTable({
        order: [],
        pageLength: 10
    });
});
</script>
';


// =====================================================
// LOAD LAYOUT ADMIN
// =====================================================
require_once BASE_PATH . '/app/views/layouts/main.php';
?>