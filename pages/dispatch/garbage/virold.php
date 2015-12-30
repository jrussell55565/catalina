<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: driverlogin.php');
}

include('global.php');
include('functions.php');

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_GET['username'];
$hawbnumber = $_GET['hawbnumber'];
$userid = $_GET['userid'];
$drivername = $_SESSION['drivername'];
$exportdest = $_GET['exportdest'];
$recordid = $_GET['recordid'];
$trailer = $_SESSION['trailer'];

$sql = mysql_query("select pieces,pallets from dispatch WHERE recordID=$recordid");

        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
        $pieces = $row[pieces];
        $pallets = $row[pallets];
        }

$sql = mysql_query("select FROM_UNIXTIME(arrivedShipperTime),arrivedShipperTime from dispatch WHERE recordID=$recordid");

        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
        	$arrivedTime = $row[0];
        	$arrivedTimeUnix = $row[1];
        }
	$splitArrivedTime = explode(" ",$arrivedTime);
	$duration = round((time() - $arrivedTimeUnix) / 60);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>AdminLTE 2 | Dashboard</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
      
      <header class="main-header">
        <!-- Logo -->
        <a href="../../index2.html" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>A</b>LT</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><?php include 'dashboardtopleft.php'; ?></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 4 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li><!-- end message -->
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>
              <!-- Tasks: style can be found in dropdown.less -->
              <li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- Task item -->
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li><!-- end task item -->
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                  <span class="hidden-xs"><?php echo "$drivername"; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                    <p>
                      Alexander Pierce - Web Developer
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="col-xs-4 text-center">
                      <a href="#">Followers</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Sales</a>
                    </div>
                    <div class="col-xs-4 text-center">
                      <a href="#">Friends</a>
                    </div>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="#" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <!-- =============================================== -->

      <!-- Left side column. contains the sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
              <p><?php echo "$drivername"; ?></p>

              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->

<ul class="sidebar-menu">
<li class="header"></li>
<li class="active treeview">
<?php include 'sidebarmenu.php'; ?>
</ul>

        </section>
        <!-- /.sidebar -->
      </aside>

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Vehicle Condition
            <small>Pre &amp; Post Trips start here</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Dispatch Board</a></li>
            <li class="active">Vehicle Condition</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">VIR Select Unit</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" border="1">
                  <tr>
                    <td colspan="4">Start Time:
<input name="<?php echo constant('BX_LT'); ?>" type="text" id="<?php echo constant('BX_LT'); ?>" value="<?php echo $localtime; ?>" size="8"/>
                      Date
                      <input name="<?php echo constant('BX_LD'); ?>" type="text" id="<?php echo constant('BX_LD'); ?>" value="<?php echo $localdate; ?>" size="8" readonly="readonly"/>

                  </tr>
                  <tr>
                    <td>Truck
                    <td><input name="<?php echo constant('BX_TI'); ?>2" type="text" id="<?php echo constant('BX_TI'); ?>" value="<?php echo $truck; ?>" size="8" readonly="readonly" />                    
                    <td colspan="2"><a href="vir.php">View Last Inspection</a>                      </tr>
                  <tr>
                    <td>Trailer
                    <td><input name="<?php echo constant('BX_LP'); ?>2" type="text" id="<?php echo constant('BX_LP'); ?>2" value="<?php echo $trailer; ?>" size="8" readonly="readonly" />                    
                    <td colspan="2"><a href="vir.php">View Last Inspection </a>                      </tr>
                  <tr>
                    <td colspan="4"><div align="center">Pre Trip:
                        <input type="checkbox" name="cbpretrip2" value="cbpretrip" id="cbpretrip2"/>
