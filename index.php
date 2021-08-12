<?php
   include_once('code.php');
   session_start();
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
    background-image:url('covid.jpg');
    background-repeat: no-repeat;
    background-attachment:fixed;
    background-size:cover;
    }
    </style>
    <a href="employeeLogin.php">Employee Portal</a>
    <div class="contain row">
        <div class="com-md-6">
             <h2 class="text-center"> Registration</h2><hr/>


             <form method="POST" action = index.php> <!--refresh page when submitted-->

                <input type="hidden" id="registerRequest" name="registerRequest">
                Account ID: <input type = "text" name = "account_id" class="form-control" placeholder="Max 10 digit integer"> 
                Password: <input type = "text" name = "phn" class="form-control" placeholder="Enter Personal Health Number"> 
                Vaccination Status: <input type = "text" name = "vaccination_status" class="form-control" placeholder="Enter full, half, none">
                Vaccine Received: <input type = "text" name = "vaccine_type_received" class="form-control" placeholder="Leave empty if not yet received"> 

                <input type = "submit" value = "Register" class="btn btn-primary" name="registerSubmit"></p>

            </form>
        </div><hr/>

        <div class="com-md-6">
             <h2 class="text-center"> User Login</h2><hr/>

             <form method="POST" action = index.php> 

                <input type="hidden" id="loginRequest" name="loginRequest">
                Account ID: <input type = "text" name = "account_id" class="form-control" placeholder="Max 10 digit integer"> 
                Password: <input type = "text" name = "phn" class="form-control" placeholder="Enter Personal Health Number"> 
                <input type = "submit" value = "Login" class="btn btn-primary" name="loginSubmit"></p>
        </div>
    </div>
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>