<?php
session_start();
include("dtbs.php");
include("functions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>welcome</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-KRRjD5uS1aD2eUC5hu7jzP6b2C/4Z3a0xlKK48FaySgp5L9x3IKBTtU6iCF9CjcWYdIl8O6ne3tZaGxdQCEibA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    body {
       background-image: url('img/bg.jpg');
       margin: 10px;
       padding:20px;
    }

    h1 {
        font-family: Lucida Handwriting;
        text-align: center;
        font-size: 50px;
        padding-top: 80px;
    }
    h2{
        font-family:'Times New Roman', Times, serif;
        text-align: center;
        padding-bottom: 10px;
    }
    h3 {
        font-family: lucida ;
        text-align: center;
    }
    .offcanvas {
        background-image: url('img/brush.jpg');
    }

    #rawr {
        float: right;
        margin: 10px; /* Add some margin for better spacing */
        color:black;
    }
    #rawr:hover{
        background-color:black;
        color:antiquewhite;
    }
    .cosmet{
        float:left;
        height: 60px;
        width:60px;
        border-radius:28px;
    }
    #log:hover{
        color:blue;
    }
    .logo{
        margin: 10px;
        float:left;
        height: 60px;
        width:60px;
        border-radius:28px;
    }
    #samp{
        border-radius: 50px;
        height: 300px;
        width: min-content;
        margin-right:20px;
    }
</style>
</head>

<body>
    <nav>
        <img src="img/logo.png" class="logo">
        <a class="btn btn-outline-dark" data-bs-toggle="offcanvas" id="rawr" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            Menu
        </a>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header"> 
                    <ul class="nav nav-underline" id="links">
                        <li class="nav-item">
                          <a class="nav-link active" aria-current="page" href="login.php" id="log">Log-in</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="registrationform.php" id="log">Sign Up</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link active" aria-current="page" id="log" href="shoppinglist.php">Products</a>
                            </li>
                          <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="aboutus.html" id="log">About Us</a>
                          </li>
                      </ul>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                   Hi! Welcome, dear customer! Wanna create your beautiful transformation?
                    Trust us and you will not regret it. With cosme+ we will help you unveil your true beauty, effortlessly and flawlessly. Let your beauty do the talking. The beauty of self expression through cosmetics. </div> 
                   <br><br><center><a class="btn btn-primary w-50" href="shoppinglist.php" role="button">
            Explore 
        </a></center> 
                </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
        <h1>Welcome to Cosme+ Website!</h1>
                <h3>The beauty of self-expression through cosmetics</h3>
            </div>
        </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 mt-5">
                <h2>Make-up Category</h2>
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/loose powder.jpg" class="d-block w-100" id="samp" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/eyeshadow glitter.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  <div class="carousel-item ">
                        <img src="img/blushon.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  <div class="carousel-item ">
                        <img src="img/setting spray.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  <div class="carousel-item ">
                        <img src="img/conceler.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  
                </div>
            </div>
            </div>
            <div class="col-6 mt-5">
                <h2>Skincare Essentials Category</h2>
            <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/toner.jpg" class="d-block w-100" id="samp" alt="...">
                    </div>
                    <div class="carousel-item">
                        <img src="img/cleanser.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  <div class="carousel-item">
                        <img src="img/moisturizer.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  <div class="carousel-item">
                        <img src="img/sleeping mask.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>
                  <div class="carousel-item">
                        <img src="img/vitamin C.jpg" class="d-block w-100" id="samp" alt="...">
                  </div>


                </div>
            </div>
            </div>
        </div>          
    </div>
     
    <script src="js/bootstrap.js">
        // Auto play carousel
        $(document).ready(function () {
            $('#carouselExampleAutoplaying').carousel({
                interval: 3000, // Change slide every 3 seconds
                cycle: true
            });
        });
    </script>
</body>
</html>
