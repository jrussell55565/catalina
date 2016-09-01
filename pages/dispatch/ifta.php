<?php
   session_start();
   
if ($_SESSION['login'] != 1)
{
        header('Location: /pages/dispatch/adminonly.php');
}

   
   include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
   mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
   mysql_select_db($db_name) or DIE('Database name is not available!');
   
   $username = $_SESSION['userid'];
   $drivername = $_SESSION['drivername'];
   $truckid = $_SESSION['truckid'];
   $trailerid = $_SESSION['trailerid'];
   
   $us_state_abbrevs = array('AL', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY');
   
   # Get the driver names and employee_id
   $driver_array = array();
   $statement = 'SELECT fname, lname, employee_id from users WHERE title = "Driver" ORDER BY fname';
   $results = mysql_query($statement);
   while($row = mysql_fetch_array($results, MYSQL_BOTH))
   {
       $driver_array[$row['employee_id']] = $row['fname']." ".$row['lname'];
   }
   mysql_free_result($results);

   $issue_options = array('NA', 'Date Missing', 'Driver Missing', 'HWB Missing', 'Route HWY Missing', 'State Ext Ent Missing', 'Miles Missing', 'Odometer Missing', 'Other');
   $fuel_issue_options = array('NA', 'Fuel Inv # Missing', 'Fuel Gallons Missing', 'Fuel Vendor Missing', 'Fuel City Missing', 'Fuel State Missing', 'Fuel Odometer Missing', 'Other');

   # Create associative array for the border states
   $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
   $state_border_array = array();
   $statement = "select state_extent,bs_01,bs_02,bs_03,bs_04,bs_05,bs_06,bs_07,bs_08,bs_09 from ifta_stateboarders";
   if ($result = $mysqli->query($statement)) {
     while($obj = $result->fetch_object()){ 
            $states = $obj->state_extent . "," .
                      $obj->bs_01 . "," .
                      $obj->bs_02 . "," .
                      $obj->bs_03 . "," .
                      $obj->bs_04 . "," .
                      $obj->bs_05 . "," .
                      $obj->bs_06 . "," .
                      $obj->bs_07 . "," .
                      $obj->bs_08 . "," .
                      $obj->bs_09
                      ;
             array_push($state_border_array, $states) ;
     }
   }
   /* free result set */
   $result->close();

   ?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <title>IFTA Trip Reports</title>
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
      <script>
        var odo_counter = 0;
        var fuel_counter = 0;
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
               <h1> Trip Reports / Enter New Trip</h1>
               <ol class="breadcrumb">
                  <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
                  <li class="active">Enter New Trip Report</li>
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
                           <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"><i class="fa fa-plus"></i></button>
                        </div>
                        <div style="width: 50%; text-align: center; margin:auto; display: none;" id="search_alert" class="alert alert-danger" role="alert">Include a beginning AND end date.</div>
                     </div>
                     <div class="box-body">
                        <form name="frm_ifta_search" method="GET" action="ifta.php" role="form">
                           <div class="table-responsive">
                              <table width="646" class="table table-condensed table-striped">
                                 <tr>
                                    <td width="111">Trip Number </td>
                                    <td width="444"><input name="trip_search_tripnum" type="text" class="input-sm form-control" id="search_tripnum" value="" size="10"></td>
                                    <td width="18">&nbsp;</td>
                                    <td width="53">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>HWB Number </td>
                                    <td><input name="trip_search_hwbnum" type="text" class="input-sm form-control" id="search_hwbnum" value="" size="10"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>Trip Starting </td>
                                    <td><input name="trip_search_startdate" type="text" class="input-sm form-control datepicker" id="trip_search_startdate" size="10" data-date-format="mm/dd/yyyy"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>Trip Ending</td>
                                    <td><input name="trip_search_enddate" type="text" class="input-sm form-control datepicker" id="trip_search_enddate" size="10" data-date-format="mm/dd/yyyy"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>Truck #</td>
                                    <td><input name="trip_search_trucknumber" type="text" class="input-sm form-control" id="trip_search_trucknumber" size="10"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>State Exit/Enter</td>
                                   <td><select class="input-sm form-control" name="txt_state_enter_details[]" id="txt_state_enter_details_<?php echo $random;?>" value="">
                                     <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                     <option><?php echo $state;?></option>
                                     <?php } ?>
                                   </select></td>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>Driver</td>
                                   <td><select class="input-sm form-control" name="trip_search_driver" id="trip_search_driver" value="">
                                     <option value="null">Choose Driver...</option>
                                     <?php
                                             foreach ($driver_array as $employee_id => $driver) { ?>
                                     <option value=<?php echo $employee_id;?>><?php echo $driver;?></option>
                                     <?php } ?>
                                   </select></td>
                                   <td>&nbsp;</td>
                                   <td>&nbsp;</td>
                                 </tr>                                 
                                 <tr>
                                    <td colspan="2">
                                       <button type="button" id="btn_display_results" name="btn_display_results" value="display" class="btn btn-primary dropdown-toggle">Display Results</button>
                                    </td>
                                 </tr>
                              </table>
                           </div>
                        </form>
                        <div id="search_results">
                        </div>
                     </div>
                     <!-- /.box-body -->
                     <div class="box-footer"></div>
                     <!-- /.box-footer-->
                  </div>
                  <!-- /.box -->
                  <!-- /.box-header -->
                  <div class="box-body no-padding">
                     <!-- PAGE CONTENT HERE -->
                     <!-- Default box -->
                     <div class="box">
                        <div class="box-header" style="text-align: center;">
                           <h3 class="box-title">Add IFTA Trip
                           </h3>
                           <?php
                            if (isset($_GET['error'])) {
                              echo "<br>";
                              echo '<div style="width: 50%; text-align: center; margin:auto" class="alert alert-danger" role="alert">Error adding record: ',urldecode($_GET['error']),'</div>';
                            }?>
                           <div class="box-tools pull-right">
                              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                           </div>
                        </div>
                        <div class="box-body">
                        <form name="frm_ifta_add" method="POST" action="processifta.php" role="form" enctype="multipart/form-data">
                           <table width="693" class="table table-condensed table-striped" id="tbl_ifta_add">
                              <tbody id="tbody_ifta_details">
                                 <tr>
                                    <td colspan="2"><div align="center"><strong>Trip Info</strong></div>
                                   <td colspan="2"><div align="center"><strong>OTR Trip Compliance </strong></div></td>
                                 </tr>
                                 <tr>
                                   <td width="57"><strong>Trip #</strong>
                                   <td width="231"><input class="input-sm form-control" name="txt_tripnum" type="text" id="txt_tripnum" value="" required>                                    
                                    <td width="152"><div align="right"><strong>Trip Compliance</strong></div></td>
                                    <td width="233"></label>
                                      <select class="input-sm form-control" name="compliance_trip" id="compliance_trip">
                                        <option>Incomplete</option>
                                        <option selected>Complete</option>
                                        <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Start Date</strong>
                                   <td><input class="input datepicker" name="txt_date_start" type="text" id="txt_date_start" value="" required>                                    
                                    <td><div align="right"><strong>Logs Included</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_logs" id="compliance_logs">
                                      <option>Incomplete</option>
                                      <option selected>Complete</option>
                                      <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>End Date
                                   </strong>
                                   <!-- Old Style Date Picker 
                                   <input class="input-sm form-control datepicker" name="txt_date_end" type="text" id="txt_date_end" value="" required>  -->                      
                                    <td><input class="input datepicker" name="txt_date_end" type="text" id="txt_date_end" value="" required>                                    
                                    <td><div align="right"><strong>VIR Included</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_vir" id="compliance_vir">
                                      <option>Incomplete</option>
                                      <option selected>Complete</option>
                                      <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Driver 1
                                   </strong>
                                   <td><select class="input-sm form-control" name="sel_add_driver_1" id="sel_add_driver_1" value="">
                                     <option value="null">Choose Driver...</option>
                                     <?php
                                             foreach ($driver_array as $employee_id => $driver) { ?>
                                     <option value=<?php echo $employee_id;?>><?php echo $driver;?></option>
                                     <?php } ?>
                                   </select>                                    
                                    <td><div align="right"><strong>Fuel Reciepts</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_fuel" id="compliance_fuel">
                                      <option>Incomplete</option>
                                      <option selected>Complete</option>
                                      <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Driver 2
                                   </strong>
                                   <td><select class="input-sm form-control" name="sel_add_driver_2" id="sel_add_driver_2" value="">
                                     <option value="null">Choose Driver...</option>
                                     <?php
                                             foreach ($driver_array as $employee_id => $driver) { ?>
                                     <option value=<?php echo $employee_id;?>><?php echo $driver;?></option>
                                     <?php } ?>
                                   </select>                                    
                                    <td><div align="right"><strong>BOL Included</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_bol" id="compliance_bol">
                                      <option>Incomplete</option>
                                      <option selected>Complete</option>
                                      <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Truck #
                                   </strong>
                                   <td><input class="input-sm form-control" name="txt_truckno" type="text" id="txt_truckno" value="" required>                                    
                                    <td><div align="right"><strong>Permits</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_permits" id="compliance_permits">
                                      <option>Incomplete</option>
                                      <option selected>Complete</option>
                                      <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Starting OD
                                   </strong>
                                   <td><input class="input-sm form-control" name="txt_od_start" type="text" id="txt_od_start" value="" required>                                    
                                   <td><div align="right"><strong>GPS Reports</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_gps" id="compliance_gps">
                                      <option>Incomplete</option>
                                      <option selected>Complete</option>
                                      <option>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Ending OD
                                   </strong>
                                   <td><input class="input-sm form-control" name="txt_od_end" type="text" id="txt_od_end" value="" required>                                    
                                   <td><div align="right"><strong>DOT Violations</strong></div></td>
                                    <td><select class="input-sm form-control" name="compliance_dot" id="compliance_dot">
                                      <option>Incomplete</option>
                                      <option>Complete</option>
                                      <option selected>NA</option>
                                    </select></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Total Miles
                                   </strong>
                                   <td><input class="input-sm form-control" name="txt_od_total" type="text" id="txt_od_total" value="">                                    
                                   <td><div align="right"><strong>Notes For Driver</strong></div></td>
                                   <td><input name="notes_trip_driver" type="text" class="input-sm form-control" id="notes_trip_driver" value="" data-toggle="tooltip" data-placement="top" title="These notes will populate as a General note about the Trip report if there are any issues that Driver needs to resolve."></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Starting City &amp; State
                                   </strong>
                                   <td><input class="input-sm form-control" name="location_start" type="text" id="location_start" value="" data-toggle="tooltip" data-placement="top" title="Enter the starting state and city. This will help the driver understand any alerts via email if problems with Trip." required>                                   
                                   <td><div align="right"><strong>Available Points for Trip</strong></div></td>
                                   <td><input class="input-sm form-control" name="points_trip" type="number" id="points_trip"></td>
                                 </tr>
                                 <tr>
                                   <td><strong> Stops Cities States 
                                   </strong>
                                   <td><input class="input-sm form-control" name="location_stops" type="text" id="location_stops" value="" data-toggle="tooltip" data-placement="top" title="Enter a few of the inbetween cities and states. This will help the driver understand any alerts via email if problems with Trip." required>                                   
                                   <td><div align="right"><strong>Available Points for Fuel</strong></div></td>
                                   <td><input class="input-sm form-control" name="points_fuel" type="number" id="points_fuel"></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Ending City &amp; State
                                   </strong>
                                   <td><input class="input-sm form-control" name="location_end" type="text" id="location_end" value="" data-toggle="tooltip" data-placement="top" title="Enter the ending state and city. This will help the driver understand any alerts via email if problems with Trip." required>                                   
                                   <td><div align="right"><strong>Available Points for Images</strong></div></td>
                                   <td><input class="input-sm form-control" name="points_images" type="number" id="points_images"></td>
                                 </tr>
                                 <tr>
                                   <td><strong>Internal Notes                                 
                                   </strong>
                                   <td><input class="input-sm form-control" name="notes_trip_internal" type="text" id="notes_trip_internal" value="" data-toggle="tooltip" data-placement="top" title="Internal notes does not distribute to anyone.">                                 
                                   <td><div align="right"><strong>Max available Points For Trip</strong></div></td>
                                   <td><input class="input-sm form-control" name="ifta_trip_points_available" type="text" id="ifta_trip_points_available" value="Sum the Available Points" data-toggle="tooltip" data-placement="top" title="3 different categories: Trip / Fuel / Images." readonly></td>
                                 </tr>
                                 <tr>
                                   <td>                                 
                                   <td>                                 
                                   <td><div align="right"><strong>Points Awarded For Trip</strong></div></td>
                                   <td><input class="input-sm form-control" name="ifta_trip_points_available5" type="text" id="ifta_trip_points_available5" value="Add Points based on Data Entry" data-toggle="tooltip" data-placement="top" title="Points Awarded / If team drivers total points will be awarded to both drivers. If Local Points will be assigned to specific drivers." readonly></td>
                                 </tr>
                           </table>
                          <p></p>
                           <table width="1130" class="table table-condensed table-striped" id="add_ifta_table">
                              <tr>
                                 <td colspan="15" style="text-align: center; font-weight: bold;">Enter Trip info Below</td>
                              </tr>
                              <tr>
                                 <td width="52">Trip #</td>
                                 <td width="42">Date</td>
                                 <td width="54">Driver</td>
                                 <td width="74">HWB</td>
                                 <td width="105">Routes Hwys</td>
                                 <td width="55">Exit</td>
                                 <td width="55">Ent</td>
                                 <td width="112">OD State Line</td>
                                 <td width="64">St Miles</td>
                                 <td width="49">Permit</td>
                                 <td width="34">Issue</td>
                                 <td width="53">Choose</td>
                                 <td width="9">Comments</td>
                                <td width="9">Resolved</td>
                                 <td width="41" style="text-align: right;"><button class="btn btn-xs btn-primary" type="button" name="txt_new_row_details[]" id="txt_new_row_details_0" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addOdoRow(this);"><span class="glyphicon glyphicon-plus"></span></button></td>
                              </tr>
                              <tr id="tr_add_driver_details_0">
                              </tr>
                              <td></tbody>
                           </table>
                <p></p>
                           <table width="805" class="table table-condensed table-striped" id="add_ifta_fuel">
                              <tbody>
                                 <tr style="text-align: center; font-weight: bold;">
                                    <td colspan="11">Enter Fuel Info for Trip</td>
                                 </tr>
                                 <tr>
                                    <td width="37">Trip #</td>
                                    <td width="29">Date</td>
                                    <td width="85">Truck Gallons</td>
                                    <td width="70">Reefer Fuel</td>
                                    <td width="65">Other Fuel</td>
                                    <td width="46">Vendor</td>
                                    <td width="25">City</td>
                                    <td width="31">State</td>
                                    <td width="62">Odometer</td>
                                    <td width="6">&nbsp;</td>
                                    <td width="97" style="text-align: right;"><button class="btn btn-xs btn-primary" type="button" name="txt_new_row_fuel[]" id="txt_new_row_fuel_0" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addFuelRow(this);"><span class="glyphicon glyphicon-plus"></span></button></td>
                                 </tr>
                                 <tr id="tr_add_fuel_details_0">
                                 </tr>
                              </tbody>
                           </table>
                       <p></p>
                           <table class="table table-condensed table-striped">
                            <tbody>
                              <tr>
                                 <td colspan="2" style="text-align: center; font-weight: bold;">Upload Trip Images</td>
                              </tr>
                              <tr>
                                 <td width="214">Image IFTA Trip Report</td>
                                 <td><input name="ifta_image_trip[]" type="file" class="file-loading input-sm form-control" id="ifta_image_trip" multiple=false>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_1" value="ifta_image_trip"></td>
                              </tr>
                              <tr>
                                 <td>Image IFTA Fuel Reciepts</td>
                                 <td><input name="ifta_image_fuel[]" type="file" class="file-loading input-sm form-control" id="ifta_image_fuel" multiple=false>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_2" value="ifta_image_fuel"></td>
                              </tr>
                              <tr>
                                 <td>Image IFTA GPS Data</td>
                                 <td><input name="ifta_image_gps[]" type="file" class="file-loading input-sm form-control" id="ifta_image_gps" multiple=false>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_3" value="ifta_image_gps"></td>
                              </tr>
                              <tr>
                                 <td>Image Individual Trip Permits</td>
                                 <td><input name="ifta_image_permits[]" type="file" class="file-loading input-sm form-control" id="ifta_image_permits" multiple=false>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_4" value="ifta_image_permits"></td>
                              </tr>
                              <tr>
                                 <td>Image Driver Logs (for current trip)</td>
                                 <td><input name="ifta_image_drivers_logs[]" type="file" class="file-loading input-sm form-control" id="ifta_image_drivers_logs" multiple=false>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_5" value="ifta_image_drivers_logs"></td>
                              </tr>
                              <tr>
                                 <td>Image BOL (for current trip)</td>
                                 <td><input name="ifta_image_bol[]" type="file" class="file-loading input-sm form-control" id="ifta_image_bol" multiple=false>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_6" value="ifta_image_bol"></td>
                              </tr>
                              <tr>
                                <td>Comments</td>
                                <td><span style="width: 5em;">
                                  <input class="input-sm form-control input name="issue_comment2" type="text" id="issue_comment2" size="50">
                                </span></td>
                              </tr>
                              <tr>
                                <td>Choose Issue</td>
                                <td><span style="width: 5em;">
                                  <select name="sl_trip_detail_issue2" id="sl_trip_detail_issue2">
                                    <option selected>NA</option>
                                    <option>BOL</option>
                                    <option>Fuel</option>
                                    <option>Logs</option>
                                    <option>VIR</option>
                                    <option>Other</option>
                                  </select>
                                </span></td>
                              </tr>
                              <tr>
                                <td>Resolved (All Items Must be in)</td>
                                <td><span style="width: 5em;">
                                  <input class="input datepicker" name="txt_date_details_<?php echo $random;?>2" type="text" id="txt_date_details_<?php echo $random;?>3" value="" size="16">
                                </span></td>
                              </tr>
                            </tbody>
                           </table>
                        <p></p>
                        <button type="submit" class="btn btn-danger" name="add_ifta">Submit</button>
                        <span class="box-title">Open
                        <input name="radio" type="radio" id="trip_status" value="open" checked>
                        <label for="trip_status"></label>
