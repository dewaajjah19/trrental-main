<?php
class ArmadaController extends Controller
{
    private $armadaModel;

    public function __construct()
    {
        session_start();
        $this->isLoggedIn();
        $this->armadaModel = $this->model('ArmadaModel');
    }

    public function index()
    {
        $data = [
            'title'      => 'Armada',
            'activePage' => 'armada',
            'armada'     => $this->armadaModel->getAll(),
        ];
        $this->view('armada/index', $data);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gambar = $this->uploadGambar();

            if ($gambar === false) {
                $_SESSION['error'] = 'Gagal upload gambar! Gunakan format JPG, JPEG, PNG, atau WEBP.';
                $this->redirect('armada/create');
                return;
            }

            $this->armadaModel->create([
                'jenis_armada'       => $_POST['jenis_armada'],
                'nama_armada'        => $_POST['nama_armada'],
                'merk_armada'        => $_POST['merk_armada'],
                'tipe_armada'        => $_POST['tipe_armada'],
                'plat_armada'        => $_POST['plat_armada'],
                'tahun_armada'       => $_POST['tahun_armada'],
                'transmisi'          => $_POST['transmisi'],
                'harga_sewa_perhari' => $_POST['harga_sewa_perhari'],
                'status_armada'      => $_POST['status_armada'],
                'gambar_armada'      => $gambar,
            ]);

            $_SESSION['success'] = 'Armada berhasil ditambahkan!';
            $this->redirect('armada');
            return;
        }

