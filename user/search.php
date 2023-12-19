<?php
include("../dtbs.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <!-- Add Bootstrap CSS link here -->
    <link rel="stylesheet" href="../css/bootstrap.css">
    <style>
        body{
            background-color:antiquewhite;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <?php
    if (isset($_GET['query'])) {
        $searchQuery = $_GET['query'];

        // Implement your search logic here, e.g., query the database
        $sql = "SELECT * FROM items WHERE item_name LIKE '%$searchQuery%' OR item_desc LIKE '%$searchQuery%' or keywords LIKE '%$searchQuery%'";
        $result = $con->query($sql);

        // Display search results
        if ($result->num_rows > 0) {
            echo "<h2 class='mt-4'>Search Results for '$searchQuery' </h2><hr>";
            echo "<div class='row'>";
            while ($row = $result->fetch_assoc()) {
                // Display each search result with an option to add to cart
                echo "<div class='col-md-4 mb-4'>";
                echo "<div class='card'>";
                echo "<img src='../img/{$row['item_img']}' class='card-img-top' alt='Product Image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>{$row['item_name']}</h5>";
                echo "<p class='card-text'>{$row['item_desc']}</p>";
                echo "<p class='card-text'>\${$row['item_price']}</p>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No results found.</p>";
        }
    }
    ?>
</div>

<!-- Add Bootstrap JS and Popper.js links here -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
