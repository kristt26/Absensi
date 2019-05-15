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
$absen->Tanggal = date('Y-m-d');
$stmt = $absen->readByTanggal();
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
