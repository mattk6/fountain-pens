  <!-- Final Project by Matthew Kruse  11/11/2023 -->
<!DOCTYPE html>
<html>
  <head>
    <title>Process Contact Form</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <link href="css/script.css" rel="stylesheet">
  </head>
  <body>
    <div id="wrapper">    
      <?php include 'header.php'?> 
      <?php include 'navbar.php'?>
      <main>
        <?php
            $errorMsg = "";

            //validate if empty, have a message output
            if(empty($_POST['name'])) {
                $errorMsg .= "<p>You must enter your name.</p>\n";
            }

            if (empty($_POST['email'])) {
                $errorMsg .= "<p>You must enter your email.</p>\n";
            } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errorMsg .= "<p>Invalid email format.</p>\n";
            }

            if(empty($_POST['Experiencelevel'])) {
                $errorMsg .= "<p>You must select your experience level with fountain pens.</p>\n";
            }

            if(empty($_POST['message'])) {
                $errorMsg .= "<p>You must enter a message.</p>\n";
            }

            // don't continue if an error ocurred
            if(strlen($errorMsg) > 0) {
                echo $errorMsg;
                echo "<p>Click your browser's Back button to return to the Contact form and fix these errors.</p>\n";
            } else {
                
                //read input
                $name = addslashes($_POST['name']);
                $email = addslashes($_POST['email']);
                $experienceLevel = addslashes($_POST['Experiencelevel']);
                $message = addslashes($_POST['message']);
                $phone = '';
                $Satisfactionlevel = '';
                
                if(!empty($_POST['phone'])) {
                    $phone = addslashes($_POST['phone']);
                }
                if(!empty($_POST['Satisfactionlevel'])) {
                    $Satisfactionlevel = addslashes($_POST['Satisfactionlevel']);
                }

                //open and write to contactinfo.txt
                $contactInfo = fopen("contactinfo.txt", "ab");
                if (is_writeable("contactinfo.txt")) {
                    $data = "$name, $email, $experienceLevel, $phone, $Satisfactionlevel, $message\n";

                    if (fwrite($contactInfo, $data)) {
                        echo "<p>Thank you for contacting us!</p>\n";
                    } else {
                        echo "<p>Cannot process your contact.</p>\n";
                    }
                } else {
                    echo "<p>Cannot write to the file.</p>\n";
                }

                fclose($contactInfo);
            }
        ?>
      </main>
      <?php include 'footer.php'?>
    </div>
  </body>
</html>
