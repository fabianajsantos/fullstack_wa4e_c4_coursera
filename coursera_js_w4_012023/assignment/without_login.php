    <h1>Chuck Severance's Resume Registry</h1>
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
    <p>
        <a href="login.php">Please log in</a>
    </p>
    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th>Name</th>
            <th>Headline</th>
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
            }
            ?>
        </tbody>
    </table>

