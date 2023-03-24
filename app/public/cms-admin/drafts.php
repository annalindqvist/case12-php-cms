<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}

// use Template
$page = new Page();

$result = $page->selectAllDrafts();



// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();


$title = "Drafts";

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
    <hr>
    <?php 
    // Session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo "<div><p>". $_SESSION['message'] . "</p></div>";
        unset( $_SESSION['message']);
    }
    ?>
    <h1><?= $title ?></h1>

    <a href="create_page.php">Add new page</a>
    <?php

    function print_ul_li($result)
    {
        echo "<ul>";
        foreach ($result as $page) {
            // $id = $page['page_id'];
            $page_name = $page['page_name'];
            echo "<li>
                    <p>", $page['page_name'], "</p>
                    <div>
                    <a href='delete_page.php?id=$page_name'>Delete</a>
                    <a href='edit_page.php?id=$page_name'>Edit</a>
                    <a href='page_preview.php?id=$page_name'>Preview page</a>
                    </div>
                 </li>";
        }
        echo "</ul>";
    }
    print_ul_li($result);

    ?>


</body>
</html>