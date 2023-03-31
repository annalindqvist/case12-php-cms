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
        print_r($result);
        // if there is a match and the user is not the same as this user
        if($result[0]['user_id'] !== $user_id) {
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

        <form action="" method="post">
            <input type="number" name="id" id="id" value="<?php echo $user_id?>" hidden>

            <div class="flex items-center mb-4">
                <label for="firstname"
                    class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Firstname</label>
                <input
                    class="block w-72 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    type="text" name="firstname" id="firstname" min="1" max="30" value="<?php echo $user_firstname ?>"
                    required>
            </div>
            <div class="flex items-center mb-4">
                <label for="lastname"
                    class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Lastname</label>
                <input
                    class="block w-72 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    type="text" name="lastname" id="lastname" value="<?php echo $user_lastname ?>" min="1" max="30"
                    required>
            </div>
            <div class="flex items-center mb-4">
                <label for="email" class="mb-2 mr-2 text-lg font-semibold text-gray-900 dark:text-white">Email</label>
                <input
                    class="block w-72 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    type="email" name="email" id="email" value="<?php echo $user_email ?>" required>
            </div>
            <input type="submit" value="Update user information"
                class='py-3 px-6 ml-auto mt-4 w-max text-sm bg-blue-500 rounded-xl text-blue-100 drop-shadow-md cursor-pointer hover:bg-blue-600'>
        </form>
        <?php
            if(!empty($err)) {
                echo "<ul class='flex text-sm -mt-12 text-red-600 flex-col pb-6'>";
                foreach($err as $error) {
                    echo "<li class='text-bold'>" . $error . "</li>";
                }
                echo "</ul>";
                } 
        ?>

    </main>

    <script>
        document.getElementById('session-msg-btn').addEventListener("click", () => {
            console.log("btnclick")
            document.getElementById('session-div').style.display = "none";
        });
    </script>
</body>

</html>