<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';
require_once "../Parsedown.php";

if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}


// use Template
$user = new User();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    $user_result = $user->findOne($user_id);
   
    if (!empty($user_result) || is_array($user_result)) {
        foreach ($user_result as $row) {
                $user_firstname = $row['firstname'];
                $user_lastname = $row['lastname'];
                $user_email = $row['email'];
                $user_position = ($row['position'] == 1) ? "admin" : "user";;
                $user_id = $row['user_id'];
            }
    }
} 


// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();


$title = "User";

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
    <hr>
    <?php 
    // Session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo "<div><p>". $_SESSION['message'] . "</p></div>";
        unset( $_SESSION['message']);
    }
    ?>
    <div>
        <p><?= $user_firstname ?></p>
        <p><?= $user_lastname ?></p>
        <p><?= $user_email ?></p>
        <p><?= $user_position ?></p>
    </div>

</body>
</html>