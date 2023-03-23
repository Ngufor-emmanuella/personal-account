<?php
require "database.php";

check_login();

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'post_delete') {
  // delete your piost
  // id comes from session data but if u get id n it doesnt exist setd default to 0
  $id = $_GET['id']?? 0;

  // for a user to delete only his posts
  $user_id = $_SESSION['info']['id'];

  $query = "SELECT * FROM posts WHERE id = '$id' && user_id = '$user_id' limit 1";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);
    if(file_exists($row['image'])) {
      unlink($row['image']);
    }
  }

  $query = "DELETE FROM posts WHERE id = '$id' && user_id = '$user_id' limit 1";
  $result = mysqli_query($conn, $query);

  // redirect the user to the logout page
  header("location: profile.php");
  die;
}elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] =="post_edit") {

  // post edit
  $id = $_GET['id'] ?? 0;
  $user_id = $_SESSION['info']['id'];

  $image_added = false;

  if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {
    // file was uploaded
    $folder = "uploads/";
    // to give permision to access the folder we put 0777 and true
    if(!file_exists($folder)) {
      mkdir($folder, 0777, true);
    }
    // we want the folder to go to d destination below
    $image = $folder . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image);
    
        $query = "SELECT * FROM posts WHERE id = '$id' && user_id = '$user_id' limit 1";
        $result = mysqli_query($conn, $query);
      
        if(mysqli_num_rows($result) > 0) {
      
          $row = mysqli_fetch_assoc($result);
          if(file_exists($row['image'])) {
            unlink($row['image']);
          }
        }
  
    $image_added = true;
  }
  $post = addslashes($_POST['post']);

  if($image_added == true) {
  $query = "update posts set post = '$post', image = '$image' WHERE id = '$id' && user_id = '$user_id' limit 1";
  } else {
  $query =  "update posts set post = '$post' WHERE id = '$id' && user_id = '$user_id' limit 1";
  }
  
  $result = mysqli_query($conn, $query);

  header("location: profile.php");
  die;

}
elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['action']) && $_POST['action'] == 'delete') {
  // delete your profile
  // id comes from session data
  $id = $_SESSION['info']['id'];
  $query = "DELETE FROM Account WHERE id = '$id' limit 1";
  $result = mysqli_query($conn, $query);

  if(file_exists($_SESSION['info']['image'])) {
    unlink($_SESSION['info']['image']);
  }

  $query = "DELETE FROM posts WHERE user_id = '$id'";
  $result = mysqli_query($conn, $query);

  // redirect the user to the logout page
  header("location: logout.php");
  die;
}elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['username'])) {

  // profile edit
  $image_added = false;
  echo "<pre>";
  if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {
    // file was uploaded
    $folder = "uploads/";
    // to give permision to access the folder we put 0777 and true
    if(!file_exists($folder)) {
      mkdir($folder, 0777, true);
    }
    // we want the folder to go to d destination below
    $image = $folder . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image);
    
    // unlink is how you delete files
    if(file_exists($_SESSION['info']['image'])) {
      unlink($_SESSION['info']['image']);
    }

    $image_added = true;
  }
  $username = addslashes($_POST['username']);
  $password = addslashes($_POST['password']);
  $email = addslashes($_POST['email']);
  $date = date('Y-m-d H:i:s');
  $gender = addslashes($_POST['gender']);
  $id = $_SESSION['info']['id'];

  if($image_added == true) {
  $query = "update Account set username = '$username', email = '$email', password = '$password', image = '$image'
   WHERE id = '$id' limit 1";
  } else {
  $query =  "update Account set username = '$username', email = '$email', password = '$password'
  WHERE id = '$id' limit 1";
  }
  
  $result = mysqli_query($conn, $query);

  $query = "SELECT * FROM Account WHERE id = '$id' limit 1";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  
  // the code below will update d session info w what ive added
  if(mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);
    $_SESSION['info'] = $row;
  }

  header("location: profile.php");
  die;

}

