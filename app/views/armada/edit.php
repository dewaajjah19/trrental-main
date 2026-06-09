<?php
// =====================================================
// START BUFFER CONTENT
// =====================================================
ob_start();
?>

<?php
/** @var array $armada */

// =====================================================
// HELPER: AMANKAN OUTPUT HTML
// Digunakan agar data yang tampil di HTML aman dari karakter khusus.
// =====================================================
if (!function_exists('e')) {
    function e($value)
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// =====================================================
// HELPER: URL GAMBAR ARMADA
// Digunakan agar preview foto lama bisa tampil dari 2 sumber:
//
// 1. Localhost:
//    gambar_armada = armada_xxxxx.jpg
//    dibaca dari /public/assets/img/armada/
//
// 2. Vercel Blob:
//    gambar_armada = https://...blob.vercel-storage.com/...
//    langsung dipakai sebagai URL gambar.
//
// Ini penting agar armada yang gambarnya diupload dari Vercel tetap
// bisa tampil saat admin membuka halaman edit.
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
?>


<!-- =====================================================
     PAGE HEADER
     Judul halaman edit armada dan tombol kembali.
===================================================== -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Armada</h1>

    <a href="<?= BASE_URL ?>/armada" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>


<!-- =====================================================
     FORM EDIT ARMADA
     Digunakan admin untuk memperbarui data armada.
===================================================== -->
<div class="card shadow mb-4">
    <div class="card-body">

        <form method="POST"
            action="<?= BASE_URL ?>/armada/edit/<?= e($armada['id_armada']) ?>"
            enctype="multipart/form-data">

            <div class="row">

                <!-- =========================================
                     KOLOM KIRI: DATA IDENTITAS ARMADA
                ========================================== -->
                <div class="col-md-6">

                    <!-- Jenis Armada -->
                    <div class="form-group">
                        <label>Jenis Armada</label>
                        <select name="jenis_armada" class="form-control" required>
                            <option value="mobil" <?= $armada['jenis_armada'] === 'mobil' ? 'selected' : '' ?>>
                                Mobil
                            </option>
                            <option value="motor" <?= $armada['jenis_armada'] === 'motor' ? 'selected' : '' ?>>
                                Motor
                            </option>
                        </select>
                    </div>

                    <!-- Nama Armada -->
                    <div class="form-group">
                        <label>Nama Armada</label>
                        <input type="text"
                            name="nama_armada"
                            class="form-control"
                            value="<?= e($armada['nama_armada']) ?>"
                            required>
                    </div>

                    <!-- Merk Armada -->
                    <div class="form-group">
                        <label>Merk</label>
                        <input type="text"
                            name="merk_armada"
                            class="form-control"
                            value="<?= e($armada['merk_armada']) ?>"
                            required>
                    </div>

                    <!-- Tipe / CC Armada -->
                    <div class="form-group">
                        <label>Tipe</label>
                        <input type="text"
                            name="tipe_armada"
                            class="form-control"
                            value="<?= e($armada['tipe_armada']) ?>"
                            required>
                    </div>

                    <!-- Plat Nomor -->
                    <div class="form-group">
                        <label>Plat Nomor</label>
                        <input type="text"
                            name="plat_armada"
                            class="form-control"
                            value="<?= e($armada['plat_armada']) ?>"
                            required>
                    </div>

                </div>


                <!-- =========================================
                     KOLOM KANAN: SPESIFIKASI, HARGA, STATUS, FOTO
                ========================================== -->
                <div class="col-md-6">

                    <!-- Tahun Armada -->
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number"
                            name="tahun_armada"
                            class="form-control"
                            value="<?= e($armada['tahun_armada']) ?>"
                            min="2000"
                            max="2035"
                            required>
                    </div>

                    <!-- Transmisi -->
                    <div class="form-group">
                        <label>Transmisi</label>
                        <select name="transmisi" class="form-control" required>
                            <option value="Manual" <?= $armada['transmisi'] === 'Manual' ? 'selected' : '' ?>>
                                Manual
                            </option>
                            <option value="Matic" <?= $armada['transmisi'] === 'Matic' ? 'selected' : '' ?>>
                                Matic
                            </option>
                        </select>
                    </div>

                    <!-- Harga Sewa -->
                    <div class="form-group">
                        <label>Harga Sewa/Hari (Rp)</label>
                        <input type="number"
                            name="harga_sewa_perhari"
                            class="form-control"
                            value="<?= e($armada['harga_sewa_perhari']) ?>"
                            min="0"
                            required>
                        <small class="text-muted">
                            Isi angka tanpa titik. Contoh: 500000.
                        </small>
                    </div>

                    <!-- Status Armada -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status_armada" class="form-control" required>
                            <option value="tersedia" <?= $armada['status_armada'] === 'tersedia' ? 'selected' : '' ?>>
                                Tersedia
                            </option>
                            <option value="disewa" <?= $armada['status_armada'] === 'disewa' ? 'selected' : '' ?>>
                                Disewa
                            </option>
                            <option value="maintenance" <?= $armada['status_armada'] === 'maintenance' ? 'selected' : '' ?>>
                                Maintenance
                            </option>
                        </select>
                    </div>

                    <!-- Foto Armada -->
                    <div class="form-group">
                        <label>Foto Armada</label>

                        <!-- Preview Foto Lama -->
                        <?php if (!empty($armada['gambar_armada'])): ?>
                            <div class="mb-2">
                                <img src="<?= e(armadaImageUrl($armada['gambar_armada'])) ?>"
                                    height="80"
                                    style="border-radius:6px; object-fit:cover;">
                                <small class="d-block text-muted mt-1">
                                    Foto saat ini
                                </small>
                            </div>
                        <?php endif; ?>

                        <!-- Upload Foto Baru -->
                        <input type="file"
                            name="gambar_armada"
                            class="form-control-file"
                            accept="image/*">

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti foto. Format JPG, PNG, atau WEBP. Maksimal 4MB untuk upload online.
                        </small>
                    </div>

                </div>

            </div>


            <!-- =========================================
                 TOMBOL UPDATE
            ========================================== -->
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Update
            </button>

        </form>

    </div>
</div>


<?php
// =====================================================
// END BUFFER CONTENT & LOAD LAYOUT
// =====================================================
$content = ob_get_clean();
require_once BASE_PATH . '/app/views/layouts/main.php';
?>