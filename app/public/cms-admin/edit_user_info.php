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

$user = new User();

$user_firstname = null;
$user_lastname = null;
$user_email = null;
$user_position = null;
$user_id = null;

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


if ($_POST) {

    print_r($_POST);
    $user_email = $_POST['email'];
    $user_firstname = $_POST['firstname'];
    $user_lastname = $_POST['lastname'];
    $user_id = $_POST['id'];

    // $password = $_POST['password'];
    $err = array();
    
    // validate email 
    if (empty(trim($_POST['email']))) {
        $err[] = "Please fill in an email";
    } 
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $err[] = "Please double check the email";
    }
    
    // validate firstname
    if (empty(trim($_POST['firstname']))) {
        $err[] = "Please fill in a firstname.";
    }
    if (strlen(trim($_POST['firstname'])) > 30 || strlen(trim($_POST['firstname'])) < 1) {
        $err[] = "Firstname format is not correct. Please try again.";
    }

    // validate lastname
    if (empty(trim($_POST['lastname']))) {
        $err[] = "Please fill in a lastname.";
    } 
    if (strlen(trim($_POST['lastname'])) > 30 || strlen(trim($_POST['lastname'])) < 1) {
        $err[] = "Lastname format is not correct. Please try again.";
    }

    // if errors in form - print them out - else go on
    if (empty($err)) {
        // check if there is already a user with this email
        $result = $user->findOneEmail($user_email);
        // if there is a match
        if($result) {
            $err[] = "Please double check the email";
        } else {
            
            // returns boolean
            $update_user = $user->updateOne($user_id, $user_email, $user_firstname, $user_lastname);
            if($update_user) {
                // add message that all went ok
                $_SESSION['message'] = "Successfully updated information on user.";
                header('Location: users.php');	
                exit();
            } else {
                $_SESSION['message'] = "Something went wrong, try again later.";
                header('Location: users.php');	
                exit();
            }
        }  
    } else {
        echo "ELSE";
    }


}


$title = "Update user information";

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

    <form action="" method="post">
        <input type="number" name="id" id="id" value="<?php echo $user_id?>" hidden>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $user_email ?>" required>
        <label for="firstname">Firstname</label>
        <input type="text" name="firstname" id="firstname"  min="1" max="30" value="<?php echo $user_firstname ?>"required >
        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $user_lastname ?>" min="1" max="30" required>
        <input type="submit" value="Update user information">
    </form>


</body>
</html>