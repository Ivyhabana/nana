<?php
session_start();
include_once("dtbs.php");
include("functions.php");


// Fetch all products
$sqlSelectProducts = "SELECT * FROM items";
$resultProducts = mysqli_query($con, $sqlSelectProducts);
// Check for errors in the query
if (!$resultProducts) {
    die("Error in SQL query for products: " . mysqli_error($con));
}

// Fetch products with category 3 (sets/bundles)
$sqlSelectBundles = "SELECT * FROM items WHERE cat_id = 3";
$resultBundles = mysqli_query($con, $sqlSelectBundles);

// Check for errors in the query
if (!$resultBundles) {
    die("Error in SQL query for bundles: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Welcome</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
 <style>
        .cosmet {
            float: left;
            height: 60px;
            width: 60px;
            border-radius: 28px;
        }

        body {
            background-color: antiquewhite;
            margin: 10px;
            padding: 10px;
        }

        footer {
            background-color: antiquewhite;
            color: black;
            height: 50px;
        }

        .card {
            margin-bottom: 20px;
        }

        h1 {
            font-family: Georgia;
        }

        #drop {
            background-color: transparent;
            text: black;
        }

        #drop:hover {
            background-color: beige;
            text: black;
        }
        #pics{
            height:250px;
            width:250px;
        }
    </style>

<body>
    <nav class="navbar bg-body-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="img/logo.png" class="cosmet"></a>

            <form class="d-flex" method="get" action="users/search.php">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="query">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
            <!-- Corrected dropdown structure -->
            <div class="btn-group">
                <!-- Updated Log-in button -->
                <button type="button" class="btn btn-outline-info" onclick="window.location.href='login.php'">Log-in</button>
                <button type="button" class="btn btn-outline-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                <ul class="dropdown-menu" id="drop">
                    <li><a class="dropdown-item" href="registrationform.php">Sign-up</a></li>
                    <li><a class="dropdown-item" href="#aboutus.html">About Us</a></li>
                    <li><a class="dropdown-item" href="index.php">Exit</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <hr>
    <div class="container-fluid">
        <div class="row">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Home</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Bundles/Deals</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
        <!-- Home Tab -->
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <div class="row">
                <h1 class="text-success font-weight-bold mt-5">Featured</h1>
                <?php
                while ($row = mysqli_fetch_assoc($resultProducts)) {
                ?>
                    <div class="col-3 mt-5">
                        <a class="btn btn-outline-dark" data-bs-toggle="collapse" href="#<?php echo $row['item_id']; ?>" role="button" aria-expanded="false" aria-controls="<?php echo $row['item_id']; ?>">
                            <img src="img/<?php echo $row['item_img']; ?>" class="card-img-top" id="pics" alt="...">
                        </a>
                        <div class="collapse" id="<?php echo $row['item_id']; ?>">
                            <div class="card card-body">
                                <h5 class="card-title"><?php echo $row['item_name']; ?></h5>
                                <p class="card-text"><?php echo $row['item_desc']; ?></p>
                                <p class="card-text">Price: $<?php echo $row['item_price']; ?></p>
                                <a href="login.php" class="btn btn-primary">BUY</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

        <!-- Bundles/Deals Tab -->
        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
            <h1 class="text-success font-weight-bold mt-5">Bundles/Deals</h1>
            <div class="row">
                <?php
                while ($bundle = mysqli_fetch_assoc($resultBundles)) {
                ?>
                    <div class="col-3 mt-5">
                        <a class="btn btn-outline-dark" data-bs-toggle="collapse" href="#bundle_<?php echo $bundle['item_id']; ?>" role="button" aria-expanded="false" aria-controls="bundle_<?php echo $bundle['item_id']; ?>">
                            <img src="img/<?php echo $bundle['item_img']; ?>" class="card-img-top" id="pics" alt="...">
                        </a>
                        <div class="collapse" id="bundle_<?php echo $bundle['item_id']; ?>">
                            <div class="card card-body">
                                <h5 class="card-title"><?php echo $bundle['item_name']; ?></h5>
                                <p class="card-text"><?php echo $bundle['item_desc']; ?></p>
                                <p class="card-text">Price: $<?php echo $bundle['item_price']; ?></p>
                                <a href="login.php" class="btn btn-primary">BUY</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

        </div>
    </div>
</body>

<footer>
    <center> @Cosme+ 2023</center>
</footer>

</html>
