<!DOCTYPE html>
<html lang="en">
  <!-- Final Project by Matthew Kruse  11/11/2023 -->
  <head>
    <title>Matthew's Fountain Pens - Login</title>
    <link href="css/script.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/icon.svg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>

  <body>
    <a href="#main" class="skip">Skip to main content</a>
    <div id="wrapper">
      
      <?php include 'header.php';?> 
      <?php include 'navbar.php';?> 

      <main>
        <h2>Login</h2>
        <form action="processlogin.php" method="post">
          <p>
            Username: <input name="shopusername" type="text" value="<?php if(isset($_COOKIE[ "shopusername"])) { echo $_COOKIE["shopusername"];} ?>" class="input-field">
          </p>
          <p>
            Password: <input name="shoppassword" type="text" value="<?php if(isset($_COOKIE[ "shoppassword"])) { echo $_COOKIE["shoppassword"];} ?>" class="input-field">
          </p>

          <p><input type="checkbox" name="remember" 
            <?php if(isset($_COOKIE[ "remember"])) { echo 'checked="checked"';} ?>>Remember me
          </p>

          <p><input type="submit" value="Login"></span></p>
        </form>
      </main>
      <?php include 'footer.php';?>
    </div>
  </body>
</html>
<script src="js/fountainpens.js"></script>