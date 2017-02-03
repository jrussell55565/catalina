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

    <!-- Recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">New user registration</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" id="register" name="register" method="post" action="/pages/login/processlogin.php" onSubmit="return validateRegistration(); false">
                            <fieldset>
                                <div class="form-group">
                                    <label for="fname">First name</label>
                                    <input class="form-control" placeholder="" name="fname" type="text" autofocus value='' id="fname">
                                    <div id="fname_error"  class="alert alert-danger" role="alert" style="margin-top: 5px; padding: 1px; text-align: center; display: none;"></div>
                                    <label for="lname">Last name</label>
                                    <input class="form-control" placeholder="" name="lname" type="text" autofocus value='' id="lname">
                                    <div id="lname_error" class="alert alert-danger" role="alert" style="margin-top: 5px; padding: 1px; text-align: center; display: none;"></div>
                                    <label for="username">Username</label>
                                    <input class="form-control" placeholder="" name="username" type="text" autofocus value='' id="username">
                                    <div id="username_error" class="alert alert-danger" role="alert" style="margin-top: 5px; padding: 1px; text-align: center; display: none;"></div>
                                    <label for="email">Email</label>
                                    <input class="form-control" placeholder="" name="email" type="text" autofocus value='' id="email">
                                    <div id="email_error" class="alert alert-danger" role="alert" style="margin-top: 5px; padding: 1px; text-align: center; display: none;"></div>
                                    <div class="panel-body">
                                    <?php if ($_GET['return'] == 'true'){ ?>
                                      <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center">
                                        An email verification was sent to the email address provided.
                                      </div>
                                    <?php }elseif ($_GET['return'] == 'false'){ ?>
                                      <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center">
                                        <?php echo $_GET['error'];?>
                                      </div>
                                    <?php }else{ ?>
                                    <p>Please enter your name and email address to create your account
                                    <?php } ?>
                                    <input type="hidden" id="register" name="register" value="true">
                                    </div>
                                </div>
                                <div class="g-recaptcha" data-sitekey="6LczKRQUAAAAAIWVKzezUqNlCH5qms23bAmI4vFQ" style="padding-left: 5%;"></div>
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Sign up">
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
