<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/Page.php';
require_once "../Parsedown.php";

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
    $page_result = $page->findOne($page_name);
    if ($page_result) {
        foreach ($page_result as $row) {
            $page_name = $row['page_name'];
            $page_content = $row['content'];
            $page_id = $row['page_id'];
            $visibility = ($row['published'] == 1) ? "Publish" : "Draft";
            $created_with = ($row['tiny_mce'] == 1) ? "Tiny_mce" : "Markdown";
        }

        if ($created_with == "Markdown") {
            $Parsedown = new Parsedown();
            $page_content = $Parsedown->text($page_content);
        }
    } else {
        $_SESSION['message'] = "Could not find the page, try again later.";
        header("location: pages.php");
        exit();
    }

}  

if ($_POST) {

    // get the updated content
    $page_content = trim($_POST["content"]);
    $page_name = trim($_POST["page_name"]);
    $page_id = intval(trim($_POST["id"]));
    $visibility = ($_POST['visibility'] == "Publish") ? 1 : 0;
    $created_with = trim($_POST["created_with"]);


    if (!empty($page_id) && is_int($page_id) && is_int($visibility)){
        // Check if there is any content
        if (strlen($page_content) > 0 && strlen($page_name) > 0) {
            $result = $page->updateOne($page_content, $page_name, $page_id, $visibility, $created_with);
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
    <script src="https://cdn.tiny.cloud/1/hm6kn3xumizv482p8yrpiabec13ytrd5rom5tj6swzapa993/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>

</head>

<body class="w-screen flex">

    <script>
        tinymce.init({
            selector: '#tinyEditor',
            menubar: false,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
            toolbar: 'undo redo | styleselect | forecolor |blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
        });
    </script>

    <?php include ROOT . '/cms-includes/partials/header.php'; ?>
    <main class="bg-blue-100 flex w-full m-6 flex-col space-y-10 p-6 rounded-2xl drop-shadow-sm">
    <?php 
    // Session message
    if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
        echo "<div id='session-div' class='fixed top-10 inset-x-0 space-x-2'>
        <div
          class='pointer-events-auto mx-auto hidden w-96 max-w-full rounded-lg bg-white bg-clip-padding text-sm shadow-lg shadow-black/5 data-[te-toast-show]:block data-[te-toast-hide]:hidden dark:bg-neutral-600'
          id='static-example' role='alert' aria-live='assertive' aria-atomic='true' data-te-autohide='false' data-te-toast-init data-te-toast-show>
          <div class='flex items-center justify-between rounded-t-lg border-b-2 border-blue-500 border-opacity-100 bg-white bg-clip-padding px-4 pt-2.5 pb-2 dark:border-opacity-50 dark:bg-neutral-600'>
            <p class='font-bold text-neutral-500 dark:text-neutral-200'>Message</p>
            <div class='flex items-center'><button id='session-msg-btn' type='button'
                class='ml-2 box-content rounded-none border-none opacity-80 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none'
                data-te-toast-dismiss aria-label='Close'> <span class='w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25'>
                  <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'
                    stroke-width='1.5'
                    stroke='currentColor'
                    class='h-6 w-6'> <path
                      stroke-linecap='round'
                      stroke-linejoin='round'
                      d='M6 18L18 6M6 6l12 12' />
                  </svg></span></button></div></div> <div class='break-words rounded-b-lg bg-white py-4 px-4 text-neutral-700 dark:bg-neutral-600 dark:text-neutral-200'>"
                  . $_SESSION['message'] .
          "</div>
        </div>
      </div>";
        unset( $_SESSION['message']);
    }
    ?>
   <h1 class="text-3xl font-semibold text-blue-800"><?= $title ?></h1>

    <?php 
        if(!empty($err)) {
            echo "<ul>";
            echo "<li>" . $err . "</li>";
            echo "</ul>";
        }
    ?>

<div class="rounded-2xl border m-4 bg-blue-200">
       <!-- Tabs -->
       <ul id="tabs" class="inline-flex pt-2 px-1 w-full bg-blue-200">
            <li class="bg-blue-400 px-4 text-slate-100 font-semibold py-2 rounded-t -mb-px"><a
                    id="default-tab" href="#first">Tiny MCE editor</a></li>
            <li class="px-4 text-blue-800 font-semibold py-2 rounded-t"><a href="#second">Markdown editor</a></li>
        </ul>

        <!-- Tab Contents -->
        <div id="tab-contents">
            <div id="first" class="p-4">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="number" name="id" id="id" value=<?= $page_id?> hidden>
                    <input type="number" name="created_with" id="created_with" value='1' hidden>
                    <div class="mt-4 mb-6">
                        <label class="text-xl font-semibold" for="page_name">Name of page</label>
                        <input  class="w-72 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="page_name" id="page_name" value=<?= $page_name?> required>
                    </div>
                    
                    <textarea id="tinyEditor" name="content" rows="20" cols="100"><?php echo $page_content ?></textarea>

                    <div class="flex mt-4">
                        <input class="mr-2" type="radio" id="Draft" name="visibility" value="Draft"
                        <?php if (isset($visibility) && $visibility=="Draft") echo "checked";?>>
                        <label class="mr-4 text-lg font-semibold" for="Draft">Draft</label><br>

                        <input class="mr-2" type="radio" id="Publish" name="visibility" value="Publish"
                        <?php if (isset($visibility) && $visibility=="Publish") echo "checked";?>>
                        <label class="text-lg font-semibold" for="Publish">Publish</label><br>
                    </div>
                    <input class='py-3 px-6 ml-auto mt-4 w-max text-lg bg-blue-500 rounded-xl text-blue-100 drop-shadow-md cursor-pointer hover:bg-blue-600' type="submit" value="submit">
                </form>
            </div>
            <div id="second" class="hidden p-4">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="number" name="id" id="id" value=<?= $page_id?> hidden>
                    <input type="number" name="created_with" id="created_with" value='0' hidden>
                    <div class="mt-4 mb-6">
                        <label class="text-xl font-semibold" for="page_name">Name of page</label>
                        <input class="w-72 p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 sm:text-md focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="page_name" id="page_name" value=<?= $page_name?> required>
                    </div>
                    <div class="flex">
                        <textarea class="w-3/4" name="content" id="content" cols="30" rows="10"
                        required><?php echo $page_content ?></textarea>
                        <div class="ml-4 bg-blue-100 p-4 w-1/4">
                            <h2 class="text-xl font-semibold mb-3">Markdown cheat-sheet</h2>
                            <ul>
                                <li>Headings: # = H1, ## = H2, ### = H3</li>
                                <li>Bold text: **text**</li>
                                <li>Itlic text: *text*</li>
                                <li>Blockquote: > Quote</li>
                                <li>Ordered list: 1. First item 2. Second item</li>
                                <li>Unordered list: - First item - Second item</li>
                                <li>Link: [title](https://www.example.com)</li>
                                <li>Image: ![alt text](image.jpg)</li>
                                <li class="mt-5 italic text-slate-500">For more, see: <a href=" https://www.markdownguide.org/cheat-sheet/">cheat-sheet</a></li>
                            </ul>
                        </div>
                    </div> 
                    <div class="flex mt-4">
                        <input class="mr-2" type="radio" id="Draft" name="visibility" value="Draft"
                        <?php if (isset($visibility) && $visibility=="Draft") echo "checked";?>>
                        <label class="mr-4 text-lg font-semibold" for="Draft">Draft</label><br>

                        <input class="mr-2" type="radio" id="Publish" name="visibility" value="Publish"
                        <?php if (isset($visibility) && $visibility=="Publish") echo "checked";?>>
                        <label class="text-lg font-semibold" for="Publish">Publish</label><br>
                    </div>
                    <input class='py-3 px-6 ml-auto mt-4 w-max text-lg bg-blue-500 rounded-xl text-blue-100 drop-shadow-md cursor-pointer hover:bg-blue-600' type="submit" value="Save page">
                </form>
            </div>
        </div>
    </div>
    </main>

    <script>
        let tabsContainer = document.querySelector("#tabs");
        let tabTogglers = tabsContainer.querySelectorAll("#tabs a");
        tabTogglers.forEach(function (toggler) {
            toggler.addEventListener("click", function (e) {
                e.preventDefault();
                let tabName = this.getAttribute("href");
                let tabContents = document.querySelector("#tab-contents");
                for (let i = 0; i < tabContents.children.length; i++) {
                    tabTogglers[i].parentElement.classList.remove("border-t", "border-r", "border-l",
                        "-mb-px", "bg-white");
                    tabContents.children[i].classList.remove("hidden");
                    if ("#" + tabContents.children[i].id === tabName) {
                        continue;
                    }
                    tabContents.children[i].classList.add("hidden");
                }
                e.target.parentElement.classList.add("border-t", "border-r", "border-l", "-mb-px",
                    "bg-white");
            });
        });
        document.getElementById('session-msg-btn').addEventListener("click", () => {
            console.log("btnclick")
            document.getElementById('session-div').style.display = "none";
        });
    </script>



</body>

</html>