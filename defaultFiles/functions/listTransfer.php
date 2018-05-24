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
$iban = $_GET['iban'];

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
$sql = "
SELECT
  transfers.destIBAN,
  transfers.sourceIBAN,
  transfers.date,
  transfers.amount,
  transfers.purpose,
  u.prename destName1,
  u.lastname destName2,
  u2.prename sourceName1,
  u2.lastname sourceName2,
  k.kontoName source,
  k2.kontoName dest
FROM transfers
  JOIN konten k ON transfers.destIBAN = k.IBAN
  JOIN konten k2 ON transfers.sourceIBAN = k2.IBAN
  JOIN user u ON k.userID = u.id
  join user u2 ON k2.userID = u2.id
WHERE sourceIBAN LIKE '$iban' or destIBAN like '$iban';";
$result = $conn->query($sql);
$output = "";
while ($res = $result->fetch_array(MYSQLI_ASSOC)) {
    if ($output != "") {
        $output .= ",";
    }
    if ($res["sourceIBAN"] == $iban) {
        $output .= '{"otherIBan":"' . $res["sourceIBAN"] . '",';
        $output .= '"purpose":"' . $res["purpose"] . '",';
        $output .= '"date":"' . $res["date"] . '",';
        $output .= '"prename":"' . $res["sourceName1"] . '",';
        $output .= '"lastname":"' . $res["sourceName2"] . '",';
        $output .= '"kontoname":"' . $res["source"] . '",';
        $output .= '"amount":"' . $res["amount"] . '"}';
    } elseif($res["destIBAN"] == $iban){
        $output .= '{"otherIBan":"' . $res["destIBAN"] . '",';
        $output .= '"purpose":"' . $res["purpose"] . '",';
        $output .= '"date":"' . $res["date"] . '",';
        $output .= '"prename":"' . $res["destName1"] . '",';
        $output .= '"lastname":"' . $res["destName2"] . '",';
        $output .= '"kontoname":"' . $res["dest"] . '",';
        $output .= '"amount":"' . $res["amount"] . '"}';
    }else{
        //WUT U DO M8
        //Just to meme the Lehrer
    }

}
$output = '{"fullList":[' . $output . ']}';
$conn->close();

echo($output);