Closed
<input type="radio" name="radio" id="trip_status" value="closed">
                        </span>
                        Create Task For Driver 
                        <input type="checkbox" name="cb_ifta_create_task" id="cb_ifta_create_task">
                        <label for="cb_ifta_create_task"></label>
                        </form>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer"></div>
                        <!-- /.box-footer-->
                     </div>
                     <!-- /.box -->
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
      <!-- Are You Sure? -->
      <script src="<?php echo HTTP;?>/dist/js/jquery.are-you-sure.js"></script>
      <script>
         function addOdoRow() {
         odo_counter = odo_counter + 1;
         var random = odo_counter;
         var tripnum = $("#txt_tripnum").val();

         var driver_list = [];
         driver_list[0] = {
                                 "id" : $("#sel_add_driver_1").val(),
                                 "name": $("#sel_add_driver_1 option:selected").text()
                               };
         driver_list[1] = {
                                 "id" : $("#sel_add_driver_2").val(),
                                 "name": $("#sel_add_driver_2 option:selected").text()
                               };

         var new_row = `<tr id="tr_add_driver_details_`+random+`">
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_tripnum_details[]" type="text" id="txt_tripnum_details_`+random+`" value="`+tripnum+`" readonly>
                                    <input type="hidden" name="hdn_details_id[]" id="hdn_details_id_`+random+`" value="1"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_date_details[]" type="text" id="txt_date_details_`+random+`" value="" size=""></td>
                                 <td>
                                  <select class="input-sm form-control" name="txt_driver_details[]" type="text" id="txt_driver_details_`+random+`" value="">
                                   <option value="null">Choose...</option>
                                  </select>
                                 </td>
                                 <td style="width: 5em;"><input class="input-sm form-control hwb" name="txt_hwb_details[]" type="text" id="txt_hwb_details_`+random+`"></td>
                                 <td><input class="input-sm form-control" name="txt_routes_details[]" type="text" id="txt_routes_details_`+random+`"></td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_exit_details[]" id="txt_state_exit_details_`+random+`" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_enter_details[]" id="txt_state_enter_details_`+random+`" value="">
                                       <option></option>
                                    </select>
                                 </td>
                                 <td><input class="input-sm form-control" name="txt_state_odo_details[]" type="number" id="txt_state_odo_details_`+random+`"></td>
                                 <td>
                                    <input class="input-sm form-control" name="txt_state_miles_details[]" type="number" id="txt_state_miles_details_`+random+`" value="">
                                 </td>
                                 <td><input class="input-sm" type="checkbox" name="txt_permit_req_details[]" id="txt_permit_req_details_`+random+`"></td>
                                 <td><input class="input-sm" type="checkbox" name="cb_trip_issue_details[]" id="cb_trip_issue_details_`+random+`"></td>
                                    <td>
                                   <select class="input-sm form-control" name="sl_trip_issue_details[]" id="sl_trip_issue_details_`+random+`">
                                     <?php
                                       foreach ($issue_options as $issue) { ?>
                                     <option> <?php echo $issue;?></option>
                                     <?php } ?>
                                   </select>
                                    </td>
                                   <td><div align="center">
                                   <input name="issue_comment_details[]" type="text" class="input-sm form-control" id="issue_comment_details_`+random+`" size="30">
                                 </div></td>
                                 <td><input class="input-sm form-control datepicker" name="date_resolved_details[]" type="text" id="date_resolved_details_`+random+`" size="16"></td>
                                 <td style="text-align: right;"><button class="btn btn-sm btn-primary" type="button" name="txt_new_row_details[]" id="txt_new_row_details_0" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addOdoRow(this);"><span class="glyphicon glyphicon-plus"></span></button></td>
                                 <td style="text-align: right;">
                                    <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_details[]" id="txt_delete_row_details_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                 </td>
                              </tr>`;
         
         // Append a new tr to the table based on the 'new_row' variables         
         $("#add_ifta_table > tbody:last-child").append(new_row);

         // Set the hwb to the value of the hwb on the previous row
         var prev_hwb = $("#tr_add_driver_details_"+random).prev().children('td').eq(3).children(':text').val();
         $("#txt_hwb_details_"+random).val(prev_hwb);


         // Set the value of txt_state_enter_details select box to states that surround the txt_state_exit_details select box
         <?php
           echo "var states = {\n";
           foreach ($state_border_array as $state) {
             $state_array = array();
             $state_array = explode(",", $state);
             echo "        '$state_array[0]' : [";
             for($i = 1; $i <= 8; $i++) {
               if ($state_array[$i] != '') {
                 echo "'" . $state_array[$i] . "'";
               }
               if (empty($state_array[$i])) { continue; }
               echo ",";
             }
             echo "],\n";
           }
           echo "        };\n";
         ?>

         var prev_st_enter = $("#tr_add_driver_details_"+random).prev().children('td').eq(6).find('option:selected').text();

         // Set the Exit state to the value of the "enter" state of the previous line
         if (prev_st_enter) {
             $("#txt_state_exit_details_"+random+" option:contains(" + prev_st_enter + ")").attr('selected', 'selected');
         }

         $("#txt_state_enter_details_"+random)
         .find('option')
         .remove()
         .end()

         // Only set the state_enter values if we're NOT the first row
         if (random == 1) {
           $("#txt_state_enter_details_"+random)
           <?php
           foreach ($us_state_abbrevs as $state) {
             ?>
             .append('<option><?php echo $state;?></option>')
             <?php
           }?>
         }else{
           var primary_state = $("#txt_state_exit_details_"+random).children("option").filter(":selected").text();
           for (var i = 0; i <= states[primary_state].length - 1; i++) {
               //console.log(states[primary_state][i]);
               $("#txt_state_enter_details_"+random)
               .append('<option>'+states[primary_state][i]+'</option>')
           }
         }

         // Append Driver 1 to the select box
         $("#txt_driver_details_"+random)
         .find('option')
         .remove()
         .end()
         .append('<option value="'+driver_list[0].id+'">'+driver_list[0].name+'</option>')
         .val(driver_list[0].name)

         // If we chose a driver 2 then we'll append that
         if (driver_list[1].id != 'null') {
           $("#txt_driver_details_"+random)
           .append('<option value="'+driver_list[1].id+'">'+driver_list[1].name+'</option>')
           .val(driver_list[1].name);
         }

         // Make the first driver the selected driver
         $("#txt_driver_details_"+random+" option[value="+driver_list[0].id+"]").prop('selected', true);

         }
         
         function addFuelRow(id) {
         fuel_counter = fuel_counter + 1;
         var random = fuel_counter;
         var tripnum = $("#txt_tripnum").val();
         var new_row = `<tr id="tr_add_fuel_details_`+random+`">
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_tripnum[]" type="text" id="txt_fuel_tripnum_`+random+`" value="`+tripnum+`" readonly>
                                    <input type="hidden" name="hdn_fuel_id[]" id="hdn_fuel_id_`+random+`" value="1"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_fuel_date[]" type="text" id="txt_fuel_date_`+random+`" value="" size=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_gallons[]" type="text" id="txt_fuel_gallons_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_reefer[]" type="text" id="txt_fuel_reefer_`+random+`" value=""></td>
                                    <td style="width: 10em;"><input class="input-sm form-control" name="txt_fuel_other[]" type="text" id="txt_fuel_other_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_vendor[]" type="text" id="txt_fuel_vendor_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_city[]" type="text" id="txt_fuel_city_`+random+`" value=""></td>
                                    <td>
                                       <select class="input-sm form-control" name="txt_fuel_state[]" id="txt_fuel_state_`+random+`" value="<?php echo $row['st_enter'];?>">
                                          <?php
                                             foreach ($us_state_abbrevs as $state) { ?>
                                          <option><?php echo $state;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_odo[]" type="text" id="txt_fuel_odo_`+random+`" value=""></td>
                                    <td style="text-align: right;"><button class="btn btn-sm btn-primary" type="button" name="txt_new_row_fuel[]" id="txt_new_row_fuel_0" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addFuelRow(this);"><span class="glyphicon glyphicon-plus"></span></button></td>
                                    <td style="text-align: right;">
                                       <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_fuel[]" id="txt_delete_row_fuel_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                 </tr>`;

         $("#add_ifta_fuel > tbody:last-child").append(new_row);
         }
      </script>

