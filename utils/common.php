<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('PROJECT_FOLDER', '/CodeGarou/'); 
define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'] . PROJECT_FOLDER); 

session_start();
require_once SITE_ROOT . "utils/database.php";

?>