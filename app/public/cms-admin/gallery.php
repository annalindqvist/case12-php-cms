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

<body class="w-screen flex">

    <?php include ROOT . '/cms-includes/partials/header.php'; ?>
    <main class="bg-blue-100 flex w-full m-6 flex-col space-y-10 p-6 rounded-2xl drop-shadow-sm">
    <?php include ROOT . '/cms-includes/partials/session_msg.php'; ?>
    
        <div class="flex">
            <h1 class="text-3xl font-semibold text-blue-800"><?= $title ?></h1>

            <form class="ml-auto flex" action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="image" required class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-500 file:text-white
                    hover:file:bg-blue-600 cursor-pointer
                    ">
                <input type="submit" value="Upload"
                    class='py-3 px-6 ml-auto w-max bg-blue-500 rounded-xl text-blue-100 drop-shadow-md cursor-pointer hover:bg-blue-600'>
            </form>
        </div>

        <?php include ROOT . '/cms-includes/partials/gallery.php'; ?>
    </main>
    <script>
        document.getElementById('session-msg-btn').addEventListener("click", () => {
            console.log("btnclick")
            document.getElementById('session-div').style.display = "none";
        });
    </script>

</body>

</html>