<?php
<?php
require_once "pdo.php";
session_start();

$failure = false;


     $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :xyz");
     $stmt->execute(array(":xyz" => $_GET["profile_id"]));
     $row = $stmt->fetch(PDO::FETCH_ASSOC);
     if($row === false){

        $_SESSION["success"] = "Bad value for auto_id";
        header("Location: index.php");
        return;


     }

     $a = htmlentities($row["first_name"]);
     $b = htmlentities($row["last_name"]);
     $c = htmlentities($row["email"]);
     $d = htmlentities($row["headline"]);
     $e = htmlentities($row["summary"]);





     if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"]) && isset($_POST["headline"]) && isset($_POST["summary"])){


      if(strlen($_POST["first_name"]) > 1 && strlen($_POST["last_name"])> 1 && strlen($_POST["email"]) > 1 && strlen($_POST["headline"])> 1 && strlen($_POST["summary"])> 1){
        if(str_contains(haystack: $_POST["email"], needle: "@")){



              $sql = "UPDATE profile SET  first_name = :first_name, last_name = :last_name, email =:email, headline = :headline , summary = :summary WHERE profile_id = :profile_id";
              $stmt = $pdo->prepare($sql);
              $stmt->execute(array(
                  ":first_name" => $_POST["first_name"],
                  ":last_name" => $_POST["last_name"],
                  ":email" => $_POST["email"],
                  ":headline" => $_POST["headline"],
                  ":summary" => $_POST["summary"],
                  ":profile_id" => $_GET["profile_id"]));
                  $_SESSION["success"] = "Profile Update";
                  header("Location: index.php");

         }
         else {
          $failure = "Email address must contain @";
         }
        }
         else{

          $failure = "All fields are required ";
        }

      }
      if(isset($_POST["cancel"])){
        $_SESSION["success"] = "";
        header("Location: index.php");
     }

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>02830995</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<div class="container">
<?php

echo ( '<h1> Editing Automobile '  );
echo ("</h1>");

  echo('<p style="color: red">'.htmlentities($failure)."</p>");


?>

<body>
    <form method="POST">

    <p>First Name:
    <input type="text" size ="60px" name="first_name" value="<?= $a ?>"></p>
    <p>Last name:
    <input type="text" size ="60px" name="last_name" value="<?= $b ?>"></p>
    <p>Email:
    <input type="text" name= "email" size ="30px" value="<?= $c ?>" ></p>
    <p>Headline:
    <input type="text" name="headline" size ="10px" value="<?= $d ?>"></p>
    <p>Summary: <br>
    <textarea name="summary" id="summ" cols="80" rows="10" ><?= $e ?></textarea>
    <br>
    <input type="submit" value="Save" name="edit">
    <input type="submit" value="Cancel" name="cancel">



</div>
    </form>
</body>

</html>

