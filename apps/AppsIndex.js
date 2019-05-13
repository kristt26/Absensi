var app = angular.module("AppsIndex", ["ngRoute", "CtrlIndex"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/Main", {
            templateUrl: "apps/views/Main.html",
            controller: "MainController"
        })
        .when("/Absen", {
            templateUrl: "apps/views/Absen.html",
            controller: "AbsenController"
        })
        .when("/Login", {
            templateUrl: "apps/views/Login.html",
            controller: "LoginController"
        })
        .otherwise({ redirectTo: '/Main' })

})