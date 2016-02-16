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
   
   # Get the driver names and employee_id
   $driver_array = array();
   $statement = 'SELECT fname, lname, employee_id from users WHERE title = "Driver" ORDER BY fname';
   $results = mysql_query($statement);
   while($row = mysql_fetch_array($results, MYSQL_BOTH))
   {
       $driver_array[$row['employee_id']] = $row['fname']." ".$row['lname'];
   }
   mysql_free_result($results);
   
   if (isset($_GET['btn_display_results']))
   {
       # Create our SQL based on the inputs
       $statement = "SELECT * from ifta WHERE 1=1 ";
       switch ($_GET['btn_display_results'])
       {
           case "display":
               break;
           case "export":
               break;
       }
   }
   
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
                        <form name="frm_ifta_search" method="GET" action="ifta.php" role="form">
                           <div class="table-responsive">
                              <table class="table table-condensed table-striped">
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
                                    <td>
                                       <select class="input-sm form-control" name="trip_search_state" id="trip_search_state" value="">
                                          <option>Choose State...</option>
                                          <?php
                                             foreach ($us_state_abbrevs as $state) { ?>
                                          <option><?php echo $state;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
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
                                       <button type="submit" id="btn_display_results" name="btn_display_results" value="display" class="btn btn-default dropdown-toggle">Display Results</button>
                                       <button type="submit" id="btn_export_results" name="btn_display_results" value="export" class="btn btn-default dropdown-toggle">Export Results</button>
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
                              <td width="72">
                                 <p>State Enter</p>
                              </td>
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
                           <div class="box-tools pull-right">
                              <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                           </div>
                        </div>
                        <div class="box-body">
                        <form name="frm_ifta_add" method="POST" action="processifta.php" role="form">
                           <table id="tbl_ifta_add" class="table table-condensed table-striped">
                              <tbody>
                                 <tr>
                                    <td>Trip #
                                    <td><input class="input-sm form-control" name="txt_tripnum" type="text" id="txt_tripnum" value=""></td>
                                 </tr>
                                 <tr>
                                    <td>Start Date
                                    <td><input class="input-sm form-control datepicker" name="txt_date_start" type="text" id="txt_date_start" value=""></td>
                                 </tr>
                                 <tr>
                                    <td>End Date
                                    <td><input class="input-sm form-control datepicker" name="txt_date_end" type="text" id="txt_date_end" value=""></td>
                                 </tr>
                                 <tr>
                                    <td>Driver 1
                                    <td>
                                       <select class="input-sm form-control" name="sel_add_driver_1" id="sel_add_driver_1" value="">
                                          <option value="null">Choose Driver...</option>
                                          <?php
                                             foreach ($driver_array as $employee_id => $driver) { ?>
                                          <option value=<?php echo $employee_id;?>><?php echo $driver;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Driver 2
                                    <td>
                                       <select class="input-sm form-control" name="sel_add_driver_2" id="sel_add_driver_2" value="">
                                          <option value="null">Choose Driver...</option>
                                          <?php
                                             foreach ($driver_array as $employee_id => $driver) { ?>
                                          <option value=<?php echo $employee_id;?>><?php echo $driver;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Truck #
                                    <td><input class="input-sm form-control" name="txt_truckno" type="text" id="txt_truckno" value=""></td>
                                 </tr>
                                 <tr>
                                    <td>Starting OD
                                    <td><input class="input-sm form-control" name="txt_od_start" type="text" id="txt_od_start" value=""></td>
                                 </tr>
                                 <tr>
                                    <td>Ending OD
                                    <td><input class="input-sm form-control" name="txt_od_end" type="text" id="txt_od_end" value=""></td>
                                 </tr>
                                 <tr>
                                    <td>Total Trip Miles
                                    <td><input class="input-sm form-control" name="txt_od_total" type="text" id="txt_od_total" value=""></td>
                                 </tr>
                           </table>
                           <p></p>
                           <table id="add_ifta_table" class="table table-condensed table-striped">
                              <tr>
                                 <td colspan="11" style="text-align: center; font-weight: bold;">Enter Trip info Below</td>
                              </tr>
                              <tr>
                                 <td>Trip #</td>
                                 <td>Date</td>
                                 <td>Driver</td>
                                 <td>HWB</td>
                                 <td>Routes Hwys</td>
                                 <td>Exit</td>
                                 <td>Ent</td>
                                 <td>OD State Line</td>
                                 <td>State Miles</td>
                                 <td>Permit Req</td>
                                 <td>Options</td>
                              </tr>
                              <tr id="tr_add_driver_details_1">
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_tripnum_details[]" type="text" id="txt_tripnum_details_1" value="" readonly>
                                    <input type="hidden" name="hdn_details_id[]" id="hdn_details_id_`+random+`" value="1"></td>
                                 <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_date_details[]" type="text" id="txt_date_details_1" value="" size=""></td>
                                 <td>
                                  <select class="input-sm form-control" name="txt_driver_details[]" type="text" id="txt_driver_details_1" onFocus="create_driver_sel(this);" value="">
                                   <option value="null">Choose...</option>
                                  </select>
                                 </td>
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_hwb_details[]" type="text" id="txt_hwb_details_1"></td>
                                 <td><input class="input-sm form-control" name="txt_routes_details[]" type="text" id="txt_routes_details_1"></td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_exit_details[]" id="txt_state_exit_details_1" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_enter_details[]" id="txt_state_exit_details_1" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td><input class="input-sm form-control" name="txt_state_odo_details[]" type="text" id="txt_state_odo_details_1"></td>
                                 <td>
                                    <input class="input-sm form-control" name="txt_state_miles_details[]" type="text" id="txt_state_miles_details_1" value="">
                                 </td>
                                 <td><input class="input-sm" type="checkbox" name="txt_permit_req_details[]" id="txt_permit_req_details_1"></td>
                                 <td>
                                    <button class="btn btn-primary" type="button" name="txt_new_row_details[]" id="txt_new_row_details_1" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addOdoRow(this);"><span class="glyphicon glyphicon-plus"></span></button>
                                    <button class="btn btn-danger" type="button" name="txt_delete_row_details[]" id="txt_delete_row_details_1" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                 </td>
                              </tr>
                              </tbody>
                           </table>
                           <p></p>
                           <table id="add_ifta_fuel" class="table table-condensed table-striped">
                              <tbody>
                                 <tr style="text-align: center; font-weight: bold;">
                                    <td colspan="11">Enter Fuel Info for Trip</td>
                                 </tr>
                                 <tr>
                                    <td>Trip #</td>
                                    <td>Date</td>
                                    <td>Truck Gallons</td>
                                    <td>Reefer Fuel</td>
                                    <td>Other Fuel</td>
                                    <td>Vendor</td>
                                    <td>City</td>
                                    <td>State</td>
                                    <td>Odometer</td>
                                    <td>Options</td>
                                 </tr>
                                 <tr id="tr_add_fuel_details_1">
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_tripnum[]" type="text" id="txt_fuel_tripnum_1" value="" readonly>
                                    <input type="hidden" name="hdn_fuel_id[]" id="hdn_fuel_id_1" value="1"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_fuel_date[]" type="text" id="txt_fuel_date_1" value="" size=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_gallons[]" type="text" id="txt_fuel_gallons_1" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_reefer[]" type="text" id="txt_fuel_reefer_1" value=""></td>
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_other[]" type="text" id="txt_fuel_other_1" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_vendor[]" type="text" id="txt_fuel_vendor_1" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_city[]" type="text" id="txt_fuel_city_1" value=""></td>
                                    <td>
                                       <select class="input-sm form-control" name="txt_fuel_state[]" id="txt_fuel_state_1" value="<?php echo $row['st_enter'];?>">
                                          <?php
                                             foreach ($us_state_abbrevs as $state) { ?>
                                          <option><?php echo $state;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                    <td><input class="input-sm form-control" name="txt_fuel_odo[]" type="text" id="txt_fuel_odo_1" value=""></td>
                                    <td>
                                       <button class="btn btn-primary" type="button" name="txt_new_row_fuel[]" id="txt_new_row_fuel_1" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addFuelRow(this);"><span class="glyphicon glyphicon-plus"></span></button>
                                       <button class="btn btn-danger" type="button" name="txt_delete_row_fuel[]" id="txt_delete_row_fuel_1" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <p></p>
                           <table class="table table-condensed table-striped">
                            <tbody>
                              <tr>
                                 <td colspan="3" style="text-align: center; font-weight: bold;">Upload Trip Images Trip #1111</td>
                              </tr>
                              <tr>
                                 <td>Image IFTA Trip Report</td>
                                 <td><input name="ifta_image_trip" type="file" class="file-loading input-sm form-control" id="ifta_image_trip" multiple=true></td>
                                 <td><input type="" name="Add9" id="Add12" value="Update Image">
                                    <input type="" name="Add11" id="Add11" value="Delete Image">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image IFTA Fuel Reciepts</td>
                                 <td><input name="ifta_image_fuel" type="file" class="file-loading input-sm form-control" id="ifta_image_fuel" multiple=true></td>
                                 <td><input type="" name="Add13" id="Add13" value="Update Image">
                                    <input type="" name="Add12" id="Add14" value="Delete Image">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image IFTA GPS Data</td>
                                 <td><input name="ifta_image_gps" type="file" class="file-loading input-sm form-control" id="ifta_image_gps" multiple=true></td>
                                 <td><input type="" name="Add14" id="Add15" value="Update Image">
                                    <input type="" name="Add14" id="Add16" value="Delete Image">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image Individual Trip Permits</td>
                                 <td><input name="ifta_image_permits" type="file" class="file-loading input-sm form-control" id="ifta_image_permits" multiple=true></td>
                                 <td><input type="" name="Add15" id="Add17" value="Update Image">
                                    <input type="" name="Add15" id="Add18" value="Delete Image">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image Driver Logs (for current trip)</td>
                                 <td><input name="ifta_image_drivers_logs" type="file" class="file-loading input-sm form-control" id="ifta_image_drivers_logs" multiple=true></td>
                                 <td><input type="" name="Add16" id="Add19" value="Update Image">
                                    <input type="" name="Add16" id="Add20" value="Delete Image">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image BOL (for current trip)</td>
                                 <td><input name="ifta_image_bol" type="file" class="file-loading input-sm form-control" id="ifta_image_bol" multiple=true></td>
                                 <td><input type="" name="Add17" id="Add21" value="Update Image">
                                    <input type="" name="Add17" id="Add22" value="Delete Image">
                                 </td>
                              </tr>
                            </tbody>
                           </table>
                        <p></p>
                        <button type="submit" class="btn btn-danger">Submit</button>
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
      <script>
         function addOdoRow() {
         var random = Math.ceil(Math.random() * 10000);
         var tripnum = $("#txt_tripnum").val();
         var new_row = `<tr id="tr_add_driver_details_`+random+`">
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_tripnum_details[]" type="text" id="txt_tripnum_details_`+random+`" value="`+tripnum+`" readonly>
                                    <input type="hidden" name="hdn_details_id[]" id="hdn_details_id_`+random+`" value="1"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_date_details[]" type="text" id="txt_date_details_`+random+`" value="" size=""></td>
                                 <td>
                                  <select class="input-sm form-control" name="txt_driver_details[]" type="text" id="txt_driver_details_`+random+`" onFocus="create_driver_sel(this);" value="">
                                   <option value="null">Choose...</option>
                                  </select>
                                 </td>
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_hwb_details[]" type="text" id="txt_hwb_details_`+random+`"></td>
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
                                    <select class="input-sm form-control" name="txt_state_enter_details[]" id="txt_state_exit_details_`+random+`" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td><input class="input-sm form-control" name="txt_state_odo_details[]" type="text" id="txt_state_odo_details_`+random+`"></td>
                                 <td>
                                    <input class="input-sm form-control" name="txt_state_miles_details[]" type="text" id="txt_state_miles_details_`+random+`" value="">
                                 </td>
                                 <td><input class="input-sm" type="checkbox" name="txt_permit_req_details[]" id="txt_permit_req_details_`+random+`"></td>
                                 <td>
                                    <button class="btn btn-primary" type="button" name="txt_new_row_details[]" id="txt_new_row_details_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addOdoRow(this);"><span class="glyphicon glyphicon-plus"></span></button>
                                    <button class="btn btn-danger" type="button" name="txt_delete_row_details[]" id="txt_delete_row_details_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                 </td>
                              </tr>`;
                  
         $("#add_ifta_table > tbody:last-child").append(new_row);
         }
         
         function addFuelRow(id) {
         var random = Math.ceil(Math.random() * 1000);
         var tripnum = $("#txt_tripnum").val();
         var new_row = `<tr id="tr_add_fuel_details_`+random+`">
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_tripnum[]" type="text" id="txt_fuel_tripnum_`+random+`" value="`+tripnum+`" readonly>
                                    <input type="hidden" name="hdn_fuel_id[]" id="hdn_fuel_id_`+random+`" value="1"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_fuel_date[]" type="text" id="txt_fuel_date_`+random+`" value="" size=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_gallons[]" type="text" id="txt_fuel_gallons_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_reefer[]" type="text" id="txt_fuel_reefer_`+random+`" value=""></td>
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_other[]" type="text" id="txt_fuel_other_`+random+`" value=""></td>
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
                                    <td><input class="input-sm form-control" name="txt_fuel_odo[]" type="text" id="txt_fuel_odo_`+random+`" value=""></td>
                                    <td>
                                       <button class="btn btn-primary" type="button" name="txt_new_row_fuel[]" id="txt_new_row_fuel_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addFuelRow(this);"><span class="glyphicon glyphicon-plus"></span></button>
                                       <button class="btn btn-danger" type="button" name="txt_delete_row_fuel[]" id="txt_delete_row_fuel_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                 </tr>`;

         $("#add_ifta_fuel > tbody:last-child").append(new_row);
         }
      </script>
<script>
$(document).ready(function(){
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
    $("#txt_tripnum_details_1").val($("#txt_tripnum").val());
    $("#txt_fuel_tripnum_1").val($("#txt_tripnum").val());
  });
});

function create_driver_sel(v_id) {
  var driver_list = [];
    driver_list[0] = {
                                 "id" : $("#sel_add_driver_1").val(),
                                 "name": $("#sel_add_driver_1 option:selected").text()
                               };
    driver_list[1] = {
                                 "id" : $("#sel_add_driver_2").val(),
                                 "name": $("#sel_add_driver_2 option:selected").text()
                               };

    $("#"+v_id.id)
      .find('option')
      .remove()
      .end()
      .append('<option value="'+driver_list[0].id+'">'+driver_list[0].name+'</option>')
      .val(driver_list[0].name)
      .append('<option value="'+driver_list[1].id+'">'+driver_list[1].name+'</option>')
      .val(driver_list[1].name);
}

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
