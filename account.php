<?php

include "code.php";
session_start();
connectToDB();
$account_id = $_GET["account_id"];
$phn = $_GET["phn"];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Covid-19 Registration System</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</link>
</head>
<style>
    body {
    background-image:url('covid.jpg');
    background-repeat: no-repeat;
    background-attachment:fixed;
    background-size:cover;
    }

    table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    }

    tr {
    background-color: #FFFFFF;
    }

    div{
        text-align:center;
    }

</style>
<a href = "logout.php"> LOGOUT </a>

<body>
    <div class="container">
        <div class="com-md-6">
            <h2 class="text-center">Welcome <h2>
                <form method="POST">
                    <div class = "form-group">
                        <input type="submit" name = "viewApptSubmit" class="btn btn-primary" value = "View Appointments"></p> 
                    </div>
                </form>
                        
                <?php
                    if(isset($_POST["viewApptSubmit"])) {
                        
                        mysqli_select_db($db_conn, 'andreasy');
                        
                        $appt_info_result = mysqli_query($db_conn, "SELECT appt_date, appt_time, dose_num, postal_code, street_num, appt_id
                                                                    FROM Appt_info
                                                                    WHERE account_id = '$account_id'");
                        $appt_info_array = mysqli_fetch_array($appt_info_result, MYSQLI_ASSOC);
                        $numRows = mysqli_num_rows($appt_info_result);
                        $street_num = $appt_info_array["street_num"];
                        $postal_code = $appt_info_array["postal_code"];
                        $query = "SELECT appt_id, appt_date, appt_time, dose_num, building_name, room_num   
                                    FROM Appt_info, VaccineLocations_AtAddress
                                    WHERE VaccineLocations_AtAddress.street_num = '$street_num' AND
                                    VaccineLocations_AtAddress.postal_code = '$postal_code' AND
                                    Appt_info.account_id = '$account_id'";
                        $res = mysqli_query($db_conn, $query);
                        if($res->num_rows < 1){
                            echo '<div class="alert alert-warning" role="alert">
                                    You do not have any appointments. Make some!
                            </div>';
                        }else {
                            echo "<table>";
                            echo "<tr>
                            <th>Appointment ID </th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Dose Number </th>
                            <th>Building Name </th>
                            <th>Room Number </th>";
                            while($row = mysqli_fetch_array($res, MYSQLI_ASSOC)){
                                echo "<tr><td>" 
                                . $row["appt_id"] . "</td><td>" 
                                . $row["appt_date"] . "</td><td>"
                                . $row["appt_time"] . "</td><td>"
                                . $row["dose_num"] . "</td><td>"
                                . $row["building_name"] . "</td><td>"
                                . $row["room_num"] . "</td></tr>";
                            }
                            echo "</table>";      
                        }        
                    }
                ?>

            <div class="form-group">
                <form>
                    <button type="button" style="margin-top:150px;" name = "redirectAppt" class="btn btn-primary" 
                    value = "Make Appointment" id = "redirectApptBtn">Make Appointment</button>
                    <script>
                        var btn = document.getElementById('redirectApptBtn');
                        btn.addEventListener('click', function() {
                        document.location.href = '<?php echo "make_appt.php?account_id=$account_id&phn=$phn" ?>';
                        });
                    </script>

                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>