<?php
session_start();
require_once "pdo.php";
require_once "tools.php";

if (!isset($_SESSION['name'])) {
    die('ACCESS DENIED');
}

if (isset($_POST['cancel'])) {
    // Redirect the browser to view.php
    header("Location: index.php");
    return;
}



if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header('Location: index.php');
    return;
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing auto_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name,  profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header('Location: index.php');
    return;
}

?>

<h1>Deleting Profile </h1>
<form method="post" action="delete.php">
    <p>First name: <?= htmlentities($row['first_name']) ?></p>
    <p>Last name: <?= htmlentities($row['last_name']) ?></p>
    <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
    <input type="submit" value="Delete" name="delete">
    <input type="submit" value="Cancel" name="cancel">
    <!--<a href="index.php">Cancel</a>-->
</form>