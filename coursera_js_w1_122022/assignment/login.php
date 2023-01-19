<?php

session_start();

require_once "pdo.php";
require_once "bootstrap.php";

if (isset($_POST['cancel'])) {
    // Redirect the browser to add.php
    header("Location: index.php");
    return;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if (isset($_POST["email"]) && isset($_POST["pass"])) {
    unset($_SESSION["email"]);  // Logout current user

    if (empty($_POST['email']) || empty($_POST['pass'])) {

        $_SESSION["error"] = "Email and password are required.";
        error_log("Email and password are required!", 0);
        header('Location: login.php');
        return;

    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        //   $_SESSION["error"] = "Email must have an at_sign (@)";
        error_log("Email must have an at_sign (@)", 0);
        header('Location: login.php');
        return;

    } else {

        $check = hash('md5', $salt . $_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
        WHERE email = :em AND pass = :pw');
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;

        } else {
            $_SESSION["error"] = "Incorrect password";
            header('Location: login.php');
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title> Fabiana Santos - b16238d0</title>

    <?php require_once "bootstrap.php"; ?>
    <!--    calling external js-->
    <script type="text/javascript" src="valida.js"></script>

</head>
<body>
<div class="container">
    <h1>Please Log In</h1><br>

    <?php
    if (isset($_SESSION["error"])) {
        echo('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
        unset($_SESSION["error"]);
    }
    if (isset($_SESSION["success"])) {
        echo('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
        unset($_SESSION["success"]);
    }
    ?>

    <form method="POST">
        <div><label for="nam">Email</label>
            <input type="text" name="email" id="nam">
        </div>
        <br/>
        <div><label for="id_1723">Password</label>
            <input type="text" name="pass" id="id_1723">
        </div>
        <br/>
        <input type="submit" value="Log In" onclick="return doValidate();">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>