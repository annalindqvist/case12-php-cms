<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';
require_once "Parsedown.php";

if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}

$page_id = $_GET['id'];

// use Template
$page = new Page();

if ($page_id) {
    $all_pages = $page->selectAll();
    $chosen_page = $page->findOne($page_id);

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

    <?php include ROOT . '/cms-includes/partials/header.php'; ?>

    <?php

    // make this to a menu of all pages
    function print_ul_li($all_pages)
    {
        echo "<ul>";
        foreach ($all_pages as $page) {
            echo "<li>", 
                    "<p>", $page['page_name'], "</p>",
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