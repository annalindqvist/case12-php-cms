<header>
    <h2><?= "Logged in as: " . $_SESSION['firstname'] ?></h2>
    <ul>
        <li><a href="../cms-admin/signout.php">Sign out</a></li>
        <li><a href="../cms-admin/pages.php">Pages</a></li>
        <li><a href="../cms-admin/drafts.php">Drafts</a></li>
        <li><a href="../cms-admin/users.php">Users</a></li>
        <li><a href="../cms-admin/gallery.php">Gallery</a></li>
        <li><a href="../cms-admin/settings.php">Settings</a></li>
        <li><a href="../view_page.php">Go to site</a></li>

    </ul>
</header>