<?php
session_start();
include("../dtbs.php");
include("../functions.php");

// Initialize cart item counter
if (!isset($_SESSION['cartItemCount'])) {
    $_SESSION['cartItemCount'] = 0;
}

// Fetch bundle/deal products (cat_id = 3)
$sqlBundleProducts = "SELECT * FROM items WHERE cat_id = '3'";
$resultBundleProducts = $con->query($sqlBundleProducts);

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch the name of the logged-in user with user_type 'U'
$clientName = $_SESSION['username'];

// Fetch the name of the user with user_type 'U' who is currently logged in
$sqlClientName = "SELECT username FROM users WHERE user_type = 'U' AND username = '$clientName'";
$resultClientName = $con->query($sqlClientName);
$clientName = ($resultClientName && $resultClientName->num_rows > 0) ? $resultClientName->fetch_assoc()['username'] : '';


// Handle adding to cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addToCart'])) {
    $productId = $_POST['productId'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1;

    // Check if the item is already in the cart
    $sqlCheckCart = "SELECT * FROM cart WHERE user_id = '$clientName' AND item_id = '$productId'";
    $resultCheckCart = $con->query($sqlCheckCart);
    
    if ($resultCheckCart->num_rows > 0) {
        // Update quantity if item is already in the cart
        $sqlUpdateCart = "UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$clientName' AND item_id = '$productId'";
        if ($con->query($sqlUpdateCart) === TRUE) {
            echo "Item added to cart successfully!";
        } else {
            echo "Error: " . $sqlUpdateCart . "<br>" . $con->error;
        }
    } else {
        // Insert item into the cart if not already present
        $sqlAddToCart = "INSERT INTO cart (user_id, item_id, quantity) VALUES ('$clientName', '$productId', '$quantity')";
        if ($con->query($sqlAddToCart) === TRUE) {
            echo "Item added to cart successfully!";
        } else {
            echo "Error: " . $sqlAddToCart . "<br>" . $con->error;
        }
    }
    // Increment the cart item counter in the session
    $_SESSION['cartItemCount']++;
}    


// Fetch product information
$sqlProducts = "SELECT * FROM items";
$resultProducts = $con->query($sqlProducts);

// Fetch skincare products
$sqlSkincareProducts = "SELECT * FROM items WHERE cat_id = '1'";
$resultSkincareProducts = $con->query($sqlSkincareProducts);

// Fetch makeup products
$sqlMakeupProducts = "SELECT * FROM items WHERE cat_id = '2'";
$resultMakeupProducts = $con->query($sqlMakeupProducts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .cosmet {
            float: left;
            height: 60px;
            width: 60px;
            border-radius: 28px;
        }

        h1 {
            font-family: Georgia;
            text-align: center;
        }

        h2 {
            font-family: times new roman;
        }

        body {
            background-color: antiquewhite;
            margin: 10px;
            padding: 10px;
        }

        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }

        .button-container {
            display: flex;
            flex-direction: column;
        }

        .quantity-input {
            width: 60px;
            margin-bottom: 10px; /* Adjust as needed */
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .btn-container button {
            flex-grow: 1;
            margin: 0 5px; /* Adjust as needed */
        }
        #buy{
            width:100%;
            float: right;
            margin-top:20px;
        }
    </style>
</head>

<body id="userpage">
<nav class="navbar bg-body-primary">
    <div class="container-fluid">
        <a class="navbar-brand"><img src="../img/logo.png" class="cosmet"></a>
        <form class="d-flex" method="get" action="search.php">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <a href="cart.php" class="btn btn-outline-dark">
            <i class="fas fa-shopping-cart"> </i>
            <?php echo ($_SESSION['cartItemCount'] > 0) ? "<span class='badge bg-danger'>{$_SESSION['cartItemCount']}</span>" : ''; ?>
        </a>
        <a href="orders.php" class="btn btn-outline-dark">
            <i class="fas fa-file-alt"></i></a>
        <a class="btn btn-success" href="logout.php">Log out</a>
    </div>
