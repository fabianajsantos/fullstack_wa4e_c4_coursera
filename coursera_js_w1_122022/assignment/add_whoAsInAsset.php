    <?php
session_start();

if ( ! isset($_SESSION['who']) ) {
    die('Not logged in');

require_once "pdo.php";

        if ((isset($_POST['year']) && isset($_POST['mileage'])) && isset($_POST['make'])) {
        if ((!is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))) {
            //$failure = 'Mileage and year must be numeric';
            $_SESSION["error"] = 'Mileage and year must be numeric';
        } else if(empty($_POST['make']))
        {
            //$failure = 'Make is required';
            $_SESSION["error"] = 'Make is required';
            //header('Location: login.php');
        }
        else
        {
            $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :mk, :yr, :mi)');
            $row = $stmt->execute(
                array(
                    ':mk' => htmlentities($_POST['make']),
                    ':yr' => htmlentities($_POST['year']),
                    ':mi' => htmlentities($_POST['mileage'])
                )
            );
            //$failure = 'Record inserted';
            $_SESSION["success"] = 'Record inserted';
            header('Location: view.php');
            return;

        }
    }
    }

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

    <form class="form-login" method="post">
        <div class="login-card card">
            <div class="card-header">
                <span class="font-weight-bold">Tracking Autos
                    <?php
                    if (isset($_REQUEST['name'])) {
                        echo "<p>Welcome: ";
                        echo htmlentities($_REQUEST['name']);
                        echo "</p>\n";
                    }
                    if (isset($_SESSION["error"])) {
                        echo('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
                        unset($_SESSION["error"]);
                    }
                    if (isset($_SESSION["success"])) {
                        echo('<p style="color:green">' . $_SESSION["su"] . "</p>\n");
                        unset($_SESSION["success"]);
                    }
                  /*  if ($failure !== false) {
                    // Look closely at the use of single and double quotes
                        echo ('<p style="color: red;">' . htmlentities($failure) . "</p>\n");
                    }
                    */?>

                </span>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="make">Make</label>
                    <input type="text" size="40" name="make">
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="text" size="40" name="year">
                </div>
                <div class="form-group">
                    <label for="mileage">Mileage</label>
                    <input type="text" size="37" name="mileage">
                </div>
                <div>

                </div>

                <input type="submit" value="Add" name="Add"></input>
                <!-- <input type="submit" value="Add" /> -->
                <input type="submit" name="logout" value="Logout">
                <!--// If the user requested logout go back to index.php-->
                <a href=" <?php echo ($_SERVER['PHP_SELF']); ?>">Refresh</a>