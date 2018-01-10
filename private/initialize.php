<?php
ob_start(); // output buffering is turned on
session_start();

define("PRIVATE_PATH", __DIR__);
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("SHARED_PATH", PRIVATE_PATH . "/shared");
define("PUBLIC_PATH", PROJECT_PATH . "/public");
$public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7;
$doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
define("WWW_ROOT", $doc_root);

require_once 'functions.php';
require_once 'database.php';
require_once 'query_functions.php';
$db = db_connect();
$errors = [];
