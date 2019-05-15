<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set("Asia/Seoul");
// include database and object file
include_once '../../../api/config/Database.php';
include_once '../../../api/objects/Absen.php';
$database = new Database();
$db = $database->getConnection();
$absen = new Absen($db);
$data = json_decode(file_get_contents("php://input"));
if (!empty($data->IdPegawai)) {
// $TanggalSistem = create_da
    $Tgl = date('Y-m-d') . " " . "07:30:00";
    $JamDatang = new DateTime($Tgl);
    $JamPulang = new DateTime(date('Y-m-d') . " " . "15:00:00");
    $TglSistem = new DateTime();
    $absen->IdPegawai = $data->IdPegawai;
    $absen->Tanggal = date('Y-m-d');
    $stmt = $absen->readOne();
    $num = $stmt->rowCount();
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $CekJamDatang = null;
    $CekJamPulang = null;
    if ($num == 0) {
        $CekJamDatang = null;
        $CekJamPulang = null;
    } else {
        $a = $row[0];
        $CekJamDatang = $a['JamDatang'];
        $CekJamPulang = $a['JamPulang'];
    }

    $stmt = null;
    if (($JamDatang > $TglSistem || $JamDatang < $TglSistem) && $JamPulang > $TglSistem) {
        // if ($TanggalSistem > $TanggalAwal) {
        if ($num > 0) {
            http_response_code(503);
            echo json_encode(array("message" => "Anda Sudah Absen Datang"));
        } else {
            if ($JamDatang > $TglSistem) {
                $absen->IdPegawai = $data->IdPegawai;
                $absen->Tanggal = date('Y-m-d');
                $absen->JamDatang = date('H:i:s');
                $absen->Terlambat = "00:00:00";
                $absen->Keterangan = "H";
                if ($absen->create()) {
                    $DataPegawai = array(
                        'IdAbsen' => $absen->IdAbsen,
                        'IdPegawai' => $absen->IdPegawai,
                        'Tanggal' => $absen->Tanggal,
                        'JamDatang' => $absen->JamDatang,
                        'JamPulang' => $absen->JamPulang,
                        'Terlambat' => $absen->Terlambat,
                        'Keterangan' => $absen->Keterangan,
                        'Status' => "Insert",
                    );
                    http_response_code(200);
                    echo json_encode($DataPegawai);
                }
            } else {
                $diff = $TglSistem->diff($JamDatang);
                $absen->Terlambat = $diff->h . ":" . $diff->i . ":" . $diff->s;
                $absen->IdPegawai = $data->IdPegawai;
                $absen->Tanggal = date('Y-m-d');
                $absen->JamDatang = date('H:i:s');
                $absen->Keterangan = "H";
                if ($absen->create()) {
                    $DataPegawai = array(
                        'IdAbsen' => $absen->IdAbsen,
                        'IdPegawai' => $absen->IdPegawai,
                        'Tanggal' => $absen->Tanggal,
                        'JamDatang' => $absen->JamDatang,
                        'JamPulang' => $absen->JamPulang,
                        'Terlambat' => $absen->Terlambat,
                        'Keterangan' => $absen->Keterangan,
                        'Status' => "Insert",
                    );
                    http_response_code(200);
                    echo json_encode($DataPegawai);
                }
            }

        }
    } else {
        if ($num > 0 && $CekJamPulang == null && $JamPulang < $TglSistem) {
            $absen->IdPegawai = $data->IdPegawai;
            $absen->Tanggal = date('Y-m-d');
            $absen->JamPulang = date('H:i:s');
            if ($absen->update()) {
                http_response_code(200);
                $DataPegawai = array(
                    'JamPulang' => $absen->JamPulang,
                    'Status' => "Update",
                );
                echo json_encode($DataPegawai);
            }
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Anda Sudah Absen Pulang"));
        }
    }
} else {
    http_response_code(503);
    echo json_encode(array("message" => "Pilih Pegawai"));
}
