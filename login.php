<?php
require "database.php";

if($_SERVER['REQUEST_METHOD'] == "POST") {

  $password = addslashes($_POST['password']);
  $email = addslashes($_POST['email']);

  $query = "SELECT * FROM Account WHERE   email = '$email' && password = '$password' limit 1";

  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    $_SESSION['info'] = $row;
    
    header("location: profile.php");
    die;
  } else {
    $error = "Wrong email or password";
  };

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In  Page</title>
</head>
<body>
  <?php include "header.php";?>

  <div style="margin: auto; max-width: 600px">

  <?php
  if(!empty($error)) {
    echo "<div>".$error."</div>";
  }
  ?>

  <h2 style="text-align: center;"> Log-In</h2>
    <form method="Post" style="margin: auto; padding: 10px">
      <input type="email" name="email" placeholder="Email" required><br>
      <input type="password" name="password" placeholder="password" required><br>
      <button>Log-In</button>
    </form>
  </div>


 
  <?php include "footer.php";?>
</body>
</html>