Post Trip:
<input type="checkbox" name="cbposttrip2" value="cbposttrip" id="cbposttrip2"/>
                    </div>                  </tr>
                  <tr>
                    <td><div align="center">Unit Type</div>                        
                  <td colspan="3"><div align="center">Condition of Vehicle</div>                  </tr>
                  <tr>
                    <td width="89"><a href="vir.php"><img src="images/semi.gif" alt="Semi" width="74" height="45"></a>                  
                    <td width="74" bgcolor="#33FF00">
                    
                        <div align="center">Green
                        <input type="checkbox" name="cbgreensemi" id="cbgreensemi">
                        <label for="cbgreensemi"></label>
                        </div>
                        
                    <td width="69" bgcolor="#FFFF00"><div align="center">Yellow
                        <input type="checkbox" name="cbyellowsemi" id="cbyellowsemi">
                        <label for="cbyellowsemi"></label>
                    </div>
                    <td width="73" bgcolor="#FF0000"><div align="center">Red
                        <input type="checkbox" name="cbredsemi" id="cbredsemi">
                        <label for="cbredsemi"></label>
                    </div>                    
                  </tr>
                  <tr>
                    <td><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire">
                    <td bgcolor="#33FF00">
                      <div align="center">Green
                        <input type="checkbox" name="cbgreentrailer" id="cbgreentrailer">
                        <label for="cbgreentrailer"></label>
                      </div>
                      
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowtrailer" id="cbyellowtrailer">
                      <label for="cbyellowtrailer"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredtrailer" id="cbredtrailer">
                      <label for="cbredsemi2"></label>
                    </div>                    </tr>
                  <tr>
                    <td><a href="vir.php"><img src="images/trailer.gif" alt="Trailer" width="77" height="38"></a>
                    <td bgcolor="#33FF00">
                      <div align="center">Green
                        <input type="checkbox" name="cbgreenboxtruck" id="cbgreenboxtruck">
                        <label for="cbgreenboxtruck"></label>
                      </div>
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowboxtruck" id="cbyellowboxtruck">
                      <label for="cbyellowboxtruck"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredboxtruck" id="cbredboxtruck">
                      <label for="cbredsemi3"></label>
                    </div>                    </tr>
                  <tr>
                    <td><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire">
                    <td bgcolor="#33FF00">

                      <div align="center">Green
                        <input type="checkbox" name="cbgreensprinter" id="cbgreensprinter">
                        <label for="cbgreensprinter"></label>
                      </div>
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowsprinter" id="cbyellowsprinter">
                      <label for="cbyellowsprinter"></label>
                    </div>                  
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredsprinter" id="cbredsprinter">
                      <label for="cbredsprinter"></label>
                    </div>                  
                  </tr>
                  <tr>
                    <td><a href="vir.php"><img src="images/Boxtruck.gif" alt="BoxTruck" width="73" height="41"></a>                    
                    <td bgcolor="#33FF00"><div align="center">Green
                      <input type="checkbox" name="cbgreensprinter2" id="cbgreensprinter2">
                      <label for="cbgreensprinter2"></label>
                    </div>                    
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowsprinter2" id="cbyellowsprinter2">
                      <label for="cbyellowsprinter2"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredsprinter2" id="cbredsprinter2">
                      <label for="cbredsprinter2"></label>
                    </div>                    </tr>
                  <tr>
                    <td><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire">
                    <td bgcolor="#33FF00"><div align="center">Green
                      <input type="checkbox" name="cbgreensprinter3" id="cbgreensprinter3">
                      <label for="cbgreensprinter3"></label>
                    </div>                    
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowsprinter3" id="cbyellowsprinter3">
                      <label for="cbyellowsprinter3"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredsprinter3" id="cbredsprinter3">
                      <label for="cbredsprinter3"></label>
                    </div>                    
                  </tr>
                  <tr>
                    <td><a href="vir.php"><img src="images/sprinter.gif" alt="Sprinter" width="71" height="51"></a>
                    <td bgcolor="#33FF00"><div align="center">Green
                      <input type="checkbox" name="cbgreensprinter4" id="cbgreensprinter4">
                      <label for="cbgreensprinter4"></label>
                    </div>                    
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowsprinter4" id="cbyellowsprinter4">
                      <label for="cbyellowsprinter4"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredsprinter4" id="cbredsprinter4">
                      <label for="cbredsprinter4"></label>
                    </div>                    
                  </tr>
                  <tr>
                    <td><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"><img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire">
                    <td bgcolor="#33FF00"><div align="center">Green
                      <input type="checkbox" name="cbgreensprinter5" id="cbgreensprinter5">
                      <label for="cbgreensprinter5"></label>
                    </div>                    
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowsprinter5" id="cbyellowsprinter5">
                      <label for="cbyellowsprinter5"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredsprinter5" id="cbredsprinter5">
                      <label for="cbredsprinter5"></label>
                    </div>                    </tr>
                  <tr>
                    <td colspan="4"><div align="center"> <A HREF="#submit inspection">Go To Submit</A></div>                  
                  </tr>
                </table>
              </form>
            </div><!-- /.box-body -->
            <div class="box-footer">If not All Green Additional Items below  as necessary</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->

          <!-- Default box -->
           <div class="box"> 
