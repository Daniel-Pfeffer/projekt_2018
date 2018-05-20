<?php
/**
 * Created by PhpStorm.
 * User: danielpfeffer
 * Date: 11.04.18
 * Time: 19:56
 */
session_start();
$_SESSION['isLoggedIn']=false;
$_SESSION['user'] = null;
header("Location: ../startRegister.php");