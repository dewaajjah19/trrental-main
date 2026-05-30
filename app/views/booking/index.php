<?php ob_start(); ?>
<?php /** @var array $bookings*/ ?>

<div class="page-title">Data Booking</div>

<!-- Filter -->
<div class="table-card mb-4">
    <h6 class="font-weight-700 mb-3" style="font-weight:700">Filter Booking</h6>
    <form method="GET" action="<?= BASE_URL ?>/booking">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="small font-weight-600">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control form-control-sm"
                    value="<?= $filter['tgl_awal'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="small font-weight-600">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control form-control-sm"
                    value="<?= $filter['tgl_akhir'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="small font-weight-600">Status</label>
                <select name="status" class="form-control form-control-sm">
                    <option value="">Semua</option>
                    <option value="menunggu" <?= ($filter['status'] ?? '') === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="dikonfirmasi" <?= ($filter['status'] ?? '') === 'dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="disewa" <?= ($filter['status'] ?? '') === 'disewa' ? 'selected' : '' ?>>Disewa</option>
                    <option value="selesai" <?= ($filter['status'] ?? '') === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                    <option value="dibatalkan" <?= ($filter['status'] ?? '') === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-purple btn-sm mr-2">
                    <i class="fas fa-filter"></i> Terapkan Filter
                </button>
                <a href="<?= BASE_URL ?>/booking" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success'] ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Table -->
<div class="table-card">
    <div class="table-responsive">
        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Armada</th>
                    <th>Tgl/Waktu Pinjam</th>
                    <th></th>
                    <th>Tgl/Waktu Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $i => $b): ?>
                    <tr>
                        <td><?= str_pad($b['id_booking'], 3, '0', STR_PAD_LEFT) ?></td>
                        <td><?= $b['nama_cust'] ?></td>
                        <td><?= $b['nama_armada'] ?></td>
                        <td><?= date('d F Y', strtotime($b['tgl_pinjam'])) ?></td>
                        <td><i class="fas fa-arrow-right text-muted"></i></td>
                        <td><?= date('d F Y', strtotime($b['tgl_kembali'])) ?></td>
                        <td>
                            <?php
                            $badgeClass = match ($b['status_booking']) {
                                'menunggu'     => 'badge-menunggu',
                                'dikonfirmasi' => 'badge-dikonfirmasi',
                                'disewa'       => 'badge-disewa',
                                'selesai'      => 'badge-selesai',
                                'dibatalkan'   => 'badge-dibatalkan',
                                default        => 'badge-menunggu'
                            };
                            $badgeLabel = match ($b['status_booking']) {
                                'menunggu'     => 'Menunggu',
                                'dikonfirmasi' => 'Dikonfirmasi',
                                'disewa'       => 'On Progress',
                                'selesai'      => 'Done',
                                'dibatalkan'   => 'Dibatalkan',
                                default        => '-'
                            };
                            ?>
                            <span class="status-badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/booking/detail/<?= $b['id_booking'] ?>"
                                class="btn-detail">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
$scripts = '<script>$(document).ready(function(){$("#dataTable").DataTable({order:[]});});</script>';
require_once BASE_PATH . '/app/views/layouts/main.php';
?>