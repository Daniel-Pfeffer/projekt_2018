<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 17.04.18
 * Time: 16:22
 */
//Variable declaration
include "../../library/console.php";
$console = new console();
session_start();
$user = $_SESSION['user'];
$console->log($user['0']);

$kontoName = $_GET['kontoName'];
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];

//Database connection
$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

$userID = $user[0];
$checkSQL = "SELECT count(*)anz from konten where userID = $userID and kontoName like \"$kontoName\"";
$res = $conn->query($checkSQL);
if ($res) {
    $res = $res->fetch_assoc();
    if ($res['anz'] > 0) {
        exit;
    } else {
        $checkCount = "SELECT count(*)anz from konten where userID = $userID;";
        $res = $conn->query($checkCount);
        $anz;
        if ($res) {
            $anz = $res->fetch_assoc()['anz'];
        } else {
            exit;
        }
        if ($anz < 5) {
            $hasMaster = 0;
            $checkMaster = "SELECT master from user where id = $userID";
            $res2 = $conn->query($checkMaster);
            if ($res2) {
                $res2 = $res2->fetch_assoc();
                $hasMaster = is_null($res2["master"]) ? 0 : 1;
            }
            $IBAN = "SB" . rand(1, 99);
            for ($i = 0; $i < 4; $i++) {
                $IBAN .= rand(1, 9999);
            }
            $sql = "INSERT INTO konten (userID, kontoName, kontoType, stand, IBAN) VALUES (\"$userID\",\"$kontoName\",\"$hasMaster\",100,\"$IBAN\")";
            $resFin = $conn->query($sql);
        }
    }
}