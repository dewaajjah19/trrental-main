<?php
class AuthController extends Controller {
    private $staffModel;

    public function __construct() {
        session_start();
        $this->staffModel = $this->model('StaffModel');
    }

    public function index() {
        $this->redirect('auth/login');
    }

    public function login() {
        if (isset($_SESSION['staff_id'])) {
            $this->redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $staff = $this->staffModel->findByUsername($username);

            if ($staff && password_verify($password, $staff['password'])) {
                $_SESSION['staff_id']   = $staff['id_staff'];
                $_SESSION['staff_name'] = $staff['nama_staff'];
                $_SESSION['username']   = $staff['username'];
                $this->redirect('dashboard');
            } else {
                $data['error'] = 'Username atau password salah!';
                $this->view('auth/login', $data);
            }
        } else {
            $this->view('auth/login', []);
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}