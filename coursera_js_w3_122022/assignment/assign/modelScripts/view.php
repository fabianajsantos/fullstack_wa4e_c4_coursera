<?php
/*from orlando*/
<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['email']) ) {
    die("ACCESS DENIED");
}

if ( isset($_POST['done'] ) ) {
    // Redirect the browser to game.php
    $_SESSION["done"] =$_POST['done'];
    header("Location: index.php");
    return;
}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;

}

$stmt = $pdo->prepare("SELECT first_name, last_name, email, headline, summary, profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
/*
$stmt = $pdo->prepare("SELECT year, description FROM Position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row_position = $stmt->fetch(PDO::FETCH_ASSOC);
*/

$profile_position = $_GET['profile_id'];
$stmt = $pdo->query("SELECT year, description FROM Position where profile_id = $profile_position");
//$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row_position = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
         <title>Orlando Nakamaura's Profile information</title>
    </head>
    <body>
        <h1>Deleteing Profile</h1>

        <form method="post">

            <p>First Name: <?= $fn ?> </p>

            <p>Last Name: <?= $ln ?> </p>
            <p>Email: <?= $email ?> </p>
            <p>Headline:<br/>
            <p> <?= $headline ?> </p>
            <p>Summary:<br/>
            <p> <?= $summary ?> </p>
            <p> Position </p>
            <?php
                echo "\n<ul>\n";
                $count=1;
                foreach ($row_position as $rows_p) {


                    echo ("<li>"."<!--".$count."-->");
                    echo($rows_p['year']." ".$rows_p['description']."</li>");
                    $count++;
                }
                echo "\n</ul>\n";
            ?>

            <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>"/>
            <input type="submit" name="done" value="done"/>
        </form>

    </body>
</html>

