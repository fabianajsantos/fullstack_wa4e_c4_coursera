<?php

function flashMessages()
{

    if (isset($_SESSION['error'])) {

        if (isset($_SESSION["error"])) {
            echo('<p style="color:red">' . $_SESSION["error"] . "</p>\n");
            unset($_SESSION["error"]);
        }
        if (isset($_SESSION["success"])) {
            echo('<p style="color:green">' . $_SESSION["success"] . "</p>\n");
            unset($_SESSION["success"]);
        }

    }
}

function validateProfile()
{

    if (strlen($_POST['first_name']) == 0 || strlen($_POST['last_name']) == 0 || strlen($_POST['email']) == 0 || strlen($_POST['headline']) == 0 || strlen($_POST['summary']) == 0) {
        return "All fields are required";
    }

    if (strpos($_POST['email'], '@') === false) {
        return "Email must have an at_sign (@)";
    }
    return true;

}

function validatePos()
{
    for ($i = 1; $i <= 3; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;
        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];
        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }
        if (!is_numeric($year)) {
            return "Position must be numeric";
        }
    }
    return true;

}

function loadPos($pdo, $profile_id)
{
    $stmt = $pdo->prepare('select * from position where profile_id = :prof Order by rank');
    $stmt->execute(array(':prof' => $profile_id));
    $positions = $stmt->fetchALL(PDO::FETCH_ASSOC);
    return $positions;
}

function loadEdu($pdo, $profile_id)
{
      $stmt = $pdo->prepare('select year, name from education
                              JOIN institution ON education.institution_id = institution.institution_id
                              where profile_id = :prof Order by rank');
      $stmt->execute(array(':prof' => $profile_id));
      $educations = $stmt->fetchALL(PDO::FETCH_ASSOC);
      return $educations;
}

function insertPositions($pdo, $profile_id)
{
    $rank = 1;
    for ($i = 1; $i <= 3; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        $stmt = $pdo->prepare('INSERT INTO Position
        (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');

        $stmt->execute(array(
                ':pid' => $_REQUEST['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
        );
        $rank++;
    }
}




