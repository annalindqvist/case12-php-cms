<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You need to sign in to access.";
    header('Location: signin.php');	
    exit();
}

$page = new Page();
$all_pages = $page->selectAllPublished();

if ($_POST) {

    $result = $page->menuPriority($_POST);
    if($result) {
        $_SESSION['message'] = "Successfully updated menu.";
        header("location: settings.php");
        exit();
    }else {
        $_SESSION['message'] = "Something went wrong, try again later.";
        header("location: settings.php");
        exit();
    }
}

$title = "Settings";

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
    <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Change the menu priority.</h2>
    <ul class="max-w-lg mb-6 space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
        <li>Priority: 1 will be first in the menu and also appear as <span class="font-medium">startpage.</span></li>
        <li>Using same priority will sort on page name in ascending order.</li>
    </ul>

    
    <?php
    function menu_form($all_pages)
    {
        $menu_item = 0;
        echo "<form action='' method='post'>";

        // create select-list foreach page who is published
        foreach ($all_pages as $page) {
            $page_name = $page['page_name'];
            $menu_item++;
            echo "<div class='flex items-center mb-3 w-48'><label for='$page_name' class='block mb-2 text-sm font-medium text-gray-900 dark:text-white'>" . $page['page_name'] . "</label>",
                 "<select id='test' name='$page_name' class='bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block ml-auto max-w-lg p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'>";
                    // create option to prioritize the menu items 
                    for ($i=1; $i <= count($all_pages); $i++) { 
                        if($page['menu_priority'] == $i) {
                            echo "<option value='$i' selected>" . $i . "</option>";
                        } else {
                            echo "<option value='$i'>" . $i . "</option>";
                        }
                    }
            echo "</select></div>";
        }
        echo "<input type='submit' value='Submit menu priority' class='py-3 px-6 ml-auto mt-4 w-max text-sm bg-blue-500 rounded-xl text-blue-100 drop-shadow-md cursor-pointer hover:bg-blue-600'>
            </form>";
    }
        
    menu_form($all_pages);

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