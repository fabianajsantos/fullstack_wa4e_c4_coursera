<!--c1-->
<?php
session_start();
require_once "pdo.php";
if(isset($_POST["submit"])){
$salt = "XyZzy12*_";
$email = $_POST['email'];
$userPss = md5($salt . $_POST['pass']);
//$sql = "select user_id from users where email = :em and password  :pss ";
$stmt = $conn->prepare("select *  from users where email = :em and password = :pss ");
$stmt->execute([":em" => $email , ":pss" => $userPss]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
var_dump($row);
//$storedPss = md5($salt . "php123");
if(strlen($email) > 0 && strlen($userPss) > 0){
$_SESSION["errors"] = [];
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
$_SESSION["errors"][]='must be an email';
}
if(!$row){
$_SESSION["errors"][]='Incorrect password';
}
if(count( $_SESSION["errors"])==0 && $row ){
$_SESSION['email']= $email;
$_SESSION['user_id']= $row['user_id'];
$_SESSION['name'] = $row['name'];
return header("Location: index.php");
}else{
return header("Location: login.php");
}
}else{
$_SESSION['message'] = 'User name and password are required' ;
return header("Location: login.php");
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>85a7680f</title>
</head>
<body>
<h1>Please Log In</h1>
<p style="color: red;">
    <?php
    if(isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    if(isset( $_SESSION["errors"])){
        foreach( $_SESSION["errors"] as $value){
            echo $value . '<br>';
        }
        unset( $_SESSION["errors"] );

    }
    ?>
</p>
<form action="" method="post">
    email <input type="text" name="email"><br>
    password <input type="password" name="pass" id="id_1723"><br>
    <input type="submit" value="Log In" name="submit" onclick="return doValidate();">
    <a href="index.php">Cancel</a>
</form>
<p>For a password hint, view source and find a password hint in the HTML comments.</p>
<script src="validate.js">

</script>
</body>
</html>
<!--c2-->
<?php

require_once "pdo.php";
require_once "bootstrap.php";

session_start();

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}
if ((isset($_POST['first_name']) && isset($_POST['last_name'])) && isset($_POST['headline']) && isset($_POST['profile_id'])) {
// Data validation
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['headline']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        //header('Location: add.php');

        return;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $_SESSION["error"] = "Email must have an at_sign (@)";
        error_log("Email must have an at_sign (@)", 0);
        header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
        //header('Location: login.php');
        return;
    }
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
    $_SESSION['success'] = 'Record updated';
    header('Location: index.php');
    return;
}
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
$profile_id = $row['profile_id'];
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
        <div class="card-header bg-transparent">
                                    <span class="font-weight-bold">Editing profile for
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
                <p>First Name:
                    <input type="text" name="first_name" value="<?= $fn ?>"></p>
                <p>Last Name:
                    <input type="text" name="last_name" value="<?= $ln ?>"></p>
                <p>Email:
                    <input type="text" name="email" value="<?= $em ?>"></p>
                <p>Headline:
                    <input type="text" name="headline" value="<?= $he ?>"></p>
                <p>Summary:
                    <textarea name="summary" class="form-control form-control-sm"></textarea>
                    <input type="hidden" name="profile_id" value="<?= $profile_id ?>">

                    <input class="btn btn-primary btn-sm" type="submit" value="Save">
                    <a href="index.php" class="btn btn-primary btn-sm" role="button">Cancel</a>
            </div>
        </div>
</div>
</form>
</body>

<!--c3-->
<?php

session_start();

if ( ! isset($_SESSION['name']) ) {
    die("Not logged in");
}

// If the user requested logout go back to index.php
if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}

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
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
    die();
}

$name = htmlentities($_SESSION['name']);

$_SESSION['color'] = 'red';

