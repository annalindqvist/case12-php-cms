<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';

// if already logged in - go to dashboard
if(isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You aldready have an account and are online, sign out to create a new account.";
    header('Location: dashboard.php');	
    exit();
}

$user = new User();
$setupTable = $user->setup();

$email = null;
$firstname = null;
$lastname = null;
$password = null;

$title = "Sign up";

if ($_POST) {

    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $err = array();

    // validate email 
    if (empty(trim($_POST['email']))) {
        $err[] = "Please fill in your email";
    } 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err[] = "Please double check your email";
    }
    
    // validate password
    if (empty(trim($_POST['password']))) {
        $err[] = "Please fill in a password";
    } 
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        $err[] = "Password format is not correct. Please try again.";
    }
    
    // validate firstname
    if (empty(trim($_POST['firstname']))) {
        $err[] = "Please fill in your firstname.";
    }
    if (strlen(trim($_POST['firstname'])) > 30 || strlen(trim($_POST['firstname'])) < 1) {
        $err[] = "Firstname format is not correct. Please try again.";
    }

    // validate lastname
    if (empty(trim($_POST['lastname']))) {
        $err[] = "Please fill in your lastname.";
    } 
    if (strlen(trim($_POST['lastname'])) > 30 || strlen(trim($_POST['lastname'])) < 1) {
        $err[] = "Lastname format is not correct. Please try again.";
    }
    
    // if errors in form - print them out - else go on
    if (empty($err)) {
        // check if there is already a user with this email
        $result = $user->findOneEmail($email);
        // if there is a match
        if($result) {
            echo "User already exists";
        } else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            // returns boolean
            $register_user = $user->insertOne($email, $hash_pass, $firstname, $lastname);
            if($register_user) {
                // add message that all went ok
                header('Location: signin.php');	
            } else {
                echo "Something went wrong, try again later.";
            }
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
    <?php 
    // Session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo "<div><p>". $_SESSION['message'] . "</p></div>";
        unset( $_SESSION['message']);
    }
    ?>
    
    <h1><?= $title ?></h1>

    <form action="" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $email ?>" required>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" min="5" max="20" value="<?php echo $password ?>" required>
        <label for="firstname">Firstname</label>
        <input type="text" name="firstname" id="firstname"  min="1" max="30" value="<?php echo $firstname ?>"required >
        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" value="<?php echo $lastname ?>" min="1" max="30" required>
        <input type="submit" value="Sign up">
    </form>

    <?php
        if(!empty($err)) {
            echo "<ul>";
            foreach($err as $error) {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul>";
        }
        
    ?>

</body>
</html>