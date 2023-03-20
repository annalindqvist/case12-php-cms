<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';

// use Temmplate
$user_template = new User();

// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();

$title = "Sign up";

if ($_POST) {

    // validate email 
    if (empty($_POST['email'])) {
        exit("Please fill in email.");
    } else {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Email format is not correct. Please try again.";
            exit($emailErr);
        }
    }

    // validate password
    if (empty($_POST['password'])) {
        exit("Please fill in password.");
    } else {
        $password = trim($_POST['password']);
        if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            $passErr = "Password format is not correct. Please try again.";
            exit($passErr);
        }else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    // validate firstname
    if (empty($_POST['firstname'])) {
        exit("Please fill in your firstname.");
    } else {
        $firstname = trim($_POST['firstname']);
        if (strlen($_POST['firstname']) > 30 || strlen($_POST['firstname']) < 1) {
            $firstnameErr = "firstname format is not correct. Please try again.";
            exit($firstnameErr);
        }
    }

    // validate lastname
    if (empty($_POST['lastname'])) {
        exit("Please fill in your lastname.");
    } else {
        $lastname = trim($_POST['lastname']);
        if (strlen($_POST['lastname']) > 30 || strlen($_POST['lastname']) < 1) {
            $lastnameErr = "Lastname format is not correct. Please try again.";
            exit($lastnameErr);
        }
    }

    // check if there is already a user with this email
    $result = $user_template->findOneEmail($email);

    // if there is
    if($result) {
        echo "User already exists";

    } else {
        // returns boolean
        $register_user = $user_template->insertOne($email, $hash_pass, $firstname, $lastname);
        if($register_user) {
            // add message that all went ok
            header('Location: signin.php');	
        } else {
            echo "Something went wrong, try again later.";
        }
            
} 
}
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
    
    <h1><?= $title ?></h1>
    
    <form action="" method="post">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" min="5" max="20" required>

        <label for="firstname">Firstname</label>
        <input type="text" name="firstname" id="firstname" min="1" max="30"required>

        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" min="1" max="30" required>

        <input type="submit" value="Sign up">
    </form>

</body>
</html>