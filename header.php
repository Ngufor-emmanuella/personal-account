<style>
    *{
      padding: 0;
      margin: 0;
      box-sizing: border-box;

    }
    a{
      text-decoration: none;
    }

    body{
      background-color: #f7f7eb;
      font-weight: tahoma;
    }

    header a {
      color: #fff;
    }

    header div {
      padding: 30px;
    }

    header{
      background-color: #766ecc;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    footer{
      padding: 20px;
      text-align: center;
      background-color: #eee;
    }

    input{
      margin: 4px;
      padding: 8px;
      width: 100%;
    }

    textarea{
      margin: 4px;
      padding: 8px;
      width: 100%;
    }


    button{
      padding: 10px;
      cursor: pointer;
    }
  </style>


  <header>
    <div><a href="home.php">Home</a></div>
    <div><a href="profile.php">Profile</a></div>

    <?php if(empty($_SESSION['info'])): ?>
      <div><a href="login.php">Login</a></div>
      <div><a href="signup.php">Signup</a></div>
    <?php else: ?>
      <div><a href="logout.php">Log Out</a></div>
      <?php endif; ?>
  </header>