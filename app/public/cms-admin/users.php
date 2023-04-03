<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You need to sign in to access.";
    header('Location: signin.php');	
    exit();
}

// use Temmplate
$user = new User();

$result = $user->selectAll();
if (!$result) {
    $_SESSION['message'] = "Something went wrong.";
    header('Location: dashboard.php');	
    exit();
}

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
<body class="w-screen flex">

    <?php include ROOT . '/cms-includes/partials/header.php'; ?>

    <main class="bg-blue-100 flex w-full m-6 flex-col space-y-10 p-6 rounded-2xl drop-shadow-sm p">
    <?php include ROOT . '/cms-includes/partials/session_msg.php'; ?>
    
    <div class="max-w-lg">
        <h1 class="text-3xl font-semibold text-blue-800"><?= $title ?></h1>
            <?php

            function print_ul_li($result)
            {
                echo "<ul>";
                foreach ($result as $user) {
                    $id = $user['user_id'];
                    echo "<li class='py-3 px-6 my-4 bg-blue-200 rounded-xl text-blue-800 drop-shadow-md flex items-center cursor-pointer hover:bg-blue-300 hover:text-blue-900'>", 
                            // change tgis to user id
                            "<a href='user.php?id=$id'>",
                                "<p class='text-md font-medium text-gray-900 truncate dark:text-white'>" . $user['firstname'] . " " .$user['lastname'] . "</p>",
                                "<p class='text-sm text-gray-500 truncate dark:text-gray-400'>Position: " . (($user['position'] == 1) ? 'admin' : 'user') . "</p>",
                            "</a>",
                        "</li>";
                }
                echo "</ul>";
            }
            print_ul_li($result);

            ?>
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