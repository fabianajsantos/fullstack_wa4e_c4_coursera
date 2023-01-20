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

<!--ARMANDO-->

<?php
session_start();

if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to autos.php
    header("Location: index.php");
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

require_once 'pdo.php';
require_once 'util.php';

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
} elseif ( $row['user_id'] !== $_SESSION['user_id'] ) {
    $_SESSION['error'] = 'User cant access this profile';
    header( 'Location: index.php' ) ;
    return;
}

if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])
                                 && isset($_POST['headline']) && isset($_POST['summary'])
                                 && isset($_POST['profile_id']) ) {

	$msg = validatePos();
	if ( is_string($msg) ) {
		$_SESSION['error'] = $msg;
		header("Location: edit.php?profile_id=".$_GET['profile_id']);
		return;
	}

    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "E-mail not valid";
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
        $stmt->execute(array(":xyz" => $_POST['profile_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $row === false ) {
            $_SESSION['error'] = 'Bad value for profile_id';
            header( 'Location: index.php' ) ;
            return;
        } elseif ( $row['user_id'] !== $_SESSION['user_id'] ) {
            $_SESSION['error'] = 'User cant update this profile';
            header( 'Location: index.php' ) ;
            return;
        }

            $sql = "UPDATE Profile SET first_name = :first_name, last_name = :last_name,
                    email = :email, headline = :headline, summary = :summary
                    WHERE profile_id = :profile_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':first_name' => htmlentities($_POST['first_name']),
                ':last_name' => htmlentities($_POST['last_name']),
                ':email' => htmlentities($_POST['email']),
                ':headline' => htmlentities($_POST['headline']),
                ':summary' => htmlentities($_POST['summary']),
                ':profile_id' => htmlentities($_POST['profile_id']) ));

			// Clear out the old position entries
			$stmt = $pdo->prepare('DELETE FROM position
			    WHERE profile_id=:pid');
			$stmt->execute(array(':pid' => $_REQUEST['profile_id']));

			// Insert the position entries
			$rank = 1;
			for($i=1; $i<=9; $i++) {
				if ( ! isset($_POST['year'.$i]) ) continue;
				if ( ! isset($_POST['desc'.$i]) ) continue;
				$year = $_POST['year'.$i];
				$desc = $_POST['desc'.$i];

				$stmt = $pdo->prepare('INSERT INTO position
					(profile_id, rank, year, description)
					VALUES ( :pid, :rank, :year, :desc)');
				$stmt->execute(array(
					':pid' => $_REQUEST['profile_id'],
					':rank' => $rank,
					':year' => $year,
					':desc' => $desc,)
				);
				$rank++;
			}

            $_SESSION['success'] = "Profile updated";
            header("Location: index.php");
            return;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Armando Zanone's Resume Registry</title>
<?php require_once "head.php"; ?>

<link rel="stylesheet" href="css/forms.css">

</head>

<body>

<div class="container">
<h1>Adding Profile for UMSI</h1>

<?php flashMessages(); ?>

<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $row['first_name'] ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $row['last_name'] ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $row['email'] ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $row['headline'] ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"><?= $row['summary'] ?></textarea></p>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
<?php

$positions = loadPos($pdo, $row['profile_id']);

$countPos = 0;
foreach ($positions as &$pos) {
	$countPos++;
    echo '<div id="position'.$countPos.'">';
	echo '    <p>Year: <input type="text" name="year'.$countPos.'" value="'.$pos["year"].'" />';
    echo '        <input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();countPos--;return false;"></p>';
    echo '        <textarea name="desc'.$countPos.'" rows="8" cols="80">'.$pos["description"].'</textarea>';
    echo '</div>';
}

?>
</div>
</p>
<p>
<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>"/>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
countPos = <?= $countPos ?>;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position'+countPos+'\').remove();countPos--;return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>

</div>
</body>
</html>

<!--yulia-->

<?php
session_start();
require_once('pdo.php');

if ( ! isset($_SESSION['user_id']) ) {
  die("ACCESS DENIED");
}

if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
&& isset($_POST['email']) && isset($_POST['headline'])
&& isset($_POST['summary'])) {
  if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
  || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
     $_SESSION['error'] = 'All fields are required';
     header("Location: add.php?profile_id=" . $_POST["profile_id"]);
     return;
  }
  if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
       $_SESSION['error'] = "Email address must contain @";
       header("Location: add.php?profile_id=" . $_POST["profile_id"]);
       return;
  }



  try{
    $sql = "UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su
             WHERE profile_id = :pid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary'],
      ':pid' => $_POST['profile_id']));

  $profile_id = $pdo->lastInsertId();

  $rank1 = 1;
  for($i=1; $i<=9; $i++){
    if(!isset($_POST['year'.$i])) continue;
    if(!isset($_POST['desc'.$i])) continue;
    $year = $_POST['year'.$i];
    $desc = $_POST['desc'.$i];

    $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank1, year, description) VALUES ( :pid, :rank1, :year, :desc)');

    $stmt->execute(array(
      ':pid' => $profile_id,
      ':rank1' => $rank1,
      ':year' => $year,
      ':desc' => $desc));
    $rank1++;
  }
  $_SESSION['success'] = 'Profile updated';
  header( 'Location: index.php' );
  return;

  }catch (Exception $er) {
        echo("Error");
        error_log("SQL error=".$er->getMessage());
        return;
    }
 }

 $stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
 $stmt->execute(array(":pid" => $_GET['profile_id']));
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
/*
 $stmt = $pdo->prepare("SELECT * FROM Position WHERE profile_id = :pid");
 $stmt->execute(array(":pid" => $_GET['profile_id']));
 $row = $stmt->fetch(PDO::FETCH_ASSOC);
*/
 if ( $row === false ) {
     $_SESSION['error'] = 'Bad value for profile_id';
     header( 'Location: edit.php' ) ;
     return;
 }

 ?>
 <!DOCTYPE html>
