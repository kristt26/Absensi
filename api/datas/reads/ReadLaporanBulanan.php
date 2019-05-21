<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set("Asia/Seoul");
include_once '../../config/Database.php';
include_once '../../objects/Pegawai.php';
include_once '../../objects/Absen.php';
$database = new Database();
$db = $database->getConnection();
$pegawai = new Pegawai($db);
$absen = new Absen($db);
$data = json_decode(file_get_contents("php://input"));
$DataBulanInggris = array(0 => 'January', 1 => 'February', 2 => 'March', 3 => 'April', 4 => 'May', 5 => 'June', 6 => 'July', 7 => 'August', 8 => 'September', 9 => 'October', 10 => 'November', 11 => 'December');
$b = (int) $data->Id;
$a = $b + 1;
$Bulan = $DataBulanInggris[$b];
$absen->Tanggal = date('Y') . "-" . "0" . $a . "-" . "01";
$c = "last day of " . $Bulan . " " . date('Y');
$TanggalAkhir = date('Y-m-d', strtotime($c));
$stmt = $absen->readByDates($TanggalAkhir);
$RowAbsen = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pegawai->read();
$RowPegawai = $stmt->fetchAll(PDO::FETCH_ASSOC);
// if ($num > 0) {
$Datas = array("records" => array());
foreach ($RowPegawai as &$valuePegawai) {
    $DataPegawai = array(
        'NIP' => $valuePegawai['NIP'],
        'Nama' => $valuePegawai['Nama'],
        'JumlahHari' => "",
        'Hadir' => "",
        'Sakit' => "",
        'Ijin' => "",
        'Alpa' => "",
        'Cuti' =>"",
        'JumlahTidakHadir' =>"",
        'Presentase' =>""
    );
    $Hadir = 0;
    $Ijin = 0;
    $Sakit = 0;
    $Alpa = 0;
    $Cuti =0;
    foreach ($RowAbsen as &$valueAbsen) {
        if ($valuePegawai['IdPegawai'] == $valueAbsen['IdPegawai']) {
            if ($valueAbsen['Keterangan'] == "H") {
                $Hadir += 1;
            } elseif ($valueAbsen['Keterangan'] == "I") {
                $Ijin += 1;
            } elseif ($valueAbsen['Keterangan'] == "S") {
                $Sakit += 1;
            } elseif ($valueAbsen['Keterangan'] == "Cuti") {
                $Cuti += 1;
            }else {
                $Alpa += 1;
            }
        }
    }
    $DataPegawai['JumlahHari']=$data->JumlahHari;
    $DataPegawai['Hadir']=$Hadir;
    $DataPegawai['Ijin']=$Ijin;
    $DataPegawai['Sakit']=$Sakit;
    $DataPegawai['Cuti']=$Cuti;
    $DataPegawai['Alpa']=$Alpa;
    $DataPegawai['JumlahTidakHadir']=$Alpa+$Sakit+$Ijin+$Cuti;
    $DataPegawai['Presentase']=$Hadir/$data->JumlahHari*100;
    array_push($Datas['records'], $DataPegawai);
}
echo json_encode($Datas);
