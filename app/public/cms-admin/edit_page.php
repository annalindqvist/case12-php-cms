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
    echo $page_name;
    $page_result = $page->findOne($page_name);
    if ($page_result) {
        foreach ($page_result as $row) {
            $page_name = $row['page_name'];
            $page_content = $row['content'];
            $page_id = $row['page_id'];
            $visibility = ($row['published'] == 1) ? "Publish" : "Draft";
        }
    } else {
        $_SESSION['message'] = "Could not find the page, try again later.";
        header("location: pages.php");
        exit();
    }

}  
//else {
//     $_SESSION['message'] = "TEST Something went wrong, try again later.";
//     header("location: pages.php");
//     exit();
// }


if ($_POST) {

    // get the updated content
    $page_content = trim($_POST["content"]);
    $page_name = trim($_POST["page_name"]);
    $page_id = intval(trim($_POST["id"]));
    $visibility = ($_POST['visibility'] == "Publish") ? 1 : 0;

    if (!empty($page_id) && is_int($page_id) && is_int($visibility)){
        // Check if there is any content
        if (strlen($page_content) > 0 && strlen($page_name) > 0) {
            $result = $page->updateOne($page_content, $page_name, $page_id, $visibility);
            if($result) {
                $_SESSION['message'] = "Successfully updated page.";
                header("location: pages.php");
                exit();
            }else {
                $_SESSION['message'] = "Something went wrong, try again later.";
                header("location: pages.php");
                exit();
            }
        } else {
            $err = "Make sure all fields are filled in correctly.";
        }
    } else {
        $err = "Make sure all fields are filled in correctly.";
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
        <input type="number" name="id" id="id" value=<?= $page_id?> hidden>
        <label for="page_name">Name of page</label>
        <input type="text" name="page_name" id="page_name" value=<?= $page_name?> required>
        <label for="content">Content</label>
        <textarea name="content" id="content" cols="30" rows="10" required><?php echo $page_content ?></textarea>

        <input type="radio" id="Draft" name="visibility" value="Draft" <?php if (isset($visibility) && $visibility=="Draft") echo "checked";?>>
        <label for="Draft">Draft</label><br>

        <input type="radio" id="Publish" name="visibility" value="Publish" <?php if (isset($visibility) && $visibility=="Publish") echo "checked";?>>
        <label for="Publish">Publish</label><br>
        
        <input type="submit" value="submit">
    </form>

    <?php 
        if(!empty($err)) {
            echo "<ul>";
            echo "<li>" . $err . "</li>";
            echo "</ul>";
        }
    ?>

</body>
</html>