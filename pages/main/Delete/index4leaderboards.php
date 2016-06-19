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
$role = $_SESSION['role'];

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
<BASE href="http://dispatch.catalinacartage.com">
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
            Welcome <span class="box-title"><?php echo "$_SESSION[fname]"; ?></span></h1>

          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->  


          <div class="row">
            <div class="col-md-12">
              <div class="box"><!-- /.box-header --><!-- ./box-body -->


          <div class="row">
            <!-- Left col -->
            <div class="col-md-8"  style="width: 100%;">
              <!-- MAP & BOX PANE -->
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Shipment Updates Stats</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-8">
                      <p class="text-center">
                        <strong>Dispatch vs. Update - 12 months.</strong>
                      </p>
                      <div class="chart">
                        <!-- Sales Chart Canvas -->
                        <canvas id="dispatchChart" style="height: 380px; width: 760px;" width="1520" height="360"></canvas>
                      </div><!-- /.chart-responsive -->
                        <div id="js-legend" class="chart-legend"></div>
                    </div><!-- /.col -->
                    <div class="col-md-4">
                      <p class="text-center">
                        <strong>User Statistics - current month.</strong>
                      </p>
                      <div class="progress-group">
<?php
$sql = "SELECT * 
FROM   (SELECT Count(*) AS dispatch_orders 
        FROM   dispatch 
        WHERE  ( puagentdriverphone = (SELECT driverid 
                                       FROM   users 
                                       WHERE  username = \"$username\") 
                 AND Extract(YEAR_MONTH FROM Str_to_date(hawbdate, '%c/%e/%Y')) BETWEEN 
                     Extract(YEAR_MONTH FROM CURRENT_DATE) AND Extract( 
                     YEAR_MONTH FROM 
                         CURRENT_DATE + INTERVAL 1 month) ) 
                OR ( delagentdriverphone = (SELECT driverid 
                                            FROM   users 
                                            WHERE  username = \"$username\") 
                     AND Extract(YEAR_MONTH FROM Str_to_date(duedate, '%c/%e/%Y')) BETWEEN 
                         Extract(YEAR_MONTH FROM CURRENT_DATE) AND Extract( 
                         YEAR_MONTH FROM 
                             CURRENT_DATE + INTERVAL 1 month) )) dispatch, 
       (SELECT Count(*) AS updated_orders 
        FROM   driverexport 
        WHERE  employee_id = (SELECT employee_id 
                              FROM   users 
                              WHERE  username = \"$username\") 
               AND ( status = 'Picked Up' 
                      OR status = 'Delivered' ) 
               AND extract(YEAR_MONTH FROM driverexport.date) BETWEEN Extract(YEAR_MONTH FROM CURRENT_DATE) AND 
                                Extract( 
                                YEAR_MONTH FROM 
                                    CURRENT_DATE + INTERVAL 1 month)) 
       driverexport, 
       (SELECT Count(*) AS pu_dispatch 
        FROM   dispatch 
        WHERE  ( puagentdriverphone = (SELECT driverid 
                                       FROM   users 
                                       WHERE  username = \"$username\") 
                 AND Extract(YEAR_MONTH FROM Str_to_date(hawbdate, '%c/%e/%Y')) BETWEEN 
                     Extract(YEAR_MONTH FROM CURRENT_DATE) AND Extract( 
                     YEAR_MONTH FROM 
                         CURRENT_DATE + INTERVAL 1 month) )) pu_dispatch, 
       (SELECT Count(*) AS pu_updated 
        FROM   driverexport 
        WHERE  employee_id = (SELECT employee_id 
                              FROM   users 
                              WHERE  username = \"$username\") 
               AND status = 'Picked Up' 
               AND extract(YEAR_MONTH FROM driverexport.date) BETWEEN Extract(YEAR_MONTH FROM CURRENT_DATE) AND 
                                Extract( 
                                YEAR_MONTH FROM 
                                    CURRENT_DATE + INTERVAL 1 month)) 
       pickup_updated, 
       (SELECT Count(*) AS del_dispatch 
        FROM   dispatch 
        WHERE  ( delagentdriverphone = (SELECT driverid 
                                        FROM   users 
                                        WHERE  username = \"$username\") 
                 AND Extract(YEAR_MONTH FROM Str_to_date(duedate, '%c/%e/%Y')) BETWEEN 
                     Extract(YEAR_MONTH FROM CURRENT_DATE) AND Extract( 
                     YEAR_MONTH FROM 
                         CURRENT_DATE + INTERVAL 1 month) )) del_dispatch, 
       (SELECT Count(*) AS del_updated 
        FROM   driverexport 
        WHERE  employee_id = (SELECT employee_id 
                              FROM   users 
                              WHERE  username = \"$username\") 
               AND status = 'Delivered' 
               AND extract(YEAR_MONTH FROM driverexport.date) BETWEEN Extract(YEAR_MONTH FROM CURRENT_DATE) AND 
                                Extract( 
                                YEAR_MONTH FROM 
                                    CURRENT_DATE + INTERVAL 1 month)) 
       del_updated,
       (SELECT count(*) accessorials_count
  FROM (select accessorials from driverexport where employee_id = (SELECT employee_id 
                              FROM   users 
                              WHERE  username = \"$username\")
AND extract(YEAR_MONTH FROM driverexport.date) BETWEEN Extract(YEAR_MONTH FROM CURRENT_DATE) AND 
                                Extract( 
                                YEAR_MONTH FROM 
                                    CURRENT_DATE + INTERVAL 1 month)) t CROSS JOIN 
(
   SELECT a.N + b.N * 10 + 1 n
     FROM 
    (SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
   ,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
    ORDER BY n
) n
 WHERE n.n <= 1 + (LENGTH(t.accessorials) - LENGTH(REPLACE(t.accessorials, ',', '')))) accessorials";
$result = mysql_query($sql);
$row = mysql_fetch_array($result,MYSQL_BOTH);
?>
                        <span class="progress-text"> Total: Updated vs Dispatched</span>
                        <span class="progress-number"><b><?php echo $row['updated_orders'];?></b>/<?php echo $row['dispatch_orders'];?></span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-aqua" style="width: <?php echo (($row['updated_orders'] / $row['dispatch_orders'] ) * 100);?>%"></div>
                        </div>
                         <div class="progress-group">
                        <span class="progress-text"> PU: Updated  vs Dispatched</span>
                        <span class="progress-number"><b><?php echo $row['pu_updated'];?></b>/<?php echo $row['pu_dispatch'];?></span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-blue" style="width: <?php echo (($row['pu_dispatch'] / $row['pu_updated'] ) * 100);?>%"></div>
                        </div>
                      </div><!-- /.progress-group -->
                      <div class="progress-group">
                        <span class="progress-text">DEL: Updated vs Dispatched</span>                        
                        <span class="progress-number"><b><?php echo $row['del_updated'];?></b>/<?php echo $row['del_dispatch'];?></span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-red" style="width: <?php echo (($row['del_dispatch'] / $row['del_updated'] ) * 100);?>%"></div>
                        </div>
                      </div><!-- /.progress-group -->
                      <div class="progress-group">
                        <span class="progress-text">Accessorials added vs HWB Updated</span>
                        <span class="progress-number"><b><?php echo $row['accessorials_count'];?></b>/<?php echo $row['updated_orders'];?></span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-green" style="width: <?php echo (($row['accessorials_count'] / $row['updated_orders'] ) * 100);?>%"></div>
                        </div>
                      </div><!-- /.progress-group -->
                      <div class="progress-group">
                        <span class="progress-text">IFTA Reports submitted vs trips run OTR</span><span class="progress-number"><b>250</b>/500</span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-yellow" style="width: 80%"></div>
                        </div>
                        <div class="progress-group">
                        <span class="progress-text">CSA Points</span><span class="progress-number"><b>5</b>/25</span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-red" style="width: 10%"></div>
                        </div>
                      </div><!-- /.progress-group -->
                        <div class="progress-group">
                        <span class="progress-text">VIR Updates</span><span class="progress-number"><b>5</b>/40</span>
                        <div class="progress sm">
                          <div class="progress-bar progress-bar-red" style="width: 10%"></div>
                         </div>
                        </div>
                       </div>
                      </div>
                     </div><!-- /.progress-group -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div><!-- ./box-body -->
               </div>
                </div>
                <!-- /.box-body -->
              </div>
                 <div class="row">
                <div class="col-md-6">
               </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.col -->
           </div><!-- /.row -->
           
 
 
 
           <!--  Google Pie Chart start -->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  
                  <h3 class="box-title">All Company Stats</h3>

                  <!-- Remove Search Tool
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
                <div class="box-body table-responsive no-padding">

    			<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    			<script type="text/javascript">
      			google.charts.load('current', {'packages':['corechart']});
     			google.charts.setOnLoadCallback(drawChart);
      			function drawChart() {

		        var data = google.visualization.arrayToDataTable([
        		['Task', 'Hours per Day'],
          		['HWB Updates',     11],
          		['VIR Updates',      2],
          		['Paperwork',  2],
          		['CSA Compliance', 2],
          		['Accessorials',    7]				
        		]);

        		var options = {
          		title: 'Company Stats'
       			};

        		var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        		chart.draw(data, options);
      			}
    			</script>
     	        <div id="piechart" style="width: 400px; height: 300px;"></div>
 






 
 
 
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>        
          <!--  Google Pie Chart End -->          
           
           
      
        
        
        
        













          <!-- Top Box Full sized window -->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  
                  <h3 class="box-title">Top Performers</h3>

                  <!-- Remove Search Tool
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
                <div class="box-body table-responsive no-padding">
                  <table width="2506%" class="table table-hover">
                    <tr>
                      <th width="2%">#</th>
                      <th width="17%">Name</th>
                      <th width="22%">Graph Score</th>
                      <th width="6%">Score</th>
                      <th width="11%">Positive Points</th>
                      <th width="12%"> Negative Points</th>
                      <th width="8%">Total Points</th>
                      <th width="11%">Best Category</th>
                      <th width="11%">Worst Category</th>
                    </tr>
                    <tr>
                      <td>1</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Crayston MiddleBrook</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>5</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>6</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>7</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>8</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 20%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>9</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 20%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>10</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 20%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-red">32</span></td>
                      <td><span class="badge bg-light-blue">1768</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>         
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">
             <!-- Removing box heeader with border  
                <div class="box-header with-border">
                  <blockquote> 
             -->
              <!-- Adding the Spinner Box Icon here   
              <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-progress"><i class="fa fa-spinner fa-spin"></i></span>
                </a>                    
              </div>
              </div>
              -->      
                    <h3 class="box-title"> Shipment Updates</h3>
                       <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>
                  </blockquote>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" width="24" height="24" class="img-circle"> Dash</td>
                     
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>

                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td>
                        <div class="progress progress-xs progress-striped active">
                          <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                        </div>
                      </td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 39%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 20%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                <!-- /. removing the Pagination Replce by Scroll Bar -->
<!--
                  <ul class="pagination pagination-sm no-margin pull-right">
                    <li><a href="#">&laquo;</a></li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">&raquo;</a></li>
                  </ul>
-->Would like to have a scoll bars for all these categories show only the 1st 10 in the box and then the scroll activates to see users below in that category.</div>
              </div><!-- /.box -->

<!-- Found this code on JSfiddle.  Not sure if it will help with the programming of the progress bars...-->
<!-- 
jQuery(document).ready( function(){
    window.percent = 0;
    window.progressInterval = window.setInterval( function(){
        if(window.percent < 100) {
            window.percent++;
            jQuery('.progress').addClass('progress-striped').addClass('active');
            jQuery('.progress .bar:first').removeClass().addClass('bar')
            .addClass ( (percent < 40) ? 'bar-danger' : ( (percent < 80) ? 'bar-warning' : 'bar-success' ) ) ;
            jQuery('.progress .bar:first').width(window.percent+'%');
            jQuery('.progress .bar:first').text(window.percent+'%');
        } else {
            window.clearInterval(window.progressInterval);
            jQuery('.progress').removeClass('progress-striped').removeClass('active');
            jQuery('.progress .bar:first').text('Done!');
        }
    },
-->







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
              
                 <h3 class="box-title">VIR Pre &amp; Post Trips</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
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
              
                 <h3 class="box-title">Paperwork Compliance</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
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
              
                 <h3 class="box-title">CSA Compliance</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
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
                  <h3 class="box-title"> Accessorial Added</h3>
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
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->











                <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Trip Reports</h3>
                  <div class="box-tools pull-right">
                  <!--          <div class="box"> -->
                  <!--Remove the div Class "box" above and add ?? to below primary collapsed -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div> 
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
 <div class="box">
                <div class="box-header">
                  <h3 class="box-title">VIR Pre &amp; Post Trips</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
                    </tr>
                  </table>
              </div><!-- /.box-body -->
              </div>             
              
              
              
              
              
              
              
              
              
              
              
              










<div class="box">
                <div class="box-header">
                  <h3 class="box-title">Truck Idle Time</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
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
                  <h3 class="box-title">Admin Window Below</h3>
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
                  <table width="595%" class="table table-hover">
                    <tr>
                      <th>#</th>
                      <th>User</th>
                      <th>Days Worked</th>
                      <th>Status</th>
                      <th>Reason</th>
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
<!-- ChartJS -->
<script src="<?php echo HTTP;?>/dist/js/Chart.min.js"></script>
<script>
// Get context with jQuery - using jQuery's .get() method.
var ctx = $("#dispatchChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.
<?php
# Create array with months.
$sql = "select monthname(str_to_date(pu_month,'%m-%y')),sum(pickups) from
(
SELECT
date_format(str_to_date(hawbDate,'%c/%e/%Y'),'%m-%y') pu_month,
sum(CASE monthname(str_to_date(hawbDate,'%c/%e/%Y'))
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'Octover' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    dispatch
WHERE

    puAgentDriverPhone = (SELECT 
            driverid
        FROM
            users
        WHERE
            username = \"$username\")           
AND 
str_to_date(hawbDate,'%c/%e/%Y') > DATE(now()) - INTERVAL 12 MONTH
group by pu_month
UNION ALL
SELECT
date_format(str_to_date(dueDate,'%c/%e/%Y'),'%m-%y') pu_month,
sum(CASE monthname(str_to_date(dueDate,'%c/%e/%Y'))
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'October' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    dispatch
WHERE

    delAgentDriverPhone = (SELECT 
            driverid
        FROM
            users
        WHERE
            username = \"$username\")           
AND 
str_to_date(dueDate,'%c/%e/%Y') > DATE(now()) - INTERVAL 12 MONTH
group by pu_month
) foo
group by pu_month
order by pu_month DESC";

$months = array();
$dispatch_number = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result,MYSQL_BOTH))
{
  array_push($months,"'$row[0]'");
  array_push($dispatch_number,$row[1]);
}
mysql_free_result($result);

$months =  rtrim(implode(',',$months),',');
$dispatch_number =  rtrim(implode(',',$dispatch_number),',');
?>
var data = {
    labels: [<?php echo $months;?>],
    datasets: [
        {
            label: "Dispatched",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo $dispatch_number;?>]
        },
<?php
$sql = "SELECT
monthname(date) pu_month,
SUM(CASE monthname(date)
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'October' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    driverexport
WHERE employee_id =
(select employee_id from users where username = \"$username\")
AND
date > DATE(now()) - INTERVAL 12 MONTH
AND
(status = 'Picked Up' OR status = 'Delivered')
group by pu_month
order by date DESC";

$months = array();
$dispatch_number = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result,MYSQL_BOTH))
{
  array_push($months,"'$row[0]'");
  array_push($dispatch_number,$row[1]);
}
mysql_free_result($result);

$months =  rtrim(implode(',',$months),',');
$dispatch_number =  rtrim(implode(',',$dispatch_number),',');
?>
       {
            label: "Updated",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo $dispatch_number;?>]
        },
    ]
};
var myLineChart = new Chart(ctx).Line(data, {
});
x = myLineChart.generateLegend();
$("#js-legend").html(x);
</script>

<!-- Demo -->
</body>
</html>