<?php
session_start();
include("../dtbs.php");
include("../functions.php");

// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags($input));
}

function addProduct($con, $name, $description, $price, $category, $quantity, $image, $keywords)
{
    $name = sanitizeInput($name);
    $description = sanitizeInput($description);
    $price = sanitizeInput($price);
    $category = sanitizeInput($category);
    $quantity = sanitizeInput($quantity);
    $image = sanitizeInput($image);
    $keywords = sanitizeInput($keywords);

    // Upload image file
    $targetDirectory = "../img/";  // Adjust the path as needed
    $targetFile = $targetDirectory . basename($image);

    $image_file_type = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        return;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        return;
    }

    // Allow only certain file formats
    if ($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg" && $image_file_type != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        return;
    }

    // Move the uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
       // Insert product into the database with keywords
    $sql = "INSERT INTO items (item_name, item_desc, keywords, item_price, cat_id, item_qty, item_img) 
            VALUES ('$name', '$description', '$keywords', '$price', '$category', '$quantity', '$image')";

        if ($con->query($sql) === TRUE) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
    
}

// Function to handle product deletion
function deleteProduct($con, $productId)
{
    $productId = sanitizeInput($productId);

    $sql = "DELETE FROM items WHERE item_id = '$productId'";

    if ($con->query($sql) === TRUE) {
        echo "Product deleted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

// Function to handle product update
function updateProduct($con, $productId, $price, $quantity)
{
    $productId = sanitizeInput($productId);
    $price = sanitizeInput($price);
    $quantity = sanitizeInput($quantity);

    $sql = "UPDATE items SET item_price = '$price', item_qty = '$quantity' WHERE item_id = '$productId'";

    if ($con->query($sql) === TRUE) {
        echo "Product updated successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addProduct'])) {
    addProduct($con, $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $_POST['quantity'], $_FILES["image"]["name"], $_POST['keywords']);
}

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteProduct'])) {
    deleteProduct($con, $_POST['productId']);
}

// Handle product update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProduct'])) {
    updateProduct($con, $_POST['productId'], $_POST['price'], $_POST['quantity']);
}

// Fetch product information
$sql = "SELECT * FROM items i
        JOIN category c ON c.cat_id=i.cat_id" ;
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Manage Products</title>
</head>

<style>
    header {
        color: black;
        padding: 10px;
        text-align: center;
        }

    body {
        margin: 10px;
        padding: 10px;
    }

    footer {
        text-align: center;
        color: black;
        height: 50px;
    }
</style>

<body style="background-color: antiquewhite;">
    <nav>
        <?php include("nav.php"); ?><hr>
    </nav>
<header> <h1> Manage Products</header>
    <h2>Product List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category ID</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['item_id'] . "</td>";
                echo "<td>" . $row['item_name'] . "</td>";
                echo "<td>" . $row['cat_name'] . "</td>";
                echo "<td>" . $row['item_price'] . "</td>";
                echo "<td>" . $row['item_qty'] . "</td>";
                echo "<td>
                        <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>
                            <input type='hidden' name='productId' value='" . $row['item_id'] . "'>
                            <button type='submit' name='deleteProduct' class='btn btn-danger'>Delete</button>
                            <a href='edit_products.php?id=" . $row['item_id'] . "' class='btn btn-warning'>
                                <i class='fas fa-edit'></i>
                            </a>
                        </form>
                    </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <h2>Add Product</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" required>
                <div class="invalid-feedback">Please enter a name.</div>
            </div>
            <div class="col-md-6">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" name="description" required></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" required>
                <div class="invalid-feedback">Please enter a price.</div>
            </div>
            <div class="col-md-6">
                <label for="keywords" class="form-label">Keywords (comma-separated):</label>
                <input type="text" class="form-control" name="keywords">
            </div>
            <div class="col-md-6">
                <label for="category" class="form-label">Category:</label>
                <select class="form-select" name="category" required>
                    <option value="1">Skincare</option>
                    <option value="2">Makeup</option>
                    <option value="3">Sets</option>
                </select>
                <div class="invalid-feedback">Please select a category.</div>
            </div>
            <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" required>
                <div class="invalid-feedback">Please enter a quantity.</div>
            </div>
            <div class="col-md-6">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control" name="image" accept="image/*" required>
                <div class="invalid-feedback">Please choose an image.</div>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" name="addProduct" class="btn btn-success">Add Product</button>
            </div>
        </div>
    </form>

    <script src="../js/bootstrap.js"></script>
    <script>
        // Bootstrap validation
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>

</html>