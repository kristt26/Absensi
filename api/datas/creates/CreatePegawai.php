<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
include_once '../../../api/config/Database.php';
include_once '../../../api/objects/Pegawai.php';
$database = new Database();
$db = $database->getConnection();
$pegawai = new Pegawai($db);
$data = json_decode(file_get_contents("php://input"));
if(
    !empty($data->NIP) && 
    !empty($data->Nama) && 
    !empty($data->JK) && 
    !empty($data->Kontak) && 
    !empty($data->Alamat) &&
    !empty($data->Email) &&
    !empty($data->Password) &&
    !empty($data->Jabatan)
    ){
    $pegawai->NIP = $data->NIP;
    $pegawai->Nama = $data->Nama;
    $pegawai->JK = $data->JK;
    $pegawai->Kontak = $data->Kontak;
    $pegawai->Alamat = $data->Alamat;
    $pegawai->Email = $data->Email;
    $pegawai->Password = md5($data->Password);
    $pegawai->Jabatan = $data->Jabatan;
    if($pegawai->create()){
        http_response_code(201);
        echo json_encode(array("message" => $pegawai->IdPegawai));
    }else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create Pegawai"));
    }
}else
{
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Unable to create Pegawai. Data is incomplete."));
}
 
?>