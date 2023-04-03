<?php
declare(strict_types=1);
session_start();
include_once '../cms-config.php';
include_once ROOT . '/cms-includes/models/Database.php';
include_once ROOT . '/cms-includes/models/User.php';

// if already logged in - go to dashboard
if(isset($_SESSION['auth'])) {
    $_SESSION['message'] = "You aldready have an account and are online, sign out to create a new account.";
    header('Location: dashboard.php');	
    exit();
}

$user = new User();
$setup_table = $user->setup();

$email = null;
$firstname = null;
$lastname = null;
$password = null;

$title = "Sign up";

if ($_POST) {

    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];
    $err = array();

    // validate email 
    if (empty(trim($_POST['email']))) {
        $err[] = "Please fill in your email";
    } 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err[] = "Please double check your email";
    }
    
    // validate password
    if (empty(trim($_POST['password']))) {
        $err[] = "Please fill in a password";
    } 
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        $err[] = "Password format is not correct. Please try again.";
    }
    
    // validate firstname
    if (empty(trim($_POST['firstname']))) {
        $err[] = "Please fill in your firstname.";
    }
    if (strlen(trim($_POST['firstname'])) > 30 || strlen(trim($_POST['firstname'])) < 1) {
        $err[] = "Firstname format is not correct. Please try again.";
    }

    // validate lastname
    if (empty(trim($_POST['lastname']))) {
        $err[] = "Please fill in your lastname.";
    } 
    if (strlen(trim($_POST['lastname'])) > 30 || strlen(trim($_POST['lastname'])) < 1) {
        $err[] = "Lastname format is not correct. Please try again.";
    }
    
    // if errors in form - print them out - else go on
    if (empty($err)) {
        // check if there is already a user with this email
        $result = $user->findOneEmail($email);
        // if there is a match
        if($result) {
            $err[] = "User already exists";
        } else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            // returns boolean
            $register_user = $user->insertOne($email, $hash_pass, $firstname, $lastname);
            if($register_user) {
                $_SESSION['message'] = "You are now registered. Sign in to access the CMS.";
                header('Location: signin.php');	
                exit();
            } else {
                $_SESSION['message'] = "Something went wrong. Try again later.";
                header('Location: signin.php');	
                exit();
            }
        }  
    }
}
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
<?php include ROOT . '/cms-includes/partials/session_msg.php'; ?>
    
     <section class="gradient-form h-screen w-screen bg-blue-200 dark:bg-neutral-700">
        <div class="container h-full p-10">
            <div class="g-6 flex h-full flex-wrap items-center justify-center text-neutral-800 dark:text-neutral-200">
                <div class="w-full">
                    <div class="block rounded-lg bg-white shadow-lg dark:bg-neutral-800">
                        <div class="g-0 lg:flex lg:flex-wrap">
                            <div class="px-4 md:px-0 lg:w-6/12">
                                <div class="md:mx-6 md:p-12">
                                    <div class="text-center">
                                        <img class="mx-auto w-48"
                                            src="../cms-content/uploads/illustration.webp"
                                            alt="logo" />
                                        <h4 class="mt-1 mb-12 pb-1 text-2xl font-semibold">
                                            CMS made by Anna
                                        </h4>
                                    </div>
                                    <form method="post">
                                        <p class="mb-4 text-lg">Sign up</p>
                                        <div class="relative mb-4" data-te-input-wrapper-init>
                                            <label for="email" class="text-sm text-neutral-500 peer-focus:text-blue-600">Email</label>
                                            <input type="email"
                                                class=" min-h-[auto] w-full rounded bg-slate-100 border-0 py-[0.32rem] px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                                name="email" value="<?php echo $email ?>" required/>
                                           
                                        </div>
                                        <div class="relative mb-4" data-te-input-wrapper-init>
                                        <label for="password" class="text-sm text-neutral-500 peer-focus:text-blue-600">Password</label>
                                            <input type="password" name="password"
                                                class="peer block min-h-[auto] w-full bg-slate-100 rounded border-0 py-[0.32rem] px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                                min="5" max="20" value="<?php echo $password ?>" required/>
                                        </div>
                                        <div class="relative mb-4" data-te-input-wrapper-init>
                                        <label for="firstname" class="text-sm text-neutral-500 peer-focus:text-blue-600">Firstname</label>
                                            <input type="txt" name="firstname"
                                                class="min-h-[auto] w-full rounded bg-slate-100 border-0 py-[0.32rem] px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear  data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                                min="1" max="30" value="<?php echo $firstname ?>"required/>
                                        </div>
                                        <div class="relative mb-4" data-te-input-wrapper-init>
                                        <label for="lastname" class="text-sm text-neutral-500 peer-focus:text-blue-600">Lastname</label>
                                            <input type="text" name="lastname"
                                                class="min-h-[auto] w-full rounded bg-slate-100 py-[0.32rem] px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                                min="1" max="30" value="<?php echo $lastname ?>" required/>
                                        </div>
                                        <div class="mb-12 pt-1 pb-1 text-center">
                                            <input style="background: linear-gradient(to right, lightskyblue, blue, darkblue);" type="submit" value="Sign up" class="cursor-pointer mb-3 inline-block w-full rounded px-6 pt-2.5 pb-2 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_rgba(0,0,0,0.2)] transition duration-150 ease-in-out hover:shadow-[0_8px_9px_-4px_rgba(0,0,0,0.1),0_4px_18px_0_rgba(0,0,0,0.2)] focus:shadow-[0_8px_9px_-4px_rgba(0,0,0,0.1),0_4px_18px_0_rgba(0,0,0,0.2)] focus:outline-none focus:ring-0 active:shadow-[0_8px_9px_-4px_rgba(0,0,0,0.1),0_4px_18px_0_rgba(0,0,0,0.2)]">
                                        </div>
                                    </form>
                                    <?php
                                        if(!empty($err)) {
                                            echo "<ul class='flex text-sm -mt-12 text-red-600 flex-col pb-6'>";
                                            foreach($err as $error) {
                                                echo "<li class='text-bold'>" . $error . "</li>";
                                            }
                                            echo "</ul>";
                                        }
                                    ?>
                                    <div class="flex items-center justify-between pb-6">
                                            <p class="mb-0 mr-2">Aldeady have an account?</p>
                                            <a href="/cms-admin/signin.php"class=" inline-block rounded border-2 border-danger px-6 pt-2 pb-[6px] text-xs font-medium uppercase leading-normal text-danger transition duration-150 ease-in-out hover:border-danger-600 hover:bg-neutral-500 hover:bg-opacity-10 hover:text-danger-600 focus:border-danger-600 focus:text-danger-600 focus:outline-none focus:ring-0 active:border-danger-700 active:text-danger-700 dark:hover:bg-neutral-100 dark:hover:bg-opacity-10"
                                            data-te-ripple-init data-te-ripple-color="light">Sign in</a>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center rounded-b-lg lg:w-6/12 lg:rounded-r-lg lg:rounded-bl-none"
                                style="background: linear-gradient(to right, blue,  darkblue)">
                                <div class="px-4 py-6 text-white md:mx-6 md:p-12">
                                    <h4 class="mb-6 text-xl font-semibold">- A simple CMS by Anna Lindqvist</h4>
                                    <p class="text-sm">
                                        This is a simple CMS made as a school project. Here you need you need to sign up to get access. If you're not signed in you still access the pages made by the admins of this CMS <a class="hover:font-semibold" href="../index.php">here</a>.
                                    </p>
                                    <p class="italic text-sm mt-4">Ps. the styling was not my top priority on this case. Don't judge.</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


<script>
        document.getElementById('session-msg-btn').addEventListener("click", () => {
            console.log("btnclick")
            document.getElementById('session-div').style.display = "none";
        });
    </script>

</body>
</html>