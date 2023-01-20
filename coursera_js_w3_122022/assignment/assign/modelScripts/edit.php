<?php
 /*from orlando*/

<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['email']) ) {
    die("ACCESS DENIED");
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    $_SESSION["cancel"] =$_POST['cancel'];
    header("Location: index.php");
    return;
}

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    // Data validation
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    if ( strpos($_POST['email'],'@') === false ) {
        $_SESSION['error'] = 'Email address must contain @';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    $sql = "UPDATE profile SET first_name = :first_name, last_name = :last_name, 
            email = :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id' => $_POST['profile_id']));


    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

    // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }



    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for user_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
        <title>Orlando Nakamaura's Profile Database</title>
    </head>

    <body>

        <div class="container">
            <p>Edit User</p>
            <form method="post">
                <p>First Name:
                <input type="text" name="first_name" value="<?= $fn ?>"></p>
                <p>Last Name:
                <input type="text" name="last_name" value="<?= $ln ?>"></p>
                <p>Email:
                <input type="text" name="email" value="<?= $email ?>"></p>
                <p>Headline:<br/>
                <input type="text" name="headline" value="<?= $headline ?>"></p>
                <p>Summary:<br/>
                <textarea name="summary" rows="8" cols="80"></textarea></p>
                <p> Position: <input type="submit" id="addPos" value="+">
                <div id="position_fields"> </div> </p>
                <input type="text" name="summary" value="<?= $summary ?>"></p>
                <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
                <p><input type="submit" value="Save"/>
                <p><input type="submit" value="Cancel" name="cancel"/>
            </form>

            <script>

                countPos=0;

                $(document).ready(function()    {
                    window.console && console.log('Document ready called');
                    $('#addPos').click(function(event){
                        event.preventDefault();
                        if(countPos >= 9) {
                            alert("Maximun of nine position entries exceeded");
                            return;
                        }
                        countPos++;
                        window.console && console.log("Adding position"+countPos);
                        $('#position_fields').append(

                           '<div id="position'+countPos+'"> \ <p> Year: <input type="text" name="year'+countPos+'" value=""/> \ <input type="button" value="-" \ onclick="$(\'#position' +countPos+'\').remove(); return false;"> </p> <textarea name="desc'+countPos+'" rows="8" cols="80"> </textarea> \ </div>'

                        );
                    });

                });


            </script>

        </div>

    </body>
</html>

<!--fim orlando-->