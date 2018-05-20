<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 10.04.18
 * Time: 14:20
 */
include("../../library/console.php");
session_start();

$console = new Console;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbName = $_SESSION['dbName'];
    $dbHost = $_SESSION['host'];
    $dbPassword = $_SESSION['dbPassword'];
    $dbUser = $_SESSION['dbUser'];

    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
    if ($conn->error) {
        echo $conn->error;
    }
    $mail = $conn->real_escape_string($_POST['mail']);
    $foreName = $conn->real_escape_string($_POST['forename']);
    $surName = $conn->real_escape_string($_POST['surname']);
    $password = $conn->real_escape_string($_POST['pwd']);

    $password = password_hash($password, PASSWORD_DEFAULT);
    $sqlGet = "SELECT count(*) available FROM user WHERE email LIKE \"$mail\" group by email";
    $res = $conn->query($sqlGet);
    if($res){
        $res = $res->fetch_assoc();
        if ($res['available'] == 0) {
            $console->log("available = true");
            $sql = "INSERT INTO user (prename,lastname,email,password,master)VALUES (\"$foreName\",\"$surName\",\"$mail\",\"$password\",NULL)";
        } else{
            $console->log("available = false");
            header("Location: ../startRegister.php?registerError=1");
            exit;
        }
    }

    $console->log($sql);
    if ($conn->query($sql) === TRUE) {
        header("Location: ../startRegister.php?registerError=0");
    } else {
        header("Location: ../startRegister.php?registerError=2");
        $console->log($conn->error);
    }
}
/*
 * Register Error:
 * 0: No Error
 * 2: Critical Error
 * 1: Email already registered
 */