// to check posts by user
elseif($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['post'])) {

  // adding post
  $image = "";
  if(!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0 && $_FILES['image']['type'] == "image/jpeg") {
    // file was uploaded
    $folder = "uploads/";
    // to give permision to access the folder we put 0777 and true
    if(!file_exists($folder)) {
      mkdir($folder, 0777, true);
    }
    // we want the folder to go to d destination below
    $image = $folder . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $image);
 
  }

  $post = addslashes($_POST['post']);
  $user_id = $_SESSION['info']['id'];
  $date = date('Y-m-d H:i:d');
  
  $query =  "INSERT INTO posts(user_id, post, image, date) VALUES ('$user_id', '$post', '$image', '$date')";
  
  $result = mysqli_query($conn, $query);

  header("location: profile.php");
  die;

}
?>

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile Page</title>
</head>
<body>
  <?php require "header.php";?>

  <div style="margin: auto; max-width: 600px">

  <?php if(!empty($_GET['action']) && $_GET['action'] == 'post_delete' && !empty($_GET['id'])):?>

    <?php
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM posts WHERE id='$id' limit 1";

    $result = mysqli_query($conn, $query);
    ?>

    <!-- we only show  e edit post if there was a result actually -->
    <?php if(mysqli_num_rows($result) > 0):?>
      <!-- show this wen its true -->
      <?php $row = mysqli_fetch_assoc($result); ?>

      <h3> Are you sure you want to delete this post ??..</h3>
      <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px">

          <img src="<?=$row['image']?>" style="width:70%;height:300px;object-fit: cover;"><br>
            
            <div><?=$row['post']?></div><br>

            <input type="hidden" name="action" value="post_delete">
            <button>Delete</button>

            <a href="profile.php">
              <button type="button">Cancel</button>
           </a>
      </form>
    <?php endif; ?>

    <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'post_edit' && !empty($_GET['id'])):?>

    <?php
    $id = (int)$_GET['id'];
    $query = "SELECT * FROM posts WHERE id='$id' limit 1";

    $result = mysqli_query($conn, $query);
    ?>

    <!-- we only show  e edit post if there was a result actually -->
    <?php if(mysqli_num_rows($result) > 0):?>
      <!-- show this wen its true -->
      <?php $row = mysqli_fetch_assoc($result); ?>

      <h4> Edit A Post...</h4>
      <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px">

          <img src="<?=$row['image']?>" style="width:70%;height:300px;object-fit: cover;"><br>
            
            image: <input type="file" name="image"><br>
            <textarea name="post" rows="8"><?=$row['post']?></textarea><br>

            <input type="hidden" name="action" value="post_edit">
            <button>Save</button>

            <a href="profile.php">
              <button type="button">Cancel</button>
           </a>
      </form>
    <?php endif; ?>


    <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'edit'):?>

      <h2 style="text-align: center;"> Edith Profile</h2>

      <form method="post" enctype="multipart/form-data" style="margin: auto; padding:10px;">
      <img src="<?php echo $_SESSION['info']['image']?>" style="width: 100px; height: 100px; object-fit: cover; margin:auto; display: block;">
        image:<input type="file" name="image"><br>
        <input value="<?php echo $_SESSION['info']['username']?>" type="text" name="username" placeholder="username" required><br>
        <input value="<?php echo $_SESSION['info']['email']?>" type="email" name="email" placeholder="Email" required><br>
        <input value="<?php echo $_SESSION['info']['password']?>" type="password" name="password" placeholder="password" required><br>
        <input value="<?php echo $_SESSION['info']['gender']?>" type="text" name="gender" placeholder="gender" required><br>
        
        <button>Save</button>

        <a href="profile.php">
          <button type="button">Cancel</button>
        </a>
      </form>

      <!-- duplicating for delete -->
      <?php elseif(!empty($_GET['action']) && $_GET['action'] == 'Delete'):?>
      <h2 style="text-align: center;">Are you sure you wan to delete your profile? </h2>
      
      <div style="margin: auto;max-width: 600px;text-align: center;">
        <form method="post" style="margin: auto; padding:10px;">

          <img src="<?php echo $_SESSION['info']['image']?>" style="width: 100px; height: 100px; object-fit: cover; margin:auto; display: block;">
            <div><?php echo $_SESSION['info']['username']?></div>
            <div><?php echo $_SESSION['info']['email']?></div>
            <div><?php echo $_SESSION['info']['gender']?></div>
            <input type="hidden" name="action" value="delete">
            
            <button>Delete</button>

            <a href="profile.php">
              <button type="button">Cancel</button>
            </a>
        </form>
      </div>

    <?php else:?>

  <h2 style="text-align: center;"> User Profile </h2>
  <br>

  <div style="margin: auto;max-width: 600px;text-align: center;">
    <div>
     <td>"<img src=<?php echo $_SESSION['info']['image']?>" style="width: 150px; height: 150px; object-fit: cover"></td>
    </div>
    <br>

    <div>
      <td><?php echo $_SESSION['info']['username']?></td>
    </div>

    <div>
      <td><?php echo $_SESSION['info']['email']?></td>
    </div>
    <br>
    <a href="profile.php?action=edit">
      <button> Edit Profile...</button>
    </a>

    <a href="profile.php?action=delete">
      <button> Delet Profile !!</button>
    </a>
    <br>


  </div>
  <hr>
  <h4> Create A Post...</h4>
    <form method="post" enctype="multipart/form-data" style="margin: auto; padding: 10px">
      
      image: <input type="file" name="image"><br>
      <textarea name="post" rows="8"></textarea><br>
      <button>Post</button>
    </form>

    <!-- to display post -->

    <hr >
    <post>

    <?php
    $id = $_SESSION['info']['id'];
    // to make d latest post b at d top we do order by descender order
    $query = "SELECT * FROM posts WHERE user_id = '$id' order by id desc limit 10";

    $result = mysqli_query($conn, $query);
    ?>

    <?php if(mysqli_num_rows($result) > 0):?>

      <?php while ($row = mysqli_fetch_assoc($result)):?>

        <!-- get the info of a particular user by checking its id -->
        <?php
        
        $user_id = $row['user_id'];
        $query = "SELECT username, image FROM Account WHERE id = '$user_id' limit 1";
        $result2 = mysqli_query($conn, $query);

        $user_row = mysqli_fetch_assoc($result2);

        ?>
 
        <div style="background-color:white; display:flex; border:solid thin #aaa;border-radius: 30px; margin-bottom: 10px; margin-top: 10px;">
            <div style="flex:1; text-align: center;">
            <img src="<?=$user_row['image']?>" style="border-radius: 50%;margin:20px;width:100px;height:100px;object-fit: cover;">
            <br>

            <?=$user_row['username']?>

          </div>

        <div style="flex:8">
            <?php if(file_exists($row['image'])):?>
              <div style="">
              <img src="<?=$row['image']?>" style="width:70%;height:300px;object-fit: cover;">
            </div>

            <?php endif;?>

            <div>
              <div style="color:#888"><?=date("j M, Y", strtotime($row['date']))?></div>
              <?php echo nl2br(htmlspecialchars($row['post']))?>

              <br><br>

              <a href="profile.php?action=post_edit&id=<?= $row['id']?>">
                <button> Edit...</button>
              </a>

              <a href="profile.php?action=post_delete&id=<?= $row['id']?> ">
                   <button type="button">Delete!!</button>
              </a>
              
            </div>
            <br><br>
        </div>


      </div>
    <?php endwhile;?>

    <?php endif;?>
    </post>
    <?php endif; ?>
  </div>
  <?php include "footer.php";?>
</body>
</html>