<!--Remove the div Class "box" above and add below primary collapsed -->
<!--     <div class="box box-primary collapsed-box">  -->
            <div class="box-header with-border">
              <h3 class="box-title">Truck VIR  <img src="images/truckimages/semimini.gif" width="25" height="15"><img src="images/truckimages/Boxtruckmini.gif" width="25" height="16"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="310" border="1">
                  <tr>
                    <td width="300" colspan="2"><div align="center"> Truck Inspection</div>
                      <div align="center"></div>
                      <div align="center">
                      <div align="center"></div>
                  </tr>
                  <tr>
                    <td><div align="center"><a href="VIR.php"><img src="images/truckimages/semitruckall.gif" alt="" width="121" height="121"><img src="images/truckimages/boxtruckall.gif" width="121" height="82"></a></div>
                  </td>
                  <tr>
                    <td height="322"><table width="300" border="1">
                    <tr>
                          <td width="44"><input name="cb_aircompressor" type="checkbox" id="cb_aircompressor" value="Air Compressor" /></td>
                          <td>Air Compressor
                            <label for="Conditions"></label></td>
                        </tr>
                    <tr>
                      <td width="44"><input name="cb_airlines" type="checkbox" id="cb_airlines" value="Air Lines" /></td>
                      <td>Air Lines</td>
                    </tr>
                    <tr>
                      <td width="44"><input name="cb_ac" type="checkbox" id="cb_ac" value="AC" /></td>
                      <td>AC</td>
                    </tr>
                    <tr>
                      <td width="44"><input name="cb_alternator" type="checkbox" id="cb_alternator" value="Alternator" /></td>
                      <td>Alternator</td>
                    </tr>
                    <tr>
                      <td width="44"><input name="cb_battery" type="checkbox" id="cb_battery" value="Battery, Electrical, Wires" /></td>
                      <td>Battery, Electrical, Wires</td>
                      </tr>
                        <tr>
                          <td><input name="cb_bodyframe" type="checkbox" id="cb_bodyframe" value="Body,Frame,Assembly" /></td>
                          <td>Body, Frame, Assembly</td>
                        </tr>
                        <tr>
                          <td><input name="cb_breaks" type="checkbox" id="cb_breaks" value="Brakes and Accessories" /></td>
                          <td>Brakes and Accessories</td>
                        </tr>
                        <tr>
                          <td><input name="cb_clutch" type="checkbox" id="cb_clutch" value="Clutch" /></td>
                          <td>Clutch</td>
                        </tr>
                        <tr>
                          <td><input name="cb_defroster" type="checkbox" id="cb_defroster" value="Defroster" /></td>
                          <td>Defroster</td>
                        </tr>
                        <tr>
                          <td><input name="cb_driveline" type="checkbox" id="cb_driveline" /></td>
                          <td>Drive Line</td>
                        </tr>
                        <tr>
                          <td><input name="cb_engine" type="checkbox" id="cb_engine" value="Engine" /></td>
                          <td>Engine</td>
                        </tr>
                        <tr>
                          <td><input name="cb_exhaust" type="checkbox" id="cb_exhaust" value="Exhaust" /></td>
                          <td>Exhaust</td>
                        </tr>
                        <tr>
                          <td><input name="cb_fifthwheel" type="checkbox" id="cb_fifthwheel" value="Fifth Wheel" /></td>
                          <td>Fifth Wheel</td>
                        </tr>
                        <tr>
                          <td><input name="cb_frontaxel" type="checkbox" id="cb_frontaxel" value="Front Axel" /></td>
                          <td>Front Axel</td>
                        </tr>
                        <tr>
                          <td><input name="cb_feultanks" type="checkbox" id="cb_feultanks" value="Fuel Tanks" /></td>
                          <td>Fuel Tanks</td>
                        </tr>
                        <tr>
                          <td><input name="cb_heater" type="checkbox" id="cb_heater" value="Heater" /></td>
                          <td>Heater</td>
                        </tr>
                        <tr>
                          <td><input name="cb_horn" type="checkbox" id="cb_horn" /></td>
                          <td>Horn</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Lights: Head,stop,tail,dash,turn</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Mirrors</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Muffler</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Oil Pressure</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Raidiator</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Registration Insurance</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Rear End</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Reflectors</td>
                        </tr>
                        <tr>
                          <td height="3"><input type="checkbox" /></td>
                          <td>Saftey Equipment</td>
                        </tr>
                        <tr>
                          <td height="5"><input type="checkbox" /></td>
                          <td>Springs</td>
                        </tr>
                        <tr>
                          <td height="-4"><input type="checkbox" /></td>
                          <td>Starter</td>
                        </tr>
                        <tr>
                          <td height="-2"><input type="checkbox" /></td>
                          <td>Steering</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Tires</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Tire Chains</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Transmission</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Wheels</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Windows</td>
                        </tr>
                        <tr>
                          <td><input type="checkbox" /></td>
                          <td>Windshield</td>
                        </tr>
                        <tr>
                          <td height="1"><input type="checkbox" /></td>
                          <td>Other</td>
                        </tr>
                        <tr>
                          <td height="3" colspan="2"><select name="Conditions" id="Conditions2">
                            <option selected>Condition</option>
                            <option>Bent</option>
                            <option>Cracked</option>
                            <option>Damaged</option>
                            <option>Expired</option>
                            <option>Leaking</option>
                            <option>Loose</option>
                            <option>Non Op.</option>
                            <option>Missing</option>
                            <option>Replace</option>
                            <option>Unsecured</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td height="50">Notes</td>
                          <td><textarea name="textarea" id="textarea2" cols="43" rows="3"></textarea></td>
                        </tr>
                      </table></td>
                    <script type="text/javascript">
		var checkDisplay = function(check, form) { //check ID, form ID
			form = document.getElementById(form), check = document.getElementById(check);
			check.onclick = function(){
				form.style.display = (this.checked) ? "block" : "none";
				form.reset();
			};
			check.onclick();
		};
                </script>
                  </tr>
                </table>
              </form>
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->
          



          <!-- Default box -->
	        <div class="box"> 
