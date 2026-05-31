<?php

/** @var array $armada */

$timeOptions = [
    '08:00',
    '09:00',
    '10:00',
    '11:00',
    '12:00',
    '13:00',
    '14:00',
    '15:00',
    '16:00',
    '17:00',
    '18:00',
    '19:00',
    '20:00'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Vehicle - TR Rental</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>/public/assets/img/logo.png?v=2">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/booking.css?v=2">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/landingpage/responsive.css?v=1">
</head>

<body>

    <div class="deco-top"></div>
    <div class="deco-bottom"></div>

    <!-- Header -->
    <div class="booking-header">
        <div class="header-inner">
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-chevron-left"></i>
            </a>
            <p class="header-title">Book Your Vehicle</p>
        </div>
    </div>

    <div class="booking-container">
        <div class="row">

            <!-- Form Kiri -->
            <div class="col-lg-7 mb-4">
                <form method="POST"
                    action="<?= BASE_URL ?>/home/booking/<?= $armada['id_armada'] ?>"
                    enctype="multipart/form-data"
                    id="bookingForm">

                    <!-- Personal Information -->
                    <div class="form-card mb-4">
                        <div class="form-section-header">
                            <div class="icon"><i class="fas fa-user"></i></div>
                            <span>Personal Information</span>
                        </div>

                        <div class="form-section-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Name <span class="req">*</span></label>
                                        <div class="input-wrap">
                                            <i class="fas fa-user input-icon"></i>
                                            <input type="text" name="nama_cust" class="form-control"
                                                placeholder="Your full name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Whatsapp <span class="req">*</span></label>
                                        <div class="input-wrap">
                                            <i class="fab fa-whatsapp input-icon"></i>
                                            <input type="text" name="no_tlp" class="form-control"
                                                placeholder="+62..." required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Country of Origin <span class="req">*</span></label>
                                        <div class="input-wrap">
                                            <i class="fas fa-globe input-icon"></i>
                                            <input type="text" name="country_origin" class="form-control"
                                                placeholder="e.g. Indonesia" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Address <span class="req">*</span></label>
                                        <div class="input-wrap">
                                            <i class="fas fa-map-marker-alt input-icon"></i>
                                            <input type="text" name="alamat" class="form-control"
                                                placeholder="Your address" required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Customer Type -->
                            <div class="customer-type-box mt-3">
                                <label class="form-label">Customer Type <span class="req">*</span></label>
                                <p class="customer-type-help">
                                    Please select your citizenship to continue the booking form.
                                </p>

                                <div class="citizenship-options">
                                    <label class="citizenship-card">
                                        <input type="radio" name="tipe_customer" value="WNI" required>
                                        <div>
                                            <h6>Indonesian Citizen</h6>
                                            <p>Only identity card is required.</p>
                                        </div>
                                    </label>

                                    <label class="citizenship-card">
                                        <input type="radio" name="tipe_customer" value="WNA" required>
                                        <div>
                                            <h6>Foreign Citizen</h6>
                                            <p>Driving license, identity/passport, flight ticket, and hotel booking are required.</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Form lanjutan muncul setelah pilih WNI/WNA -->
                    <div id="bookingExtraFields" style="display:none;">

                        <!-- Rental Schedule -->
                        <div class="form-card mb-4">
                            <div class="form-section-header">
                                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                <span>Rental Schedule</span>
                            </div>

                            <div class="form-section-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Rent Start From <span class="req">*</span></label>
                                            <div class="input-wrap">
                                                <i class="fas fa-calendar input-icon"></i>
                                                <input type="date" name="tgl_pinjam" id="tgl_pinjam"
                                                    class="form-control booking-required booking-extra-input"
                                                    min="<?= date('Y-m-d') ?>" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Pickup Time <span class="req">*</span></label>
                                            <div class="input-wrap">
                                                <i class="fas fa-clock input-icon"></i>
                                                <select name="jam_pengambilan" id="jam_pengambilan"
                                                    class="form-control booking-required booking-extra-input" disabled>
                                                    <option value="">Select pickup time</option>
                                                    <?php foreach ($timeOptions as $time): ?>
                                                        <option value="<?= $time ?>"><?= $time ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Rent Finish <span class="req">*</span></label>
                                            <div class="input-wrap">
                                                <i class="fas fa-calendar input-icon"></i>
                                                <input type="date" name="tgl_kembali" id="tgl_kembali"
                                                    class="form-control booking-required booking-extra-input"
                                                    min="<?= date('Y-m-d') ?>" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Return Time <span class="req">*</span></label>
                                            <div class="input-wrap">
                                                <i class="fas fa-clock input-icon"></i>
                                                <select name="jam_pengembalian" id="jam_pengembalian"
                                                    class="form-control booking-required booking-extra-input" disabled>
                                                    <option value="">Select return time</option>
                                                    <?php foreach ($timeOptions as $time): ?>
                                                        <option value="<?= $time ?>"><?= $time ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Pickup & Delivery -->
                        <div class="form-card mb-4">
                            <div class="form-section-header">
                                <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                <span>Pickup & Delivery</span>
                            </div>

                            <div class="form-section-body">
                                <div class="form-group">
                                    <label class="form-label">Pickup Method <span class="req">*</span></label>

                                    <div class="radio-group">
                                        <label class="radio-card selected" id="card_ambil">
                                            <input type="radio" name="metode_pengambilan"
                                                value="ambil_sendiri" checked
                                                class="booking-extra-input"
                                                onchange="togglePickup(this)" disabled>
                                            <div>
                                                <div class="radio-label"><i class="fas fa-store mr-1"></i> Pick Up</div>
                                                <div class="radio-sub">At the office</div>
                                            </div>
                                        </label>

                                        <label class="radio-card" id="card_antar">
                                            <input type="radio" name="metode_pengambilan"
                                                value="antar_jemput"
                                                class="booking-extra-input"
                                                onchange="togglePickup(this)" disabled>
                                            <div>
                                                <div class="radio-label"><i class="fas fa-motorcycle mr-1"></i> Delivery</div>
                                                <div class="radio-sub">To your location</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div id="delivery_fields" style="display:none">
                                    <div class="row mt-3">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Pickup Address</label>
                                                <div class="input-wrap">
                                                    <i class="fas fa-map-pin input-icon"></i>
                                                    <input type="text" name="titik_jemput"
                                                        class="form-control delivery-input booking-extra-input"
                                                        placeholder="Where to pick you up" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Drop Address</label>
                                                <div class="input-wrap">
                                                    <i class="fas fa-flag input-icon"></i>
                                                    <input type="text" name="alamat_pengantaran"
                                                        class="form-control delivery-input booking-extra-input"
                                                        placeholder="Where to drop off" disabled>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-card mb-4">
                            <div class="form-section-header">
                                <div class="icon"><i class="fas fa-credit-card"></i></div>
                                <span>Payment Method</span>
                            </div>

                            <div class="form-section-body">
                                <div class="radio-group">

                                    <label class="radio-card selected" id="card_transfer">
                                        <input type="radio" name="metode_pembayaran"
                                            value="transfer" checked
                                            class="booking-extra-input"
                                            onchange="selectPayment('card_transfer')" disabled>
                                        <div>
                                            <div class="radio-label"><i class="fas fa-university mr-1"></i> Transfer</div>
                                            <div class="radio-sub">Bank transfer</div>
                                        </div>
                                    </label>

                                    <label class="radio-card" id="card_tunai">
                                        <input type="radio" name="metode_pembayaran"
                                            value="tunai"
                                            class="booking-extra-input"
                                            onchange="selectPayment('card_tunai')" disabled>
                                        <div>
                                            <div class="radio-label"><i class="fas fa-money-bill mr-1"></i> Cash</div>
                                            <div class="radio-sub">Pay in person</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Documents -->
                        <div class="form-card mb-4">
                            <div class="form-section-header">
                                <div class="icon"><i class="fas fa-file-upload"></i></div>
                                <span>Upload Documents</span>
                            </div>

                            <div class="form-section-body">

                                <!-- Dokumen WNI -->
                                <div id="documentWNI" style="display:none;">
                                    <p class="document-info">
                                        Indonesian citizen only needs to upload an identity card.
                                    </p>

                                    <div class="upload-grid single-upload">
                                        <div>
                                            <label class="form-label">Identity Card <span class="req">*</span></label>
                                            <div class="upload-area" id="area_ktp_wni">
                                                <input type="file" name="foto_ktp" id="foto_ktp_wni"
                                                    class="booking-extra-input doc-input doc-wni"
                                                    accept="image/*"
                                                    onchange="previewUpload(this, 'area_ktp_wni', 'prev_ktp_wni')" disabled>
                                                <img class="preview-img" id="prev_ktp_wni">
                                                <div id="placeholder_ktp_wni">
                                                    <div class="upload-icon"><i class="fas fa-address-card"></i></div>
                                                    <div class="upload-label">Identity Card</div>
                                                    <div class="upload-hint"><span>Click to upload</span> or drag & drop</div>
                                                    <div class="upload-hint">JPG, PNG max 4MB</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dokumen WNA -->
                                <div id="documentWNA" style="display:none;">
                                    <p class="document-info">
                                        Foreign citizen must upload all required documents below.
                                    </p>

                                    <div class="upload-grid">

                                        <!-- SIM -->
                                        <div>
                                            <label class="form-label">Driver License <span class="req">*</span></label>
                                            <div class="upload-area" id="area_sim">
                                                <input type="file" name="foto_sim" id="foto_sim"
                                                    class="booking-extra-input doc-input doc-wna"
                                                    accept="image/*"
                                                    onchange="previewUpload(this, 'area_sim', 'prev_sim')" disabled>
                                                <img class="preview-img" id="prev_sim">
                                                <div id="placeholder_sim">
                                                    <div class="upload-icon"><i class="fas fa-id-card"></i></div>
                                                    <div class="upload-label">Driver License</div>
                                                    <div class="upload-hint"><span>Click to upload</span> or drag & drop</div>
                                                    <div class="upload-hint">JPG, PNG max 10MB</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- KTP / Passport -->
                                        <div>
                                            <label class="form-label">Identity Card / Passport <span class="req">*</span></label>
                                            <div class="upload-area" id="area_ktp_wna">
                                                <input type="file" name="foto_ktp" id="foto_ktp_wna"
                                                    class="booking-extra-input doc-input doc-wna"
                                                    accept="image/*"
                                                    onchange="previewUpload(this, 'area_ktp_wna', 'prev_ktp_wna')" disabled>
                                                <img class="preview-img" id="prev_ktp_wna">
                                                <div id="placeholder_ktp_wna">
                                                    <div class="upload-icon"><i class="fas fa-address-card"></i></div>
                                                    <div class="upload-label">Identity / Passport</div>
                                                    <div class="upload-hint"><span>Click to upload</span> or drag & drop</div>
                                                    <div class="upload-hint">JPG, PNG max 10MB</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tiket -->
                                        <div>
                                            <label class="form-label">Flight Ticket <span class="req">*</span></label>
                                            <div class="upload-area" id="area_tiket">
                                                <input type="file" name="foto_tiket" id="foto_tiket"
                                                    class="booking-extra-input doc-input doc-wna"
                                                    accept="image/*"
                                                    onchange="previewUpload(this, 'area_tiket', 'prev_tiket')" disabled>
                                                <img class="preview-img" id="prev_tiket">
                                                <div id="placeholder_tiket">
                                                    <div class="upload-icon"><i class="fas fa-plane"></i></div>
                                                    <div class="upload-label">Flight Ticket</div>
                                                    <div class="upload-hint"><span>Click to upload</span> or drag & drop</div>
                                                    <div class="upload-hint">JPG, PNG max 10MB</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hotel -->
                                        <div>
                                            <label class="form-label">Hotel Booking <span class="req">*</span></label>
                                            <div class="upload-area" id="area_hotel">
                                                <input type="file" name="foto_hotel" id="foto_hotel"
                                                    class="booking-extra-input doc-input doc-wna"
                                                    accept="image/*"
                                                    onchange="previewUpload(this, 'area_hotel', 'prev_hotel')" disabled>
                                                <img class="preview-img" id="prev_hotel">
                                                <div id="placeholder_hotel">
                                                    <div class="upload-icon"><i class="fas fa-hotel"></i></div>
                                                    <div class="upload-label">Hotel Booking</div>
                                                    <div class="upload-hint"><span>Click to upload</span> or drag & drop</div>
                                                    <div class="upload-hint">JPG, PNG max 10MB</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="notes-box mb-4">
                            <h6><i class="fas fa-info-circle"></i> Important Notes</h6>
                            <ul>
                                <li>All customers are required to make a deposit as a guarantee.</li>
                                <li>For cars, the deposit is <strong>$130 (2 million IDR)</strong>. It will be refunded once the car is returned.</li>
                                <li>For motorcycles, the deposit is <strong>$32 (500 thousand IDR)</strong>. It will be refunded once the motorcycle is returned.</li>
                                <li>Please download and save your invoice after booking. It will be used as booking proof.</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit Booking
                        </button>

                    </div>
                </form>
            </div>

            <!-- Kanan: Vehicle Summary -->
            <div class="col-lg-5 mb-4">
                <div class="vehicle-sticky">
                    <div class="vehicle-card">
                        <div class="vehicle-card-header">
                            <i class="fas fa-car mr-2"></i> Vehicle Summary
                        </div>

                        <div class="vehicle-img-wrap">
                            <?php if ($armada['gambar_armada']): ?>
                                <img src="<?= BASE_URL ?>/public/assets/img/armada/<?= $armada['gambar_armada'] ?>"
                                    class="img-fluid" alt="<?= $armada['nama_armada'] ?>">
                            <?php else: ?>
                                <i class="fas fa-car fa-5x" style="color:#ddd"></i>
                            <?php endif; ?>
                        </div>

                        <div class="vehicle-details">
                            <div class="vehicle-name"><?= $armada['nama_armada'] ?></div>
                            <div class="vehicle-sub"><?= $armada['merk_armada'] ?> · <?= $armada['tipe_armada'] ?></div>

                            <div class="vehicle-spec">
                                <span class="spec-badge"><i class="fas fa-cog mr-1"></i><?= $armada['transmisi'] ?></span>
                                <span class="spec-badge"><i class="fas fa-calendar mr-1"></i><?= $armada['tahun_armada'] ?></span>
                                <span class="spec-badge"><i class="fas fa-id-card mr-1"></i><?= $armada['plat_armada'] ?></span>
                            </div>

                            <div class="vehicle-price">
                                <div class="price-label">Price per day</div>
                                <div class="price-value">Rp <?= number_format($armada['harga_sewa_perhari'], 0, ',', '.') ?></div>
                            </div>

                            <div class="total-preview" id="totalPreview">
                                <div class="label">Estimated Total</div>
                                <div class="amount" id="totalAmount">-</div>
                                <div style="font-size:.78rem; opacity:.8; margin-top:4px" id="totalDetail"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const hargaPerHari = <?= $armada['harga_sewa_perhari'] ?>;

        // ==============================
        // FILTER PICKUP TIME
        // Jika tanggal pickup adalah hari ini,
        // jam yang sudah lewat akan disabled.
        // ==============================
        function getTodayValue() {
            const now = new Date();

            return now.getFullYear() + '-' +
                String(now.getMonth() + 1).padStart(2, '0') + '-' +
                String(now.getDate()).padStart(2, '0');
        }

        function timeToMinutes(time) {
            const [hour, minute] = time.split(':').map(Number);
            return (hour * 60) + minute;
        }

        function filterPickupTime() {
            const selectedDate = $('#tgl_pinjam').val();
            const pickupSelect = $('#jam_pengambilan');

            if (!selectedDate || pickupSelect.length === 0) {
                return;
            }

            const now = new Date();
            const today = getTodayValue();
            const currentMinutes = (now.getHours() * 60) + now.getMinutes();

            pickupSelect.find('option').each(function() {
                const optionValue = $(this).val();

                // Skip option placeholder
                if (!optionValue) {
                    return;
                }

                // Reset option dulu
                $(this).prop('disabled', false);
                $(this).text(optionValue);

                // Jika tanggal pickup adalah hari ini,
                // disable jam yang sudah lewat / sama dengan jam saat ini
                if (selectedDate === today && timeToMinutes(optionValue) <= currentMinutes) {
                    $(this).prop('disabled', true);
                    $(this).text(optionValue + ' - unavailable');
                }
            });

            // Kalau jam yang sedang dipilih ternyata disabled, kosongkan pilihan
            const selectedOption = pickupSelect.find('option:selected');
            if (selectedOption.prop('disabled')) {
                pickupSelect.val('');
            }
        }

        // Default awal: semua field lanjutan disabled
        $('.booking-extra-input').prop('disabled', true);
        $('.booking-required').prop('required', false);
        $('.doc-input').prop('required', false);

        // Pilih tipe customer
        $('input[name="tipe_customer"]').on('change', function() {
            const selectedType = $(this).val();

            $('.citizenship-card').removeClass('active');
            $(this).closest('.citizenship-card').addClass('active');

            $('#bookingExtraFields').slideDown(250);

            // Enable field lanjutan
            $('.booking-extra-input').prop('disabled', false);
            $('.booking-required').prop('required', true);

            // Terapkan filter jam pickup setelah field aktif
            filterPickupTime();

            // Reset dokumen
            $('.doc-input').prop('required', false).prop('disabled', true).val('');

            // Reset preview upload
            resetUploadPreview();

            if (selectedType === 'WNI') {
                $('#documentWNI').show();
                $('#documentWNA').hide();

                $('.doc-wni').prop('disabled', false).prop('required', true);
                $('.doc-wna').prop('disabled', true).prop('required', false).val('');
            }

            if (selectedType === 'WNA') {
                $('#documentWNI').hide();
                $('#documentWNA').show();

                $('.doc-wna').prop('disabled', false).prop('required', true);
                $('.doc-wni').prop('disabled', true).prop('required', false).val('');
            }

            hitungTotal();
        });

        // Hitung Total
        function hitungTotal() {
            const startValue = $('#tgl_pinjam').val();
            const finishValue = $('#tgl_kembali').val();

            if (!startValue || !finishValue) {
                $('#totalPreview').fadeOut();
                return;
            }

            const p = new Date(startValue);
            const k = new Date(finishValue);

            if (k > p) {
                const hari = Math.round((k - p) / 86400000);
                const total = hari * hargaPerHari;

                $('#totalAmount').text('Rp ' + total.toLocaleString('id-ID'));
                $('#totalDetail').text(hari + ' day(s) × Rp ' + hargaPerHari.toLocaleString('id-ID'));
                $('#totalPreview').fadeIn();
            } else {
                $('#totalPreview').fadeOut();
            }
        }

        $('#tgl_pinjam').on('change', function() {
            $('#tgl_kembali').attr('min', $(this).val());

            // Jika tanggal pickup hari ini, jam yang sudah lewat akan disabled
            filterPickupTime();

            hitungTotal();
        });

        $('#jam_pengambilan').on('focus click', function() {
            filterPickupTime();
        });

        $('#tgl_kembali').on('change', hitungTotal);

        // Toggle Pickup
        function togglePickup(el) {
            if (el.value === 'antar_jemput') {
                $('#delivery_fields').slideDown();
                $('.delivery-input').prop('disabled', false);

                $('#card_ambil').removeClass('selected');
                $('#card_antar').addClass('selected');
            } else {
                $('#delivery_fields').slideUp();
                $('.delivery-input').val('').prop('disabled', true);

                $('#card_antar').removeClass('selected');
                $('#card_ambil').addClass('selected');
            }
        }

        // Select Payment
        function selectPayment(cardId) {
            $('#card_transfer, #card_tunai').removeClass('selected');
            $('#' + cardId).addClass('selected');
        }

        // Preview Upload
        function previewUpload(input, areaId, prevId) {
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = e => {
                    const img = document.getElementById(prevId);
                    img.src = e.target.result;
                    img.style.display = 'block';

                    const placeholderId = 'placeholder_' + areaId.replace('area_', '');
                    const placeholder = document.getElementById(placeholderId);

                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };

                reader.readAsDataURL(file);
            }
        }

        function resetUploadPreview() {
            $('.preview-img').attr('src', '').hide();

            $('#placeholder_ktp_wni').show();
            $('#placeholder_sim').show();
            $('#placeholder_ktp_wna').show();
            $('#placeholder_tiket').show();
            $('#placeholder_hotel').show();
        }
    </script>

</body>

</html>