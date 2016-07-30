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
<title>Shipments</title>
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

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<!-- Custom CSS -->
<link href="<?php echo HTTP;?>/dist/css/catalina.css" rel="stylesheet" type="text/css" />

<script language="JavaScript">
  function toggle(source) {
  checkboxes = document.getElementsByName('chk_hawb[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
  }
  </script>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>PU&amp;DEL(s) <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
        <?php if ($_SESSION['login'] == 1) { echo "(Admin)"; }?>
        </a></h1>
        <a href="/pages/info/colors.php"> Click here for info about colors of the Boards! </a>
        <ol class="breadcrumb">
          <li><a href="/pages/main/index.php"><i class="fa fa-home"></i>Home</a></li>
          <!--        <li><a href="#">Tables</a></li> -->
          <li class="active">Shipment Boards</li>
        </ol>
      </section>
      
      <!-- Main content -->
      <section class="content">
               <?php
                     # if we're an admin then get all the orders
                     if ($_SESSION['login'] == 1)
                     {
                       $puPredicate = '1 = 1';
                       $delPredicate = '1 = 1';
                     }elseif ($_SESSION['login'] == 2){
                       $puPredicate = "puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                             )";
                       $delPredicate = "delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )";
                     }
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
                            $puPredicate
                          AND str_to_date(hawbDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                          =\"F\"
                          AND archived                         =\"F\"
                          )
                        OR
                          (
                           $delPredicate
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
                        $puPredicate  
                        AND str_to_date(hawbDate,'%c/%e/%Y') = DATE(now())
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
                        $delPredicate 
                        AND str_to_date(dueDate,'%c/%e/%Y') = DATE(now())
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
                            $delPredicate
                            OR 
                            $puPredicate
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
                        $puPredicate
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
                        $delPredicate 
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
                          $delPredicate 
                          OR
                          $puPredicate 
                          )
                        AND deleted =\"F\"
                        AND archived=\"T\"
                      )
                      archived";

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
                      }
                      mysql_free_result($sql);
                    ?>
          
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title"></h4>
              <div class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">
                  <li <?php if ($_GET['gather'] == 'pu') { echo "class=\"active\""; }?>>
                   <a href="orders.php?gather=pu">Pickups <?php echo "$pu_today_count";?></a></li>
                  <li <?php if ($_GET['gather'] == 'del') { echo "class=\"active\""; }?>>
                   <a href="orders.php?gather=del">Deliveries <?php echo "$del_today_count";?></a></li>
                  <li <?php if ($_GET['gather'] == '') { echo "class=\"active\""; }?>>
                   <a href="orders.php">Both <?php echo "$total_today_count";?></a></li>
                  <li <?php if ($_GET[gather] == 'archived') { echo "class=\"active\""; }?>>
                   <a href="orders.php?gather=archived">Archived <?php echo "$archived_count";?></a></li>
                </ul>
              </div>
            </div>
            <div class="box-body no-padding">
             <form class="form-inline" role="form" method="GET" action="singlehwb.php">
              <div class="form-group" style="padding-left: 5px;">
               <label class="sr-only" for="manualHwb">Search for HWB</label>
               <input type="text" class="form-control" id="hwb" name="hwb" placeholder="Search for HWB">
              </div>
             <input type="submit" class="btn btn-primary" value="Search / Request"/>
             </form>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <form method="post" action="processshipments.php">
              <table class="table">
              <tr>
                <th style="width: 20px"><input type="checkbox" value="on" name="allbox" onclick="toggle(this)"/></th>
                <th>HWB#:Drivers</th>
                <th></th>
                <th>STS:PU/DEL</th>
                <th>PCS/WT</th>
              </tr>
              <form method=post action="./processshipments.php">
                <?php
                $deliveryQuery = "delAgentDriverPhone=(select driverid from users where username=\"$username\")";
                $pickupQuery = "puAgentDriverPhone=(select driverid from users where username=\"$username\")";
                # if we're an admin then get all the orders (so override the variables we just made above)
                if ($_SESSION['login'] == 1)
                {
                  $deliveryQuery = "1 = 1";
                  $pickupQuery   = "1 = 1";
                }
                $sql = "select hawbNumber
                        ,recordID
                        ,shipperName
                        ,consigneeName
                        ,status
                        ,hawbDate
                        ,dueDate
                        ,delAgentDriverName
                        ,puAgentDriverName
                        ,pieces
                        ,weight 
                        ,readyTime
						,closeTime
						,dueTime
                        from dispatch
                        where (";
                if ($_GET['gather'] == "del")
                {
                  $sql .= $deliveryQuery . ")";
                  $sql .= " AND str_to_date(dueDate,'%c/%e/%Y') = date(now())";
                }elseif ($_GET['gather'] == "pu")
                {
                  $sql .= $pickupQuery . ")";
                  $sql .= " AND str_to_date(hawbDate,'%c/%e/%Y') = date(now())";
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

                # Status: Red needs update(EF5350), Turquise (26A69A) Update not complete,
                $array = array(
               "Picked Up" => "#80CBC4",
               "Trailer Dropped" => "#80CBC4",
               "Trace Note PU" => "#FFB74D",
               "Trace Note DEL" => "#FFB74D",
               "Appointment Scheduled" => "#80CBC4",
               "On Dock TUS" => "#80CBC4",
               "Freight At Dock" => "#80CBC4",
               "On Dock PHX" => "#80CBC4",
               "In Transit" => "#80CBC4",
               "On Dock LAX" => "#80CBC4",
               "APPT. SCHEDULED" => "#80CBC4",
               "Delivered" => "#71f971",
               "Ready To Invoice" => "#71f971",
               "Verify Pickup Date" => "#90A4AE",
               "Hold For Routing" => "#90A4AE",
               "Verify Delivery Date" => "#90A4AE",
               "Recovered" => "#90A4AE",
               "In Storage" => "#90A4AE",
               "Outside Carrier Scheduled" => "#F8D0D8",
               "Verify Shipment Assignment" => "#F8D0D8",
               "Left Apt Message" => "#F8D0D8",
               "CALL FOR APPOINTMENT" => "#90A4AE",
               "Need Routing" => "#90A4AE",
               "Need 2 Sch Appt" => "#90A4AE",
               "On Hand Destination" => "#90A4AE",
               "ATTEMPT" => "#dd4b39",
               "Attempted Delivery" => "#dd4b39",
               "Unable to Locate" => "#dd4b39",
               "New Hwb" => "#dd4b39",
               "Booked" => "#dd4b39",
               "Attempted Pick Up" => "#dd4b39",
               "Refused" => "#dd4b39",
			   "Reject PU DEL" => "#dd4b39",
               "Out for Delivery" => "#dd4b39",
               "Terminate at Origin" => "#dd4b39",
               "Accepted PU" => "#FFB74D",
               "Accepted DEL" => "#FFB74D",
               "Arrived To Consignee" => "#FFB74D",
			   "Arrived to Consignee" => "#FFB74D",
               "Dispatched" => "#FFB74D",
               "Dispatched Confirmed" => "#FFB74D",
               "Arrived to Shipper" => "#FFB74D",
                );
                $counter = 0;
                while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                {

                ?>
                  <tr style="background-color:<?php echo $array[$row['status']]; ?>">
                    <td><input type="checkbox" name="chk_hawb[]" id="chk_hawb[]" value="<?php echo "$row[recordID]"; ?>" /></td>
                    <td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                     <span>
                      <input type="hidden" id="<?php echo "order[$counter][hawb]"; ?>" name="<?php echo "order[$counter][hawb]"; ?>" value="<?php echo "$row[hawbNumber]";?>" />
                     <a href="singlehwb.php?hwb=<?php echo "$row[hawbNumber]"; ?>&amp;recordid=<?php echo "$row[recordID]"; ?>"><?php echo "$row[hawbNumber]"; ?></a><br>
                      PU:<?php echo "$row[puAgentDriverName]";?><br>
                      <?php echo "$row[readyTime]";?>
                     </span>
                     <br>
                      DEL:<?php echo "$row[delAgentDriverName]";?><br>
                      <?php echo "$row[appTime]";?>
                     </span>
                    </td>
                    <td><td style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    <span><?php echo "$row[status]";?></span><br>
                    <span><?php echo "$row[shipperName]";?></span><br>
                    <span><?php echo "$row[closeTime]";?></span><br>
                    <span><?php echo "$row[consigneeName]";?></span><br>
                    <?php echo "$row[dueTime]";?></td>
<!-- Bootstrap 3.3.4
                    <td><span>PU<?php echo "$row[puAgentDriverName]";?></span><br>
                    <span>DEL: <?php echo "$row[delAgentDriverName]";?></span><br></td> -->
                    <td>
                     <span>
                     <?php echo "$row[pieces]"; ?> / <?php echo "$row[weight]"; ?>
                     <br>
                      <?php
                            switch($_GET['gather'])
                            {
                              case "pu":
							    echo $row['hawbDate'];
                                break;
                              case "del":
                                echo $row['dueDate'];
                                break;
                              default:
                                echo $row['hawbDate'] . " <br> " . $row['dueDate'];
                                break;
                            }
                       ?>
                     </span>
                     <br>
                    </td>
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
