<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';
require_once "Parsedown.php";

$page = new Page();

if (isset($_GET['id'])) {
    $page_name = $_GET['id'];
    $all_pages = $page->selectAllMenuPriority();
    $chosen_page = $page->findOnePublished($page_name);

} else {
    $all_pages = $page->selectAllMenuPriority();
    $start_page = $all_pages[0];
} 

$title = "The website";

?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="./websitestyle.css">
</head>
<body>

    <!--  Make sure to send the id to the topbar..  -->
    <?php 
    if(isset($_SESSION['auth'])) {
        include ROOT . '/cms-includes/partials/topbar.php';
        echo "<div class='h-16'></div>";
    }
    
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

    
   <div id="main-content">
    <?php
    // make this to a menu of all pages
    function print_ul_li($all_pages)
    {
        echo "<ul id='page-menu'>";
        foreach ($all_pages as $page) {
            // $id = $page['page_id'];
            $page_name = $page['page_name'];
            echo "<li>", 
                    "<a href='/?id=$page_name'>", $page['page_name'], "</a>",
                 "</li>";
        }
        echo "</ul>";
    }
    print_ul_li($all_pages);
    ?>
    
    <?php 
        $Parsedown = new Parsedown();
        //print_r($chosen_page); array in array..
        if (isset($_GET['id'])) {
            $html = $Parsedown->text($chosen_page[0]['content']);
            echo $html;
        } else {
            $html = $Parsedown->text($start_page['content']);
            echo $html;
        }
       
    ?>
    </div>

    <script>
        document.getElementById('session-msg-btn').addEventListener("click", () => {
            console.log("btnclick")
            document.getElementById('session-div').style.display = "none";
        });
    </script>


</body>
</html>