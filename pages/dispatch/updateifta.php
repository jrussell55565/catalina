<?php
   session_start();
   
   if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
   {
           header('Location: /pages/login/driverlogin.php');
   }
   
   include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
   
   $username = $_SESSION['userid'];
   $drivername = $_SESSION['drivername'];
   $truckid = $_SESSION['truckid'];
   $trailerid = $_SESSION['trailerid'];
   
   $us_state_abbrevs = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY');
   
   // Get the info from the DB if this is a GET req.
   if (isset($_GET['trip_no'])) {
     $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
     /* check connection */
     if ($mysqli->connect_errno) {
       printf("Connect failed: %s\n", $mysqli->connect_error);
       exit();
     }
     $ifta_results = array();
     $query = "SELECT * FROM ifta WHERE trip_no = '".$_GET['trip_no']."'";
     if ($result = $mysqli->query($query)) {
         while($obj = $result->fetch_object()){ 
           $ifta_results['trip_no'] = $obj->trip_no; 
           $ifta_results['date_started'] = $obj->date_started; 
           $ifta_results['date_ended'] = $obj->date_ended; 
           $ifta_results['driver1'] = $obj->driver1; 
           $ifta_results['driver2'] = $obj->driver2; 
           $ifta_results['truck_no'] = $obj->truck_no; 
         } 

       /* free result set */
       $result->close();
     }
     $mysqli->close();
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
                     <div class="box">
                        <div class="box-header" style="text-align: center;">
                           <h3 class="box-title">IFTA Trip
                           </h3>
                           <div class="box-tools pull-right">
                              <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                           </div>
                        </div>
                        <div class="box-body">
                        <form name="frm_ifta_add" method="POST" action="processifta.php" role="form" enctype="multipart/form-data">
                           <table id="tbl_ifta_add" class="table table-condensed table-striped">
                              <tbody>
                                 <tr>
                                    <td>Trip #
                                    <td><input class="input-sm form-control" name="txt_tripnum" type="text" id="txt_tripnum" value="<?php echo $ifta_results['trip_no'];?>" required></td>
                                 </tr>
                                 <tr>
                                    <td>Start Date
                                    <td><input class="input-sm form-control datepicker" name="txt_date_start" type="text" id="txt_date_start" value="<?php echo $ifta_results['date_started'];?>" required></td>
                                 </tr>
                                 <tr>
                                    <td>End Date
                                    <td><input class="input-sm form-control datepicker" name="txt_date_end" type="text" id="txt_date_end" value="<?php echo $ifta_results['date_ended'];?>" required></td>
                                 </tr>
                                 <tr>
                                    <td>Driver 1
                                    <td>
                                       <select class="input-sm form-control" name="sel_add_driver_1" id="sel_add_driver_1" value="">
                                          <option value="null">Choose Driver...</option>
                                          <?php
                                             foreach ($driver_array as $employee_id => $driver) { ?>
                                          <option value=<?php echo "$employee_id ";?> <?php if($employee_id == $ifta_results['driver1']) { echo "selected"; }?>><?php echo $driver;?></option>
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
                                          <option value=<?php echo "$employee_id ";?><?php if($employee_id == $ifta_results['driver2']) { echo "selected"; }?>><?php echo $driver;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td>Truck #
                                    <td><input class="input-sm form-control" name="txt_truckno" type="text" id="txt_truckno" value="<?php echo $ifta_results['truck_no'];?>" required></td>
                                 </tr>
                                 <tr>
                                    <td>Starting OD
                                    <td><input class="input-sm form-control" name="txt_od_start" type="text" id="txt_od_start" value="<?php echo $ifta_results['odo_start'];?>" required></td>
                                 </tr>
                                 <tr>
                                    <td>Ending OD
                                    <td><input class="input-sm form-control" name="txt_od_end" type="text" id="txt_od_end" value="<?php echo $ifta_results['odo_end'];?>" required></td>
                                 </tr>
                                 <tr>
                                    <td>Total Trip Miles
                                    <td><input class="input-sm form-control" name="txt_od_total" type="text" id="txt_od_total" value="<?php echo $ifta_results['odo_end'] - $ifta_results['odo_start']; ?>"></td>
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
                                 <td style="text-align: right;"><button class="btn btn-xs btn-primary" type="button" name="txt_new_row_details[]" id="txt_new_row_details_1" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addOdoRow(this);"><span class="glyphicon glyphicon-plus"></span></button></td>
                              </tr>
                              <tr id="tr_add_driver_details_1">
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
                                    <td style="text-align: right;"><button class="btn btn-xs btn-primary" type="button" name="txt_new_row_fuel[]" id="txt_new_row_fuel_1" value="" data-toggle="tooltip" data-placement="top" title="Add New Row" onClick="addFuelRow(this);"><span class="glyphicon glyphicon-plus"></span></button></td>
                                 </tr>
                                 <tr id="tr_add_fuel_details_1">
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
                                 <td>Image IFTA Trip Report</td>
                                 <td><input name="ifta_image_trip[]" type="file" class="file-loading input-sm form-control" id="ifta_image_trip" multiple=true>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_1" value="ifta_image_trip"></td>
                              </tr>
                              <tr>
                                 <td>Image IFTA Fuel Reciepts</td>
                                 <td><input name="ifta_image_fuel[]" type="file" class="file-loading input-sm form-control" id="ifta_image_fuel" multiple=true>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_2" value="ifta_image_fuel"></td>
                              </tr>
                              <tr>
                                 <td>Image IFTA GPS Data</td>
                                 <td><input name="ifta_image_gps[]" type="file" class="file-loading input-sm form-control" id="ifta_image_gps" multiple=true>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_3" value="ifta_image_gps"></td>
                              </tr>
                              <tr>
                                 <td>Image Individual Trip Permits</td>
                                 <td><input name="ifta_image_permits[]" type="file" class="file-loading input-sm form-control" id="ifta_image_permits" multiple=true>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_4" value="ifta_image_permits"></td>
                              </tr>
                              <tr>
                                 <td>Image Driver Logs (for current trip)</td>
                                 <td><input name="ifta_image_drivers_logs" type="file" class="file-loading input-sm form-control" id="ifta_image_drivers_logs" multiple=true>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_5" value="ifta_image_drivers_logs"></td>
                              </tr>
                              <tr>
                                 <td>Image BOL (for current trip)</td>
                                 <td><input name="ifta_image_bol" type="file" class="file-loading input-sm form-control" id="ifta_image_bol" multiple=true>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_6" value="ifta_image_bol"></td>
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
      <!-- Are You Sure? -->
      <script src="<?php echo HTTP;?>/dist/js/jquery.are-you-sure.js"></script>
      <script>
         function addOdoRow() {
         var random = Math.ceil(Math.random() * 10000);
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
                                 <td style="text-align: right;">
                                    <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_details[]" id="txt_delete_row_details_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                 </td>
                              </tr>`;
         
         // Append a new tr to the table based on the 'new_row' variables         
         $("#add_ifta_table > tbody:last-child").append(new_row);

         var prev_hwb = $("#tr_add_driver_details_"+random).prev().children('td').eq(3).children(':text').val();
         var prev_st_enter = $("#tr_add_driver_details_"+random).prev().children('td').eq(6).find('option:selected').text();
         $("#txt_hwb_details_"+random).val(prev_hwb);

         // Set the Exit state to the value of the "enter" state of the previous line
         if (prev_st_enter) {
             $("#txt_state_exit_details_"+random+" option:contains(" + prev_st_enter + ")").attr('selected', 'selected');
         }

         // Append Driver 1 to the select box
         $("#txt_driver_details_"+random)
         .find('option')
         .remove()
         .end()
         .append('<option value="'+driver_list[0].id+'">'+driver_list[0].name+'</option>')
         .val(driver_list[0].name)

         // Make the first driver the selected driver
         $("#txt_driver_details_"+random+" option[value="+driver_list[0].id+"]").prop('selected', true);

         // If we chose a driver 2 then we'll append that
         if (driver_list[1].id != 'null') {
           $("#txt_driver_details_"+random)
           .find('option')
           .remove()
           .end()
           .append('<option value="'+driver_list[1].id+'">'+driver_list[1].name+'</option>')
           .val(driver_list[1].name);
         }

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
    function searchResults(callBack) {
      $.ajax({
       method: "GET",
       url: "searchifta.php",
       data: {
              trip_no: $("#search_tripnum").val(),
              trip_start: $("#trip_search_startdate").val(),
              trip_end: $("#trip_search_enddate").val(),
              trip_state: $("#trip_search_state").val(),
              trip_permit: $("#trip_search_permit_req").is(":checked"),
              trip_truck_no: $("#trip_search_trucknumber").val(),
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
            //console.log(data);
            var output = `<table id="tbl_search_results" class="table">
                            <tr>
                              <td>Trip Number</td>
                              <td>Status</td>
                              <td>Truck</td>
                              <td>Trip Start</td>
                              <td>Trip End</td>
                              <td>Driver Name 1</td>
                              <td>Driver Name 2</td>
                              <td>State Exit</td>
                              <td>State Enter</td>
                              <td>Total Trip Miles</td>
                           </tr>`;


            var json = jQuery.parseJSON( data );
            for(var i = 0; i < json.length; i++) {
              var obj = json[i];
               output = output + `<tr>
                              <td><a href="updateifta.php?trip_no=`+obj.trip_no+`" target="_blank">`+obj.trip_no+`</a></td>
                              <td>Open</td>
                              <td>`+obj.truck_no+`</td>
                              <td>`+obj.date_started+`</td>
                              <td>`+obj.date_ended+`</td>
                              <td>`+obj.driver1+`</td>
                              <td>`+obj.driver2+`</td>
                              <td>`+obj.st_exit+`</td>
                              <td>`+obj.st_enter+`</td>
                              <td>2345</td>
                           </tr>`;
            }
            output = output + '</table>';
            $("#search_results").html(output);
            //console.log(data);
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
    $("#txt_tripnum_details_1").val($("#txt_tripnum").val());
    $("#txt_fuel_tripnum_1").val($("#txt_tripnum").val());
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