<!--Remove the div Class "box" above and add below primary collapsed -->
<!--          <div class="box box-primary collapsed-box"> --> 
            <div class="box-header with-border">
              <h3 class="box-title">Trailer VIR</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="310" border="1">
                  <tr>
                    <td width="300" colspan="2"><div align="center">Trailer Inspection</div>
                      <div align="center"></div>
                      <div align="center">
                      <div align="center"></div>
                  </tr>
                  <tr>
                    <td><div align="center"><a href="VIR.php"><img src="images/trailer.gif" alt="" width="121" height="60"></a></div></td>
                  <tr>
                    <td height="322"><table width="300" border="1">
                      <tr>
                        <td width="34"><input type="checkbox" /></td>
                        <td width="222">Air Bag Suspension</td>
                        <td width="22" rowspan="17"><label for="Conditions"></label></td>
                      </tr>
                      <tr>
                        <td width="34"><input type="checkbox" /></td>
                        <td>Brake Connections</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Brakes</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Coupling devices</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Doors</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Floors</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Hitch</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>King pin</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Landing gear</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Lights (all)</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Registration &amp;  Insurance</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Roof</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Springs</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Tires</td>
                      </tr>
                      <tr>
                        <td><input type="checkbox" /></td>
                        <td>Wheels</td>
                      </tr>
                      <tr>
                        <td height="10"><input type="checkbox" /></td>
                        <td>Other</td>
                      </tr>
                      <tr>
                        <td height="12"><input type="checkbox" /></td>
                        <td><select name="Conditions2" id="Conditions3">
                          <option selected>Condition</option>
                          <option>Bent</option>
                          <option>Cracked</option>
                          <option>Damaged</option>
                          <option>Expired</option>
                          <option>Leaking</option>
                          <option>Loose</option>
                          <option>Non Op.</option>
                          <option>Missing</option>
                          <option>Replace</option>
                          <option>Unsecured</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td height="50">Notes</td>
                        <td colspan="2"><textarea name="textarea2" id="textarea" cols="43" rows="3"></textarea></td>
                      </tr>
                    </table></td>
                    <script type="text/javascript">
		var checkDisplay = function(check, form) { //check ID, form ID
			form = document.getElementById(form), check = document.getElementById(check);
			check.onclick = function(){
				form.style.display = (this.checked) ? "block" : "none";
				form.reset();
			};
			check.onclick();
		};
                </script>
                  </tr>
                </table>
              </form>
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->
          


          <!-- Default box -->
           <div class="box"> 
