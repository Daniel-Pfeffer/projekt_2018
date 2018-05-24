<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 17.05.18
 * Time: 19:04
 */

session_start();

$user = $_SESSION['user'];
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

$sql = "SELECT prename, kontoName, stand,IBAN FROM konten,user WHERE userID = " . $user[0] . " AND user.id = " . $user[0];
$result = $conn->query($sql);

$output = "";
while ($res = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($output != "") {
        $output .= ",";
    }
    $output .= '{"name":"' . $res["prename"] . '",';
    $output .= '"kontoTyp":"' . $res["kontoName"] . '",';
    $output .= '"iban":"' . $res["IBAN"] . '",';
    $output .= '"geld":"' . $res["stand"] . '"}';
}
$output = '{"fullKonto":[' . $output . ']}';
$conn->close();
echo($output);