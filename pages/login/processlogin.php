<?php
// Inialize session
session_start();
// Include database connection settings
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

// Include class for ipinfodb.com
include("$_SERVER[DOCUMENT_ROOT]/dist/php/ip2locationlite.class.php");

$userName 	= trim($_POST["DriverUserName"]);
$password 	= trim($_POST["DriverPassword"]);
$truck_id 	= trim($_POST["TruckID"]);
$trailer_id	= trim($_POST["LoadPosition"]);
$email		= trim($_POST["DriverEmail"]);
$admin		= $_POST["AdminLogin"];
$rental_truck    = $_POST["rentaltrucks"];
$truck_odometer = trim($_POST["truck_odometer"]);
$ipinfodb_api = '326013316f18900b3cb37d7df401dc5c9dd322b3285d8bdde74c7e35ce0c4d90';

if ($_POST["hdn_coordinates"] == '' )
{
    # Set some default coordinate (in Canada).
    $_POST["hdn_coordinates"] = '52.939916|-106.450864';
}

$coordinates    = explode('|',$_POST["hdn_coordinates"]);
$latitude = $coordinates[0];
$longitude = $coordinates[1];
$errors = array();
$getErrors = "error=";

# Create session variables so that we can repopulate
# driver login if we need to.
$_SESSION['login_username'] = $userName;
$_SESSION['login_password'] = $password;
$_SESSION['login_truckid'] = $truck_id;
$_SESSION['login_trailerid'] = $trailer_id;
# Setup duplicate-esque sessions variables for truck and trailer
$_SESSION['login_rentaltruck'] = $rental_truck;
$_SESSION['login_truckodometer'] = $truck_odometer;
$_SESSION['truckid'] = $truck_id;
$_SESSION['trailerid'] = $trailer_id;
$_SESSION['odometer'] = $truck_odometer;

# setup the database connection
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

