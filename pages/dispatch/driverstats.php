<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: driverlogin.php');
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
<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Icons -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="../../dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
<link href="../../dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

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
div.tr.swipe {
	background-color: #7ACEF4;
}
</style>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require('header.php');?>
<?php require('sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1> Shipment Boards</h1>
        <ol class="breadcrumb">
          <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
          <!--        <li><a href="#">Tables</a></li> -->
          <li class="active">Shipments</li>
        </ol>
      </section>
      
      <!-- Main content -->
      <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Overview: Your Shipments</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Tasks</th>
                  <th>Progress</th>
                  <th style="width: 40px">Count</th>
                </tr>
                <?php
                     $sql = "SELECT
                      total_today.counts   AS total_today_count,
                      pu_today.counts      AS pu_today_count,
                      del_today.counts     AS del_today_count,
                      total_alltime.counts AS total_alltime_count,
                      pu_alltime.counts    AS pu_alltime_count,
                      del_alltime.counts   AS del_alltime_count
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
                      del_alltime";
                      
                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
                        $total_today_count   = $row[total_today_count];
                        $pu_today_count      = $row[pu_today_count];
                        $del_today_count     = $row[del_today_count];
                        $total_alltime_count = $row[total_alltime_count];
                        $pu_alltime_count    = $row[pu_alltime_count];
                        $del_alltime_count   = $row[del_alltime_count];
                      }
                      mysql_free_result($sql);
                    ?>
                <tr>
                  <td>1.</td>
                  <td>Todays HWB'S</td>
                  <td><div class="progress progress-xs">
                      <div class="progress-bar progress-bar-danger" 
                       style="width: <?php echo round(($total_today_count / $total_alltime_count)*100);?>%"></div>
                    </div></td>
                  <td><span class="badge bg-red"><?php echo "$total_today_count";?></span></td>
                </tr>
                <tr>
                  <td>2.</td>
                  <td>Todays PUs</td>
                  <td><div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" 
                        style="width: <?php echo round(($pu_today_count / $pu_alltime_count)*100);?>%"></div>
                    </div></td>
                  <td><span class="badge bg-yellow"><?php echo "$pu_today_count";?></span></td>
                </tr>
                <tr>
                  <td>3.</td>
                  <td>Todays Deliveries</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-primary" style="width: 30%"></div>
                    </div></td>
                  <td><span class="badge bg-light-blue"><?php echo "$del_today_count";?></span></td>
                </tr>
                <tr>
                  <td>4.</td>
                  <td>Total HWB Assigned PU/DEL</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green"><?php echo "$total_alltime_count";?></span></td>
                </tr>
                <tr>
                  <td>5.</td>
                  <td>Total PU's</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green"><?php echo "$pu_alltime_count";?></span></td>
                </tr>
                <tr>
                  <td>6.</td>
                  <td>Total DEL's</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green"><?php echo "$del_alltime_count";?></span></td>
                </tr>
                <tr>
                  <td>7.</td>
                  <td>Total updates from driver(s)</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Arrived to Shipper</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Picked Up</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Arrived to Consignee</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
                <tr>
                  <td></td>
                  <td>Delivered</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
                <tr>
                  <td>8.</td>
                  <td>Accessorials added</td>
                  <td><div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                    </div></td>
                  <td><span class="badge bg-green">90%</span></td>
                </tr>
              </table>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
        </div>
      </div>
      <!-- /.row --> 
    </div>
  </section>
  <!-- /.content --> 
</div>
<!-- /.content-wrapper -->
<?php require('footer.php');?>

<!-- Control Sidebar -->
<?php require('r_sidebar.php');?>
<!-- /.control-sidebar --> 
<!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
</div>
<!-- ./wrapper --> 

<!-- jQuery 2.1.4 --> 
<script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script> 
<!-- Bootstrap 3.3.2 JS --> 
<script src="../../bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<!-- Slimscroll --> 
<script src="../../plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<!-- FastClick --> 
<script src='../../plugins/fastclick/fastclick.min.js'></script> 
<!-- AdminLTE App --> 
<script src="../../dist/js/app.min.js" type="text/javascript"></script> 
<!-- Swipe --> 
<script src="../../dist/js/swipe.js" type="text/javascript"></script>
</body>
</html>
