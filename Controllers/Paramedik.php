<?php
require_once 'Config/DB.php';

class Paramedik
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT 
            p.*, uk.nama as nama_unit_kerja
            FROM paramedik p
            LEFT JOIN unit_kerja uk ON uk.id = p.unit_kerja_id
        ");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function show($id)
    {
        $stmt = $this->pdo->prepare("SELECT 
            p.*, uk.nama as nama_unit_kerja
            FROM paramedik p
            LEFT JOIN unit_kerja uk ON uk.id = p.unit_kerja_id
            WHERE p.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null; // Return null if no data found
    }

    public function create($data)
    {
        try {
            // Validasi unit_kerja_id
            $this->validateForeignKey('unit_kerja', $data['unit_kerja_id']);
    
            $sql = "INSERT INTO paramedik (nama, gender, tmp_lahir, tgl_lahir, kategori, telepon, alamat, unit_kerja_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['nama'], 
                $data['gender'], 
                $data['tmp_lahir'], 
                $data['tgl_lahir'], 
                $data['kategori'], 
                $data['telepon'], 
                $data['alamat'], 
                $data['unit_kerja_id']
            ]);
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
            // Validasi unit_kerja_id
            $this->validateForeignKey('unit_kerja', $data['unit_kerja_id']);
    
            $sql = "UPDATE paramedik SET nama=:nama, gender=:gender, tmp_lahir=:tmp_lahir, tgl_lahir=:tgl_lahir, kategori=:kategori, telepon=:telepon, alamat=:alamat, unit_kerja_id=:unit_kerja_id WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
    
            $stmt->bindParam(':nama', $data['nama']);
            $stmt->bindParam(':gender', $data['gender']);
            $stmt->bindParam(':tmp_lahir', $data['tmp_lahir']);
            $stmt->bindParam(':tgl_lahir', $data['tgl_lahir']);
            $stmt->bindParam(':kategori', $data['kategori']);
            $stmt->bindParam(':telepon', $data['telepon']);
            $stmt->bindParam(':alamat', $data['alamat']);
            $stmt->bindParam(':unit_kerja_id', $data['unit_kerja_id']);
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

            $sql = "DELETE FROM paramedik WHERE id = :id";
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

$paramedik = new Paramedik($pdo);