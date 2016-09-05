<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];

// Get the compliance scores.  If I'm an admin AND driver GET var is set then we'll override it
if ($_SESSION['login'] == 1) {
  # Get the driver names and employee_id
  $driver_array = get_drivers($mysqli);
  if (isset($_GET['driver'])) {
    $emp_id = $_GET['driver'];
  }else{
    $emp_id = 'all';
  }
}else{
    // Create our driver_array if we're not an admin.
    $driver_array = [];
    $driver_array[0]['employee_id'] = $_SESSION['employee_id'];
    $driver_array[0]['name'] = $_SESSION['fname'] . " " . $_SESSION['lname'];
    $emp_id = $_SESSION['employee_id'];
}

// Set a default productivity_time if not specified
if (empty($_GET['productivity_time'])) {
  $productivity_time = "24";
}else{
  $productivity_time = $_GET['productivity_time'];
}

$csa_compliance_sql = generate_compliance_sql($emp_id,$productivity_time);
$csa_compliance_aggregate = get_sql_results($csa_compliance_sql,$mysqli);

# Get the overall threshold and append it to $csa_compliance_aggregate
for($i=0;$i<count($csa_compliance_aggregate);$i++) {
  $sql = "select * from csa_threshold WHERE basic = '" .$csa_compliance_aggregate[$i]['basic'] . "'";
  $threshold_aggregate = get_sql_results($sql,$mysqli);
    if ($threshold_aggregate === null) { 
      $csa_compliance_aggregate[$i]['threshold'] = "0";
    }else{
      $csa_compliance_aggregate[$i]['threshold'] = $threshold_aggregate[0]['threshold'];
    }
}

# Get Violation Cats.
$violations = array();
$statement = "select distinct basic from csadata where basic != ''";
$v = get_sql_results($statement,$mysqli);
for($i=0;$i<count($v);$i++) {
  array_push($violations,$v[$i]['basic']);
}

// Let's get the CSA data now for the supplied user,time, and if specified, basic
if (isset($_GET['reqtype'])) {
    $predicates = generate_compliance_predicate($_GET['driver'], $_GET['productivity_time']);
    $sql = generate_user_csa_sql($predicates[0],$predicates[1],$_GET['basic']);
    $user_csa_data = get_sql_results($sql,$mysqli);
    print json_encode($user_csa_data);
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
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

<!-- Date Picker -->
<link href="<?php echo HTTP;?>/dist/css/bootstrap-datepicker3.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="<?php echo HTTP;?>/dist/css/animate.css">

<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script>
<?php
// Get my total points
for ($i=0;$i<count($csa_compliance_aggregate);$i++) {
  if ($csa_compliance_aggregate[$i]['basic'] == 'Total Points') {
    $my_total_points = $csa_compliance_aggregate[$i]['total_points'];
  }
  if ($csa_compliance_aggregate[$i]['basic'] == 'Total Points') {
    $my_total_cash_points = $csa_compliance_aggregate[$i]['points_cash_value'];
  }
}
?>

function handleClick(event)
{
  //alert(event.item.category + ": " + event.item.values.value);
  $.getJSON( "<?php echo $_SERVER['PHP_SELF']; ?>", { productivity_time: <?php echo $productivity_time;?>, 
                                                   basic: event.item.category
                                                   <?php 
                                                    if ($_SESSION['login'] == 1) { 
                                                      if (isset($_GET['driver'])) { 
                                                        $driver = $_GET['driver']; 
                                                      }else{ 
                                                        $driver = 'all';
                                                      }  
                                                    }else{
                                                        $driver = $_SESSION['employee_id'];
                                                    }
                                                   ?>
                                                   ,driver: <?php echo "'$driver'\n"; ?>
                                                   ,reqtype: 'ajax'
                                                 })
  .success(function(data) {
    populate_csa(data);
  })
  .error(function(data) { console.log(data); });
}

var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "light",
  "listeners": [{
      "event":"clickGraphItem",
      "method":handleClick
  }],
  "dataProvider": [
<?php
// Now loop through and create our graph
for ($i=0;$i<count($csa_compliance_aggregate);$i++) {
  if (preg_match("/^Total/",$csa_compliance_aggregate[$i]['basic'])) {
    // Skip the basic that are our totals
    continue;
  }
  echo "{\n";
  echo "  \"category\": \"" . $csa_compliance_aggregate[$i]['basic'] . "\",\n";
  echo "  \"my_val\": " . round($csa_compliance_aggregate[$i]['total_points'] / $my_total_points,2) * 100 . ",\n";
  echo "  \"threshold\":" . $csa_compliance_aggregate[$i]['threshold'] * 100 . "\n";
  echo "}";
  if ($i != count($csa_compliance_aggregate) - 1) {
    // We're not the last element so let's append a comma.
    echo ",";
  }else{
    echo "\n";
  }
}
?>

],
  "graphs": [{
    "fillAlphas": 0.9,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "my_val",
    "clustered": false
  }, {
    "lineThickness": 3,
    "lineColor": "#cc0000",
    "noStepRisers": true,
    "type": "column",
    "valueField": "threshold",
    "openField": "threshold",
    "columnWidth": 0.7,
    "clustered": false
  }],
  "columnWidth": 0.5,
  "categoryField": "category",
  "categoryAxis": {
    "axisAlpha": 0,
    "gridAlpha": 0,
    "title": "Category"
  },
  "valueAxes": [{
    "axisAlpha": 0,
    "title": "Points"
  }]
});
</script>