<script>
$(document).ready(function(){
  // Ajax calls for search
  $("#btn_display_results").click(function() {
    // If no trip number was specified then make sure we enter a date range
    if ($("#search_tripnum").val().length < 1) {
      if (($("#trip_search_startdate").val().length < 1)
        || ($("#trip_search_enddate").val().length < 1)) {
       $("#search_alert").show();
       return false;
      }
    }
    function searchResults(callBack) {
      $.ajax({
       method: "GET",
       url: "searchifta.php",
       data: {
              trip_no: $("#search_tripnum").val(),
              hwb_no: $("#search_hwbnum").val(),
              trip_start: $("#trip_search_startdate").val(),
              trip_end: $("#trip_search_enddate").val(),
              trip_state: $("#trip_search_state").val(),
              trip_truck_no: $("#trip_search_trucknumber").val(),
			  trip_state_exit: $("#trip_search_state_exit").val(),
			  trip_state_enter: $("#trip_search_state_enter").val(),
              trip_driver: $("#trip_search_driver").val()
            },
       success: function(data, textStatus, xhr) {
            //$('.rtnMsg').html(data);
            myRtnA = "Success"
            return callBack( myRtnA, data );  // return callBack() with myRtna
        },
        error: function(xhr, textStatus, errorThrown) {
            //$('.rtnMsg').html("opps: " + textStatus + " : " + errorThrown);
            myRtnA = "Error"
            return callBack ( myRtnA, errorThrown ); // return callBack() with myRtna
        }
      });
    }

    searchResults(function(myRtn, data) {
          if (myRtn == "Success") {
            //resetForm($('#myForm'));
            //resetForm($('form[name=addChlGrp]'));
            console.log(data);
            var output = `<table id="tbl_search_results" class="table">
                            <tr>
                              <td>Trip Number</td>
                              <td>HWB Number</td>
                              <td>Odometer, start</td>
                              <td>Odometer, end</td>
                              <td>Status</td>
                              <td>Truck</td>
                              <td>Trip Start</td>
                              <td>Trip End</td>
                              <td>Driver Name 1</td>
                              <td>Driver Name 2</td>
                              <td>Total Trip Miles</td>
							  <td>State Exit</td>
							  <td>State Enter</td>
                           </tr>`;


            var json = jQuery.parseJSON( data );
            for(var i = 0; i < json.length; i++) {
              var obj = json[i];
               output = output + `<tr>
                              <td><a href="<?php echo HTTP;?>/pages/dispatch/updateifta.php?trip_no=`+obj.trip_no+`" target="_blank">`+obj.trip_no+`</a></td>
                              <td>`+obj.hwb_no+`</td>
                              <td>`+obj.odo_start+`</td>
                              <td>`+obj.odo_end+`</td>
                              <td>Open</td>
                              <td>`+obj.truck_no+`</td>
                              <td>`+obj.trip_start+`</td>
                              <td>`+obj.trip_end+`</td>
                              <td>`+obj.driver1+`</td>
                              <td>`+obj.driver2+`</td>
                              <td>`+obj.trip_miles+`</td>
							  <td>`+obj.trip_state_exit+`</td>
							  <td>`+obj.trip_state_enter+`</td>
                           </tr>`;
            }
            output = output + '</table>';
            $("#search_results").html(output);
          } else {
            console.log(data);
            //$('.rtnMsg').html("Opps! Ajax Error");
          }
        });
  });


  // Are You Sure form validator
  $('form').areYouSure();

  // Date Picker
  $('body').on('focus',".datepicker", function(){
    $(this).datepicker({
         startDate: "2015-01-01",
         todayBtn: "linked",
         autoclose: true,
         datesDisabled: '0',
         todayHighlight: true,
    });
  });
         
  // Set the value of total trip odo to the sum of start and end odo.
  $("#txt_od_end").change(function() {
    var total_odometer = parseInt($("#txt_od_end").val()) - parseInt($("#txt_od_start").val());
    $("#txt_od_total").val(total_odometer);
  });
  $("#txt_tripnum").change(function() {
    $("#txt_tripnum_details_0").val($("#txt_tripnum").val());
    $("#txt_fuel_tripnum_0").val($("#txt_tripnum").val());
  });

  // Ajax call for the truck #.  We'll use this to get the last ODO entered for the truck
  var final_odometer = 0;
  var delay = (function(){
    var timer = 0;
    return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
    };
  })(); 
  $("#txt_truckno").keyup(function() {
    delay(function(){
      $.ajax({
       method: "GET",
       url: "searchifta.php",
       data: {
              search_type: "truck_odo",
              truck_no: $("#txt_truckno").val(),
            },
       success: function(data, textStatus, xhr) {
            var json = jQuery.parseJSON( data );
            $("#txt_od_start").val(json[0]["odo_end"]);
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log("error getting the odo reading for the truck")
        }
      });
    }, 1000 );
  });

});

function deleteRow(z) {
  v_id = $("#"+z.id).parent().parent().get( 0 ).id;
  v_table_id = $("#"+z.id).parent().parent().parent().parent().get( 0 ).id;
  // Get the size of the table.  If it's > 1 then we'll allow a row to be deleted
  // (don't want to delete all the rows)
  if ($("#"+v_table_id+" tr").length > 3) {
    $('#'+v_id).remove();
  }
}

</script>
   </body>
</html>
