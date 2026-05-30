<?php
class DashboardModel extends Model {
    public function totalArmada() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM armada");
        return $result->fetch_assoc()['total'];
    }

    public function totalCustomer() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM cust");
        return $result->fetch_assoc()['total'];
    }

    public function totalBooking() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM booking");
        return $result->fetch_assoc()['total'];
    }

    public function totalStaff() {
        $result = $this->db->query("SELECT COUNT(*) as total FROM staff");
        return $result->fetch_assoc()['total'];
    }

    public function totalPendapatan() {
        $result = $this->db->query("SELECT SUM(total_bayar) as total FROM booking WHERE status_booking = 'selesai'");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    public function bookingTerbaru() {
        $result = $this->db->query("
            SELECT b.*, c.nama_cust, a.nama_armada 
            FROM booking b
            JOIN cust c ON b.id_cust = c.id_cust
            JOIN armada a ON b.id_armada = a.id_armada
            ORDER BY b.created_at DESC
            LIMIT 5
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalBookingMenunggu() {
    $result = $this->db->query("SELECT COUNT(*) as total FROM booking WHERE status_booking = 'menunggu'");
    return $result->fetch_assoc()['total'];
}
}