<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title> Fabiana Santos - b16238d0</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <?php

    if (!isset($_SESSION['user_id'])) {
        include_once('without_login.php');
    } else {
        include_once('view.php');
    }
    ?>
</div>
</body>

