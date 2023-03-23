<?php

require "database.php";

// destroy session to logout
session_unset();

// redirect to logi page while logged out
header("location: login.php");
die;

?>