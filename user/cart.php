
<?php
session_start();
include("../dtbs.php");
include("../functions.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch client name from the session
$clientName = $_SESSION['username'];

// Fetch cart items for the current user
$sqlCart = "SELECT c.cart_id, i.item_id, i.item_name, i.item_price, i.item_img, c.quantity 
            FROM cart c
            INNER JOIN items i ON c.item_id = i.item_id
            WHERE c.user_id = '$clientName'";
$resultCart = $con->query($sqlCart);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: antiquewhite;
            margin: 10px;
            padding: 10px;
        }

        .cart-item {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .total {
            font-weight: bold;
        }

        /* Adjusted styling for buttons */
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .btn-container button {
            flex: 0 0 48%; /* Adjust button width */
        }

        .cart-item img {
            max-width: 100px; /* Adjust the maximum width of the product image */
            max-height: 100px; /* Adjust the maximum height of the product image */
        }
        .cosmet{
        float:left;
        height: 60px;
        width:60px;
        border-radius:28px;
    }
    </style>
</head>

<body>
    <nav class="navbar bg-body-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="../img/logo.png" class="cosmet"></img></a>
            <a class="btn btn-success" href="logout.php">Log out</a>
        </div>
    </nav>
    <hr>
    <h1>Your Shopping Cart</h1>
    <div class="container-fluid">
        <?php
        if ($resultCart->num_rows > 0) {
            while ($row = $resultCart->fetch_assoc()) {
                echo "<div class='cart-item'>";
                echo "<img src='../img/{$row['item_img']}' alt='{$row['item_name']}' />";
                echo "<h4>{$row['item_name']}</h4>";
                echo "<p>Price: \${$row['item_price']}</p>";
                echo "<p>Quantity: {$row['quantity']}</p>";
                
                // Display total price for the item based on quantity
                $totalPrice = $row['item_price'] * $row['quantity'];
                echo "<p>Total: \$$totalPrice</p>";
                
                echo "<form method='post' action='remove_item.php'>";
                echo "<input type='hidden' name='cartId' value='{$row['cart_id']}'>";
                echo "<button type='submit' name='removeItem' class='btn btn-danger mb-2'>Remove</button>";
                echo "</form>";
                echo "<form method='post' action='payment.php'>";
                echo "<input type='hidden' name='cartId' value='{$row['cart_id']}'>";
                echo "<input type='hidden' name='productId' value='{$row['item_id']}'>";
                echo "<input type='hidden' name='quantity' value='{$row['quantity']}'>";
                echo "<button type='submit' name='buyNow' class='btn btn-success'>Buy Now</button>";
                echo "</form>";
                echo "</div>";
            }

            // Calculate total price
            $sqlTotal = "SELECT SUM(i.item_price * c.quantity) AS total
                         FROM cart c
                         INNER JOIN items i ON c.item_id = i.item_id
                         WHERE c.user_id = '$clientName'";
            $resultTotal = $con->query($sqlTotal);
            $total = $resultTotal->fetch_assoc()['total'];

            echo "<div class='total'>";
            echo "Total: \$$total";
            echo "</div>";

            // Buy Now and Continue Shopping Buttons

            echo "<a href='index.php' class='btn btn-primary'>Continue Shopping</a>";

        } else {
            echo "<p>Your cart is empty</p>";
        }
        ?>
    </div>

    <script src="../js/bootstrap.js"></script>
</body>

</html>