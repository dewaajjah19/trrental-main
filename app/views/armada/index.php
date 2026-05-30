<?php
// =====================================================
// START BUFFER CONTENT
// Menampung isi halaman sebelum dipanggil ke layout admin.
// =====================================================
ob_start();
?>

<?php
/** @var array $armada */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: STATUS ARMADA BADGE
// Menentukan warna badge berdasarkan status armada.
// =====================================================
function armadaStatusBadge($status)
{
    return match ($status) {
        'tersedia'    => 'success',
        'disewa'      => 'danger',
        'maintenance' => 'warning',
        default       => 'secondary'
    };
}

function armadaStatusLabel($status)
{
    return match ($status) {
        'tersedia'    => 'Tersedia',
        'disewa'      => 'Disewa',
        'maintenance' => 'Maintenance',
        default       => '-'
    };
}
?>


<!-- =====================================================
     PAGE HEADER
     Judul halaman dan tombol tambah armada.
===================================================== -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Armada</h1>

    <a href="<?= BASE_URL ?>/armada/create" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Armada
    </a>
</div>


<!-- =====================================================
     ALERT SUCCESS
     Menampilkan pesan berhasil jika ada session success.
===================================================== -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> <?= e($_SESSION['success']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>


<!-- =====================================================
     ALERT ERROR
     Menampilkan pesan error jika ada session error.
===================================================== -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> <?= e($_SESSION['error']) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<!-- =====================================================
     TABLE DATA ARMADA
     Menampilkan seluruh data armada yang tersimpan di database.
===================================================== -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered table-hover" id="dataTable">

                <!-- Header Tabel -->
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Nama Armada</th>
                        <th>Merk / Tipe</th>
                        <th>Plat</th>
                        <th>Tahun</th>
                        <th>Transmisi</th>
                        <th>Harga/Hari</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <!-- Isi Tabel -->
                <tbody>
                    <?php foreach ($armada as $i => $a): ?>
                        <?php
                        $badge = armadaStatusBadge($a['status_armada'] ?? '');
                        $statusLabel = armadaStatusLabel($a['status_armada'] ?? '');
                        ?>

                        <tr>
                            <!-- Nomor Urut -->
                            <td><?= $i + 1 ?></td>

                            <!-- Gambar Armada -->
                            <td class="text-center">
                                <?php if (!empty($a['gambar_armada'])): ?>
                                    <img src="<?= BASE_URL ?>/public/assets/img/armada/<?= e($a['gambar_armada']) ?>"
                                        width="60"
                                        height="45"
                                        style="object-fit:cover; border-radius:6px;">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Nama Armada -->
                            <td><?= e($a['nama_armada']) ?></td>

                            <!-- Merk dan Tipe Armada -->
                            <td>
                                <?= e($a['merk_armada']) ?> /
                                <?= e($a['tipe_armada']) ?>
                            </td>

                            <!-- Plat Nomor -->
                            <td><?= e($a['plat_armada']) ?></td>

                            <!-- Tahun Armada -->
                            <td><?= e($a['tahun_armada']) ?></td>

                            <!-- Transmisi -->
                            <td><?= e($a['transmisi']) ?></td>

                            <!-- Harga Sewa Per Hari -->
                            <td>
                                Rp <?= number_format($a['harga_sewa_perhari'], 0, ',', '.') ?>
                            </td>

                            <!-- Status Armada -->
                            <td>
                                <span class="badge badge-<?= e($badge) ?>">
                                    <?= e($statusLabel) ?>
                                </span>
                            </td>

                            <!-- Tombol Aksi -->
                            <td>
                                <a href="<?= BASE_URL ?>/armada/edit/<?= e($a['id_armada']) ?>"
                                    class="btn btn-warning btn-sm"
                                    title="Edit Armada">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <a href="javascript:void(0)"
                                    class="btn btn-danger btn-sm"
                                    title="Hapus Armada"
                                    onclick="confirmDelete('<?= BASE_URL ?>/armada/delete/<?= e($a['id_armada']) ?>', '<?= e($a['nama_armada']) ?>')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <!-- Jika Data Kosong -->
                    <?php if (empty($armada)): ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                Belum ada data armada.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>


<?php
// =====================================================
// END BUFFER CONTENT
// =====================================================
$content = ob_get_clean();


// =====================================================
// SCRIPT DATATABLES
// Mengaktifkan fitur pagination, sorting, dan search tabel.
// =====================================================
$scripts = '
<script>
$(document).ready(function(){
    $("#dataTable").DataTable({
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