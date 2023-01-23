<?php

require_once "pdo.php";
require_once "tools.php";
session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}
if ((isset($_POST['first_name']) && isset($_POST['last_name'])) && isset($_POST['headline']) && isset($_POST['profile_id'])) {
// Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
         return;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Email must have an at_sign (@)";
        error_log("Email must have an at_sign (@)", 0);
        header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
        return;
    }
    //start validation message
        ///validate position entries
        $msg = validatePos();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
            return;
        }
    //end validadtion message

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

    //POSITIONS
    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    //INSERT POSITIONS
    $rank = 1;
    for ($i = 1; $i <= 3; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
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
    header('Location: index.php');
    return;

}//end if ((isset($_POST['first_name']) && ...

//reading data from db
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
 //   $positions = $row['positions'];
    $profile_id = $row['profile_id'];

//load positions
    $positions = loadPos($pdo, $_REQUEST['profile_id']);

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
        <?php //print_r($positions);
                    ///HOW CAN I DO THIS!!!!!!!////  GRATEFULL ARMANDO

        $positions = loadPos($pdo, $row['profile_id']);

        $countPos = 0;
        foreach ($positions as &$pos) {
            $countPos++;
            echo '<div id="position'.$countPos.'">';
            echo '    <p>Year: <input type="text" name="year'.$countPos.'" value="'.$pos["year"].'" />';
            /*INSIDE PHP CODE I HAD A JQUERY CODE ON ONCLICK*/
            echo '        <input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();countPos--;return false;"></p>';
            echo '        <textarea name="desc'.$countPos.'" rows="8" cols="80">'.$pos["description"].'</textarea>';
            echo '</div>';
        }
            ?>
        </div>
        </p>
        <!--include positions- NOT WORKING-->
        <input class="btn btn-primary btn-sm" type="submit" value="Save">
        <a href="index.php" class="btn btn-primary btn-sm" role="button">Cancel</a>
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
                    <input type = "button"  value="-" \
                    onclick="$(\'#position' + countPos + '\').remove();return false;"></p>\
                    <textarea name="desc' + countPos + '" cols="80" rows="8"></textarea>\
                    </div>'
                );
            });//end click

            $('#addEdu').click(function(event){
                event.preventDefault();
                if(countEdu >= 3){
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console.log("Adding education " +countEdu);

                //grab some html with hot spots and insert into the dom
                var source = $("#edu-Template").html();
                $('#edu_fields').append(source.replace(/@COUNT@/g, countEdu));

                //Add the even handler to the new ones
                $('.scholl').autocomplete({
                    source: "school.php"
                });
            });
            
            $('.scholl').autocomplete({
                source: "school.php"
            })

        });//end ready
    </script>
</body>