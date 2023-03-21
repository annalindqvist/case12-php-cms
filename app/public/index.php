<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}

// use Temmplate
$åage = new Page();

if(isset($_SESSION['firstname'])) {
    $firstname = $_SESSION['firstname'];
} else {
    // else because all users don't have firstname at this moment
    $firstname = "";
}


// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();


$title = "Dashboard";

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
    <h2><?= $firstname ?></h2>
    <h2><?php 
        echo $_SESSION['admin'];
    // if ($_SESSION['admin'] == 1){
    //     echo "true", $_SESSION['admin'];
    // } else {
    //     echo "false", $_SESSION['admin'];
    
     ?></h2>
    <a href="signout.php">Sign out</a>


</body>
</html>