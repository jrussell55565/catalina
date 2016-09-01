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
$truckid = $_COOKIE['login_truckid'];
$trailerid = $_COOKIE['login_trailerid'];
$truckOdometer = $_COOKIE['login_truckodometer'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
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

<!-- Color overrides for VIR -->
<style>
.btn-primary {
    background-color: #5cb85c;
    border-color: #4cae4c;
}
.btn-primary:hover, .btn-primary:focus, .btn-primary.active {
    color: #ffffff;
    background-color: #d9534f;
    border-color: #d43f3a;
}
</style>

<!-- VIR visibility -->
<style type="text/css">
    .vir{
        display: none;
    }
</style>

</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
  <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
  <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> VIR AND TIRES</h1>
      <ol class="breadcrumb">
        <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
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
          <h3 class="box-title">Vechicle Inspection Report</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form class="form" name="virForm" id="virForm" method="post" action="viractions.php" onsubmit="return validateSubmit(this);">
            
            <table width="313" border="1">
              <tr>
                <td colspan="4">Start Time:
                  <input name="insp_start_time" type="text" id="insp_start_time" value="<?php echo $localtime; ?>" size="8"/>
                  Date
                  <input name="insp_date" type="text" id="insp_date" value="<?php echo $localdate; ?>" size="8" readonly/>
                  <span class="active"><?php echo "$truck_number"; ?></span>
              </tr>
              <tr>
                <td width="94">Truck
                <td width="79"><div align="center">
                  <input name="truck_number" type="text" id="truck_number" value="<?php echo $truckid; ?>" size="8" readonly />
                  <?php
                $statement = "SELECT truck_vir_condition from virs WHERE truck_number = $truckid ORDER BY vir_itemnum DESC LIMIT 1";
                $record = mysql_query($statement);
                $record = mysql_fetch_array($record);
                $a = explode(',',$record[0]);
                ?>
                </div>
                <td colspan="2" ><div align="center"><?php echo $a[0];?> on 7/1/2016</div>                </tr>
              <tr>
                <td>Trailer
                <td><div align="center">
                  <input name="trailer_number" type="text" id="trailer_number" value="<?php echo $trailerid; ?>" size="8" readonly>
                  <input name="truck_odometer" type="hidden" id="truck_odometer" value="<?php echo $truckOdometer; ?>" size="8" readonly>
                </div></td>
                <?php
                $statement = "SELECT trailer_vir_condition from virs WHERE trailer_number = $trailerid ORDER BY vir_itemnum DESC LIMIT 1";
                $record = mysql_query($statement);
                $record = mysql_fetch_array($record);
                $a = explode(',',$record[0]);
                ?>
                <td colspan="2"><div align="center"><?php echo $a[0];?> on 7/1/2016</div>                </tr>
              <tr>
                <td colspan="4">
                <div align="center">
                    Pre Trip:
                    <input name="preorposttrip" type="radio" id="pretrip" value="vir_pretrip">
                    <label for="vir_pretrip"></label>
                    Post Trip:
                    <input type="radio" name="preorposttrip" id="posttrip" value="vir_posttrip">
                    <label for="vir_posttrip"></label>
                </div>
                  <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center; display: none" id="preorpostdiv">Choose pre/post trip or Breakdown</div>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Breakdown:
                    <input name="preorposttrip" type="radio" id="breakdown" value="vir_breakdown">
                    <label for="vir_breakdown"></label>
                </div>                
              </tr>
              <tr>
                <td colspan="4"><div align="center">Truck Type</div>
                  <div align="center"></div>
              </tr>
              <tr>
                <td><div align="center"><span class="box-title"><img src="../images/semismall.gif" alt="tire"></span></div>
                <td colspan="2"><div align="center"><span class="box-title"><img src="../images/boxtrucksmall.gif" alt="tire"></span></div>
                <td width="96"><div align="center"><span class="box-title"><img src="../images/sprintersmall.gif" alt="tire"></span></div>
              </tr>
              <tr>
                <td><div align="center">
                    <input type="radio" name="trucktype" id="trucktype_combo" value="combo">
                    <label for="type_semi"></label>
                  </div>
                <td colspan="2"><div align="center">
                  <input type="radio" name="trucktype" id="trucktype_boxtruck" value="boxtruck">
                  <label for="type_boxtruck"></label>
                  </div>
                  <div align="center"></div>
                <td><div align="center">
                    <input type="radio" name="trucktype" id="trucktype_sprinter" value="sprinter">
                    <label for="type_sprinter"></label>
                  </div>
              </tr>
              <tr>
              <td colspan="4"><div align="center"> If you are reporting vir items that have not been fixed on the truck or trailer but were previously reported in the last week, Select Option Below.</div>              
              </tr>
              <tr>
                <td colspan="3"><div align="center">Previously Reported
                  <input type="radio" name="previously_reported" id="previously_reported" value="previously_reported">
                  <label for="type_sprinter2"></label>
                </div>
                             
                <td><a href="vir_previous.php">Previous VIRS</a></tr>
              <tr>
                <td colspan="3"><div align="center"></div>
                <td><div align="center"></div>                </tr>
            </table>

        <!-- /.box-body -->
        <div class="box-footer">
         <div class="vir">
         Not All Green? Add Items Below!

         <div class="virconfirmation" role="alert" style="padding: 1px; text-align: center; display: none" id="preorpostdiv">Choose a truck type to continue</div>

        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!--          <div class="box"> -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_truck_vir" id="div_truck_vir" class="box box-primary collapsed-box vir truckvir">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/semismall.gif" alt="tire">Truck Inspection <img src="../images/boxtrucksmall.gif" alt="tire"></h3>
          <table width="200" border="1">
              <tr>
                <td width="52" height="4"  bgcolor="#33FF00"><div align="center">Green
                  <input type="radio" name="vir_truck[]" id="vir_truck_green" value="Green,(No Issues)">
                  <label for="vir_truck_green"></label>
                <td width="54" height="4"  bgcolor="#FFFF00"><div align="center">Yellow
                  <input type="radio" name="vir_truck[]" id="vir_truck_yellow" value="Yellow,(Reporting Problems)">
                  <label for="vir_truck_yellow"></label>
                <td width="45" height="4" bgcolor="#FF0000"><div align="center">Red
                  <input name="vir_truck[]" type="radio" id="vir_truck_red" value="Red,(Do Not Operate)" >
                  <label for="vir_truck_red"></label>
              </tr>
</table>

          <!--Removed Sprinter Image on Header Box <img src="../images/sprintersmall.gif" alt="tire">--></h3>
        </div>
        <div class="box-body" id="truck_inspection_virs">
            <table class="table">
              <?php accessorials("Truck",basename(__FILE__),$username); ?>

                <td colspan="2"><div>Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
                  <div>
                    <textarea name="vir_notes_detailed_truck" id="vir_notes_detailed_truck" cols="43" rows="3" placeholder="Truck VIR Note"></textarea>
                  </div></td>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!--          <div class="box"> -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_trailer_vir" id="div_trailer_vir" class="box box-primary collapsed-box vir combo">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/trailersmall.gif" alt="tire"> Trailer Inspection <img src="../images/trailersmall.gif" alt="tire"></h3>
        <table width="200" border="1">
  <tr>
    <td width="52" height="4" bgcolor="#33FF00"><div align="center">Green 
                  <input type="radio" name="vir_trailer[]" id="vir_trailer_green" value="Green,(No Issues)">
                  <label for="vir_truck_green"></label></td>
    <td width="54" height="4" bgcolor="#FFFF00"><div align="center">Yellow
                  <input type="radio" name="vir_trailer[]" id="vir_trailer_yellow" value="Yellow,(Reporting Problems)">
                  <label for="vir_truck_yellow"></label></td>
    <td width="45" height="4" bgcolor="#FF0000"><div align="center">Red
                  <input name="vir_trailer[]" type="radio" id="vir_trailer_red" value="Red,(Do Not Operate)" >
                  <label for="vir_truck_red"></label></td>
  </tr>
</table>          
        </div>
        <div class="box-body" id="trailer_inspection_virs">
            <table class="table">
              <?php accessorials("Trailer",basename(__FILE__),$username); ?>

                <td colspan="2"><div>Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
                  <div>
                    <textarea name="vir_notes_detailed_trailer" id="vir_notes_detailed_trailer" cols="43" rows="3" placeholder="Trailer VIR Note"></textarea>
                  </div></td>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!-- Default box <div class="box">       -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_truck_tire_vir" id="div_truck_tire_vir" class="box box-primary collapsed-box vir combo">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Truck Tires <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>

        <table width="200" border="1">
  <tr>
    <td width="52" height="4" bgcolor="#33FF00"><div align="center">Green 
                  <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire_green" value="Green,(No Issues)">
                  <label for="vir_truck_tire_green"></label></td>
    <td width="54" height="4" bgcolor="#FFFF00"><div align="center">Yellow
                  <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire_yellow" value="Yellow,(Reporting Problems)">
                  <label for="vir_truck_tires_yellow"></label></td>
    <td width="45" height="4" bgcolor="#FF0000"><div align="center">Red
                  <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire_red" value="Red,(Do Not Operate)" >
                  <label for="vir_truck_tires_red"></label></td>
  </tr>
</table>

          
        </div>
        <div class="box-body" id="truck_tire_inspection_virs">
            <table width="311" height="720" border="1">
              <tr>
                <td height="24" colspan="4"><div align="center"> Truck &amp; Trailer Tires (Combo)</div>
              </tr>
              <tr>
                <td height="88" colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>
              </tr>
              <tr>
                <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>
              </tr>
              <tr>
                <td width="91" height="86"><div align="center">
                    <p>D Steer
                      <select name="truck_tires_driverside_steer_combo" id="truck_tires_driverside_steer_combo">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_steer_pressure_combo" id="truck_tires_driverside_steer_pressure_combo">
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
                      <select name="truck_tires_passenger_steer_combo" id="truck_tires_passenger_steer_combo">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_steer_pressure_combo" id="truck_tires_passenger_steer_pressure_combo">
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
                      <select name="truck_tires_driverside_ax1front_combo" id="truck_tires_driverside_ax1front_combo">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_ax1front_pressure_combo" id="truck_tires_driverside_ax1front_pressure_combo">
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
                    <select name="truck_tires_driverside_ax1front_position_combo" id="truck_tires_driverside_ax1front_position_combo">
                      <option selected>Both</option>
                      <option>Outside</option>
                      <option>Inside</option>
                    </select>
                  </div>
                <td><div align="center">
                    <p>A1 P Front
                      <select name="truck_tires_passenger_ax1front_combo" id="truck_tires_passenger_ax1front_combo">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_ax1front_pressure_combo" id="truck_tires_passenger_ax1front_pressure_combo">
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
                    <select name="truck_tires_passenger_ax1front_position_combo" id="truck_tires_passenger_ax1front_position_combo">
                      <option selected>Both</option>
                      <option>Outside</option>
                      <option>Inside</option>
                    </select>
                  </div>
              </tr>
              <tr>
                <td height="117"><div align="center">
                    <p>A2 D
                      Rear
                      <select name="truck_tires_driverside_ax2rear_combo" id="truck_tires_driverside_ax2rear_combo">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_ax2rear_pressure_combo" id="truck_tires_driverside_ax2rear_pressure_combo">
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
                      <select name="truck_tires_driverside_ax2rear_position_combo" id="truck_tires_driverside_ax2rear_position_combo">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
                <td height="117"><div align="center">
                    <p>A2 P Rear
                      <select name="truck_tires_passenger_ax2rear_combo" id="truck_tires_passenger_ax2rear_combo">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_ax2rear_pressure_combo" id="truck_tires_passenger_ax2rear_pressure_combo">
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
                      <select name="truck_tires_passenger_ax2rear_position_combo" id="truck_tires_passenger_ax2rear_position_combo">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
              </tr>
              <tr>
                <td height="23">
                <td>
              </tr>
              <tr>
                <td height="24" colspan="3"><div align="center"> Enter Notes Below</div>
              </tr>
              <tr>
                <td height="24" colspan="3"><div align="center">
                    <textarea name="truck_tires_notes_combo" id="truck_tires_notes_combo" cols="43" rows="3" placeholder="Truck Tires Note"></textarea>
                  </div>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!--          <div class="box"> -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_trailer_tire_vir" id="div_trailer_tire_vir" class="box box-primary collapsed-box vir combo">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Trailer Tires <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
          
      <!--Put Checkbox for Green Yellow Red for Trailer Tires here --> 

          <table width="200" border="1">
  <tr>
    <td width="52" height="4" bgcolor="#33FF00"><div align="center">Green 
                  <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire_green" value="Green,(No Issues)">
                  <label for="vir_trailer_tire_green"></label></td>
    <td width="54" height="4" bgcolor="#FFFF00"><div align="center">Yellow
                  <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire_yellow" value="Yellow,(Reporting Problems)">
                  <label for="vir_trailer_tire_yellow"></label></td>
    <td width="45" height="4" bgcolor="#FF0000"><div align="center">Red
                  <input name="vir_trailer_tire[]" type="radio" id="vir_trailer_tire_red" value="Red,(Do Not Operate)" >
                  <label for="vir_trailer_tire_red"></label></td>
  </tr>
</table>


        </div>
        <div class="box-body" id="trailer_tire_inspection_virs">
            <table width="311" height="928" border="1">
              <tr>
                <td height="24" colspan="4"><div align="center">(Pre trip again when Changing Trailers)</div>
              </tr>
              <tr>
                <td height="88" colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>
              </tr>
              <tr>
                <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>
              </tr>
              <tr>
                <td width="91" height="164">
                <td width="93" rowspan="5"><img src="../images/traileronly.gif" width="115" height="597">
                <td width="93">
              </tr>
              <tr>
                <td height="101">
                <td width="93">
              </tr>
              <tr>
                <td height="24">
                <td height="24">
              </tr>
              <tr>
                <td height="159"><div align="center">
                    <p>TA1D Front
                      <select name="trailer_tires_driverside_ax1front_trailer" id="trailer_tires_driverside_ax1front_trailer">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="trailer_tires_driverside_ax1front_pressure_trailer" id="trailer_tires_driverside_ax1front_pressure_trailer">
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
                      <select name="trailer_tires_driverside_ax1front_position_trailer" id="trailer_tires_driverside_ax1front_position_trailer">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
                <td height="159"><div align="center">
                    <p>TA1P Front
                      <select name="trailer_tires_passenger_ax1front_trailer" id="trailer_tires_passenger_ax1front_trailer">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="trailer_tires_passenger_ax1front_pressure_trailer" id="trailer_tires_passenger_ax1front_pressure_trailer">
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
                      <select name="trailer_tires_passenger_ax1front_position_trailer" id="trailer_tires_passenger_ax1front_position_trailer">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
              </tr>
              <tr>
                <td height="24"><div align="center">
                    <p>TA2D Rear
                      <select name="trailer_tires_driverside_ax2rear_trailer" id="trailer_tires_driverside_ax2rear_trailer">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="trailer_tires_driverside_ax2rear_pressure_trailer" id="trailer_tires_driverside_ax2rear_pressure_trailer">
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
                      <select name="trailer_tires_driverside_ax2rear_position_trailer" id="trailer_tires_driverside_ax2rear_position_trailer">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
                <td height="24"><div align="center">
                    <p>TA2P Rear
                      <select name="trailer_tires_passenger_ax2rear_trailer" id="trailer_tires_passenger_ax2rear_trailer">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="trailer_tires_passenger_ax2rear_pressure_trailer" id="trailer_tires_passenger_ax2rear_pressure_trailer">
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
                      <select name="trailer_tires_passenger_ax2rear_position_trailer" id="trailer_tires_passenger_ax2rear_position_trailer">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
              </tr>
              <tr>
                <td height="25" colspan="3"><div align="center"> Enter Notes Below</div>
              </tr>
              <tr>
                <td height="24" colspan="3"><div align="center">
                    <textarea name="trailer_tires_notes_trailer" id="trailer_tires_notes_trailer" cols="43" rows="3" placeholder="Trailer Tire Note"></textarea>
                  </div>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!--          <div class="box"> -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_boxtruck_tire_vir" id="div_boxtruck_tire_vir" class="box box-primary collapsed-box vir boxtruck">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Boxtruck Tires<img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
          
           <table width="200" border="1">
  <tr>
    <td width="52" height="4" bgcolor="#33FF00"><div align="center">Green 
                  <input type="radio" name="vir_truck_tire[]" id="vir_box_tire_green" value="Green,(No Issues)">
                  <label for="vir_truck_tire"></label></td>
                  
    <td width="54" height="4" bgcolor="#FFFF00"><div align="center">Yellow
                  <input type="radio" name="vir_truck_tire[]" id="vir_box_tire_yellow" value="Yellow,(Reporting Problems)">
                  <label for="vir_trailer_tire"></label></td>
                  
    <td width="45" height="4" bgcolor="#FF0000"><div align="center">Red
                  <input name="vir_truck_tire[]" type="radio" id="vir_box_tire_red" value="Red,(Do Not Operate)" >
                  <label for="vir_truck_tire"></label></td>
  </tr>
</table>       
          
        </div>
        <div class="box-body" id="boxtruck_tire_inspection_virs">
            <table width="311" height="584" border="1">
              <tr>
                <td colspan="4"><div align="center"> Tire Inspection</div>
              </tr>
              <tr>
                <td colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>
              </tr>
              <tr>
                <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>
              </tr>
              <tr>
                <td width="91" height="86"><div align="center">
                    <p>BTD Steer
                      <select name="truck_tires_driverside_steer_boxtruck" id="truck_tires_driverside_steer_boxtruck">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_steer_pressure_boxtruck" id="truck_tires_driverside_steer_pressure_boxtruck">
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
                      <select name="truck_tires_passenger_steer_boxtruck" id="truck_tires_passenger_steer_boxtruck">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_steer_pressure_boxtruck" id="truck_tires_passenger_steer_pressure_boxtruck">
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
                      <select name="truck_tires_driverside_ax1front_boxtruck" id="truck_tires_driverside_ax1front_boxtruck">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_ax1front_pressure_boxtruck" id="truck_tires_driverside_ax1front_pressure_boxtruck">
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
                      <select name="truck_tires_driverside_ax1front_position_boxtruck" id="truck_tires_driverside_ax1front_position_boxtruck">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
                <td height="71"><div align="center">
                    <p>BTPF Drive
                      <select name="truck_tires_passenger_ax1front_boxtruck" id="truck_tires_passenger_ax1front_boxtruck">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_ax1front_pressure_boxtruck" id="truck_tires_passenger_ax1front_pressure_boxtruck">
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
                      <select name="truck_tires_passenger_ax1front_position_boxtruck" id="truck_tires_passenger_ax1front_position_boxtruck">
                        <option selected>Both</option>
                        <option>Outside</option>
                        <option>Inside</option>
                      </select>
                    </p>
                  </div>
              </tr>
              <tr>
                <td height="86">
                <td height="86">
              </tr>
              <tr>
                <td height="24" colspan="3"><div align="center">
                    <textarea name="truck_tires_notes_boxtruck" id="truck_tires_notes_boxtruck" cols="43" rows="3" placeholder="BT Tire Note"></textarea>
                  </div>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!--          <div class="box"> -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_sprinter_tire_vir" id="div_sprinter_tire_vir" class="box box-primary collapsed-box vir sprinter">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Sprinter tires <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
          
                     <table width="200" border="1">
  <tr>
    <td width="52" height="4" bgcolor="#33FF00"><div align="center">Green 
                  <input type="radio" name="vir_truck_tire[]" id="vir_sprinter_tire_green" value="Green,(No Issues)">
                  <label for="vir_trailer_tire"></label></td>
    <td width="54" height="4" bgcolor="#FFFF00"><div align="center">Yellow
                  <input type="radio" name="vir_truck_tire[]" id="vir_sprinter_tire_yellow" value="Yellow,(Reporting Problems)">
                  <label for="vir_trailer_tire"></label></td>
    <td width="45" height="4" bgcolor="#FF0000"><div align="center">Red
                  <input name="vir_truck_tire[]" type="radio" id="vir_sprinter_tire_red" value="Red,(Do Not Operate)" >
                  <label for="vir_trailer_tire"></label></td>
  </tr>
</table>
          
          
        </div>
        <div class="box-body" id="sprinter_tire_inspection_virs">
            <table width="314" height="488" border="1">
              <tr>
                <td colspan="4"><div align="center"> Tire Inspection Sprinter</div>
              </tr>
              <tr>
                <td colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>
              </tr>
              <tr>
                <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>
              </tr>
              <tr>
                <td width="91" height="149"><div align="center">
                    <p>SD Steer
                      <select name="truck_tires_driverside_steer_sprinter" id="truck_tires_driverside_steer_sprinter">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_steer_pressure_sprinter" id="truck_tires_driverside_steer_pressure_sprinter">
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
                      <select name="truck_tires_passenger_steer_sprinter" id="truck_tires_passenger_steer_sprinter">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_steer_pressure_sprinter" id="truck_tires_passenger_steer_pressure_sprinter">
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
                      <select name="truck_tires_driverside_ax1front_sprinter" id="truck_tires_driverside_ax1front_sprinter">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_driverside_ax1front_pressure_sprinter" id="truck_tires_driverside_ax1front_pressure_sprinter">
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
                      <select name="truck_tires_passenger_ax1front_sprinter" id="truck_tires_passenger_ax1front_sprinter">
                        <option>Excellent</option>
                        <option selected>Good</option>
                        <option>Poor</option>
                        <option>Red Tag</option>
                      </select>
                    </p>
                    <p>
                      <select name="truck_tires_passenger_ax1front_pressure_sprinter" id="truck_tires_passenger_ax1front_pressure_sprinter">
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
                <td height="86">
              </tr>
              <tr>
                <td height="24" colspan="3"><div align="center">
                    <textarea name="truck_tires_notes_sprinter" id="truck_tires_notes_sprinter" cols="43" rows="3" placeholder="Sprinter Tire Note"></textarea>
                  </div>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <div class="box vir virsubmit">
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
            <table width="258" height="307" border="1">
              <tr>
                <td width="272" colspan="3"><div align="center"> Submit VIR &amp; Tire Report</div>
                  <div align="center"></div>
                  <div align="center">
                  <div align="center"></div>
              </tr>
              <tr>
                <td colspan="2"><img src="../images/finish.jpg" alt="Submit" width="252" height="119"></td>
              <tr>
                <td height="85" colspan="2"><table width="250" border="1">
               <td colspan="4">
                  <div class="alert alert-danger" role="alert" style="padding: 1px; text-align: center; display: none" id="generalStatus"></div>
               </td>
                  </table>
                  <div align="center">General VIR Notes: <?php echo "$drivername"; ?> </div>
                  <div align="center">
                    <textarea name="vir_notes_finish" id="vir_notes_finish" cols="35" rows="3" placeholder="Enter final notes here"></textarea>
                  </div></td>
              </tr>
              <tr>
                <td ><!-- /.Please insert running current time here  On submit us that date time -->

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
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <p><a href="https://www.fmcsa.dot.gov/regulations/title49/section/396.11"> Read before Sending Inspection:</a></p>
          <p><a href="https://www.fmcsa.dot.gov/regulations/title49/section/396.11"> 396.11 DVIR FMCSA Rules</a></p>
        </div>
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

<!-- javascript to open or close the vir divs -->
<script type="text/javascript">
$(document).ready(function(){
    $('input[type="radio"]').click(function(){
        // Open the div if a yellow/red truck vir was selected
        if($(this).attr("id")=="vir_truck_yellow" || $(this).attr("id")=="vir_truck_red")
        {
            $("#truck_inspection_virs").show();
        }
        if($(this).attr("id")=="vir_truck_green")
        {
            $("#truck_inspection_virs").hide();
        }
        // Open the div if a yellow/red trailer vir was selected
        if($(this).attr("id")=="vir_trailer_yellow" || $(this).attr("id")=="vir_trailer_red")
        {
            $("#trailer_inspection_virs").show();
        }
        if($(this).attr("id")=="vir_trailer_green")
        {
            $("#trailer_inspection_virs").hide();
        }
        // Open the div if a yellow/red truck tire vir was selected
        if($(this).attr("id")=="vir_truck_tire_yellow" || $(this).attr("id")=="vir_truck_tire_red")
        {
            $("#truck_tire_inspection_virs").show();
        }
        if($(this).attr("id")=="vir_truck_tire_green")
        {
            $("#truck_tire_inspection_virs").hide();
        }
        // Open the div if a yellow/red trailer tire vir was selected
        if($(this).attr("id")=="vir_trailer_tire_yellow" || $(this).attr("id")=="vir_trailer_tire_red")
        {
            $("#trailer_tire_inspection_virs").show();
        }
        if($(this).attr("id")=="vir_trailer_tire_green")
        {
            $("#trailer_tire_inspection_virs").hide();
        }
        // Open the div if a yellow/red boxtruck tire vir was selected
        if($(this).attr("id")=="vir_box_tire_yellow" || $(this).attr("id")=="vir_box_tire_red")
        {
            $("#boxtruck_tire_inspection_virs").show();
        }
        if($(this).attr("id")=="vir_box_tire_green")
        {
            $("#boxtruck_tire_inspection_virs").hide();
        }
        // Open the div if a yellow/red trailer tire vir was selected
        if($(this).attr("id")=="vir_sprinter_tire_yellow" || $(this).attr("id")=="vir_sprinter_tire_red")
        {
            $("#sprinter_tire_inspection_virs").show();
        }
        if($(this).attr("id")=="vir_sprinter_tire_green")
        {
            $("#sprinter_tire_inspection_virs").hide();
        }

        if($(this).attr("value")=="combo")
        {
          if ((document.getElementById('pretrip').checked) || (document.getElementById('posttrip').checked) || (document.getElementById('breakdown').checked))
          {
            $(".vir").not(".combo").hide();
            $(".combo").show();
            $(".truckvir").show();
            $(".virsubmit").show();
            $(".virconfirmation").hide();
          }else{
            $('#preorpostdiv').css('display', 'block');
            $(this).prop('checked', false);
          }
        }
        if($(this).attr("value")=="boxtruck"){
          if ((document.getElementById('pretrip').checked) || (document.getElementById('posttrip').checked) || (document.getElementById('breakdown').checked))
          {
            $(".vir").not(".boxtruck").hide();
            $(".boxtruck").show();
            $(".truckvir").show();
            $(".virsubmit").show();
            $(".virconfirmation").hide();
          }else{
            $('#preorpostdiv').css('display', 'block');
            $(this).prop('checked', false);
          }
        }
        if($(this).attr("value")=="sprinter"){
          if ((document.getElementById('pretrip').checked) || (document.getElementById('posttrip').checked) || (document.getElementById('breakdown').checked))
          {
            $(".vir").not(".sprinter").hide();
            $(".sprinter").show();
            $(".truckvir").show();
            $(".virsubmit").show();
            $(".virconfirmation").hide();
          }else{
            $('#preorpostdiv').css('display', 'block');
            $(this).prop('checked', false);
          }
        }
    });
});

function validateSubmit( obj ){
    if(!$('#vir_truck_green').is(':checked') &&
       !$('#vir_truck_yellow').is(':checked') &&
       !$('#vir_truck_red').is(':checked'))
    {
      $('#generalStatus').html("Must specify truck condition")
      $('#generalStatus').css('display', 'block');
      return false
    }
    // Check for sprinter
    if($('#trucktype_sprinter').is(':checked'))
    {
      if(!$('#vir_sprinter_tire_green').is(':checked') &&
       !$('#vir_sprinter_tire_yellow').is(':checked') &&
       !$('#vir_sprinter_tire_red').is(':checked'))
      {
       $('#generalStatus').html("Choose an option for sprinter tire conditions")
       $('#generalStatus').css('display', 'block');
       return false
      }
    }
    // Check for boxtruck
    if($('#trucktype_boxtruck').is(':checked'))
    {
      if(!$('#vir_box_tire_green').is(':checked') &&
       !$('#vir_box_tire_yellow').is(':checked') &&
       !$('#vir_box_tire_red').is(':checked'))
      {
       $('#generalStatus').html("Choose an option for boxtruck tire conditions")
       $('#generalStatus').css('display', 'block');
       return false
      }
    }
    // Now only check the following if a semi was selected
    if($('#trucktype_combo').is(':checked'))
    {
        if(!$('#vir_truck_tire_green').is(':checked') &&
           !$('#vir_truck_tire_yellow').is(':checked') && 
           !$('#vir_truck_tire_red').is(':checked'))
        {
          $('#generalStatus').html("Choose an option for truck tire conditions")
          $('#generalStatus').css('display', 'block');
          return false
        }
        if(!$('#vir_trailer_green').is(':checked') &&
           !$('#vir_trailer_yellow').is(':checked') && 
           !$('#vir_trailer_red').is(':checked'))
        {
           $('#generalStatus').html("Choose an option for trailer condition")
           $('#generalStatus').css('display', 'block');
           return false
        }
        if(!$('#vir_trailer_tire_green').is(':checked') &&
           !$('#vir_trailer_tire_yellow').is(':checked') && 
           !$('#vir_trailer_tire_red').is(':checked'))
        {
           $('#generalStatus').html("Choose an option for trailer tire conditions")
           $('#generalStatus').css('display', 'block');
           return false
        }
    }
    if($('#vir_truck_yellow').is(':checked') || $('#vir_truck_red').is(':checked'))
    {
        var truck_vir = [];
        $.each($("input[name='truck_ck_accessorials[]']:checked"), function(){            
            truck_vir.push($(this).val());
        });
        if (truck_vir.length == 0)
        {
          $('#generalStatus').html("Please select an issue when marking truck as yellow or red.")
          $('#generalStatus').css('display', 'block');
          return false
        }
    }
    if($('#vir_trailer_yellow').is(':checked') || $('#vir_trailer_red').is(':checked'))
    {
        var trailer_vir = [];
        $.each($("input[name='trailer_ck_accessorials[]']:checked"), function(){            
            trailer_vir.push($(this).val());
        });
        if (trailer_vir.length == 0)
        {
          $('#generalStatus').html("Please select an issue when marking trailer as yellow or red.")
          $('#generalStatus').css('display', 'block');
          return false
        }
    }
    if($('#vir_truck_tire_yellow').is(':checked') || $('#vir_truck_tire_red').is(':checked'))
    {
        if($('#truck_tires_driverside_steer_combo option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_steer_combo option:selected').text() == 'Excellent' &&
           $('#truck_tires_driverside_ax1front_combo option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_ax1front_combo option:selected').text() == 'Excellent' &&
           $('#truck_tires_driverside_ax2rear_combo option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_ax2rear_combo option:selected').text() == 'Excellent')
          {
            $('#generalStatus').html("Select an appropriate tire status when marking truck tire as yellow or red.");
            $('#generalStatus').css('display', 'block');
            return false
           }
    }
    if($('#vir_trailer_tire_yellow').is(':checked') || $('#vir_trailer_tire_red').is(':checked'))
    {
        if($('#trailer_tires_driverside_ax1front_trailer option:selected').text() == 'Excellent' &&
           $('#trailer_tires_passenger_ax1front_trailer option:selected').text() == 'Excellent' &&
           $('#trailer_tires_driverside_ax2rear_trailer option:selected').text() == 'Excellent' &&
           $('#trailer_tires_passenger_ax2rear_trailer option:selected').text() == 'Excellent')
          {
            $('#generalStatus').html("Select an appropriate tire status when marking trailer tire as yellow or red.");
            $('#generalStatus').css('display', 'block');
            return false
           }
    }
    if($('#vir_box_tire_yellow').is(':checked') || $('#vir_box_tire_red').is(':checked'))
    {
        if($('#truck_tires_driverside_steer_boxtruck option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_steer_boxtruck option:selected').text() == 'Excellent' &&
           $('#truck_tires_driverside_ax1front_boxtruck option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_ax1front_boxtruck option:selected').text() == 'Excellent')
          {
            $('#generalStatus').html("Select an appropriate tire status when marking boxtruck tire as yellow or red.");
            $('#generalStatus').css('display', 'block');
            return false
           }
    }
    if($('#vir_sprinter_tire_yellow').is(':checked') || $('#vir_sprinter_tire_red').is(':checked'))
    {
        if($('#truck_tires_driverside_steer_sprinter option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_steer_sprinter option:selected').text() == 'Excellent' &&
           $('#truck_tires_driverside_ax1front_sprinter option:selected').text() == 'Excellent' &&
           $('#truck_tires_passenger_ax1front_sprinter option:selected').text() == 'Excellent')
          {
            $('#generalStatus').html("Select an appropriate tire status when marking sprinter tire as yellow or red.");
            $('#generalStatus').css('display', 'block');
            return false
           }
    }

    return true
}
</script>
</body>
</html>
