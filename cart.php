<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/cart.css">
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
    </div><br>
<?php
// Establish a connection to your database
$conn = mysqli_connect("localhost", "root", "", "china-global-door");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to retrieve cart products from the database
$query = "SELECT * FROM cart";
$result = mysqli_query($conn, $query);

// Check if there are products in the cart
if (mysqli_num_rows($result) > 0) {
    ?>
    <div class="cart-container">
        <h1>Your Cart</h1>
        <div class="cart-content">
            <div class="cart-products">
                <div class="cart-header">
                    <div class="column-product">PRODUCT</div>
                    <div class="column-price">PRICE</div>
                    <div class="column-quantity">QUANTITY</div>
                    <div class="column-total">TOTAL</div>
                </div>
                <?php
                $subtotal = 0; // Initialize subtotal
                
                // Loop through each product in the cart
                while ($row = mysqli_fetch_assoc($result)) {
                    $total = $row['price'] * $row['quantity']; // Calculate total for each item
                    $subtotal += $total; // Add to subtotal
                    ?>
                    <div class="cart-item">
                        <div class="column-product">
                            <img src="<?php echo $row['image']; ?>" alt="Product Image">
                            <div class="product-details">
                                <h4><?php echo $row['name']; ?></h4>
                            </div>
                        </div>
                        <div class="column-price">$<?php echo $row['price']; ?></div>
                        <div class="column-quantity">
                            <input type="text" value="<?php echo $row['quantity']; ?>" class="qty-input" readonly>
                        </div>
                        <div class="column-total">$<?php echo $total; ?></div>
                        <div class="column-remove">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <input type="hidden" name="remove" value="<?php echo $row['id']; ?>">
                                <button class="remove-btn" type="submit">×</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }

                // Handle product removal from the cart
                if (isset($_POST['remove'])) {
                    $id = $_POST['remove'];
                    // Query to remove the item from the cart/database
                    $query = "DELETE FROM cart WHERE id = '$id'";
                    mysqli_query($conn, $query);
                    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page
                    exit;
                }
                ?>
            </div>
            <div class="order-summary">
                <h2>ORDER SUMMARY</h2>
                <div class="summary-item">
                    <span>TOTAL:</span>
                    <span>$<?php echo $subtotal; ?></span>
                </div>
                <button class="checkout-btn">Proceed To Checkout</button>
                <button class="continue-btn">Continue Shopping</button>
            </div>
        </div>
    </div>
    <?php
} else {
    // If the cart is empty
    echo '
    <div class="cart-container">
        <h1>Your Cart</h1>
        <div class="cart-content">
            <div class="cart-products">
                <div class="cart-header">
                    <div class="column-product">PRODUCT</div>
                    <div class="column-price">PRICE</div>
                    <div class="column-quantity">QUANTITY</div>
                    <div class="column-total">TOTAL</div>
                </div>
                <div class="cart-item">
                    <div class="column-product">
                        <div class="product-details">
                           <h2>The cart is empty</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-summary">
                <h2>ORDER SUMMARY</h2>
                <div class="summary-item">
                    <span>TOTAL:</span>
                    <span>$0</span>
                </div>
                <button class="checkout-btn">Proceed To Checkout</button>
                <button class="continue-btn">Continue Shopping</button>
            </div>
        </div>
    </div>';
}

// Close the database connection
mysqli_close($conn);
?>
</body>
</html>
<style></style>