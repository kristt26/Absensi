var app = angular.module("CtrlIndex", [])
    .controller("MainController", function ($scope, $timeout, $http) {
        $scope.clock = "loading clock..."; // initialise the time variable
        $scope.tickInterval = 1000 //ms
        $scope.Hari = "";
        $scope.Bulan = "";
        $scope.Bulan = "";
        $scope.TanggalSistem = {};
        var tick = function () {
            var myMonths = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $http({
                method: "POST",
                url: "api/datas/reads/ReadTanggal.php"
            }).then(function (response) {
                var Tahun = parseInt(response.data.Tahun);
                var Bulan = parseInt(response.data.Bulan) - 1;
                var Tanggal = parseInt(response.data.Tanggal);
                var Jam = parseInt(response.data.Jam);
                var Menit = parseInt(response.data.Menit);
                var Detik = parseInt(response.data.Detik);
                $scope.TanggalSistem = new Date(Tahun, Bulan, Tanggal, Jam, Menit, Detik);
                $scope.clock = $scope.TanggalSistem; // get the current time
                var date = angular.copy($scope.TanggalSistem);
                var month = date.getMonth();
                var thisDay = date.getDay();
                $scope.Bulan = myMonths[month];
                $scope.Hari = myDays[thisDay];
                // reset the timer
            }, function (error) {
                alert(error.message);
            })
            $timeout(tick, $scope.tickInterval);
        }
        $timeout(tick, $scope.tickInterval);
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
        $scope.TanggalAbsen = $scope.Hari+", "+thisDay+" "+$scope.Bulan+" "+tahun;

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
            var Url = "api/datas/creates/CreateAbsen.php";
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
    .controller("LoginController", function($scope, $http){
        $scope.DataInput={};
        $scope.Login=function(){
            Url = "api/datas/reads/Login.php";
            Data = $scope.DataInput;
            $http({
                method: "POST",
                url: Url,
                data:Data
            }).then(function(response){
                window.location.href = "admin.html";
            }, function(error){
                alert(error.data.message);
            })
        }
    })
    ;