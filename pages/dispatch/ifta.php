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
$truckid = $_SESSION['truckid'];
$trailerid = $_SESSION['trailerid'];

$us_state_abbrevs = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY');


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

</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>IFTA</h1>
        <ol class="breadcrumb">
          <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">Vehicle Inspection Report</li>
        </ol>
      </section>
      
      <!-- Main content -->
      <section class="content">
      <div class="row">
        <div class="col-md-8">
      <!-- Extra Pages...
          <div class="box">
            <div class="box-header with-border">
              <h4 class="box-title"></h4>
              <div class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">

                  <li>
                    <a href="orders.php?gather=pu">Page1</a></li>
                  <li>
                   <a href="orders.php?gather=del">Page2</a></li>
                   -->
                </ul>
        </div>
</div>
            <!-- /.box-header -->
            <div class="box-body no-padding">


            <!-- PAGE CONTENT HERE -->

          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">IFTA Trip Report</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="frm_ifta_previous" method="get" action="processifta.php">
                    <input type="submit" name="load_ifta" id="load_ifta" value="Load Previous Trip" />
              </form>
              <form name="frm_ifta" method="post" action="processifta.php">
                <table width="690" border="1">
                  <tr>
                    <td colspan="4">Please fill out trip report below to match the hand written Fuel Report</td>
                  </tr>
                  <tr>
                    <td >Tractor
                      <input name="truck_number" type="text" id="truck_number" value="<?php echo $_SESSION['truckid']; ?>" size="10" readonly="readonly" />
                    </td>
