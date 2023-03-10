<?php

require_once "pdo.php";
require_once "bootstrap.php";

session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}
if ((isset($_POST['first_name']) && isset($_POST['last_name'])) && isset($_POST['headline']) && isset($_POST['profile_id'])) {
// Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        //header('Location: add.php');

        return;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Email must have an at_sign (@)";
        error_log("Email must have an at_sign (@)", 0);
        header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
        //header('Location: login.php');
        return;
    }
    $sql = "UPDATE profile SET 
                first_name = :first_name,   
                last_name = :last_name,
                email = :email, 
                headline = :headline, 
                summary = :summary
            WHERE profile_id = :profile_id ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array
        (
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary'],
            ':profile_id' => $_POST['profile_id'])
    );
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header('Location: index.php');
    return;
}
$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$he = htmlentities($row['headline']);
$su = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabiana Santos - b16238d0</title>
    <link rel="stylesheet" href="assets/css/comum.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icofont.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<div class="container">
    <form class="form-login" method="post">
            <div class="card-header bg-transparent">
                                    <span class="font-weight-bold">Editing profile for
                                        <?php echo $_SESSION['name'];
                                        if (isset($_SESSION["error"])) {
                                            echo('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
                                            unset($_SESSION["error"]);
                                        }
                                        if (isset($_SESSION["success"])) {
                                            echo('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
                                            unset($_SESSION["success"]);
                                        }
                                        ?>
                                    </span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <p>First Name:
                        <input type="text" name="first_name" value="<?= $fn ?>"></p>
                    <p>Last Name:
                        <input type="text" name="last_name" value="<?= $ln ?>"></p>
                    <p>Email:
                        <input type="text" name="email" value="<?= $em ?>"></p>
                    <p>Headline:
                        <input type="text" name="headline" value="<?= $he ?>"></p>
                    <p>Summary:
                        <textarea name="summary" class="form-control form-control-sm"></textarea>
                        <input type="hidden" name="profile_id" value="<?= $profile_id ?>">

                        <input class="btn btn-primary btn-sm" type="submit" value="Save">
                        <a href="index.php" class="btn btn-primary btn-sm" role="button">Cancel</a>
                </div>
            </div>
</div>
</form>
</body>
