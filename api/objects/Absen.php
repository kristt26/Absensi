<?php
class Absen
{
    private $conn;
    private $table_name="absen";
    public $IdAbsen;
    public $IdPegawai;
    public $Tanggal;
    public $JamDatang;
    public $JamPulang;
    public $Keterangan;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM ".$this->table_name."";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO ".$this->table_name."SET IdPegawai=?, Tanggal=?, JamDatang=?, JamPulang=?, Keterangan=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->IdPegawai);
        $stmt->bindParam(2, $this->Tanggal);
        $stmt->bindParam(3, $this->JamDatang);
        $stmt->bindParam(4, $this->JamPulang);
        $stmt->bindParam(5, $this->Keterangan);
        if($stmt->execute()){
            $this->IdAbsen= $this->conn->lastInsertId();
            return true;
        }else
        {
            return false;
        }
    }

    public function update()
    {
        $query = "UPDATE ".$this->table_name."SET Tanggal=?, JamDatang=?, JamPulang=?, Keterangan=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Tanggal);
        $stmt->bindParam(2, $this->JamDatang);
        $stmt->bindParam(3, $this->JamPulang);
        $stmt->bindParam(4, $this->Keterangan);
        if($stmt->execute()){
            return true;
        }else
        {
            return false;
        }
    }

    public function delete()
    {
        $query = "DELETE FROM ".$this->table_name." WHERE IdAbsen=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->IdAbsen);
        if($stmt->execute()){
            return true;
        }else
        {
            return false;
        }
    }
}

?>