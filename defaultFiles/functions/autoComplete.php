<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 24.05.18
 * Time: 06:34
 */
session_start();
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];
$searchQuery = $_GET['q'];

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

if (!$conn->error) {
    $sql = "SELECT
        u.prename,
        u.lastname,
        u.email,
        k.kontoName,
        k.IBAN
        FROM user u
          INNER JOIN konten k ON u.id = k.userID
        WHERE u.prename LIKE '$searchQuery%' OR k.IBAN LIKE '$searchQuery%' OR u.lastname LIKE '$searchQuery%';";
    $result = $conn->query($sql);

    $output = "";
    while ($res = $result->fetch_array(MYSQLI_ASSOC)) {
        if ($output != "") {
            $output .= ",";
        }
        $output .= '{"prename":"' . $res["prename"] . '",';
        $output .= '"lastname":"' . $res["lastname"] . '",';
        $output .= '"email":"' . $res["email"] . '",';
        $output .= '"kontoName":"' . $res["kontoName"] . '",';
        $output .= '"iban":"' . $res["IBAN"] . '"}';
    }
    $output = '{"output":[' . $output . ']}';
    $conn->close();
    echo($output);
}