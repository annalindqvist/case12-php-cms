<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';
require_once "../Parsedown.php";



// $page_id = $_GET['id'];
$page_name = $_GET['id'];

// use Template
$page = new Page();

if ($page_name) {
    $all_pages = $page->selectAll();
    $chosen_page = $page->findOne($page_name);

} else {
    echo "Something went wrong.";
} 


// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();


$title = "The website";

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

    <!--  Make sure to send the id to the topbar..  -->
    <?php 
    if(isset($_SESSION['auth'])) {
        include ROOT . '/cms-includes/partials/topbar.php';
        echo "<hr>";
    }
    // Session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo "<div><p>". $_SESSION['message'] . "</p></div>";
        unset( $_SESSION['message']);
    }
    ?>
   
    <?php
    // make this to a menu of all pages
    function print_ul_li($all_pages)
    {
        echo "<ul>";
        foreach ($all_pages as $page) {
            // $id = $page['page_id'];
            $page_name = $page['page_name'];
            echo "<li>", 
                    "<a href='page_preview.php?id=$page_name'>", $page['page_name'], "</a>",
                 "</li>";
        }
        echo "</ul>";
    }
    print_ul_li($all_pages);
    ?>

    <?php 
        $Parsedown = new Parsedown();
        //print_r($chosen_page); array in array..
        $html = $Parsedown->text($chosen_page[0]['content']);

        echo $html;
    ?>


</body>
</html>