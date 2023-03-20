<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}

// use Temmplate
$user = new User();




// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();


$title = "Users";

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/cms-content/styles/style.css">
</head>
<body>

    <?php include ROOT . '/cms-includes/partials/header.php'; ?>
    <h1><?= $title ?></h1>
    <?php

    $result = $user->selectAll();
    //print_r($result);

    function print_ul_li($result)
    {
        echo "<ul>";
        foreach ($result as $user) {
            echo "<li>", 
                    "<p> Firstname: ", $user['firstname'], "</p>",
                    "<p> Lastname: ", $user['lastname'], "</p>",
                    "<p> Email: ", $user['email'],  "</p>",
                 "</li>";
        }
        echo "</ul>";
    }
    print_ul_li($result);

    ?>


</body>
</html>