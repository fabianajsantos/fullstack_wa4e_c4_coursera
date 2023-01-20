<?php
//index.php
require_once 'pdo.php';
require_once 'tools.php';

session_start();

//Retrieve de profile from the database
/*$stmt = $pdo->query('select * from profile');
$profiles = $stmt->fetchAll(Pdo::FETCH_ASSOC);*/

?>
<!DOCTYPE html>
<html>
<head>
   <!-- <title>Fabiana Santos - b16238d0</title>-->
    <title>Fabiana Santos - 984eef88</title>
    <?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
    <?php
flashMessages();
    if (!isset($_SESSION['user_id'])) {
        include_once('without_login.php');
    } else {
        include_once('view.php');
    }
    ?>
</div>
</body>

