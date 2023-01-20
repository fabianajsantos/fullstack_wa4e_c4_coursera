<?php
session_start();
require_once "pdo.php";
require_once "tools.php";

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing auto_id";
    header('Location: index.php');
    return;
}
?>
<!--//start to view-->
<!DOCTYPE html>
<html>
<head>
    <title> Fabiana Santos - b16238d0</title>
    <?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
    <h1>Profile information</h1>
    <?php
    if (isset($_SESSION["error"])) {
        echo('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
        unset($_SESSION["error"]);
    }
    if (isset($_SESSION["success"])) {
        echo('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
        unset($_SESSION["success"]);
    }

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $positions = loadPos($pdo, $_REQUEST['profile_id']);

    foreach ($rows as $row) {

        //var_dump($rows);
        echo '<tr>';
        echo '<td>' . '<p>First name: </p>' . '</td>';
        echo '<td>' . (htmlentities($row['first_name'])) . '</td>';
        echo '<td>' . '<p>Last name: </p>' . '</td>';
        echo '<td>' . (htmlentities($row['last_name'])) . '</td>';
        echo '<td>' . '<p>Email: </p>' . '</td>';
        echo '<td>' . (htmlentities($row['email'])) . '</td>';
        echo '<td>' . '<p>Headline: </p>' . '</td>';
        echo '<td>' . (htmlentities($row['headline'])) . '</td>';
        echo '<td>' . '<p>Summary:: </p>' . '</td>';
        echo '<td>' . (htmlentities($row['summary'])) . '</td>';
        echo '<td>' . '<p>Position: </p>' . '</td>';
        /*        echo '<td>' . '<?= $positions ?>' . '</td>'; I dont know how call positions*/
        echo '<tr>';

        $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
        $stmt->execute(array(":xyz" => $_GET['profile_id']));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>
    <a href="index.php" class="btn btn-primary" role="button">done</a>
</div>
</body>


