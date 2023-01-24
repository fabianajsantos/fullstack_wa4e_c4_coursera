<?php

echo 'teste';

require_once "pdo.php";
require_once "tools.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    die('ACCESS DENIED');
}
if (isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
}



//begin to update data
$stmt = $pdo->prepare('UPDATE profile SET 
                first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su
            WHERE profile_id = :pid and user_id=:uid');
$stmt->execute(array
    (
        ':pid' => $_REQUEST['profile_id'],
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])

);



insertPositions($pdo, $_REQUEST['profile_id']);

$stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
$stmt->execute(array(':pid' => $_REQUEST['profile_id']));


if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}


//select data to show on the form
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

/*$_SESSION['success'] = 'Record updated';
header('Location: index.php');
return;*/

/*









//get profile
$stmt = $pdo->prepare('SELECT * FROM profile where profile_id = :prof AND user_id= :uid');
$stmt->execute(array(':prof' => $_REQUEST['profile_id'], ':uid' => $_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ($profile === false) {
    $_SESSION['error'] = "Could not load profile";
    header('Location: index.php');
    return;
}
$fn = htmlentities($profile['first_name']);
$ln = htmlentities($profile['last_name']);
$em = htmlentities($profile['email']);
$he = htmlentities($profile['headline']);
$su = htmlentities($profile['summary']);
$profile_id = $profile['profile_id'];


$_SESSION['success'] = 'Record updated';
header('Location: index.php');
return;
*/
//reinsert positions

//delete data
// Clear out the old position entries
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--    <title>Fabiana Santos - b16238d0</title>-->
    <title>Fabiana Santos - b16238d0</title>
    <?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
    <form
    ="form" method="post">
    <H1>Editing profile for <?php echo $_SESSION['name']; ?></H1>
    <?php flashMessages(); ?>
    <form method="post" action="edit.php">
      <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
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
        <div id="position_fields">
        <?php
        //load positions
        //$positions = loadPos($pdo, $profile['profile_id']);
        $positions = loadPos($pdo, $_REQUEST['profile_id']);

        $countPos = 0;
        foreach ($positions as &$pos)
        {
            $countPos++;
            echo '<div id="position'.$countPos.'">';
            echo '<p>Year: <input type="text" name="year'.$countPos.'" value="'.$pos["year"].'" />';
            /*INSIDE PHP CODE I HAD A JQUERY CODE ON ONCLICK*/
            echo '<input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();countPos--;return false;"></p>';
            echo '<textarea name="desc'.$countPos.'" rows="8" cols="80">'.$pos["description"].'</textarea>';
            echo '</div>';
        }
        ?>
        </div>
        <input class="btn btn-primary btn-sm" type="submit" value="Save">
        <a href="index.php" class="btn btn-primary btn-sm" role="button">Cancel</a>
    </form>
          </div>
        </body>
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
                    <input type = "button"  value="-" \
                    onclick="$(\'#position' + countPos + '\').remove();return false;"></p>\
                    <textarea name="desc' + countPos + '" cols="80" rows="8"></textarea>\
                    </div>');
        });//end click
    });
</script>
</html>



