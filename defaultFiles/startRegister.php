<?php
/**
 * Created by PhpStorm.
 * User: Daniel Pfeffer
 * Date: 22.03.18
 * Time: 06:56
 */
session_start();
include("../library/console.php");
$console = new console();
$dbName = $_SESSION['dbName'];
$dbHost = $_SESSION['host'];
$dbPassword = $_SESSION['dbPassword'];
$dbUser = $_SESSION['dbUser'];

$conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);
if (isset($_COOKIE['spicyBankUserSecure'])) {
    $valueOfCookie = $_COOKIE['spicyBankUserSecure'];
    $valueOfCookie = explode(":", $valueOfCookie);
    $selector = $valueOfCookie[0];
    $validator = explode("^^", $valueOfCookie[1]);

    $sql = "SELECT hashedValidator,userid,expires from auth_tokens where selector like \"$selector\" ";
    $res = $conn->query($sql);
    if ($res) {
        $res = $res->fetch_assoc();
        if (strtotime($res['expires']) - time() <= 0) {
            if (password_verify($valueOfCookie[1], $res['hashedValidator'])) {
                $console->log("Welcome to 2nd if");
                $oneID = $res["userid"];
                $getUserSpecifiedThings = "SELECT email, password from user where id = \"$oneID\"";
                $userAttr = $conn->query($getUserSpecifiedThings);
                if ($userAttr) {
                    $isRem = true;
                    $userAttr = $userAttr->fetch_assoc();
                    $mail = $userAttr['email'];
                    $password = $userAttr['password'];
                }
            }
        }
    }
}
$deleteBoi = "DELETE auth_tokens FROM project.auth_tokens WHERE (expires - " . time() . ") < 0";
$console->log($deleteBoi);
$conn->query($deleteBoi);
?>
<html>
<head>
    <link rel="icon" href="https://media.giphy.com/media/YJjvTqoRFgZaM/giphy.gif">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/createUserForm.css">
    <meta charset="utf-8">
    <title>Spicy Banking</title>
</head>
<body>
<nav class="navbar bg-dark navbar-dark">
    <a class="navbar-brand">
        <h2 class="navbar-text justify-content-center">Registrierung</h2>
    </a>

    <div class="navbar-nav dropdown show" id="dropdownDiv">
        <div class="nav-item btn btn-secondary dropdown-toggle" role="button"
             id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <a class="nav-link justify-content-end">Login</a>
        </div>
        <form class="dropdown-menu p-4" x-placement="bottom-start" aria-labelledby="dropdownMenuLink"
              action="functions/login.php" method="post" id="dropdownForm" autocomplete="new-mail">
            <div class="form-group">
                <label for="dropdownFormEmail2">Email address</label>
                <input type="email" class="form-control" id="dropdownFormEmail2" autocomplete="new-password"
                       placeholder="email@example.com"
                       name="mailLogin" value="<?php if ($isRem) echo "$mail"; ?>"/>
            </div>
            <div class="form-group">
                <label for="dropdownFormPassword2">Password</label>
                <input type="password" class="form-control" autocompelete="new-password" id="dropdownFormPassword2"
                       placeholder="Password"
                       name="passwordLogin"/>
            </div>
            <div class="form-group ml-4">
                <input class="form-check-input" type="checkbox" id="remMe" name="rememberMe" <?php if ($isRem) {
                    echo 'checked=\"yes\"';
                } ?>"/>
                <label class="form-check-label" for="remMe">Remember me</label>
            </div>
            <input type="submit" class="btn btn-primary" value="Sign in"/>
        </form>
    </div>
</nav>

<div class="container mt-5">

    <form action="functions/register.php" method="post" onsubmit="return checkAll()">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="regForename">Vorname:</label><br>
                <input class="form-control" name="forename" id="regForename"/><br>
            </div>
            <div class="form-group col-md-6">
                <label for="regSurname">Nachname:</label><br>
                <input class="form-control" name="surname" id="regSurname"/><br>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">

                <label for="preMail">Email:</label>
                <?php
                if (isset($_GET['registerError'])) {
                    $regErr = $_GET['registerError'];
                    if ($regErr == 1) {
                        $errMess = "E-Mail bereits vorhanden";
                        echo "<label id='errorExecute' class='badge badge-warning'>$errMess</label>";
                    }
                }
                ?><br>
                <input name="mail" class="form-control" type="email" id="preMail"/><br>
            </div>
            <div class="form-group col-md-6">
                <label for="prePassword">Passwort:</label><br>
                <input name="pwd" class="form-control" type="password" id="prePassword"/><br>
            </div>
        </div>
        <input type="submit" class="form-control col-md-3 mx-auto" value="Registrieren" id="submit"/>
        <div class="row">
            <?php
            if (isset($_GET['registerError'])) {
                $regErr = $_GET['registerError'];
                if ($regErr == 0) {
                    $errMess = "Registrieren war erfolgreich";
                    echo "<label id='errorExecute' class='badge badge-secondary'>$errMess</label>";
                } elseif ($regErr == 2) {
                    $errMess = "Kritischer Fehler";
                    echo "<label id='errorExecute' class='badge badge-danger'>$errMess</label>";
                }
            }
            ?></div>
    </form>
</div>
</body>
</html>
