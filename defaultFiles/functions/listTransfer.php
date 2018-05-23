<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 19.05.18
 * Time: 17:35
 */

//Get every Transfer
session_start();

$user = $_SESSION['user'];
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

$sql = "SELECT * from transfers WHERE destID=$user[0] OR sourceID=$user[0];";
$result = $conn->query($sql);

$output = "";
while ($res = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($output != "") {
        $output .= ",";
    }
    $output .= '{"source":"' . $res["sourceID"] . '",';
    $output .= '"dest":"' . $res["destID"] . '",';
    $output .= '"purpose":"' . $res["purpose"] . '",';
    $output .= '"date":"' . $res["date"] . '",';
    $output .= '"amount":"' . $res["amount"] . '"}';
}
$output = '{"fullList":[' . $output . ']}';
$conn->close();

echo($output);