<style>
#chartdiv {
    width: 800px;
    height: 500px;
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
            CSA Compliance</h1>
            
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">CSA Compliance</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->

<div class="container">
  <div class="row text-center">
    <div class="col-sm-<?php if ($_SESSION['login'] == 1) { echo 7; }else{ echo 3; }?> pull-left well">
      <form class="form-inline" action="#" method="get">
    <div class="form-group col-sm-3">
      <select name="productivity_time" id="productivity_time" class="input-sm form-control" style="width:12em;">
        <option value="24">24 Months</option>
        <option value="24+1">25th Month</option>
        <option value="all">All</option>
      </select>
    </div>
<?php if ($_SESSION['login'] == 1) { ?>
    <div class="form-group col-sm-3">
      <select name="driver" id="driver" class="input-sm form-control" style="width:12em;">
         <option value="all" <?php if ($_GET['driver'] == 'all') { echo " selected "; }?>>-All-</option>
           <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
             <option value=<?php echo $driver_array[$i]['employee_id'];
              if ($driver_array[$i]['employee_id'] == $_GET['driver']) { echo " selected "; } ?>
             ><?php echo $driver_array[$i]['name'];?></option>
           <?php } ?>
      </select>
     </div>
<?php } ?>
        <button class="btn btn-primary col-sm-3 pull-<?php if ($_SESSION['login'] == 1) { echo "left"; }else{ echo "right"; }?>" type="submit" name="return_csa" id="return_csa">Search</button>
      </form>
    </div>
  </div>
</div>

<div class="row">          
 <div class="col-md-12">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Compliance Chart</h3><br>
                  </h5>total points:<?php echo $my_total_points;?> | total cash points:<?php echo $my_total_cash_points;?> </h5>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools --> 
                </div><!-- /.box-header -->
                 <div class="box-body">
<form enctype="multipart/form-data" id="frm_graph" name="frm_graph" role="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div id="chartdiv"></div>
</form>
                 </div>
                </div>
              </div>
</div> 
          
          <div class="row">
            <div class="col-md-12">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">CSA Compliance</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
<table class="table table-striped" id="csa_table">
 <thead>
  <tr>
    <th>Name</th>
  </tr>
</thead>
<tbody>

<?php

