var app = angular.module("Ctrl", [])
    .controller("MainController", function ($scope) {
        $scope.Testing = "Testing"
        $scope.Init = function () {
            // alert($scope.Testing);
        }
    })
    .controller("PegawaiController", function ($scope, $http) {
        $scope.DatasPegawai = [];
        $scope.DataInput = {};
        $scope.SetForm;
        $scope.ShowPass = true;
        $scope.HidePass = false;
        $scope.Init = function () {
            $scope.DataInput = {};
            var Url = "api/datas/reads/ReadPegawai.php";
            $http({
                method: "GET",
                url: Url
            }).then(function (response) {
                $scope.DatasPegawai = response.data.records;
            }, function (error) {
                alert(response.error);
            })
        }
        $scope.Tambah = function () {
            $scope.DataInput = {};
            $scope.SetForm = "Tambah";
            $scope.ShowPass = true;
            $scope.HidePass = false;
        }
        $scope.Edit = function (item) {
            $scope.SetForm = "Edit";
            $scope.DataInput = angular.copy(item);
            $scope.ShowPass = false;
            $scope.HidePass = true;
        }
        $scope.Simpan = function () {
            var Data = $scope.DataInput;
            var Url = "api/datas/creates/CreatePegawai.php";
            $http({
                method: "POST",
                url: Url,
                data: Data
            }).then(function (response) {
                if (response.data.message > 0) {
                    $scope.DataInput.IdPegawai = response.data.message;
                    $scope.DatasPegawai.push(angular.copy($scope.DataInput));
                }
                $scope.DataInput = {};

            }, function (error) {
                alert(error.message);
            })
        }
    })
    .controller("AbsenController", function ($http, $scope, $timeout, $filter) {
        $scope.DatasPegawai = [];
        $scope.DatasAbsen = [];
        $scope.selected = {};
        $scope.DataInput = {};
        $scope.TanggalAbsen="";
        var myMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var month = new Date().getMonth();
        var thisDay = new Date().getDay();
        var xtahun = new Date().getYear();
        var tahun = (xtahun < 1000)?xtahun + 1900 : xtahun;
        $scope.Bulan = myMonths[month];
        $scope.Hari = myDays[thisDay]; 
        var Tgl =new Date().getDate();
        $scope.TanggalAbsen = $scope.Hari+", "+Tgl+" "+$scope.Bulan+" "+tahun;

        $scope.Init = function () {
            var Url = "api/datas/reads/ReadPegawai.php";
            $http({
                method: "GET",
                url: Url
            }).then(function (response) {
                $scope.DatasPegawai = response.data.records;
                $http.get("api/datas/reads/ReadAbsen.php")
                    .then(function (response) {
                        $scope.DatasAbsen = response.data.records;
                    });
            }, function (error) {
                alert(response.error);
            })
        }

        $scope.Simpan = function () {
            var JamSistem = Date.now();
            $scope.Tanggal = $filter('date')(new Date(), 'dd/MM/yyyy');
            $scope.Jam = $filter('date')(new Date(), 'HH:mm:ss');
            $scope.DataInput.IdPegawai = $scope.selected.IdPegawai;
            $scope.DataInput.Tanggal = $scope.Tanggal;
            $scope.DataInput.Jam = $scope.Jam;
            var Url = "api/datas/creates/CreateAbsenAdmin.php";
            var Data = angular.copy($scope.DataInput);
            $http({
                method: "POST",
                url: Url,
                data: Data
            }).then(function (response) {
                if (response.status == 200) {
                    if(response.data.Status=="Insert"){
                        var a = angular.copy(response.data);
                        angular.forEach($scope.DatasPegawai, function(value, key){
                            if(value.IdPegawai==a.IdPegawai){
                                a.Nama= value.Nama;
                                a.NIP = value.NIP;
                            }
                        });
                        $scope.DatasAbsen.push(angular.copy(a));
                    }else{
                        var a = angular.copy(response.data);
                        angular.forEach($scope.DatasAbsen, function(value, key){
                            if(value.IdPegawai==$scope.DataInput.IdPegawai){
                                value.JamPulang = a.JamPulang;
                            }
                        });
                    }
                }else{
                    alert(response.data.message);
                }

            }, function (error) {
                alert(error.data.message);
            })
        }
    })
    .controller("LaporanController", function ($scope, $http) {
        $scope.DatasLaporan = [];
        $scope.DatasPegawai = [];
        $scope.selected = {};
        $scope.selectedPegawai = {};
        $scope.Bulan = [{ Id: 0, Nama: "Januari" }, { Id: 1, Nama: "Februari" }, { Id: 2, Nama: "Maret" }, { Id: 3, Nama: "April" }, { Id: 4, Nama: "Mei" }, { Id: 5, Nama: "Juni" }, { Id: 6, Nama: "Juli" }, { Id: 7, Nama: "Agustus" }, { Id: 8, Nama: "September" }, { Id: 9, Nama: "Oktober" }, { Id: 10, Nama: "November" }, { Id: 11, Nama: "Desember" }];
        $scope.Init = function () {
            $http.get('api/datas/reads/ReadPegawai.php')
                .then(function (response) {
                    $scope.DatasPegawai = response.data.records;
                })
        }
        $scope.GetData = function () {
            var a = {};
            if ($scope.selected.Id != undefined && $scope.selectedPegawai.IdPegawai != undefined) {
                a.Id = $scope.selected.Id;
                a.IdPegawai = $scope.selectedPegawai.IdPegawai;
                $http({
                    method: "POST",
                    url: "api/datas/reads/ReadLaporan.php",
                    data: a
                }).then(function (response) {
                    $scope.DatasLaporan = response.data.records;
                });
            }
        }
        $scope.GetBulan = function (){
            $http({
                method: "POST",
                url: "api/datas/reads/ReadLaporanBulanan.php",
                data: $scope.selected
            }).then(function (response) {
                $scope.DatasLaporan = response.data.records;
            });
        }
        $scope.printToCart = function (printSectionId) {
            var innerContents = document.getElementById(printSectionId).innerHTML;
            var popupWinindow = window.open('', '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
            popupWinindow.document.open();
            popupWinindow.document.write('<html><head><link href="assets/index/css/bootstrap.min.css" rel="stylesheet"></head><body onload="window.print()"><div>' + innerContents + '</html>');
            popupWinindow.document.close();
        }
    })
    .controller("ControllerLaporan", function ($scope, $http) {
        $DatasLaporan = [];
        $http.get("../../../api/datas/reads/ReadLaporanPegawai.php")
            .then(function (response) {
                $scope.DatasLaporan = response.data.records;
                onload=window.print();
            })
    });