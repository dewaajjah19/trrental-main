<?php
class HomeController extends Controller
{
    public function productsAll($jenis = 'mobil')
    {
        $armada = $this->armadaModel->getArmadaTersedia();

        $filtered = array_filter($armada, function ($a) use ($jenis) {
            return strtolower($a['jenis_armada'] ?? '') === strtolower($jenis);
        });

        $data = [
            'title'  => 'Products - TR Rental',
            'jenis'  => $jenis,
            'armada' => $filtered
        ];

        $this->view('home/products_all', $data);
    }

    public function invoice($id_booking = null)
    {
        if (!$id_booking) {
            $this->redirect('home/products');
        }

        $booking = $this->bookingModel->getById($id_booking);

        if (!$booking) {
            $this->redirect('home/products');
        }

        $this->view('home/invoice', [
            'title' => 'Invoice Booking',
            'booking' => $booking
        ]);
    }

    private $armadaModel;
    private $bookingModel;

    public function __construct()
    {
        $this->armadaModel = $this->model('ArmadaModel');
        $this->bookingModel = $this->model('BookingModel');
    }

    public function index()
    {
        $data = [
            'title'  => 'TR Rental - Sewa Kendaraan',
            'armada' => $this->armadaModel->getArmadaTersedia(),
        ];
        $this->view('home/index', $data);
    }

    public function products()
    {
        $data = [
            'title'  => 'Products - TR Rental',
            'armada' => $this->armadaModel->getArmadaTersedia(),
        ];
        $this->view('home/products', $data);
    }

