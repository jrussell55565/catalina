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

if(isset($_GET['submit']))
{
  # Override the time predicate here.
    $fileName = time() . '.csv';
    $fileDir = '/tmp/';
    $file = fopen($fileDir . $fileName, "w") or die("Unable to open file!");
    $sql = "SELECT * FROM virs WHERE insp_date BETWEEN str_to_date('".$_GET['start']."','%m/%d/%Y') AND str_to_date('".$_GET['end']."','%m/%d/%Y') LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error());;
    /* get column metadata */
    $column_names = array();
    $header = '';
    for ($i = 0; $i <= mysql_num_fields($result); $i++)
    {  
      $meta = mysql_fetch_field($result, $i);
      array_push($column_names,$meta->name);
    }
    mysql_free_result($result);
    $header = implode(',',$column_names);
    $header = rtrim($header,',') . "\n";
    file_put_contents($fileDir . $fileName, $header, LOCK_EX);
    $sql = "SELECT * FROM virs WHERE insp_date BETWEEN str_to_date('".$_GET['start']."','%m/%d/%Y') AND str_to_date('".$_GET['end']."','%m/%d/%Y')";
    $result = mysql_query($sql) or die(mysql_error());;
    while ($row = mysql_fetch_array($result, MYSQL_BOTH))
    {
      for ($i = 0; $i <= mysql_num_fields($result); $i++)
      {
        $a = str_replace(array("\r","\n"), ' ', $row[$i]);
        $a = str_replace(",","|",$a);
        $fullRow .= $a . ",";
      }
      $fullRow = rtrim_limit($fullRow,',',1) . "\n";
      file_put_contents($fileDir . $fileName, $fullRow, FILE_APPEND | LOCK_EX);
      unset($fullRow);
    }
      fclose($fileDir . $fileName);
      mysql_free_result($result);

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

# Get the list of mechanics from the users table.
$sql = 'SELECT CONCAT(fname," ",lname) as mechanic ,employee_id from users where title = "Mechanic"';
$results = mysql_query($sql);
$mechanics = array();
$mechanic_id = array();
while ($row = mysql_fetch_array($results,MYSQL_BOTH))
{
array_push($mechanics,$row[0]);
array_push($mechanic_id,$row[1]);
}
mysql_free_result($results);
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
<!-- Date Picker -->
<link href="<?php echo HTTP;?>/dist/css/bootstrap-datepicker3.css" rel="stylesheet">
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
    <li>
      <a href="#">
      <i class="fa fa-home"></i> Home
      </a>
    </li>
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
# If non-admin logs in then only show their info
if ($_SESSION['login'] == 2)
{
$restricted_predicate = "AND (truck_number = ".$_GET['truck_number']." OR trailer_number = ".$_GET['trailer_number'].")";
}else{
$restricted_predicate = '';
}

foreach (range(0, 10) as $number)
{

$sql = "SELECT * FROM virs WHERE 1=1 $restricted_predicate
AND insp_date = date(now()) - INTERVAL $number DAY
ORDER BY insp_date DESC, vir_itemnum ASC";
$sql = mysql_query($sql);
?>
            <tr>
              <td style="width: 20px;"><i class="glyphicon glyphicon-wrench"></i></td>
              <td><?php echo "$number day ago";?>
                <a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse"
href="#<?php echo $number;?>_details" aria-expanded="false" aria-controls="<?php echo $number;?>_details" style="padding-left: 15px;">
                </a></td>
            </tr>
            <tr class="collapse" id="<?php echo $number;?>_details">
              <td colspan="9"><div class="well table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>PO</th>
                        <th>Unit</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Tires</th>
                        <th>Insp Type</th>
                        <th>Reported</th>
                        <?php
if ($_SESSION['login'] == 1)
{
?>
                        <th>Actions</th>
                        <?php
}
?>
                        <th>Updated Status</th>
                      </tr>
                      <?php
while($row = mysql_fetch_array($sql, MYSQL_BOTH))
{
if ($row['truck_number'] != '')
{
# $tot is Truck or Trailer
$tot = 'truck';
}elseif($row['trailer_number'] != '') {
$tot = 'trailer';
}
?>
                      <tr>
                        <td><!-- Button trigger modal -->
                          
                          <button type="button" class="btn btn-primary btn-small" data-toggle="modal" data-target="#modal_<?php echo $tot;?>_<?php echo $row['vir_itemnum'];?>">
                          <?php echo $row['vir_itemnum'];?>
                          </button></td>
                        <td><?php echo $row[$tot.'_number'];?></td>
                        <td><img src="../images/<?php if($tot == 'truck')
{
if ($row['trucktype'] == "combo")
{
echo 'semismall.gif';
}
if($row['trucktype'] == "boxtruck")
{
echo 'boxtrucksmall.gif';
}
if($row['trucktype'] == 'sprinter')
{
echo 'sprintersmall.gif';
}
}else{
echo 'trailersmall.gif';
}
?>"></img></td>
                        <td><?php
if (preg_match('/^Green/',$row[$tot.'_vir_condition']))
{
echo '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
}elseif (preg_match('/^Yellow/',$row[$tot.'_vir_condition'])){
echo '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
}elseif (preg_match('/^Red/',$row[$tot.'_vir_condition'])){
echo '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
}
?></td>
                        <td><?php
if (preg_match('/^Green/',$row[$tot.'_tires_overall']))
{
echo '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
}elseif (preg_match('/^Yellow/',$row[$tot.'_tires_overall'])){
echo '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
}elseif (preg_match('/^Red/',$row[$tot.'_tires_overall'])){
echo '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
}
?></td>
                        <td><?php echo str_replace('vir_','',$row['insp_type']);?></td>
                        <td><?php echo $row['driver_name'];?></td>
                        <?php
if ($_SESSION['login'] == 1)
{
?>
                        <td><form role="form" method="post" action="vir_previous.php">
                            <input type="hidden" name="hdn_vir_itemnum" id="hdn_vir_itemnum" value="<?php echo $row['vir_itemnum'];?>"/>
                            <div class="form-group">
                              <select name="vir_status" id="vir_status_<?php echo $row['vir_itemnum'];?>" class="form-control" onchange="submitSelect(this,<?php echo $row['vir_itemnum'];?>,'modal_close_<?php echo $tot;?>_<?php echo $row['vir_itemnum'];?>');">
                                <?php
if ($row['updated_status'] == '')
{
?>
                                <option>Choose Option</option>
                                <?php
}
?>
                                <option <?php if($row['updated_status'] == 'Open') { echo 'selected=selected';}?>>Open</option>
                                <option <?php if($row['updated_status'] == 'Work Order Created') { echo 'selected=selected';}?>>Work Order Created</option>
                                <option <?php
                                if(($row['updated_status'] == 'Close') || ((preg_match('/^Green/',$row[$tot.'_vir_condition']) && (preg_match('/^Green/',$row[$tot.'_tires_overall'])))))
                                { 
                                   echo 'selected=selected';
                                }
                                ?>
                                >Close</option>
                              </select>
                            </div>
                          </form></td>
                        <?php
}
?>
                        <td><div id="updated_status_<?php echo $row['vir_itemnum'];?>">
                            <?php
if (preg_match('/^Open/',$row['updated_status']))
{
echo '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
}elseif (preg_match('/^Work/',$row['updated_status'])){
echo '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
}elseif ((preg_match('/^Close/',$row['updated_status'])) || ((preg_match('/^Green/',$row[$tot.'_vir_condition']) && (preg_match('/^Green/',$row[$tot.'_tires_overall']))))){
echo '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
}
?>
                          </div></td>
                      </tr>
                     
                      <!-- CLOSE ISSUE MODAL -->
                    <div class="modal fade" id="modal_close_<?php echo $tot;?>_<?php echo $row['vir_itemnum'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Close <?php echo $row['vir_itemnum'];?></h4>
                          </div>
                          <div class="modal-body">
                            <div class="row" style="padding-bottom: 5px;">
                              <div class="col-md-4">
                               <b>Repair Notes</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                               <input type="text" id="repair_notes_<?php echo $row['vir_itemnum'];?>" class="form-control" placeholder="Enter repair notes..."/>
                              </div>
                            </div>
                            <div class="row" style="padding-bottom: 5px;">
                              <div class="col-md-4">
                               <b>Cost of Repair</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                               <div class="input-group">
                                 <span class="input-group-addon">$</span>
                                 <input type="text" id="cost_<?php echo $row['vir_itemnum'];?>"class="form-control" aria-label="Amount (to the nearest dollar)">
                                 <span class="input-group-addon">.00</span>
                               </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                               <b>Repaired By:</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                              <select name="mechanic" id="mechanic_<?php echo $row['vir_itemnum'];?>" class="form-control">
                              <?php
                              for($i = 0; $i < count($mechanics); $i++)
                              {
                              ?>
                               <option id="<?php echo $mechanic_id[$i];?>"><?php echo $mechanics[$i];?></option>
                              <?php
                              }
                              ?>
                              </select>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeVir(<?php echo $row['vir_itemnum'];?>,$('#repair_notes_<?php echo $row['vir_itemnum'];?>').val(),$('#cost_<?php echo $row['vir_itemnum'];?>').val(),$('#mechanic_<?php echo $row['vir_itemnum'];?> option:selected').text())">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /MODAL -->
 
                      <!-- GENERAL MODAL -->
                    <div class="modal fade" id="modal_<?php echo $tot;?>_<?php echo $row['vir_itemnum'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Details for <?php echo $row['vir_itemnum'];?></h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-4">
                                General Notes
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                                <?php if ($row['vir_finish_notes'] == '')
{
if ($tot == 'trailer')
{
echo '<span class="label label-info">See Truck Notes</span>';
}else{
echo '<span class="label label-danger">No Notes</span>';
}
}else{
echo $row['vir_finish_notes'];
}
?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Items
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                                <?php if ($row[$tot.'_vir_items'] == '')
{
echo '<span class="label label-danger">No Items</span>';
}else{
echo $row[$tot.'_vir_items'];
}
?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Truck / Trailer Notes
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                                <?php if ($row[$tot.'_vir_notes'] == '')
{
echo '<span class="label label-danger">No Notes</span>';
}else{
echo $row[$tot.'_vir_notes'];
}
?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Tire Notes
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                                <?php if ($row[$tot.'_tires_notes'] == '')
{
echo '<span class="label label-danger">No Notes</span>';
}else{
echo $row[$tot.'_tires_notes'];
}
?>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /MODAL -->
                    
                      </form>
                    
                    <?php
}
?>
                  </table>
                </div></td>
            </tr>
            <?php
}
mysql_free_result($sql);
?>
            <tr>
              <td colspan="2">
          <?php
                 if ($_SESSION['login'] == 1)
                 {
                 ?>
          <form enctype="multipart/form-data" role="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div>
            <div class="input-daterange input-group" id="datepicker" style="width: 25%; vertical-align:top;">
              <input type="text" class="input-sm form-control datepicker" name="start" id="startDate" data-date-format="mm/dd/yyyy"/ required>
              <span class="input-group-addon">to</span>
              <input type="text" class="input-sm form-control datepicker" name="end" id="endDate" data-date-format="mm/dd/yyyy"/ required>
            </div>
            <input type="submit" name="submit" class="btn btn-primary" value="Export" style="margin-top: 5px;">
          </div>
          </form>
          <?php
                 }
                 ?>
              </td>
            </tr>

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
      <div class="row">
      </div>
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
  <div class='control-sidebar-bg'>
  </div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo HTTP;?>/plugins/jQuery/jQuery-2.1.4.min.js">
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo HTTP;?>/bootstrap/js/bootstrap.min.js" type="text/javascript">
</script>
<!-- Slimscroll -->
<script src="<?php echo HTTP;?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript">
</script>
<!-- FastClick -->
<script src='<?php echo HTTP;?>/plugins/fastclick/fastclick.min.js'>
</script>
<!-- AdminLTE App -->
<script src="<?php echo HTTP;?>/dist/js/app.min.js" type="text/javascript">
</script>

<!-- Demo -->
<script src="<?php echo HTTP;?>/dist/js/demo.js" type="text/javascript">
</script>
<script>
function closeVir(item,notes,cost,repaired_by)
{
  item_status = 'Close';
  $.post( "viractions.php", { vir_item: item, vir_status: item_status, repair_notes: notes, repair_cost: cost, repair_by: repaired_by })
  .success(function(data) {
  x = '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
  $("#updated_status_"+item).html(x);
  })
  .error(function(data) { console.log(data); });
}
function submitSelect(sel,item,modal)
{
if (sel.value == "Close")
{
  $('#'+modal).modal('show');
}else{
  $.post( "viractions.php", { vir_item: item, vir_status: sel.value })
  .success(function() {
  if (sel.value == "Open")
  {
  x = '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
  }
  if (sel.value == "Work Order Created")
  {
  x = '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
  }
  if (sel.value == "Close")
  {
  x = '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
  }
  $("#updated_status_"+item).html(x);
  })
  .error(function(data) { console.log(data); });
  }
}
</script>
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
<script type="text/javascript">
  var today = new Date();
  var minus30days = new Date();
  minus30days.setDate(minus30days.getDate() - 30 );
  <?php
    if (empty($_GET['start']))
    {
      $startDate = "minus30days.getMonth() + 1 + '/' + minus30days.getDate() + '/' + minus30days.getFullYear()";
    }else{
      $startDate = "'$_GET[start]'";
    }
    if (empty($_GET['end']))
    {
      $endDate = "today.getMonth() + 1 + '/' + today.getDate() + '/' + today.getFullYear()";
    }else{
      $endDate = "'$_GET[end]'";
    }
  ?>
    $('#startDate').val(<?php echo $startDate;?>)
    $('#endDate').val(<?php echo $endDate;?>)
</script>
</body>
</html>
