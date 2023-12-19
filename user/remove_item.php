<?php
session_start();
include("../dtbs.php");
include("../functions.php");

if (isset($_POST['removeItem'])) {
    $cartId = $_POST['cartId'];

    // Perform the deletion from the cart
    $sqlDelete = "DELETE FROM cart WHERE cart_id = '$cartId'";
    $resultDelete = $con->query($sqlDelete);

    if ($resultDelete) {
        echo "<script>alert('Item removed from the cart.');</script>";
    } else {
        echo "<script>alert('Failed to remove item from the cart.');</script>";
    }

    // Redirect back to the cart page
    header("Location: cart.php");
    exit();
}
?>