if (isset($_REQUEST['profile_id']))
{

    $profile_id = htmlentities($_REQUEST['profile_id']);

    // Check to see if we have some POST data, if we do process it
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
    {
        if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1)
        {
            $_SESSION['status'] = "All fields are required";
            header("Location: edit.php?profile_id=" . htmlentities($_REQUEST['profile_id']));
            return;
        }

        if (strpos($_POST['email'], '@') === false)
        {
            $_SESSION['status'] = "Email address must contain @";
            header("Location: edit.php?profile_id=" . htmlentities($_REQUEST['profile_id']));
            return;
        }

        $first_name = htmlentities($_POST['first_name']);
        $last_name = htmlentities($_POST['last_name']);
        $email = htmlentities($_POST['email']);
        $headline = htmlentities($_POST['headline']);
        $summary = htmlentities($_POST['summary']);

        $stmt = $pdo->prepare("
            UPDATE profile
            SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id
        ");

        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':headline' => $headline,
            ':summary' => $summary,
            ':profile_id' => $profile_id,
        ]);

        $_SESSION['status'] = 'Record edited';
        $_SESSION['color'] = 'green';

        header('Location: index.php');
        return;
    }

    $stmt = $pdo->prepare("
        SELECT * FROM profile
        WHERE profile_id = :profile_id
    ");

    $stmt->execute([
        ':profile_id' => $profile_id,
    ]);

    $profile = $stmt->fetch(PDO::FETCH_OBJ);

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Abishek Gyawali Autos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Editing Profile for <?php echo $name; ?></h1>
    <?php
    if ( $status !== false )
    {
        // Look closely at the use of single and double quotes
        echo(
            '<p style="color: ' .$status_color. ';" class="col-sm-10 col-sm-offset-2">'.
            htmlentities($status).
            "</p>\n"
        );
    }
    ?>
    <form method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-2" for="first_name">First Name:</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="first_name" id="first_name" value="<?php echo $profile->first_name; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="last_name">Last Name:</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="last_name" id="last_name" value="<?php echo $profile->last_name; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="email" id="email" value="<?php echo $profile->email; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="headline">Headline:</label>
            <div class="col-sm-5">
                <input class="form-control" type="text" name="headline" id="headline" value="<?php echo $profile->headline; ?>">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="summary">Summary:</label>
            <div class="col-sm-5">
                <textarea class="form-control" name="summary" id="summary" rows="8"><?php echo $profile->summary; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-2">
                <input class="btn btn-primary" type="submit" value="Save">
                <input class="btn" type="submit" name="cancel" value="Cancel">
            </div>
        </div>
    </form>

</div>
</body>
</html>
<!--c4-->
<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['user_id']) ) {
    die("ACCESS DENIED");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1  ||
        strlen($_POST['email']) < 1  || strlen($_POST['headline']) < 1
        || strlen($_POST['summary']) < 1){
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
        return;
    }
    else if(strpos($_POST['email'], '@') === false){
        $_SESSION['error'] = "Email address must contain @";
        header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
        return;
    }
    else{
        $stmt = $pdo->prepare('UPDATE profile SET first_name = :firstn,
    last_name = :lastn, email = :email, headline = :hl, summary = :s
            WHERE profile_id = :id');
        $stmt->execute(array(
            ':firstn' => $_POST['first_name'],
            ':lastn' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':hl' => $_POST['headline'],
            ':s' => $_POST['summary'],
            ':id' => $_GET['profile_id']
        ));
        $_SESSION['success'] = "Profile edited";
        header('Location: index.php');
        return;
    }
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
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tianer Zhou - Resume Registry</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<p>Edit Profile</p>
<form method="post">
    <p>First name:
        <input type="text" name="first_name" value="<?= $first_name ?>"></p>
    <p>Last name:
        <input type="text" name="last_name" value="<?= $last_name ?>"></p>
    <p>Email:
        <input type="text" name="email" value="<?= $email ?>"></p>
    <p>Headline:
        <input type="text" name="headline" value="<?= $headline ?>"></p>
    <p>Summary:
        <input type="text" name="summary" value="<?= $summary ?>"></p>

    <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
    <p><input type="submit" value="Save"/>
        <a href="index.php">Cancel</a></p>
</form>