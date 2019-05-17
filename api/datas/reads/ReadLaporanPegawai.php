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
session_start();
$database = new Database();
$db = $database->getConnection();
$pegawai = new Pegawai($db);
$absen = new Absen($db);
$DataBulanInggris = array(0 => 'January', 1 => 'February', 2 => 'March', 3 => 'April', 4 => 'May', 5 => 'June', 6 => 'July', 7 => 'August', 8 => 'September', 9 => 'October', 10 => 'November', 11 => 'December');
$b = (int) $_SESSION['Id'];
$a = $b + 1;
$Bulan = $DataBulanInggris[$b];
$absen->Tanggal = date('Y') . "-" . "0" . $a . "-" . "01";
$absen->IdPegawai=$_SESSION['IdPegawai'];
$c = "last day of " . $Bulan . " " . date('Y');
$TanggalAkhir = date('Y-m-d', strtotime($c));
$stmt = $absen->readByRanges($TanggalAkhir);
$RowAbsen = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pegawai->read();
$RowPegawai = $stmt->fetchAll(PDO::FETCH_ASSOC);
// if ($num > 0) {
$Datas = array("records" => array());
foreach ($RowAbsen as &$valueAbsen) {
    $DataAbsen = array(
        'IdAbsen' => $valueAbsen['IdAbsen'],
        'IdPegawai' => $valueAbsen['IdPegawai'],
        'Tanggal' => $valueAbsen['Tanggal'],
        'JamDatang' => $valueAbsen['JamDatang'],
        'JamPulang' => $valueAbsen['JamPulang'],
        'Terlambat' => $valueAbsen['Terlambat'],
        'Keterangan' => $valueAbsen['Keterangan'],
        'Nama' => "",
        'NIP' => "",
    );
    foreach ($RowPegawai as &$valuePegawai) {
        if ($valueAbsen['IdPegawai'] == $valuePegawai['IdPegawai']) {
            $DataAbsen['Nama'] = $valuePegawai['Nama'];
            $DataAbsen['NIP'] = $valuePegawai['NIP'];
        }
    }
    array_push($Datas['records'], $DataAbsen);
}
echo json_encode($Datas);
