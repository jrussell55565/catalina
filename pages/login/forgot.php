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

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Forgot Password</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="forgotPassword" name="forgotPassword" method="post" action="/pages/login/processlogin.php">
                            <fieldset>
                                <div class="form-group">
                                    <label for="DriverUserName">Username or Email</label>
                                    <input class="form-control" placeholder="Username or Email" name="DriverUserName" type="text" autofocus value=''>
                                    <div class="panel-body">
                                    <?php if ($_GET['return'] == 'true'){ ?>
                                      <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center">
                                        An email was sent to dispatch requesting your password.
                                      <a href="<?php echo HTTP;?>/pages/login/driverlogin.php">Login Page</a></div>
                                    <?php }else{ ?>
                                    <p>Enter your username or email address and dispatch will send your password to the email account associated with this username.</p>
                                    <input type="hidden" id="forgotPassword" name="forgotPassword" value="true">
                                    <?php } ?>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Submit">
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
