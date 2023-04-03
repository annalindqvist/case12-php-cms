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
        include ROOT . '/cms-includes/partials/session_msg.php';
        echo "<div class='h-16'></div>";
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