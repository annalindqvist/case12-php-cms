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
$setupTable = $page->setup();

if ($_POST) {
    $form_content = trim($_POST["content"]);
    $page_name = trim($_POST["page_name"]);
    $user_id = $_SESSION['user_id'];
    $visibility = ($_POST['visibility'] == "Publish") ? 1 : 0;

    // Check if there is any content
    if (!empty($form_content) || !empty($page_name)) {

        $result = $page->insertOne($form_content, $user_id, $page_name, $visibility);
        if ($result) {
            $_SESSION['message'] = "Page created successfully!";
            header("location: pages.php");
            exit();
        }

    } else {
        $_SESSION['message'] = "Please fill in content and name of page before saving to the database.";
        header("location: create_page.php");
        exit();
    }
}  

$title = "Crete new page";

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
   
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="page_name">Name of page</label>
        <input type="text" name="page_name" id="page_name">
        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="10"></textarea>

        <input type="radio" id="Draft" name="visibility" value="Draft" checked>
        <label for="Draft">Draft</label><br>

        <input type="radio" id="Publish" name="visibility" value="Publish">
        <label for="Publish">Publish</label><br>

        <input type="submit" value="submit">
    </form>


</body>
</html>