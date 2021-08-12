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

</style>
<a href="logoutEmployee.php">LOGOUT</a>


<div class="contain row">
        <div class="com-md-6">
             <h2 class="text-center"> Welcome Worker</h2><hr/>
             <!-- <form method="POST" action = worker.php>

             <h4 class="text-center"> View Your Shifts</h4><hr/>
                <div class = "form-group">
                    <label>Shifts</label>
                    
                    <table style="width:100%">
                        <tr>
                            <th>Date</th>
                            <th>Time</th> 
                            <th>Location</th>
                        </tr>
                        <tr>
                            <td>Example</td>
                            <td>Example</td> 
                            <td>Example</td>
                        </tr>
                    </table> </br>

                </div>
            </form> -->


             <form method="POST" action = worker.php>

             <h4 class="text-center"> Update An Account</h4><hr/>
                <div class = "form-group">
                    <label>Update An Account</label>
                    <input type = "text" name = "aid" class="form-control" placeholder="Enter Account ID">
                    <input type = "text" name = "vacc_name" class="form-control" placeholder="Enter Vaccine Type">
                    <input type = "text" name = "vacc_stat" class="form-control" placeholder="Enter Vaccine Status">
                    <input type = "text" name = "eid1" class="form-control" placeholder="Enter Your Employee ID">
                    <input type = "text" name = "eid2" class="form-control" placeholder="Enter Supervisor's Employee ID">
                </div>
               
                 <div class = "form-group">
                    <input type = "hidden" id = "updateAccountRequest" name="updateAccountRequest">
                    <p><input type = "submit" name = "updateAccount" class="btn btn-primary" value="Update"></p>
                </div>
               
            </form>

             <form method="POST" action = worker.php>

             <h4 class="text-center"> Find Busiest Vaccine Location </h4><hr/>
            
                 <div class = "form-group">
                    <input type = "hidden" id = "findBusyRequest" name="findBusyRequest">
                    <p><input type = "submit" name = "findBusy" class="btn btn-primary" value="Find"></p>
                </div>
                <?php
                if(isset($_POST["findBusy"])) {
                    mysqli_select_db($db_conn, 'plkee');
                    $innerQuery = executePlainSQL("SELECT postal_code, street_num, COUNT(*)
                                                    FROM Appt_info 
                                                    GROUP BY postal_code, street_num
                                                    HAVING COUNT(*) = (SELECT MAX(numCount) 
                                                                        FROM (SELECT COUNT(*) as numCount 
                                                                        FROM Appt_info 
                                                                        GROUP BY postal_code, street_num) AS a)");

                    $row = mysqli_fetch_array($innerQuery, MYSQLI_ASSOC);
                    $postal_code = $row["postal_code"];
                    $street_num = $row["street_num"];

                   
                    $sql = executePlainSQL("SELECT v.building_name
                                            FROM VaccineLocations_AtAddress v
                                            WHERE v.postal_code = '$postal_code' AND v.street_num = '$street_num'
                                            ");

                   
                    if($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
                        echo $row["building_name"];
                    } else {
                        echo '<div class="alert alert-warning" role="alert">
                                    Could not find busiest vaccine location. 
                            </div>';
                    }
                }
               ?>
               
            </form>
  
             <form method="POST" action = worker.php>

             <h4 class="text-center"> Employee(s) of the Month </h4><hr/>
                
               
                 <div class = "form-group">
                    <input type = "submit" name = "viewEmployeeSubmit" class="btn btn-primary" value="View"></p>
                </div>

                <?php
                    if(isset($_POST["viewEmployeeSubmit"])) {
                        mysqli_select_db($db_conn, 'andreasy');

                        $result = executePlainSQL("SELECT w.worker_name 
                                                    FROM Workers w 
                                                    WHERE NOT EXISTS 
                                                        (SELECT * 
                                                        FROM Shift_id_and_Location s
                                                        WHERE NOT EXISTS 
                                                            (SELECT w.eid
                                                            FROM Assign a 
                                                            WHERE a.eid = w.eid AND a.shift_id = s.shift_id))");
                        $num = mysqli_num_rows($result);
                        if($num < 1){
                            echo '<div class="alert alert-warning" role="alert">
                                    No Employee of the Month!
                            </div>';
                        }else {
                            echo "<table>";
                            echo "<tr>
                            <th>Name </th>";
                            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                echo "<tr><td>" 
                                . $row["worker_name"] . "</td><td>";
                            }
                            echo "</table>";      
                        }             
                    }
                ?>
               
            </form>
        </div><hr/>

    </div>
     <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>