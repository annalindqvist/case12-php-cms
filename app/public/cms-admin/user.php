<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';
require_once "../Parsedown.php";

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You need to sign in to access.";
    header('Location: signin.php');	
    exit();
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

if (isset($_GET['change_to_user'])) {
    $user_id = intval($_GET['change_to_user']);
    $position = 0;
    $user_result = $user->updateOnePosition($user_id, $position);

    if ($user_result) {

        // if the online user makes themself user from admin change the $_SESSION['position']
        if ($_SESSION['user_id'] == $user_id) {
            $_SESSION['position'] = 'user';
        }
        $_SESSION['message'] = "User position updated to 'user'.";
        header('Location: users.php');	
        exit();

    } else {
        $_SESSION['message'] = "Something went wrong.";
        header('Location: users.php');	
        exit();
    }
} 
if (isset($_GET['change_to_admin'])) {
    $user_id = intval($_GET['change_to_admin']);
    $position = 1;
    $user_result = $user->updateOnePosition($user_id, $position);

    if ($user_result) {

        // if the online user makes themself user from admin change the $_SESSION['position']
        if ($_SESSION['user_id'] == $user_id) {
            $_SESSION['position'] = 'admin';
        }
        $_SESSION['message'] = "User position updated to 'admin'.";
        header('Location: users.php');	
        exit();

    } else {
        $_SESSION['message'] = "Something went wrong.";
        header('Location: users.php');	
        exit();
    }
} 
if (isset($_GET['remove_user'])) {
    $user_id = intval($_GET['remove_user']);
    $user_result = $user->deleteOne($user_id);

    if ($user_result) {
        // if the online user makes removes themselfe
        if ($_SESSION['user_id'] == $user_id) {
            $_SESSION['message'] = "Your account has been deleted.";
            header('Location: signin.php');	
            exit();
        } else {
        $_SESSION['message'] = "User is now deleted.";
        header('Location: users.php');	
        exit();
        }
    } else {
        $_SESSION['message'] = "Something went wrong.";
        header('Location: users.php');	
        exit();
    }
} 


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
    <?php
        if($_SESSION['position'] == 'admin'){
            echo "<a href='edit_user_info.php?id=$user_id'>Update user information</a>";
            if($user_position == 'admin') {
                echo "<a href='user.php?change_to_user=$user_id'>Change position to: user</a>";
            }
            if($user_position == 'user') {
                echo "<a href='user.php?change_to_admin=$user_id'>Change position to: admin</a>";
            }
            echo "<a href='user.php?remove_user=$user_id'>Remove user</a>";
        }
    ?>

</body>
</html>