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
print_r($_SESSION);

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
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1> VIR AND TIRES</h1>
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
              <form name="form1" method="post" action="">
                <table width="690" border="1">
                  <tr>
                    <td colspan="3">Please fill out trip report below to match the hand written Fuel Report</td>
                  </tr>
                  <tr>
                    <td width="286">Tractor
                      <input name="<?php echo constant('BX_TI'); ?>" type="text" id="<?php echo constant('BX_TI'); ?>" value="<?php echo $truck; ?>" size="4" readonly="readonly" />
Trailer
<input name="<?php echo constant('BX_LP'); ?>" type="text" id="<?php echo constant('BX_LP'); ?>6" value="<?php echo $trailer; ?>" size="4" readonly="readonly" /></td>
                    <td width="388" colspan="2">HWB/Trip# 
                    <input name="ifta_trip_num" type="text" id="ifta_trip_num" value="ifta_trip_num" size="12"></td>
                  </tr>
                  <tr>
                    <td>                    Enter Trip Details Below </td>
                    <td colspan="2"><a href="loadtrip.php">
                      <input type="submit" name="btn_ifta_newtrip" id="btn_ifta_newtrip" value="Start New Trip" />
                    <input type="submit" name="btn_cleariftafields2" id="btn_cleariftafields2" value="Load Trip" />
                    </a></td>
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
                    <td width="48"><input name="Truck_Odometer23" type="text" id="Truck_Odometer23" size="15">
                    <td width="85">
                    <td width="92">
                    <td width="88">
        <td width="90" colspan="3"></tr>
                  <tr>
                    <td height="42" >Date </td>
                    <td >Driver</td>
                    <td >HWB</td>
                    <td >Routes Hwys</td>
                    <td width="48" >State Exit</td>
                    <td width="64" >State Enter</td>
                    <td >Enter OD Reading at state line</td>
                    <td ><p>Total Miles</p></td>
                    <td >Status:</td>
                    <td >Update </td>
                    <td >Delete </td>
                  </tr>
                  <tr>
                    <td height="28" ><input name="<?php echo constant('BX_LD'); ?>4" type="text" id="<?php echo constant('BX_LD'); ?>4" value="<?php echo $localdateYear; ?>" size="8"/></td>
                    <td ><?php echo "$drivername"; ?></td>
                    <td ><label for="iftahwb"></label>
                    <input name="iftahwb" type="text" id="iftahwb" size="15"></td>
                    <td ><input name="ifta_route" type="text" id="ifta_route" size="30"></td>
                    <td ><input name="ifta_st_exit" type="text" id="ifta_st_exit" size="8"></td>
                    <td ><input name="ifta_st_enter" type="text" id="ifta_st_enter" size="8"></td>
                    <td ><input name="ifta_enter_st_od" type="text" id="ifta_enter_st_od" size="15"></td>
                    <td ><input name="ifta_add_st_miles" type="text" id="ifta_add_st_miles" value="100" size="10"></td>
                    <td ><input name="ifta_trip_sts" type="text" id="ifta_trip_sts" value="blank or saved or finalized" size="12"></td>
                    <td ><input type="submit" name="btn_ifta_update_item" id="btn_updateiftaitem7" value="Update" /></td>
                    <td ><input type="submit" name="btn_ifta_delete_item" id="btn_deleteiftaitem7" value="Delete" /></td>
                  </tr>
                  <tr>
                    <td height="28" >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >&nbsp;</td>
                    <td >Ending Odometer
                    <input name="ifta_end_od" type="text" id="ifta_end_od" size="15"></td>
                    <td >TMFT
                    <input name="ifta_add_all_st_miles" type="text" id="ifta_add_all_st_miles" size="10"></td>
                    <td >&nbsp;</td>
                    <td ><input type="submit" name="btn_addiftafields" id="btn_addiftafields" value="Add Miles" /></td>
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
                    <input type="submit" name="btn_cleariftafields3" id="btn_cleariftafields3" value="Clear Fields" />
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
                <tr>
                  <td><input name="<?php echo constant('BX_LD'); ?>6" type="text" id="<?php echo constant('BX_LD'); ?>6" value="<?php echo $localdateYear; ?>" size="8"/></td>
                  <td><label for="fuel_invoice"></label>
                  <input type="text" name="fuel_invoice" id="fuel_invoice"></td>
                  <td><select name="ifta_fuel_type" id="ifta_fuel_type">
                    <option>FuelType</option>
                    <option selected>diesel</option>
                    <option>unlead</option>
                    <option>biodiesel</option>
                    <option>refer</option>
                  </select></td>
                  <td><input name="ifta_fuel_gallons" type="text" id="ifta_fuel_gallons" size="8"></td>
                  <td><select name="ifta_gallons_st" id="ifta_gallons_st">
                    <option>State</option>
                    <option>AL</option>
                    <option>AK</option>
                    <option>AZ</option>
                    <option>AR</option>
                    <option>CA</option>
                    <option>CO</option>
                    <option>CT</option>
                    <option>DE</option>
                    <option>FL</option>
                    <option>GA</option>
                    <option>ID</option>
                    <option>IL</option>
                    <option>IN</option>
                    <option>IA</option>
                    <option>KS</option>
                    <option>KY</option>
                    <option>LA</option>
                    <option>ME</option>
                    <option>MD</option>
                    <option>MA</option>
                    <option>MI</option>
                    <option>MN</option>
                    <option>MS</option>
                    <option>MO</option>
                    <option>MT</option>
                    <option>NE</option>
                    <option>NV</option>
                    <option>NH</option>
                    <option>NJ</option>
                    <option>NM</option>
                    <option>NY</option>
                    <option>NC</option>
                    <option>ND</option>
                    <option>OH</option>
                    <option>OK</option>
                    <option>OR</option>
                    <option>PA</option>
                    <option>RI</option>
                    <option>SC</option>
                    <option>SD</option>
                    <option>TN</option>
                    <option>TX</option>
                    <option>UT</option>
                    <option>VT</option>
                    <option>VA</option>
                    <option>WA</option>
                    <option>WV</option>
                    <option>WI</option>
                    <option>WY</option>
                  </select></td>
                  <td><input name="ifta_fueling_miles" type="text" id="ifta_fueling_miles" size="15"></td>
                  <td><input name="fuel_reciept_total" type="text" id="Truck_Odometer17" size="15"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td><input type="submit" name="btn_addiftafields2" id="btn_addiftafields2" value="Add Reciept" /></td>
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
                <tr>
                  <td><input name="<?php echo constant('BX_LD'); ?>7" type="text" id="<?php echo constant('BX_LD'); ?>8" value="<?php echo $localdateYear; ?>" size="8"/></td>
                  <td><select name="States10" id="States12">
                    <option selected>no permit</option>
                    <option>annual</option>
                    <option>1 time</option>
                    <option>oversized</option>
                    <option>overweight</option>
                  </select></td>
                  <td><select name="States10" id="States14">
                    <option>State</option>
                    <option>AL</option>
                    <option>AK</option>
                    <option>AZ</option>
                    <option>AR</option>
                    <option>CA</option>
                    <option>CO</option>
                    <option>CT</option>
                    <option>DE</option>
                    <option>FL</option>
                    <option>GA</option>
                    <option>ID</option>
                    <option>IL</option>
                    <option>IN</option>
                    <option>IA</option>
                    <option>KS</option>
                    <option>KY</option>
                    <option>LA</option>
                    <option>ME</option>
                    <option>MD</option>
                    <option>MA</option>
                    <option>MI</option>
                    <option>MN</option>
                    <option>MS</option>
                    <option>MO</option>
                    <option>MT</option>
                    <option>NE</option>
                    <option>NV</option>
                    <option>NH</option>
                    <option>NJ</option>
                    <option>NM</option>
                    <option>NY</option>
                    <option>NC</option>
                    <option>ND</option>
                    <option>OH</option>
                    <option>OK</option>
                    <option>OR</option>
                    <option>PA</option>
                    <option>RI</option>
                    <option>SC</option>
                    <option>SD</option>
                    <option>TN</option>
                    <option>TX</option>
                    <option>UT</option>
                    <option>VT</option>
                    <option>VA</option>
                    <option>WA</option>
                    <option>WV</option>
                    <option>WI</option>
                    <option>WY</option>
                  </select></td>
                  <td><input name="Truck_Odometer16" type="text" id="Truck_Odometer20" size="15"></td>
                  <td><input name="Truck_Odometer16" type="text" id="Truck_Odometer21" size="15"></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td><input type="submit" name="btn_addiftafields3" id="btn_addiftafields3" value="Add Permit" /></td>
                </tr>
              </table>
              <p>&nbsp;</p>
              <table width="794" border="1">
                  <tr>
                    <td width="348">&nbsp;</td>
                    <td width="161"><input type="submit" name="btn_fuel2" id="btn_fuel2" value="Submit Fuel" />
                    <input type="submit" name="btn_permitstolls2" id="btn_permitstolls2" value="Submit Permits" /></td>
                </tr>
                  <tr>
                    <td>Finalize  Print &amp; Email Above Trip: <?php echo "$tripname"; ?></td>
                    <td><input type="submit" name="btn_sendfinalizeifta" id="btn_sendfinalizeifta" value="Finalize Trip" /></td>
                  </tr>
      </table>
                <p>&nbsp;</p>
            </div><!-- /.box-body -->
            <div class="box-footer">Footer</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->






          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
     <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title"><img src="../images/semismall.gif" alt="tire"> Truck VIR  <img src="../images/boxtrucksmall.gif" alt="tire"><img src="../images/sprintersmall.gif" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="310" height="399" border="1">
                  <tr>
                    <td colspan="3"><div align="center">Please Note Issues with Truck</div>                    </tr>
                  <tr>
                    <td colspan="3"><div align="center">
                      <p><a href="vir.php"><img src="images/semiboxsprinter.gif" alt="Semi" width="225" height="147"></a></p>
                    </div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">Tractor</div>                                        
                  </tr>
                  <tr>
                  <td width="51"><input type="checkbox" name="air_compressor" id="air_compressor">
                    <label for="blank"></label>
                  <td width="243">Air Compressor                                    </tr>
                  <tr>
                    <td><input type="checkbox" name="air_lines" id="air_lines">                  
                    <td>Air Lines                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="air_conditioning" id="air_conditioning">                    
                    <td>A/C Issues                  
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="alternator" id="alternator">                  
                    <td>Alternator                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="battery" id="battery">                  
                    <td>Battery                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="body" id="body">                  
                    <td>Body                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="break_accessories" id="break_accessorids">                  
                    <td>Brake Accessories</tr>
                  <tr>
                    <td><input type="checkbox" name="breakes" id="breaks">
                    <td>Brakes</tr>
                  <tr>
                    <td><input type="checkbox" name="clutch" id="clutch">                  
                    <td>Clutch                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="defroster" id="defroster">                  
                    <td>Defroster                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="drive_line" id="drive_line">                  
                    <td>Drive Line                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="engine" id="engine">                  
                    <td>Engine                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="exhaust" id="exhaust">                  
                    <td>Exhaust                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="fifth_wheel" id="fifth_wheel">                  
                    <td>Fifth Wheel                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="front_axel" id="front_axel">                  
                    <td>Front Axle                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="fuel_tanks" id="fuel_tanks">                  
                    <td>Fuel Tanks                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="heater" id="heater">                  
                    <td>Heater                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="horn" id="horn">                  
                    <td>Horn                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="lights" id="lights">                  
                    <td>Lights: Head,Stop,Tail,Dash,Turn                   
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="mirrors" id="mirrors">                  
                    <td>Mirrors                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="muffler" id="muffler">                  
                    <td>Muffler                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="oil_pressure" id="oil_pressure">                  
                    <td>Oil Pressure                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="radiator" id="radiator">                  
                    <td>Radiator                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="rear_end" id="rear_end">                  
                    <td>Rear End                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="reflectors" id="reflectors">                  
                    <td>Reflectors                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="safety_equipment" id="saftety_equipment">                  
                    <td>Safety Equipment, Fire Ext.,triangles,fuses</tr>
                  <tr>
                    <td><input type="checkbox" name="springs" id="springs">                  
                    <td>Springs</tr>
                  <tr>
                    <td><input type="checkbox" name="starter" id="blank27">                  
                    <td>Starter                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank28" id="blank28">                  
                    <td>Steering                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank29" id="blank29">                  
                    <td>Tires                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank30" id="blank30">                  
                    <td>Tire Chains                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank31" id="blank31">                  
                    <td>Transmission</tr>
                  <tr>
                    <td><input type="checkbox" name="blank32" id="blank32">                  
                    <td>Wheels                  
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank33" id="blank33">                  
                    <td>Windows                  
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank34" id="blank34">                  
                    <td>Windshield                  
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="blank35" id="blank35">                  
                    <td>Other: Please make Note below</tr>
                  <tr>
                  <td colspan="2">                  <div align="center">
                    <textarea name="vir_detailed_truck" id="vir_detailed_truck" cols="43" rows="3"></textarea>
                  </div>                  <div align="center">
                    <input type="checkbox" />Truck VIR Condition Satisfactory
                  </div>
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->






          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
     <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title"><img src="../images/trailersmall.gif" alt="tire"> Trailer VIR <img src="../images/trailersmall.gif" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
        </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="306" height="399" border="1">
                  <tr>
                    <td colspan="3"><div align="center">Please Note Issues with Truck</div>                    </tr>
                  <tr>
                    <td colspan="3"><div align="center">
                      <p><span class="box-title"><img src="../images/trailer.gif" alt="tire" width="241" height="91"></span></p>
                    </div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">Trailer</div>                                        
                  </tr>
                  <tr>
                  <td width="64">                                                                                                                        <input type="checkbox" name="breakes" id="breaks">                    <td width="226">Brake Connections</tr>
                  <tr>
                    <td><input type="checkbox" name="clutch" id="clutch">                  
                    <td>Brakes</tr>
                  <tr>
                    <td><input type="checkbox" name="defroster" id="defroster">                  
                    <td><p>Coupling devices                    </p></tr>
                  <tr>
                    <td><input type="checkbox" name="drive_line" id="drive_line">                  
                    <td>Doors</tr>
                  <tr>
                    <td><input type="checkbox" name="engine" id="engine">                  
                    <td>Floors                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="exhaust" id="exhaust">                  
                    <td>Hitch</tr>
                  <tr>
                    <td><input type="checkbox" name="fifth_wheel" id="fifth_wheel">                  
                    <td>King Pin                    
                  </tr>
                  <tr>
                    <td><input type="checkbox" name="front_axel" id="front_axel">                  
                    <td>Landing Gear</tr>
                  <tr>
                    <td><input type="checkbox" name="fuel_tanks" id="fuel_tanks">                  
                    <td>Lights: Stop,Tail,Dash,Turn, Running</tr>
                  <tr>
                    <td><input type="checkbox" name="heater" id="heater">                  
                    <td>Roof</tr>
                  <tr>
                    <td><input type="checkbox" name="horn" id="horn">                  
                    <td>Springs</tr>
                  <tr>
                    <td><input type="checkbox" name="lights" id="lights">                  
                    <td>Tires</tr>
                  <tr>
                    <td>
                    <input type="checkbox" name="blank35" id="blank35">                  
                  <td>                  Other: Please make Notes below</tr>
                  <tr>
                  <td colspan="2">                  <div align="center">
                    <textarea name="vir_detailed_truck" id="vir_detailed_truck" cols="43" rows="3"></textarea>
                  </div>                  <div align="center">
                    <input type="checkbox" />
                    Trailer VIR Condition Satisfactory
                  </div>
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->




