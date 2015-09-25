<?php
// Inialize session
session_start();
// Include database connection settings
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

$userName 	= $_POST["DriverUserName"];
$password 	= $_POST["DriverPassword"];
$truckId 	= $_POST["TruckID"];
$trailerId	= $_POST["LoadPosition"];
$email		= $_POST["DriverEmail"];
$admin		= $_POST["AdminLogin"];
$rentalTruck    = $_POST["rentaltrucks"];
$truckOdometer = $_POST["truck_odometer"];
$coordinates    = explode('|',$_POST["hdn_coordinates"]);
$latitude = $coordinates[0];
$longitude = $coordinates[1];
$errors = array();
$getErrors = "error=";

# Create session variables so that we can repopulate
# driver login if we need to.
$_SESSION['login_username'] = $userName;
$_SESSION['login_password'] = $password;
$_SESSION['login_truckid'] = $truckId;
$_SESSION['login_trailerid'] = $trailerId;
# Setup duplicate-esque sessions variables for truck and trailer
$_SESSION['login_rentaltruck'] = $rentalTruck;
$_SESSION['login_truckodometer'] = $truckOdometer;
$_SESSION['truckid'] = $truckId;
$_SESSION['trailerid'] = $trailerId;

# setup the database connection
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

# First, if we've gotten here via the forgotPassword page then we'll just 
# validate and skip the rest

if ($_POST['forgotPassword'] == 'true')
{
    $forgottenUser = $_POST['DriverUserName'];
    $sql = "SELECT email FROM users WHERE username = '" . mysql_real_escape_string($forgottenUser) . "'"; 
    $login = mysql_query($sql);
    if (!$login) {
        die('Invalid query: ' . mysql_error());
    }
    if (mysql_num_rows($login) == 1)
    {
        $row = mysql_fetch_array($login, MYSQL_BOTH);
        $forgottenEmail = $row['email'];
        sendEmail('dispatch@catalinacartage.com',"Password Request","$forgottenUser has requested a new password.\r\nPlease send a new password to $forgottenEmail");
    }
    header("Location: /pages/login/forgot.php?return=true");
}

// Check username and password match
if (mysql_num_rows($login) == 1)
{
        while ($row = mysql_fetch_array($login, MYSQL_BOTH))
        {
}

$login = mysql_query($sql);
}

if ($admin)
{
	$admin = "1";
}else{
	$admin = "0";
}

if ($admin == 0)
{
    # First, make sure the inputs were valid (only if we are not an admin)
    if (checkOdometer($userName, $truckId) == 0)
    {
        if ($truckOdometer == null)
        {
            $errors['odometer'] = "Please enter an odometer reading for truck $truckId";
            processErrors($errors);
            exit;
        }
    }
    if (checkRental($truckId) == 0)
    {
        if ($rentalTruck == 'No Rental')
        {
            $errors['rental'] = "This truck ($truckId) looks like a rental.  Please choose a rental company";
            processErrors($errors);
        }
    }
}

// Retrieve username and password from database according to user's input
$sql = "SELECT * FROM users WHERE (username = '" . mysql_real_escape_string($userName) . "') 
        and (password = '" . mysql_real_escape_string($password) . "')";

$login = mysql_query($sql);
if (!$login) {
    die('Invalid query: ' . mysql_error());
}

// Check username and password match
if (mysql_num_rows($login) == 1)
{
        while ($row = mysql_fetch_array($login, MYSQL_BOTH))
        {
                $_SESSION['userid'] = $row['username'];
                $isadmin = $row['admin'];
                $_SESSION['drivername'] = $row['drivername'];
                $_SESSION['email']      = $row['email'];
        }
  
        # Check that the odometer is increasing (not for admins)
        if ($isadmin != 1)
        {
            checkOdometerIncrements($userName, $truckId, $truckOdometer);
        }

        $sql = "insert into coordinates (driver_id, latitude, longitude)
                values ((select driverid from users where username = '$_SESSION[userid]'),
                        $latitude, $longitude)";

        $output = mysql_query($sql);

        # Insert into the login_capture table
        $sql = "INSERT INTO login_capture (driver_driverid, truck_number, trailer_number, rental, truck_odometer)
                VALUES
                ((SELECT driverid from users WHERE username = '$userName'), 
                $truckId, $trailerId, '$rentalTruck',$truckOdometer)";

        $output = mysql_query($sql);

        setCookies($_SESSION['login_username'],$_SESSION['login_password'],$_SESSION['login_truckid'],$_SESSION['login_trailerid'],$_SESSION['login_rentaltruck'],$_SESSION['login_truckodometer']);
        unset($_SESSION['login_username']);
        unset($_SESSION['login_password']);
        unset($_SESSION['login_truckid']);
        unset($_SESSION['login_trailerid']);
        unset($_SESSION['login_rentaltruck']);
        unset($_SESSION['login_truckodometer']);
        if ($isadmin == 1)
        {
                if ($admin == 1)
                {
                        $_SESSION['login'] = 1;
                        header('Location: /pages/main/adminindex.php');
                }else{
                        $_SESSION['login'] = 2;
                        header('Location: /pages/main/index.php');
                }
        }else{
                $_SESSION['login'] = 2;
                header('Location: /pages/main/index.php');
        }
} else {
        // Login failed
        $_SESSION['login'] = 0;
        $errors['credentials'] = "Invalid username or password";
        unset($_SESSION['login_username']);
        unset($_SESSION['login_password']);
        processErrors($errors);
}

# Now if the errors array is larger than zero we create the GET
# request and send it back to the login page.


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

function checkOdometer($driver, $truck)
{
    # Validate they've entered an odometer reading TODAY
    $sql = "select truck_odometer from login_capture where driver_driverid = 
            (SELECT driverid from users WHERE username = '$driver')
             and truck_number = $truck
             and date_format(login_time, '%Y-%m-%d') = CURRENT_DATE()";
    
    $result = mysql_query($sql);
    if (! $result)
    {
        echo "There was an error trying to query for mileage";
        exit;
    } 
    $num_rows = mysql_num_rows($result);
    return $num_rows;
}

function checkOdometerIncrements($driver, $truck, $truckOdometer)
{
    # Validate that the odometer reading they entered today has incremented since yesterday
    $sql = "select $truckOdometer - truck_odometer AS difference
            from login_capture 
            where truck_number = $truck 
            and driver_driverid=(SELECT driverid from users WHERE username = '$driver')
            and date_format(login_time, '%Y-%m-%d') < CURRENT_DATE()
            order by login_time desc limit 1";

    $result = mysql_query($sql);
    if (! $result)
    {
        echo "There was an error trying to query for mileage";
        exit;
    } 
    $row = mysql_fetch_array($result, MYSQL_BOTH);
    if ($row['difference'] < 1)
    {
        # Looks like the odometer reading has not increased
        #$errors['odometer_inc'] = "The odometer reading has not increased.";
        processErrors($errors);
    }
}

function checkRental($truck)
{
    $sql = "select cattrucks from equipment where cattrucks = $truck";
    $result = mysql_query($sql);
    if (! $result)
    {
        echo "There was an error trying to query for rentals";
        exit;
    } 
    $num_rows = mysql_num_rows($result);
    return $num_rows;
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
