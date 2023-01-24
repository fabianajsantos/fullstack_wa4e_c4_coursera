<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<title>Fabiana Santos - 984eef88</title>
<body>
</head>
<div class='container'>
    <h1>Chuck Severance's Resume Registry </h1>


    <?php
    require_once "pdo.php";
    require_once "head.php";

    session_start();
    $stm = $pdo->query("SELECT first_name,last_name,email,headline, summary ,user_id FROM profile");
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if ( ! isset($_SESSION['name']) ) {
/*        for($i=0;$i < 100;$i++){
        }*/
        echo("  
    <p>
    <a href='login.php'>Please log in</a>
    </p> <p>");

        if($row === false){

            echo("");
        }
        else {
            print_r("<table border='1'><tr><th>Name </th><th> Headline</th>");
            echo('</p>');

            $stm = $pdo->query("SELECT first_name,headline,profile_id FROM profile");
            while ($row = $stm->fetch(PDO::FETCH_ASSOC)){


                echo("<tr><td>");
                echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$row['first_name']);
                echo('</a>');
                echo("</td><td>");
                echo(htmlentities($row["headline"]));
                echo("</td></tr>");
            }
        }
    }
    else {

        if($row == false){

            echo("");
        }
        else {
            print_r("<table border='1'><tr><th>Name </th><th> Headline</th><th> Action </th></tr>");
            echo('<p style="color: green">');
            if(isset($_SESSION["success"])){
                echo($_SESSION["success"]);

            }
            echo('</p>');

            $stm = $pdo->query("SELECT first_name,headline,profile_id FROM profile");
            while ($row = $stm->fetch(PDO::FETCH_ASSOC)){


                echo("<tr><td>");
                echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$row['first_name']);
                echo('</a>');
                echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$row['last_name']);
                echo('</a>');
                echo("</td><td>");
                echo(htmlentities($row["headline"]));
                echo("</td><td>");
                echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
                echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>  ');
                echo("</td></tr>");
            }
        }
        echo (" </table><p><a href='add.php'>Add New Entry</a></p>
        <p><a href='logout.php'>Logout</a></p>");
    }
    ?>
</div>
</body>

