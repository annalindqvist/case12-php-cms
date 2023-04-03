<?php
declare(strict_types=1);
session_start();
include_once  '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You need to sign in to access.";
    header('Location: signin.php');	
    exit();
} 

// use Temmplate - not needed? 
$page = new Page();

if(isset($_SESSION['firstname'])) {
    $firstname = $_SESSION['firstname'];
} else {
    // else because all users don't have firstname at this moment
    $firstname = "";
}

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

<body class="w-screen flex">


    <?php include ROOT . '/cms-includes/partials/header.php'; ?>

    <main class="bg-blue-100 flex w-full m-6 flex-col items-center space-y-10 py-6 rounded-2xl drop-shadow-sm">
    <?php include ROOT . '/cms-includes/partials/session_msg.php'; ?>
        <div class="bg-blue-200 rounded-xl px-20 py-10 flex items-center justify-evenly">
            <div>
                <h1 class="font-mono text-3xl mb-2 font-bold text-blue-700">Welcome <?= $firstname ?>,</h1>
                <p class="text-xl">Have a great day!</p>
            </div>
        </div>

    </main>

    <script>
        document.getElementById('session-msg-btn').addEventListener("click", () => {
            console.log("btnclick")
            document.getElementById('session-div').style.display = "none";
        });
    </script>
</body>

</html>