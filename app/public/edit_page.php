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

$chosen_page_id = $_GET['id'];

if ($chosen_page_id) {
    $chosen_page = $page->findOne($chosen_page_id);
   
    foreach ($chosen_page as $row) {
            $page_title = $row['page_name'];
            $page_content = $row['content'];
            $page_id = $row['page_id'];
        }
} else {
    echo "Something went wrong.";
} 

echo $page_id;
// THE PAGE ID IS GONE AFTR SUBMITTING FORM - find the best way to get it.
// input hidden with value of id? 
// must be another way?

if ($_POST) {
    $form_content = trim($_POST["content"]);
    $page_name = trim($_POST["page_name"]);

    // Check if there is any content
    if (!empty($form_content) || !empty($page_name)) {
        // Prepare sql query to insert new journal entry
        $result = $page->updateOne($form_content, $page_name, $page_id);
        header("location: pages.php");

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
        <label for="page_name">Name of page</label>
        <input type="text" name="page_name" id="page_name" value=<?= $page_content?>>
        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="10" <?= $page_content?>>
        </textarea>
        
        <input type="submit" value="submit">
    </form>


</body>
</html>