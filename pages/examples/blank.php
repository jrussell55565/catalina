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
        <h1> #INSERT PAGE NAME HERE#</h1>
        <ol class="breadcrumb">
          <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">#INSERT PAGE NAME HERE#</li>
        </ol>
      </section>
      
<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->      
      
      <!-- Main content -->
      <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title"></h4>
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
	
            Box Content Inside Box
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Vechicle Inspection Quick Report</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form class="form" name="virForm" method="post" action="viractions.php">
            <table width="313" border="1">
              <tr>
                <td colspan="4">Start Time:
                  <input name="insp_start_time" type="text" id="insp_start_time" value="<?php echo $localtime; ?>" size="8"/>
                  Date
                  <input name="insp_date" type="text" id="insp_date" value="<?php echo $localdate; ?>" size="8" readonly/>
                  <span class="active"><?php echo "$truck_number"; ?></span>
              </tr>
              <tr>
                <td width="95">Truck
                <td width="72"><input name="truck_number" type="text" id="truck_number" value="<?php echo $truckid; ?>" size="8" readonly />
                <td colspan="2"><a href="vir_previous_truck.php"> Previous VIR</a>
              </tr>
              <tr>
                <td>Trailer
                <td><input name="trailer_number" type="text" id="trailer_number" value="<?php echo $trailerid; ?>" size="8" readonly />
                <td colspan="2"><a href="vir_previous_trailer.php">Previous VIR</a>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Pre Trip:
                    <input type="radio" name="preorposttrip" id="preorposttrip" value="vir_pretrip" checked>
                    <label for="vir_pretrip"></label>
                    Post Trip:
                    <input type="radio" name="preorposttrip" id="preorposttrip" value="vir_posttrip">
                    <label for="vir_posttrip"></label>
                  </div>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Truck Type</div>
                  <div align="center"></div>
              </tr>
              <tr>
                <td><div align="center"><span class="box-title"><img src="../images/semismall.gif" alt="tire"></span></div>
                <td colspan="2"><div align="center"><span class="box-title"><img src="../images/boxtrucksmall.gif" alt="tire"></span></div>
                <td width="100"><div align="center"><span class="box-title"><img src="../images/sprintersmall.gif" alt="tire"></span></div>
              </tr>
              <tr>
                <td><div align="center">
                    <input type="radio" name="trucktype" id="trucktype" value="combo">
                    <label for="type_semi"></label>
                  </div>
                <td colspan="2"><div align="center">
                    <input type="radio" name="trucktype" id="trucktype" value="boxtruck">
                    <label for="type_boxtruck"></label>
                  </div>
                  <div align="center"></div>
                <td><div align="center">
                    <input type="radio" name="trucktype" id="trucktype" value="sprinter">
                    <label for="type_sprinter"></label>
                  </div>
              </tr>
            </table>
            <table width="313" border="1">
              <tr>
                <td height="10" colspan="4"><div align="center">
                    <label for="VIR Conditions &amp; Tires"></label>
                    VIR Conditions &amp; Tires</div>
              </tr>
              <tr>
                <td width="83">Truck
                <td width="64" bgcolor="#33FF00"><div align="center">Green
                    <input type="radio" name="vir_truck[]" id="vir_truck[]" value="green" checked>
                    <label for="vir_truck_green"></label>
                  </div>
                <td width="66" bgcolor="#FFFF00"><div align="center">Yellow
                    <input type="radio" name="vir_truck[]" id="vir_truck[]" value="yellow">
                    <label for="vir_truck_yellow"></label>
                  </div>
                <td width="62" bgcolor="#FF0000"><div align="center">Red
                    <input type="radio" name="vir_truck[]" id="vir_truck[]" value="red">
                    <label for="vir_truck_red"></label>
                  </div>
              </tr>
              <tr>
                <td>Truck <img src="../images/smalltires.gif" width="25" height="25" alt="tire">
                <td bgcolor="#33FF00"><div align="center">Green
                    <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire[]" value="green" checked>
                    <label for="truck_tires_green"></label>
                  </div>
                <td bgcolor="#FFFF00"><div align="center">Yellow
                    <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire[]" value="yellow">
                    <label for="truck_tires_yellow"></label>
                  </div>
                <td bgcolor="#FF0000"><div align="center">Red
                    <label for="cb_trailer_tires_red"></label>
                    <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire[]" value="red">
                    <label for="truck_tires_red"></label>
                  </div>
              </tr>
              <tr>
                <td><a href="vir.php"><img src="../images/trailer.gif" alt="Trailer" width="77" height="38"></a>
                <td bgcolor="#33FF00"><div align="center">Green
                    <label for="cb_trailer_green3"></label>
                    <input type="radio" name="vir_trailer[]" id="vir_trailer[]" value="green" checked>
                    <label for="vir_trailer_green"></label>
                  </div>
                <td bgcolor="#FFFF00"><div align="center">Yellow
                    <label for="cb_trailer_yellow3"></label>
                    <input type="radio" name="vir_trailer[]" id="vir_trailer[]" value="yellow">
                    <label for="vir_trailer_yellow"></label>
                  </div>
                <td bgcolor="#FF0000"><div align="center">Red
                    <input type="radio" name="vir_trailer[]" id="vir_trailer[]" value="red">
                    <label for="vir_trailer_red"></label>
                    <label for="cb_trailer_red3"></label>
                  </div>
              </tr>
              <tr>
                <td>Trailer <img src="../images/smalltires.gif" width="25" height="25" alt="tire">
                <td bgcolor="#33FF00"><div align="center">Green
                    <label for="cb_trailer_tires_green3"></label>
                    <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire[]" value="green" checked>
                    <label for="trailer_tires_green"></label>
                  </div>
                <td bgcolor="#FFFF00"><div align="center">Yellow
                    <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire[]" value="yellow">
                    <label for="trailer_vir_tires_yellow"></label>
                  </div>
                <td bgcolor="#FF0000"><div align="center">Red
                    <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire[]" value="red">
                    <label for="trailer_vir_tires_red"></label>
                  </div>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Enter Additional Notes below</div></td>
              </tr>
              <tr>
                <td colspan="4"><div align="center">
                    <textarea name="vir_notes_quick_report" id="vir_notes_quick_report"  cols="43" rows="3" placeholder="Please type notes for any items needing attention!"></textarea>
                  </div></td>
              </tr>
              <tr>
                <td colspan="4"><A HREF="#submitvir"></A>
                  <div align="center"> <A HREF="#submitvir">VIR OK, Tires OK / Go To Submit</A></div></td>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
         <div class="vir">
         Not All Green? Add Items Below!
         </div>
         <div class="virconfirmation">
         Choose a truck type to continue.
         </div>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->


			<!-- END PAGE CONTENT HERE -->


            </div>
            <!-- /.box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row --> 
    </div>







    
  <section> 
  </section>
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
