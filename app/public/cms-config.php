<?php

// auto set database server 

// production, public webserver
$db_host = "localhost"; // usually: localhost
$db_name = "cms-db";
$db_user = "db_user_linode";
$db_password = "db_password_linode";


// development, localhost (docker-compose.yml)
if (strpos($_SERVER['SERVER_NAME'], "localhost") !== false) {
    $db_host = "mysql";
    $db_name = "cms-db";
    $db_user = "db_user";
    $db_password = "db_password";
}

// define constants
define("DB_HOST", $db_host);
define("DB_NAME", $db_name);
define("DB_USER", $db_user);
define("DB_PASSWORD", $db_password);

define("ROOT", $_SERVER['DOCUMENT_ROOT']);

?>