<td>
Trailer
<input name="trailer_number" type="text" id="trailer_number" value="<?php echo $_SESSION['trailerid']; ?>" readonly /></td>
<input name="truck_rental" type="hidden" id="trailer_rental" value="<?php echo $_SESSION['truckrental']; ?>" readonly /></td>
</td>
                    <td width="388">Trip# 
                    <input name="trip_num" type="text" id="trip_num" value="<?php echo $_GET['tripno'];?>" size="12" required></td>
                    <td width="388">Trip Start Odometer
                    <?php
                     $sql = "select start_odometer from ifta where trip_num = '$_GET[tripno]' LIMIT 1";
                     $startOdometer = mysql_result(mysql_query($sql),0);
                    ?>
                    <input name="start_odometer" type="text" id="start_odometer" value="<?php echo $startOdometer;?>" size="12" 
                     <?php if ($startOdometer != '') { echo 'readonly'; }else{ echo  'required'; }?>></td>
                  </tr>
                  <tr>
                    <td colspan="4">                    Enter Trip Details Below </td>
                  </tr>
                </table>
  <table width="1012" height="136" border="1">
                  <tr>
                    <td width="62" height="25">
                    <td width="91">
                    <td width="107">
                    <td width="167">
                    <td colspan="2">
                    Starting OD
                    <td width="48">
                    <td width="85">
        <td>
        </tr>
                  <tr>
                    <td height="42" >Date </td>
                    <td >Driver</td>
                    <td >HWB</td>
                    <td >Routes Hwys</td>
                    <td width="48" >State Exit
                    </td>
                    <td width="64" >State Enter
                    </td>
                    <td >Enter OD Reading at state line</td>
                    <td ><p>Total Miles</p></td>
                    <td >Status:</td>
                  </tr>
                  <?php
                  if (isset($_GET['tripno']))
                  {
                    $sql = "SELECT id,trip_num,truck_number,trailer_number,truck_rental,drivername,
                            COALESCE(start_odometer,0) as start_odometer,
                            date_trip,hwb,route,st_exit,st_enter,state_line_odometer,trip_sts,end_odometer
                            FROM ifta WHERE trip_num = '$_GET[tripno]' order by id ASC";
                    $sql = mysql_query($sql); 
                  }
                  $index = 0;
                  while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                  {
                  ?> 
                  <tr>
                    <td height="28" >
                     <input name="date_trip[]" type="text" id="date_trip[]" value="<?php echo (isset($row['date_trip'])? date("m/d/Y", strtotime($row['date_trip'])) : $localdateYear) ;?>" size="8"/>
                     <input name="id_trip[]" type="hidden" id="id_trip[]" value="<?php echo $row['id'];?>">
                    </td>
                    <td ><?php echo "$drivername"; ?></td>
                    <td ><label for="iftahwb"></label>
                    <input name="hwb[]" type="text" id="hwb[]" size="15" value="<?php echo $row['hwb'];?>" required></td>
                    <td ><input name="route[]" type="text" id="route[]" size="30" value="<?php echo $row['route'];?>" required></td>
                    <td >
                    <select name="st_exit[]" id="st_exit[]" value="<?php echo $row['st_exit'];?>">
                    <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                       <option <?php if ($row['st_exit'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                     <?php } ?>
                    </select>
                    </td>
                    <td >
                    <select name="st_enter[]" id="st_enter[]" value="<?php echo $row['st_enter'];?>">
                    <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                       <option <?php if ($row['st_enter'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                     <?php } ?>
                    </select>
                    </td>
                    <td >
                     <input name="state_line_odometer[]" type="number" id="state_line_odometer[]" size="15" value="<?php echo $row['state_line_odometer'];?>" required>
                    </td>
                    <?php
                     # If this is the first record then find the difference
                     # between state_line_odometer - start_odometer
                     if ($index == 0)
                     {
                       $totalMiles = $row['state_line_odometer'] - $row['start_odometer'];
                     }
                       $index++;
                    ?>
                    <td ><input name="total_miles[]" type="hidden" id="total_miles[]" size="10" value="<?php echo $totalMiles;?>" readonly></td>
                    <td ><input name="trip_sts[]" type="text" id="trip_sts[]" size="12" value="<?php echo $row['trip_sts'];?>" readonly></td>
                  </tr>
                <?php
                }
                ?>
                 <tr>
                    <td height="28" >
                     <input name="date_trip[]" type="text" id="date_trip[]" value="<?php echo $localdateYear ;?>" size="8"/>
                     <input name="id_trip[]" type="hidden" id="id_trip[]" value="">
                    </td>
                    <td ><?php echo "$drivername"; ?></td>
                    <td ><label for="iftahwb"></label>
                    <input name="hwb[]" type="text" id="hwb[]" size="15" value="" required></td>
                    <td ><input name="route[]" type="text" id="route[]" size="30" value="" required></td>
                    <td >
                    <select name="st_exit[]" id="st_exit[]" value="">
                    <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                       <option><?php echo $state;?></option>
                     <?php } ?>
                    </select>
                    </td>
                    <td >
                    <select name="st_enter[]" id="st_enter[]">
                    <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                       <option><?php echo $state;?></option>
                     <?php } ?>
                    </select>
                    </td>
                    <td ><input name="state_line_odometer[]" type="number" id="state_line_odometer[]" size="15" value="" required></td>
                    <td ><input name="total_miles[]" type="number" id="total_miles[]" size="10" value="" readonly></td>
                    <td ><input name="trip_sts[]" type="text" id="trip_sts[]" size="12" value="" readonly></td>
                  </tr>
                  <tr>
                    <td height="28" >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >Ending Odometer
                    <input name="end_odometer[]" type="number" id="end_odometer[]" size="15" value="<?php echo $row['end_odometer'];?>"></td>
                    <td >TMFT
                    <input name="total_trip_miles[]" type="number" id="total_trip_miles[]" size="10" value="<?php echo $row['total_trip_miles'];?>"></td>
                    <td >&nbsp;</td>
                  </tr>

              </table>
              <table width="799" border="1">
                <tr>
                  <td colspan="4">Fuel  Reporting</td>
                  <td colspan="3"><img src="images/fuelclipart.gif" width="60" height="48"></td>
                </tr>
                <tr>
                  <td colspan="7">Enter Fuel Details Below
                    <a href="loadtrip.php">View Past Fuel</a></td>
                </tr>
                <tr>
                  <td width="72"><p>Date</p></td>
                  <td width="101">Ticket Inv #</td>
                  <td>Fuel Type</td>
                  <td>Gallons</td>
                  <td>State</td>
                  <td>Current Truck Miles</td>
                  <td width="202">Total $ Reciept</td>
                </tr>
                  <?php
                  if (isset($_GET['tripno']))
                  {
                    $sql = "SELECT id,date_fuel,fuel_invoice_no,fuel_type,fuel_gallons,fuel_st,
                            fuel_odometer,fuel_receipt_total
                            FROM ifta WHERE trip_num = '$_GET[tripno]' order by id ASC";
                    $sql = mysql_query($sql);
                  while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                  {
                  ?> 
                <tr>
                  <td>
                   <input name="date_fuel[]" type="text" id="date_fuel[]" value="<?php echo (isset($row['date_fuel'])? date("m/d/Y", strtotime($row['date_fuel'])) : $localdateYear) ;?>" size="8"/>
                   <input name="id_fuel[]" type="hidden" id="id_fuel[]" value="<?php echo $row['id'];?>">
                  </td>
                  <td><label for="fuel_invoice"></label>
                  <input type="text" name="fuel_invoice_no[]" id="fuel_invoice_no[]" value="<?php echo $row['fuel_invoice_no'];?>"></td>
                  <td><select name="fuel_type[]" id="fuel_type[]">
                    <option>FuelType</option>
                    <option <?php if ($row['fuel_type'] == "diesel") { echo "selected"; }?>>diesel</option>
                    <option <?php if ($row['fuel_type'] == "unlead") { echo "selected"; }?>>unlead</option>
                    <option <?php if ($row['fuel_type'] == "biodiesel") { echo "selected"; }?>>biodiesel</option>
                    <option <?php if ($row['fuel_type'] == "refer") { echo "selected"; }?>>refer</option>
                  </select></td>
                  <td><input name="fuel_gallons[]" type="number" id="fuel_gallons[]" size="8" value="<?php echo $row['fuel_gallons'];?>"></td>
                  <td><select name="fuel_st[]" id="fuel_st[]" value="<?php echo $row['fuel_st'];?>">
                    <option>State</option>
                     <?php foreach ($us_state_abbrevs as $state) {?>
                       <option <?php if ($row['fuel_st'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                     <?php } ?>
                  </select></td>
                  <td><input name="fuel_odometer[]" type="text" id="fuel_odometer[]" size="15" value="<?php echo $row['fuel_odometer'];?>"></td>
                  <td><input name="fuel_receipt_total[]" type="text" id="fuel_receipt_total[]" size="15" value="<?php echo $row['fuel_receipt_total'];?>"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <?php
                }
               }
                ?>
               <tr>
                  <td>
                   <input name="date_fuel[]" type="text" id="date_fuel[]" value="<?php echo $localdateYear ;?>" size="8"/>
                   <input name="id_fuel[]" type="hidden" id="id_fuel[]" value="">
                  </td>
                  <td><label for="fuel_invoice"></label>
                  <input type="text" name="fuel_invoice_no[]" id="fuel_invoice_no[]" value=""></td>
                  <td><select name="fuel_type[]" id="fuel_type[]">
                    <option>FuelType</option>
                    <option>diesel</option>
                    <option>unlead</option>
                    <option>biodiesel</option>
                    <option>refer</option>
                  </select></td>
                  <td><input name="fuel_gallons[]" type="number" id="fuel_gallons[]" size="8" value=""></td>
                  <td><select name="fuel_st[]" id="fuel_st[]" value="">
                    <option>State</option>
                     <?php foreach ($us_state_abbrevs as $state) {?>
                       <option><?php echo $state;?></option>
                     <?php } ?>
                  </select></td>
                  <td><input name="fuel_odometer[]" type="text" id="fuel_odometer[]" size="15" value=""></td>
                  <td><input name="fuel_receipt_total[]" type="text" id="fuel_receipt_total[]" size="15" value=""></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>

              </table>
              <table width="647" border="1">
                <tr>
                  <td colspan="3">Permit  &amp; Tolls Reporting</td>
                  <td width="120"><img src="images/permit.gif" width="105" height="48"></td>
                  <td><img src="images/toll.gif" width="149" height="53"></td>
                </tr>
                <tr>
                  <td width="89">Date</td>
                  <td width="142">Permit Type</td>
                  <td width="78">State</td>
                  <td>Permit #</td>
                  <td>Total Receipt</td>
                </tr>
                  <?php
                  if (isset($_GET['tripno']))
                  {
                    $sql = "SELECT id,date_permit,permit_type,permit_st,permit_no,permit_receipt
                            FROM ifta WHERE trip_num = '$_GET[tripno]' order by id ASC";
                    $sql = mysql_query($sql);
                  while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                  {
                  ?> 
                <tr>
                  <td>
                   <input name="date_permit[]" type="text" id="date_permit[]" value="<?php echo (isset($row['date_permit'])? date("m/d/Y", strtotime($row['date_permit'])) : $localdateYear) ;?>" size="8"/>
                   <input name="id_permit[]" type="hidden" id="id_permit[]" value="<?php echo $row['id'];?>">
                  </td>
                  <td><select name="permit_type[]" id="permit_type[]">
                    <option <?php if ($row['permit_type'] == "no permit") { echo "selected"; }?>>no permit</option>
                    <option <?php if ($row['permit_type'] == "annual") { echo "selected"; }?>>annual</option>
                    <option <?php if ($row['permit_type'] == "1 time") { echo "selected"; }?>>1 time</option>
                    <option <?php if ($row['permit_type'] == "oversized") { echo "selected"; }?>>oversized</option>
                    <option <?php if ($row['permit_type'] == "overweight") { echo "selected"; }?>>overweight</option>
                  </select></td>
                  <td>
                   <select name="permit_st[]" id="permit_st[]">
                    <option>State</option>
                    <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                       <option <?php if ($row['permit_st'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                     <?php } ?>
                  </select></td>
                  <td><input name="permit_no[]" type="text" id="permit_no[]" size="15" value="<?php echo $row['permit_no'];?>"></td>
                  <td><input name="permit_receipt[]" type="text" id="permit_receipt[]" size="15" value="<?php echo $row['permit_receipt'];?>"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
               </tr>
                <?php
                }
               }
                ?>
                <tr>
                  <td>
                   <input name="date_permit[]" type="text" id="date_permit[]" value="<?php echo $localdateYear ;?>" size="8"/>
                   <input name="id_permit[]" type="hidden" id="id_permit[]" value="">
                  </td>
                  <td><select name="permit_type[]" id="permit_type[]">
                    <option>no permit</option>
                    <option>annual</option>
                    <option>1 time</option>
                    <option>oversized</option>
                    <option>overweight</option>
                  </select></td>
                  <td>
                   <select name="permit_st[]" id="permit_st[]">
                    <option>State</option>
                    <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                       <option><?php echo $state;?></option>
                     <?php } ?>
                  </select></td>
                  <td><input name="permit_no[]" type="text" id="permit_no[]" size="15" value=""></td>
                  <td><input name="permit_receipt[]" type="text" id="permit_receipt[]" size="15" value=""></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
               </tr>

              </table>
              <p>&nbsp;</p>
              <table width="794" border="1">
                  <tr>
                    <td>
                    <input type="submit" name="btn_submit_all" id="btn_submit_all" value="Update All" />
                    Finalize:
                    <input type="checkbox" name="finalize" id="finalize">
                    </td>
                </tr>
      </table>
      </form>
                <p>&nbsp;</p>
            </div><!-- /.box-body -->
            <div class="box-footer"></div>
            <!-- /.box-footer-->
          </div><!-- /.box -->






    









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
</body>
</html>
