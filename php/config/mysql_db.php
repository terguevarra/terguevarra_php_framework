<?php
    
date_default_timezone_set('Asia/Manila');
    
$dbhost = "localhost";
$dbuser = "root";
$dbpwd = "killerlook";
$dbname = "sakila";

$conn = new mysqli($dbhost, $dbuser, $dbpwd, $dbname);

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

?>