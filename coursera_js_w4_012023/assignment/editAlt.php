<?php

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
if (!isset($_REQUEST['profile_id'])) {
    die('ACCESS DENIED');
}
/*if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}*/
//reading data from db

/*if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}*/

$stmt = $pdo->prepare('SELECT * FROM profile where profile_id = :prof AND user_id= :uid');
$stmt->execute(array(':prof' => $_REQUEST['profile_id'], ':uid' => $_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if ($profile === false) {
    $_SESSION['error'] = "Could not load profile";
    header('Location: index.php');
    return;
}

if ((isset($_POST['first_name']) && isset($_POST['last_name'])) &&
    isset($_POST['email']) && isset($_POST['headline']) &&
    isset($_POST['summary'])) {
// Data validation
    /*  if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
          $_SESSION['error'] = 'All fields are required';
          header("Location: edit.php?profile_id=" . $_POST['profile_id']);
           return;
      } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

          $_SESSION["error"] = "Email must have an at_sign (@)";
          error_log("Email must have an at_sign (@)", 0);
          header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
          return;
      }*/
    //start validation message
    //validate profile
    $msg = validateProfile();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: add.php");
        return;
    }
    ///validate position entries if present
    $msg = validatePos();
    if (is_string($msg)) {
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=" . $_REQUEST["profile_id"]);
        return;
    }
    //end validadtion message

    //should validae educations

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

    //POSITIONS
    //INSERT POSITIONS
    insertPositions($pdo, $_REQUEST['profile_id']);

    /* $rank = 1;
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
         $rank++;*/

    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
    $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

    insertEducation($pdo, $_REQUEST['profile_id']);

    /*    $_SESSION['success'] = 'Profile updated';
        header('Location: index.php');
        return;*/


    $fn = htmlentities($row['first_name']);
    $ln = htmlentities($row['last_name']);
    $em = htmlentities($row['email']);
    $he = htmlentities($row['headline']);
    $su = htmlentities($row['summary']);
    //   $positions = $row['positions'];
    $profile_id = $row['profile_id'];


    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;

//end if ((isset($_POST['first_name']) && ...
}



//load positions
 //   $positions = loadPos($pdo, $_REQUEST['profile_id']);
   // $schools = loadEdu($pdo, $_REQUEST['profile_id']);

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
        <?php

          $schools = loadEdu($pdo, $_REQUEST['profile_id']);

          $countEdu = 0;


          echo ('<p>Education: <input type="submit" id="addEdu"  value="+">'."\n");
          echo ('div id="edu_fields">'."\n");
          if (count($schools)> 0){
              foreach ($schools as $school){
                  $countEdu++;
                  echo('div id="edu'.$countEdu.'">');
                  echo
                '<p>Year: <input type="text" name="edu_year'.$countEdu.'" value="'.$school["year"].'" />
                <input type="button" value="-" onclick="$(\'#edu'.$countEdu.'\').remove();countPos--;return false;"></p>
                <p>School: <input type="text" size="80" name="edu_school'.$countEdu. '" class="school" value="'.htmlentities($school['name']).'"/>';
                echo "\n</div>\n";
              }
          }
               echo ("div></p>\n");
        ?>







        </div>
        </>
        <p>School: <input type="text" size="80" name="edu_school1" class="school" value="" /></p>


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
                    </div>');
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
                $('.school').autocomplete({
                    source: "school.php"
                });
            });
            
            $('.school').autocomplete({
                source: "school.php"
            });

        });//end ready
    </script>
     <!--   html with substitution hot spots-->
<!--    <script id="edu-template" type="text">
    <div id="edu@COUNT@">
    <p>Year:<input type="text" name="edu_year@COUNT@" value="" />
    <input type = "button"  value="-" onclick="$('#edu@COUNT@').remove(); return false;"><br>
    <p>School:<input type="text" size="80" name="edu_year@COUNT@" class="school" value="" />
    </p>
    </div>
    </script>-->
</div>
</body>
</html>