    public function booking($id_armada = null)
    {
        if (!$id_armada) $this->redirect('');

        $armada = $this->armadaModel->getById($id_armada);
        if (!$armada || $armada['status_armada'] !== 'tersedia') {
            $this->redirect('home/products');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = getDB();

            // ==============================
            // VALIDASI BASIC
            // ==============================
            $nama    = trim($_POST['nama_cust'] ?? '');
            $no_tlp  = trim($_POST['no_tlp'] ?? '');
            $alamat  = trim($_POST['alamat'] ?? '');
            $country = trim($_POST['country_origin'] ?? '');

            $tipe_customer = $_POST['tipe_customer'] ?? '';
            if (!in_array($tipe_customer, ['WNI', 'WNA'])) {
                die('Customer type tidak valid.');
            }

            $tgl_pinjam       = $_POST['tgl_pinjam'] ?? '';
            $jam_pengambilan  = $_POST['jam_pengambilan'] ?? '';
            $tgl_kembali      = $_POST['tgl_kembali'] ?? '';
            $jam_pengembalian = $_POST['jam_pengembalian'] ?? '';

            if (!$nama || !$no_tlp || !$alamat || !$country || !$tgl_pinjam || !$jam_pengambilan || !$tgl_kembali || !$jam_pengembalian) {
                die('Data booking belum lengkap.');
            }

            // Pastikan tanggal kembali lebih besar dari tanggal pinjam
            $jumlah_hari = (int)((strtotime($tgl_kembali) - strtotime($tgl_pinjam)) / 86400);

            if ($jumlah_hari <= 0) {
                die('Tanggal kembali harus lebih besar dari tanggal pinjam.');
            }

            $total_bayar = $jumlah_hari * (int)$armada['harga_sewa_perhari'];

            // ==============================
            // SIMPAN CUSTOMER
            // ==============================
            $stmt = $db->prepare("
            INSERT INTO cust (nama_cust, no_tlp, alamat, country_origin)
            VALUES (?, ?, ?, ?)
        ");

            if (!$stmt) {
                die('Prepare customer gagal: ' . $db->error);
            }

            $stmt->bind_param('ssss', $nama, $no_tlp, $alamat, $country);
            $stmt->execute();
            $id_cust = $db->insert_id;

            // ==============================
            // UPLOAD DOKUMEN
            // ==============================
            $uploadDir = BASE_PATH . '/public/assets/img/dokumen/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $foto_sim   = null;
            $foto_ktp   = null;
            $foto_tiket = null;
            $foto_hotel = null;

            if ($tipe_customer === 'WNI') {
                // WNI hanya wajib upload KTP / kartu identitas
                $foto_ktp = $this->uploadDokumen('foto_ktp', $uploadDir, true);
            }

            if ($tipe_customer === 'WNA') {
                // WNA wajib upload semua dokumen
                $foto_sim   = $this->uploadDokumen('foto_sim', $uploadDir, true);
                $foto_ktp   = $this->uploadDokumen('foto_ktp', $uploadDir, true);
                $foto_tiket = $this->uploadDokumen('foto_tiket', $uploadDir, true);
                $foto_hotel = $this->uploadDokumen('foto_hotel', $uploadDir, true);
            }

            // ==============================
            // DATA BOOKING
            // ==============================
            $metode_pengambilan = $_POST['metode_pengambilan'] ?? 'ambil_sendiri';
            $titik_jemput       = $_POST['titik_jemput'] ?? '';
            $alamat_pengantaran = $_POST['alamat_pengantaran'] ?? '';
            $metode_pembayaran  = $_POST['metode_pembayaran'] ?? 'transfer';

            // Kalau ambil sendiri, kosongkan field pickup/drop address
            if ($metode_pengambilan === 'ambil_sendiri') {
                $titik_jemput = '';
                $alamat_pengantaran = '';
            }

            // ==============================
            // SIMPAN BOOKING
            // ==============================
            $stmt2 = $db->prepare("
            INSERT INTO booking 
            (
                id_cust,
                tipe_customer,
                id_armada,
                tgl_pinjam,
                jam_pengambilan,
                tgl_kembali,
                jam_pengembalian,
                jumlah_hari,
                metode_pengambilan,
                titik_jemput,
                alamat_pengantaran,
                metode_pembayaran,
                status_booking,
                total_bayar,
                foto_sim,
                foto_ktp,
                foto_tiket,
                foto_hotel,
                created_at
            )
            VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'menunggu', ?, ?, ?, ?, ?, NOW())
        ");

            if (!$stmt2) {
                die('Prepare booking gagal: ' . $db->error);
            }

            $stmt2->bind_param(
                'isissssissssissss',
                $id_cust,
                $tipe_customer,
                $id_armada,
                $tgl_pinjam,
                $jam_pengambilan,
                $tgl_kembali,
                $jam_pengembalian,
                $jumlah_hari,
                $metode_pengambilan,
                $titik_jemput,
                $alamat_pengantaran,
                $metode_pembayaran,
                $total_bayar,
                $foto_sim,
                $foto_ktp,
                $foto_tiket,
                $foto_hotel
            );

            $stmt2->execute();
            $id_booking = $db->insert_id;

            // ==============================
            // UPDATE STATUS ARMADA
            // ==============================
            $stmt3 = $db->prepare("UPDATE armada SET status_armada = 'disewa' WHERE id_armada = ?");
            $stmt3->bind_param('i', $id_armada);
            $stmt3->execute();

            // Redirect ke halaman sukses dengan id booking
            $this->redirect('home/sukses/' . $id_booking);
        } else {
            $data = [
                'title'  => 'Book Your Vehicle - TR Rental',
                'armada' => $armada,
            ];

            $this->view('home/booking', $data);
        }
    }


    public function sukses($id_booking = null)
    {
        $this->view('home/sukses', [
            'title' => 'Booking Berhasil',
            'id_booking' => $id_booking
        ]);
    }


    private function uploadDokumen($field, $uploadDir, $required = false)
    {
        if (empty($_FILES[$field]['name'])) {
            if ($required) {
                die('Dokumen wajib belum diupload: ' . $field);
            }

            return null;
        }

        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            die('Gagal upload dokumen: ' . $field);
        }

        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            die('Format file tidak valid untuk ' . $field . '. Gunakan JPG, JPEG, PNG, atau WEBP.');
        }

        // Maksimal 10MB
        if ($_FILES[$field]['size'] > 10 * 1024 * 1024) {
            die('Ukuran file terlalu besar untuk ' . $field . '. Maksimal 10MB.');
        }

        $fileName = uniqid($field . '_') . '.' . $ext;
        $targetPath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
            die('Gagal menyimpan file: ' . $field);
        }

        return $fileName;
    }
}
