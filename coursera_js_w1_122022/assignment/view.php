<?php
session_start();

require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}
// If the user requested logout go back to index.php
if (isset($_POST['logout'])) {
    header('Location: logout.php');
    return;
}
?>
<!--//start to view-->
<!DOCTYPE html>
<html>
<head>
    <title> Fabiana Santos - b16238d0</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">

    <div class="card-header bg-transparent border-success">

        <!--<div class="container">-->
        <h1> <?php echo $_SESSION['name']; ?>'s Resume Registry</h1>
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
    </div>

    <div class="card-body text-success">
        <table class="table table-striped">
            <thead class="thead-light">
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <th colspan="2">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM profile");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                echo '<tr>';
                echo '<td>' . (htmlentities($row['last_name'])) . "&nbsp" . (htmlentities($row['first_name'])) . '</td>';
                echo '<td>' . (htmlentities($row['headline'])) . '</td>';
                // header("Location: edit.php?profile_id=" . $_POST["profile_id"]);
                echo '<td>' . '<a href="edit.php?profile_id=' . $row['profile_id'] . '">Edit</a>  ' . '</td>';
                echo '<td>' . '<a href="delete.php?profile_id=' . $row['profile_id'] . '">Delete</a>' . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>

           <a href="add.php" class="btn btn-primary" role="button">Add New Entry</a>
           <a href="logout.php" class="btn btn-primary" role="button">Logout</a>

               <style>
                table tr:last-child {
                    font-weight: bold;
                }

                p.confirm {
                    color: green;
                    text-align: center
                }
            </style>

  <!--      <div class="card-footer bg-transparent border-success">Footer</div>-->
    </div>
</body>