<html>
  <head>
    <title>Yulia Derbeneva</title>
  </head>
  <body>
      <div class="container">
      <h1>Editing Profile</h1>
      <p>
        <?php
            if ( isset($_SESSION['success']) ) {
              echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
              unset($_SESSION['success']);
            }
            if ( isset($_SESSION['error']) ) {
              echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
              unset($_SESSION['error']);
            }

            $first_name=htmlentities($row['first_name']);
            $last_name=htmlentities($row['last_name']);
            $email=htmlentities($row['email']);
            $headline=htmlentities($row['headline']);
            $summary=htmlentities($row['summary']);
            $profile_id = $row['profile_id'];
            $year=htmlentities($row['year']);
            $desc=htmlentities($row['desc']);
        ?>
      </p>
      <form method="post">
     <p>First Name:
      <input type="text" name="first_name" value="<?= $first_name ?>"</p>
      <p>Last Name:
      <input type="text" name="last_name" value="<?= $last_name ?>"</p>
      <p>Email:
      <input type="text" name="email" value="<?= $email ?>"</p>
      <p>Headline:<br><input type="text" name="headline" value="<?= $headline ?>"</p>
      <p>Summary:<br>  <input type="text" name="summary" value="<?= $summary ?>">
        <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
      <p>Position:
      <input type="submit" name="addPos" id="addPos" value="+">
      <div id="position_fields"></div>
      </p>
      <p><input type="submit" value="Save">
      <input type="submit" name="cancel" value="Cancel"></p>
      </form>
      <script>
      countPos = 0;
      $(document).ready(function(){
        window.console && console.log('Document ready called');
        $('#addPos').click(function(event){
        event.preventDefault();
        if (countPos >= 9) {
          alert("Maximum of nine position entries exceeded");
          return;
        }
        countPos++;
        window.console && console.log("Adding position " + countPos);
        $('#position_fields').append(
          '<div id="position' + countPos + '"> \
                  <p>Year: <input id="year" type="text" name="year' + countPos + '" value="<?= $year ?>" /> \
                  <input type="button" value="-" \
                      onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
                  <input type="text" value="<?= $desc ?>" name="desc' + countPos + '" rows="8" cols="80"></textarea>\
                  </div>');
        });
      });
      </script>
    </div>
    </body>
  </html>

