var app = angular.module("Apps", ["ngRoute", "Ctrl"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/Main", {
            templateUrl: "apps/views/Main.html",
            controller: "MainController"
        })
        .when("/Pegawai", {
            templateUrl: "apps/views/Pegawai.html",
            controller: "PegawaiController"
        })
        .when("/Pengguna", {
            templateUrl: "apps/views/Pengguna.html",
            controller: "PenggunaController"
        })
        .when("/Absen", {
            templateUrl: "apps/views/AbsenAdmin.html",
            controller: "AbsenController"
        })
        .when("/Laporan", {
            templateUrl: "apps/views/Laporan.html",
            controller: "LaporanController"
        })
        .otherwise({ redirectTo: '/Main' })

})