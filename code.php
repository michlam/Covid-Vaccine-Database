<?php   
        session_start();
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;
            $result = $db_conn->query($cmdstr);
            return $result;
        }


        function console_log( $data ){
          echo '<script>';
          echo 'console.log('. json_encode( $data ) .')';
          echo '</script>';
        }


        function connectToDB() {
            global $db_conn;

            // Your username is (CWL_ID) and the password is a(student number). For example, 
			      // platypus is the username and a12345678 is the password.

            $db_conn= new mysqli("dbserver.students.cs.ubc.ca", "andreasy", "a18412817", "andreasy");
            if ($db_conn->connect_error) {
                debugAlertMessage('Connect Failed' . $db_conn->connect_error);
                console_log("false");
                return false;
            } else {
                debugAlertMessage('Successfully Connected to MYSQL');
                console_log("true");
                return true;
            }

        }

        function disconnectFromDB() {
            global $db_conn;


            debugAlertMessage("Disconnect from Database");
            $db_conn->close();
        }

        #POST REQUESTS
        function handleUserLoginRequest() { // complete
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');
            
            $account_id = $_POST['account_id'];
            $phn = $_POST['phn'];

            $query = "SELECT * FROM Accounts_HasClients WHERE account_id = '$account_id' AND phn = '$phn'";
            $result = mysqli_query($db_conn, $query);

            if (mysqli_num_rows($result) > 0) {
                header("location: account.php?account_id=$account_id&phn=$phn");
                exit();
            } else {
                header('location:index.php?Invalid = Please enter correct Username and Password');
                exit();
            }
        }

        function handleRegisterRequest() { // complete
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');

            $account_id = $_POST['account_id'];
            $vaccination_status = $_POST['vaccination_status'];
            $vaccine_type_received = $_POST['vaccine_type_received'];
            $phn =  $_POST['phn'];

            $result = executePlainSQL( "SELECT * FROM Accounts_HasClients WHERE account_id = '$account_id' OR phn = '$phn'");
            $num = mysqli_num_rows($result);

            if ($num == 1) {
                echo "Account ID or PHN Already Taken";
            } else {
                $stmt = $db_conn->prepare("insert into Accounts_HasClients(account_id,vaccination_status,vaccine_type_received,phn) values (?, ?, ?, ?)"); 
                $stmt->bind_param("issi", $account_id, $vaccination_status, $vaccine_type_received, $phn);
                $stmt->execute();
                $stmt->close();

                $stmt = $db_conn->prepare("insert into Account_PHN(account_id,phn) values (?, ?)"); 
                $stmt->bind_param("ii", $account_id, $phn);
                $stmt->execute();
                $stmt->close();
                echo "Registration Sucessful";
            }

            $db_conn->commit();
        }

        function handleOfficialLoginRequest() { // complete
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');
            
            $eid = $_POST['eid'];

            $result = executePlainSQL("SELECT * FROM Officials WHERE eid = '$eid'"); 

            if (mysqli_num_rows($result) > 0) {
                header("location: official.php?eid=$eid");
                exit();
            } else {
                header('location:employeeLogin.php?Invalid = Please enter correct ID');
                exit();
            }
        }

        function handleWorkerLoginRequest() { // complete
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');

            $eid = $_POST['eid'];

            $result = executePlainSQL("SELECT * FROM Workers WHERE eid = '$eid'"); 

             if (mysqli_num_rows($result) > 0) {
                header("location: worker.php?eid=$eid");
                exit();
            } else {
                header('location:employeeLogin.php?Invalid = Please enter correct ID');
                exit();
            }
        }

        function handleViewVaccineRequest() {  // c
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');

            $vacc_name = $_POST['vacc_name'];
            $result = executePlainSQL("SELECT Count(*) AS num FROM Workers_ReportingTo_Officials WHERE vaccinated_name = '$vacc_name' GROUP BY vaccinated_name");
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $value = 10000 - $row["num"];
            echo "There are " . $value . " doses of " . $vacc_name . " remaining.<br>";

        }

        function handleReplenishRequest() { // complete
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');

            $vacc_name = $_POST['vacc_name'];
            // UPDATE DATABASE
            executePlainSQL("UPDATE Vaccine_Sets SET quantity = 10000 WHERE vaccine_name = 'vacc_name'");
            echo "The stock of " . $vacc_name . " has been replenished. There are 10000 doses remaining. <br>";

            // DELETE TUPLES
            executePlainSQL("DELETE FROM Workers_ReportingTo_Officials WHERE vaccinated_name = '$vacc_name'");
            $db_conn->commit();

        }


    
        function handleNotifyNewsRequest() { // complete
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');

            $news_name = $_POST['news_name'];
            $vacc_num = $_POST['vacc_num'];
            $eid = $_POST['eid'];

            $result = executePlainSQL("SELECT * FROM Officials_ReportingTo_Sources WHERE sname = '$news_name' AND eid = '$eid'");
            $num = mysqli_num_rows($result);

            if ($num > 0) {
                $result = executePlainSQL("UPDATE Officials_ReportingTo_Sources SET vaccinated_total = '$vacc_num' WHERE sname = '$news_name' AND eid = '$eid'"); 
            } else {
                $stmt = $db_conn->prepare("INSERT INTO Officials_ReportingTo_Sources VALUES (?, ?, ?)"); 
                $stmt->bind_param("sis", $news_name, $vacc_num, $eid);
                $stmt->execute();
                $stmt->close();
            }

            $db_conn->commit();    
            echo "Latest News from " . $news_name . ": " . $vacc_num . " doses have been administered lately.<br>";
        }

        function handleUpdateAccountRequest() {
            global $db_conn;
            mysqli_select_db($db_conn, 'andreasy');

            $aid = $_POST['aid'];
            $vacc_name = $_POST['vacc_name'];
            $vacc_stat = $_POST['vacc_stat'];
            
            // update accounts_hasclients
            $result = executePlainSQL("SELECT * FROM Accounts_HasClients WHERE account_id = '$aid'");
            $num = mysqli_num_rows($result);

            if($num > 0) {
                executePlainSQL("UPDATE Accounts_HasClients SET vaccination_status= '$vacc_stat', vaccine_type_received= '$vacc_name' WHERE account_id = '$aid'");
                $eid1 = $_POST['eid1'];
                $eid2 = $_POST['eid2'];

                $stmt = $db_conn->prepare("insert into Workers_ReportingTo_Officials (eid1,eid2,vaccinated_name) values (?, ?, ?)"); 
                $stmt->bind_param("sss", $eid1, $eid2, $vacc_name);
                $stmt->execute();
                $stmt->close();

                echo "User " . $aid . "'s account has been updated. <br>";

            } else {
                echo "No such account <br>";

            }
            $db_conn->commit();  
        }

    //    // Find address with the highest number of appointments made ?
    //     // get the postal code and street num that shows up the most in appt_info, 
    //     // and display the building name in vaccineLocations_AtAddress associated with it
    //     function handleFindBusyRequest() {
    //         global $db_conn;
    //         mysqli_select_db($db_conn, 'andreasy');
            
            


    //         // $sql = "SELECT v.building_name 
    //         //         FROM VaccineLocations_AtAddress v 
    //         //         WHERE v.postal_code, v.street_num = (SELECT a.postal_code, a.street_num 
    //         //                                             FROM Appt_info a 
    //         //                                             GROUP BY a.postal_code, a.street_num ORDER BY COUNT(a.postal_code, a.street_num) 
    //         //                                             DESC LIMIT 1)";

    //         // $sql = executePlainSQL("SELECT * FROM VaccineLocations_AtAddress WHERE postal_code = 'A0B 1C2' AND street_num = '1'");

    //         $sql = executePlainSQL("SELECT v.building_name, COUNT(*)
    //                                 FROM VaccineLocations_AtAddress v 
    //                                 WHERE EXISTS
    //                                      (SELECT *
    //                                     FROM Appt_info a 
    //                                     WHERE v.postal_code = a.postal_code AND v.street_num = a.street_num
    //                                     GROUP BY a.postal_code, a.street_num 
    //                                     ORDER BY COUNT(*) 
    //                                     DESC LIMIT 1) 
                                        
    //                                 GROUP BY v.building_name");


    //         // $building = mysqli_query($sql);

    //         // $building_name = mysqli_fetch_array($sql,MYSQL_ASSOC);
    //         console_log("printing SQL");
    //         console_log($sql);
    //         while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){
    //                             echo $row["building_name"];
    //         }
    //         // while($building_name = mysqli_fetch_array($sql, MYSQL_ASSOC)) {
    //         //     console_log("goodbye");
    //         //     console_log($building_name['building_name']);
    //         //     echo "The busiest vaccination location is " . $building_name['building_name'] . ".<br>";
    //         // }
    //         console_log("hello");
            
            
            
    //         // IDEA: subquery gets the postal code and street num that appears the most often, and joins it with table VaccineLocations_AtAddress
    //     }

        

        // HANDLE ALL POST ROUTES
	    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            console_log("testing");
            if (connectToDB()) {
                if (array_key_exists('loginRequest', $_POST)) {
                    handleUserLoginRequest();
                } else if (array_key_exists('registerRequest', $_POST)) {
                    handleRegisterRequest();
                } else if (array_key_exists('officialLoginRequest', $_POST)) {
                    handleOfficialLoginRequest();
                } else if (array_key_exists('workerLoginRequest', $_POST)) {
                    handleWorkerLoginRequest();
                } else if (array_key_exists('viewVaccineRequest', $_POST)) {
                    handleViewVaccineRequest();
                } else if (array_key_exists('replenishRequest', $_POST)) {
                    handleReplenishRequest();
                } else if (array_key_exists('notifyNewsRequest', $_POST)) {
                    handleNotifyNewsRequest();
                } else if (array_key_exists('updateAccountRequest', $_POST)) {
                    handleUpdateAccountRequest();
                } 
                disconnectFromDB();
            }
        }

    if (isset($_POST['loginSubmit']) || 
        isset($_POST['updateAccount']) || 
        isset($_POST['notifyNews']) || 
        isset($_POST['replenishVaccine']) || 
        isset($_POST['viewVaccine']) || 
        isset($_POST['registerSubmit']) || 
        isset($_POST['officialLoginSubmit']) || 
        isset($_POST['workerLoginSubmit'])) {
            handlePOSTRequest();
    } 
?>