<?php
class Absen
{
    private $conn;
    private $table_name = "absen";
    public $IdAbsen;
    public $IdPegawai;
    public $Tanggal;
    public $JamDatang;
    public $JamPulang;
    public $Terlambat;
    public $Keterangan;

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

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE IdPegawai =? and Tanggal=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->IdPegawai);
        $stmt->bindParam(2, $this->Tanggal);
        $stmt->execute();

        return $stmt;
    }

    public function readByTanggal()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE Tanggal=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Tanggal);
        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET IdPegawai=?, Tanggal=?, JamDatang=?, JamPulang=?, Terlambat=?, Keterangan=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->IdPegawai);
        $stmt->bindParam(2, $this->Tanggal);
        $stmt->bindParam(3, $this->JamDatang);
        $stmt->bindParam(4, $this->JamPulang);
        $stmt->bindParam(5, $this->Terlambat);
        $stmt->bindParam(6, $this->Keterangan);
        if ($stmt->execute()) {
            $this->IdAbsen = $this->conn->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET JamPulang=? WHERE IdPegawai=? and Tanggal=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->JamPulang);
        $stmt->bindParam(2, $this->IdPegawai);
        $stmt->bindParam(3, $this->Tanggal);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE IdAbsen=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->IdAbsen);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
