<?php
ob_start();
session_start();

include ('includes/database_connection.php');
include('classes/comment.php');
//include the user class, pass in the database connection
include('classes/user.php');
$user = new User($db); 
$comment = new Comment($db);
?>
