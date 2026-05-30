<?php
class ArmadaModel extends Model
{
    protected $table = 'armada';

    public function getAll()
    {
        $result = $this->db->query("SELECT * FROM {$this->table} ORDER BY id_armada DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getArmadaTersedia()
    {
        $result = $this->db->query("SELECT * FROM {$this->table} WHERE status_armada = 'tersedia' ORDER BY id_armada DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id_armada = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (jenis_armada, nama_armada, merk_armada, tipe_armada, plat_armada, tahun_armada, 
             transmisi, harga_sewa_perhari, status_armada, gambar_armada) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            'sssssissss',
            $data['jenis_armada'],
            $data['nama_armada'],
            $data['merk_armada'],
            $data['tipe_armada'],
            $data['plat_armada'],
            $data['tahun_armada'],
            $data['transmisi'],
            $data['harga_sewa_perhari'],
            $data['status_armada'],
            $data['gambar_armada']
        );
        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET 
            jenis_armada = ?, nama_armada = ?, merk_armada = ?, tipe_armada = ?, plat_armada = ?, 
            tahun_armada = ?, transmisi = ?, harga_sewa_perhari = ?, 
            status_armada = ?, gambar_armada = ?
            WHERE id_armada = ?");
        $stmt->bind_param(
            'sssssissssi',
            $data['jenis_armada'],
            $data['nama_armada'],
            $data['merk_armada'],
            $data['tipe_armada'],
            $data['plat_armada'],
            $data['tahun_armada'],
            $data['transmisi'],
            $data['harga_sewa_perhari'],
            $data['status_armada'],
            $data['gambar_armada'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_armada = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
