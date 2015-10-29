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
<link rel="stylesheet" href="http://dispatch.catalinacartage.com:8080/dist/css/animate.css">
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Admin Dashboard</h1>
            
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
          <!-- Shipment Boards -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-cog fa-spin"></i></span>
                </a>               
                <div class="info-box-content">
                <span class="info-box-text"><a href="/pages/dispatch/orders.php">View  / EDIT / Export All DISPATCHES</a></span><span class="info-box-number">  Todays  PU:  <?php echo "$pu_today_count";?><br>
                    Todays DEL:   <?php echo "$del_today_count";?></span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->



            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/dispatch/vir.php" class="button animated rubberBand">
<style>
 a.button {
	 -webkit-animation-duration: 5s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
                <span class="info-box-icon bg-red"><i class="fa fa-wrench faa-wrench animated"></i></span>
</a>               
                <div class="info-box-content">
                  <span class="info-box-text"><a href="/pages/dispatch/vir.php">View / EDIT / EXPORT DRIVERS VIRs</a></span>
                  <span class="info-box-number">                  Total VIR's Reported: ?/PHP</span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/fuel.php" class="button2 animated zoomIn">
<style>
 a.button2 {
	 -webkit-animation-duration: 6s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>              
                <span class="info-box-icon bg-green"><i class="fa fa-tachometer"></i></span>
                </a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/vir.php">VIEW / EDIT / EXPORT FUEL LOGS</a></span>
                  <span class="info-box-number">                  Total Fuel Reported: ?/PHP  </span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->



            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-yellow"><i class="fa fa-newspaper-o"></i></span>
</a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/vir.php">VIEW / EDIT / EXPORT IFTA Reports</a></span>
                  <span class="info-box-number">                  Total IFTA Reports: ?/PHP </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            



            
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-newspaper-o"></i></span>
</a>

                <div class="info-box-content">
                  <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/vir.php">VIEW / EDIT / EXPORT DOT Saftey Report</a></span>
                  <span class="info-box-number">                  Import Reports Here</span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            
            

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-newspaper-o"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/adminusers.php">VIEW / Edit / EXPORT  VIR</a></span>
                  <span class="info-box-number">                  PU &amp; DEL </span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->


            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-newspaper-o"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/adminusers.php">VIEW / EDIT / eXPORT HWB Accessorials</a></span><span class="info-box-number">PU &amp; DEL</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            
            


            
            
            

<div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="/pages/dispatch/adminusers.php" class="button4 animated bounce">
<style>
 a.button4 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="/pages/dispatch/adminusers.php">VIEW / EDIT / EXPORT New Users</a></span>
                  <span class="info-box-number">View all User Profiles                  </span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.info-box -->
          </div><!-- /.row -->
          
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Users</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
<table class="table table-striped">
 <thead>
  <tr>
    <th> </th>
    <th>Name</th>
    <th>Login As</th>
    <th>Title</th>
    <th>Office</th>
    <th>Phone Number</th>
    <th>Login</th>
    <th>Password</th>
    <th>Status</th>
  </tr>
 </thead>
 <tbody>
<?php
                      $sql = "SELECT * FROM users";
                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
?>
<tr>
<td><a href="#"><i class="glyphicon glyphicon-user"></i></a></td>
<td>
<div style="float:left;width:80%;"><?php echo $row['drivername'];?></div>
<div style="float:right;width:20%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse" 
  href="#<?php echo $row['username'];?>_details"aria-expanded="false" aria-controls="<?php echo $row['username'];?>_details">
  </a></div>
</td>
<td><a href="#"><i class="glyphicon glyphicon-lock"></i></a></td>
<td><?php echo $row['title'];?></td>
<td><?php echo $row['office'];?></td>
<td><?php echo $row['driverid'];?></td>
<td><?php echo $row['username'];?></td>
<td><?php echo $row['password'];?></td>
<td><?php echo $row['status'];?></td>
</tr>
<tr class="collapse" id="<?php echo $row['username'];?>_details">
<td colspan="9">
  <div class="well">
   <table>
    <tr>
     <td rowspan="3">
       <img src="/dist/img/user1-128x128.jpg"/>
     </td>
     <td style="padding: 5px">
      <label for="fname">First Name</label>
      <input type="text" class="form-control" id="fname" placeholder="" value="<?php echo $row['fname'];?>">
     </td>
     <td style="padding: 5px">
      <label for="mname">Middle Name</label>
      <input type="text" class="form-control" id="mname" placeholder="" value="<?php echo $row['mname'];?>">
     </td>
     <td style="padding: 5px">
      <label for="lname">Last Name</label>
      <input type="text" class="form-control" id="lname" placeholder="" value="<?php echo $row['lname'];?>">
     </td>
     <td style="padding: 5px">
      <label for="status">User Status</label>
      <input type="text" class="form-control" id="status" placeholder="" value="<?php echo $row['status'];?>">
     </td>
     <td style="padding: 5px">
      <label for="role">Access Role</label>
      <input type="text" class="form-control" id="role" placeholder="" value="<?php echo $row['role'];?>">
     </td>
     <td style="padding: 5px">
      <label for="office">Office Location</label>
      <input type="text" class="form-control" id="office" placeholder="" value="<?php echo $row['office'];?>">
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="addr1">Home Address 1</label>
      <input type="text" class="form-control" id="addr1" placeholder="" value="<?php echo $row['addr1'];?>">
     </td>
     <td style="padding: 5px">
      <label for="addr2">Home Address 2</label>
      <input type="text" class="form-control" id="addr2" placeholder="" value="<?php echo $row['addr2'];?>">
     </td>
     <td style="padding: 5px">
      <label for="city">Home City</label>
      <input type="text" class="form-control" id="city" placeholder="" value="<?php echo $row['city'];?>">
     </td>
     <td style="padding: 5px">
      <label for="state">Home State</label>
      <input type="text" class="form-control" id="state" placeholder="" value="<?php echo $row['state'];?>">
     </td>
     <td style="padding: 5px">
      <label for="zip">Home Zip</label>
      <input type="text" class="form-control" id="zip" placeholder="" value="<?php echo $row['zipcode'];?>">
     </td>
     <td style="padding: 5px">
      <label for="jobTitle">Job Title</label>
      <input type="text" class="form-control" id="jobTitle" placeholder="" value="<?php echo $row['title'];?>">
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" placeholder="" value="<?php echo $row['email'];?>">
     </td>
     <td style="padding: 5px">
      <label for="emailUpdates">Email Updates</label>
      <input type="checkbox" class="form-control" id="emailUpdates" placeholder="" value="<?php echo $row['email'];?>">
     </td>
     <td style="padding: 5px">
      <label for="emailUpdates">Text Updates</label>
      <input type="checkbox" class="form-control" id="emailUpdates" placeholder="" value="<?php echo $row['email'];?>">
     </td>
     <td style="padding: 5px">
      <label for="quietTimeVal1">Quiet Time (begining)</label>
      <input type="text" class="form-control" id="quietTimeVal1" placeholder="" value="<?php echo $row['quiet_time'];?>">
     </td>
     <td style="padding: 5px">
      <label for="quietTimeVal2">Quiet Time (ending)</label>
      <input type="text" class="form-control" id="quietTimeVal2" placeholder="" value="<?php echo $row['quiet_time'];?>">
     </td>
     <td style="padding: 5px">
      <label for="ssn">SSN</label>
      <input type="text" class="form-control" id="ssn" placeholder="" value="<?php echo $row['ssn'];?>">
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="medicalCard">MC Expiry Date</label>
      <input type="text" class="form-control" id="medicalCard" placeholder="" value="<?php echo $row['med_expire_dt'];?>">
     </td>
     <td style="padding: 5px">
      <label for="mobilePhone">Mobile Phone</label>
      <input type="text" class="form-control" id="mobilePhone" placeholder="" value="<?php echo $row['driverid'];?>">
     </td>
     <td style="padding: 5px">
      <label for="contract">Contract</label>
      <input type="text" class="form-control" id="contract" placeholder="" value="<?php echo $row['contract'];?>">
     </td>
     <td style="padding: 5px">
      <label for="startDate">Start Date</label>
      <input type="text" class="form-control" id="startDate" placeholder="" value="<?php echo $row['start_date'];?>">
     </td>
     <td style="padding: 5px">
      <label for="departureDate">Departure Date</label>
      <input type="text" class="form-control" id="departureDate" placeholder="" value="<?php echo $row['start_date'];?>">
     </td>
     <td style="padding: 5px">
      <label for="username">Username</label>
      <input type="text" class="form-control" id="username" placeholder="" value="<?php echo $row['username'];?>">
     </td>
     <td style="padding: 5px">
      <label for="password">Password</label>
      <input type="text" class="form-control" id="password" placeholder="" value="<?php echo $row['password'];?>">
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
       <input type="submit" class="btn btn-primary" value="Update">
     </td>
    </tr>
   </table>
  </div>
</td>
</tr>
<?php
                      }
                      mysql_free_result($sql);
?>
</tbody>
</table>
                </div><!-- ./box-body -->
               </div><!-- /.col -->
          </div><!-- /.row -->

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <div class="row">
              </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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
