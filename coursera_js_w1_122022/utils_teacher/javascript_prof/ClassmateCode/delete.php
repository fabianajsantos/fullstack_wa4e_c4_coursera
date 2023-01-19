<!--c1-->

<!--c2-->
<?php

require_once "pdo.php";
session_start();

if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header('Location: view.php');
    return;
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing auto_id";
    header('Location: view.php');
    return;
}

$stmt = $pdo->prepare("SELECT first_name, profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header('Location: view.php');
    return;
}

?>
<p>Confirm: Deleting <?= htmlentities($row['first_name']) ?></p>

<form method="post">
    <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
</form>

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

if (isset($_REQUEST['profile_id']))
{
    $profile_id = htmlentities($_REQUEST['profile_id']);

    if (isset($_POST['delete']))
    {
        $stmt = $pdo->prepare("
            DELETE FROM profile
            WHERE profile_id = :profile_id
        ");

        $stmt->execute([
            ':profile_id' => $profile_id,
        ]);

        $_SESSION['status'] = 'Record deleted';
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

    <style type="text/css">
        form {margin-top: 20px;}
    </style>
</head>
<body>
<div class="container">

    <h1>Deleteing Profile</h1>

    <div class="row">
        <div class="col-sm-2">
            First Name:
        </div>
        <div class="col-sm-3">
            <?php echo $profile->first_name; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            Last Name:
        </div>
        <div class="col-sm-3">
            <?php echo $profile->last_name; ?>
        </div>
    </div>

    <form method="post" class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-4">
                <input type="hidden" name="profile_id" value="<?php echo $profile->profile_id; ?>">
                <input class="btn btn-primary" type="submit" name="delete" value="Delete" onclick="return confirmDelete();">
                <input class="btn btn-default" type="submit" name="cancel" value="Cancel">
            </div>
        </div>
    </form>

    <script type="text/javascript">
        function confirmDelete()
        {
            var delProfile = confirm('Are you sure you want to delete this profile?');

            if (delProfile)
            {
                return true;
            }

            return false;
        }
    </script>

</div>
</body>
</html>

<!--c4-->
<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Profile deleted';
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
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Tianer Zhou - Resume Registry</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<p>Confirm: Deleting <?= htmlentities($row['first_name']." ".$row['last_name']) ?></p>

<form method="post">
    <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
    <input type="submit" value="Delete" name="delete">
    <a href="index.php">Cancel</a>
</form>
