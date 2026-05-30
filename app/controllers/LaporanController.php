<?php
class LaporanController extends Controller {
    private $laporanModel;
    private $dashboardModel;

    public function __construct() {
        session_start();
        $this->isLoggedIn();
        $this->laporanModel   = $this->model('LaporanModel');
        $this->dashboardModel = $this->model('DashboardModel');
    }

    public function index() {
        $tahun  = $_GET['tahun'] ?? date('Y');
        $filter = [
            'tgl_awal'  => $_GET['tgl_awal'] ?? date('Y-m-01'),
            'tgl_akhir' => $_GET['tgl_akhir'] ?? date('Y-m-t'),
            'kegiatan'  => $_GET['kegiatan'] ?? 'semua',
        ];

        $pendapatanBulanan = $this->laporanModel->getPendapatanPerBulan($tahun);
        $armadaTersering   = $this->laporanModel->getArmadaTersering();
        $bookings          = $this->laporanModel->getBookingByFilter($filter);
        $totalPendapatan   = $this->laporanModel->getTotalPendapatanByFilter($filter);

        $data = [
            'title'               => 'Laporan',
            'activePage'          => 'laporan',
            'tahun'               => $tahun,
            'filter'              => $filter,
            'pendapatanBulanan'   => $pendapatanBulanan,
            'armadaTersering'     => $armadaTersering,
            'bookings'            => $bookings,
            'totalPendapatan'     => $totalPendapatan,
            'totalBookingMenunggu'=> $this->dashboardModel->totalBookingMenunggu(),
        ];
        $this->view('laporan/index', $data);
    }
}