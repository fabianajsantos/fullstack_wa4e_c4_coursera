<!--c1-->
<?php
session_start();
require_once "pdo.php";
$stmt = $conn->query("select first_name, last_name , headline , profile_id from profile");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>907754d4</title>
</head>

<body>
<h1>hamza Resume Registry</h1>
<!--  show login and logout   -->
<?php
//start ---- flash message
if (isset($_SESSION["insert"])) {
    echo $_SESSION["insert"] . "<br>";
    unset($_SESSION["insert"]);
}
if (isset($_SESSION["update"])) {
    echo $_SESSION["update"] . "<br>";
    unset($_SESSION["update"]);
}
if (isset($_SESSION["delete"])) {
    echo $_SESSION["delete"] . "<br>";
    unset($_SESSION["delete"]);
}
if (isset($_SESSION["success"])) {
    echo $_SESSION["success"] . "<br>";
    unset($_SESSION["success"]);
}
// end ----flash message

if (!isset($_SESSION["user_id"])) {
    ?>
    <a href="login.php">Please log in</a>
    <?php
} else {
    ?>
    <p>
        <a href='logout.php'>log out</a>
    </p>
    <p>
        <a href='add.php'>Add New Entry</a>
    </p>


    <?php
}
?>

<!-- end code show login and logout   -->
<table border="1px">
    <tr>
        <th>name</th>
        <th>headline</th>
        <th>action</th>
    </tr>
    <?php

    while ($rows = $stmt->fetch()) {

        ?>
        <tr>
            <td><?= $rows['first_name'] . " " . $rows['last_name']; ?></td>
            <td><?= $rows['headline']; ?></td>
            <td>
                <a href="edit.php?profile_id=<?= $rows['profile_id']; ?>">edit</a>
                <a href="delete.php?profile_id=<?= $rows['profile_id']; ?>">delete</a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<p> <b>Note: </b> Your implementation should retain data across multiple logout/login sessions. This sample implementation clears all its data periodically - which you should not do in your implementation.</p>
</body>

</html>
<!--c2-->
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title> Fabiana Santos - b16238d0</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <?php

    if (!isset($_SESSION['user_id'])) {
        include_once('without_login.php');
    } else {
        include_once('view.php');
    }
    ?>
</div>
</body>

<!--c3-->
<?php

session_start();

$logged_in = false;
$profiles = [];

if (isset($_SESSION['name']) ) {

    $logged_in = true;
    $status = false;

    if ( isset($_SESSION['status']) ) {
        $status = htmlentities($_SESSION['status']);
        $status_color = htmlentities($_SESSION['color']);

        unset($_SESSION['status']);
        unset($_SESSION['color']);
    }

    try
    {
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=javascript',
            'fred', 'zap');
        // See the "errors" folder for details...
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $all_profiles = $pdo->query("SELECT * FROM profile");

        while ( $row = $all_profiles->fetch(PDO::FETCH_OBJ) )
        {
            $profiles[] = $row;
        }
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Abishek Gyawali Resume Resistry</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class = "container">
    <h1> Abishek Gyawali Resume Registry </h1>

    <?php if (!$logged_in) : ?>
        <p>
            <a href = "login.php"> Please log in </a>
        </p>
        <p>
            Attempt
            <a href="add.php"> add data </a>
            without logging in.
        </p>
    <?php else : ?>

        <?php
        if ( $status !== false )
        {
            // Look closely at the use of single and double quotes
            echo(
                '<p style="color: ' .$status_color. ';" class="col-sm-10">'.
                $status.
                "</p>\n"
            );
        }
        ?>

        <?php if (empty($profiles)) : ?>
            <p> No rows found </p>
        <?php else : ?>
            <div class = "row">
                <div class = "col-md-8">
                    <table class = "table">
                        <thead>
                        <tr>
                            <th> Name </th>
                            <th> Headline </th>
                            <th> Action </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($profiles as $profile) : ?>
                            <tr>
                                <td>
                                    <a href="view.php?profile_id=<?php echo $profile -> profile_id; ?>">
                                        <?php echo $profile -> first_name . ' ' . $profile -> last_name; ?>
                                    </a>
                                </td>
                                <td> <?php echo $profile -> headline ?> </td>
                                <td>
                                    <a href="edit.php?profile_id=<?php echo $profile -> profile_id; ?>">
                                        Edit
                                    </a>
                                    <a href="delete.php?profile_id=<?php echo $profile->profile_id; ?>">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
        <p>
            <a href="add.php">Add New Entry </a>
        </p>
        <p>
            <a href="logout.php">Logout</a>
        </p>
    <?php endif; ?>
</div>
</body>
</html>
<!--c4-->
!DOCTYPE html>
<html>
<head>
    <title>Tianer Zhou - Resume Registry</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Tianer Zhou's Resume Registry</h1>
    <?php
    session_start();
    require_once "pdo.php";
    if(!isset($_SESSION['name']))
        echo('<p><a href="login.php">Please log in</a></p>');
    else{
        if ( isset($_SESSION['error']) ) {
            echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
            unset($_SESSION['error']);
        }
        if ( isset($_SESSION['success']) ) {
            echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
            unset($_SESSION['success']);
        }
        $nRows = $pdo->query('SELECT count(*) from profile')->fetchColumn();
        if($nRows > 0){
            $stmt = $pdo->query("SELECT user_id, profile_id, first_name, last_name, headline FROM profile");
            echo('<table class = "table">'."\n");
            echo("<tr>
  <td>Name</td>
  <td>Headline</td>");
            if(isset($_SESSION['name'])){
                echo("<td>Actions</td>");
            }
            echo("</tr>");
            while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                echo "<tr><td>";
                echo('<a href="view.php?profile_id="'.$row['profile_id'].'>');
                echo(htmlentities($row['first_name'])." ".htmlentities($row['last_name']).'</a>');
                echo("</td><td>");
                echo(htmlentities($row['headline']));
                if($row['user_id'] == $_SESSION['user_id']){
                    echo('</td><td><a href = "edit.php?profile_id='.$row['profile_id'].'">Edit</a> /
      <a href = "delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
                }
                echo("</td></tr>\n");
            }
            echo("</table>");
        }
        if($_SESSION['user_id']){
            echo('<p><a href="add.php">Add New Entry</a></p>');
            echo('<p><a href="logout.php">Logout</a></p>');
        }
    }
    ?>
</div>
</body>