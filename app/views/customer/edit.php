<?php
// =====================================================
// START BUFFER CONTENT
// Semua isi halaman edit customer ditampung sebelum masuk layout admin.
// =====================================================
ob_start();
?>

<?php
/** @var array $customer */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
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
     Judul halaman edit customer.
===================================================== -->
<div class="page-title">Edit Customer</div>


<!-- =====================================================
     BREADCRUMB
     Navigasi kecil untuk menunjukkan posisi halaman.
===================================================== -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/customer">Data Customer</a>
        </li>
        <li class="breadcrumb-item active">Edit Customer</li>
    </ol>
</nav>


<!-- =====================================================
     FORM EDIT CUSTOMER
     Digunakan untuk memperbarui data customer.
===================================================== -->
<div class="table-card" style="max-width:600px">

    <form method="POST" action="<?= BASE_URL ?>/customer/edit/<?= e($customer['id_cust']) ?>">

        <!-- Nama Customer -->
        <div class="form-group">
            <label class="small font-weight-600">Nama Customer</label>
            <input type="text"
                name="nama_cust"
                class="form-control"
                value="<?= e($customer['nama_cust']) ?>"
                required>
        </div>

        <!-- Nomor WhatsApp -->
        <div class="form-group">
            <label class="small font-weight-600">No. Whatsapp</label>
            <input type="text"
                name="no_tlp"
                class="form-control"
                value="<?= e($customer['no_tlp']) ?>"
                required>
        </div>

        <!-- Alamat Customer -->
        <div class="form-group">
            <label class="small font-weight-600">Alamat</label>
            <textarea name="alamat"
                class="form-control"
                rows="3"
                required><?= e($customer['alamat']) ?></textarea>
        </div>

        <!-- Negara Asal Customer -->
        <div class="form-group">
            <label class="small font-weight-600">Country of Origin</label>
            <input type="text"
                name="country_origin"
                class="form-control"
                value="<?= e($customer['country_origin']) ?>"
                required>
        </div>

        <!-- Tombol Action -->
        <div class="d-flex align-items-center">
            <button type="submit" class="btn btn-purple mr-2">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>

            <a href="<?= BASE_URL ?>/customer/detail/<?= e($customer['id_cust']) ?>"
                class="btn btn-outline-secondary">
                Batal
            </a>
        </div>

    </form>

</div>


<?php
// =====================================================
// END BUFFER CONTENT & LOAD LAYOUT ADMIN
// =====================================================
$content = ob_get_clean();
require_once BASE_PATH . '/app/views/layouts/main.php';
?>