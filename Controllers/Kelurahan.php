<?php
require_once 'Config/DB.php';

class Kelurahan
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        $stmt = $this->pdo->query("SELECT * FROM kelurahan");
        $data = $stmt->fetchAll();
        return $data;
    }

    public function show($id)
    {
        $stmt = $this->pdo->query("SELECT * FROM kelurahan WHERE id=$id");
        $data = $stmt->fetch();
        return $data;
    }

    public function create($data)
    {
        $sql = "INSERT INTO kelurahan (id, nama, kec_id) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['id'], $data['nama'], $data['kec_id']]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE kelurahan SET nama=:nama, kec_id=:kec_id WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nama', $data['nama']);
        $stmt->bindParam(':kec_id', $data['kec_id']);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $this->show($id);
    }

    public function delete($id) {
        $row = $this->show($id);
        $sql = "DELETE FROM kelurahan WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $row;
    }
}

$kelurahan = new Kelurahan($pdo);