<!--Remove the div Class "box" above and add below primary collapsed -->
<!--      <div class="box box-primary collapsed-box"> --> 
            <div class="box-header with-border">
              <h3 class="box-title">Tires Semi + Trailer Combo <img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="1094" border="1">
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Truck &amp; Trailer Tires (Combo)</div>                    </tr>
                  <tr>
                    <td height="88" colspan="4"><div align="center"><a href="VIR.php"><img src="images/tires.gif" alt="" width="98" height="86"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="86">                    <div align="center">
                      <p>Driver Steer
                        <select name="Conditions4" id="Conditions4">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions4" id="Conditions5">
<option>125</option>
<option>120</option>
<option>115</option>
<option>110</option>
<option selected>105</option>
<option>100</option>
<option>95</option>
<option>90</option>
<option>80</option>
<option>85</option>
<option>75</option>
<option>70</option>
<option>65</option>
<option>60</option>
<option>50</option>
<option>55</option>
<option>40</option>
<option>30</option>
<option>20</option>
<option>10</option>
                        </select>
                      </p>
                    </div>
                    <td width="93" rowspan="7">
                    <img src="images/truckimages/semiandtrailertop.gif" width="105" height="793">                    
                    <td width="93" height="86"><div align="center">
                      <p>Pasg Steer                      
                        <select name="Conditions5" id="Conditions6">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions18" id="Conditions23">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option selected>105</option>
                          <option>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="50">
                    <td>                                                            
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>Drives Front D
                        <select name="Conditions25" id="Conditions26">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions25" id="Conditions27">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions25" id="Conditions28">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                    <td><div align="center">
                      <p>Drives
                        Front P
                        <select name="Conditions26" id="Conditions29">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions26" id="Conditions30">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                        <select name="Conditions26" id="Conditions31">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>Drives
                        Rear D
                        <select name="Conditions28" id="Conditions35">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions28" id="Conditions36">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions28" id="Conditions37">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>
                    <td height="117"><div align="center">
                      <p>Drives Rear P
                        <select name="Conditions27" id="Conditions32">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions27" id="Conditions33">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions27" id="Conditions34">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    </tr>
                  <tr>
                    <td height="186">
                    <td height="186"></tr>
                  <tr>
                    <td height="123"><div align="center">
                      <p>Trailer Front
                        D
                        <select name="Conditions32" id="Conditions47">
                      <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions32" id="Conditions48">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions32" id="Conditions49">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                    <td height="123"><div align="center">
                      <p>Trailer Front
                        P
                          <select name="Conditions31" id="Conditions44">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions31" id="Conditions45">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions31" id="Conditions46">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>Trailer Rear
                        D
                        <select name="Conditions29" id="Conditions38">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions29" id="Conditions39">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions29" id="Conditions40">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                    <td height="117"><div align="center">
                      <p>Trailer Rear
                        P
                          <select name="Conditions30" id="Conditions41">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions30" id="Conditions42">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions30" id="Conditions43">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                  </tr>
                  <tr>
                  <td height="24" colspan="3">Enter Notes Below for Tire Info!</tr>
                  <tr>
                    <td height="24" colspan="3"><textarea name="textarea3" id="textarea3" cols="43" rows="3"></textarea>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm Semi &amp; Semi Tire Inspection</div>                  
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->




          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
      <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title">Tires  Trailer Only <img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="865" border="1">
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Trailer Tires (if you add addition trailer on shift)</div>                    
                  </tr>
                  <tr>
                    <td height="88" colspan="4"><div align="center"><a href="VIR.php"><img src="images/tires.gif" alt="" width="98" height="86"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="164">
                    <td width="93" rowspan="5">
                    <img src="images/truckimages/traileronly.gif" width="105" height="594">                    
                    <td width="93">                  </tr>
                  <tr>
                    <td height="101">                  
                    <td width="93">                    
                  </tr>
                  <tr>
                    <td height="24">
                    <td height="24"></tr>
                  <tr>
                    <td height="159"><div align="center">
                      <p>Trailer Front D
                        <select name="Conditions3" id="Conditions">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions3" id="Conditions12">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions3" id="Conditions13">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>
                    <td height="159"><div align="center">
                      <p>Trailer Front P
                        <select name="Conditions11" id="Conditions14">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions11" id="Conditions15">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions11" id="Conditions16">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    </tr>
                  <tr>
                    <td height="24"><div align="center">
                      <p>Trailer Rear
                        D
                        <select name="Conditions12" id="Conditions17">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions12" id="Conditions18">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions12" id="Conditions19">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                    <td height="24"><div align="center">
                      <p>Trailer Rear
                        P
                        <select name="Conditions13" id="Conditions20">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions13" id="Conditions21">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions13" id="Conditions22">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                  </tr>
                  <tr>
                  <td height="42" colspan="3">Enter Notes Below for Tire Info!</tr>
                  <tr>
                    <td height="24" colspan="3"><textarea name="textarea3" id="textarea3" cols="43" rows="3"></textarea>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm Trailer Tire Inspection</div>                  
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->









          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
    <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title">Tires Box Truck <img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="584" border="1">
                  <tr>
                    <td colspan="4"><div align="center"> Tire Inspection</div>                    </tr>
                  <tr>
                    <td colspan="4"><div align="center"><a href="VIR.php"><img src="images/tires.gif" alt="" width="98" height="86"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="86">                    <div align="center">
                      <p>D Steer
                        <select name="Conditions4" id="Conditions4">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions4" id="Conditions5">
