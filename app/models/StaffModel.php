<?php
class StaffModel extends Model {
    protected $table = 'staff';

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}