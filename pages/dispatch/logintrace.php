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

# Get the driver names and employee_id
$driver_array = array();
$statement = 'SELECT fname, lname, employee_id from users WHERE title = "Driver" ORDER BY fname';
$results = mysql_query($statement);
while($row = mysql_fetch_array($results, MYSQL_BOTH))
{
    $driver_array[$row['employee_id']] = $row['fname']." ".$row['lname'];
}
mysql_free_result($results);

# Process GET requests.  Made by the driver export calls
if (isset($_GET['exportDisplay']))
{
  $startDate = $_GET['start'];
  $endDate = $_GET['end'];
  $exportType = $_GET['inlineRadioOptions'];
  $driver_predicate = '';
  $truck_predicate = '';

  if ($_GET['trip_search_driver'] != 'null')
  {
    $driver_predicate = 'AND b.drivername = (SELECT username from users where employee_id="'.$_GET['trip_search_driver'].'")';
  }
  if (! empty($_GET['truck_no']))
  {
    $truck_predicate = 'AND b.truck_number = '.$_GET['truck_no'];
  }
  if ($_GET['remove_dup'] == 'true')
  {
    $select_statement = "SELECT distinct
    a.drivername,
    b.driver_driverid,
    b.truck_number,
    b.trailer_number,
    b.rental,
    DATE(b.login_time) AS login_time,
    b.truck_odometer";
  }else{
    $select_statement = "SELECT
    a.drivername,
    b.driver_driverid,
    b.truck_number,
    b.trailer_number,
    b.rental,
    b.login_time,
    b.truck_odometer";
  }

  $loginSql = "$select_statement
  from users a, 
   login_capture b 
  where 
   a.driverid = b.driver_driverid
   and login_time between str_to_date('$startDate 00:00:00','%m/%d/%Y %H:%i:%s')
     and str_to_date('$endDate 23:59:59','%m/%d/%Y %H:%i:%s')
  $driver_predicate
  $truck_predicate
  AND truck_number != 0
  GROUP BY drivername , driver_driverid , driver_driverid , truck_number , trailer_number , rental , truck_odometer , login_time
  order by b.login_time";

  if ($_GET['inlineRadioOptions'] == "exportCsv")
  {
    $fileName = time() . '.csv';
    $fileDir = '/tmp/';
    $file = fopen($fileDir . $fileName, "w") or die("Unable to open file!");
    $sql = mysql_query($loginSql);
    while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
    {
      $fullRow = $row['drivername'] . "," .
                 $row['driver_driverid'] . "," .
                 $row['truck_number'] . "," .
                 $row['trailer_number'] . "," .
                 $row['rental'] . "," .
                 $row['login_time'] . "," .
                 $row['truck_odometer'] . "\n";
      file_put_contents($fileDir . $fileName, $fullRow, FILE_APPEND | LOCK_EX);
     }
      fclose($fileDir . $fileName);

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($fileDir . $fileName));
      header ("Content-Disposition:attachment; filename=\"$fileName\"");
      readfile($fileDir . $fileName);
      unlink($fileDir . $fileName);
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">

    <title>Trace Login</title>
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

    <!-- Date Picker -->
    <link href="<?php echo HTTP;?>/dist/css/bootstrap-datepicker3.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo HTTP . "/dist/css/animate.css";?>">
    </head>
    <body class="skin-blue sidebar-mini">
    <div class="wrapper">
      <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
      <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>
      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Catalina Dashboard <small>1.0.</small></h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>
        
        <!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->
        
        <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>
        
        <!-- End Animated Top Menu --><!-- /.row --> 
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Truck Trailer Driver  Export<span class="progress-text"></span> Data</h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <div class="btn-group">
                    <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <!-- /.box-header -->
              <form class="form" method="get" action="">
              <div class="box-body">
               <div class="input-daterange input-group" id="datepicker" style="width: 25%;">
                <input type="text" class="input-sm form-control datepicker" name="start" data-date-format="mm/dd/yyyy"/ required>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control datepicker" name="end" data-date-format="mm/dd/yyyy"/ required>
               </div>
               <div class="input-group" id="driver" style="width: 25%;">
                 <select class="input-sm form-control" name="trip_search_driver" id="trip_search_driver" value="" style="margin-top: 5px;">
                  <option value="null">Choose Driver...</option>
                  <?php
                  foreach ($driver_array as $employee_id => $driver) { ?>
                    <option value=<?php echo $employee_id;?>><?php echo $driver;?></option>
                  <?php } ?>
                </select>
               </div>
               <div class="input-group">
                  <input type="text" class="input-sm form-control" name="truck_no" placeholder="Truck Number" style="margin-top: 5px;">
               </div>
                <div class="input-group" style="margin-top: 5px">
                 <p>
                 <input name="remove_dup" type="checkbox" id="remove_dup" value="true" checked> Remove Day Duplicates
                 </p> 
                 <label class="radio-inline">
                  <input name="inlineRadioOptions" type="radio" id="inlineRadio1" value="exportDisplay" checked> Display
                 </label>
                 <label class="radio-inline">
                  <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="exportCsv"> CSV
                 </label>
                </div>
                <div class="input-group" style="margin-top: 5px">
                 <input type="submit" class="btn btn-primary" name="exportDisplay" id="exportDisplay" value="Display / Export"/>
                </div>
                <div class="input-group" style="margin-top: 5px" style="display: none;">
                 <?php if ($_GET['inlineRadioOptions'] == "exportDisplay")
                 {
                   ?>
                   <table class="table" style="display: block;">
                   <th>drivername</th>
                   <th>Phone</th>
                   <th>truck number</th>
                   <th>trailer number</th>
                   <th>rental</th>
                   <th>login time</th>
                   <th>odometer</th>
   
                   <?php
                   $sql = mysql_query($loginSql);
                   while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                   {
                   ?>
                     <tr>
                      <td><?php echo $row['drivername'];?></td>
                      <td><?php echo $row['driver_driverid'];?></td>
                      <td><?php echo $row['truck_number'];?></td>
                      <td><?php echo $row['trailer_number'];?></td>
                      <td><?php echo $row['rental'];?></td>
                      <td><?php echo $row['login_time'];?></td>
                      <td><?php echo $row['truck_odometer'];?></td>
                     </tr>
                   <?php
                   }
                   ?>
                   </table> 
                  <?php
                  }
                  ?>
                </div>
              </div>
              </form>
              <!-- ./box-body -->
              <div class="box-footer"> </div>
              <!-- /.box-footer --> 
            </div>
            <!-- /.box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 

      </div>
      <!-- /.row -->
      </section>
      <!-- /.content --> 
    </div>

    <!-- /.content-wrapper -->

    <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/footer.php');?>

    <!-- Control Sidebar -->
    <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/r_sidebar.php');?>
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

    <!-- Date Picker -->
    <script src="<?php echo HTTP;?>/dist/js/bootstrap-datepicker.js"></script>
    <script>
    $('.datepicker').datepicker({
    startDate: "2015-01-01",
    todayBtn: "linked",
    autoclose: true,
    datesDisabled: '0',
    todayHighlight: true,
    });
    </script>

</body>
</html>