// This is the list of user(s) and their violations.  This is default on page-load because
// We'll be dynamically updating this if we click in the graph
for($i=0;$i<count($driver_array);$i++) {
  if ($driver_array[$i]['employee_id'] != $emp_id) {
    // We only want to pull the csa data for the employee we chose in the select box.
     if ($emp_id != 'all') {
       // Skip this person
       continue;
     }
  }
  // Okay, now if we chose 'all' in the select box we'll reset the variable to the 
  // employee_id of the user in the current iteration of the for loop
  if ($emp_id == 'all') {
    $tmp_emp_id = $driver_array[$i]['employee_id'];
  }else{
    // Otherwise we'll just set this variable to the current emp_id val.
    $tmp_emp_id = $emp_id;
  }

// Let's append a modified name to the array (we'll strip out spaces)
$driver_array[$i]['modified_name'] = preg_replace("/\s/","_",$driver_array[$i]['name']);
?>
<tr>
<td>
<div style="float:right;width:80%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse"
  onClick="$(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-right');"
  href="#<?php echo $driver_array[$i]['modified_name'];?>_details" aria-expanded="false" aria-controls="<?php echo $driver_array[$i]['modified_name'];?>_details">
  </a></div>
<div style="float:left;width:20%;"><?php echo $driver_array[$i]['name'];?></div>
</td>
<td></td>
<td></td>
</tr>
<tr class="collapse" id="<?php echo $driver_array[$i]['modified_name'];?>_details">
<td colspan="9">
  <div class="well">

<table>
<tr>
  <?php 

$predicates = generate_compliance_predicate($tmp_emp_id, $productivity_time);
$sql = generate_user_csa_sql($predicates[0],$predicates[1]);
$user_csa_data = get_sql_results($sql,$mysqli);

for($j=0;$j<count($user_csa_data);$j++) {
?>
  <td style="padding: 5px">
    <label for="status">Date</label></td>
  <td style="padding: 5px">
    <label for="status">Catagory</label></td>
  <td style="padding: 5px">
    <label for="addr1">Code</label></td>
  <td style="padding: 5px">
    <label for="addr1">V Weight</label></td>
  <td style="padding: 5px">
    <label for="addr1">T Weight</label></td>
  <td colspan="2" style="padding: 5px">
    <label for="addr1">Description</label></td>
  <td colspan="2" style="padding: 5px">
    <label for="addr1">Co-Driver</label></td>
  <td colspan="2" style="padding: 5px">
    <label for="addr1">Points</label></td>
  <td colspan="2" style="padding: 5px">
    <label for="addr1">Cash Points</label></td>
</tr>
<tr>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['date'];?>" size="12" readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['basic'];   if (empty($user_csa_data[$j]['basic'])) { echo 'No Violation'; }?>" size="15"readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['code'];   if (empty($user_csa_data[$j]['code'])) {
    echo 'None';
}?>" size="5"readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['violation_weight'];?>" size="10" readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['time_weight'];?>" size="10" readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['description'];   if (empty($user_csa_data[$j]['description'])) {
    echo 'No Violations Discovered';
}?>" size="45"readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['co_driver_first_name'] . ' ' . $user_csa_data[$j]['co_driver_last_name'];?>" size="15" readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['total_points'];?>" size="12" readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $user_csa_data[$j]['points_cash_value'];?>" size="12" readonly></td>
</tr>
<?php
 }
 ?>
   </table>
  </div>
</td>
</tr>
<?php
                      }
?>
</tbody>
</table>
                </div><!-- ./box-body -->
              </div><!-- /.col -->
          </div><!-- /.row -->