# First, if we've gotten here via the forgotPassword page then we'll just 
# validate and skip the rest
if (isset($_POST['forgotPassword']) && ($_POST['forgotPassword'] == 'true'))
{
    $forgottenUser = $mysqli->real_escape_string($_POST['DriverUserName']);
    $statement = "SELECT username,email,password FROM users WHERE 
                  lower(username) = lower('" . $forgottenUser . "')
                  or
                  lower(email) = lower('" . $forgottenUser . "')";

     if ($result = $mysqli->query($statement)) 
     {
         $rows = mysqli_stmt_store_result($statement);
         if ($rows == 1)
         {
             while ($row = mysqli_fetch_assoc($result))
             {
                 $forgottenUser = $row['username'];
                 $forgottenEmail = $row['email'];
                 $password = $row['password'];
                 sendEmail('dispatch@catalinacartage.com',"Password Request","$forgottenUser has forgotten their current username or password.\r\n
                 Username: $forgottenUser\r\n
                 Email: $forgottenEmail\r\n
                 Password: $password\r\n");
             }
         }
    }
    $result->close();
    header("Location: /pages/login/forgot.php?return=true");
    exit;
}

// If we're trying to sign up do this part here
if (isset($_POST['register']) && ($_POST['register'] == 'true'))
{
    $fname = $mysqli->real_escape_string($_POST['fname']);
    $lname = $mysqli->real_escape_string($_POST['lname']);
    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Let's validate via recaptcha that the user is not a bot.
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = 'secret=' . $recaptcha_key . '&remoteip=' . $_SERVER['REMOTE_ADDR'] . '&response=' . $recaptcha_response;
    $response=file_get_contents($url."?secret=".$recaptcha_key."&response=".$recaptcha_response."&remoteip=".$_SERVER['REMOTE_ADDR']);
    $obj = json_decode($response);
    if($obj->success == false) {
        $url_error = urlencode("Failed recaptcha response.  Please try again");
        header("location: /pages/login/register.php?return=false&error=$url_error");
        exit;
    }

    $salt = '@KowM$viHR8t';
    $hash = md5( $salt . rand(0,1000) );
    $url = HTTP . "/pages/login/processlogin.php?email=$email&hash=$hash";

    // Insert a record into the users table 
    # Start TX
    $mysqli->autocommit(FALSE);
    $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    try {
        $statement = "INSERT INTO users (fname, lname, email, is_activated, activation_hash, username, status, employee_id )
                      VALUES
                      ('$fname', '$lname', '$email', 0, '$hash', '$username', 'onboarding', UUID())";

        if ($mysqli->query($statement) === false)
        {
            throw new Exception($mysqli->error);
        }
        $body = "Thanks for signing up!\r\n";
        $body .= "In order to get started you will need to activate your account.\r\n";
        $body .= "Click on the link below to confirm your email address.\r\n";
        $body .= $url . "\r\n";
                
        sendEmail($email,'Verify your account',$body,null,'accounting@catalinacartage.com','jobs@catalinacartage.com');
        $mysqli->commit();
        header("Location: /pages/login/register.php?return=true");
        exit;

    } catch (Exception $e) {
        // An exception has been thrown
        // We must rollback the transaction
        $detailed_message = "Unable to register.";
        if (preg_match('/Duplicate entry \'\w+\' for key \'username\'/',$e->getMessage())) {
            $detailed_message = 'That username appears to be taken.  Please try another.';
        }
        error_log($e->getMessage());
        $url_error = urlencode($detailed_message);
        $mysqli->rollback();
        header("location: /pages/login/register.php?return=false&error=$url_error");
        $mysqli->autocommit(TRUE);
        $mysqli->close();
        exit;
    }
    
}

# Here let's check if someone is trying to validate their email
if (isset($_GET['email']) && isset($_GET['hash']))
{
    $email = $mysqli->real_escape_string($_GET['email']);
    $hash = $mysqli->real_escape_string($_GET['hash']);
    $statement = "SELECT id,username,employee_id FROM users WHERE email = '$email'
                  and activation_hash = '$hash'";
    try {
        if ($result = $mysqli->query($statement)) {
            $row_cnt = $result->num_rows;
            while($obj = $result->fetch_object()){
                $id = $obj->id;
                $username = $obj->username;
                $employee_id = $obj->employee_id;
            }
            if ($row_cnt > 0) {
                # Looks like this was a legitimate link.  Let's set is_activated = 1
                # and redirect
                # Start TX
                $mysqli->autocommit(FALSE);
                $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
                $statement = "UPDATE users SET is_activated = 1 WHERE id = $id";

                if ($mysqli->query($statement) === false)
                {
                    throw new Exception("Error UPDATING users: ".$mysqli->error);
                }
                $mysqli->commit();
                $_SESSION['onboarding'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['employee_id'] = $employee_id;
            }else{
                # This was not a legitimate activation attempt.  
                throw new Exception("Unknown user attempted to register (email or hash not found)");
            }
        }else{
            throw new Exception($mysqli->error);
        }
    } catch (Exception $e) {
        error_log($e);
        $url_error = urlencode("Unable to complete activation.  Please contact us for assistance.");
        $mysqli->rollback();
        header("location: /pages/login/register.php?return=false&error=$url_error");
        $mysqli->autocommit(TRUE);
        $mysqli->close();
        exit;
    } finally {
        $result->close();
        header("location: /pages/dispatch/admin/users.php");
        exit;
    }
}
# Now, let's see if we entered a valid username/password combination.
// Retrieve username and password from database according to user's input
$userName = $mysqli->real_escape_string($userName);
$password = $mysqli->real_escape_string($password);
$statement = "SELECT * FROM users WHERE (username = '" . $userName . "') 
        and (password = '" . $password . "')
        and status = 'Active'";

if ($result = $mysqli->query($statement)) 
{
    $row_cnt = $result->num_rows;
    if ($row_cnt == 1)
    {
        # Okay, we entered a valid username/password combination
        while ($row = mysqli_fetch_assoc($result))
        {
             $_SESSION['userid'] = $row['username'];
             $isadmin = $row['admin'];
             $_SESSION['drivername'] = $row['drivername'];
             $_SESSION['email']      = $row['email'];
             $_SESSION['username']      = $row['username'];
             $_SESSION['fname']      = $row['fname'];
             $_SESSION['lname']      = $row['lname'];
             $_SESSION['employee_id'] = $row['employee_id'];
             $_SESSION['latitude'] = $latitude;
             $_SESSION['longitude'] = $longitude;
        }
        # If I logged in as an admin and I'm really an admin...
        if (isset($admin) && $isadmin == 1)
        {
            $registered_admin = 1;
        }elseif (isset($admin) && $isadmin == 0) {
            $errors['admin'] = "Administrative access is restricted.";
            processErrors($errors);
        }else{
            $registered_admin = 0;
        }
        # Check that the odometer is increasing (not for admins)
        if ($registered_admin == 0)
        {
            checkOdometerIncrements($mysqli, $userName, $truck_id, $truck_odometer);
            # First, make sure the inputs were valid (only if we are not an admin)
            if (checkOdometer($mysqli, $userName, $truck_id) == 0)
            {
                if ($truck_odometer === null)
                {
                    $errors['odometer'] = "Please enter an odometer reading for truck $truck_id";
                    processErrors($errors);
                    exit;
                }
            }
            if (checkRental($mysqli, $truck_id) == 0)
            {
                if ($rental_truck == 'No Rental')
                {
                    $errors['rental'] = "This truck ($truck_id) looks like a rental.  Please choose a rental company";
                    processErrors($errors);
                }
            }
            # Get the coordinates of the requester
            $coordinates = getCoordinates($ipinfodb_api);
            $latitude = $coordinates['latitude'];
            $longitude = $coordinates['longitude'];
            $message = $coordinates['message'];

            # Now insert the coordinates
            $sql = "insert into coordinates (driver_id, latitude, longitude, message, employee_id)
                    values ((select driverid from users where username = '$_SESSION[userid]'),
                    $latitude, $longitude, $message,
                    (select employee_id from users where username = '$_SESSION[userid]'))";

            try {
                if ($mysqli->query($sql) === false)
                {
                    throw new Exception("Unable to insert coordinates: ".$mysqli->error);
                }
            }
            catch (Exception $e)
            {
                error_log($e); 
                header('Location: /pages/errors/500.html');
                exit;
            }
        }
        # Now capture the login
        if ($registered_admin == 1)
        {
            $sql = "INSERT INTO login_capture (driver_driverid, drivername, truck_number, trailer_number, rental, truck_odometer)
               VALUES
               ((SELECT driverid from users WHERE username = '$userName'), '$userName',
               'admintruck', 'admintrailer', 'adminrental','adminodometer')";
        }else{
            # If we're a box truck and don't have a trailer then set it to NULL
            if ($trailer_id == '')
            {
                $trailer_id = 'NULL';
            }
            $sql = "INSERT INTO login_capture (driver_driverid, drivername, truck_number, trailer_number, rental, truck_odometer)
               VALUES
               ((SELECT driverid from users WHERE username = '$userName'), '$userName',
               $truck_id, $trailer_id, '$rental_truck',$truck_odometer)";
        }
        try {
            if ($mysqli->query($sql) === false)
            {
                throw new Exception("Unable to insert record into login_capture: ".$mysqli->error);
            }
        }
        catch (Exception $e)
        {
            error_log($e); 
            header('Location: /pages/errors/500.html');
            exit;
        } 
            
        setCookies($_SESSION['login_username'],$_SESSION['login_password'],$_SESSION['login_truckid'],$_SESSION['login_trailerid'],$_SESSION['login_rentaltruck'],$_SESSION['login_truckodometer']);
        
        unset($_SESSION['login_username']);
        unset($_SESSION['login_password']);
        unset($_SESSION['login_truckid']);
        unset($_SESSION['login_trailerid']);
        unset($_SESSION['login_rentaltruck']);
        unset($_SESSION['login_truckodometer']);
        unset($_SESSION['onboarding']);

        if ($registered_admin == 1)
        {
            $_SESSION['login'] = 1;
        }else{
            $_SESSION['login'] = 2;
        }
        header('Location: /pages/main/index.php');
        exit;
    }else{
        // Login failed
        $_SESSION['login'] = 0;
        $errors['credentials'] = "Invalid username or password";
        unset($_SESSION['login_username']);
        unset($_SESSION['login_password']);
        processErrors($errors);
    }
}

function processErrors($errors)
{
    $getErrors = "error=";
    if (sizeof($errors) > 0)
    {
        foreach ($errors as $key => $value)
        {
            $getErrors .= $key . ",";
        }
        # strip the trailing comma
        $getErrors = rtrim($getErrors,',');
        header("Location: /pages/login/driverlogin.php?$getErrors");
        exit;
    }
}

function checkOdometer($mysqli, $driver, $truck)
{
    # Validate they've entered an odometer reading TODAY
    $statement = "select truck_odometer from login_capture where driver_driverid = 
            (SELECT driverid from users WHERE username = '$driver')
             and truck_number = $truck
             and date_format(login_time, '%Y-%m-%d') = CURRENT_DATE()";
    
    if ($result = $mysqli->query($statement))
    {
        $row_cnt = $result->num_rows;
        return $row_cnt;
    }else{
        throw new Exception("Unable to validate the odometer reading: ".$mysqli->error);
        exit;
    }
}

function checkOdometerIncrements($mysqli, $driver, $truck, $truck_odometer)
{
    # Validate that the odometer reading they entered today has incremented since yesterday
    $statement = "select $truck_odometer - truck_odometer AS difference
            from login_capture 
            where truck_number = $truck 
            and driver_driverid=(SELECT driverid from users WHERE username = '$driver')
            and date_format(login_time, '%Y-%m-%d') < CURRENT_DATE()
            order by login_time desc limit 1";

    if ($result = $mysqli->query($statement))
    {
        $row_cnt = $result->num_rows;
        while ($obj = $result->fetch_object())
        {
            $difference = $obj->difference;
        }
        if ($row_cnt == 0)
        {
            $difference = 0;
        }
    }else{
        throw new Exception("Unable to validate if the odometer is incrementing: ".$mysqli->error);
        exit;
    }

    if ($row < 1)
    {
        # Looks like the odometer reading has not increased
        processErrors($errors);
    }
}

function checkRental($mysqli, $truck)
{
    $statement = "select cattrucks from equipment where cattrucks = $truck";
    if ($result = $mysqli->query($statement))
    {
        $row_cnt = $result->num_rows;
        return $row_cnt;
    }else{
        throw new Exception("Unable to check if this is a rental truck: ".$mysqli->error);
        exit;
    }
}

function setCookies($login_username,$login_password,$login_truckid,$login_trailerid,$login_rentaltruck,$login_truckodometer)
{
    date_default_timezone_set("America/Phoenix");
    $timestamp = strtotime('tomorrow, midnight') - 1;
    setcookie("login_truckid", $login_truckid,$timestamp,'/');
    setcookie("login_trailerid", $login_trailerid,$timestamp,'/');
    setcookie("login_rentaltruck", $login_rentaltruck,$timestamp,'/');
    setcookie("login_truckodometer", $login_truckodometer,$timestamp,'/');
    $timestamp = strtotime('+6 month');
    setcookie("login_username", $login_username,$timestamp,'/');
    setcookie("login_password", $login_password,$timestamp,'/');
}

function getCoordinates($ipinfodb_api) {
    //Load the class
    $ipLite = new ip2location_lite;
    $ipLite->setKey($ipinfodb_api);
 
    //Get errors and locations
    try {
        $locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
        if (!empty($locations) && is_array($locations)) {
            $latitude = $locations['latitude'];
            $longitude = $locations['longitude'];
            $message = 'NULL';   
        }else{
            $errors = $ipLite->getError();
            throw new Exception('Unable to get geolocation data: '. $errors);
        }
    } catch (Exception $e) {
        $latitude = 'NULL';
        $longitude = 'NULL';
        $message = $e->getMessage();
    } finally {
        $output = array("latitude" => $latitude, "longitude" => $longitude, "message" => $message);
        return $output;
    }
}
?>
