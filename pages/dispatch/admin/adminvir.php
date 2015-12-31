<?php
session_start();

if ($_SESSION['login'] != 1)
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
<link href="../../dist/css/AdminLTE.css" rel="stylesheet" type="text/css">
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
            Catalina Dashboard
            <small>1.0.</small></h1>
            
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>
<!-- End Animated Top Menu -->


          
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">VIR Accessorials</h3>
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
                </div><!-- /.box-header --><!-- ./box-body -->
                <div class="box-footer">
                  <div id="mainContent">
                    <table class="table">
                      <tr>
                        <td>VIR Item</td>
                        <td>Type</td>
                        <td>Options</td>
                      </tr>
                      <form id="accessorials_add" name="accessorials_add" method="post" action="accessorialactions.php">
                        <tr>
                          <td><input name="revenue_charge" type="text" id="revenue_charge" class="form-control"/></td>
                          <td>
                            <select class="form-control" name="acc_type" id="acc_type">
                              <option selected="selected">Truck</option>
                              <option>Trailer</option>
                            </select></td>
                          <td>
                           <input name="btn_submit" value="Add" type="submit" id="btn_submit" class="btn btn-primary"/>
                           <input type="hidden" name="hdn_vir"/>
                          </td>
                      </form>
                      <?php
                $result = mysql_query("SELECT * FROM accessorials WHERE src_page='VIR' ORDER BY acc_type,revenue_charge");
                $counter = 0;
                while ($row = mysql_fetch_array($result, MYSQL_BOTH))
                {
	?>
                      <form id="accessorials_del" name="accessorials_del" method="post" action="accessorialactions.php">
                        <tr>
                          <td><input type="hidden" name="acc_itemnum" value="<?php echo "$row[acc_itemnum]";?>">
                            <input name="revenue_charge" type="text" id="revenue_charge" value="<?php echo "$row[revenue_charge]";?>" class="form-control"></td>
                          <td>
                            <select class="form-control" name="acc_type" id="acc_type">
                              <option <?php if ($row['acc_type'] == 'Truck') { echo "selected"; }?>>Truck</option>
                              <option <?php if ($row['acc_type'] == 'Trailer') { echo "selected"; }?>>Trailer</option>
                            </select></td>
                          <td><input name="btn_submit" value="Delete" type="submit" id="btn_submit" class="btn btn-danger"/>
                            <input name="btn_submit" value="Update" type="submit" id="btn_submit" class="btn btn-primary"/>
                            <input type="hidden" name="hdn_vir"/>
                          </td>
                      </form>
                      <?php
		}
	?>
                    </table>
                    <h2>&nbsp;
                      <?php if (isset($_SESSION['dberror'])) { $error = $_SESSION['dberror']; echo "$error\n"; } ?>
                    </h2>
                    <!-- end #mainContent -->
                  </div>
                 </div><!-- /.row -->
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row --><!-- PAGE CONTENT HERE -->

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