<?php if ($_SESSION['login'] == 1)
      {
      ?>
      
<div class="col-md-12">
              <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">Internal  Compliance</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools --> 
                </div><!-- /.box-header -->
                 <div class="box-body">

                  <div class="input-daterange input-group" id="datepicker" style="width: 25%">
                   <input type="text" class="input-sm form-control datepicker" name="start" data-date-format="mm/dd/yyyy"/ required>
                   <span class="input-group-addon">to</span>
                   <input type="text" class="input-sm form-control datepicker" name="end" data-date-format="mm/dd/yyyy"/ required>
                  </div>
                 </div>
                </div>
              </div>












      
            <div class="col-md-12">
              <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">Company Violations</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->                  
                </div><!-- /.box-header -->
                 <div class="box-body">

                  <form enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table>
  <tr>
 <td style="padding: 5px">
  <label for="status">Date</label>
                  <div class="input-daterange input-group" id="datepicker_update">
                   <input type="text" class="input-sm form-control datepicker" name="violation_date" data-date-format="mm/dd/yyyy"/ required>
</div>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Driver</label>
  <select class="form-control"  value="" name="driver" required>
  <option value="">Select Driver</option>
  <?php
  foreach($drivers as $i)
  {
  ?>
  <option value="<?php echo $i;?>"><?php echo $i;?></option>
  <?php
  }
  ?>
  </select>
 </td>

 <td style="padding: 5px">
  <label for="status">Category</label>
  <select class="form-control" name="violation_cat">
  <?php
  foreach($violations as $a)
  {
  ?>
  <option value="<?php echo $a;?>"><?php echo $a;?></option>
  <?php
  }
  ?>
  </select>
 </td>
 <td style="padding: 5px">
  <label for="addr1">Group</label>
  <select class="form-control" name="violation_group" required>
   <option value="1">1</option>
  </select>
 </td>
 <td style="padding: 5px">
  <label for="addr1">Code</label>
  <select class="form-control" name="violation_code" required>
   <option value="1">1</option>
  </select>
 </td>
 <td style="padding: 5px; width: 90px;">
  <label for="addr1">V. Weight</label>
  <select class="form-control"  value="" name="violation_weight">
  <?php
  foreach(range(1,10) as $i)
  {
  ?>
  <option value="<?php echo $i;?>"><?php echo $i;?></option>
  <?php
  }
  ?>
  </select>
 </td>
 <td style="padding: 5px; width: 90px;">
  <label for="addr1">T. Weight</label>
  <select class="form-control"  value="" name="time_weight">
  <?php
    foreach(range(1,3) as $i)
  {
  ?>
  <option value="<?php echo $i;?>"><?php echo $i;?></option>
  <?php
  }
  ?>
  </select>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Description</label>
  <input type="text" class="form-control"  value="" name="description" required>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Co-Driver</label>
  <select class="form-control"  value="" name="co_driver">
  <option value="NULL">Select Driver</option>
  <?php
  foreach($drivers as $i)
  {
  ?>
  <option value="<?php echo $i;?>"><?php echo $i;?></option>
  <?php
  }
  ?>
  </select>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Total Points</label>
  <input type="text" class="form-control"  value="1" name="totoal_points" required>
 </td>
  </tr>
   <tr>
     <td style="padding: 5px">
       <input type="submit" name="update_csa" class="btn btn-primary" value="Submit">
       <?php
        $sql_emp = "SELECT employee_id from users where lower(\"".$row['first_name']."\") = lower(fname)
                AND lower(\"".$row['last_name']."\") = lower(lname)";
        $result_emp = mysql_query($sql_emp);
        $row_emp = mysql_fetch_array($result_emp);
        mysql_free_result($result_emp);
       ?>
       <input type="hidden" name="hdn_employee_id" value="<?php echo $row_emp[0];?>">
     </td>
         </tr>
</table>
   </form>
 
                 </div>
                </div>
              </div>

            <div class="col-md-12">
              <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">CSA Reports</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools --> 
                </div><!-- /.box-header -->
                 <div class="box-body">

                  <div class="input-daterange input-group" id="datepicker" style="width: 25%">
                   <input type="text" class="input-sm form-control datepicker" name="start" data-date-format="mm/dd/yyyy"/ required>
                   <span class="input-group-addon">to</span>
                   <input type="text" class="input-sm form-control datepicker" name="end" data-date-format="mm/dd/yyyy"/ required>
                  </div>
                 </div>
                </div>
              </div>
            </div>
        <?php
        }
        ?>

          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <div class="row">
              </div><!-- /.col -->
            </div><!-- /.row -->
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
<script>
    $('.datepicker_update').datepicker({
    startDate: "2015-01-01",
    todayBtn: "linked",
    autoclose: true,
    datesDisabled: '0',
    todayHighlight: true,
    });
</script>
<script>
   function populate_csa(data) {
       //console.log(data);
       $('#csa_table tbody').empty();

       // Get the driver names
       var driver_names = [];
       $.each(data, function(k, v) {
           //console.log( "Key: " + k + ", Value: " + v );
           $.each(v, function(key, value) {
              //console.log( "Key: " + key + ", Value: " + value );
              if (key == 'name') {
                driver_names.push(value);
              }
           })
       });

       // Remove duplicate driver names
       var unique_driver = driver_names.filter(function(elem, index, self) {
           return index == self.indexOf(elem);
       })
var r;
$.each(unique_driver.sort(), function(k,v) {
       r += `<tr>
       <td>
       <div style="float:right;width:80%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse"
       onClick="$(this).toggleClass(\'glyphicon-chevron-down glyphicon-chevron-right\');"
       href="#`+v.replace(/\s/g,'_')+`_details" aria-expanded="false" aria-controls="`+v.replace(/\s/g,'_')+`_details">
      </a></div>
       <div style="float:left;width:20%;">`+v+`</div>
       </td>
       <td></td>
       <td></td>
       </tr>
       <tr class="collapse" id="`+v.replace(/\s/g,'_')+`_details">
       <td colspan="9">
         <div class="well">
       
       <table>
       <tr>`;


       // While we're still in this loop let's re-loop through our json objects
       // to get the csa data we need...

       // Get the driver names
       $.each(data, function(k1, v1) { 
           // Each of our JSON objects (outer object)
           if (data[k1]['name'] == v) {
             //console.log(data[k1]['name']);
             //console.log(data[k1]['basic']);
                r += `<td style="padding: 5px">
                  <label for="status">Date</label></td>
                <td style="padding: 5px">
                  <label for="status">Catagory</label></td>
                <td style="padding: 5px">
                  <label for="addr1">Code</label></td>
                <td style="padding: 5px">
                  <label for="addr1">V Weight</label></td>
                <td style="padding: 5px">
                  <label for="addr1">T Weight</label></td>
                <td colspan="2" style="padding: 5px">
                  <label for="addr1">Description</label></td>
                <td colspan="2" style="padding: 5px">
                  <label for="addr1">Co-Driver</label></td>
                <td colspan="2" style="padding: 5px">
                  <label for="addr1">Points</label></td>
                <td colspan="2" style="padding: 5px">
                  <label for="addr1">Cash Points</label></td>
              </tr>
              <tr>
                <td style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['date']+`" size="12" readonly></td>
                <td style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['basic']+`" size="15"readonly></td>
                <td style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['code']+`" size="5"readonly></td>
                <td style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['violation_weight']+`" size="10" readonly></td>
                <td style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['time_weight']+`" size="10" readonly></td>
                <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['description']+`" size="45"readonly></td>
                <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['co_driver_first_name']+' '+data[k1]['co_driver_last_name']+`" size="15" readonly></td>
                <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['total_points']+`" size="12" readonly></td>
                <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="`+data[k1]['points_cash_value']+`" size="12" readonly></td>
              </tr>`;
           }
       });

       r += `</table>`;
});
$('#csa_table tbody').append(r.replace(/\n/g,''));
}
</script>
</body>
</html>

