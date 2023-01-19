<!--c1-->


<!--c2-->
<?php

session_start();

require_once "pdo.php";
require_once "bootstrap.php";

if (isset($_POST['cancel'])) {
    // Redirect the browser to add.php
    header("Location: index.php");
    return;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

if (isset($_POST["email"]) && isset($_POST["pass"])) {
    unset($_SESSION["email"]);  // Logout current user

    if (empty($_POST['email']) || empty($_POST['pass'])) {

        $_SESSION["error"] = "Email and password are required.";
        error_log("Email and password are required!", 0);
        header('Location: login.php');
        return;

    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        //   $_SESSION["error"] = "Email must have an at_sign (@)";
        error_log("Email must have an at_sign (@)", 0);
        header('Location: login.php');
        return;

    } else {

        $check = hash('md5', $salt . $_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
        WHERE email = :em AND pass = :pw');
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;

        } else {
            $_SESSION["error"] = "Incorrect password";
            header('Location: login.php');
            return;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title> Fabiana Santos - b16238d0</title>

    <?php require_once "bootstrap.php"; ?>
    <!--    calling external js-->
    <script type="text/javascript" src="valida.js"></script>

</head>
<body>
<div class="container">
    <h1>Please Log In</h1><br>

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

    <form method="POST">
        <div><label for="nam">Email</label>
            <input type="text" name="email" id="nam">
        </div>
        <br/>
        <div><label for="id_1723">Password</label>
            <input type="text" name="pass" id="id_1723">
        </div>
        <br/>
        <input type="submit" value="Log In" onclick="return doValidate();">
        <input type="submit" name="cancel" value="Cancel">
    </form>
</div>
</body>

<!--c3-->
<?php // Do not put any HTML above this line

session_start();

if ( isset($_POST['cancel'] ) )
{
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$failure = false;  // If we have no POST data

if ( isset($_SESSION['failure']) ) {
    $failure = htmlentities($_SESSION['failure']);

    unset($_SESSION['failure']);
}

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) )
{
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 )
    {
        $_SESSION['failure'] = "User name and password are required";
        header("Location: login.php");
        return;
    }

    $pass = htmlentities($_POST['pass']);
    $email = htmlentities($_POST['email']);

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

    $stmt = $pdo->prepare("
        SELECT * FROM users
        WHERE email = :email AND password = :password
    ");

    $stmt->execute([
        ':email' => $email,
        ':password' => hash('md5', $salt.$pass),
    ]);

    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($row !== false)
    {
        error_log("Login success ".$email);
        $_SESSION['name'] = $row->name;
        $_SESSION['user_id'] = $row->user_id;

        header("Location: index.php");
        return;
    }

    error_log("Login fail ".$pass." $check");
    $_SESSION['failure'] = "Incorrect password";

    header("Location: login.php");
    return;

}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
    <title>Abishek Gyawali Login Page</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    // Note triple not equals and think how badly double
    // not equals would work here...
    if ( $failure !== false )
    {
        // Look closely at the use of single and double quotes
        echo(
            '<p style="color: red;" class="col-sm-10 col-sm-offset-2">'.
            htmlentities($failure).
            "</p>\n"
        );
    }
    ?>
    <form method="post" class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-3">
                <input class="form-control" type="text" name="email" id="email">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pass">Password:</label>
            <div class="col-sm-3">
                <input class="form-control" type="text" name="pass" id="id_1723">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-2">
                <input class="btn btn-primary" onclick="return doValidate();" type="submit" value="Log In">
                <input class="btn" type="submit" name="cancel" value="Cancel">
            </div>
        </div>
    </form>
    <p>
        For a password hint, view source and find a password hint in the HTML comments.
        <!-- Hint: The password is the four character sound a cat
        makes (all lower case) followed by 123. -->
    </p>
</div>

<script>
    function doValidate() {
        console.log('Validating...');
        try {
            addr = document.getElementById('email').value;
            pw = document.getElementById('id_1723').value;
            console.log("Validating addr="+addr+" pw="+pw);
            if (addr == null || addr == "" || pw == null || pw == "") {
                alert("Both fields must be filled out");
                return false;
            }
            if ( addr.indexOf('@') == -1 ) {
                alert("Invalid email address");
                return false;
            }
            return true;
        } catch(e) {
            return false;
        }
        return false;
    }
</script>

</body>
</html>

<!--c4-->
<?php // Do not put any HTML above this line
require_once "pdo.php";
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
    }
    else {
        $check = hash('md5', $salt.$_POST['pass']);
        $stmt = $pdo->prepare('SELECT user_id, name FROM users
        WHERE email = :em AND password = :pw');
        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $row !== false ) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            // Redirect the browser to index.php
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
        }
    }
    return;
}

// Fall through into the View
?>

<script>
    function doValidate() {
        console.log('Validating...');
        try {
            pw = document.getElementById('id_1723').value;
            email = document.getElementById('id_1722').value;
            console.log("Validating pw="+pw);
            console.log("Validating email="+email);
            if (pw == null || pw == "" || email == null || email == "") {
                alert("Both fields must be filled out");
                return false;
            }
            else if(email.indexOf('@')===-1){
                alert("Invalid email address");
                return false;
            }
            return true;
        } catch(e) {
            return false;
        }
        return false;
    }</script>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Tianer Zhou - Resume Registry</title>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <?php
    // Note triple not equals and think how badly double
    // not equals would work here...
    if ( isset($_SESSION['error']) ) {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="POST">
        Email <input type="text" name="email" id="id_1722"><br/>
        Password <input type="text" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <p>
        For a password hint, view source and find a password hint
        in the HTML comments.
        <!-- Hint: The password is the four character sound a cat
        makes (all lower case) followed by 123. -->
    </p>
</div>
</body>