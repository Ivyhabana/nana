<?php
include_once("../dtbs.php");
include("../functions.php");
session_start();

// Check if a user is selected for deletion
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $action = $_GET['action'];
    $user_id = $_GET['user_id'];

    if ($action === 'delete') {
        // Fetch user data before deletion
        $sql = "SELECT * FROM users WHERE user_id = $user_id";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $deletedUser = $result->fetch_assoc();

            // Store the deleted user in a session variable or temporary array
            $_SESSION['deleted_users'][] = $deletedUser;

            // Delete the user from the 'users' table
            $deleteSql = "DELETE FROM users WHERE user_id = $user_id";
            if ($con->query($deleteSql) === TRUE) {
                echo "User deleted successfully";
            } else {
                echo "Error deleting user: " . $con->error;
            }
        }
    }
}

// Fetch users from the 'users' table
$sql = "SELECT user_id, username, user_type FROM users";
$result = $con->query($sql);

// Fetch deleted users from the session variable or temporary array
$deletedUsers = isset($_SESSION['deleted_users']) ? $_SESSION['deleted_users'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <title>Manage Users</title>
    <style>
        body {
            background-color: antiquewhite;
            margin: 10px;
            padding: 10px;
        }

        footer {
            color: black;
            height: 50px;
            text-align:center;
            position: relative;
            margin-top: 20px;
        }
        header {
        color: black;
        padding: 10px;
        text-align: center;
        }
    </style>
</head>
<body style="background-color: antiquewhite;">
    <nav>
        <?php include("nav.php"); ?>
    </nav>
    <hr>
    <header> <h1> Manage Users</header>
    <div class="container">
        <!-- Display a list of users with delete links -->
        <div class="row">
            <div class="col">
                <h3>Active Users</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">User ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">User Type</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<th scope='row'>" . $row["user_id"] . "</th>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["user_type"] . "</td>";
                                echo "<td>
                                        <a href='manageusers.php?action=delete&user_id=" . $row['user_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Deleted Users Container -->
        <?php if (!empty($deletedUsers)): ?>
            <div class="row">
                <div class="col">
                    <h3>Deleted Users</h3>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">User ID</th>
                                <th scope="col">Username</th>
                                <th scope="col">User Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deletedUsers as $deletedUser): ?>
                                <tr>
                                    <td><?php echo $deletedUser['user_id']; ?></td>
                                    <td><?php echo $deletedUser['username']; ?></td>
                                    <td><?php echo $deletedUser['user_type']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2023 Admin Dashboard
    </footer>
    <script src="../js/bootstrap.js"></script>
</body>
</html>

