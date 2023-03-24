<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
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