        $data = [
            'title'      => 'Tambah Armada',
            'activePage' => 'armada',
        ];
        $this->view('armada/create', $data);
    }

    public function edit($id)
    {
        $armada = $this->armadaModel->getById($id);

        if (!$armada) {
            $_SESSION['error'] = 'Armada tidak ditemukan!';
            $this->redirect('armada');
            return;
        }

        // Armada yang sedang disewa tidak boleh diedit
        if (($armada['status_armada'] ?? '') === 'disewa') {
            $_SESSION['error'] = 'Armada sedang disewa dan tidak dapat diedit.';
            $this->redirect('armada');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gambar = $armada['gambar_armada']; // gunakan gambar lama jika tidak upload gambar baru

            if (!empty($_FILES['gambar_armada']['name'])) {
                $uploadedGambar = $this->uploadGambar();

                if ($uploadedGambar === false) {
                    $_SESSION['error'] = 'Gagal upload gambar! Gunakan format JPG, JPEG, PNG, atau WEBP.';
                    $this->redirect('armada/edit/' . $id);
                    return;
                }

                // Hapus gambar lama hanya jika:
                // 1. Berjalan di localhost / hosting writable
                // 2. Gambar lama bukan URL Blob
                if (
                    !$this->isVercelRuntime() &&
                    !empty($gambar) &&
                    !$this->isUrl($gambar)
                ) {
                    $oldPath = BASE_PATH . '/public/assets/img/armada/' . $gambar;

                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $gambar = $uploadedGambar;
            }

            $this->armadaModel->update($id, [
                'jenis_armada'       => $_POST['jenis_armada'],
                'nama_armada'        => $_POST['nama_armada'],
                'merk_armada'        => $_POST['merk_armada'],
                'tipe_armada'        => $_POST['tipe_armada'],
                'plat_armada'        => $_POST['plat_armada'],
                'tahun_armada'       => $_POST['tahun_armada'],
                'transmisi'          => $_POST['transmisi'],
                'harga_sewa_perhari' => $_POST['harga_sewa_perhari'],
                'status_armada'      => $_POST['status_armada'],
                'gambar_armada'      => $gambar,
            ]);

            $_SESSION['success'] = 'Armada berhasil diupdate!';
            $this->redirect('armada');
            return;
        }

        $data = [
            'title'      => 'Edit Armada',
            'activePage' => 'armada',
            'armada'     => $armada,
        ];
        $this->view('armada/edit', $data);
    }

    public function delete($id)
    {
        $armada = $this->armadaModel->getById($id);

        if (!$armada) {
            $_SESSION['error'] = 'Armada tidak ditemukan!';
            $this->redirect('armada');
            return;
        }

        // Armada yang sedang disewa tidak boleh dihapus
        if (($armada['status_armada'] ?? '') === 'disewa') {
            $_SESSION['error'] = 'Armada sedang disewa dan tidak dapat dihapus.';
            $this->redirect('armada');
            return;
        }

        // Hapus file fisik hanya di localhost.
        // Di Vercel, file gambar dari Blob tidak dihapus dari sini.
        if (
            !$this->isVercelRuntime() &&
            !empty($armada['gambar_armada']) &&
            !$this->isUrl($armada['gambar_armada'])
        ) {
            $filePath = BASE_PATH . '/public/assets/img/armada/' . $armada['gambar_armada'];

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->armadaModel->delete($id);

        $_SESSION['success'] = 'Armada berhasil dihapus!';
        $this->redirect('armada');
        return;
    }

    // =====================================================
    // HELPER: DETEKSI VERCEL
    // Digunakan untuk membedakan alur upload localhost dan Vercel.
    // Localhost bisa simpan file ke folder public.
    // Vercel tidak bisa, jadi harus upload ke Blob.
    // =====================================================
    private function isVercelRuntime()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';

        return getenv('VERCEL') || strpos($host, 'vercel.app') !== false;
    }

    // =====================================================
    // HELPER: CEK APAKAH VALUE ADALAH URL
    // Digunakan agar sistem tahu apakah gambar_armada adalah:
    // - URL Blob, contoh https://...blob.vercel-storage.com/...
    // - nama file lokal, contoh armada_xxx.jpg
    // =====================================================
    private function isUrl($value)
    {
        return is_string($value) && preg_match('/^https?:\/\//i', $value);
    }

    // =====================================================
    // UPLOAD GAMBAR ARMADA
    //
    // Localhost:
    // - File disimpan ke public/assets/img/armada/
    // - Database menyimpan nama file
    //
    // Vercel:
    // - File dikirim ke Vercel Blob
    // - Database menyimpan URL Blob
    // =====================================================
    private function uploadGambar()
    {
        if (empty($_FILES['gambar_armada']['name'])) {
            return null;
        }

        if (!isset($_FILES['gambar_armada']['error']) || $_FILES['gambar_armada']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $ext = strtolower(pathinfo($_FILES['gambar_armada']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            return false;
        }

        // Batasi ukuran file untuk upload online/testing
        $maxSize = 4 * 1024 * 1024; // 4 MB
        if ($_FILES['gambar_armada']['size'] > $maxSize) {
            return false;
        }

        if ($this->isVercelRuntime()) {
            return $this->uploadGambarToVercelBlob($ext);
        }

        // Localhost / hosting biasa
        $uploadDir = BASE_PATH . '/public/assets/img/armada/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid('armada_') . '.' . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['gambar_armada']['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }

    // =====================================================
    // UPLOAD GAMBAR ARMADA KE VERCEL BLOB
    //
    // Digunakan hanya saat project berjalan di Vercel.
    // Return value-nya adalah URL public Blob yang disimpan ke database.
    // =====================================================
    private function uploadGambarToVercelBlob($ext)
    {
        $token = getenv('BLOB_READ_WRITE_TOKEN');

        if (!$token) {
            return false;
        }

        $storeId = $this->getVercelBlobStoreId($token);

        if (!$storeId) {
            return false;
        }

        $tmpPath = $_FILES['gambar_armada']['tmp_name'];

        if (!is_uploaded_file($tmpPath)) {
            return false;
        }

        $fileBytes = file_get_contents($tmpPath);

        if ($fileBytes === false) {
            return false;
        }

        $fileName = uniqid('armada_') . '.' . $ext;
        $pathname = 'armada/' . date('Y/m/d') . '/' . $fileName;

        $mimeType = $this->getMimeTypeFromExtension($ext);
        $endpoint = 'https://vercel.com/api/blob/?pathname=' . rawurlencode($pathname);

        $requestId = $storeId . ':' . time() . ':' . uniqid();

        $headers = [
            'Authorization: Bearer ' . $token,
            'x-api-version: 12',
            'x-vercel-blob-store-id: ' . $storeId,
            'x-api-blob-request-id: ' . $requestId,
            'x-vercel-blob-access: public',
            'x-content-type: ' . $mimeType,
            'x-add-random-suffix: 0',
            'x-content-length: ' . strlen($fileBytes),
            'Content-Type: application/octet-stream',
            'Content-Length: ' . strlen($fileBytes),
        ];

        $ch = curl_init($endpoint);

        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_POSTFIELDS     => $fileBytes,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode < 200 || $httpCode >= 300 || empty($result['url'])) {
            return false;
        }

        return $result['url'];
    }

    // =====================================================
    // HELPER: AMBIL STORE ID DARI TOKEN BLOB
    // Digunakan oleh proses upload langsung dari PHP ke Vercel Blob.
    // =====================================================
    private function getVercelBlobStoreId($token)
    {
        $parts = explode('_', $token);
        $storeId = $parts[3] ?? '';

        if (strpos($storeId, 'store_') === 0) {
            $storeId = substr($storeId, 6);
        }

        return $storeId;
    }

    // =====================================================
    // HELPER: MIME TYPE GAMBAR
    // Digunakan agar Blob tahu tipe file yang diupload.
    // =====================================================
    private function getMimeTypeFromExtension($ext)
    {
        $mimeTypes = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'webp' => 'image/webp',
        ];

        return $mimeTypes[$ext] ?? 'application/octet-stream';
    }
}
