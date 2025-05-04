<?php
require_once 'Config/DB.php';

class Periksa
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        try {
            $stmt = $this->pdo->query("SELECT 
                p.id, p.tanggal, p.berat, p.tinggi, p.tensi, p.keterangan,
                ps.nama as nama_pasien, pm.nama as nama_paramedik
                FROM periksa p
                LEFT JOIN pasien ps ON ps.id = p.pasien_id
                LEFT JOIN paramedik pm ON pm.id = p.paramedik_id
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                p.id, p.tanggal, p.berat, p.tinggi, p.tensi, p.keterangan, p.pasien_id, p.paramedik_id,
                ps.nama as nama_pasien, pm.nama as nama_paramedik
                FROM periksa p
                LEFT JOIN pasien ps ON ps.id = p.pasien_id
                LEFT JOIN paramedik pm ON pm.id = p.paramedik_id
                WHERE p.id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: null; // Return null if no data found
        } catch (PDOException $e) {
            die('Query failed: ' . $e->getMessage());
        }
    }

    public function create($data)
    {
        try {
            $this->validateForeignKey('pasien', $data['pasien_id']);
            $this->validateForeignKey('paramedik', $data['paramedik_id']);
    
            $sql = "INSERT INTO periksa (tanggal, berat, tinggi, tensi, keterangan, pasien_id, paramedik_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['tanggal'],
                $data['berat'],
                $data['tinggi'],
                $data['tensi'],
                $data['keterangan'],
                $data['pasien_id'],
                $data['paramedik_id']
            ]);
            echo "Data berhasil disimpan!";
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            die('Insert failed: ' . $e->getMessage());
        } catch (Exception $e) {
            die('Validation failed: ' . $e->getMessage());
        }
    }

    public function update($id, $data)
    {
        try {
            // Validasi pasien_id dan paramedik_id
            $this->validateForeignKey('pasien', $data['pasien_id']);
            $this->validateForeignKey('paramedik', $data['paramedik_id']);

            $sql = "UPDATE periksa SET tanggal=:tanggal, berat=:berat, tinggi=:tinggi, tensi=:tensi, keterangan=:keterangan, pasien_id=:pasien_id, paramedik_id=:paramedik_id WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':tanggal', $data['tanggal']);
            $stmt->bindParam(':berat', $data['berat']);
            $stmt->bindParam(':tinggi', $data['tinggi']);
            $stmt->bindParam(':tensi', $data['tensi']);
            $stmt->bindParam(':keterangan', $data['keterangan']);
            $stmt->bindParam(':pasien_id', $data['pasien_id']);
            $stmt->bindParam(':paramedik_id', $data['paramedik_id']);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            $stmt->execute();
            return $this->show($id);
        } catch (PDOException $e) {
            die('Update failed: ' . $e->getMessage());
        } catch (Exception $e) {
            die('Validation failed: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $row = $this->show($id);
            if (!$row) {
                throw new Exception("Data with ID $id not found.");
            }

            $sql = "DELETE FROM periksa WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $row;
        } catch (PDOException $e) {
            die('Delete failed: ' . $e->getMessage());
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    private function validateForeignKey($table, $id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Invalid foreign key reference: $table.id = $id");
        }
    }
}