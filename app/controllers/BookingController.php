<?php
class BookingController extends Controller {
    private $bookingModel;
    private $dashboardModel;

    public function __construct() {
        session_start();
        $this->isLoggedIn();
        $this->bookingModel   = $this->model('BookingModel');
        $this->dashboardModel = $this->model('DashboardModel');
    }

    public function index() {
        $filter = [
            'status'    => $_GET['status'] ?? '',
            'tgl_awal'  => $_GET['tgl_awal'] ?? '',
            'tgl_akhir' => $_GET['tgl_akhir'] ?? '',
        ];

        $data = [
            'title'               => 'Data Booking',
            'activePage'          => 'booking',
            'bookings'            => $this->bookingModel->getAll($filter),
            'filter'              => $filter,
            'totalBookingMenunggu'=> $this->dashboardModel->totalBookingMenunggu(),
        ];
        $this->view('booking/index', $data);
    }

    public function detail($id) {
        $booking = $this->bookingModel->getById($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking tidak ditemukan!';
            $this->redirect('booking');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status_booking'];
            $this->bookingModel->updateStatus($id, $status);

            // Jika selesai, kembalikan status armada jadi tersedia
            if ($status === 'selesai' || $status === 'dibatalkan') {
                $db = getDB();
                $db->query("UPDATE armada SET status_armada = 'tersedia' WHERE id_armada = {$booking['id_armada']}");
            }

            $_SESSION['success'] = 'Status booking berhasil diupdate!';
            $this->redirect('booking/detail/' . $id);
        }

        $data = [
            'title'               => 'Detail Booking',
            'activePage'          => 'booking',
            'booking'             => $booking,
            'totalBookingMenunggu'=> $this->dashboardModel->totalBookingMenunggu(),
        ];
        $this->view('booking/detail', $data);
    }

    public function delete($id) {
        $this->bookingModel->delete($id);
        $_SESSION['success'] = 'Booking berhasil dihapus!';
        $this->redirect('booking');
    }
}