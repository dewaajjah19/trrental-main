<?php
class LaporanModel extends Model {

    public function getPendapatanPerBulan($tahun) {
        $result = $this->db->query("
            SELECT MONTH(tgl_pinjam) as bulan, SUM(total_bayar) as total
            FROM booking
            WHERE YEAR(tgl_pinjam) = $tahun AND status_booking = 'selesai'
            GROUP BY MONTH(tgl_pinjam)
            ORDER BY bulan ASC
        ");
        $data = array_fill(1, 12, 0);
        foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
            $data[(int)$row['bulan']] = (int)$row['total'];
        }
        return $data;
    }

    public function getArmadaTersering() {
        $result = $this->db->query("
            SELECT a.nama_armada, COUNT(b.id_booking) as total_disewa
            FROM booking b
            JOIN armada a ON b.id_armada = a.id_armada
            GROUP BY b.id_armada
            ORDER BY total_disewa DESC
            LIMIT 6
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookingByFilter($filter = []) {
        $where = "WHERE 1=1";
        if (!empty($filter['tgl_awal'])) {
            $where .= " AND b.tgl_pinjam >= '{$filter['tgl_awal']}'";
        }
        if (!empty($filter['tgl_akhir'])) {
            $where .= " AND b.tgl_pinjam <= '{$filter['tgl_akhir']}'";
        }
        if (!empty($filter['kegiatan']) && $filter['kegiatan'] !== 'semua') {
            if ($filter['kegiatan'] === 'penyewaan') {
                $where .= " AND b.status_booking IN ('disewa','selesai')";
            } elseif ($filter['kegiatan'] === 'pendapatan') {
                $where .= " AND b.status_booking = 'selesai'";
            }
        }

        $result = $this->db->query("
            SELECT b.*, c.nama_cust, a.nama_armada
            FROM booking b
            JOIN cust c ON b.id_cust = c.id_cust
            JOIN armada a ON b.id_armada = a.id_armada
            $where
            ORDER BY b.created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalPendapatanByFilter($filter = []) {
        $where = "WHERE status_booking = 'selesai'";
        if (!empty($filter['tgl_awal'])) {
            $where .= " AND tgl_pinjam >= '{$filter['tgl_awal']}'";
        }
        if (!empty($filter['tgl_akhir'])) {
            $where .= " AND tgl_pinjam <= '{$filter['tgl_akhir']}'";
        }
        $result = $this->db->query("SELECT SUM(total_bayar) as total FROM booking $where");
        return $result->fetch_assoc()['total'] ?? 0;
    }
}