<?php
//index.php
require_once 'pdo.php';
require_once 'tools.php';

session_start();
/*if (!isset($_SESSION['user_id'])) {
    die('Not logged in');
}*/
if (!isset($_SESSION['user_id'])) {
    include_once('without_login.php');
}else{

    $stmt = $pdo->query('select * from profile');
    $profiles = $stmt->fetchAll(Pdo::FETCH_ASSOC);
}
/*if (isset($_POST['logout'])) {
    header('Location: logout.php');
    return;
}*/
/**else{
    include_once('index.php');

}*/

//Retrieve de profile from the database

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


    //else {
    ?>

    <h1>teste nao logado</h1>
</body>

