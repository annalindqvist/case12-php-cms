<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}

$page_name = $_GET['id'];

// use Template
$page = new Page();

if ($page_name) {
    $result = $page->deleteOne($page_name);
    $_SESSION['message'] = "Successfully deleted page.";
    header("location: pages.php");
    exit();

} else {
    echo "Something went wrong.";
} 

$title = "Delete page";

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
    <h1><?= $title ?></h1>
</body>
</html>