<?php
session_start();
include("../dtbs.php");
include("../functions.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch client name and user_id from the session
$clientName = $_SESSION['username'];

// Use a prepared statement to fetch orders for the current user with user details
$sqlOrders = "SELECT o.*, i.*, u.user_id as user_id, u.username, (o.ord_qty * i.item_price) as total_price
              FROM orders o
              JOIN items i ON o.item_id = i.item_id
              JOIN users u ON o.user_id = u.username
              WHERE o.user_id = ? AND o.status = ?";

$stmt = $con->prepare($sqlOrders);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order History</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
            background-color: antiquewhite;
            margin: 10px;
            padding: 10px;
        }

        .order-item {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .status-badge {
            font-size: 14px;
        }

        .badge-pending {
            background-color: yellow;
            color: black;
        }

        .badge-cancelled {
            background-color: red;
            color: white;
        }

        .badge-on-the-way {
            background-color: lightblue;
            color: black;
        }

        .badge-delivered {
            background-color: green;
            color: white;
        }

        .pic {
            height: 200px;
            width: 200px;
        }

        .cosmet {
            float: left;
            height: 60px;
            width: 60px;
            border-radius: 28px;
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
    <h1>Order History</h1>
    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending">Pending</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="onthe-way-tab" data-bs-toggle="tab" href="#on_the_way">On the Way</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="delivered-tab" data-bs-toggle="tab" href="#delivered">Delivered</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled">Cancelled</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabsContent">
        <?php
        // Iterate through the switch cases
        $statusArray = ['pending', 'cancelled', 'on_the_way', 'delivered'];
        foreach ($statusArray as $status) {
            // Fetch orders for the current status
            $stmt->bind_param("ss", $clientName, $status);
            $stmt->execute();
            $resultStatus = $stmt->get_result();

            echo "<div class='tab-pane fade" . ($status === 'pending' ? ' show active' : '') . "' id='{$status}'>";
            if ($resultStatus->num_rows > 0) {
                while ($order = $resultStatus->fetch_assoc()) {
                    echo "<div class='order-item'>";
                    echo "<p>Order# {$order['order_id']}</p>";
                    echo "<p>User ID: {$order['user_id']}</p>";
                    echo "<p>User: {$order['username']}</p>";
                    echo "<p>Status: {$order['status']}</span></p>";
                    echo "<img src='../img/{$order['item_img']}' alt='Item Image' class='pic'>";
                    echo "<p>Item Name: {$order['item_name']}</p>";
                    echo "<p>Quantity: {$order['ord_qty']}</p>";
                    echo "<p>Price per item: {$order['item_price']}</p>";
                    echo "<p>Total Price: {$order['total_price']}</p>";
                    echo "<p>Delivery Address: {$order['address']}</p>";
                    echo "<p>Contact: {$order['contact']}</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No {$status} orders found.</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
    <script src="../js/bootstrap.js"></script>
</body>
</html>
