<!--/*-->
<!--Link to Documents: https://docs.google.com/a/csumb.edu/document/d/1KNpn_Bj7iEKPeEEjzqRdB9Rr6N0Vaivb8B4JfacR0Yk/edit?usp=sharing -->
<!--Link to Github: https://github.com/CarlosHuizarValenzuela/CST336_Proj1-->
<!--Link to Trello: https://trello.com/b/GpRMmRIB/project-1-cst-336-->
<!--*/-->

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
        
        
        <form action="zombieStore.php" method="POST">
        <!--****************************************************************-->
        <div>
        <?php
            if(!empty($_POST['sortType']) && !empty($_POST['orderType'])){
                $sortChoice = $_POST['sortType'];
                $orderChoice = $_POST['orderType'];
                
                // echo $sortChoice;
                // echo $orderChoice;
            }
            else if (empty($_POST['sortType']) && empty($_POST['orderType'])) {
                
            }
            else if(empty($_POST['sortType']) || empty($_POST['orderType'])){
                echo "You cannot have without the other.";
            }
            
            if (!empty($_POST['priceFilter']) && $_POST['priceFilter'] < 0) {
                echo "You cannot enter a price lower than 0.";
            }
            
            if (!empty($_POST['weightFilter']) && $_POST['weightFilter'] < 0) {
                echo "You cannot enter a weight lower than 0.";
            }
        ?>
        </div>
        <!--****************************************************************-->
        <div>
            <br>
            <label>Sorting Type:</label>
            <select name="sortType"> 
                <option selected="selected" disabled="disabled"></option>
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
            
            <label>Order By Type:</label>
            <select name="orderType">
                <option selected="selected" disabled="disabled"></option>
                <option value="name">Name</option>
                <option value="price">Price</option>
                <option value="weight">Weight</option>
            </select>
            <br><br>
            
            <label>Enter your Max Price Filter:</label> 
            <input type="text" name="priceFilter">
            <br><br>
            
            <label>Enter your Max Weight Filter:</label>
            <input type="text" name="weightFilter">
            <br><br>
            
            <label>Enter the starting letters of the product you are searching for:</label>
            <input type="text" name="nameFilter">
            <br><br>
            
            <input type="submit" value="Arrange Items"/>
            <hr>
        </div>
        <!--****************************************************************-->
        </form>
        <h3>Click on Item to Open Description and Get Option to Add to Cart</h3>
        
        <form action="confirmation.php" method="POST">
        <?php
            $next = 0;
            $num = 0;
            showGuns($dbConn);
            showMelee($dbConn);
            echo "<div style='clear:right;'></div>";
            showExplosives($dbConn);
            echo "<div style='clear:left;'></div>";
            showAmmo($dbConn);
            echo "<div style='clear:right;'></div>";
            showMedical($dbConn);
        ?>
        
        <div id="footer">
            <br><br>
            <input type="submit" name="submit" value="Submit"/>
        </div>
        
            
            
        </form>
        



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
        global $next, $num;
        $sql = "SELECT Guns.*
                FROM Guns";
        if (!empty($_POST['priceFilter']) && $_POST['priceFilter'] >= 0) {
            $sql = $sql." WHERE Guns.Price <= ".$_POST['priceFilter']."";
        }
        if (!empty($_POST['weightFilter']) && $_POST['weightFilter'] >= 0) {
            if (empty($_POST['priceFilter'])) {
                $sql = $sql." WHERE Guns.Weight <= ".$_POST['weightFilter']."";
            }
            else {
                $sql = $sql." AND Guns.Weight <= ".$_POST['weightFilter']."";
            }
        }
        if (!empty($_POST['nameFilter'])) {
            if (empty($_POST['priceFilter']) && empty($_POST['weightFilter'])) {
                $sql = $sql." WHERE Guns.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
            else {
                $sql = $sql." AND Guns.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
        }
        if ($_POST['sortType'] == "asc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Guns.ProductName ASC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Guns.Price ASC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Guns.Weight ASC";
            }
        }
        else if ($_POST['sortType'] == "desc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Guns.ProductName DESC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Guns.Price DESC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Guns.Weight DESC";
            }
        }
        else {
            $sql = $sql." ORDER BY Guns.GunId ASC";
        }
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table style='float:left'>
                <th colspan='3'>Guns</th>
                <tr>
                    <td style='width:200px'><b>Gun Model</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr><br>";
        while ($row = $stmt->fetch()) {
            $num++;
            $value = $row["ProductName"];
            echo "  <tr>
                        <td style='width:200px' class='popup' onclick='myFunction($next)'>".$row['ProductName']."
                        <span class='popuptext' id=$next>".$row['ProductDesc'].
                        "<br><label>Add to Cart: </label><input type='checkbox' name='Cart[".$num."]' value='$value'></span></td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
                    $next++;
        }
        echo "</table>";
    }
    
    function showMelee($dbConn) {
        global $next, $num;
        $sql = "SELECT Melee.*
                FROM Melee";
        if (!empty($_POST['priceFilter']) && $_POST['priceFilter'] >= 0) {
            $sql = $sql." WHERE Melee.Price <= ".$_POST['priceFilter']."";
        }
        if (!empty($_POST['weightFilter']) && $_POST['weightFilter'] >= 0) {
            if (empty($_POST['priceFilter'])) {
                $sql = $sql." WHERE Melee.Weight <= ".$_POST['weightFilter']."";
            }
            else {
                $sql = $sql." AND Melee.Weight <= ".$_POST['weightFilter']."";
            }
        }
        if (!empty($_POST['nameFilter'])) {
            if (empty($_POST['priceFilter']) && empty($_POST['weightFilter'])) {
                $sql = $sql." WHERE Melee.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
            else {
                $sql = $sql." AND Melee.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
        }
        if ($_POST['sortType'] == "asc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Melee.ProductName ASC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Melee.Price ASC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Melee.Weight ASC";
            }
        }
        else if ($_POST['sortType'] == "desc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Melee.ProductName DESC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Melee.Price DESC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Melee.Weight DESC";
            }
        }
        else {
            $sql = $sql." ORDER BY Melee.MeleeId ASC";
        }
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<table style='float:right'>
                <th colspan='3'>Melee</th>
                <tr>
                    <td style='width:200px'><b>Melee Weapon</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            $num++;
            $value = $row["ProductName"];
            echo "  <tr>
                        <td style='width:200px' class='popup' onclick='myFunction($next)'>".$row['ProductName']."
                        <span class='popuptext' id=$next>".$row['ProductDesc'].
                        "<br><label>Add to Cart: </label><input type='checkbox' name='Cart[".$num."]' value='$value'></span></td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
                    $next++;
        }
        echo "</table>";
    }
    
    function showExplosives($dbConn) {
        global $next, $num;
        $sql = "SELECT Explosives.*
                FROM Explosives";
        if (!empty($_POST['priceFilter']) && $_POST['priceFilter'] >= 0) {
            $sql = $sql." WHERE Explosives.Price <= ".$_POST['priceFilter']."";
        }
        if (!empty($_POST['weightFilter']) && $_POST['weightFilter'] >= 0) {
            if (empty($_POST['priceFilter'])) {
                $sql = $sql." WHERE Explosives.Weight <= ".$_POST['weightFilter']."";
            }
            else {
                $sql = $sql." AND Explosives.Weight <= ".$_POST['weightFilter']."";
            }
        }
        if (!empty($_POST['nameFilter'])) {
            if (empty($_POST['priceFilter']) && empty($_POST['weightFilter'])) {
                $sql = $sql." WHERE Explosives.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
            else {
                $sql = $sql." AND Explosives.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
        }
        if ($_POST['sortType'] == "asc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Explosives.ProductName ASC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Explosives.Price ASC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Explosives.Weight ASC";
            }
        }
        else if ($_POST['sortType'] == "desc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Explosives.ProductName DESC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Explosives.Price DESC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Explosives.Weight DESC";
            }
        }
        else {
            $sql = $sql." ORDER BY Explosives.ExplosivesId ASC";
        }
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<br><table style='float:right'>
                <th colspan='3'>Explosives</th>
                <tr>
                    <td style='width:200px'><b>Explosive Type</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            $num++;
            $value = $row["ProductName"];
            echo "  <tr>
                        <td style='width:200px' class='popup' onclick='myFunction($next)'>".$row['ProductName']."
                        <span class='popuptext' id=$next>".$row['ProductDesc'].
                        "<br><label>Add to Cart: </label><input type='checkbox' name='Cart[".$num."]' value='$value'></span></td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
                    $next++;
        }
        echo "</table><br>";
    }
    
    function showAmmo($dbConn) {
        global $next, $num;
        $sql = "SELECT Ammo.*
                FROM Ammo";
        if (!empty($_POST['priceFilter']) && $_POST['priceFilter'] >= 0) {
            $sql = $sql." WHERE Ammo.Price <= ".$_POST['priceFilter']."";
        }
        if (!empty($_POST['weightFilter']) && $_POST['weightFilter'] >= 0) {
            if (empty($_POST['priceFilter'])) {
                $sql = $sql." WHERE Ammo.Weight <= ".$_POST['weightFilter']."";
            }
            else {
                $sql = $sql." AND Ammo.Weight <= ".$_POST['weightFilter']."";
            }
        }
        if (!empty($_POST['nameFilter'])) {
            if (empty($_POST['priceFilter']) && empty($_POST['weightFilter'])) {
                $sql = $sql." WHERE Ammo.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
            else {
                $sql = $sql." AND Ammo.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
        }
        if ($_POST['sortType'] == "asc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Ammo.ProductName ASC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Ammo.Price ASC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Ammo.Weight ASC";
            }
        }
        else if ($_POST['sortType'] == "desc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Ammo.ProductName DESC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Ammo.Price DESC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Ammo.Weight DESC";
            }
        }
        else {
            $sql = $sql." ORDER BY Ammo.AmmoId ASC";
        }
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<br><table style='float:left'>
                <th colspan='3'>Ammo</th>
                <tr>
                    <td style='width:200px'><b>Ammo Type</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            $num++;
            $value = $row["ProductName"];
            echo "  <tr>
                        <td style='width:200px' class='popup' onclick='myFunction($next)'>".$row['ProductName']."
                        <span class='popuptext' id=$next>".$row['ProductDesc'].
                        "<br><label>Add to Cart: </label><input type='checkbox' name='Cart[".$num."]' value='$value'></span></td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
                    $next++;
        }
        echo "</table>";
    }
    
    function showMedical($dbConn) {
        global $next, $num;
        $sql = "SELECT Medical.*
                FROM Medical";
        if (!empty($_POST['priceFilter']) && $_POST['priceFilter'] >= 0) {
            $sql = $sql." WHERE Medical.Price <= ".$_POST['priceFilter']."";
        }
        if (!empty($_POST['weightFilter']) && $_POST['weightFilter'] >= 0) {
            if (empty($_POST['priceFilter'])) {
                $sql = $sql." WHERE Medical.Weight <= ".$_POST['weightFilter']."";
            }
            else {
                $sql = $sql." AND Medical.Weight <= ".$_POST['weightFilter']."";
            }
        }
        if (!empty($_POST['nameFilter'])) {
            if (empty($_POST['priceFilter']) && empty($_POST['weightFilter'])) {
                $sql = $sql." WHERE Medical.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
            else {
                $sql = $sql." AND Medical.ProductName LIKE '".$_POST['nameFilter']."%'";
            }
        }
        if ($_POST['sortType'] == "asc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Medical.ProductName ASC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Medical.Price ASC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Medical.Weight ASC";
            }
        }
        else if ($_POST['sortType'] == "desc") {
            if ($_POST['orderType'] == "name") {
                $sql = $sql." ORDER BY Medical.ProductName DESC";
            }
            else if ($_POST['orderType'] == "price") {
                $sql = $sql." ORDER BY Medical.Price DESC";
            }
            else if ($_POST['orderType'] == "weight") {
                $sql = $sql." ORDER BY Medical.Weight DESC";
            }
        }
        else {
            $sql = $sql." ORDER BY Medical.MedicalId ASC";
        }
        $stmt = $dbConn->prepare($sql);
        $stmt->execute();
        echo "<br><table style='float:right'>
                <th colspan='3'>Medical</th>
                <tr>
                    <td style='width:200px'><b>Medical Equipment</b></td>
                    <td style='width:100px'><b>Weight (kg)</b></td>
                    <td style='width:100px'><b>Price</b></td>
                </tr>";
        while ($row = $stmt->fetch()) {
            $num++;
            $value = $row["ProductName"];
            echo "  <tr>
                        <td style='width:200px' class='popup' onclick='myFunction($next)'>".$row['ProductName']."
                        <span class='popuptext' id=$next>".$row['ProductDesc'].
                        "<br><label>Add to Cart: </label><input type='checkbox' name='Cart[".$num."]' value='$value'></span></td>
                        <td style='width:100px'>".$row['Weight']."</td>
                        <td style='width:100px'>$".$row['Price']."</td>
                    </tr>";
                    $next++;
        }
        echo "</table><br>";
    }
?>