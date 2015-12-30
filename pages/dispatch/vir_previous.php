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

if ($_GET['action'] == 'loginas')
{
  $_SESSION['userid'] = $_GET['username'];
  $_SESSION['username'] = $_GET['username'];
  $_SESSION['drivername'] = $_GET['drivername'];
  $_SESSION['login'] = 2;
  header("Location: /pages/main/index.php");
}

# Let's do some form processing
if(isset($_POST['submit']))
{
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Previous VIR</title>
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
<link rel="stylesheet" href="<?php echo HTTP;?>/dist/css/animate.css">
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
  <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
  <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Admin Previous VIR</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Previous VIR</li>
      </ol>
    </section>
    
    <!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->
    
    <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuadminanimation.php');?>
    
    <!-- End Animated Top Menu -->
    
    <div class="row">
    <div class="col-md-12">
    <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">VIR</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
    <table class="table table-striped">
        <tbody>
      
      <?php
foreach (range(0, 12) as $number)
{

$sql = "SELECT * FROM virs WHERE 1=1 AND (truck_number = ".$_GET['truck_number']." OR trailer_number = ".$_GET['trailer_number'].") AND
        insp_date = date(now()) - INTERVAL $number DAY";
$sql = mysql_query($sql);
$row = mysql_fetch_array($sql, MYSQL_BOTH);
?>
      <tr>
        <td style="width: 20px;"><i class="glyphicon glyphicon-user"></i></td>
        <td><?php echo "$number day ago";?> <a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse"
  href="#<?php echo $number;?>_details" aria-expanded="false" aria-controls="<?php echo $number;?>_details" style="padding-left: 15px;"> </a></td>
      </tr>
      <tr class="collapse" id="<?php echo $number;?>_details">
        <td colspan="9"><div class="well">
            <table>
foo <?php echo $number;?>
            </table>
          </div></td>
      </tr>
<?php
mysql_free_result($sql);
}
?>
      
        </tbody>
      
    </table>
  </div>
  <!-- ./box-body --> 
</div>
<!-- /.col -->
</div>
<!-- /.row --> 
<!-- Main row -->
<div class="row"> 
  <!-- Left col -->
  <div class="col-md-8">
    <div class="row"> </div>
    <!-- /.col --> 
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
</body>
</html>
