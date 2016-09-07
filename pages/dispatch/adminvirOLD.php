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
               <?php
                     $sql = "SELECT
                      total_today.counts   AS total_today_count,
                      pu_today.counts      AS pu_today_count,
                      del_today.counts     AS del_today_count,
                      total_alltime.counts AS total_alltime_count,
                      pu_alltime.counts    AS pu_alltime_count,
                      del_alltime.counts   AS del_alltime_count,
                      archived.counts      AS archived_count
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
                      archived";
                  
                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
                        $total_today_count   = $row[total_today_count];
                        $pu_today_count      = $row[pu_today_count];
                        $del_today_count     = $row[del_today_count];
                        $total_alltime_count = $row[total_alltime_count];
                        $pu_alltime_count    = $row[pu_alltime_count];
                        $del_alltime_count   = $row[del_alltime_count];
                        $archived_count      = $row[archived_count];
                      }
                      mysql_free_result($sql);
                    ?>
          
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title"></h4>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <form method="post" action="processshipments.php">
              <table class="table">
              <tr>
                <th>VIR Name</th>
                <th>Type</th>
                <th>Input Type</th>
                <th>Page</th>
                <th>Option</th>
              </tr>
      <form id="accessorials_add" name="accessorials_add" method="post" action="accessorialactions.php">
        <tr>
          <td>
            <input name="virName" type="text" id="virName"/>
          </td>
          <td>
            <select name="virType" id="virType">
              <option selected="selected">TruckVIR</option>
              <option>TrailerVIR</option>
            </select>
          </td>
          <td><input name="revenue_amount" type="text" id="revenue_amount"/></td>
          <td><select name="checkortext" id="checkortext">
              <option selected="selected">Check Box</option>
              <option>Text Field</option>
              <option>Hidden Field</option>
            </select></td>
          <td><select name="srcPage" id="srcPage">
              <option selected="selected"></option>
              <option>puconfirmed.php</option>
              <option>delconfirmed.php</option>
              <option>TrailerDroppedPU.php</option>
              <option>TrailerDroppedDEL.php</option>
              <option>ArrivedtoConsignee.php</option>
              <option>AttemptDEL.php</option>
              <option>Refused.php</option>
              <option>AttemptPU.php</option>
              <option>ArrivedtoShipper.php</option>
            </select></td>
          <td><input name="btn_submit" value="Add" type="submit" id="btn_submit" /></td>
         </tr>
      </form>
              <form method=post action="./processshipments.php">
                <?php
                $deliveryQuery = "delAgentDriverPhone=(select driverid from users where username=\"$username\")";
                $pickupQuery = "puAgentDriverPhone=(select driverid from users where username=\"$username\")";
                $sql = "select hawbNumber,recordID,shipperName,consigneeName,status from dispatch
                        where (";
                if ($_GET['gather'] == "pu")
                {
                  $sql .= $deliveryQuery . ")";
                }elseif ($_GET['gather'] == "del")
                {
                  $sql .= $pickupQuery . ")";
                }else{
                  $sql .= $deliveryQuery . " OR " . $pickupQuery . ")";
                }

                if ($_GET['gather'] == "archived")
                {
                    $sql .= "AND archived=\"T\" AND deleted=\"F\"";
                }else{
                    $sql .= "AND archived=\"F\" AND deleted=\"F\"";
                }

                $sql .= " ORDER BY str_to_date(hawbDate,'%c/%e/%Y') DESC";
                $sql = mysql_query($sql);

                # Purple (DBCCE6): Pickup; Green (E9FCE9): Delivered; Red (F8D0D8): Other
                $array = array(
               "Delivered" => "#E9FCE9",
               "Picked UP" => "#DBCCE6",
               "In Transit" => "#F8D0D8",
               "Terminate at Origin" => "#F8D0D8",
               "Dispatched" => "#F8D0D8",
               "Booked" => "#F8D0D8",
               "On Dock TUS" => "#F8D0D8",
               "Freight At Dock" => "#F8D0D8",
               "On Dock PHX" => "#F8D0D8",
               "Dispatched Confirmed" => "#F8D0D8",
               "CALL FOR APPOINTMENT" => "#F8D0D8",
               "Out for Delivery" => "#F8D0D8",
               "Need Routing" => "#F8D0D8",
               "Need 2 Sch Appt" => "#F8D0D8",
               "Hold For Routing" => "#F8D0D8",
               "On Dock LAX" => "#F8D0D8",
               "Verify Delivery Date" => "#F8D0D8",
               "Arrived to Shipper" => "#F8D0D8",
               "Ready To Invoice" => "#F8D0D8",
               "On Hand Destination" => "#F8D0D8",
               "Appointment Scheduled" => "#F8D0D8",
               "ATTEMPT" => "#F8D0D8",
               "Recovered" => "#F8D0D8",
               "Verify Pickup Date" => "#F8D0D8",
               "Refused" => "#F8D0D8",
               "In Storage" => "#F8D0D8",
               "Unable to Locate" => "#F8D0D8",
               "Arrived to Consignee" => "#F8D0D8",
               "APPT. SCHEDULED" => "#F8D0D8",
               "Trailer Dropped" => "#F8D0D8",
               "New Hwb" => "#F8D0D8",
               "Attempted Delivery" => "#F8D0D8",
               "Outside Carrier Scheduled" => "#F8D0D8",
               "Attempted Pick Up" => "#F8D0D8",
               "Verify Shipment Assignment" => "#F8D0D8",
               "Left Apt Message" => "#F8D0D8",
               "Trace note PU" => "#F8D0D8",
               "Accepted PU" => "#F8D0D8",
                );
                $counter = 0;
                while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                {

                ?>
                  <tr>
                    <td>
                     <span>
                      <input type="hidden" id="<?php echo "order[$counter][hawb]"; ?>" name="<?php echo "order[$counter][hawb]"; ?>" value="<?php echo "$row[hawbNumber]";?>" />
                      <a href="singlehwb.php?hwb=<?php echo "$row[hawbNumber]"; ?>&amp;recordid=<?php echo "$row[recordID]"; ?>"><?php echo "$row[hawbNumber]"; ?></a><br>
                      <?php echo "$row[status]";?>
                     </span>
                    </td>
                    <td><span>Shipper: <?php echo "$row[shipperName]";?></span><br>
                      <span>Consignee: <?php echo "$row[consigneeName]";?></span><br></td>
                  </tr>
                </div>
                <?php
                }
                ?>
                <tr>
                  <td colspan="3"><div class="input-group">
                      <div class="input-group-btn">
                        <?php
                         if ($_GET['gather'] == "archived")
                         {
                        ?>
                           <span style="padding-right: 5px">
                            <input type="submit" class="btn btn-danger" id="delete"
                          name="delete" value="Delete Selected"></input>
                           </span>
                           <span>
                            <input type="submit" class="btn btn-danger" id="unarchive"
                          name="unarchive" value="Unarchive Selected"></input>
                           </span>
                        <?php
                         }else{
                        ?>
                        <span>
                        <input type="submit" class="btn btn-danger" id="archive"
                          name="archive" value="Archive Selected"</input>
                        </span>
                        <?php
                         }
                        ?>
                      </div>
                      <!-- /btn-group --> 
                    </div></td>
                </tr>
                </table>
              </form>
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
