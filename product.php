<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="css/productInfo.css" />
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
    </body>
</html>

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

// Get product ID from URL
$product_id = isset($_GET['id']) ? $_GET['id'] : 1; // Default to 1 if no ID is provided

// Fetch product details from the database
$sql = "SELECT image, details, price FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <div class="card">
        <nav>
            <a href="index.php"><img src="image/arrow.png" alt="Back" /></a>
        </nav>
        <div class="photo">
            <img src="http://localhost/china-global-door/admin/image/<?php echo $row['image']; ?>" alt="Product Image" />
        </div>
        <div class="description">
            <h2><?php echo $row['details']; ?></h2>
           <!-- <p><?php //echo $row['description']; ?></p>-->
            <h1><?php echo $row['price']; ?>$</h1>
            <div class="quantity-box">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" value="1" class="quantity-input" />
                <span class="quantity-arrow">
                    <i class="fas fa-chevron-up"></i>
                    <i class="fas fa-chevron-down"></i>
                </span>
            </div>
            <button>Add to Wishlist</button>
            <button>Add to Cart</button>
        </div>
    </div>
    <?php
} else {
    echo "Product not found.";
}

$conn->close();
?>
