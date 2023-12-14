<!doctype html>
<html lang="en">
  <!-- Final Project by Matthew Kruse  11/11/2023 -->

  <head>
    <title>Matthew's Fountain Pens - Contact</title>
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
        <h2>Contact Us</h2>

        <!-- add a form with name, email, phone, satisfaction, experience, and message  -->
        <form action="processform.php" method="post">

          <!-- name, email, phone inputs, text, email, and tel respectively -->
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required><br><br>
          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required><br><br>
          <label for="phone">Phone number (optional):</label>
          <input type="tel" id="phone" name="phone"  placeholder="000-000-0000" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"><br><br>

          <!-- experience level and satisfaction level dropdown -->
          <label for="Experiencelevel">Experience with Fountain Pens:</label>      
          <select name="Experiencelevel" id="Experiencelevel" placeholder="Select Option">
            <option value="" disabled selected>Select your option</option>
            <option value="None">No experience, never owned, never used</option>
            <option value="Novice">I own inexpensive or disposable fountain pen(s)</option>
            <option value="User">I use a really good fountain pen regularly and I refill my ink</option>
            <option value="Collector">I collect fountain pens</option>
          </select>

          <label for="Satisfactionlevel">Your satisfaction with this site (optional)</label>
          <select name="Satisfactionlevel" id="Satisfactionlevel">
            <option value="" disabled selected>Select your satisfaction 0-5</option>
            <option value="0">0 dis-satisfied with the site</option>
            <option value="1">1 not satisfied with the site</option>
            <option value="2">2 neither satisfied nor dis-satisfied</option>
            <option value="3">3 site is perfectly acceptable</option>
            <option value="4">4 satisfied</option>
            <option value="5">5 site exceeds expectations</option>
          </select>

          <!-- message text box input -->
          <label for="message">Message:</label><br>
          <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>

          <!-- submit button -->
          <input type="submit" value="Submit">
        </form>
      </main>
      <!-- include footer  -->
      <?php include 'footer.php'?>
    </div>
  </body>
</html>
