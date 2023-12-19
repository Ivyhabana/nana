<?php
session_start();
include("../dtbs.php");
include("../functions.php");

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
}

// Fetch client name from the session
$clientName = $_SESSION['username'];

// Fetch sales data
$sqlSales = "SELECT * FROM orders WHERE status = 'delivered'";
$resultSales = $con->query($sqlSales);

// Calculate the sum of prices
$sqlSumPrices = "SELECT SUM(ord_price) AS total_sales FROM orders WHERE status = 'delivered'";
$resultSumPrices = $con->query($sqlSumPrices);
$totalSales = ($resultSumPrices && $resultSumPrices->num_rows > 0) ? $resultSumPrices->fetch_assoc()['total_sales'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales and Inventory</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body {
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
            text-align: center;
        }

        h2 {
            font-family: times new roman;
            text-align: center;
            margin-top: 20px;
        }

        table {
            margin-top: 20px;
        }

        p {
            font-size: 18px;
            margin-top: 20px;
            text-align: center;
        }

        .green-button {
            float: right;
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>

<body style="background-color:antiquewhite;">
    <nav>
        <?php include("nav.php"); ?>
    </nav>
    <section class="container-fluid">
        <h1>Sales and Inventory</h1>
        <hr>

        <h2>Sales Data</h2>
        <div class="table-responsive">
            <table class="table table-striped">
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
                    if ($resultSales && $resultSales->num_rows > 0) {
                        while ($sale = $resultSales->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $sale["order_id"] . "</td>";
                            echo "<td>" . $clientName . "</td>";
                            echo "<td>" . $sale["item_id"] . "</td>";
                            echo "<td>" . $sale["ord_qty"] . "</td>";
                            echo "<td>" . $sale["ord_price"] . "</td>";
                            echo "</tr>";
                        }
                        // Output the sum of prices
                        echo "<tr>";
                        echo "<td></td>"; // Empty cell for "Item ID" column
                        echo "<td colspan='4' class='text-right'><p class='green-button'>Total Sales: $" . $totalSales . "</p></td>";
                        echo "</tr>";
                    } else {
                        echo "<tr><td colspan='5'>No sales data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <footer class="mt-auto">
        &copy; 2023 Admin Dashboard
    </footer>
    <script src="../js/bootstrap.js"></script>
</body>

</html>
