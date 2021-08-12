<?php
    include "code.php";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Covid-19 Registration System</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"></link>
    <link rel="stylesheet" type="text/css" href="css/style.css"></link>
</head>

<body>
    <style>
        body {
    background-image:url('covid2.jpg');
    background-repeat: no-repeat;
    background-attachment:fixed;
    background-size:cover;
    }
    </style>
    <a href="index.php">User Portal</a>
    <div class="contain row">
        <div class="com-md-6">
             <h2 class="text-center"> Healthcare Official</h2><hr/>
            <form method="POST" action = employeeLogin.php> 
                <input type = "hidden" id = "officialLoginRequest" name="officialLoginRequest">
                Employee ID: <input type = "text" name = "eid" class="form-control" placeholder="ex. E1234"> 
                
                <input type = "submit" value = "Login" class="btn btn-primary" name="officialLoginSubmit"></p>
            </form>

             
        <div class="com-md-6">
             <h2 class="text-center"> Healthcare Worker</h2><hr/>
            <form method="POST" action = employeeLogin.php> 
                <input type = "hidden" id = "workerLoginRequest" name="workerLoginRequest">
                Employee ID: <input type = "text" name = "eid" class="form-control" placeholder="ex. E1234"> 
                
                <input type = "submit" value = "Login" class="btn btn-primary" name="workerLoginSubmit"></p>
            </form>
        </div>
    </div>
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>