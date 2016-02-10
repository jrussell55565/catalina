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

print_r($_GET);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IFTA Trip Reports</title>
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

</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>IFTA Trip Reports</h1>
        <ol class="breadcrumb">
          <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">IFTA TRIP Reports</li>
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
          <div class="box box-primary collapsed-box">
            <div class="box-header" style="text-align: center;">
              <h3 class="box-title"> Search IFTA Trips </h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Open"><i class="fa fa-plus"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="frm_ifta" method="GET" action="ifta.php" role="form">
               <div class="table-responsive">
                <table class="table table-condensed">
                  <tr>
                    <td>Trip Number </td>
                    <td><input class="input-sm form-control" name="trip_search_tripnum" type="text" id="search_tripnum" value=""></td>
                  </tr>
                  <tr>
                    <td>Trip Starting </td>
                    <td><input type="text" class="input-sm form-control datepicker" name="trip_search_startdate" id="trip_search_startdate" data-date-format="mm/dd/yyyy"></td>
                  </tr>
                  <tr>
                    <td>Trip Ending</td>
                    <td><input type="text" class="input-sm form-control datepicker" name="trip_search_enddate" id="trip_search_enddate" data-date-format="mm/dd/yyyy"></td>
                  </tr>
                  <tr>
                    <td>State Enter / State Exit</td>
                    <td><select class="input-sm form-control" name="trip_search_state" id="trip_search_state" value="">
                      <option>Choose State...</option>
                      <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                      <option><?php echo $state;?></option>
                      <?php } ?>
                    </select></td>
                  </tr>
                  <tr>
                    <td>Permit Required</td>
                    <td><input class="input-sm form-control" type="checkbox" name="trip_search_permit_req" id="trip_search_permit_req"></td>
                  </tr>
                  <tr>
                    <td>Truck #</td>
                    <td><input class="input-sm form-control" name="trip_search_trucknumber" type="text" id="trip_search_trucknumber" value="Truck Number"></td>
                  </tr>
                  <tr>
                    <td>Driver</td>
                    <td><input class="input-sm form-control" name="trip_search_driver" type="text" id="trip_search_driver" value="Driver"></td>
                  </tr>
                  <tr>
                    <td>
        <button type="submit" id="btn_display_results" name="btn_display_results" class="btn btn-default dropdown-toggle">Display Results</button>
        <button type="submit" id="btn_export_results" name="btn_export_results" class="btn btn-default dropdown-toggle">Export Results</button>
                    </td>
                  </tr>
                </table>
                </div>
              </form>
                <table width="1142" border="1">
                  <tr>
                    <td height="23" colspan="11">Display Results</td>
                  </tr>
                  <tr>
                    <td width="112" height="23">Trip Number</td>
                    <td width="53">Status</td>
                    <td width="49">Truck</td>
                    <td width="82">Trip Start</td>
                    <td width="85">Trip End</td>
                    <td width="100">Driver Name 1</td>
                    <td width="91">Driver Name 2</td>
                    <td width="72">State Exit</td>
                    <td width="72"><p>State Enter</p></td>
                    <td width="116">Total Trip Miles</td>
                    <td width="240">Want to use Click Load or Button??</td>
                  </tr>
                  <tr>
                    <td><a href="#">12345(clickload)</a></td>
                    <td>Open</td>
                    <td>1111</td>
                    <td>1/20/2015</td>
                    <td>1/28/2015</td>
                    <td>Jason Shumsky</td>
                    <td>Jaime Russell</td>
                    <td>AZ</td>
                    <td>NV</td>
                    <td>2345</td>
                    <td><input type="submit" name="Add6" id="Add2" value="Load Trip"></td>
                  </tr>
                  <tr>
                    <td height="28"><a href="#">12346</a></td>
                    <td>Finalized</td>
                    <td>1112</td>
                    <td>1/21/2015</td>
                    <td>1/29/2015</td>
                    <td>Jason Shumsky</td>
                    <td>none</td>
                    <td>NV</td>
                    <td>AZ</td>
                    <td>3255</td>
                    <td><input type="submit" name="Add7" id="Add3" value="Load Trip"></td>
                  </tr>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer"></div>
            <!-- /.box-footer-->
          </div><!-- /.box -->









            <!-- /.box-header -->
            <div class="box-body no-padding">


            <!-- PAGE CONTENT HERE -->

          <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Edit IFTA Trip (insert Trip PHP)
                </h3><div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="frm_ifta" method="post" action="processifta.php">
                <table width="1096" border="1">
                  <tr>
                    <td colspan="10">Enter Trip Info Below </td>
                  </tr>
                  <tr>
                    <td width="77" height="25">Trip #
                    <td width="77"> Start
                      Date
                    <td width="77">End
                      Date                   
                    <td width="97">Driver                    
                      1
                    <td width="97">Driver 2
                    <td width="65">Truck #
                    <td width="97">Starting OD
                    <td width="97">Ending OD
                    <td width="104">Total Trip Miles                    
                  <td width="244">Options /                    Finalize:
                    <input type="checkbox" name="finalize" id="finalize">
