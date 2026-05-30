<?php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = BASE_PATH . '/app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View tidak ditemukan: " . $view);
        }
    }

    protected function model($model) {
        require_once BASE_PATH . '/app/models/' . $model . '.php';
        return new $model();
    }

    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . $url);
        exit;
    }

    protected function isLoggedIn() {
        if (!isset($_SESSION['staff_id'])) {
            $this->redirect('auth/login');
        }
    }
}