<?php
/**
 * Created by PhpStorm.
 * User: Daniel Pfeffer
 * Date: 22.03.18
 * Time: 06:45
 */
session_start();
if(isset($_SESSION)){
    $_SESSION['dbName']="project";
    $_SESSION['host']="localhost";
    $_SESSION['dbUser'] = "project";
    $_SESSION['dbPassword']="thisIsTheProjectOfTheNightTheNightOhJeah";
    $_SESSION['user'];
    $_SESSION['isLoggedIn'];
}

if(!$_SESSION['isLoggedIn']){
    header("Location: defaultFiles/startRegister.php");
} else{
    header("Location: defaultFiles/spicyBank.php");
}
exit;