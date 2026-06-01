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
                $_SESSION['error'] = 'Gagal upload gambar!';
                $this->redirect('armada/create');
                return;
            }

            $this->armadaModel->create([
                'jenis_armada'     => $_POST['jenis_armada'],
                'nama_armada'      => $_POST['nama_armada'],
                'merk_armada'      => $_POST['merk_armada'],
                'tipe_armada'      => $_POST['tipe_armada'],
                'plat_armada'      => $_POST['plat_armada'],
                'tahun_armada'     => $_POST['tahun_armada'],
                'transmisi'        => $_POST['transmisi'],
                'harga_sewa_perhari' => $_POST['harga_sewa_perhari'],
                'status_armada'    => $_POST['status_armada'],
                'gambar_armada'    => $gambar,
            ]);

            $_SESSION['success'] = 'Armada berhasil ditambahkan!';
            $this->redirect('armada');
        } else {
            $data = [
                'title'      => 'Tambah Armada',
                'activePage' => 'armada',
            ];
            $this->view('armada/create', $data);
        }
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
            $gambar = $armada['gambar_armada']; // gunakan gambar lama dulu
            $imageNote = '';

            if (!empty($_FILES['gambar_armada']['name'])) {

                // Vercel filesystem read-only, jadi gambar armada tidak disimpan di Vercel.
                // Data lain tetap boleh diupdate.
                if ($this->isVercelRuntime()) {
                    $imageNote = ' Gambar tidak diubah karena upload gambar armada tidak disimpan di Vercel.';
                } else {
                    $uploadedGambar = $this->uploadGambar();

                    if ($uploadedGambar === false) {
                        $_SESSION['error'] = 'Gagal upload gambar!';
                        $this->redirect('armada/edit/' . $id);
                        return;
                    }

                    // Hapus gambar lama hanya di localhost / hosting writable
                    if ($gambar && file_exists(BASE_PATH . '/public/assets/img/armada/' . $gambar)) {
                        unlink(BASE_PATH . '/public/assets/img/armada/' . $gambar);
                    }

                    $gambar = $uploadedGambar;
                }
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

            $_SESSION['success'] = 'Armada berhasil diupdate!' . $imageNote;
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

        // Di Vercel, filesystem read-only. Jangan unlink file fisik.
        // Di localhost, file gambar tetap boleh dihapus.
        if (!$this->isVercelRuntime() && !empty($armada['gambar_armada'])) {
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

    private function isVercelRuntime()
    {
        $host = $_SERVER['HTTP_HOST'] ?? '';

        return getenv('VERCEL') || strpos($host, 'vercel.app') !== false;
    }

    private function uploadGambar()
    {
        if (empty($_FILES['gambar_armada']['name'])) {
            return null;
        }

        // Vercel filesystem read-only.
        // Untuk versi demo/testing, upload gambar armada di Vercel di-skip.
        // Localhost tetap menyimpan gambar normal.
        if ($this->isVercelRuntime()) {
            return null;
        }

        $uploadDir = BASE_PATH . '/public/assets/img/armada/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['gambar_armada']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            return false;
        }

        $fileName = uniqid('armada_') . '.' . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['gambar_armada']['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }
}
