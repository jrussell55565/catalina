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

?>

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
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Admin Temp Page delete later<small></small></h1>
            
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
          <!-- Shipment Boards -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-cog fa-spin"></i></span>
                </a>               
                <div class="info-box-content">
                <span class="info-box-text"><a href="/pages/dispatch/orders.php">View  / EDIT / Export All DISPATCHES</a></span><span class="info-box-number">  Todays  PU:  <?php echo "$pu_today_count";?><br>
                    Todays DEL:   <?php echo "$del_today_count";?></span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->



            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/dispatch/vir.php" class="button animated rubberBand">
<style>
 a.button {
	 -webkit-animation-duration: 5s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
                <span class="info-box-icon bg-red"><i class="fa fa-wrench faa-wrench animated"></i></span>
</a>               
                <div class="info-box-content">
                  <span class="info-box-text"><a href="/pages/dispatch/vir.php">View / EDIT / EXPORT DRIVERS VIRs</a></span>
                  <span class="info-box-number">                  Total VIR's Reported: ?/PHP</span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/fuel.php" class="button2 animated zoomIn">
<style>
 a.button2 {
	 -webkit-animation-duration: 6s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>              
                <span class="info-box-icon bg-green"><i class="fa fa-tachometer"></i></span>
                </a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/vir.php">VIEW / EDIT / EXPORT FUEL LOGS</a></span>
                  <span class="info-box-number">                  Total Fuel Reported: ?/PHP  </span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->



            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-yellow"><i class="fa fa-newspaper-o"></i></span>
</a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/vir.php">VIEW / EDIT / EXPORT IFTA Reports</a></span>
                  <span class="info-box-number">                  Total IFTA Reports: ?/PHP </span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            



            
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-newspaper-o"></i></span>
</a>

                <div class="info-box-content">
                  <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/vir.php">VIEW / EDIT / EXPORT DOT Saftey Report</a></span>
                  <span class="info-box-number">                  Import Reports Here</span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            
            

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-newspaper-o"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/adminusers.php">VIEW / Edit / EXPORT  VIR</a></span>
                  <span class="info-box-number">                  PU &amp; DEL </span>
                </div>
                <!-- /.info-box-content -->

              </div><!-- /.info-box -->
            </div><!-- /.col -->


            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="../../../../linux8080/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-newspaper-o"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="../../../../linux8080/pages/dispatch/adminusers.php">VIEW / EDIT / eXPORT HWB Accessorials</a></span><span class="info-box-number">PU &amp; DEL</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            
            


            
            
            

<div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="/pages/dispatch/adminusers.php" class="button4 animated bounce">
<style>
 a.button4 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="/pages/dispatch/adminusers.php">VIEW / EDIT / EXPORT New Users</a></span>
                  <span class="info-box-number">View all User Profiles                  </span>
                </div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.info-box -->
          </div><!-- /.row -->
          
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">User Administration: Temp Page</h3>
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
                  <table width="878" border="1" id="Users">
                    <tr>
                      <td width="72">Driver Name</td>
                      <td width="72">Login</td>
                      <td width="72">Pass</td>
                      <td>Email</td>
                      <td>AltE</td>
                      <td width="120">V Text</td>
                      <td width="34">AltV</td>
                      <td width="144">Driver ID</td>
                      <td width="51">Admin</td>
                      <td width="38">Add</td>
                      <td width="281">Delete</td>
                    </tr>
                    <?php
		$result = mysql_query("SELECT * FROM users ORDER BY drivername");
		$counter = 0;
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
		{
			echo "<form id=\"usermanagement\" name=\"usermanagement\" method=\"post\" action=\"useractions.php\">\n";
			echo "<tr>\n";
			echo "<td><label>\n";
			echo "<input name=\"driver_name\" type=\"text\" id=\"driver_name\" value=\"$row[drivername]\"/>\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_login\" type=\"text\" id=\"driver_login\" value=\"$row[username]\" />\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_password\" type=\"text\" id=\"driver_password\" value=\"$row[password]\" />\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_email\" type=\"text\" id=\"driver_email\" size=\"40\" value=\"$row[email]\" />\n";
			echo "<td><label>\n";
			echo "<input type=\"checkbox\" name=\"ck_emailupdate\" id=\"ck_emailupdate\" "; 
			if ($row[emailupdate] == "1")
			{		
				echo "checked />\n";
			}else{
				echo "/>\n";
			}
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_vtext\" type=\"text\" id=\"driver_vtext\" size=\"40\" value=\"$row[vtext]\" />\n";
			echo "<td><label>\n";
			echo "<input type=\"checkbox\" name=\"ck_vtextupdate\" id=\"ck_vtextupdate\" "; 
			if ($row[vtextupdate] == "1")
			{		
				echo "checked />\n";
			}else{
				echo "/>\n";
			}
			echo "</label></td>\n";
			echo "<td><label>\n";
			echo "<input name=\"driver_ID\" type=\"text\" id=\"driver_ID\" value=\"$row[driverid]\" />\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_admin\" type=\"checkbox\" id=\"driver_admin\" ";
			// Get admin checkbox status
			if ($row[admin] == "1")
			{		
				echo "checked />\n";
			}else{
				echo "/>\n";
			}
			echo "</label></td>\n";

			echo "<td>\n";
			echo "<input name=\"btn_submit\" value=\"Update\" type=\"submit\" id=\"btn_submit\" />\n";
			echo "<td><label>\n";
			echo "<input name=\"btn_submit\" value=\"Delete\" type=\"submit\" id=\"btn_submit\" />\n";
			echo "</td>\n";
			
			$counter++;
			echo "</form>\n";
			echo "</tr>\n";
			
		}
		?>
                    <form id="usermanagement_add" name="usermanagement_add" method="post" action="useractions.php">
                      <tr>
                        <td><label>
                          <input name="driver_name" type="text" id="driver_name" size="12" />
                        </label></td>
                        <td><label>
                          <input name="driver_login" type="text" id="driver_login" size="12"  />
                        </label></td>
                        <td><label>
                          <input name="driver_password" type="text" id="driver_password" size="12"/>
                        </label></td>
                        <td width="120"><label>
                          <input name="driver_email" type="text" id="driver_email" size="20" />
                        </label></td>
                        <td width="33"><input type="checkbox" name="ck_emailupdate" id="ck_emailupdate" /></td>
                        <td><label>
                          <input name="driver_vtext" type="text" id="driver_vtext" size="20" />
                        </label></td>
                        <td><input type="checkbox" name="ck_vtextupdate" id="ck_vtextupdate" /></td>
                        <td><label>
                          <input name="driver_ID" type="text" id="driver_ID" size="20"  />
                        </label></td>
                        <td><label>
                          <input name="driver_admin" type="checkbox" id="driver_admin" />
                        </label></td>
                        <td><input name="btn_submit" value="Add" type="submit" id="btn_submit" /></td>
                        <td>&nbsp;</td>
                    </form>
  </tr>
  
                  </table>
                  <div class="row">
<div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                        <h5 class="description-header">4210</h5>
                        <span class="description-text">Total Updates</span>
                      </div><!-- /.description-block -->
              </div><!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                        <h5 class="description-header">8390</h5>
                        <span class="description-text">TOTAL Dispached</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block border-right">
                        <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                        <h5 class="description-header">50.01%</h5>
                        <span class="description-text">TOTAL %</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-3 col-xs-6">
                      <div class="description-block">
                        <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                        <h5 class="description-header">1200</h5>
                        <span class="description-text">GOAL COMPLETIONS</span>
                      </div><!-- /.description-block -->
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
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/footer.php');?>

<!-- Control Sidebar -->
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/r_sidebar.php');?>
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
