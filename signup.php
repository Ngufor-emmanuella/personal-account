<?php
require "database.php";

if($_SERVER['REQUEST_METHOD'] == "POST") {
  $username = addslashes($_POST['username']);
  $password = addslashes($_POST['password']);
  $email = addslashes($_POST['email']);
  $date = date('Y-m-d H:i:s');
  $gender = addslashes($_POST['gender']);

  $query = "INSERT INTO Account(username, password, email, date, gender) VALUES ('$username', '$password', '$email', '$date', '$gender')";
  
  $result = mysqli_query($conn, $query);

  header("location: login.php");
  die;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up Page</title>
</head>
<body>
  <?php include "header.php";?>

  <div style="margin: auto; max-width: 600px">

  <h2 style="text-align: center;"> Sign-Up</h2>
    <form method="Post" style="margin: auto; padding: 10px">
      <input type="text" name="username" placeholder="username" required><br>
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="password" required><br>
      <input type="text" name="gender" placeholder="gender" required><br>
      <button>Sign-up</button>
    </form>
  </div>


 
  <?php include "footer.php";?>
</body>
</html>