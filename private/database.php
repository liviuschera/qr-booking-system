<?php
require_once 'db_credentials.php';

function db_connect()
{
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    check_connection();
    return $db;
}

function db_disconnect($connection)
{
    if (isset($connection)) {
        mysqli_close($connection);
    }
}

function check_connection()
{
    if (mysqli_connect_errno()) {
        $msg = "Failed to connect to MySQL: Failed to connect to MySQL: ". mysqli_connect_error() . "(" . mysqli_connect_errno() . ")";
        exit($msg);
    }
}

function confirm_result($result){
    if(!$result) {
        exit("Database query failed.");
    }
}

function db_escape($connection, $string){
    return mysqli_real_escape_string($connection, $string);
}
