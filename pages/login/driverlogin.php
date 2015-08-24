<?php
session_start();
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Driver Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo HTTP;?>/dist/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?php echo HTTP;?>/dist/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo HTTP;?>/dist/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom JS -->
    <script src="<?php echo HTTP;?>/dist/js/catalina_functions.js"></script>
    <script src="<?php echo HTTP;?>/dist/js/catalina_formvalidate.js"></script>

</head>

<body onLoad="getLocation()">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Catalina Driver Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="Driverlogin" name="Driverlogin" method="post" onSubmit="return validateLogin() ; getLocation()"  action="/pages/login/processlogin.php">
                            <fieldset>
                                <div>
                                    <input type="hidden" id=hdn_coordinates name=hdn_coordinates value=''/>
                                </div>
                                <div class="form-group">
                                    <label for="DriverUserName">Username</label>
                                    <?php if (strpos($_GET['error'], 'credentials') !== false) { ?>
                                      <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center">Invalid username or password.</div>
                                    <?php } ?> 
                                    <input class="form-control" placeholder="Username" name="DriverUserName" type="text" autofocus
                                    value='<?php if(isset($_SESSION['login_username'])){ echo $_SESSION['login_username']; }elseif (isset($_COOKIE['login_username'])){ echo $_COOKIE['login_username']; } ?>'>
                                </div>
                                <div class="form-group">
                                    <label for="DriverPassword">Password</label>
                                    <input class="form-control" placeholder="Password" name="DriverPassword" type="password"
                                    value='<?php if(isset($_SESSION['login_password'])){ echo $_SESSION['login_password']; }elseif (isset($_COOKIE['login_password'])){ echo $_COOKIE['login_password']; } ?>'/>
                                </div>
                                <div class="form-group">
                                    <label for="TruckID">Truck Number</label>
                                      <div id="truck_error" class="alert alert-danger" role="alert" style="padding: 1px; text-align: center; display: none;"></div>
                                    <input class="form-control" placeholder="Truck Number" name="TruckID" type="number"
                                    value='<?php if(isset($_SESSION['login_truckid'])){ echo $_SESSION['login_truckid']; }elseif (isset($_COOKIE['login_truckid'])) { echo $_COOKIE['login_truckid']; } ?>'/>
                                </div>
                                <div class="form-group">
                                    <label for="truck_odometer">Odometer</label>
                                    <?php if (strpos($_GET['error'], 'odometer_inc') !== false) { ?>
                                      <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center">
                                        The odometer has not increased since last login (previous to today).</div>
                                    <?php } ?>
                                      <div id="odo_error1" class="alert alert-danger" role="alert" style="padding: 1px; text-align: center; display: none;"></div>
                                      <div id="odo_error2" class="alert alert-danger" role="alert" style="padding: 1px; text-align: center; display: none;"></div>
                                    <input class="form-control" placeholder="Odometer" name="truck_odometer" type="number"
                                    value='<?php if(isset($_SESSION['login_truckodometer'])){ echo $_SESSION['login_truckodometer']; }elseif (isset($_COOKIE['login_truckodometer'])){ echo $_COOKIE['login_truckodometer']; } ?>' data-toggle="popover" data-placement="top" data-content="It's okay to have the same odometer entry each day." data-trigger="focus"/>
                                </div>
                                <div class="form-group">
                                    <label for="LoadPosition">Trailer Number</label>
                                      <div id="trailer_error" class="alert alert-danger" role="alert" style="padding: 1px; text-align: center; display: none;"></div>
                                    <input class="form-control" placeholder="Trailer" name="LoadPosition" type="number" 
                                    value='<?php if(isset($_SESSION['login_trailerid'])){ echo $_SESSION['login_trailerid']; }elseif (isset($_COOKIE['login_trailerid'])){ echo $_COOKIE['login_trailerid']; } ?>' data-toggle="popover" data-placement="top" data-content="If you switch trailers during the day please update your new trailer." data-trigger="focus"/>
                                </div>
                                <div class="form-group">
                                    <label for="rentaltrucks">Rental</label>
                                    <?php if (strpos($_GET['error'], 'rental') !== false) { ?>
                                      <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center">
                                        This Truck looks like a rental, please choose rental company.</div>
                                    <?php } ?>
                                    <select class="form-control" name="rentaltrucks" id="rentaltrucks">
                                    <?php
                                     $rentals = array('No Rental','Ryder','RWC','Penske','Enterprise','Other');
                                     foreach ($rentals as $i)
                                     {
                                         ?><option<?php if ($_SESSION['login_rentaltruck'] == $i){ echo " selected=selected"; }elseif ($_COOKIE['login_rentaltruck'] == $i){echo " selected=selected"; } ?>><?php echo $i; ?></option><?php echo "\n"; ?>
                                       <?php
                                     } ?>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="AdminLogin" id="AdminLogin" type="checkbox" value="admin">Administrative Login
                                    </label>
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Login">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo HTTP;?>/dist/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo HTTP;?>/dist/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo HTTP;?>/dist/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Initialize Popover -->
    <script>
    $(function () {
     $('[data-toggle="popover"]').popover()
    })
    </script>


</body>

</html>
