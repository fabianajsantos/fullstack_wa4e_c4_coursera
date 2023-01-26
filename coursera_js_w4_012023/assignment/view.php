<?php

/*validating profile*/
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

/*CONN AND FUNCTION*/
require_once 'pdo.php';
require_once 'tools.php';

/*GETTING DATA*/
$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

/*VALIDATING ROWS*/
if ($row === false) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: index.php');
    return;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Fabiana - Resume Registry</title>

    <?php require_once "head.php"; ?>

</head>
<body>
<div class="container">

    <h1>Profile information</h1>

    <?php
    flashMessages();
    ?>

    <p>First Name: <?= $row['first_name'] ?></p>
    <p>Last Name: <?= $row['last_name'] ?></p>
    <p>Email: <?= $row['email'] ?></p>
    <p>Headline: <?= $row['headline'] ?></p>

    <p>Education: </p>
    <ul>
        <?php

        $educations = loadEdu($pdo, $_REQUEST['profile_id']);
        //   $positions = loadPos($pdo, $_GET['profile_id']);

        foreach ($educations as &$edu) {
            echo "<li>";
            echo $edu["year"] . ": " . $edu["name"];
            echo "</li>";
        }
        ?>
    </ul>
    <p>Position: </p>
    <ul>
        <?php

        $positions = loadPos($pdo, $_REQUEST['profile_id']);
        //   $positions = loadPos($pdo, $_GET['profile_id']);

        foreach ($positions as &$pos) {
            echo "<li>";
            echo $pos["year"] . ": " . $pos["description"];
            echo "</li>";
        }
        ?>
    </ul>


    <p><a href="index.php">Done</a></p>

</div>

</body>
</html>