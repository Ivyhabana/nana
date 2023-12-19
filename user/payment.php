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

// Fetch user information including address and contact
$sqlUser = "SELECT `user_id`, `user_fullname`, `username`, `user_email_address`, `address`, `contact`
            FROM `users`
            WHERE `username` = '$clientName'";
$resultUser = $con->query($sqlUser);

// Check if user information is found
if ($resultUser->num_rows > 0) {
    $userInfo = $resultUser->fetch_assoc();
} else {
    // Handle the case where user information is not found
    echo "User information not found.";
    exit();
}

// Check if the item ID is set in the URL
if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];

    // Fetch the specific item for payment, including item_img
    $sqlItem = "SELECT i.item_id, i.item_name, i.item_price, i.item_qty, i.item_img
                FROM items i
                WHERE i.item_id = '$productId'";
    $resultItem = $con->query($sqlItem);

    // Display the item details for payment
    if ($resultItem->num_rows > 0) {
        $item = $resultItem->fetch_assoc();
    } else {
        // Handle the case where the item is not found
        echo "Item not found.";
        exit();
    }
} else {
    // Redirect back to the cart page if the item ID is not set
    header("Location: cart.php");
    exit();
}

// Handle placing the order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['placeOrder'])) {
    // Get address and contact from the form
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    // Check if quantity is set in the form
    if (isset($_POST['quantity'])) {
        $ordqty = $_POST['quantity'];
    }

    // Calculate the total price of the order
    $totalPrice = $item['item_price'] * $ordqty;

    // Insert order into the database with updated ord_price
    $sqlOrder = "INSERT INTO orders (user_id, item_id, ord_qty, ord_price, address, contact) 
                 VALUES ('$clientName', '{$item['item_id']}', '$ordqty', '$totalPrice', '$address', '$contact')";

    if ($con->query($sqlOrder) === TRUE) {
        // Update the item quantity in the items table
        $newQuantity = $item['item_qty'] - $ordqty; // Assuming each order reduces the quantity
        $sqlUpdateQuantity = "UPDATE items SET item_qty = $newQuantity WHERE item_id = '{$item['item_id']}'";
        $con->query($sqlUpdateQuantity);

        // Delete the item from the cart after placing the order
        $sqlDeleteFromCart = "DELETE FROM cart WHERE user_id = '$clientName' AND item_id = '{$item['item_id']}'";
        $con->query($sqlDeleteFromCart);

        echo "Order placed successfully!<br>";
        echo "Total Price: $" . number_format($totalPrice, 2); // Display total price
        // Redirect back to the cart page if the item ID is not set
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sqlOrder . "<br>" . $con->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: antiquewhite;
            margin: 10px;
            padding: 10px;
        }

        .cosmet {
            float: left;
            height: 60px;
            width: 60px;
            border-radius: 28px;
        }

        .cart-item {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .total {
            font-weight: bold;
            margin-top: 10px;
        }

        .user-info {
            margin-top: 10px;
        }

        .btn-container {
            margin-top: 10px;
        }

        .btn-container button {
            margin-right: 10px;
        }

        .pic {
            height: 200px;
            width: 200px;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-body-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="../img/logo.png" class="cosmet"></a>
            <a class="btn btn-success" href="logout.php">Log out</a>
        </div>
    </nav>
    <hr>
    <h1>Payment Details</h1>
    <div class="container-fluid">
        <div class='cart-item'>
            <img src="../img/<?php echo $item['item_img']; ?>" alt="Product Image" class="pic">
            <h4><?php echo $item['item_name']; ?></h4>
            <p>Price: $<?php echo $item['item_price']; ?></p>
        </div>

        <!-- Display user information -->
        <div class='user-info'>
            <p>User: <?php echo $userInfo['user_fullname']; ?></p>
            <p>Email: <?php echo $userInfo['user_email_address']; ?></p>
        </div>

        <!-- Payment and Continue Shopping Buttons -->
        <div class='btn-container'>
            <form method='post' action='payment.php'>
                <input type='hidden' name='productId' value='<?php echo $item['item_id']; ?>'>
                <input type='hidden' name='itemPrice' value='<?php echo $item['item_price']; ?>'>
                <input type='hidden' name='itemQuantity' value='<?php echo $ordqty; ?>'>
                <!-- Add input fields for address, contact, and quantity -->
                <label for='address'>Address:</label>
                <input type='text' name='address' id='address' value='<?php echo $userInfo['address']; ?>' required>
                <label for='contact'>Contact:</label>
                <input type='text' name='contact' id='contact' value='<?php echo $userInfo['contact']; ?>' required>
                <label for='quantity'>Quantity:</label>
                <input type='number' name='quantity' id='quantity' value='<?php echo $_POST['quantity']; ?>' required>
                <button type='submit' name='placeOrder' class='btn btn-success'>Place Order</button>
            </form>
            <a href='index.php' class='btn btn-primary'>Continue Shopping</a>
        </div>
    </div>

    <script src="../js/bootstrap.js"></script>
</body>

</html>
