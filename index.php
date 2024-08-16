<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>collection</title>
    <link rel="stylesheet" href="css/collection.css" />
    <script src="collection.js"></script>
  </head>
  <body>
    <div class="header">
      <a href="index.php">Home</a>
      <a href="menu.html">Menu</a>
      <a href="profil.html">Profile</a>
      <a href="about.html">About us</a>
      <a href="contact.html">Contact Us</a>
      <div class="search-container">
        <span><img src="image/search.png" alt="" /></span>
        <input type="text" placeholder="search" />
      </div>
      <div class="shopping">
        <a href="cart.php"><img src="image/shopping-cart.png" alt="" /> </a>
      </div>
    </div>

    <br />
    <?php
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "china-global-door";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Fetch products from the database
    $sql = "SELECT id, image, details,price FROM products";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo '<div id="product-grid">';
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo '<div class="product" id="product' . $row["id"] . '">';
            echo '<img src="http://localhost/china-global-door/images/' . $row['image'] . '">';

            echo '<h4>' . $row["details"] . ' ' . $row["price"] . '$</h4>';

            echo '<button onclick="wishList()"> Add to wishlist❤ </button>  ';
            echo '<button onclick="addToCart()">Add to cart</button>';
            echo '</div>';
        }
        
        echo '</div>';
    } else {
       // echo "0 results";
    }
    
    $conn->close();
    ?>
    
   
  </body>
</html>
