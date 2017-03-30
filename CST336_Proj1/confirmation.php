<?php
    session_start();
    if(empty($_SESSION['Cart'])){
        $_SESSION['Cart'] = array();
    }
?>

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
        <script>
        // When the user clicks on <div>, open the popup
        function myFunction($thing) {
            var popup = document.getElementById($thing);
            popup.classList.toggle("show");
        }
        </script>
        <?php
          $servername = getenv('IP');
          $dbPort = 3306;
          $database = "ZombieStore";
          $username = getenv('C9_USER');
          $password = "";
          $dbConn = new PDO("mysql:host=$servername;port=$dbPort;dbname=$database", $username, $password);
          $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
          unset($_SESSION['Cart']);
          if (empty($_SESSION['Cart'])){
            $_SESSION['Cart'] = array();
          }
          if (!empty($_POST['Cart'])) {
              foreach ($_POST['Cart'] as $item) {
                  array_push($_SESSION['Cart'], $item);
              }
          }
        ?>
        <h1>Zombie Survival Store</h1>
    
        
        
        <form action="zombieStore.php" method="POST">
        <!--****************************************************************-->
        <!--****************************************************************-->
            
        <!--****************************************************************-->
        </form>
        
        <form action="confirmation.php" method="POST">
            
            
        </form>
        
        <?php 
            if (!empty($_SESSION['Cart']))
                printMerchandise($dbConn);
            else {
               echo "<h3>You did not buy anything!</h3>";    
            }
        ?>
        
        <p style="text-align:center"><a href="zombieStore.php" class="none"><button type="button">Back</button></a></p>

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
    function printMerchandise($dbConn) {
        $priceTotal = 0;
        $weightTotal = 0;
        echo "<table align=center>
                <th colspan='3'>Shopping Cart</th>
                <tr>
                    <td style='width:200px'><b>Item Name</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        foreach ($_SESSION['Cart'] as $item) {
            $sql = "SELECT *
                    FROM Guns
                    WHERE Guns.ProductName = '$item' 
                    UNION
                    SELECT *
                    FROM Ammo
                    WHERE Ammo.ProductName = '$item' 
                    UNION
                    SELECT *
                    FROM Explosives
                    WHERE Explosives.ProductName = '$item' 
                    UNION
                    SELECT *
                    FROM Melee
                    WHERE Melee.ProductName = '$item' 
                    UNION
                    SELECT *
                    FROM Medical
                    WHERE Medical.ProductName = '$item'";
            $stmt = $dbConn->prepare($sql);
            $stmt->execute();
            $productInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            $weightTotal += $productInfo['Weight'];
            $priceTotal += $productInfo['Price'];
            echo "  <tr>
                        <td style='width:200px'>$item</td>
                        <td style='width:100px'>".$productInfo['Weight']."</td>
                        <td style='width:100px'>$".$productInfo['Price']."</td>
                    </tr>";
        }
        echo "  <tr>
                    <td style='width:200px'>Totals</td>
                    <td style='width:100px'>".$weightTotal."</td>
                    <td style='width:100px'>$".$priceTotal."</td>
                </tr>";
        echo "</table><br>";
    }
?>