<!-- Default box -->
<!-- Default box <div class="box">       -->           
<!--Remove the div Class "box" above and add below primary collapsed -->
      <div class="box box-primary collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Truck Tires <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="720" border="1">
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Truck &amp; Trailer Tires (Combo)</div>                    </tr>
                  <tr>
                    <td height="88" colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="86">                    <div align="center">
                      <p>D Steer
                        <select name="Conditions4" id="Conditions4">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions4" id="Conditions5">
<option>125</option>
<option>120</option>
<option>115</option>
<option>110</option>
<option selected>105</option>
<option>100</option>
<option>95</option>
<option>90</option>
<option>80</option>
<option>85</option>
<option>75</option>
<option>70</option>
<option>65</option>
<option>60</option>
<option>50</option>
<option>55</option>
<option>40</option>
<option>30</option>
<option>20</option>
<option>10</option>
                        </select>
                      </p>
                    </div>
                    <td width="93" rowspan="5"><img src="../images/semitopview.gif" width="121" height="404">
                    <td width="93" height="86"><div align="center">
                      <p>P Steer
<select name="Conditions5" id="Conditions6">
                      <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions18" id="Conditions23">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option selected>105</option>
                          <option>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="23">
                    <td>                                                            
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>A1 D Front
                        <select name="Conditions25" id="Conditions26">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions25" id="Conditions27">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                        <select name="Conditions25" id="Conditions28">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                    </div>                  
                    <td><div align="center">
                      <p>A1 P Front
                        <select name="Conditions" id="Conditions2">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions" id="Conditions3">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <select name="Conditions" id="Conditions29">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                  </div>                    </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>A2 D
                        Rear
                        <select name="Conditions28" id="Conditions35">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions28" id="Conditions36">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions28" id="Conditions37">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>
                    <td height="117"><div align="center">
                      <p>A2 P Rear
                        <select name="Conditions27" id="Conditions32">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions27" id="Conditions33">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions27" id="Conditions34">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    </tr>
                  <tr>
                    <td height="23">                                        <td></tr>
                  <tr>
                  <td height="24" colspan="3"><div align="center"> Enter Notes Below</div>                  </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <textarea name="textarea" id="textarea" cols="43" rows="3"></textarea>
                  </div>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm TruckTire Inspection</div>                  
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->




          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
      <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Trailer Tires <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="928" border="1">
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Trailer Tires (If Swap Trailer during the day)</div>                    
                  </tr>
                  <tr>
                    <td height="88" colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="164">
                    <td width="93" rowspan="5">
                    <img src="../images/traileronly.gif" width="115" height="597">                    
                    <td width="93">                  </tr>
                  <tr>
                    <td height="101">                  
                    <td width="93">                    
                  </tr>
                  <tr>
                    <td height="24">
                    <td height="24"></tr>
                  <tr>
                    <td height="159"><div align="center">
                      <p>TA1D Front
                        <select name="Conditions3" id="Conditions">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions3" id="Conditions12">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions3" id="Conditions13">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>
                    <td height="159"><div align="center">
                      <p>TA1P Front
                        <select name="Conditions11" id="Conditions14">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions11" id="Conditions15">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions11" id="Conditions16">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    </tr>
                  <tr>
                    <td height="24"><div align="center">
                      <p>TA2D Rear
                        <select name="Conditions12" id="Conditions17">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions12" id="Conditions18">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions12" id="Conditions19">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                    <td height="24"><div align="center">
                      <p>TA2P Rear
                        <select name="Conditions13" id="Conditions20">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions13" id="Conditions21">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>95</option>
                          <option>90</option>
                          <option>80</option>
                          <option>85</option>
                          <option>75</option>
                          <option>70</option>
                          <option>65</option>
                          <option>60</option>
                          <option>50</option>
                          <option>55</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions13" id="Conditions22">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                  </tr>
                  <tr>
                  <td height="25" colspan="3"><div align="center"> Enter Notes Below</div>                  </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <textarea name="textarea3" id="textarea3" cols="43" rows="3"></textarea>
                    </div>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm Additinal Trailer Tire Inspection</div>                  
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->









          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
    <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire">  Boxtruck tires<img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="584" border="1">
                  <tr>
                    <td colspan="4"><div align="center"> Tire Inspection</div>                    </tr>
                  <tr>
                    <td colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="86">                    <div align="center">
                      <p>BTD Steer
                        <select name="Conditions4" id="Conditions4">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions4" id="Conditions5">
