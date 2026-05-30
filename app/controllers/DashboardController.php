<?php
class DashboardController extends Controller
{
    private $dashboardModel;
    private $armadaModel;

    public function __construct()
    {
        session_start();
        $this->isLoggedIn();
        $this->dashboardModel = $this->model('DashboardModel');
        $this->armadaModel = $this->model('ArmadaModel');
    }

    public function index()
    {
        $data = [
            'title'          => 'Dashboard',
            'activePage'     => 'dashboard',
            'totalArmada'    => $this->dashboardModel->totalArmada(),
            'totalCustomer'  => $this->dashboardModel->totalCustomer(),
            'totalBooking'   => $this->dashboardModel->totalBooking(),
            'totalStaff'     => $this->dashboardModel->totalStaff(),
            'totalPendapatan' => $this->dashboardModel->totalPendapatan(),
            'bookingTerbaru' => $this->dashboardModel->bookingTerbaru(),
        ];
        $this->view('dashboard/index', $data);
    }

    public function products()
    {
        $data = [
            'title'  => 'Products - TR Rental',
            'armada' => $this->armadaModel->getArmadaTersedia(),
        ];
        $this->view('home/products', $data);
    }
}
