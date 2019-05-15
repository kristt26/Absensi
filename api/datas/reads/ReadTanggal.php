<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set("Asia/Seoul");
$GetTanggal = new DateTime();
$a =strtotime($GetTanggal->format('H:i:s'));
$SetJamDatang = strtotime('07:30:00');
$c=$SetJamDatang-$a;
$time = date($c);
$HasilJam=$time;
$Tanggal=date('Y-m-d H:i:s');
$Waktu = time();
$Data = array(
    'Tanggal' => date('d'),
    'Bulan' => date('m'),
    'Tahun' => date('Y'),
    'Jam' => date('H'),
    'Menit' => date('i'),
    'Detik' => date('s')
);
echo json_encode($Data);

 
?>