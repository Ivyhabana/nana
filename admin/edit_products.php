<?php
session_start();
include("../dtbs.php");
include("../functions.php");

// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags($input));
}

// Function to get product details by ID
function getProductDetails($con, $productId)
{
    $productId = sanitizeInput($productId);

    $sql = "SELECT * FROM items WHERE item_id = '$productId'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

// Function to update product details including keywords
function updateProduct($con, $productId, $name, $description, $keywords, $price, $quantity, $image)
{
    $productId = sanitizeInput($productId);
    $name = sanitizeInput($name);
    $description = sanitizeInput($description);
    $keywords = sanitizeInput($keywords);
    $price = sanitizeInput($price);
    $quantity = sanitizeInput($quantity);
    $image = sanitizeInput($image);

    // Check if a new image is provided
    if ($_FILES['new_image']['name'] != "") {
        // Upload new image file
        $targetDirectory = "../img/";  // Adjust the path as needed
        $targetFile = $targetDirectory . basename($_FILES["new_image"]["name"]);

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["new_image"]["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            return;
        }

        // Check file size
        if ($_FILES["new_image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            return;
        }

        // Allow only certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            return;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $targetFile)) {
            // Update product with new image
            $sql = "UPDATE items SET item_name = '$name', item_desc = '$description', keywords = '$keywords', item_price = '$price', item_qty = '$quantity', item_img = '$image' WHERE item_id = '$productId'";
        } else {
            echo "Error uploading image.";
            return;
        }
    } else {
        // Update product without changing the image
        $sql = "UPDATE items SET item_name = '$name', item_desc = '$description', keywords = '$keywords', item_price = '$price', item_qty = '$quantity' WHERE item_id = '$productId'";
    }

    if ($con->query($sql) === TRUE) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $con->error;
    }
}
// Handle form submission for product update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProduct'])) {
    $productId = $_POST['item_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_FILES["new_image"]["name"];

    updateProduct($con, $productId, $name, $description, $keywords, $price, $quantity, $image);
}

// Retrieve product details based on the ID from the query parameter
if (isset($_GET['id'])) {
    $productId = sanitizeInput($_GET['id']);
    $productDetails = getProductDetails($con, $productId);
} else {
    // Redirect to the product list page if the ID is not provided
    header("Location: manageproducts.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Edit Product</title>
</head>

<style>
    header {
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
    <h2>Edit Product</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="item_id" value="<?php echo $productId; ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" value="<?php echo $productDetails['item_name']; ?>" required>
                <div class="invalid-feedback">Please enter a name.</div>
            </div>
            <div class="col-md-6">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" name="description" required><?php echo $productDetails['item_desc']; ?></textarea>
                <div class="invalid-feedback">Please enter a description.</div>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" value="<?php echo $productDetails['item_price']; ?>" required>
                <div class="invalid-feedback">Please enter a price.</div>
            </div>
            <div class="col-md-6">
                <label for="keywords" class="form-label">Keywords:</label>
                <input type="text" class="form-control" name="keywords" value="<?php echo $productDetails['keywords']; ?>" required>
                <div class="invalid-feedback">Please enter keywords.</div>
            </div>
            <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" value="<?php echo $productDetails['item_qty']; ?>" required>
                <div class="invalid-feedback">Please enter a quantity.</div>
            </div>
            <div class="col-md-6">
                <label for="new_image" class="form-label">New Image:</label>
                <input type="file" class="form-control" name="new_image" accept="image/*">
                <div class="invalid-feedback">Please choose a valid image file.</div>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" name="updateProduct" class="btn btn-primary">Update Product</button>
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