Open
<input name="unfinalize" type="checkbox" id="unfinalize" checked>                  
                  </tr>
                  <tr>
                    <td><input name="tripnum" type="text" id="tripnum" value="man enter or echo" size="12"></td>
                    <td><input name="trip_startdate3" type="text" id="trip_startdate3" value="man enter or echo" size="12"></td>
                    <td><input name="trip_enddate3" type="text" id="trip_enddate3" value="man enter or echo" size="12"></td>
                    <td><input name="trip_driver1" type="text" id="trip_driver5" value="man enter or echo" size="15"></td>
                    <td><input name="trip_driver2" type="text" id="trip_driver6" value="man enter or echo" size="15"></td>
                    <td><input name="trip_trucknumber" type="text" id="trip_trucknumber" value="man enter or echo" size="10"></td>
                    <td><input name="trip_startod" type="text" id="trip_startod" value="man enter or echo" size="15"></td>
                    <td><input name="trip_endod" type="text" id="trip_endod" value="man enter or echo" size="15"></td>
                    <td><input name="trip_totalmiles" type="text" id="trip_totalmiles" value="DB calc Sub Start OD from End OD" size="15"></td>
                    <td><input type="submit" name="Add4" id="Add10" value="Delete">
                      <input type="submit" name="Add3" id="Add9" value="Save">
                    <input type="submit" name="Add8" id="Add6" value="New Trip"></td>
                  </tr>
                </table>
                <table width="1258" height="120" border="1">
                  <tr>
                    <td colspan="11">Enter Trip info Below</td>
                  </tr>
                  <tr>
                    <td width="60" height="46">Trip #</td>
                    <td width="60">Date </td>
                    <td width="90">Driver </td>
                    <td width="90">HWB </td>
                    <td width="120">Routes Hwys</td>
                    <td width="41">Exit</td>
                    <td width="41"> Ent</td>
                    <td width="90"> OD  State Line</td>
                    <td width="72">State Miles</td>
                    <td width="100">Permit Req</td>
                    <td width="762">Options</td>
                  </tr>
                  <tr>
                    <td height="28"><label for="tripnum3"></label>
                    <input name="tripnum" type="text" id="tripnum" value="echo trip" size="10"></td>
                    <td><input name="trip_startdate" type="text" id="trip_startdate" value="Date" size="10"></td>
                    <td><input name="trip_driver2" type="text" id="trip_driver" value="Choose from Current Trip # drop down only available drivers" size="15"></td>
                    <td><label for="trip_hwb"></label>
                    <input name="trip_hwb" type="text" id="trip_hwb" size="15"></td>
                    <td><label for="trip_routes"></label>
                    <input name="trip_routes" type="text" id="trip_routes" size="20"></td>
                    <td><select name="trip_st_exit[]" id="trip_st_exit[]" value="<?php echo $row['st_exit'];?>">
                      <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                      <option <?php if ($row['st_exit'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                      <?php } ?>
                    </select></td>
                    <td><select name="trip_st_enter[]" id="trip_st_enter[]" value="<?php echo $row['st_enter'];?>">
                      <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                      <option <?php if ($row['st_enter'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                      <?php } ?>
                    </select></td>
                    <td><label for="trip_enter_state_od"></label>
                    <input name="trip_enter_state_od" type="text" id="trip_enter_state_od" size="15"></td>
                    <td><label for="trip_state_miles"></label>
                    
                    <!-- Calculate State Miles From DB Entries
                    No miles will be calculated unless 1 entry is input, null if no entry.
                    1st Entry of "Current Trip" Look at Odometer at State Line,
                    Lets say OD State Line entered is 1000.  Starting State AZ.
                    Lets say Starting OD entered on this trip is 500.
                    So At the State Line, User entered 1000.  Starting od is 500.
                    So 1000 minus 500 = 500.  That is to populate in the state miles.
                    
                    Now for line 2 that gets entered.  
                    
                    
                    
                     -->
                    
                    <input name="trip_state_miles" type="text" id="trip_state_miles" value="DB calc See notes:" size="12"></td>
                    
                    
                    
                    
                    <td><input type="checkbox" name="trip_permit_req" id="trip_permit_req">
                    <label for="trip_permit_req"></label></td>
                    <td><label for="ifta_image_type">
                      <input type="submit" name="Add5" id="Add7" value="Update Row">
                      <input type="submit" name="Add" id="Add4" value="Add Row">
                      <input type="submit" name="Add10" id="Add" value="Delete Row">
                    </label></td>
                  </tr>
                </table>
                <table width="1257" border="1">
                  <tr>
                    <td colspan="10">Enter Fuel Info for Trip</td>
                  </tr>
                  <tr>
                    <td width="92">Trip #</td>
                    <td width="61">Fuel Date</td>
                    <td width="101">Truck Gallons</td>
                    <td width="76">Reefer Fuel</td>
                    <td width="90">Other Fuel</td>
                    <td width="49">Vendor</td>
                    <td width="63">City</td>
                    <td width="55">State</td>
                    <td width="142">Odometer</td>
                    <td width="559">Options</td>
                  </tr>
                  <tr>
                    <td><input name="tripnum4" type="text" id="tripnum4" value="echo trip" size="12"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><select name="st_enter[]3" id="st_enter[]3" value="<?php echo $row['st_enter'];?>">
                      <?php
                     foreach ($us_state_abbrevs as $state) { ?>
                      <option <?php if ($row['st_enter'] == "$state") { echo "selected"; }?>><?php echo $state;?></option>
                      <?php } ?>
                    </select></td>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="Add2" id="Add5" value="Update Row">
                      <input type="submit" name="Add2" id="Add8" value="Add Row"></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
                <table width="676" border="1">
                  <tr>
                    <td colspan="3">Upload Trip Images Trip #1111</td>
                  </tr>
                  <tr>
                    <td width="236">Image IFTA Trip Report</td>
                    <td width="211"><input name="ifta_image_trip" type="file" class="file-loading" id="ifta_image_trip" size="10" multiple=true></td>
                    <td width="211"><input type="submit" name="Add9" id="Add12" value="Update Image">
                    <input type="submit" name="Add11" id="Add11" value="Delete Image"></td>
                  </tr>
                  <tr>
                    <td>Image IFTA Fuel Reciepts</td>
                    <td><input name="ifta_image_fuel" type="file" class="file-loading" id="ifta_image_fuel" size="10" multiple=true></td>
                    <td><input type="submit" name="Add13" id="Add13" value="Update Image">
                    <input type="submit" name="Add12" id="Add14" value="Delete Image"></td>
                  </tr>
                  <tr>
                    <td>Image IFTA GPS Data</td>
                    <td><input name="ifta_image_gps" type="file" class="file-loading" id="ifta_image_gps" size="10" multiple=true></td>
                    <td><input type="submit" name="Add14" id="Add15" value="Update Image">
                    <input type="submit" name="Add14" id="Add16" value="Delete Image"></td>
                  </tr>
                  <tr>
                    <td>Image Individual Trip Permits</td>
                    <td><input name="ifta_image_permits" type="file" class="file-loading" id="ifta_image_permits" size="10" multiple=true></td>
                    <td><input type="submit" name="Add15" id="Add17" value="Update Image">
                    <input type="submit" name="Add15" id="Add18" value="Delete Image"></td>
                  </tr>
                  <tr>
                    <td>Image Driver Logs (for current trip)</td>
                    <td><input name="ifta_image_drivers_logs" type="file" class="file-loading" id="ifta_image_drivers_logs" size="10" multiple=true></td>
                    <td><input type="submit" name="Add16" id="Add19" value="Update Image">
                    <input type="submit" name="Add16" id="Add20" value="Delete Image"></td>
                  </tr>
                  <tr>
                    <td>Image BOL (for current trip)</td>
                    <td><input name="ifta_image_bol" type="file" class="file-loading" id="ifta_image_bol" size="10" multiple=true></td>
                    <td><input type="submit" name="Add17" id="Add21" value="Update Image">
                    <input type="submit" name="Add17" id="Add22" value="Delete Image"></td>
                  </tr>
                </table>
              </form>
            </div>
            <!-- /.box-body -->
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
</body>
</html>
