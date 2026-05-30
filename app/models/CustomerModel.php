<?php
class CustomerModel extends Model {
    protected $table = 'cust';

    public function getAll() {
        $result = $this->db->query("SELECT * FROM {$this->table} ORDER BY id_cust DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_cust = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getBookingByCust($id_cust) {
        $stmt = $this->db->prepare("
            SELECT b.*, a.nama_armada, a.gambar_armada
            FROM booking b
            JOIN armada a ON b.id_armada = a.id_armada
            WHERE b.id_cust = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->bind_param('i', $id_cust);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET 
            nama_cust = ?, no_tlp = ?, alamat = ?, country_origin = ?
            WHERE id_cust = ?");
        $stmt->bind_param(
            'ssssi',
            $data['nama_cust'],
            $data['no_tlp'],
            $data['alamat'],
            $data['country_origin'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_cust = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}