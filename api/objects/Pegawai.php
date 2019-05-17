<?php
class Pegawai
{
    private $conn;
    private $table_name = "pegawai";
    public $IdPegawai;
    public $NIP;
    public $Nama;
    public $JK;
    public $Kontak;
    public $Alamat;
    public $Email;
    public $Password;
    public $Jabatan;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function Login()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Email= ? and Password=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Email);
        $stmt->bindParam(2, $this->Password);
        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = $row[0];
            $this->Nama = $data['Nama'];
            $this->Email = $data['Email'];
            return true;
        } else {
            return false;
        }

    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET NIP=?, Nama=?, JK=?, Kontak=?, Alamat=?, Email=?, Password=?, Jabatan=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->NIP);
        $stmt->bindParam(2, $this->Nama);
        $stmt->bindParam(3, $this->JK);
        $stmt->bindParam(4, $this->Kontak);
        $stmt->bindParam(5, $this->Alamat);
        $stmt->bindParam(6, $this->Email);
        $stmt->bindParam(7, $this->Password);
        $stmt->bindParam(8, $this->Jabatan);
        if ($stmt->execute()) {
            $this->IdPegawai = $this->conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . "SET NIP=?, Nama=?, JK=?, Kontak=?, Alamat=?, Email=?, Password=?, Jabatan=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->NIP);
        $stmt->bindParam(2, $this->Nama);
        $stmt->bindParam(3, $this->JK);
        $stmt->bindParam(4, $this->Kontak);
        $stmt->bindParam(5, $this->Alamat);
        $stmt->bindParam(6, $this->Email);
        $stmt->bindParam(7, $this->Password);
        $stmt->bindParam(8, $this->Jabatan);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE IdPegawai=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->IdPegawai);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
