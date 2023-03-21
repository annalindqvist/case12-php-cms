<?php
declare(strict_types=1);
session_start();
include_once 'cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

if(!isset($_SESSION['auth'])) {
    header('Location: signin.php');	
}

// use Temmplate
$page = new Page();

// check if user is admin - if not not rights to edit

if (isset($_GET['id'])) {

    $page_id = $_GET['id'];
    $page_result = $page->findOne($page_id);
    print_r($page_result);
   
    foreach ($page_result as $row) {
            $page_name = $row['page_name'];
            $page_content = $row['content'];
            $page_id = $row['page_id'];
        }

    echo $page_content; 
} 
// This else will happen after submitting this form?
// else {
   // echo "Something went wrong.";
    //header("location: pages.php");
    
//} 

if ($_POST) {

    // get the updated content
    $page_content = trim($_POST["content"]);
    $page_name = trim($_POST["page_name"]);
    $page_id = trim($_POST["id"]);

    if (empty($page_id) || !is_int($page_id)){
        // Check if there is any content
        if (!empty($page_content) || !empty($page_name)) {
            // Prepare sql query to insert new journal entry
            $result = $page->updateOne($page_content, $page_name, $page_id);
            header("location: pages.php");
        }
    } else {
        echo "Please fill in any content before saving to the database.";
    }
}  
  



$title = "Edit page";

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
   
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="number" name="id" id="id" value=<?= $page_id?> hidden>
        <label for="page_name">Name of page</label>
        <input type="text" name="page_name" id="page_name" value=<?= $page_name?>>
        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="10"><?php echo $page_content ?></textarea>
        
        <input type="submit" value="submit">
    </form>


</body>
</html>