<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_portfolio"; // make sure DB name same ho phpMyAdmin me

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
}
?>
