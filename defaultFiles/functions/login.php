<?php
include("../../library/console.php");
$console = new console();
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $dbName = $_SESSION['dbName'];
    $dbHost = $_SESSION['host'];
    $dbPassword = $_SESSION['dbPassword'];
    $dbUser = $_SESSION['dbUser'];

    $conn = mysqli_connect($dbHost, $dbUser, $dbPassword, $dbName);

    $passwordInput = $conn->real_escape_string($_POST['passwordLogin']);
    $emailInput = $conn->real_escape_string($_POST['mailLogin']);
    $remember = $_POST['rememberMe'];

    $sql = "SELECT user.id,user.prename,user.lastname,user.email,user.password FROM user WHERE email LIKE \"$emailInput\"";

    $res = $conn->query($sql);
    if ($res) {
        $res = $res->fetch_assoc();
        if (password_verify($passwordInput, $res['password'])) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['user'] = [$res['id'], $res['prename'], $res['lastname']];
            if ($remember == "on") {
                //Selector = name of cookie
                //Validator = value of cookie
                $cookieSelector = generateToken();
                $userID = $res['id'];
                $cookieValidator = implode("^^", $_SESSION['user']);
                $expireDate = time() + (86400 * 12);
                setcookie("spicyBankUserSecure", $cookieSelector . ":" . $cookieValidator, $expireDate, "/");
                $hashedValidator = password_hash($cookieValidator, PASSWORD_DEFAULT);
                $secureCookie = "INSERT INTO auth_tokens (selector, hashedValidator, userid, expires) VALUES (\"$cookieSelector\",\"$hashedValidator\",\"$userID\",\"$expireDate\")";
                $conn->query($secureCookie);
            }
            header("Location: ../spicyBank.php");
            exit;
        } else {
            header("Location: ../startRegister.php");
            exit;
        }
    } else {
        header("Location: ../startRegister.php");
        exit;
    }
}
function generateToken($length = 20)
{
    try {
        return bin2hex(random_bytes($length));
    } catch (Exception $e) {
    }
}