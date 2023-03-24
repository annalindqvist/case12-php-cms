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
// if not admin - no access 
if($_SESSION['position'] == 'user') {
    $_SESSION['message'] = "Only admin can create pages.";
    header('Location: pages.php');	
    exit();
}

$page = new Page();

if (isset($_GET['id'])) {
    $page_name = $_GET['id'];
    $result = $page->deleteOne($page_name);
    if ($result) {
        $_SESSION['message'] = "Successfully deleted page.";
        header("location: pages.php");
        exit();
    }
    
} else {
    $_SESSION['message'] = "Something went wrong, try again later.";
    header("location: pages.php");
    exit();
} 

?>