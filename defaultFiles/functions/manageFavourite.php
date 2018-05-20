<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 09.05.18
 * Time: 08:11
 */

session_start();
if (!is_null($_GET)) {
    //Getter very Important
    $method = $_GET['method'];
    $symbol = $_GET['symbol'];
    //Database Paramter from Session
    $dbName = $_SESSION['dbName'];
    $dbHost = $_SESSION['host'];
    $dbPassword = $_SESSION['dbPassword'];
    $dbUser = $_SESSION['dbUser'];
    //User
    $user = $_SESSION['user'];
    //Connection
    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
    //Get Symbol ID
    $idOfSymbol = null;
    $sqlGet = "SELECT stockID FROM stocks WHERE stockIndex = '$symbol';";
    $res = $conn->query($sqlGet);
    if ($res) {
        $res = $res->fetch_assoc();
        $idOfSymbol = $res['stockID'];
    }
    //Manage
    if ($method == 0) {
        //TODO: FAVOURITE
        $sqlInsert = "insert into favStock(userID, stockID) VALUES ($user[0],$idOfSymbol);";
        $res = $conn->query($sqlInsert);
    } else {
        //TODO: UNFAVOURITE
        $sqlDelete = "DELETE from favStock WHERE userID =$user[0] AND stockID = $idOfSymbol;";
        $conn->query($sqlDelete);
    }
}