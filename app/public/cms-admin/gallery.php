<?php
declare(strict_types=1);
session_start();
include_once  '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';

// if not auth go to signin.php
if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You need to sign in to access.";
    header('Location: signin.php');	
    exit();
} 

if(!empty($_FILES)) {
    $name = $_FILES['image']['name'];
    $type = $_FILES['image']['type'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $error = $_FILES['image']['error'];
    $size = $_FILES['image']['size'];

    if($error) {
      exit("File upload fail, code: $error");
    }

    $extensions = ["jpg", "gif", "png", "jpeg"];
    $type_parts = explode("/", $type);

    $extension = $type_parts[1];
    if(in_array($extension, $extensions)) {
        
        $target_directory = $_SERVER['DOCUMENT_ROOT'] . "/cms-content/uploads/";

        if (move_uploaded_file($tmp_name, $target_directory . $name)) {
            $_SESSION['message'] = "File uploaded";
           
        } else {
            $_SESSION['message'] = "Something went wrong.";
        }
        header('Location: gallery.php');	
        exit();
    }

    
}

$title = "Gallery";

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

    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="submit" value="Upload">
    </form>


    <?php include ROOT . '/cms-includes/partials/gallery.php'; ?>
   

</body>
</html>