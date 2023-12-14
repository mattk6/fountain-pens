<!doctype html>
<html lang="en">
  <!-- Final Project by Matthew Kruse  11/11/2023 -->
  <head>
    <title>Matthew's Fountain Pens - Products</title>
    <link rel="stylesheet" href="css/script.css">
    <link rel="icon" type="image/x-icon" href="img/icon.svg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
  </head>

  <body>
    <a href="#main" class="skip">Skip to main content</a>
    <div id="wrapper">
      <!-- include header and navbar -->
      <?php include 'header.php'?>
      <?php include 'navbar.php'?>

      <main>
        <h2>Order Online</h2>  
        <?php  
          // Acquire a SQLite database called MainDB
          class MyDB extends SQLite3
          {
            function __construct()
            {
              $this->open('MainDB.db');
            }
          }

          $db = new MyDB();

          // Create a new Product table to load teh latest products
          $res=$db->exec('DROP TABLE IF EXISTS Product;');
          $res=$db->exec('CREATE TABLE IF NOT EXISTS Product
          ( prodid INTEGER,
            prodname TEXT,
            proddesc TEXT,
            prodimg TEXT,
            prodprice REAL);');

          // Source CSV file with product list
          $OutName = "products.csv";

          // if product list file is found load each row into the $data array
          if(0 <> filesize($OutName)) {
            $handle = fopen($OutName, "r");
            while($data = fgetcsv($handle)) {
              $item1 = $data[0];
              $item2 = $data[1];
              $item3 = $data[2];
              $item4 = $data[3];
              $item5 = $data[4];
              $sql3 = "INSERT INTO Product
                values('$item1','$item2','$item3','$item4','$item5');";
              $res=$db->exec($sql3);
            }
            fclose($handle);
          }

          // Loop through the product table to show items on the Order Form
          $i = 0;
          $ItemList = array();
          $query1="SELECT * from Product;";
          $result=$db->query($query1);

          while($row = $result->fetchArray()) {

            $ItemList[$i]['name'] = $row['prodname'];
            $ItemList[$i]['description'] = $row['proddesc'];
            $ItemList[$i]['price'] = $row['prodprice'];
            $ItemList[$i]['img'] = $row['prodimg'];
            $ItemList[$i]['quantity'] = 0;
            ++$i; // increment array index by 1
          }
          $db->close();
        ?>

        <?php
          $ShowForm = FALSE;
          $ShowLink = FALSE;

          // Assign quantity if set
          if (isset($_POST['quantity'])) {
            if(is_array($_POST['quantity'])) {
              foreach ($_POST['quantity'] as $Index => $Qty) {
                $ItemList[$Index]['quantity'] = $Qty;
              }
            }
          }

          if (isset($_POST['purchase'])) { // Place order
            $TimeMicro = microtime();
            $TimeArray = explode(" ", $TimeMicro);
            $OutName = "OnlineOrders/" . $TimeArray[1] . "." . $TimeArray[0] . ".txt"; // file name to save order info
            $OutArray = array();
            $OrderedItemCount = 0;
            foreach ($ItemList as $Index => $Info) {
              if ($Info["quantity"]>0) {
                ++$OrderedItemCount;
                $TempString=$Index . "," . $Info["name"] . "," . 
                  $Info["quantity"] . "," . $Info["price"] . "," .
                  ($Info["quantity"] * $Info["price"]) . "\n";
                $OutArray[]=$TempString;
              }
            }
            if ($OrderedItemCount>0) {
              $ShowLink = TRUE;
              $result = file_put_contents($OutName,$OutArray);
              if ($result===FALSE)
                // failure writing order to file. output error to user
                echo "<p>There was a problem saving your order.</p>\n";
              else {
                echo "<p>Your order was successfully submitted.</p>\n";


                $db = new MyDB();

                $res=$db->exec("CREATE TABLE IF NOT EXISTS CustOrders (p_id INTEGER, p_name TEXT, qty INTEGER, u_price REAL, t_price REAL);");
                if(0 <> filesize($OutName)) {
                  $handle = fopen($OutName, "r");
                  while($data = fgetcsv($handle)) {
                    $item1 = $data[0];
                    $item2 = $data[1];
                    $item3 = $data[2];
                    $item4 = $data[3];
                    $item5 = $data[4];
                    $sql = "INSERT INTO CustOrders (p_id, p_name, qty, u_price, t_price) values ('$item1','$item2','$item3','$item4','$item5' )";
                    $db->exec($sql);
                  }
                  fclose($handle);
                }
              }
            }
            else {
              echo "<p>You have not ordered anything yet.</p>\n";
              $ShowForm = TRUE;
            }
          }
          else {
            $ShowForm = TRUE;
            if (isset($_POST['AddItem'])) {
              if (is_array($_POST['AddItem'])) {
                $ItemsToAdd=array_keys($_POST['AddItem']);
                foreach ($ItemsToAdd as $Index) {
                  ++$ItemList[$Index]["quantity"];
                }
              }
            }
            if (isset($_POST['SubtractItem'])) {
              if (is_array($_POST['SubtractItem'])) {
                $ItemsToSubtract=array_keys($_POST['SubtractItem']);
                foreach ($ItemsToSubtract as $Index) {
                    --$ItemList[$Index]["quantity"];
                }
              }
            }
          }

          if ($ShowForm) {
            echo "<form action=\"products.php\" method=\"POST\">\n";
          }
          echo "    <table cellspacing=\"0\">\n";
          echo "       <tr><th>&nbsp;&nbsp;Qty&nbsp;&nbsp;</th>";
          if ($ShowForm) {
            echo "<th></th>";
          }
          echo "<th>&nbsp;&nbsp;Item&nbsp;&nbsp;</th>" .
              "<th>&nbsp;&nbsp;Unit&nbsp;Price&nbsp;&nbsp;</th>" .
              "<th>&nbsp;&nbsp;Item&nbsp;Subtotal&nbsp;&nbsp;</th></tr>\n";
          $ItemCount=count($ItemList);
          $TotalItems=0;
          $TotalAmount=0;

          // use variables to swap out table background colors
          $bgColorA= "Linen"; // "LightGrey";
          $bgColorB= "Cornsilk"; // "Silver";
          $bgcolor=$bgColorA;

          // Generate html for each product 
          for ($i=0; $i<$ItemCount; ++$i) {
            // perform calculations
            $SubtotalAmount=$ItemList[$i]["quantity"] * $ItemList[$i]["price"];
            $UnitPrice = number_format($ItemList[$i]["price"], 2, ".", ".");
            $ItemPrice = number_format($SubtotalAmount, 2, ".", ".");
            $TotalItems+=$ItemList[$i]["quantity"];
            $TotalAmount+=$SubtotalAmount;

            //quantity & +/- buttons 
            echo "      <tr style=\"background-color:$bgcolor\"><td>&nbsp;&nbsp;\n" .
              $ItemList[$i]["quantity"] . "<input type=\"hidden\" name=\"quantity[$i]\" value=\"" . 
              $ItemList[$i]["quantity"] . "\" />&nbsp;&nbsp;</td>";
            if ($ShowForm) {
              echo "<td>";
              if ($ItemList[$i]["quantity"]>0) {
                echo "<input style=\"width:20px\" type=\"submit\" name=\"SubtractItem[$i]\" value=\"-\" /><br />";
              }
              echo "<input style=\"width:20px\" type=\"submit\" name=\"AddItem[$i]\" value=\"+\" /></td>";
            }

            // item - image, name, description, unit price
            echo "<td>&nbsp;&nbsp;<strong>" . '<img src="img/' . $ItemList[$i]["img"] . '" height="140px">' .
              $ItemList[$i]["name"] . "</strong>&nbsp;&nbsp;<br />&nbsp;&nbsp;" . $ItemList[$i]["description"] .
              "&nbsp;&nbsp;</td><td align=\"right\">&nbsp;&nbsp;$UnitPrice&nbsp;&nbsp;</td> <td align=\"right\">&nbsp;&nbsp;$ItemPrice&nbsp;&nbsp;</td></tr>\n";
              if ($bgcolor==$bgColorB)
                $bgcolor=$bgColorA;
              else
                $bgcolor=$bgColorB;
            } // end of loop

            // totals - Total Item Count, Total Price
            if ($TotalItems>0) {
              $TotalPrice = number_format($TotalAmount, 2, '.', '.');
              echo "      <tr><td colspan=\"2\">&nbsp;&nbsp;<strong>$TotalItems</strong>&nbsp;&nbsp;</td>";
              echo "<td ";
              if ($ShowForm) {
                echo "colspan=\"2\"";
              }
              echo "align=\"right\">&nbsp;&nbsp;<strong>Total =&gt;</strong>&nbsp;&nbsp;" . 
                  "</td><td align=\"right\">&nbsp;&nbsp;<strong>\$&nbsp;$TotalPrice</strong>&nbsp;&nbsp;</td></tr>\n";
            }
            echo "    </table>\n";
            echo "    <br />\n";
          
          // Place order submit button  
          if ($ShowForm) {
            if($TotalItems>0) {
              echo "    <input type=\"submit\" name=\"purchase\" value=\"Place Order\" />\n";
            }
            echo "</form>\n";
          }

          // Place another order link
          if ($ShowLink) {
            echo "    <a href=\"products.php\">Place another order</a>\n";
          }

        ?>
      </main>
      <!-- include footer  -->
      <?php include 'footer.php'?>
    </div>
  </body>
</html>
