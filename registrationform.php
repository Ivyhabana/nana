<?php
session_start();
include_once("dtbs.php");
include("functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST["fullname"];
    $uname = $_POST["uname"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $contact = $_POST["contact"];
    $password = $_POST["pwd"];
    $conf_pwd = $_POST["conf_pwd"];

    // Perform basic validation
    if (empty($fullname) || empty($uname) || empty($email) || empty($address) || empty($contact) || empty($password) || empty($conf_pwd)) {
        echo "All fields are required.";
        exit;
    }

    // Validate password and confirm password match
    if ($password != $conf_pwd) {
        echo "Password and Confirm Password do not match.";
        exit;
    }

    // Hash the password (for security)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $query = "INSERT INTO users (user_fullname, username, user_email_address, address, contact, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);

    // Use the correct syntax for bind_param
    $stmt->bind_param("ssssss", $fullname, $uname, $email, $address, $contact, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful!";
        header("location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!-- Rest of your HTML code remains unchanged -->

<html>
<head>
    <meta charset="UTF-8">
    <title>register</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<style>
    body {
        background-image:url('https://scontent-ord5-2.xx.fbcdn.net/v/t1.15752-9/315517761_837090724265703_5991438036763085948_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=510075&_nc_eui2=AeEtzuZdw2vBvw9XrV1qE6nrYv3pYxRN3Kpi_eljFE3cqjCogVrsxeUOf8_8F4CKtuuixVgESbM4OYJj95_CBhNd&_nc_ohc=UXru7Og5E_YAX9cDkAQ&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent-ord5-2.xx&oh=03_AdSuDWu44e-AERZIxXVjoqc5XpajCoiIEyo7_to5P4hAZA&oe=659936B9');
        background-repeat:no-repeat;
        background-size: 100%;
        font-family: Georgia;
    }

    h1 {
        color: solid black;
        font-size: 50px;
        text-align: center;
        margin-top:50px;
    }
    #rawr {
        margin: 10px; /* Add some margin for better spacing */
        color:black;
    }
    #rawr:hover{
        background-color:black;
        color:antiquewhite;
    }
    #rwr {
        margin: 10px; /* Add some margin for better spacing */
        color:black;
    }
    #rwr:hover{
        background-color:black;
        color:antiquewhite;
    }
</style>
<body>
    <div class="container">
        <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
            <div class="col-md-3">
            <h1>Register</h1><br><br>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="" class="form-label"> Fullname
                            <input type="text" class="form-control" name="fullname">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label"> Username
                            <input type="text" class="form-control" name="uname">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">E-mail Address
                            <input type="email" class="form-control" name="email">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label"> Address
                            <input type="text" class="form-control" name="address">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label"> Contact
                            <input type="text" class="form-control" name="contact">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label"> Password
                            <input type="Password" class="form-control" name="pwd">
                        </label>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label"> Confirm Password
                            <input type="Password" class="form-control" name="conf_pwd">
                        </label>
                    </div>

                    <input type="submit" class="btn btn-outline-dark" name="register" id="rwr">
                    <a class="btn btn-outline-dark" id="rawr" href="login.php" role="button">
           Go to Login
        </a>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="js/bootstrap.js"></script>
</html>
