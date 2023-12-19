<?php
include_once("dtbs.php");
include("functions.php");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);  // Escape input to prevent SQL injection
    $password = $_POST['password'];

    $sql_check_user = "SELECT * FROM users WHERE `username` = '$username'";
    $user_result = mysqli_query($con, $sql_check_user);

    if (mysqli_num_rows($user_result) == 1) {
        $row = mysqli_fetch_assoc($user_result);

        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['username'] = $username;

            if ($row['user_type'] == 'A') {
                header("location: admin/index.php");
                exit();
            } else if ($row['user_type'] == 'U') {
                header("location: users/index.php");
                exit();
            } else {
                header("location: login.php?error=404");
                exit();
            }
        } else {
            header("location:login.php?error=invalid_password");
            exit();
        }
    } else {
        header("location: index.php?error=user_not_found");
        exit();
    }
}
?>
