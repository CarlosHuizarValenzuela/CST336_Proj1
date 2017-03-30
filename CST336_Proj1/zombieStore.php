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
        
        <?php
            showGuns($dbConn);
            showMelee($dbConn);
            showExplosives($dbConn);
            showAmmo($dbConn);
            showMedical($dbConn);
        ?>




















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

<?php
    function showGuns($dbConn) {
        $sql = "SELECT Guns.*
                FROM Guns
                ORDER BY Guns.GunId ASC";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table>
                <th colspan='3'>Guns</th>
                <tr>
                    <td style='width:200px'><b>Gun Model</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            echo "  <tr>
                        <td style='width:200px'>".$row['ProductName']."</td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
        }
        echo "</table><br>";
    }
    
    function showMelee($dbConn) {
        $sql = "SELECT Melee.*
                FROM Melee
                ORDER BY Melee.MeleeId ASC";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table>
                <th colspan='3'>Melee</th>
                <tr>
                    <td style='width:200px'><b>Melee Weapon</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            echo "  <tr>
                        <td style='width:200px'>".$row['ProductName']."</td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
        }
        echo "</table><br>";
    }
    
    function showExplosives($dbConn) {
        $sql = "SELECT Explosives.*
                FROM Explosives
                ORDER BY Explosives.ExplosivesId ASC";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table>
                <th colspan='3'>Explosives</th>
                <tr>
                    <td style='width:200px'><b>Explosive Type</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            echo "  <tr>
                        <td style='width:200px'>".$row['ProductName']."</td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
        }
        echo "</table><br>";
    }
    
    function showAmmo($dbConn) {
        $sql = "SELECT Ammo.*
                FROM Ammo
                ORDER BY Ammo.AmmoId ASC";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table>
                <th colspan='3'>Ammo</th>
                <tr>
                    <td style='width:200px'><b>Ammo Type</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            echo "  <tr>
                        <td style='width:200px'>".$row['ProductName']."</td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
        }
        echo "</table><br>";
    }
    
    function showMedical($dbConn) {
        $sql = "SELECT Medical.*
                FROM Medical
                ORDER BY Medical.MedicalId ASC";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table>
                <th colspan='3'>Medical</th>
                <tr>
                    <td style='width:200px'><b>Medical Equipment</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            echo "  <tr>
                        <td style='width:200px'>".$row['ProductName']."</td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
        }
        echo "</table><br>";
    }
?>