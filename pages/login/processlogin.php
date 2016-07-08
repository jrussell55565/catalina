<?php
// Inialize session
session_start();
// Include database connection settings
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

$userName 	= trim($_POST["DriverUserName"]);
$password 	= trim($_POST["DriverPassword"]);
$truck_id 	= trim($_POST["TruckID"]);
$trailer_id	= trim($_POST["LoadPosition"]);
$email		= trim($_POST["DriverEmail"]);
$admin		= $_POST["AdminLogin"];
$rental_truck    = $_POST["rentaltrucks"];
$truck_odometer = trim($_POST["truck_odometer"]);
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
            # Now insert the coordinates
            $sql = "insert into coordinates (driver_id, latitude, longitude)
               values ((select driverid from users where username = '$_SESSION[userid]'),
                       $latitude, $longitude)";
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
?>
