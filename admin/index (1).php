<?php
session_start();
include("../dtbs.php");
include("../functions.php");

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch the name of the admin
$sqlAdminName = "SELECT username FROM users WHERE user_type = 'A'";
$resultAdminName = $con->query($sqlAdminName);
$adminName = ($resultAdminName && $resultAdminName->num_rows > 0) ? $resultAdminName->fetch_assoc()['username'] : '';

// Fetch users from the database
$sqlUsers = "SELECT user_id, username, user_status FROM users";
$resultUsers = $con->query($sqlUsers);

// Fetch pending orders from the database
$sqlPendingOrders = "SELECT order_id, user_id, item_id, ord_qty, ord_price FROM orders WHERE status = 'pending'";
$resultPendingOrders = $con->query($sqlPendingOrders);

// Fetch delivered orders from the database
$sqlSales = "SELECT order_id, item_id, ord_qty, ord_price FROM orders WHERE status = 'delivered'";
$resultSales = $con->query($sqlSales);

// Calculate the sum of prices
$sqlSumPrices = "SELECT SUM(ord_price) AS total_sales FROM orders WHERE status = 'delivered'";
$resultSumPrices = $con->query($sqlSumPrices);
$totalSales = ($resultSumPrices && $resultSumPrices->num_rows > 0) ? $resultSumPrices->fetch_assoc()['total_sales'] : 0;

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body, table{
            margin: 10px;
            padding: 10px;
        }

        footer {
            text-align: center;
            color: black;
            height: 50px;
        }

        h1 {
            font-family: Georgia;
        }

        h2 {
            font-family: times new roman;
            text-align: center;
        }

        .green-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            float: right;
        }
    </style>
</head>
<body style="background-color: antiquewhite;">
    <nav>
        <?php include("nav.php"); ?>
    </nav>
    <section>
        <div class="container">
            <h1>Hello, Admin <?php echo $adminName ?></h1>
            <hr>

            <!-- Pending Orders Table -->
            <div class="row">
                <div class="col">
                    <h2>Pending Orders</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">User</th>
                                <th scope="col">Item ID</th>
                                <th scope="col">Order Quantity</th>
                                <th scope="col">Order Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultPendingOrders && $resultPendingOrders->num_rows > 0) {
                                while ($pendingOrder = $resultPendingOrders->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$pendingOrder['order_id']}</td>";
                                    echo "<td>{$pendingOrder['user_id']}</td>";
                                    echo "<td>{$pendingOrder['item_id']}</td>";
                                    echo "<td>{$pendingOrder['ord_qty']}</td>";
                                    echo "<td>{$pendingOrder['ord_price']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No pending orders found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- User and Admin Table -->
            <div class="row">
                <div class="col-6">
                    <h2>User and Admin Table</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">User ID</th>
                                <th scope="col">Username</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultUsers && $resultUsers->num_rows > 0) {
                                while ($user = $resultUsers->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<th scope='row'>{$user['user_id']}</th>";
                                    echo "<td>{$user['username']}</td>";
                                    echo "<td>{$user['user_status']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Sales Data Table -->
                <div class="col-6">
                    <h2>Sales Data</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Order ID</th>
                                <th scope="col">Item ID</th>
                                <th scope="col">Order Quantity</th>
                                <th scope="col">Order Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($resultSales && $resultSales->num_rows > 0) {
                                while ($sale = $resultSales->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$sale['order_id']}</td>";
                                    echo "<td>{$sale['item_id']}</td>";
                                    echo "<td>{$sale['ord_qty']}</td>";
                                    echo "<td>{$sale['ord_price']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No sales data found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

                    <!-- Sum of Prices -->
                    <p class='green-button'>Total Sales: $<?php echo $totalSales; ?></p>
                </div>
            </div>
        </div>
    </section>
    <footer>
        &copy; <?php echo date("Y"); ?> Admin Dashboard
    </footer>
    <script src="../js/bootstrap.js"></script>
</body>
</html>