<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 09.04.18
 * Time: 10:08
 */

session_start();
include("../library/betterIFs.php");
$user = $_SESSION['user'];
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];
if (betterIFs::false_nt) {
    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
}
?>
<html>
<head>
    <title>Spicy Banking</title>
    <!--<link rel="icon" href="https://media.giphy.com/media/YJjvTqoRFgZaM/giphy.gif">-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.10/angular.min.js"></script>
    <meta charset="utf-8">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="../css/chartjs/canvasjs.min.js"></script>
    <link rel="stylesheet" href="../css/mainFile.css">
</head>
<body>
<nav class="navbar bg-dark navbar-dark">
    <a class="navbar-brand">
        <h2 class="navbar-text justify-content-center">Spicy Bank</h2>
    </a>


    <div class="navbar-nav">
        <button class="btn btn-outline-light my-2 my-sm-0" onclick="location.replace('functions/logout.php')">Logout
        </button>

    </div>
</nav>
<div class="container">
    <div ng-app="listKonto">
        <div class="row">
            <div class="col-sm mb-5 mr-auto ml-auto">
                <div class="ml-3" id="kontoansicht">
                    <div id="header" class="justify-content-center pl-2">
                        <!--
                        Wenn Name verfügbar Finanzübersicht klein und grau.
                        -->
                        <a class="nameAv">Finanzübersicht</a><br>
                        <a class="nameClass">NameOfKonto</a>
                        <label class="settings mr-3">
                            <input type="button" style="display: none;" onclick="alert('Settings Button')"/>
                            <img src="../icons/black/settings.svg" width="30px" height="30px"/>
                        </label><br>
                    </div>
                    <hr style="width: 100%;">
                    <div class="kontos pl-3 mr-3" style="clear:both">
                        <div ng-controller="listKontoCtrl" id="completeKontoView">
                            <div ng-repeat="x in konten">
                                <hr class="hr" ng-if="!$first">
                                <a class="name">{{x.name}}</a><br>
                                <a class="kontoTyp">{{x.kontoTyp}}</a><br>
                                <a class="stand">Stand:</a>
                                <a class="geld">{{x.geld}}</a>
                            </div>
                            <!------------->
                            <div style="display: inline" class="pl-3">
                                <div></div>
                                <input type="text" placeholder="Kontoname" name="kontoName" id="kontoName"
                                       ng-model="kontoVal"/>
                                <label ng-click="createKonto()">
                                    <img src="../icons/black/add.svg"/>
                                </label>
                            </div>
                            <script>
                                let app = angular.module('listKonto', []);
                                app.controller('listKontoCtrl', function ($scope, $http) {
                                    $scope.kontoVal = "KontoName";
                                    $scope.reload = function () {
                                        $http.get("functions/listKonten.php")
                                            .then(function (response) {
                                                $scope.konten = response.data.fullKonto;
                                            });
                                    };
                                    $scope.createKonto = function () {
                                        $http.get("functions/createKonto.php?kontoName=" + $scope.kontoVal)
                                            .then(function (response) {
                                                $scope.kontoVal = "";
                                                $scope.reload();
                                            });
                                    };
                                    $scope.reload();
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm ml-auto mr-auto" id="stockPrice">
                <h2>Abonnierte Aktienkurse</h2>
                <?php
                //include_once("functions/stockMarket.php");
                ?>
            </div>
        </div>
        <div class="row" id="turnover" ng-controller="listSales">
            <div class="col-sm mb-5 mr-auto ml-auto">
                <div class="ml-3" id="umsatzverlauf">
                    <div id="headerUmsatz" class="justify-content-center pl-2">
                        <a class="nameAv">Umsatzverlauf</a><br>
                        <a class="nameClass">NameOfUser</a>
                        <label class="settings mr-3" id="umsatzverlaufSettings">
                            <input type="button" style="display: none;" onclick="alert('Settings Button')"/>
                            <img src="../icons/black/settings.svg" width="30px" height="30px"/>
                        </label><br>
                    </div>
                    <script>
                        app.controller('listSales', function ($scope, $http) {
                            $scope.list = function () {
                                $http.get("functions/listTransfer.php")
                                    .then(function (response) {
                                        $scope.salesDisp = response.data.fullList;
                                    });
                            };
                            $scope.autoFill = function () {
                                if ($scope.nameInput.trim().length !== 0) {
                                    $http.get("functions/autoComplete.php?q=" + $scope.nameInput)
                                        .then(function (response) {
                                            $scope.autoComplete = response.data.output;
                                        });
                                }
                            };
                            $scope.nameChosen = function (item) {
                                $scope.nameInput = item.x.iban;
                            };
                            //$scope.list();
                        });
                    </script>
                    <hr style="width: 100%;">
                    <div>
                        <div class="kontos pl-3 mr-3" style="clear:both">
                            <div ng-repeat="x in salesDisp">
                                <a class="date">{{x.date}}</a>
                                <div class="middle">
                                    <a class="purpose">{{x.purpose}}</a><br>
                                    <a class="IBAN_Other">{{x.source}}</a><br><!--ÄNDERN-->
                                </div>
                                <a class="amount">{{x.amount}}€</a><br>
                                <br>
                            </div>
                            <div id="controllerForm">
                                <button id="buttonExpandForm">+</button>
                                <script>
                                    $(document).ready(function () {
                                        $("#buttonExpandForm").click(function () {
                                            $("#formTransfer").toggle();
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="transferForm">
                    <label>
                        <input type="text" ng-model="nameInput" placeholder="Name der Person oder IBAN"
                               ng-change="autoFill()" style="width: 250px"/>
                    </label>
                    <div ng-repeat="x in autoComplete">
                        <a id="{{x.iban}}" ng-click="nameChosen(this)">{{x.prename}} {{x.lastname}} {{x.kontoName}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<!--
    Alpha Vantage API-KEY: 8IC1KW4FEJ2EDU3D
-->