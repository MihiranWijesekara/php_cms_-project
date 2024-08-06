<?php

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    
    define('DB_PASS', 'Ab2#*De#');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'cms');
}

$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

?>
