<!DOCTYPE html>
<html>
    <title>W3.CSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Joti+One" rel="stylesheet">  <!-- font-family: 'Joti One', cursive; -->
    <link rel="stylesheet" 
          href="https://www.w3schools.com/w3css/4/w3.css"
    />
    <link rel="stylesheet" 
              href="myCSS.css" 
              type="text/css" />
    <style>
        .mySlides {display:none;}
    </style>
    
    <body>
        <?php
          $servername = getenv('IP');
          $dbPort = 3306;
          $database = "ZombieStore";
          $username = getenv('C9_USER');
          $password = "";
          $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
          $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        ?>
        <h1>Zombie Survival Store</h1>
        <ul>
          <li><a href="zombieStore.php">Home</a></li>
          <li><a href="gunsPage.php">Guns</a></li>
          <li><a href="melee.php">Melee</a></li>
          <li><a href="explosives.php">Exposives</a></li>
          <li><a href="ammo.php">Ammo</a></li>
          <li><a href="medical.php">Medical</a></li>
        </ul>
        <div class="w3-content w3-display-container" style="text-align:center">
            <div class="w3-display-container mySlides">
              <img src="imgs/ak47.jpg">
            </div>
            <div class="w3-display-container mySlides">
              <img src="imgs/awp.jpg">
            </div>
            <div class="w3-display-container mySlides">
              <img src="imgs/p2000.jpg">
            </div>
            <div class="w3-display-container mySlides">
              <img src="imgs/m4a4.jpg">
            </div>
        </div>























        <script>
        var myIndex = 0;
        carousel();
        
        function carousel() {
            var i;
            var x = document.getElementsByClassName("mySlides");
            for (i = 0; i < x.length; i++) {
               x[i].style.display = "none";  
            }
            myIndex++;
            if (myIndex > x.length) {myIndex = 1}    
            x[myIndex-1].style.display = "block";  
            setTimeout(carousel, 2000); // Change image every 2 seconds
        }
        </script>

    </body>
</html>
