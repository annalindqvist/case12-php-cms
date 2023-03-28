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



// use Database
// klassen protected - kan inte nå åtkomst
// Call to protected Database::__construct() from invalid context
// $database = new Database();


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

    
    <?php
    function menu_form($all_pages)
    {
        $menu_item = 0;
        echo "<form action='' method='post'>";

        // create select-list foreach page who is published
        foreach ($all_pages as $page) {
            $page_name = $page['page_name'];
            $menu_item++;
            echo "<label for='$page_name'>" . $page['page_name'] . "</label>",
                 "<select id='test' name='$page_name'>";
                    // create option to prioritize the menu items 
                    for ($i=1; $i <= count($all_pages); $i++) { 
                       echo "<option value='$i'>" . $i . "</option>";
                    }
                
            echo "</select>";
        }
        echo "<input type='submit' value='Submit menu'>
            </form>";
    }
        
    
    menu_form($all_pages);

    ?>
   

</body>
</html>