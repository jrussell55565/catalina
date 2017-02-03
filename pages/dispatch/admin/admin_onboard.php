<?php
session_start();

if ($_SESSION['login'] != 1)
{
        header('Location: /pages/dispatch/adminonly.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
$position_array = array('Office', 'Driver');

if (isset($_POST['btn_submit'])){
  // Let's update the DB with the values entered.
    $id = $_POST['id'];
    $order_rank = $_POST['order_rank'];
    $phase = $_POST['phase'];
    $category = $_POST['category'];
    $position = implode('|', $_POST['position']);

  switch ($_POST['btn_submit'])
  {
    case "Delete":
        $sql = "DELETE FROM onboard_management WHERE id=$id";
        break;
    case "Update":
        $sql = "UPDATE onboard_management set order_rank=$order_rank, phase=$phase, category=\"$category\", position=\"$position\"  
          WHERE id=$id";
        break;
    case "Add":
        $sql = "INSERT INTO onboard_management (order_rank,phase,category,position) VALUES ($order_rank,$phase,\"$category\",\"$position\")";
        break;
  }
  try{
    # Start TX
    $mysqli->autocommit(FALSE);
    $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    if ($mysqli->query($sql) === false)
      {
          throw new Exception($mysqli->error);
      }
      $mysqli->commit();
      header("location: /pages/dispatch/admin/admin_onboard.php?return=true");
      exit;
  }catch(Exception $e){
      error_log($e->getMessage());
      $url_error = urlencode($e->getMessage());
      $mysqli->rollback();
      header("location: /pages/dispatch/admin/admin_onboard.php?return=false&error=$url_error");
      $mysqli->autocommit(TRUE);
      $mysqli->close();
      exit;
  }
}

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
          <h1>ADMIN ONBOARD FORMS</h1>
            
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
                  <h3 class="box-title">Add Onboard Items Below</h3>
                  <?php if ($_GET['return'] == 'false') {
                    echo '<div id="error"  class="alert alert-danger" role="alert" style="margin-top: 5px; padding: 1px; text-align: center; display: block;">';
                    echo $_GET['error'];
                    echo '</div>';
                  }
                  ?>
                  <div id="error"  class="alert alert-danger" role="alert" style="margin-top: 5px; padding: 1px; text-align: center; display: none;"></div>
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
                        <td>Phase</td>
                        <td>Order</td>
                        <td>Category</td>
                        <td>Position</td>
                        <td>Options</td>
                      </tr>
                      <form id="onboard_management" name="onboard_management" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <tr>
                          <td><input name="order_rank" type="text" class="form-control digit_only" id="order_rank" required="true" /></td>
                          <td><input name="phase" type="text" class="form-control digit_only" id="phase" required="true"/></td>
                          <td><input name="category" type="text" class="form-control" id="category" required="true"/></td>
                          <td><select class="form-control" name="position[]" id="position" required="true" multiple="TRUE">
                            <option>Office</option>
                            <option>Driver</option>
                          </select></td>
                          <td>
                           <input name="btn_submit" value="Add" type="submit" id="btn_submit" class="btn btn-primary"/>
                          </td>
                      </form>
                      <?php                      
                      try {
                        $statement = "select id,order_rank,phase,category,position from onboard_management order by order_rank";
                        if ($result = $mysqli->query($statement)) {
                          $sql_object = array();
                          $counter = 0;
                          while($obj = $result->fetch_object()){
                              $id = $obj->id;
                              $sql_object[$counter]['id'] = $obj->id;
                              $sql_object[$counter]['order_rank'] = $obj->order_rank;
                              $sql_object[$counter]['phase'] = $obj->phase;
                              $sql_object[$counter]['category'] = $obj->category;
                              $sql_object[$counter]['position'] = $obj->position;
                              $counter++;
                          }
                        }else{
                            throw new Exception($mysqli->error);
                        }
                      }catch (Exception $e) {
                        error_log($e->getMessage());
                        $url_error = urlencode($detailed_message);
                        $mysqli->rollback();
                        header("location: /pages/dispatch/admin/admin_onboard.php?return=false&error=$url_error");
                        $mysqli->autocommit(TRUE);
                        $mysqli->close();
                        exit;
                      }
                      
                for($i=0;$i<sizeof($sql_object);$i++)
                {
	?>
                      <form id="onboard_management" name="onboard_management" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                        <tr>
                          <td><input type="hidden" id="id" name="id" value="<?php echo $sql_object[$i]['id'];?>">
                            <input name="order_rank" type="text" class="form-control digit_only" id="order_rank" value="<?php echo $sql_object[$i]['order_rank'];?>" required="true">
                          </td>
                          <td><input name="phase" type="text" class="form-control digit_only" id="phase" value="<?php echo $sql_object[$i]['phase'];?>" required="true" ></td>
                          <td><input name="category" type="text" class="form-control" id="category" value="<?php echo $sql_object[$i]['category'];?>" required="true"></td>
                          <td><select class="form-control" name="position[]" id="position" multiple="TRUE" required="true">
                          <?php
                            foreach($position_array as $p) {
                              echo "<option value='$p' ";
                              // position may be a concatenated string.  Let's explode it
                              foreach (explode('|', $sql_object[$i]['position']) as $boom) {
                                if ($boom == $p) { echo "selected"; }
                              }
                              echo ">$p</option>";
                            }
                          ?>
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

<script>
// Don't allow non-digits in mobile phone
$(document).ready(function () {
  //called when key is pressed in textbox
  $(".digit_only").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
   });
});
</script>
</body>
</html>
