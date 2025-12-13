<?php

$host = 'localhost';
$db = 'user_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if($conn->connect_error) // if ever there is a connection problem with he database
{
    die('Connection Failed'.$conn -> connect_error);
}

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','user_db');

$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

?>