<option>125</option>
<option>120</option>
<option>115</option>
<option>110</option>
<option selected>105</option>
<option>100</option>
<option>90</option>
<option>80</option>
<option>70</option>
<option>60</option>
<option>50</option>
<option>40</option>
<option>30</option>
<option>20</option>
<option>10</option>
                        </select>
                      </p>
                    </div>
                    <td width="93" height="149" rowspan="4"><img src="../images/Box_Truck_Top.gif" width="121" height="336">                                        
                    <td width="93" height="86"><div align="center">
                      <p>BTP Steer                      
                        <select name="Conditions5" id="Conditions6">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions6" id="Conditions7">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option selected>105</option>
                          <option>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="113">                                                            
                    <td height="113">                                                            
                  </tr>
                  <tr>
                    <td height="71"><div align="center">
                      <p>BTD Drive
                        <select name="Conditions7" id="Conditions8">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions9" id="Conditions9">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions23" id="Conditions24">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                                                            
                    <td height="71"><div align="center">
                      <p>BTPF Drive
                        <select name="Conditions8" id="Conditions10">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions10" id="Conditions11">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions24" id="Conditions25">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="86">
                    <td height="86"></tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <textarea name="textarea5" id="textarea5" cols="43" rows="3"></textarea>
                  </div>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm Box Truck Tire Inspection</div>                  
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->





          <!-- Default box -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
     <div class="box box-primary collapsed-box">  
            <div class="box-header with-border">
              <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire">   Sprinter tires <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="314" height="488" border="1">
                  <tr>
                    <td colspan="4"><div align="center"> Tire Inspection Sprinter</div>                    </tr>
                  <tr>
                    <td colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="149">                    <div align="center">
                      <p>SD Steer
                        <select name="Conditions4" id="Conditions4">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions4" id="Conditions5">
