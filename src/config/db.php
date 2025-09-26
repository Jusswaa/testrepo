<?php
$host = "db"; 
$user = "user";
$pass = "pass";
$db   = "testdb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
