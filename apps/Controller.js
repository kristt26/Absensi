var app = angular.module("Ctrl", [])
    .controller("MainController", function($scope) {
        $scope.Testing = "Testing"
        $scope.Init = function() {
            // alert($scope.Testing);
        }
    })
    .controller("PegawaiController", function($scope, $http) {
        $scope.DatasPegawai = [];
        $scope.DataInput = {};
        $scope.SetForm;
        $scope.ShowPass = true;
        $scope.HidePass = false;
        $scope.Init = function() {
            $scope.DataInput = {};
            var Url = "api/datas/reads/ReadPegawai.php";
            $http({
                method: "GET",
                url: Url
            }).then(function(response) {
                $scope.DatasPegawai = response.data.records;
            }, function(error) {
                alert(response.error);
            })
        }
        $scope.Tambah = function() {
            $scope.DataInput = {};
            $scope.SetForm = "Tambah";
            $scope.ShowPass = true;
            $scope.HidePass = false;
        }
        $scope.Edit = function(item) {
            $scope.SetForm = "Edit";
            $scope.DataInput = angular.copy(item);
            $scope.ShowPass = false;
            $scope.HidePass = true;
        }
        $scope.Simpan = function() {
            var Data = $scope.DataInput;
            var Url = "api/datas/creates/CreatePegawai.php";
            $http({
                method: "POST",
                url: Url,
                data: Data
            }).then(function(response) {
                if (response.data.message > 0) {
                    $scope.DataInput.IdPegawai = response.data.message;
                    $scope.DatasPegawai.push(angular.copy($scope.DataInput));
                }
                $scope.DataInput = {};

            }, function(error) {
                alert(error.message);
            })
        }
    });