<option>125</option>
<option>120</option>
<option>115</option>
<option>110</option>
<option selected>105</option>
<option>100</option>
<option>90</option>
<option>80</option>
<option>70</option>
<option>60</option>
<option>50</option>
<option>40</option>
<option>30</option>
<option>20</option>
<option>10</option>
                        </select>
                      </p>
                    </div>
                    <td width="105" height="149" rowspan="4"><img src="../images/sprintertop.gif" width="105" height="248">                                        
                    <td width="96" height="149"><div align="center">
                      <p>SP Steer                      
                        <select name="Conditions5" id="Conditions6">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions6" id="Conditions7">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option selected>105</option>
                          <option>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="24">                                                            
                    <td height="24">                                                            
                  </tr>
                  <tr>
                    <td height="71"><div align="center">
                      <p>SD
                        R
                        <select name="Conditions7" id="Conditions8">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions9" id="Conditions9">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                    <td height="71"><div align="center">
                      <p>SP R
                        <select name="Conditions8" id="Conditions10">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions10" id="Conditions11">
                          <option>125</option>
                          <option>120</option>
                          <option>115</option>
                          <option>110</option>
                          <option>105</option>
                          <option selected>100</option>
                          <option>90</option>
                          <option>80</option>
                          <option>70</option>
                          <option>60</option>
                          <option>50</option>
                          <option>40</option>
                          <option>30</option>
                          <option>20</option>
                          <option>10</option>
                        </select>
                      </p>
                    </div>                                                            
                  </tr>
                  <tr>
                    <td height="86">
                    <td height="86"></tr>
                  <tr>
                  <td height="24" colspan="3"><div align="center">
                    <textarea name="textarea6" id="textarea6" cols="43" rows="3"></textarea>
                  </div>                  </tr>
                  <tr>
                    <td colspan="3"><div align="center">
                      <input type="checkbox" />
