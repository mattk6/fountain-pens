<?php
          // Storing username and password in Cookies if choose to remember
          if(isset($_POST['remember'])) {
            setcookie("shopusername", $_POST["shopusername"]);
            setcookie("shoppassword", $_POST["shoppassword"]);
            setcookie("remember", 'checked');
          }
          else {
            setcookie("shopusername", "");
            setcookie("shoppassword", "");
            setcookie("remember", '');
          }

?>

<!DOCTYPE html>
<html>
  <!-- Final Project by Matthew Kruse  11/11/2023 -->
  <head>    
    <title>Matthew's fountain pen login</title>
    <link href="css/script.css" rel="stylesheet">
  </head>
  <body>
    <div id="wrapper">
      <?php include 'header.php'?> 
      <main>
        <br>
        <?php
          // Storing username and password in Cookies if choose to remember
          if(isset($_POST['remember'])) {
            echo "Cookies Set Successfully";
          }
          else {
            echo "Cookies Not Set";
          }
        ?>
        <p><a href="index.php">Go back to Login Page</a> </p>
      </main>  
      <?php include 'footer.php'?>

    </div>
  </body>
</html>
