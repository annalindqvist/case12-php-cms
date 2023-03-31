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


$title = "User profile";

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
    <?php 
    // Session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo "<div id='session-div' class='fixed top-10 inset-x-0 space-x-2'>
        <div
          class='pointer-events-auto mx-auto hidden w-96 max-w-full rounded-lg bg-white bg-clip-padding text-sm shadow-lg shadow-black/5 data-[te-toast-show]:block data-[te-toast-hide]:hidden dark:bg-neutral-600'
          id='static-example' role='alert' aria-live='assertive' aria-atomic='true' data-te-autohide='false' data-te-toast-init data-te-toast-show>
          <div class='flex items-center justify-between rounded-t-lg border-b-2 border-blue-500 border-opacity-100 bg-white bg-clip-padding px-4 pt-2.5 pb-2 dark:border-opacity-50 dark:bg-neutral-600'>
            <p class='font-bold text-neutral-500 dark:text-neutral-200'>Message</p>
            <div class='flex items-center'><button id='session-msg-btn' type='button'
                class='ml-2 box-content rounded-none border-none opacity-80 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none'
                data-te-toast-dismiss aria-label='Close'> <span class='w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25'>
                  <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                    stroke-width='1.5'
                    stroke='currentColor'
                    class='h-6 w-6'> <path
                      stroke-linecap='round'
                      stroke-linejoin='round'
                      d='M6 18L18 6M6 6l12 12' />
                  </svg></span></button></div></div> <div class='break-words rounded-b-lg bg-white py-4 px-4 text-neutral-700 dark:bg-neutral-600 dark:text-neutral-200'>"
                  . $_SESSION['message'] .
          "</div>
        </div>
      </div>";
        unset( $_SESSION['message']);
    }
    ?>
     <h1 class="text-3xl font-semibold text-blue-800"><?= $title ?></h1>

    <div>
        <div class="flex">
            <h2 class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Firstname:</h2>
            <p class="mb-2 text-lg text-gray-900 dark:text-white"><?= $user_firstname ?></p>
        </div>
        <div class="flex">
            <h2 class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Lastname:</h2>
            <p class="mb-2 text-lg text-gray-900 dark:text-white"><?= $user_lastname ?></p>
        </div>
        <div class="flex">
            <h2 class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Email:</h2>
            <p class="mb-2 text-lg text-gray-900 dark:text-white"><?= $user_email ?></p>
        </div>
        <div class="flex">
            <h2 class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Position:</h2>
            <p class="mb-2 text-lg text-gray-900 dark:text-white"><?= $user_position ?></p>
        </div>
        <div class="flex flex-col mt-4">
        <?php
            if($_SESSION['position'] == 'admin'){
                echo "<a class='mb-2 text-blue-600 hover:text-blue-700 hover:font-semibold' href='edit_user_info.php?id=$user_id'>Update user information</a>";
                if($user_position == 'admin') {
                    echo "<a class='mb-2 text-blue-600 hover:text-blue-700 hover:font-semibold' href='user.php?change_to_user=$user_id'>Change position to: user</a>";
                }
                if($user_position == 'user') {
                    echo "<a class='mb-2 text-blue-600 hover:text-blue-700 hover:font-semibold' href='user.php?change_to_admin=$user_id'>Change position to: admin</a>";
                }

                echo "<div class='bg-red-300 mt-6 max-w-max rounded-md flex items-center justify-center'>
                        <a class='py-2 px-4 text-red-600 hover:text-red-700 hover:font-semibold' href='user.php?remove_user=$user_id'>Remove user</a>",
                     "</div>";
            }
        ?>
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