Check To Confirm Sprinter Tire Inspection </div>                    
                    </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->






<!-- Default box -->
          <div class="box">
<!--Remove the div Class "box" above and add below primary collapsed -->
<!--      <div class="box box-primary collapsed-box"> --> 
            <div class="box-header with-border">
              <h3 class="box-title">Submit Inspections</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="318" border="1">
                  <tr>
                    <td width="306" colspan="3"><div align="center"> Submit VIR &amp; Tire Report</div>
                      <div align="center"></div>
                      <div align="center">
                      <div align="center"></div>
                  </tr>
                  <tr>
                    <td colspan="2"><img src="../images/finish.jpg" alt="Submit" width="310" height="152"></td>
                  <tr>
                    <td height="85" colspan="2"><table width="310" border="1">
                    </table>
                      <div align="center">Additional Notes: <?php echo "$drivername"; ?> </div>
                      <div align="center">
                        <textarea name="textarea4" id="textarea4" cols="43" rows="3"></textarea>
                      </div></td>
                  </tr>
                  <tr>
                    <td >
                    <!-- /.Please insert running current time here  On submit us that date time -->
                    <div align="center">End Time:
                        <input name="<?php echo constant('BX_LT'); ?>2" type="text" id="<?php echo constant('BX_LT'); ?>2" value="<?php echo $localtime; ?>" size="8"/>
                    Date: 
                    <input name="<?php echo constant('BX_LD'); ?>2" type="text" id="<?php echo constant('BX_LD'); ?>2" value="<?php echo $localdate; ?>" size="8" readonly="readonly"/>
                    </div></td>
                   
                  </tr>
                  <tr>
                    <td ><div align="center">
                      <input type="submit" name="submitvir" id="submitvir" value="Submit Inspection" />
                    </div></td>
                  </tr>
                  <tr>
                    <td ><div align="center">You will be sent and email of this inspection! </div></td>
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">
              <p><a href="https://www.fmcsa.dot.gov/regulations/title49/section/396.11"> Read before Sending Inspection:</a></p>
              <p><a href="https://www.fmcsa.dot.gov/regulations/title49/section/396.11"> 396.11 DVIR FMCSA Rules</a></p>
            </div>
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