<option>125</option>
<option>120</option>
<option>115</option>
<option>110</option>
<option selected>105</option>
<option>100</option>
<option>90</option>
<option>80</option>
<option>70</option>
<option>60</option>
<option>50</option>
<option>40</option>
<option>30</option>
<option>20</option>
<option>10</option>
                        </select>
                      </p>
                    </div>
                    <td width="93" height="149" rowspan="4"><img src="images/truckimages/Box_Truck_Top.gif" width="121" height="336">                                        
                    <td width="93" height="86"><div align="center">
                      <p>P Steer                      
                        <select name="Conditions5" id="Conditions6">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions6" id="Conditions7">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option selected>105</option>
                          <option>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="113">                                                            
                    <td height="113">                                                            
                  </tr>
                  <tr>
                    <td height="71"><div align="center">
                      <p>DFDrive
                        <select name="Conditions7" id="Conditions8">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions9" id="Conditions9">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions23" id="Conditions24">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                                                            
                    <td height="71"><div align="center">
                      <p>PF Drive
                        <select name="Conditions8" id="Conditions10">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions10" id="Conditions11">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions24" id="Conditions25">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="86">
                    <td height="86"></tr>
                  <tr>
                  <td height="24" colspan="3"><div align="center"> 
                    <input type="checkbox" />
                  Confirm Box Truck Tire Inspection</div>                    </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->





          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
     <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title">Tires  Sprinter <img src="images/truckimages/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="488" border="1">
                  <tr>
                    <td colspan="4"><div align="center"> Tire Inspection Sprinter</div>                    </tr>
                  <tr>
                    <td colspan="4"><div align="center"><a href="VIR.php"><img src="images/tires.gif" alt="" width="98" height="86"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="110">                    <div align="center">
                      <p>D Steer
                        <select name="Conditions4" id="Conditions4">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions4" id="Conditions5">
<option>125</option>
<option>120</option>
<option>115</option>
<option>110</option>
<option selected>105</option>
<option>100</option>
<option>90</option>
<option>80</option>
<option>70</option>
<option>60</option>
<option>50</option>
<option>40</option>
<option>30</option>
<option>20</option>
<option>10</option>
                        </select>
                      </p>
                    </div>
                    <td width="93" height="149" rowspan="4"><img src="images/truckimages/sprintertop.gif" width="119" height="248">                                        
                    <td width="93" height="110"><div align="center">
                      <p>P Steer                      
                        <select name="Conditions5" id="Conditions6">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions6" id="Conditions7">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option selected>105</option>
                          <option>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="24">                                                            
                    <td height="24">                                                            
                  </tr>
                  <tr>
                    <td height="71"><div align="center">
                      <p>DFDrive
                        <select name="Conditions7" id="Conditions8">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions9" id="Conditions9">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                    <td height="71"><div align="center">
                      <p>PF Drive
                        <select name="Conditions8" id="Conditions10">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions10" id="Conditions11">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="86">
                    <td height="86"></tr>
                  <tr>
                  <td height="24" colspan="3"><div align="center"> 
                    <input type="checkbox" />
                    Check To Confirm Sprinter Tire Inspection
                  </div>                    
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->






<!-- Default box -->
          <div class="box">
