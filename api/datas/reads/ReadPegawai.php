<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../../config/Database.php';
include_once '../../objects/Pegawai.php';
$database = new Database();
$db = $database->getConnection();
$pegawai = new Pegawai($db);
$stmt = $pegawai->read();
$num = $stmt->rowCount();
if($num>0)
{
    $Datas= array("records"=>array());
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);
 
        $item=array(
            "IdPegawai" => $IdPegawai,
            "NIP" => $NIP,
            "Nama" => $Nama,
            "JK" => $JK,
            "Kontak" => $Kontak,
            "Alamat" => $Alamat,
            "Email" => $Email,
            "Password" => $Password,
            "Jabatan" => $Jabatan
        );
 
        array_push($Datas["records"], $item);
    }
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($Datas);
}else
{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No Pegawai found")
    );
}
?>