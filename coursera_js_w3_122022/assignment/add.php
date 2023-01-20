<?php
session_start();
require_once "pdo.php";
require_once "tools.php";

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
    header("Location: index.php");
    return;
}

//inserted variables - validating not set - handle incoming data
if ((isset($_POST['first_name'])    && isset($_POST['last_name'])    && isset($_POST['headline'])    && isset($_POST["email"])    && isset($_POST["headline"])    && isset($_POST["summary"]))) {
    //validating  if blank
    if (strlen($_POST['first_name']) < 1   || strlen($_POST['last_name']) < 1  || strlen($_POST['headline']) < 1  || strlen($_POST['email']) < 1   || strlen($_POST['summary']) < 1 ) {

        $msg = validateProfile();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }
        ///validate position entries
        $msg = validatePos();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        }
    }
//Inserting valid data
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
    for ($i = 1; $i <= 3; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;
        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        $smtp = $pdo->prepare('insert into position (profile_id, rank, year, description) values ( :pid, :rank, :year, :desc)');
        $smtp->execute(array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
        );
        $rank++;
    }

    $_SESSION["success"] = 'Record added.';
    header('Location: index.php');
    return;
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
    <form method="post">
        <div class="card-header bg-transparent">
            <h1>Adding Profile for <?php echo $_SESSION['name']; ?></h1>
            <?php flashMessages();  ?>
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
        </div>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" size="60">
            <br>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" size="60">
            <br>
            <label for="email">Email:</label>
            <input type="text" name="email" size="60">
            <br>
            <label for="headline">Headline:</label>
            <input type="text" name="headline" size="60">
            <br>
            <label for="summary">Summary:</label>
            <textarea name="summary"></textarea>
            <p>Position: <input type="submit" id="addPos" value="+">
            <div id="position_fields"></div>
            </p>
            <p>
                <input class="btn btn-primary btn-sm" type="submit" value="Add" name="add">
                <input class="btn btn-primary btn-sm" type="submit" name="cancel" value="Cancel">
            </p>
        </form>
        <script>
            countPos = 0;
            $(document).ready(function () {
                window.console && console.log('Document ready called');
                $('#addPos').click(function (event) {
                    event.preventDefault();
                    if (countPos >= 3) {
                        alert("max p pos");
                        return;
                    }
                    countPos++;

                    window.console && console.log("add " + countPos);
                    $('#position_fields').append
                    (
                        '<div id="position' + countPos + '"> \
                    <p>Year:<input type="text" name="year' + countPos + '" value="" />\
                    \
                    <input type = "button"  value="-" \
                    onclick="$(\'#position' + countPos + '\').remove();return false;"></p>\
                    <textarea name="desc' + countPos + '" cols="80" rows="8"></textarea>\
                    </div>'
                    );
                });//end click
            });//end ready
        </script>
</body>