<!--Remove the div Class "box" above and add below primary collapsed -->
<!--      <div class="box box-primary collapsed-box"> --> 
            <div class="box-header with-border">
              <h3 class="box-title">Submit Inspections</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="314" border="1">
                  <tr>
                    <td width="304" colspan="3"><div align="center"> Submit VIR &amp; Tire Report</div>
                      <div align="center"></div>
                      <div align="center">
                      <div align="center"></div>
                  </tr>
                  <tr>
                    <td colspan="2"><img src="images/finish.jpg" alt="Submit" width="308" height="136"></td>
                  <tr>
                    <td colspan="2"><table width="310" border="1">
                    </table>
                      <div align="center">Additional Notes: <?php echo "$drivername"; ?> </div>
                      <textarea name="Remarks" id="Remarks" cols="52" rows="2">Enter Additional Notes Here!</textarea></td>
                    <script type="text/javascript">
		var checkDisplay = function(check, form) { //check ID, form ID
			form = document.getElementById(form), check = document.getElementById(check);
			check.onclick = function(){
				form.style.display = (this.checked) ? "block" : "none";
				form.reset();
			};
			check.onclick();
		};
                </script>
                  </tr>
                  <tr>
                    <td ><div align="center">
                      <input type="submit" name="btn_sourceform2" id="btn_sourceform2" value="Submit Inspection" />
                    </div></td>
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->




        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
      </footer>
      
      <!-- Control Sidebar -->      
      <aside class="control-sidebar control-sidebar-dark">                
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
          <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
          
          <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <!-- Home tab content -->
          <div class="tab-pane" id="control-sidebar-home-tab">
            <h3 class="control-sidebar-heading">Recent Activity</h3>
            <ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                    <p>Will be 23 on April 24th</p>
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-user bg-yellow"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                    <p>New phone +1(800)555-1234</p>
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                    <p>nora@example.com</p>
                  </div>
                </a>
              </li>
              <li>
                <a href='javascript::;'>
                  <i class="menu-icon fa fa-file-code-o bg-green"></i>
                  <div class="menu-info">
                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                    <p>Execution time 5 seconds</p>
                  </div>
                </a>
              </li>
            </ul><!-- /.control-sidebar-menu -->

            <h3 class="control-sidebar-heading">Tasks Progress</h3> 
            <ul class='control-sidebar-menu'>
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Custom Template Design
                    <span class="label label-danger pull-right">70%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                  </div>                                    
                </a>
              </li> 
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Update Resume
                    <span class="label label-success pull-right">95%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                  </div>                                    
                </a>
              </li> 
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Laravel Integration
                    <span class="label label-waring pull-right">50%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                  </div>                                    
                </a>
              </li> 
              <li>
                <a href='javascript::;'>               
                  <h4 class="control-sidebar-subheading">
                    Back End Framework
                    <span class="label label-primary pull-right">68%</span>
                  </h4>
                  <div class="progress progress-xxs">
                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                  </div>                                    
                </a>
              </li>               
            </ul><!-- /.control-sidebar-menu -->         

          </div><!-- /.tab-pane -->
          <!-- Stats tab content -->
          <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
          <!-- Settings tab content -->
          <div class="tab-pane" id="control-sidebar-settings-tab">            
            <form method="post">
              <h3 class="control-sidebar-heading">General Settings</h3>
              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Report panel usage
                  <input type="checkbox" class="pull-right" checked />
                </label>
                <p>
                  Some information about this general settings option
                </p>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Allow mail redirect
                  <input type="checkbox" class="pull-right" checked />
                </label>
                <p>
                  Other sets of options are available
                </p>
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Expose author name in posts
                  <input type="checkbox" class="pull-right" checked />
                </label>
                <p>
                  Allow the user to show his name in blog posts
                </p>
              </div><!-- /.form-group -->

              <h3 class="control-sidebar-heading">Chat Settings</h3>

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Show me as online
                  <input type="checkbox" class="pull-right" checked />
                </label>                
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Turn off notifications
                  <input type="checkbox" class="pull-right" />
                </label>                
              </div><!-- /.form-group -->

              <div class="form-group">
                <label class="control-sidebar-subheading">
                  Delete chat history
                  <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                </label>                
              </div><!-- /.form-group -->
            </form>
          </div><!-- /.tab-pane -->
        </div>
      </aside><!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class='control-sidebar-bg'></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
    <script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- SlimScroll -->
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src='../../plugins/fastclick/fastclick.min.js'></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js" type="text/javascript"></script>
    
    <!-- Demo -->
    <script src="../../dist/js/demo.js" type="text/javascript"></script>
  </body>
</html>