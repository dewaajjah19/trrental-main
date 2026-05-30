<?php
class BookingModel extends Model {
    protected $table = 'booking';

    public function getAll($filter = []) {
        $where = "WHERE 1=1";
        if (!empty($filter['status'])) {
            $where .= " AND b.status_booking = '{$filter['status']}'";
        }
        if (!empty($filter['tgl_awal'])) {
            $where .= " AND b.tgl_pinjam >= '{$filter['tgl_awal']}'";
        }
        if (!empty($filter['tgl_akhir'])) {
            $where .= " AND b.tgl_pinjam <= '{$filter['tgl_akhir']}'";
        }

        $result = $this->db->query("
            SELECT b.*, c.nama_cust, c.no_tlp, a.nama_armada, a.gambar_armada
            FROM {$this->table} b
            JOIN cust c ON b.id_cust = c.id_cust
            JOIN armada a ON b.id_armada = a.id_armada
            $where
            ORDER BY b.created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT b.*, c.nama_cust, c.no_tlp, c.alamat, c.country_origin,
                   a.nama_armada, a.tipe_armada, a.gambar_armada, a.harga_sewa_perhari
            FROM {$this->table} b
            JOIN cust c ON b.id_cust = c.id_cust
            JOIN armada a ON b.id_armada = a.id_armada
            WHERE b.id_booking = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET status_booking = ? WHERE id_booking = ?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_booking = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}