</nav>
    <hr>
    <h1>Hello, <?php echo $clientName;?></h1><hr>
    <div class="container-fluid">
        <div class="row">
            <!-- Display Bundles/Deals -->
        <h2>Bundles/Deals</h2>
        <?php
        if ($resultBundleProducts->num_rows > 0) {
            while ($row = $resultBundleProducts->fetch_assoc()) {
                echo "<div class='col-md-3 card-small'>";
                echo "<div class='card'>";
                echo "<img src='../img/{$row['item_img']}' class='card-img-top' alt='Product Image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$row['item_name']}</h5>";
                echo "<p class='card-text'>{$row['item_desc']}</p>";
                echo "<p class='card-text'>\${$row['item_price']}</p>";
                echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                echo "<div class='button-container'>";
                echo "<label class='mr-2'>Quantity:</label>";
                echo "<input type='number' name='quantity' class='quantity-input' min='1' required>";
                echo "<div class='btn-container'>";
                echo "<button type='submit' name='addToCart' class='btn btn-success'>Add to Cart</button>";
                echo "</div>";
                echo "</div>";
                echo "</form>";
                // Add "Buy Now" button
                echo "<form method='post' action='payment.php'>"; // Assuming payment.php is your payment page
                echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                echo "<input type='hidden' name='quantity' value='1'>"; // Default quantity is 1, you can adjust as needed
                echo "<button type='submit' name='buyNow' class='btn btn-primary' id='buy'>Buy Now</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No bundles/deals available</p>";
        }
        ?>
    </div><hr>
        <div class="row">
                <h2>Skincare Products</h2>
                <?php
                if ($resultSkincareProducts->num_rows > 0) {
                    while ($row = $resultSkincareProducts->fetch_assoc()) {
                        echo "<div class='col-md-3 card-small'>";
                        echo "<div class='card'>";
                        echo "<img src='../img/{$row['item_img']}' class='card-img-top' alt='Product Image'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$row['item_name']}</h5>";
                        echo "<p class='card-text'>{$row['item_desc']}</p>";
                        echo "<p class='card-text'>\${$row['item_price']}</p>";
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                        echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                        echo "<div class='button-container'>";
                        echo "<label class='mr-2'>Quantity:</label>";
                        echo "<input type='number' name='quantity' class='quantity-input' min='1' required>";
                        echo "<div class='btn-container'>";
                        echo "<button type='submit' name='addToCart' class='btn btn-success' >Add to Cart</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</form>";
                        // Add "Buy Now" button
                        echo "<form method='post' action='payment.php'>"; // Assuming payment.php is your payment page
                        echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                        echo "<input type='hidden' name='quantity' value='1'>"; // Default quantity is 1, you can adjust as needed
                        echo "<button type='submit' name='buyNow' class='btn btn-primary' id='buy'>Buy Now</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No skincare products available</p>";
                }
                ?>
            </div><hr>
            <div class="row">
                <h2>Makeup Products</h2>
                <?php
                if ($resultMakeupProducts->num_rows > 0) {
                    while ($row = $resultMakeupProducts->fetch_assoc()) {
                        echo "<div class='col-md-3 card-small'>";
                        echo "<div class='card'>";
                        echo "<img src='../img/{$row['item_img']}' class='card-img-top' alt='Product Image'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>{$row['item_name']}</h5>";
                        echo "<p class='card-text'>{$row['item_desc']}</p>";
                        echo "<p class='card-text'>\${$row['item_price']}</p>";
                        echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                        echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                        echo "<div class='button-container'>";
                        echo "<label class='mr-2'>Quantity:</label>";
                        echo "<input type='number' name='quantity' class='quantity-input' min='1' required>";
                        echo "<div class='btn-container'>";
                        echo "<button type='submit' name='addToCart' class='btn btn-success'>Add to Cart</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</form>";
                        // Add "Buy Now" button
                        echo "<form method='post' action='payment.php'>"; // Assuming payment.php is your payment page
                        echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                        echo "<input type='hidden' name='quantity' value='1'>"; // Default quantity is 1, you can adjust as needed
                        echo "<button type='submit' name='buyNow' class='btn btn-primary' id='buy'>Buy Now</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No makeup products available</p>";
                }
                ?>
            </div>
        </div>

    <script src="../js/bootstrap.js"></script>
</body>

</html>