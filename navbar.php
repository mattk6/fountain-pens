<nav>
  <!-- Final Project by Matthew Kruse  11/11/2023 -->
  <ul>
    <?php
      // get the current file name
      $fileName = basename($_SERVER['PHP_SELF']);

      // define the nav items in array
      $navItems = array("Login"=>"index.php", 
                        "Home"=>"home.php", 
                        "Products"=>"products.php",
                        "Contact"=>"contactus.php",
                        "About Us"=> "aboutus.php");

      // loop through and build the list items                    
      foreach ($navItems as $key => $value) {

        if ($value === $fileName) {
          echo "<li><a href=\"" . $value . "\" class=\"active\">" . $key . "</a></li>";
        } else {
          echo "<li><a href=\"" . $value . "\">" . $key . "</a></li>";
        }
      }
    ?>
  </ul>
</nav>
