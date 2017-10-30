<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Trace PU</title>
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

<!-- Bootstrap time Picker -->
<link href="<?php echo HTTP;?>/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />

<!-- Custom Catalina CSS -->
<link href="<?php echo HTTP;?>/dist/css/catalina.css" rel="stylesheet" type="text/css" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<script language="JavaScript">
  function toggle(source) {
  checkboxes = document.getElementsByName('chk_hawb[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;

  }
</script>
<style>
.btn-primary.active {
	background-color: limegreen;
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
      <h1>Dropped</h1>
      hwb: <?php echo $_GET[hwb]; ?>
      <ol class="breadcrumb">
        <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
        <!--        <li><a href="#">Tables</a></li> -->
        <li class="active">Dropped</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box">
          <!-- /.box-header -->
          <div class="box-body">
          <form method="post" action="export.php">
            <table class="table table-bordered">
              <?php
                     $recordid = $_GET['recordid'];
                     $sql = "select pieces, pallets
                             from dispatch WHERE recordID=$recordid";
                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
                                $pieces = $row[pieces];
                                $pallets = $row[pallets];
                      }
                      mysql_free_result($sql);
                      if ($pallets == 0 || $pallets == '') # pallets is zero or empty
                      {
                          $pallets = $pieces; # Set pallets to what pieces is
                      }
                    ?>
              <tr>
                <td><label>Pieces</label></td>
                <td><input type="number" id="txt_pieces" name="txt_pieces"
                    value="<?php echo $pallets; #Yup, use pallets;?>" class="form-control"></td>
              </tr>
              <tr>
                <td><label>Pallets</label></td>
                <td><input type="number" id="txt_pallets" name="txt_pallets"
                    value="<?php echo $pallets; ?>" class="form-control"></td>
              </tr>
              <tr>
                <td><label>DropTime</label></td>
                <td><div class="bootstrap-timepicker">
                    <div class="bootstrap-timepicker-widget dropdown-menu">
                      <table>
                        <tbody>
                          <tr>
                            <td><a href="#" data-action="incrementHour"><i class="glyphicon glyphicon-chevron-up"></i></a></td>
                            <td class="separator">&nbsp;</td>
                            <td><a href="#" data-action="incrementMinute"><i class="glyphicon glyphicon-chevron-up"></i></a></td>
                            <td class="separator">&nbsp;</td>
                            <td class="meridian-column"><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-up"></i></a></td>
                          </tr>
                          <tr>
                            <td><span class="bootstrap-timepicker-hour">07</span></td>
                            <td class="separator">:</td>
                            <td><span class="bootstrap-timepicker-minute">30</span></td>
                            <td class="separator">&nbsp;</td>
                            <td><span class="bootstrap-timepicker-meridian">AM</span></td>
                          </tr>
                          <tr>
                            <td><a href="#" data-action="decrementHour"><i class="glyphicon glyphicon-chevron-down"></i></a></td>
                            <td class="separator"></td>
                            <td><a href="#" data-action="decrementMinute"><i class="glyphicon glyphicon-chevron-down"></i></a></td>
                            <td class="separator">&nbsp;</td>
                            <td><a href="#" data-action="toggleMeridian"><i class="glyphicon glyphicon-chevron-down"></i></a></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <input type="text" class="form-control timepicker" name="bx_localtime">
                        <div class="input-group-addon"> <i class="fa fa-clock-o"></i> </div>
                      </div>
                      <!-- /.input group --> 
                    </div>
                    <!-- /.form group --> 
                  </div></td>
              <tr>
                <td><label>TraceNotes</label></td>
                <td><textarea id="remarks" name="remarks" class="form-control" required placeholder="Please Enter Air Way Bill # here...."></textarea></td>
              </tr>
            </table>
            </div>
            <!-- /.box-body -->
            
            <div class="box collapsed-box">
              <div class="box-header with-border">
                <h3 class="box-title">Accessorials</h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                </div>
              </div>
              <div class="box-body">
                <table class="table table-bordered">
                  <?php accessorials("PU",basename(__FILE__),$username); ?>
                </table>
              </div>
              <!-- /.box-body --> 
            </div>
            <div class="box-footer"> 
             <input type="submit" class="btn btn-primary" name="btn_sourceform" value="Dropped"> </input>
             <input type="hidden" name="recordid" value="<?php echo $_GET[recordid];?>"> </input>
            </div>
            </div>
          </form>
          <!-- /.box --> 
          <!-- /.box-header --> 
        </div>
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
<!-- Select2 --> 
<script src="<?php echo HTTP;?>/plugins/select2/select2.full.min.js" type="text/javascript"></script> 
<!-- InputMask --> 
<script src="<?php echo HTTP;?>/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script> 
<script src="<?php echo HTTP;?>/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script> 
<script src="<?php echo HTTP;?>/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script> 
<!-- date-range-picker --> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script> 
<script src="<?php echo HTTP;?>/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script> 
<!-- bootstrap color picker --> 
<script src="<?php echo HTTP;?>/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script> 

<!-- bootstrap time picker --> 
<script src="<?php echo HTTP;?>/plugins/timepicker/bootstrap-timepicker.js" type="text/javascript"></script> 
<!-- Slimscroll --> 
<script src="<?php echo HTTP;?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script> 

<!-- iCheck 1.0.1 --> 
<script src="<?php echo HTTP;?>/plugins/iCheck/icheck.min.js" type="text/javascript"></script> 

<!-- FastClick --> 
<script src='<?php echo HTTP;?>/plugins/fastclick/fastclick.min.js'></script> 
<!-- AdminLTE App --> 
<script src="<?php echo HTTP;?>/dist/js/app.min.js" type="text/javascript"></script> 

<!-- AdminLTE for demo purposes --> 
<script src="<?php echo HTTP;?>/dist/js/demo.js" type="text/javascript"></script> 

<!-- Page script --> 
<script type="text/javascript">
      $(function () {
        //Timepicker
        $(".timepicker").timepicker({
          showInputs: false,
          showMeridian: false,
          minuteStep: 1
        });
      });
    </script> 

<!-- Catalina --> 
<script src="<?php echo HTTP;?>/dist/js/catalina_timepicker.js" type="text/javascript"></script>
</body>
</html>
