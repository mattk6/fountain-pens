<!doctype html>
<html lang="en">
  <head>
    <title>Matthew's Fountain pens - About Us</title>
    <link rel="stylesheet" href="css/script.css">

    <link rel="icon" type="image/x-icon" href="icon.svg">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- JavaScript is needed here to display my map -->
    <script src="js/fountainpens.js"></script>
    <script src="js/jquery-3.1.1.js"></script>
    <script>
      initPenShowMap();
    </script>
  </head>

  <body>
    <a href="#main" class="skip">Skip to main content</a>
    <div id="wrapper">

        <!-- include header and navbar -->
        <?php include 'header.php'?>
        <?php include 'navbar.php'?>


      <main id="main" class="maps">
        
        <h2>About Us</h2>
        <section class="page">

          <p>Though Matthew's Fountain Pens is a very small pen storefront that started in 2023, we have been 
            collecting, trading, fixing and converting fountain pens for years, which began in our home State 
            of Colorado. Having attended a number of pen shows, you can meet us or a vendor that sells our products 
            at any of the pen show events displayed here:</p>

          <div class="map-position">
            <div id="penShowMap"></div>
          </div>
        </section>    
      </main>
      <?php include 'footer.php'?>
    </div>
  </body>
</html>
