<?php
class CustomerController extends Controller {
    private $customerModel;
    private $dashboardModel;

    public function __construct() {
        session_start();
        $this->isLoggedIn();
        $this->customerModel  = $this->model('CustomerModel');
        $this->dashboardModel = $this->model('DashboardModel');
    }

    public function index() {
        $data = [
            'title'               => 'Data Customer',
            'activePage'          => 'customer',
            'customers'           => $this->customerModel->getAll(),
            'totalBookingMenunggu'=> $this->dashboardModel->totalBookingMenunggu(),
        ];
        $this->view('customer/index', $data);
    }

    public function detail($id) {
        $customer = $this->customerModel->getById($id);
        if (!$customer) {
            $_SESSION['error'] = 'Customer tidak ditemukan!';
            $this->redirect('customer');
        }

        $data = [
            'title'               => 'Detail Customer',
            'activePage'          => 'customer',
            'customer'            => $customer,
            'bookings'            => $this->customerModel->getBookingByCust($id),
            'totalBookingMenunggu'=> $this->dashboardModel->totalBookingMenunggu(),
        ];
        $this->view('customer/detail', $data);
    }

    public function edit($id) {
        $customer = $this->customerModel->getById($id);
        if (!$customer) {
            $_SESSION['error'] = 'Customer tidak ditemukan!';
            $this->redirect('customer');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->customerModel->update($id, [
                'nama_cust'      => $_POST['nama_cust'],
                'no_tlp'         => $_POST['no_tlp'],
                'alamat'         => $_POST['alamat'],
                'country_origin' => $_POST['country_origin'],
            ]);
            $_SESSION['success'] = 'Data customer berhasil diupdate!';
            $this->redirect('customer/detail/' . $id);
        }

        $data = [
            'title'               => 'Edit Customer',
            'activePage'          => 'customer',
            'customer'            => $customer,
            'totalBookingMenunggu'=> $this->dashboardModel->totalBookingMenunggu(),
        ];
        $this->view('customer/edit', $data);
    }

    public function delete($id) {
        $this->customerModel->delete($id);
        $_SESSION['success'] = 'Customer berhasil dihapus!';
        $this->redirect('customer');
    }
}