<?php
include 'code.php';
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

    div{
        text-align:center;
    }
</style>
<a href = "logout.php"> LOGOUT </a>

<body>
    
    <div class="contain row">
            <div class="com-md-6">
            <a href = <?php echo "account.php?account_id=$account_id&phn=$phn" ?>> Return to Account Homepage </a>
                    <h4 class="text-center">Make An Appointment<h4>
                        <form method="POST">
                            <input type="hidden" id="makeApptRequest" name="makeApptRequest">
                            <input type="hidden" name="account_id">
                            Date:<input type = "date" name = "appt_date" class="form-control" min="2021-07-01" max="2021-12-31" value= "2021-06-14"> 
                            <br></br>
                            Time:<select name="appt_time" id="appt_time" class="form-control">
                                <option value='09:00'>09:00</option>
                                <option value='12:00'>12:00</option>
                                <option value='15:00'>15:00</option>
                                <option value='18:00'>18:00</option>
                                <option value='20:00'>20:00</option>
                            </select>
                            <br></br>
                            Dose Number:
                                <select name = "dose_num" id ="dose_num" class = "form-control">
                                    <option value ="1">1</option>
                                    <option vlue = "2">2</option>
                                </select>
                            <br></br>
                            Location:
                                <select name = "vaccine_locations" id="vaccine_locations" class= "form-control">
                                    <?php
                                        $sql = mysqli_query($db_conn, "SELECT DISTINCT building_name FROM VaccineLocations_AtAddress");
                                        while ($row = $sql->fetch_assoc()){
                                            $bn = $row['building_name'];
                                            echo '<option value = "'. $bn . '">'. $bn. '</option>'; 
                                        }
                                    ?>
                                </select>
                            <input type ="submit" style="margin-top:50px;" value = "Make Appointment" class="btn btn-primary" name="makeApptSubmit"></p>
                        </form>
                        <?php
                            if(isset($_POST["makeApptRequest"])) {
                                mysqli_select_db($db_conn, 'andreasy');
                                
                                $appt_time = $_POST['appt_time'];
                                $appt_date = $_POST['appt_date'];
                                $dose_num = $_POST['dose_num'];
                       
                                // getting the building name to get postal code and street num, see appt_info table for deets
                                $building_nameResult = $_POST['vaccine_locations'];
                                
                                $result = executePlainSQL("SELECT street_num, postal_code 
                                                            FROM VaccineLocations_AtAddress 
                                                            WHERE building_name = '$building_nameResult'");
                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                                $postal_code = $row['postal_code'];
                                $street_num = $row['street_num'];


                                
                                // taking the maximum appt_id and incrementing 1 when creating a new appt
                                $max = executePlainSQL("SELECT MAX(appt_id) as appt_id 
                                                        FROM Appt_info");
                                $maxResult = mysqli_fetch_array($max, MYSQLI_ASSOC);
                                $appt_id = $maxResult['appt_id'] + 1;

                                // first insert into Appt_info_with_shift 
                                // then insert into Appt_info
                                // account_id and phn are obtained from the top of this php file using the URL
                                $appt_info_with_shift_INSERT = "INSERT INTO Appt_info_with_shift(appt_id, appt_time, phn, shift_id)
                                                VALUES ('$appt_id', '$appt_time', '$phn', null)";
                                $appt_info_INSERT = "INSERT INTO Appt_info(appt_date, appt_time, dose_num, account_id, postal_code, street_num, appt_id)
                                            VALUES ('$appt_date','$appt_time', '$dose_num', 
                                                    '$account_id', '$postal_code', '$street_num', '$appt_id')";
                                                
                                if(mysqli_query($db_conn, $appt_info_with_shift_INSERT) === TRUE &&
                                    mysqli_query($db_conn, $appt_info_INSERT) === TRUE) {
                                    echo '<div class="alert alert-success" role="alert">
                                            Succesfully made appointment!
                                            </div>';
                                            
                                }else{
                                    echo mysqli_errno($db_conn).": ". mysqli_error($db_conn)."\n";
                                    echo '<div class="alert alert-warning" role="alert">
                                            Error in making appointment! :( 
                                            </div>';
                                };
                            }
                        ?>
                        
            </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html>