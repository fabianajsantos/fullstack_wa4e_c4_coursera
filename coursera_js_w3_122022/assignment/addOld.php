<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}
// If the user requested logout go back to index.php
if (isset($_POST['logout'])) {
    header('Location: logout.php');
    return;
}
if (isset($_POST['cancel'])) {
    // Redirect the browser to view.php
    header("Location: view.php");
    return;
}
//inserted variables - validating not set
if ((isset($_POST['first_name']) && isset($_POST['last_name'])) && isset($_POST['headline'])) {
    //validating  if blank
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: add.php?profile_id=" . $_POST['profile_id']);
        //header('Location: add.php');

        return;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Email must have an at_sign (@)";
        error_log("Email must have an at_sign (@)", 0);
        header("Location: add.php?profile_id=" . $_POST["profile_id"]);
        //header('Location: login.php');
        return;
    } else {
        $stmt = $pdo->prepare('INSERT INTO Profile  (user_id,first_name, last_name, email, headline, summary)
            VALUES ( :uid, :fn, :ln, :em, :he, :su)');

        $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
        );

        $profile_id = $pdo->lastInsertId();


        //insert the positions entries
        $rank = 1;
        for ($i = 0; $i <= 1; $i++) {
            if (!isset($_POST['year' . $i])) continue;
            if (!isset($_POST['desc' . $i])) continue;
            $year = $_POST['year' . $i];
            $desc = $_POST['desc' . $i];

            $smtp = $pdo->prepare('insert into position (profile_id, rank, year, description) values ( :pid, :rank, :year, :desc)');
            $smtp->execute(array(
                    ':pid' => $_SESSION['profile_id'],
                    ':rank' => $_POST['rank'],
                    ':year' => $_POST['year'],
                    ':desc' => $_POST['description'])
            );
            $rank++;
        }
        $_SESSION["success"] = 'Record added.';
        header('Location: index.php');
        return;
    }
}
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
<!--        <div class="login-card card" style="width: 18rem;">
-->            <div class="card-header bg-transparent">
                            <span class="font-weight-bold">Adding Profile for
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
                            <label for="fn">First Name:</label>
                            <input type="text" class="form-control form-control-sm" name="first_name">
                            <br>
                            <label for="ln">Last Name:</label>
                            <input type="text" class="form-control form-control-sm" name="last_name">
                            <br>
                            <label for="mail">Email:</label>
                            <input type="text" class="form-control form-control-sm" name="email">
                            <br>
                            <label for="he">Headline:</label>
                            <input type="text" class="form-control form-control-sm" name="headline">
                            <br>
                            <label for="su">Summary:</label>
                            <textarea name="summary" class="form-control form-control-sm"></textarea>
                            <br>
                            <textarea name="year" class="form-control form-control-sm"></textarea>
                            <br>
                            <textarea name="desc" class="form-control form-control-sm"></textarea>
                            <br>
                            <input class="btn btn-primary btn-sm" type="submit" value="Add" name="Add">
                            <input class="btn btn-primary btn-sm" type="submit" name="cancel" value="Cancel">

                        </div>
        </div>
    </form>
</body>





