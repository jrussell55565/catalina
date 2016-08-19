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

# Get Violation Cats.
$violations = array();
$statement = "select distinct basic from csadata where basic != ''";
$result = mysql_query($statement);
while($row = mysql_fetch_array($result))
{
  array_push($violations,$row[0]);
}
mysql_free_result($result);

$drivers = array();
$statement = "select drivername from users where status = 'Active' ORDER BY 1";
$result = mysql_query($statement);
while($row = mysql_fetch_array($result))
{
  # Used to populate the driver dropdown box
  array_push($drivers,$row[0]);
}
mysql_free_result($result);

if ($_POST['update_csa'])
{
  # Get the employee_id for the user specified
  $statement = "SELECT employee_id FROM users WHERE
                drivername = '".$_POST['driver']."'";

  $result = mysql_query($statement);
  $row = mysql_fetch_array($result,MYSQL_BOTH);
  mysql_free_result($result);
                
  $sql = "INSERT INTO csadata_int (
          employee_id,
          creation_date,
          violation_cat,
          violation_group,
          violation_code,
          violation_weight,
          violation_time_weight,
          violation_description,
          co_driver,
          total_points,
          author) 
          VALUES (
          '".$row['employee_id']."',
          str_to_date('".$_POST['violation_date']."','%m/%d/%Y'),
          '".$_POST['violation_cat']."',
          '".$_POST['violation_group']."',
          '".$_POST['violation_code']."',
          ".$_POST['violation_weight'].",
          ".$_POST['time_weight'].",
          '".$_POST['description']."',
          '".$_POST['co_driver']."',
          ".$_POST['total_points'].",
          '".$_SESSION['employee_id']."'
          )";

  $result = mysql_query($sql) or die ("unable to insert into csadata_int: ".mysql_error()); 
  unset($_POST);
  header("Location: csa.php");
}
?>



<?php
                     $sql = "SELECT
                      total_today.counts   AS total_today_count,
                      pu_today.counts      AS pu_today_count,
                      del_today.counts     AS del_today_count,
                      total_alltime.counts AS total_alltime_count,
                      pu_alltime.counts    AS pu_alltime_count,
                      del_alltime.counts   AS del_alltime_count,
                      archived.counts      AS archived_count,
                      virs_daily.count     AS virs_daily_count,
                      virs_weekly.count    AS virs_weekly_count
                    FROM
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          AND str_to_date(hawbDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                          =\"F\"
                          AND archived                         =\"F\"
                          )
                        OR
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username =\"$username\"
                            )
                          AND str_to_date(dueDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                         =\"F\"
                          AND archived                        =\"F\"
                          )
                      )
                      total_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          puAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND str_to_date(hawbDate,'%c/%e/%Y') = DATE(now())
                        AND deleted                          =\"F\"
                        AND archived                         =\"F\"
                        AND deleted                          =\"F\"
                        AND archived                         =\"F\"
                      )
                      pu_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          delAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND str_to_date(dueDate,'%c/%e/%Y') = DATE(now())
                        AND deleted                         =\"F\"
                        AND archived                        =\"F\"
                        AND deleted                         =\"F\"
                        AND archived                        =\"F\"
                      )
                      del_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          OR puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      total_alltime,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          puAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      pu_alltime,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          delAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      del_alltime,
                      (
                      SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          OR puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          )
                        AND deleted =\"F\"
                        AND archived=\"T\"
                      )
                      archived,
                     (
                      SELECT
                          COUNT(*) AS count
                        FROM
                          virs
                        WHERE
                        driver_name=\"$username\"
                        AND insp_date = date(now())
                      ) virs_daily,
                      (
                      SELECT
                          COUNT(*) AS count
                        FROM
                          virs
                        WHERE
                        driver_name=\"$username\"
                        AND insp_date BETWEEN date(now()) AND date(now()) - INTERVAL 8 DAY
                      ) virs_weekly";

                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
                        $total_today_count   = $row['total_today_count'];
                        $pu_today_count      = $row['pu_today_count'];
                        $del_today_count     = $row['del_today_count'];
                        $total_alltime_count = $row['total_alltime_count'];
                        $pu_alltime_count    = $row['pu_alltime_count'];
                        $del_alltime_count   = $row['del_alltime_count'];
                        $archived_count      = $row['archived_count'];
                        $virs_daily_count    = $row['virs_daily_count'];
                        $virs_weekly_count   = $row['virs_weekly_count'];
                      }
                      mysql_free_result($sql);

