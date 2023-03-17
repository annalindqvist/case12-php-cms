<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Template.php';

// use Temmplate
$template = new Template();

// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();

$title = "Login";

if ($_POST) {
    // print_r($_POST);
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == "" || $password == "") {
        echo "Password or email field is empty.";

    } else {
        $result = $template->findOne($email, $password);
        echo "Result from db: " . print_r($result);

        if(is_array($result) && !empty($result)) {
            foreach ($result as $row) {
                $validuser = $row['email'];
                $_SESSION['auth'] = true;
                $_SESSIOM['validuser'] = $validuser;
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
            }
            echo print_r($_SESSION);
		} else {
			echo "Invalid username or password.";
		}
        if(isset($_SESSION['auth'])) {
			header('Location: index.php');	
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
    <a href="signup.php">Sign up</a>

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
        <input type="submit" value="Sign in">
    </form>

</body>
</html>