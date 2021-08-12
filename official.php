<?php
include "code.php";
session_start();
connectToDB();

$eid = $_GET["eid"];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Covid-19 Registration System</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</link>
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
    <a href="logoutEmployee.php">LOGOUT</a>
    <form method="POST" action= "official.php">
    
    

    <div class="contain row">
        <div class="com-md-6">

             <h2 class="text-center"> Welcome Official </h2><hr/>

             <form method="POST" action = official.php>

             <h4 class="text-center"> View Vaccine Quantity</h4><hr/>
                <div class = "form-group">
                    <label>Vaccine Name</label>

                    <input type = "text" name = "vacc_name" class="form-control" placeholder="Enter Vaccine Name">
                </div>
               
                 <div class = "form-group">
                    <input type = "hidden" id = "viewVaccineRequest" name="viewVaccineRequest">
                    <p><input type = "submit" name = "viewVaccine" class="btn btn-primary" value="View"></p>
                </div>
               
            </form>


            <form method="POST" action = official.php>

                <h4 class="text-center"> Replenish Vaccine Supply</h4><hr/>
                <div class = "form-group">
                    <label>Vaccine Name</label>

                    <input type = "text" name = "vacc_name" class="form-control" placeholder="Enter Vaccine Name">
                </div>
               

                 <div class = "form-group">
                    <input type = "hidden" id = "replenishRequest" name="replenishRequest">
                    <p><input type = "submit" name = "replenishVaccine" class="btn btn-primary" value="Replenish"></p>

                </div>
               
            </form>

            <form method="POST">

                <h4 class="text-center">View All Appointments</h4><hr/>

                 <div class = "form-group">
                    <input type = "hidden" id = "viewAppointmentRequest" name="viewAppointmentRequest">
                    <p><input type = "submit" name = "viewAppointment" class="btn btn-primary" value="View Appointments"></p>

                </div>
                <?php
                    
                    if (isset($_POST["viewAppointment"])) {
                        mysqli_select_db($db_conn, 'andreasy');
                        console_log("test");
                        $result = executePlainSQL("SELECT appt_date, appt_time FROM Appt_info");
        
                        echo "<table>";
                        echo "<tr><th>Appointment Date</th><th>Appointment Time</th><th>";
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                            echo "<tr><td>" . $row["appt_date"] . "</td><td>" . $row["appt_time"] . "</td></tr>";
                        }
                        echo "</table>";
                    }
                ?> 
            </form>



            <form method="POST" action = official.php>

             <h4 class="text-center"> Notify News Source</h4><hr/>
                <div class = "form-group">
                    <label>News Source Name</label>
                    <input type = "text" name = "news_name" class="form-control" placeholder="Enter News Source Name">
                    <input type = "text" name = "vacc_num" class="form-control" placeholder="Enter Vaccination Quantity">
                    <input type = "text" name = "eid" class="form-control" placeholder="Enter Employee ID">
                </div>
               
                 <div class = "form-group">
                    <input type = "hidden" id = "notifyNewsRequest" name="notifyNewsRequest">
                    <p><input type = "submit" name = "notifyNews" class="btn btn-primary" value="Notify"></p>
                </div>
               
            </form>
        </div><hr/>

    </div>

     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>