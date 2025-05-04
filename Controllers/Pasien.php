<?php
require_once 'Config/DB.php';

class Pasien
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT 
            p.*, k.nama as nama_kelurahan
            FROM pasien p
            LEFT JOIN kelurahan k ON k.id = p.kelurahan_id
        ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    public function show($id)
    {
        $stmt = $this->pdo->prepare("SELECT 
            p.*, k.nama as nama_kelurahan
            FROM pasien p
            LEFT JOIN kelurahan k ON k.id = p.kelurahan_id
            WHERE p.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO pasien (kode, nama, tmp_lahir, tgl_lahir, gender, email, alamat, kelurahan_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['kode'],
                $data['nama'],
                $data['tmp_lahir'],
                $data['tgl_lahir'],
                $data['gender'],
                $data['email'],
                $data['alamat'],
                $data['kelurahan_id']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Gagal menambahkan data pasien: " . $e->getMessage());
        }
    }

    public function update($id, $data)
    {
        $sql = "UPDATE pasien SET kode=:kode, nama=:nama, tmp_lahir=:tmp_lahir, tgl_lahir=:tgl_lahir, gender=:gender, email=:email, alamat=:alamat, kelurahan_id=:kelurahan_id WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':kode', $data['kode']);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':tmp_lahir', $data['tmp_lahir']);
        $stmt->bindParam(':tgl_lahir', $data['tgl_lahir']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':alamat', $data['alamat']);
        $stmt->bindParam(':kelurahan_id', $data['kelurahan_id']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        return $this->show($id);
    }

    public function getLatestPasien()
    {
        $stmt = $this->pdo->query("SELECT * FROM pasien ORDER BY id DESC LIMIT 1");
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function delete($id)
    {
        try {
            // Periksa apakah ada data terkait di tabel lain
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM periksa WHERE pasien_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                throw new Exception("Data pasien tidak dapat dihapus karena masih digunakan di tabel periksa.");
            }

            // Hapus data pasien
            $sql = "DELETE FROM pasien WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            throw new Exception("Gagal menghapus data pasien: " . $e->getMessage());
        }
    }
}