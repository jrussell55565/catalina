<?php
session_start();

if ($_SESSION['login'] != 1)
{
        header('Location: /pages/dispatch/adminonly.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_LOCAL_INFILE, true);
$mysqli->real_connect($db_hostname, $db_username, $db_password, $db_name);

if ($_POST['btn_csa'])
{
  try
  {
    switch ($_FILES['file_csa']['error'])
    {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new Exception('No file found.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception('Exceeded filesize limit.');
        default:
            throw new Exception('Unknown error uploading '.$_FILES['file_csa']['name']);
    }
    $statement = "load data local infile \"".$_FILES['file_csa']["tmp_name"]."\"
               REPLACE INTO TABLE csadata FIELDS TERMINATED BY ','
               ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES
               (employee_id,@v_date,state,number,level,placard_inspection,hm_inspection,basic,violation_group,code,description,out_of_service,convicted_diff_charge,violation_weight,time_weight,total_points,points_cash_value,basic_violation_inspection,last_name,first_name,co_driver_last_name,co_driver_first_name)
               SET date = str_to_date(@v_date, '%m/%d/%Y')";

    if ($mysqli->query($statement) === false)
    {
        throw new Exception("Unable to load file into csadata: ". $mysqli->error);
    }

    } catch (Exception $e) {
        // An exception has been thrown
        // We must rollback the transaction
        $url_error = urlencode($e->getMessage());
        $mysqli->rollback();
        header("location: " . HTTP . "/pages/dispatch/imports.php?error=$url_error");
        $mysqli->autocommit(TRUE);
        $mysqli->close();
        exit;
    }
    header("location: " . HTTP . "/pages/dispatch/imports.php?status=success");
    exit;
}

if ($_POST['btn_users'])
{
  try
  {
    switch ($_FILES['file_csa']['error'])
    {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new Exception('No file found.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception('Exceeded filesize limit.');
        default:
            throw new Exception('Unknown error uploading '.$_FILES['file_csa']['name']);
    }
    $statement = "load data local infile \"".$_FILES['file_csa']["tmp_name"]."\"
               REPLACE INTO TABLE days_worked FIELDS TERMINATED BY ','
               ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES
               (`Employee Number`,`First`,`Last`,@v_date_worked,@v_time_begin,@v_time_end,`Hours`,`Amount`,`Code`,`worked`)
               SET `Date Worked` = str_to_date(@v_date_worked,'%m/%d/%y'), `Time In` = str_to_date(@v_time_begin, '%l:%i %p'), `Time Out` = str_to_date(@v_time_end,'%l:%i %p')";

    if ($mysqli->query($statement) === false)
    {
        throw new Exception("Unable to load file into csadata: ". $mysqli->error);
    }

    } catch (Exception $e) {
        // An exception has been thrown
        // We must rollback the transaction
        $url_error = urlencode($e->getMessage());
        $mysqli->rollback();
        header("location: " . HTTP . "/pages/dispatch/imports.php?error=$url_error");
        $mysqli->autocommit(TRUE);
        $mysqli->close();
        exit;
    }
    header("location: " . HTTP . "/pages/dispatch/imports.php?status=success");
    exit;
}
if ($_POST['btn_trips'])
{
  try
  {
    switch ($_FILES['file_trips']['error'])
    {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new Exception('No file found.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception('Exceeded filesize limit.');
        default:
            throw new Exception('Unknown error uploading '.$_FILES['file_trips']['name']);
    }

    $statement = "load data local infile \"".$_FILES['file_csa']["tmp_name"]."\"
               REPLACE INTO TABLE import_gps_trips FIELDS TERMINATED BY ','
               ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES
               (employee_id,`Category`,`Device`,`Driver`,`Start Address`,@v_began,`Stop Address`,@v_ended,`Miles`,`Max Speed`,`Avg Speed`,`Travel Time`,`Idle Time`)
               SET `began` = str_to_date(@v_began, '%m/%d/%Y %H:%i'), `ended` = str_to_date(@v_ended,'%m/%d/%Y %H:%i')";

    if ($mysqli->query($statement) === false)
    {
        throw new Exception("Unable to load file into import_gps_trips: ". $mysqli->error);
    }

    } catch (Exception $e) {
        // An exception has been thrown
        // We must rollback the transaction
        $url_error = urlencode($e->getMessage());
        $mysqli->rollback();
        header("location: " . HTTP . "/pages/dispatch/imports.php?error=$url_error");
        $mysqli->autocommit(TRUE);
        $mysqli->close();
        exit;
    }
    header("location: " . HTTP . "/pages/dispatch/imports.php?status=success");
    exit;
}

?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Imports</title>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/favicon/favicon.php');?>
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
<link rel="stylesheet" href="/dist/css/animate.css">
<style>
.chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
</style>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?> 

    <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Imports / <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
            <?php if ($_SESSION['login'] == 1) { echo "(Admin)"; }?>
            </a></h1>

          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Imports</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->  


          <!-- Top Box Centered Full sized window -->
          <?php
          if (isset($_GET['error'])) {
            echo "<br>";
            echo '<div style="width: 50%; text-align: center; margin:auto" class="alert alert-danger" role="alert">Error adding record: ',urldecode($_GET['error']),'</div>';
          }
          if (isset($_GET['status'])) {
            echo "<br>";
            echo '<div style="width: 50%; text-align: center; margin:auto" class="alert alert-success" role="alert">File added successfully.</div>';
          }?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">CSA Imports</h3>
                  
                  <!-- 
                  Remove Search Tool
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  -->
                  
                  <!-- Insert Plus Minus tool -->
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                
                <!-- /.box-header -->
               <form enctype="multipart/form-data" role="form" method="post" action="<?php echo HTTP . $_SERVER['PHP_SELF']; ?>">
                <div class="box-body table-responsive no-padding">
                  <table width="98%" class="table table-hover">
                    <tr>
                      <th><a href="/pages/examples/CSA_Template.csv">Download Import Template CSV</a> Need Help Matt This file does not work....</th>
                    </tr>
                    <tr>
                      <th><a href="/pages/examples/CSA_Template.csv">Download Modification Template XLXS</a> Megan And Jaime Working on this file for download</th>
                    </tr>
                    <tr>
                      <th><a href="https://csa.fmcsa.dot.gov/default.aspx">https://csa.fmcsa.dot.gov/default.aspx</a> This is where you log in to download the current CSA data</th>
                    </tr>
                    <tr>
                      <th>DOT: 1959805 (Enter Password Ask Jaime If you do not know this!)</th>
                    </tr>
                    <tr>
                      <th>Then Download All, Excel; Copy Lines 2 below to Mod Spread Sheet</th>
                    </tr>
                    <tr>
                      <th>First Export the Current Data from CSA. Then Add the new File to the current Data</th>
                    </tr>
                  </table>
                  <p>
                    <input id="file_csa" name="file_csa" type="file" multiple=false class="file-loading">
                  </p>
                  <input type="submit" class="btn btn-primary" value="Import CSA" name="btn_csa" id="btn_csa">
                  <input type="submit" class="btn btn-primary" value="Import Timesheets" name="btn_users" id="btn_users">
                  <input type="submit" class="btn btn-primary" value="Import Driver" name="btn_trips" id="btn_trips">
              </div><!-- /.box -->
            </div>
          </div>         
         </form>
         <!-- Top Box Full sized window Close Out-->        




          <!-- Top Box Centered Full sized window --><!-- Top Box Full sized window Close Out-->











        
        
         <!-- Left Side Box 1 Start-->         
          <div class="row">
            <!-- Div Class-md-6 will give the seperation of columns...-->  <div class="col-md-12"><!-- /.box -->

<!-- Left Side Box 1 End--> 






<!-- Center Box Try 1 -->         
          <div class="row">
            <!-- Div Class-md-6 will give the seperation of columns...-->  <div class="col-md-6">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> Left Box 1</h3>
                       <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

<!-- Left Side Box 1 End--> 
























<!-- Center Box Start  --> 
         <div class="row">
            <!-- Div Class-md-6 will give the seperation of columns...-->  <div class="col-md-6">
               <div class="box">
                <div class="box-header">
    
              <!-- Adding the Spinner Box Icon here
              <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-bank fa-zoomIn"></i></span>
                </a>                    
              </div>
              </div>
              -->
              
                 <h3 class="box-title">Center Box 1</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            
<div class="box">
                <div class="box-header">
    
              <!-- Adding the Spinner Box Icon here
              <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-bank fa-zoomIn"></i></span>
                </a>                    
              </div>
              </div>
              -->
              
                 <h3 class="box-title">Left Box 3</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
            </div><!-- /.box-body -->
              </div><!-- /.box -->
              
              
              
              
              
              
<div class="box">
                <div class="box-header">
    
              <!-- Adding the Spinner Box Icon here
              <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-bank fa-zoomIn"></i></span>
                </a>                    
              </div>
              </div>
              -->
              
                 <h3 class="box-title">Left Box 4</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
            </div><!-- /.box-body -->
              </div><!-- /.box -->              
              
              
              
              
              
              
              
              
              
              
              
            </div><!-- /.col -->
            
            
         
            
            



            
            <!-- End Right Side Box Menus -->
            <div class="col-md-6">           
            </div><!-- /.col -->
            <div class="col-md-6">












            <!-- Start Left Side Box Menus -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"> Right Box 1</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- /.Removing the Box Tools side element -->
                 <div class="box-tools">
                 <!--
                    <ul class="pagination pagination-sm no-margin pull-right">
                      <li><a href="#">&laquo;</a></li>
                      <li><a href="#">1</a></li>
                      <li><a href="#">2</a></li>
                      <li><a href="#">3</a></li>
                      <li><a href="#">&raquo;</a></li>
                    </ul>
                  -->  
                  </div>
                 
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->











                <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Right Box 2</h3>
                  <div class="box-tools pull-right">
                  <!--          <div class="box"> -->
                  <!--Remove the div Class "box" above and add ?? to below primary collapsed -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div> 
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
 <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Right Box 3</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
            </div><!-- /.box-body -->
              </div>             
              
              
              
              
              
              
              
              
              
              
              
              










<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Right Box 4</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table width="39%" class="table table-hover">
                    <tr>
                      <th>Enter Table Here</th>
                    </tr>
                  </table>
            </div><!-- /.box-body -->
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
          
          
          <!-- Bottom Box Full sized window -->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Bottom Box Centered</h3>
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table width="100%" class="table table-hover">
                    <tr>
                      <th width="-1%">#</th>
                      <th width="36%">User</th>
                      <th width="29%">Days Worked</th>
                      <th width="20%">Status</th>
                      <th width="16%">Reason</th>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td>John Doe</td>
                      <td>110</td>
                      <td><span class="label label-success">Approved</span></td>
                      <td>Bacon</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>Alexander Pierce</td>
                      <td>95</td>
                      <td><span class="label label-warning">Pending</span></td>
                      <td>Bacon </td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td>Bob Doe</td>
                      <td>93</td>
                      <td><span class="label label-primary">Approved</span></td>
                      <td>Bacon </td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td>Mike Doe</td>
                      <td>92</td>
                      <td><span class="label label-danger">Denied</span></td>
                      <td>Bacon </td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->







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
</body>
</html>
