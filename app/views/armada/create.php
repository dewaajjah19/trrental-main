<?php
// =====================================================
// START BUFFER CONTENT
// =====================================================
ob_start();
?>


<!-- =====================================================
     PAGE HEADER
     Judul halaman tambah armada dan tombol kembali.
===================================================== -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Armada</h1>

    <a href="<?= BASE_URL ?>/armada" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>


<!-- =====================================================
     FORM TAMBAH ARMADA
     Digunakan admin untuk menambahkan data mobil/motor baru.
===================================================== -->
<div class="card shadow mb-4">
    <div class="card-body">

        <form method="POST"
            action="<?= BASE_URL ?>/armada/create"
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
                            <option value="mobil">Mobil</option>
                            <option value="motor">Motor</option>
                        </select>
                    </div>

                    <!-- Nama Armada -->
                    <div class="form-group">
                        <label>Nama Armada</label>
                        <input type="text"
                            name="nama_armada"
                            class="form-control"
                            placeholder="Contoh: Toyota Avanza"
                            required>
                    </div>

                    <!-- Merk Armada -->
                    <div class="form-group">
                        <label>Merk</label>
                        <input type="text"
                            name="merk_armada"
                            class="form-control"
                            placeholder="Contoh: Toyota"
                            required>
                    </div>

                    <!-- Tipe / CC Armada -->
                    <div class="form-group">
                        <label>Tipe</label>
                        <input type="text"
                            name="tipe_armada"
                            class="form-control"
                            placeholder="Contoh: 1.300 CC / 150 CC"
                            required>
                    </div>

                    <!-- Plat Nomor -->
                    <div class="form-group">
                        <label>Plat Nomor</label>
                        <input type="text"
                            name="plat_armada"
                            class="form-control"
                            placeholder="Contoh: DK 1234 ABC"
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
                            placeholder="Contoh: 2024"
                            min="2000"
                            max="2035"
                            required>
                    </div>

                    <!-- Transmisi -->
                    <div class="form-group">
                        <label>Transmisi</label>
                        <select name="transmisi" class="form-control" required>
                            <option value="Manual">Manual</option>
                            <option value="Matic">Matic</option>
                        </select>
                    </div>

                    <!-- Harga Sewa -->
                    <div class="form-group">
                        <label>Harga Sewa/Hari (Rp)</label>
                        <input type="number"
                            name="harga_sewa_perhari"
                            class="form-control"
                            placeholder="Contoh: 500000"
                            min="0"
                            required>
                        <small class="text-muted">
                            Isi angka tanpa titik. Contoh: 500000, bukan 500.000.
                        </small>
                    </div>

                    <!-- Status Armada -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status_armada" class="form-control" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="disewa">Disewa</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <!-- Upload Foto Armada -->
                    <div class="form-group">
                        <label>Foto Armada</label>
                        <input type="file"
                            name="gambar_armada"
                            class="form-control-file"
                            accept="image/*">
                        <small class="text-muted">
                            Format: jpg, jpeg, png, webp.
                        </small>
                    </div>

                </div>

            </div>


            <!-- =========================================
                 TOMBOL SUBMIT
            ========================================== -->
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
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