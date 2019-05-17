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
    .controller("AbsenController", function ($http, $scope, $timeout) {
        $scope.clock = "loading clock..."; // initialise the time variable
        $scope.tickInterval = 1000 //ms

        var tick = function () {
            $scope.clock = Date.now() // get the current time
            $timeout(tick, $scope.tickInterval); // reset the timer
        }

        // Start the timer
        $timeout(tick, $scope.tickInterval);
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
    })
    .controller("ControllerLaporan", function($scope, $http){
        $DatasLaporan = [];
        $http.get("../../../api/datas/reads/ReadLaporanPegawai.php")
        .then(function(response){
            $scope.DatasLaporan = response.data.records;
        })
    });