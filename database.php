<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "personalaccount";

$conn = mysqli_connect($servername, $username, $password, $dbname);
// check connection 
if(mysqli_connect_error()) {
  echo "failed connection ";
} else {
  echo "connection to database successfull !";
}

// create database 


// create table in the personalaccount database

// create a function to check if user is logged in 
function check_login() {
  if(empty($_SESSION['info'])) {
    header("location: login.php");
    die;
  }
}

// wec call the check_login() in the profile.php file 

?>
