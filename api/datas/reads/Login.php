<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../../config/Database.php';
include_once '../../objects/Pegawai.php';
session_start();
$database = new Database();
$db = $database->getConnection();
$pegawai = new Pegawai($db);
$data = json_decode(file_get_contents("php://input"));
$pegawai->Email=$data->Email;
$pegawai->Password=md5($data->Password);
$num = $pegawai->Login();
if ($num == true) {
    $_SESSION['Nama'] = $pegawai->Nama;
    $_SESSION['Email'] = $pegawai->Email;
    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($_SESSION);
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "User dan Password tidak sesuai")
    );
}
