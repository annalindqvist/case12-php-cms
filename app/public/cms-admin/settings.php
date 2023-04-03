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
    <?php include ROOT . '/cms-includes/partials/session_msg.php'; ?>
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