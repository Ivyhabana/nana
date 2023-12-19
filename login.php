<? session_start();
?>
<html>
    <head>   
        <meta charset="UTF-8">
        <title>index</title>
        <link rel="stylesheet" href="css/bootstrap.css">
</head>
<style>

    h1{
        color: solid black;
        font-size:50px;
        text-align: center;
    }
    body{
        background-image:url('https://scontent-ord5-2.xx.fbcdn.net/v/t1.15752-9/315517761_837090724265703_5991438036763085948_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=510075&_nc_eui2=AeEtzuZdw2vBvw9XrV1qE6nrYv3pYxRN3Kpi_eljFE3cqjCogVrsxeUOf8_8F4CKtuuixVgESbM4OYJj95_CBhNd&_nc_ohc=UXru7Og5E_YAX9cDkAQ&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent-ord5-2.xx&oh=03_AdSuDWu44e-AERZIxXVjoqc5XpajCoiIEyo7_to5P4hAZA&oe=659936B9');
        background-size: 100%;
         font-family: Georgia;
    }

    </style>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
        </div><div class="col-3">
        </div>
        <div class="col-3">
        <br><br><br><br><br><h1>Log-in</h1><br>
        <form action="loginform.php" method="POST">
                <div class="mb-3">
                    
<div class="mb-3">
                    <label for="" class="form-label"> Username 
                        <input type="text" class="form-control" name="username" >
                        
</label>
</div>
<div class="mb-3">
                    <label for="" class="form-label"> Password
                        <input type="password" class="form-control" name="password">
</label>
</div>
</label>
</div>
<input type="submit" class="btn btn-outline-dark"prima name="login">
<p>Don't have an account? 
<a href="registrationform.php">Create Account</a></p>
  </form>
</div>
<div class="col-4"></div>
</div>
</body>
<script src="js/bootstrap.js"></script>
</html>
