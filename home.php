<?php
require "database.php";

check_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personal account website </title>
</head>
<body>
  <?php include "header.php";?>
  
  <div style="max-width: 600px; margin:auto;">

  <h2 style="text-align: center;">Timeline</h2>

    <?php

    // to make d latest post b at d top we do order by descender order
    $query = "SELECT * FROM posts order by id desc limit 10";

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
              <div style="color:#888"><?=date("j M, Y",strtotime($row['date']))?></div>
              <?php echo nl2br(htmlspecialchars($row['post']))?>
            </div>
        </div>


      </div>
    <?php endwhile;?>

    <?php endif;?>
  </div>
  
  <?php include "footer.php";?>
</body>
</html>