<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<!-- Bootstrap 3.3.4 -->
<link href="<?php echo HTTP;?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Icons -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="<?php echo HTTP;?>/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
<link href="<?php echo HTTP;?>/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1> Tire Inflation Info</h1>
        <ol class="breadcrumb">
          <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">Tire Inflation Info</li>
        </ol>
      </section>
      
      <!-- Main content -->
      <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h1>What Exactly Constitutes an Underinflated Truck Tire?</h1>
              July 2013, TruckingInfo.com - WebXclusive<br>
              by Jim Park, Equipment Editor - <a href="http://www.truckinginfo.com/search/default.aspx?f_Author=Jim+Park" target="_blank">Also by this author</a><br>
              <br>
              <div>
                <div>
                  <div><br>
                    <div>
                    We've all heard the claims that running tires underinflated hurts fuel economy (1 to 1.5% for every 10% under, they say), increases tire wear and wrecks casings.<br>
                      So what is underinflated?<br>
                      By definition, it's any pressure less than the minimum recommended for the tire load. For example, a steer tire with a load of 6,000 pounds would be underinflated at 105 psi (see below). A drive tire loaded to 4,520 pounds would be underinflated at 75 psi. It all depends on the load on the tire and the minimum inflation pressure for the load. <br>
                       <br>
                      <a href="http://oascentral.truckinginfo.com/RealMedia/ads/click_lx.ads/truckinginfo/essentials/L12/1374197176/Middle1/Bobit/House_HDT_EnewsSignup_June-Dec14/hdt_enews_160x600_20140612.jpg/52474e654e6c577645574541416f6e75;zip=US:85013?x" target="_blank"><span data-src="http://imagec18.247realmedia.com/RealMedia/ads/Creatives/Bobit/House_HDT_EnewsSignup_June-Dec14/hdt_enews_160x600_20140612.jpg"> </span></a>Why should we worry about defining underinflation? Because of the Federal Motor Carrier Safety Administration's CSA (Compliance, Safety, Accountability) enforcement program.<br>
                      The Commercial Vehicle Safety Alliance is currently exploring how to define underinflation for enforcement purposes. CVSA has already settled on a definition of a "flat" tire: 50% of the max cold inflation pressure stamped on the sidewall of the tire, e.g., 60 psi in a tire stamped for its max load at 120 psi inflation pressure. That's clean and simple; now CVSA wants a similarly clean and simple definition of under-inflated.<br>
                      That won't be as easy.<br>
                      Peggy Fisher, president of Tire Stamp, led a discussion at this year's annual meeting of ATA's Technology and Maintenance Council on the subject and failed to resolve the question. Difficulty arises because of an almost outdated DOT requirement that evaluations of hot or in-service tires be temperature-compensated.<br>
                      "DOT inspectors won't usually check tire pressure unless they suspect a problem, like the tire looks soft," Fisher said. "The problem is they have nothing to go by except what's stamped on the tire. To determine the proper inflation pressure of a tire, they need to know the temperature of the tire and the load on the tire. All that comes into play in determining the proper inflation pressure of a tire." <br>
                      Back in the days of tube tires, it was established that inspectors would subtract 15 psi from the gauged pressure of the tire to compensate for temperature. For example, a hot tire gauged at 85 psi would be considered cold-inflated to 70 psi. If that seems low, consider that according to the Goodyear's and Bridgestone's Load &amp; Inflation tables, a 70-pound tire can still carry a load of 3,875 pounds. Michelin allows up to 4,500 pounds at 70 psi. Even at 3,875 pounds per tire, that would still allow 31,000 pounds over a tandem axle group. Is that tire actually underinflated with a light load?<br>
                      There was also a CVSA official in the room at TMC. Kerri Wirachowsky of CVSA's vehicle committee, said the definition has to be simple or officers won't get it right.<br>
                      "I don't think a lot of trucks get citations for underinflation, but they are getting written up on the inspection reports and that's just as impactful as far as CSA is concerned," she said. "As an officer checking tire pressure at roadside, I might not be at a scale; I don't know the load or the temperature of the tire. There's no way I can tell the variation between hot and cold. If you're going to make a reg change, get rid of the minus-15 and go to the lowest common denominator." <br>
                      TMC and CVSA are still working on this, and a solution doesn't seem close at this point. But maybe this information can help you in a DataQ challenge. Is that 70-psi tire really underinflated?<br>
                      <br>
                      <h3>Load &amp; Inflation Table examples</h3>
                      <br>
                      Michelin 275/80R22.5<br>
                      Steer 5,980 @ 105 psi; 6,175 @ 110 psi<br>
                      Drive/trailer 4,770 lb single @ 75 psi<br>
                      <br>
                      Bridgestone 295/75R22.5<br>
                      Steer 5980 @ 105 psi; 6175 @ 110 psi<br>
                      Drive/trailer 4540 lb single @ 80 psi<br>
                      <br>
                      Goodyear 295/75R22.5<br>
                      Steer 5,980 @ 105 psi; 6175@110<br>
                      Drive 4,690 @ 75 psi<br>
                      <br>
                      <br>
                      <em>Read more about tire inflation in the July issue of HDT.</em></div>
                  </div>
              </div>
              </div>
              <h4 class="box-title">&nbsp;</h4>
              <div class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">
                  <li>
                   <a href="orders.php?gather=pu">Page1</a></li>
                  <li>
                   <a href="orders.php?gather=del">Page2</a></li>
                </ul>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">


            <!-- PAGE CONTENT HERE -->

            <!-- END PAGE CONTENT HERE -->


            </div>
            <!-- /.box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row --> 
  </section>
    </div>

  <!-- /.content --> 
</div>
<!-- /.content-wrapper -->
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/footer.php');?>

<!-- Control Sidebar -->
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/r_sidebar.php');?>
<!-- /.control-sidebar --> 
<!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
</div>
<!-- ./wrapper --> 

<!-- jQuery 2.1.4 --> 
<script src="<?php echo HTTP;?>/plugins/jQuery/jQuery-2.1.4.min.js"></script> 
<!-- Bootstrap 3.3.2 JS --> 
<script src="<?php echo HTTP;?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<!-- Slimscroll --> 
<script src="<?php echo HTTP;?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<!-- FastClick --> 
<script src='<?php echo HTTP;?>/plugins/fastclick/fastclick.min.js'></script> 
<!-- AdminLTE App --> 
<script src="<?php echo HTTP;?>/dist/js/app.min.js" type="text/javascript"></script> 

<!-- Demo -->
<script src="<?php echo HTTP;?>/dist/js/demo.js" type="text/javascript"></script>
</body>
</html>
