<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 23.05.18
 * Time: 10:43
 */
session_start();

$user = $_SESSION['user'];
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];

$destIban = $_GET['iban'];
$amount = $_GET['amount'];
$purpose = $_GET['purpose'];
$sourceIban = $_GET['source'];

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

$sql = "INSERT INTO transfers VALUES (\"$destIban\",\"$sourceIban\",\"$purpose\",$amount,NOW())";

$res = $conn->query($sql);
if($res){
    $sqlUpdate = "update konten set stand = stand-$amount WHERE IBAN like \"$sourceIban\";";
    $sqlUpdate2 = "update konten set stand = stand+$amount WHERE IBAN like \"$destIban\";";
    $conn->query($sqlUpdate);
    $conn->query($sqlUpdate2);
}