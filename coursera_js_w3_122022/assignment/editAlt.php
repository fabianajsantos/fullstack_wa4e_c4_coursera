<?php

require_once "pdo.php";
//require_once "bootstrap.php";
require_once "tools.php";

session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}
if ((isset($_POST['first_name']) && isset($_POST['last_name'])) && isset($_POST['headline']) && isset($_POST['profile_id'])) {
   //changed

    // Data validation
    /*    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
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
        }*/
    //changed
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
        return;
    }
    //validate position entries
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
        return;
    }

/*    $sql = "UPDATE profile SET
                        first_name = :first_name,   
                        last_name = :last_name,
                        email = :email, 
                        headline = :headline, 
                        summary = :summary
                    WHERE profile_id = :profile_id AND user_id:user_id";*/
    //changed
    $stmt = $pdo->prepare('UPDATE profile SET 
                        first_name = :first_name,   
                        last_name = :last_name,
                        email = :email, 
                        headline = :headline, 
                        summary = :summary
                    WHERE profile_id = :profile_id AND user_id:user_id";');
    $stmt->execute(array
        (
            ':profile_id' => $_REQUEST['profile_id'],
            ':user_id' => $_SESSION['user_id'],
            ':first_name' => $_POST['first_name'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary'])
    );

    //changed
    //clear the old positions
    $stmt = $pdo->prepare('DELETE FROM  position where profile_id=:pid');
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    //

/*
    //insert the positions entries
    $rank = 1;
    for ($i = 1; $i <= 2; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;
        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        $smtp = $pdo->prepare('insert into position (profile_id, rank, year, description) values ( :pid, :rank, :year, :desc)');
        $smtp->execute(array(
                ':pid' => $_REQUEST['$profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
        );
        $rank++;
    }
*/
    //changed
    if (!isset($_GET['profile_id'])) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        $_SESSION['error'] = 'Bad value for user_id';
        header('Location: index.php');
        return;
    }
    $fn = htmlentities($row['first_name']);
    $ln = htmlentities($row['last_name']);
    $em = htmlentities($row['email']);
    $he = htmlentities($row['headline']);
    $su = htmlentities($row['summary']);
    $profile_id = $row['profile_id'];


    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;

    //load positions
    $positions = loadPos($pdo, $_REQUEST['profile_id']);

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabiana Santos - b16238d0</title>
    <?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
    <h1>Editing profile for <?php echo $_SESSION['name']; ?></h1>
    <?php flashMessages(); ?>
    <form method="post" action="edit.php">
        <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
        <!--   <div class="form-group">-->
        <p>First Name:
            <input type="text" name="first_name" value="<?= $fn ?>"></p>
        <p>Last Name:
            <input type="text" name="last_name" value="<?= $ln ?>"></p>
        <p>Email:
            <input type="text" name="email" value="<?= $em ?>"></p>
        <p>Headline:
            <input type="text" name="headline" value="<?= $he ?>"></p>
        <p>Summary:
            <textarea name="summary"><?php echo $su; ?></textarea>
        <p>Position: <input type="submit" id="addPos" value="+">
        <div id="position_fields"></div>
        </p>
        <input class="btn btn-primary btn-sm" type="submit" value="Save">
        <a href="index.php" class="btn btn-primary btn-sm" role="button">Cancel</a>

    </form>
</body>
