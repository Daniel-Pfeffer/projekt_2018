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

$sql = "SELECT prename, kontoName, stand FROM konten,user WHERE userID = " . $user[0] . " AND user.id = " . $user[0];
$result = $conn->query($sql);

$output = "";
while ($res = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($output != "") {
        $output .= ",";
    }
    $output .= '{"name":"' . $res["prename"] . '",';
    $output .= '"kontoTyp":"' . $res["kontoName"] . '",';
    $output .= '"geld":"' . $res["stand"] . '"}';
}
$output = '{"fullKonto":[' . $output . ']}';
$conn->close();

echo($output);