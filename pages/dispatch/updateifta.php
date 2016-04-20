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

     # Get the driver names and employee_id
     $driver_array = array();
     $statement = 'SELECT fname, lname, employee_id from users WHERE title = "Driver" ORDER BY fname';
     if ($result = $mysqli->query($statement)) {
       while($row = $result->fetch_array(MYSQL_BOTH))
       {
         $driver_array[$row['employee_id']] = $row['fname']." ".$row['lname'];
       }
       $result->close();
     }

   # Create associative array for the border states
   $mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);
   $state_border_array = array();
   $statement = "select state_extent,bs_01,bs_02,bs_03,bs_04,bs_05,bs_06,bs_07,bs_08 from ifta_stateboarders";
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
                      $obj->bs_08
                      ;
             array_push($state_border_array, $states) ;
     }
   }
   /* free result set */
   $result->close();

     // IFTA
     $ifta_results = array();
     $query = "SELECT trip_no,date_format(date_started,'%m/%d/%Y') as date_started,date_format(date_ended,'%m/%d/%Y') as date_ended
               ,driver1,driver2,truck_no,odo_start,odo_end
               FROM ifta WHERE trip_no = '".$_GET['trip_no']."'";
     if ($result = $mysqli->query($query)) {
         while($obj = $result->fetch_object()){ 
           $ifta_results['trip_no'] = $obj->trip_no; 
           $ifta_results['date_started'] = $obj->date_started; 
           $ifta_results['date_ended'] = $obj->date_ended; 
           $ifta_results['driver1'] = $obj->driver1; 
           $ifta_results['driver2'] = $obj->driver2; 
           $ifta_results['truck_no'] = $obj->truck_no; 
           $ifta_results['odo_start'] = $obj->odo_start; 
           $ifta_results['odo_end'] = $obj->odo_end; 
         } 
       $result->close();
     }
     // IFTA_DETAILS
     $ifta_details = array();
     $counter = 0;
     $query = "SELECT ifta_details.id,ifta_details.trip_no,date_format(ifta_details.trip_date,'%m/%d/%Y') as trip_date,
               driver,ifta_details.hwb,ifta_details.route,ifta_details.st_exit,
               ifta_details.st_enter,ifta_details.state_line_odometer,ifta_details.state_miles,ifta_details.permit_required
               FROM ifta_details
               WHERE ifta_details.trip_no = '".$_GET['trip_no']."'
               ORDER BY ifta_details.state_line_odometer ASC";
     if ($result = $mysqli->query($query)) {
         while($obj = $result->fetch_object()){ 
           $ifta_details[$counter]['id'] = $obj->id;
           $ifta_details[$counter]['trip_no'] = $obj->trip_no;
           $ifta_details[$counter]['trip_date'] = $obj->trip_date;
           $ifta_details[$counter]['driver'] = $obj->driver;
           $ifta_details[$counter]['hwb'] = $obj->hwb;
           $ifta_details[$counter]['route'] = $obj->route;
           $ifta_details[$counter]['st_exit'] = $obj->st_exit;
           $ifta_details[$counter]['st_enter'] = $obj->st_enter;
           $ifta_details[$counter]['state_line_odometer'] = $obj->state_line_odometer;
           $ifta_details[$counter]['state_miles'] = $obj->state_miles;
           $ifta_details[$counter]['permit_required'] = $obj->permit_required;
           $ifta_details[$counter]['iteration'] = $counter;
           $counter++;
         } 
       $result->close();
     }

     // IFTA_FUEL
     $ifta_fuel = array();
     $counter = 0;
     $query = "select id,trip_no, date_format(trip_date,'%m/%d/%Y') as trip_date,
               fuel_gallons, fuel_reefer, fuel_other, vendor, city, state, odometer
               from ifta_fuel
               WHERE trip_no = '".$_GET['trip_no']."'
               ORDER BY odometer ASC";
     if ($result = $mysqli->query($query)) {
         while($obj = $result->fetch_object()){ 
           $ifta_fuel[$counter]['id'] = $obj->id;
           $ifta_fuel[$counter]['trip_no'] = $obj->trip_no;
           $ifta_fuel[$counter]['trip_date'] = $obj->trip_date;
           $ifta_fuel[$counter]['fuel_gallons'] = $obj->fuel_gallons;
           $ifta_fuel[$counter]['fuel_reefer'] = $obj->fuel_reefer;
           $ifta_fuel[$counter]['fuel_other'] = $obj->fuel_other;
           $ifta_fuel[$counter]['vendor'] = $obj->vendor;
           $ifta_fuel[$counter]['city'] = $obj->city;
           $ifta_fuel[$counter]['state'] = $obj->state;
           $ifta_fuel[$counter]['odometer'] = $obj->odometer;
           $counter++;
         } 
       $result->close();
     }

     // IFTA_UPLOADS
     $ifta_uploads = array();
     $query = "select id,type,file_name,file_name_uploaded from ifta_uploads
               WHERE trip_no = '".$_GET['trip_no']."'";
     if ($result = $mysqli->query($query)) {
         while($obj = $result->fetch_object()){ 
           $ifta_uploads[$obj->type]['name'] = $obj->file_name_uploaded;
           $ifta_uploads[$obj->type]['filename'] = $obj->file_name;
           $ifta_uploads[$obj->type]['id'] = $obj->id;
         }
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
      <script>
        var odo_counter = 100;
        var fuel_counter = 100;
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
                           <?php
                            if (isset($_GET['error'])) {
                              echo "<br>";
                              echo '<div style="width: 50%; text-align: center; margin:auto" class="alert alert-danger" role="alert">Error adding record: ',urldecode($_GET['error']),'</div>';
                            }?>
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
                                    <td><input class="input-sm form-control" name="txt_tripnum" type="text" id="txt_tripnum" value="<?php echo $ifta_results['trip_no'];?>" readonly></td>
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
                              <?php
                                $counter = 0;
                                for($i=0; $i<count($ifta_details); $i++) {
                                  $random = $counter + 1;
                                  $counter++;
                              ?>
                                <tr id="tr_add_driver_details_<?php echo $ifta_details[$i]['iteration'];?>">
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_tripnum_details[]" type="text" id="txt_tripnum_details_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['trip_no'];?>" readonly>
                                    <input type="hidden" name="hdn_details_id[]" id="hdn_details_id_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['id'];?>"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_date_details[]" type="text" id="txt_date_details_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['trip_date'];?>" size=""></td>
                                 <td>
                                  <select class="input-sm form-control" name="txt_driver_details[]" type="text" id="txt_driver_details_<?php echo $random;?>" value="">
                                   <option value="null">Choose...</option>
                                   <?php
                                    foreach ($driver_array as $employee_id => $driver) { ?>
                                    <option value=<?php echo "$employee_id ";?> <?php if($employee_id == $ifta_details[$i]['driver']) { echo "selected"; }?>><?php echo $driver;?></option>
                                   <?php } ?>
                                  </select>
                                 </td>
                                 <td style="width: 5em;"><input class="input-sm form-control hwb" name="txt_hwb_details[]" type="text" id="txt_hwb_details_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['hwb'];?>"></td>
                                 <td><input class="input-sm form-control" name="txt_routes_details[]" type="text" id="txt_routes_details_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['route'];?>"></td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_exit_details[]" id="txt_state_exit_details_<?php echo $random;?>" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option <?php if($state == $ifta_details[$i]['st_exit']){echo " selected ";}?>><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_enter_details[]" id="txt_state_enter_details_<?php echo $random;?>" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option <?php if($state == $ifta_details[$i]['st_enter']){echo " selected ";}?>><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td><input class="input-sm form-control" name="txt_state_odo_details[]" type="text" id="txt_state_odo_details_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['state_line_odometer'];?>"></td>
                                 <td>
                                    <input class="input-sm form-control" name="txt_state_miles_details[]" type="text" id="txt_state_miles_details_<?php echo $random;?>" value="<?php echo $ifta_details[$i]['state_miles'];?>">
                                 </td>
                                 <td><input class="input-sm" type="checkbox" name="txt_permit_req_details[]" id="txt_permit_req_details_<?php echo $random;?>" <?php if($ifta_details[$i]['permit_required'] == 'Y') { echo " checked ";} ?> value="<?php echo $ifta_details[$i]['id'];?>"></td>
                                 <td style="text-align: right;">
                                    <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_details[]" id="txt_delete_row_details_<?php echo $random;?>" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                 </td>
                              </tr>
                              <?php
                              }
                              ?>

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
                                 <?php
                                for($i=0; $i<count($ifta_fuel); $i++) {
                                  $random = mt_rand(0,0xffffff);
                              ?>
<tr id="tr_add_fuel_details_<?php echo $random;?>">
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_tripnum[]" type="text" id="txt_fuel_tripnum_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['trip_no'];?>" readonly>
                                    <input type="hidden" name="hdn_fuel_id[]" id="hdn_fuel_id_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['id'];?>"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_fuel_date[]" type="text" id="txt_fuel_date_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['trip_date'];?>" size=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_gallons[]" type="text" id="txt_fuel_gallons_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['fuel_gallons'];?>"></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_reefer[]" type="text" id="txt_fuel_reefer_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['fuel_reefer'];?>"></td>
                                    <td style="width: 10em;"><input class="input-sm form-control" name="txt_fuel_other[]" type="text" id="txt_fuel_other_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['fuel_other'];?>"></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_vendor[]" type="text" id="txt_fuel_vendor_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['vendor'];?>"></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_city[]" type="text" id="txt_fuel_city_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['city'];?>"></td>
                                    <td>
                                       <select class="input-sm form-control" name="txt_fuel_state[]" id="txt_fuel_state_<?php echo $random;?>" value="">
                                          <?php
                                             foreach ($us_state_abbrevs as $state) { ?>
                                          <option <?php if($state == $ifta_fuel[$i]['state']){echo " selected ";}?>><?php echo $state;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_odo[]" type="text" id="txt_fuel_odo_<?php echo $random;?>" value="<?php echo $ifta_fuel[$i]['odometer'];?>"></td>
                                    <td style="text-align: right;">
                                       <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_fuel[]" id="txt_delete_row_fuel_<?php echo $random;?>" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                 </tr>
                                 <?php 
                                 }
                                 ?>
                                 </tr>
                              </tbody>
                           </table>
                           <p></p>
                           <table class="table table-condensed table-striped">
                            <tbody>
                              <tr>
                                 <td colspan="3" style="text-align: center; font-weight: bold;">Upload Trip Images</td>
                              </tr>
                              <tr>
                              <td colspan="3" id="upload_error" style="text-align: center;">
                              </td>
                              </tr>
                              <tr>
                                 <td>Image IFTA Trip Report</td>
                                 <td>
                                    <?php if (isset($ifta_uploads['ifta_image_trip']['name'])) {?>
                                       <input name="ifta_image_trip[]" type="text" class="input-sm" style="border: 1px solid #CCC; width: 80%;" id="ifta_image_trip" value="<?php echo $ifta_uploads['ifta_image_trip']['name']; ?>">
                                       <div style="float: right;">
                                         <button class="btn btn-sm btn-danger" type="button" name="btn_delete_file_ifta_image_trip" id="btn_delete_file_ifta_image_trip" data-toggle="tooltip" data-placement="top" title="Delete" onClick="deleteUpload(this);" value="<?php echo $ifta_uploads['ifta_image_trip']['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                         <button class="btn btn-sm btn-primary" type="button" name="btn_view_file_ifta_image_trip" id="btn_view_file_ifta_image_trip" data-toggle="tooltip" data-placement="top" title="View" onClick='OpenInNewTab("processifta.php?download_file=1&id=<?php echo $ifta_uploads['ifta_image_trip']['id']; ?>");' ><span class="glyphicon glyphicon-search"></span></button>
                                       </div>
                                     <?php }else{ ?>
                                    <input name="ifta_image_trip[]" type="file" class="file-loading input-sm form-control" id="ifta_image_trip" multiple=false>
                                     <?php } ?>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_1" value="ifta_image_trip">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image IFTA Fuel Reciepts</td>
                                 <td>
                                    <?php if (isset($ifta_uploads['ifta_image_fuel']['name'])) {?>
                                       <input name="ifta_image_fuel[]" type="text" class="input-sm" style="border: 1px solid #CCC; width: 80%;" id="ifta_image_fuel" value="<?php echo $ifta_uploads['ifta_image_fuel']['name']; ?>">
                                       <div style="float: right;">
                                         <button class="btn btn-sm btn-danger" type="button" name="btn_delete_file_ifta_image_fuel" id="btn_delete_file_ifta_image_fuel" data-toggle="tooltip" data-placement="top" title="Delete" onClick="deleteUpload(this);" value="<?php echo $ifta_uploads['ifta_image_fuel']['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                         <button class="btn btn-sm btn-primary" type="button" name="btn_view_file_ifta_image_fuel" id="btn_view_file_ifta_image_fuel" data-toggle="tooltip" data-placement="top" title="View" onClick='OpenInNewTab("processifta.php?download_file=1&id=<?php echo $ifta_uploads['ifta_image_fuel']['id']; ?>");' ><span class="glyphicon glyphicon-search"></span></button>
                                       </div>
                                     <?php }else{ ?>
                                      <input name="ifta_image_fuel[]" type="file" class="file-loading input-sm form-control" id="ifta_image_fuel" multiple=false>
                                     <?php } ?>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_2" value="ifta_image_fuel">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image IFTA GPS Data</td>
                                 <td>
                                    <?php if (isset($ifta_uploads['ifta_image_gps']['name'])) {?>
                                       <input name="ifta_image_gps[]" type="text" class="input-sm" style="border: 1px solid #CCC; width: 80%;" id="ifta_image_gps" value="<?php echo $ifta_uploads['ifta_image_gps']['name']; ?>">
                                       <div style="float: right;">
                                         <button class="btn btn-sm btn-danger" type="button" name="btn_delete_file_ifta_image_gps" id="btn_delete_file_ifta_image_gps" data-toggle="tooltip" data-placement="top" title="Delete" onClick="deleteUpload(this);" value="<?php echo $ifta_uploads['ifta_image_gps']['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                         <button class="btn btn-sm btn-primary" type="button" name="btn_view_file_ifta_image_gps" id="btn_view_file_ifta_image_gps" data-toggle="tooltip" data-placement="top" title="View" onClick='OpenInNewTab("processifta.php?download_file=1&id=<?php echo $ifta_uploads['ifta_image_gps']['id']; ?>");' ><span class="glyphicon glyphicon-search"></span></button>
                                       </div>
                                     <?php }else{ ?>
                                    <input name="ifta_image_gps[]" type="file" class="file-loading input-sm form-control" id="ifta_image_gps" multiple=false>
                                     <?php } ?>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_3" value="ifta_image_gps">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image Individual Trip Permits</td>
                                 <td>
                                    <?php if (isset($ifta_uploads['ifta_image_permits']['name'])) {?>
                                       <input name="ifta_image_permits[]" type="text" class="input-sm" style="border: 1px solid #CCC; width: 80%;" id="ifta_image_permits" value="<?php echo $ifta_uploads['ifta_image_permits']['name']; ?>">
                                       <div style="float: right;">
                                         <button class="btn btn-sm btn-danger" type="button" name="btn_delete_file_ifta_image_permits" id="btn_delete_file_ifta_image_permits" data-toggle="tooltip" data-placement="top" title="Delete" onClick="deleteUpload(this);" value="<?php echo $ifta_uploads['ifta_image_permits']['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                         <button class="btn btn-sm btn-primary" type="button" name="btn_view_file_ifta_image_permits" id="btn_view_file_ifta_image_permits" data-toggle="tooltip" data-placement="top" title="View" onClick='OpenInNewTab("processifta.php?download_file=1&id=<?php echo $ifta_uploads['ifta_image_permits']['id']; ?>");' ><span class="glyphicon glyphicon-search"></span></button>
                                       </div>
                                     <?php }else{ ?>
                                    <input name="ifta_image_permits[]" type="file" class="file-loading input-sm form-control" id="ifta_image_permits" multiple=false>
                                     <?php } ?>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_4" value="ifta_image_permits">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image Driver Logs (for current trip)</td>
                                 <td>
                                    <?php
                                     if (isset($ifta_uploads['ifta_image_drivers_logs']['name'])) {?>
                                       <input name="ifta_image_permits[]" type="text" class="input-sm" style="border: 1px solid #CCC; width: 80%;" id="ifta_image_permits" value="<?php echo $ifta_uploads['ifta_image_drivers_logs']['name']; ?>">
                                       <div style="float: right;">
                                         <button class="btn btn-sm btn-danger" type="button" name="btn_delete_file_ifta_image_drivers_logs" id="btn_delete_file_ifta_image_drivers_logs" data-toggle="tooltip" data-placement="top" title="Delete" onClick="deleteUpload(this);" value="<?php echo $ifta_uploads['ifta_image_drivers_logs']['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                         <button class="btn btn-sm btn-primary" type="button" name="btn_view_file_ifta_image_drivers_logs" id="btn_view_file_ifta_image_drivers_logs" data-toggle="tooltip" data-placement="top" title="View" onClick='OpenInNewTab("processifta.php?download_file=1&id=<?php echo $ifta_uploads['ifta_image_drivers_logs']['id']; ?>");' ><span class="glyphicon glyphicon-search"></span></button>
                                       </div>
                                     <?php }else{ ?>
                                    <input name="ifta_image_drivers_logs" type="file" class="file-loading input-sm form-control" id="ifta_image_drivers_logs" multiple=false>
                                     <?php } ?>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_5" value="ifta_image_drivers_logs">
                                 </td>
                              </tr>
                              <tr>
                                 <td>Image BOL (for current trip)</td>
                                 <td>
                                    <?php
                                     if (isset($ifta_uploads['ifta_image_bol']['name'])) {?>
                                       <input name="ifta_image_bol[]" type="text" class="input-sm" style="border: 1px solid #CCC; width: 80%;" id="ifta_image_bol" value="<?php echo $ifta_uploads['ifta_image_bol']['name']; ?>">
                                       <div style="float: right;">
                                         <button class="btn btn-sm btn-danger" type="button" name="btn_delete_file_ifta_image_bol" id="btn_delete_file_ifta_image_bol" data-toggle="tooltip" data-placement="top" title="Delete" onClick="deleteUpload(this);" value="<?php echo $ifta_uploads['ifta_image_bol']['id']; ?>"><span class="glyphicon glyphicon-remove"></span></button>
                                         <button class="btn btn-sm btn-primary" type="button" name="btn_view_file_ifta_image_bol" id="btn_view_file_ifta_image_bol" data-toggle="tooltip" data-placement="top" title="View" onClick='OpenInNewTab("processifta.php?download_file=1&id=<?php echo $ifta_uploads['ifta_image_bol']['id']; ?>");' ><span class="glyphicon glyphicon-search"></span></button>
                                       </div>
                                     <?php }else{ ?>
                                    <input name="ifta_image_bol" type="file" class="file-loading input-sm form-control" id="ifta_image_bol" multiple=true>
                                     <?php } ?>
                                    <input type="hidden" name="hdn_upload[]" id="hdn_upload_6" value="ifta_image_bol">
                                 </td>
                              </tr>
                            </tbody>
                           </table>
                        <p></p>
                        <button type="submit" class="btn btn-danger" name="update_ifta">Submit</button>
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

         var new_row = `<tr id="tr_add_driver_details_add_`+random+`">
                                 <td style="width: 5em;"><input class="input-sm form-control" name="txt_tripnum_details_add[]" type="text" id="txt_tripnum_details_add_`+random+`" value="`+tripnum+`" readonly>
                                    <input type="hidden" name="hdn_details_id_add[]" id="hdn_details_id_add_`+random+`" value="0"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_date_details_add[]" type="text" id="txt_date_details_add_`+random+`" value="" size=""></td>
                                 <td>
                                  <select class="input-sm form-control" name="txt_driver_details_add[]" type="text" id="txt_driver_details_add_`+random+`" value="">
                                   <option value="null">Choose...</option>
                                  </select>
                                 </td>
                                 <td style="width: 5em;"><input class="input-sm form-control hwb" name="txt_hwb_details_add[]" type="text" id="txt_hwb_details_add_`+random+`"></td>
                                 <td><input class="input-sm form-control" name="txt_routes_details_add[]" type="text" id="txt_routes_details_add_`+random+`"></td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_exit_details_add[]" id="txt_state_exit_details_add_`+random+`" value="">
                                       <?php
                                          foreach ($us_state_abbrevs as $state) { ?>
                                       <option><?php echo $state;?></option>
                                       <?php } ?>
                                    </select>
                                 </td>
                                 <td>
                                    <select class="input-sm form-control" name="txt_state_enter_details_add[]" id="txt_state_enter_details_add_`+random+`" value="">
                                       <option></option>
                                    </select>
                                 </td>
                                 <td><input class="input-sm form-control" name="txt_state_odo_details_add[]" type="text" id="txt_state_odo_details_add_`+random+`"></td>
                                 <td>
                                    <input class="input-sm form-control" name="txt_state_miles_details_add[]" type="text" id="txt_state_miles_details_add_`+random+`" value="">
                                 </td>
                                 <td><input class="input-sm" type="checkbox" name="txt_permit_req_details_add[]" id="txt_permit_req_details_add_`+random+`"></td>
                                 <td style="text-align: right;">
                                    <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_details_add[]" id="txt_delete_row_details_add_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                 </td>
                              </tr>`;
         
         // Append a new tr to the table based on the 'new_row' variables         
         $("#add_ifta_table > tbody:last-child").append(new_row);

         var prev_hwb = $("#tr_add_driver_details_add_"+random).prev().children('td').eq(3).children(':text').val();

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
               if ( $state_array[$i + 1] == '' ) { continue; }
               echo ",";
             }
             echo "],\n";
           }
           echo "        };\n";
         ?>

         var prev_st_enter = $("#tr_add_driver_details_add_"+random).prev().children('td').eq(6).find('option:selected').text();
         $("#txt_hwb_details_add_"+random).val(prev_hwb);

         // Set the Exit state to the value of the "enter" state of the previous line
         if (prev_st_enter) {
             $("#txt_state_exit_details_add_"+random+" option:contains(" + prev_st_enter + ")").attr('selected', 'selected');
         }

         $("#txt_state_enter_details_"+random)
         .find('option')
         .remove()
         .end()

         // Only set the state_enter values if we're NOT the first row
         if (random == 1) {
           $("#txt_state_enter_details_add_"+random)
           <?php
           foreach ($us_state_abbrevs as $state) {
             ?>
             .append('<option value=""><?php echo $state;?></option>')
             <?php
           }?>
         }else{
           var primary_state = $("#txt_state_exit_details_add_"+random).children("option").filter(":selected").text();
           for (var i = 0; i <= states[primary_state].length - 1; i++) {
               $("#txt_state_enter_details_add_"+random)
               .append('<option value="">'+states[primary_state][i]+'</option>')
           }
         }

         // Append Driver 1 to the select box
         $("#txt_driver_details_add_"+random)
         .find('option')
         .remove()
         .end()
         .append('<option value="'+driver_list[0].id+'">'+driver_list[0].name+'</option>')
         .val(driver_list[0].name)

         // If we chose a driver 2 then we'll append that
         if (driver_list[1].id != 'null') {
           $("#txt_driver_details_add_"+random)
           .append('<option value="'+driver_list[1].id+'">'+driver_list[1].name+'</option>')
           .val(driver_list[1].name);
         }

         // Make the first driver the selected driver
         $("#txt_driver_details_add_"+random+" option[value="+driver_list[0].id+"]").prop('selected', true);

         }
         
         function addFuelRow(id) {
         var random = Math.ceil(Math.random() * 1000);
         var tripnum = $("#txt_tripnum").val();
         var new_row = `<tr id="tr_add_fuel_details_add_`+random+`">
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_tripnum_add[]" type="text" id="txt_fuel_tripnum_add_`+random+`" value="`+tripnum+`" readonly>
                                    <input type="hidden" name="hdn_fuel_id_add[]" id="hdn_fuel_id_add_`+random+`" value="0"></td>
                                    <td style="width: 7em;"><input class="input-sm form-control datepicker" name="txt_fuel_date_add[]" type="text" id="txt_fuel_date_add_`+random+`" value="" size=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_gallons_add[]" type="text" id="txt_fuel_gallons_add_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_reefer_add[]" type="text" id="txt_fuel_reefer_add_`+random+`" value=""></td>
                                    <td style="width: 10em;"><input class="input-sm form-control" name="txt_fuel_other_add[]" type="text" id="txt_fuel_other_add_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_vendor_add[]" type="text" id="txt_fuel_vendor_add_`+random+`" value=""></td>
                                    <td><input class="input-sm form-control" name="txt_fuel_city_add[]" type="text" id="txt_fuel_city_add_`+random+`" value=""></td>
                                    <td>
                                       <select class="input-sm form-control" name="txt_fuel_state_add[]" id="txt_fuel_state_add_`+random+`" value="<?php echo $row['st_enter'];?>">
                                          <?php
                                             foreach ($us_state_abbrevs as $state) { ?>
                                          <option><?php echo $state;?></option>
                                          <?php } ?>
                                       </select>
                                    </td>
                                    <td style="width: 5em;"><input class="input-sm form-control" name="txt_fuel_odo_add[]" type="text" id="txt_fuel_odo_add_`+random+`" value=""></td>
                                    <td style="text-align: right;">
                                       <button class="btn btn-sm btn-danger" type="button" name="txt_delete_row_fuel_add[]" id="txt_delete_row_fuel_add_`+random+`" value="" data-toggle="tooltip" data-placement="top" title="Delete Row" onClick="deleteRow(this);"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                                 </tr>`;

         $("#add_ifta_fuel > tbody:last-child").append(new_row);
         }
      </script>

<script>
$(document).ready(function(){
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

function deleteUpload(z) {
    console.log($("#"+z.id).parent().parent().parent().get(0));
  if(confirm("Are you sure you want to delete this file?")){
    var id = $(z).attr("value");
    $.post( "processifta.php", { delete_upload: 1, upload_id: id })
      .success(function() {
        i = z.name.replace("btn_delete_file_","");
        x = '<input name="'+i+'[]" type="file" class="file-loading input-sm form-control" id="'+i+'" multiple=false>';
        y = '<input type="hidden" name="hdn_upload[]" id="hdn_upload_1" value="'+i+'">';
        $("#"+z.id).parent().parent().html(x + y);
        $("upload_error").hide();
      })
      .fail( function(xhr, textStatus, errorThrown) {
        var obj = jQuery.parseJSON( xhr.responseText );
        z = '<div style="width: 50%; text-align: center; margin:auto" class="alert alert-danger" role="alert">'+obj.message+'</div>';
        $("#upload_error").html(z);
      });
    }
}

function OpenInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}

</script>
   </body>
</html>
