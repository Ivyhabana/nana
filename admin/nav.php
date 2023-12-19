
    <link rel="stylesheet" href="../css/bootstrap.css">
    <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   
<style>
    .cosmet {
        float: left;
        height: 60px;
        width: 60px;
        border-radius: 50%;
    }

    .navbar {
        padding: 10px;
    }

    .navbar-brand {
        margin-right: 30px;
    }

    .navbar-form {
        display: flex;
        align-items: center;
    }

    .form-control {
        margin-right: 10px;
    }

    .offcanvas-btn {
        font-size: 1.5rem;
        color: black;
        background: none;
        border: none;
        cursor: pointer;
    }

    .logo {
        border-radius: 50%;
        height: 60px;
        width: 60px;
    }

    #offcanvasScrolling {
        background-color: antiquewhite;
    }

    .offcanvas-title {
        margin-bottom: 0;
    }

    .list-group-item {
        border: none;
        padding: 10px;
    }

    .list-group-item a {
        text-decoration: none;
        color: black;
        width: 100%;
    }
</style>

<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="../img/logo.png" class="cosmet"></a>
        <!-- Use an icon for the offcanvas button -->
        <button class="offcanvas-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
            <i class="fas fa-bars"></i>
        </button>
        </div>
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">
            <img src="../img/logo.png" class="logo">
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Use an unordered list for the buttons -->
        <ul class="list-group">
            <li class="list-group-item"><a href="index.php">Home</a></li>
            <li class="list-group-item"><a href="sales.php">Sales/Inventory</a> </li>
            <li class="list-group-item"><a href="manageusers.php">Manage Users</a></li>
            <li class="list-group-item"><a href="manageorders.php">Manage Orders</a></li>
            <li class="list-group-item"><a href="manageproducts.php">Manage Products</a></li>
            <li class="list-group-item"><a href="logout.php">Log out</a></li>
        </ul>
    </div>
</div>
</nav>
