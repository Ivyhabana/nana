<?php
session_start();
include("../dtbs.php"); // Adjust the path accordingly

// Check if the user is logged in (you can customize this check based on your authentication logic)
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch client name and user_id from the session
$clientName = $_SESSION['username'];

// Use a prepared statement to fetch orders for the current user with user details
$sqlOrders = "SELECT o.*, i.*, u.user_id as user_id, u.username 
              FROM orders o
              JOIN items i ON o.item_id = i.item_id
              JOIN users u ON o.user_id = u.username
              WHERE o.user_id = ?
              ORDER BY o.order_date DESC"; // Order by order_date in descending order

$targetUserId = "$clientName"; // replace with the actual user_id
// Update order status if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["order_id"]) && isset($_POST["new_status"])) {
        $order_id = $_POST["order_id"];
        $new_status = $_POST["new_status"];

        // Update the order status in the database
        $updateSql = "UPDATE `orders` SET `status` = ? WHERE `order_id` = ?";
        $stmt = $con->prepare($updateSql);
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST["delete_order"])) {
        $order_id = $_POST["delete_order"];

        // Delete the order from the database
        $deleteSql = "DELETE FROM `orders` WHERE `order_id` = ?";
        $stmt = $con->prepare($deleteSql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch data for each status
$statuses = ['pending', 'on_the_way', 'delivered', 'cancelled'];

foreach ($statuses as $status) {
    $statusSql = "SELECT * FROM orders 
                  WHERE user_id = ? AND status = ?
                  ORDER BY order_date DESC";
    $stmt = $con->prepare($statusSql);
    $stmt->bind_param("ss", $clientName, $status);
    $stmt->execute();
    $resultStatus = $stmt->get_result();
    ${$status . 'Orders'} = $resultStatus->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Orders</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: antiquewhite;
        }

        header {
        color: black;
        padding: 10px;
        text-align: center;
        }

        .container {
            margin-top: 20px;
            background-color: white; /* Set container background color if needed */
            padding: 20px; /* Add padding for better visual appearance */
            border-radius: 10px; /* Add border radius for rounded corners */
        }

    </style>
</head>

<body style="background-color: antiquewhite;">
    <?php include('nav.php');?>
    <header>
        <h1>Manage Orders</h1>
    </header>
    <div class="container">

        <?php foreach ($statuses as $status): ?>
            <h2><?php echo ucfirst($status); ?> Orders</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Item ID</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $statusOrders = ${$status . 'Orders'};
                    if (!empty($statusOrders)) {
                        foreach ($statusOrders as $order) {
                            echo "<tr>";
                            echo "<td>{$order['order_id']}</td>";
                            echo "<td>{$order['user_id']}</td>";
                            echo "<td>{$order['item_id']}</td>";
                            echo "<td>{$order['ord_qty']}</td>";
                            echo "<td>{$order['ord_price']}</td>";
                            echo "<td>{$order['address']}</td>";
                            echo "<td>{$order['contact']}</td>";
                            echo "<td>{$order['order_date']}</td>";
                            echo "<td>";
                            echo "<form method='post' class='mr-2'>";
                            echo "<input type='hidden' name='order_id' value='{$order['order_id']}'>";
                            echo "<select class='form-control' name='new_status'>";
                            foreach ($statuses as $optionStatus) {
                                echo "<option value='{$optionStatus}'" . ($optionStatus === $status ? ' selected' : '') . ">{$optionStatus}</option>";
                            }
                            echo "</select>";
                            echo "<button type='submit' class='btn btn-primary btn-sm mt-1'>Update</button>";
                            echo "</form>";

                            // Add delete button
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='delete_order' value='{$order['order_id']}'>";
                            echo "<button type='submit' class='btn btn-danger btn-sm mt-1'>Delete</button>";
                            echo "</form>";

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No {$status} orders found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

    <script src="../js/bootstrap.js"></script>
    <!-- Add your custom scripts if needed -->
</body>

</html>
