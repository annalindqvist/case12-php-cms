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
    //print_r($_POST);
    
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    

    if ($email == "" || $pass == "" || $firstname = "" || $lastname = "") {
        echo "Some field is empty.";

    } else {
        // is there already a user with this email?
        $result = $user_template->findOneEmail($email);
        // if there is echo it out
        if($result) {

            echo "User already exists";

        } else {

            $register_user = $user_template->insertOne($email, $pass, $firstname, $lastname);
            echo "after register" . print_r($register_user);
            // if(is_array($register_user) && !empty($register_user)) {
            //         $validuser = $register_user['email'];
            //         $_SESSION['auth'] = true;
            //         $_SESSIOM['validuser'] = $validuser;
            //         $_SESSION['firstname'] = $register_user['firstname'];
            //         $_SESSION['lastname'] = $register_user['lastname'];
            //         echo print_r($_SESSION);
            // } else {
            //     echo "Invalid username or password.";
            // }
            // if(isset($_SESSION['auth'])) {
            //     header('Location: index.php');	
            // }
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

    <?php
    new DisplayDBVersion();
    // $result = $template->selectAll();
    // print_r($result);
    ?>

    <form action="" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="firstname"></label>
        <input type="text" name="firstname" id="firstname">
        <label for="lastname"></label>
        <input type="text" name="lastname" id="lastname">
        <input type="submit" value="Sign up">
    </form>

</body>
</html>