if (isset($_POST['submit']) && $_POST['submit'] == 'share')
{
  $audience = $_POST['audience'];
  if ($audience == 'PHX')
  {
    $predicate = "AND office='PHX'";
  }
  if ($audience == 'TUS')
  {
    $predicate = "AND office='TUS'";
  }
    if ($audience == 'PHL')
  {
    $predicate = "AND office='PHL'";
  }
    if ($audience == 'DEN')
  {
    $predicate = "AND office='DEN'";
  }
    if ($audience == 'LAX')
  {
    $predicate = "AND office='LAX'";
  }
    if ($audience == 'MIA')
  {
    $predicate = "AND office='MIA'";
  }
    if ($audience == 'ORD')
  {
    $predicate = "AND office='ORD'";
  }
  $message = $_POST['message'];
  $sql = "SELECT 1";
  if (isset($_POST['sendEmail']))
  {
    $sql .= ",email";
  } 
  if (isset($_POST['sendText']))
  {
    $sql .= ",vtext";
  } 
  $sql .= " FROM users WHERE 1=1 $predicate AND status='Active'";

  $sql = mysql_query($sql);
  while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
  {
    if (isset($_POST['sendEmail']))
    {
      sendEmail($row['email'],'Broadcast Message',$message); 
    } 
    if (isset($_POST['sendText']))
    {
      sendEmail($row['vtext'],'Broadcast Message',$message); 
    }
  }
  mysql_free_result($sql);

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
          
  
          
          <div class="row">
            <div class="col-md-12">
              <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">CSA Compliance 
                    <input name="radio" type="radio" id="activeusers" value="activeusers" checked>
                    <label for="activeusers"></label>
                    <label for="userstatus"></label>
Active Users /
<input type="radio" name="radio" id="inactiveusers" value="inactiveusers">
<label for="inactiveusers"></label>
Inactive Users /
<input type="radio" name="radio" id="allusers" value="allusers">
<label for="allusers"></label>
All Users 
<select name="productivity_time" id="productivity_time">
  <option value="24">24 Months</option>
  <option value="12">12 Months</option>
  <option value="6">6 Months</option>
  <option value="all">All</option>
</select>
                  </h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
<table class="table table-striped">
 <thead>
  <tr>
    <th>Name</th>
    <th>Points</th>
  </tr>
 </thead>
 <tbody>
<?php
if ($_SESSION['login'] == 2)
{
  $predicate = " AND lower(first_name) = lower('".$_SESSION['fname']."')
                 and lower(last_name) = lower('".$_SESSION['lname']."')";
}
$sql = "select distinct upper(fname) first_name, upper(lname) last_name from users
        WHERE title='Driver' $predicate ORDER BY 1";

$sql = mysql_query($sql);
while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
{
?>
<tr>
<td>
<div style="float:left;width:20%;"><?php echo $row['first_name'] . " " . $row['last_name'];?></div>
<div style="float:right;width:80%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse"
  onClick="$(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-right');"
  href="#<?php echo $row['last_name'];?>_details" aria-expanded="false" aria-controls="<?php echo $row['last_name'];?>_details">
  </a></div>
</td>
<td><?php echo $row['date'];?></td>
<td><?php echo $row['total_points'];?></td>
</tr>
<tr class="collapse" id="<?php echo $row['last_name'];?>_details">
<td colspan="9">
  <div class="well">

<table>
<tr>
  <?php 
       $first_name = $row['first_name'];
       $last_name = $row['last_name'];
       $sqlDetails = "SELECT date,
basic,
violation_group,
code,
violation_weight,
time_weight,description,
co_driver_first_name,
co_driver_last_name,
total_points
from csadata where first_name = '$first_name' and last_name= '$last_name'
UNION ALL
select creation_date,
violation_cat,
violation_group,
violation_code,
violation_weight,
violation_time_weight,
violation_description,
total_points,
(SELECT fname from users WHERE drivername = co_driver) co_driver_first_name,
(SELECT lname from users WHERE drivername = co_driver) co_driver_last_name
from csadata_int where employee_id = (select employee_id from users
where lower(fname) = lower('$first_name') and lower(lname) = lower('$last_name'))";

       $sqlDetails = mysql_query($sqlDetails);
       while ($rowDetails = mysql_fetch_array($sqlDetails, MYSQL_BOTH))
       { 
 ?>
  <td style="padding: 5px">
    <label for="status">V Date</label></td>
  <td style="padding: 5px">
    <label for="status">V Catagory</label></td>
  <td style="padding: 5px">
    <label for="addr1">V Group</label></td>
  <td style="padding: 5px">
    <label for="addr1"> Code</label></td>
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
</tr>
<tr>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['date'];?>" size="12" readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['basic'];   if (empty($rowDetails['basic'])) {
    echo 'No Violation';
}?>" size="15"readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['violation_group'];   if (empty($rowDetails['violation_group'])) {
    echo 'None';
}?>" size="18"readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['code'];   if (empty($rowDetails['code'])) {
    echo 'None';
}?>" size="5"readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['violation_weight'];?>" size="10" readonly></td>
  <td style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['time_weight'];?>" size="10" readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['description'];   if (empty($rowDetails['description'])) {
    echo 'No Violations Discovered';
}?>" size="45"readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['co_driver_first_name'] . ' ' . $rowDetails['co_driver_last_name'];?>" size="15" readonly></td>
  <td colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="<?php echo $rowDetails['total_points'] . ' ' . $rowDetails['total_points'];?>" size="12" readonly></td>
</tr>
<?php
 }
 mysql_free_result($sqlDetails);
 ?>
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

<?php if ($_SESSION['login'] == 1)
      {
      ?>
      
<div class="col-md-12">
              <div class="box box-defualt collapsed-box">
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
              <div class="box box-defualt collapsed-box">
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
              <div class="box box-defualt collapsed-box">
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

</body>
</html>
