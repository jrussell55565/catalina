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
          <h3 class="box-title">Vechicle Inspection Quick Report</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form class="form" name="virForm" method="post" action="viractions.php">
            <table width="313" border="1">
              <tr>
                <td colspan="4">Start Time:
                  <input name="insp_start_time" type="text" id="insp_start_time" value="<?php echo $localtime; ?>" size="8"/>
                  Date
                  <input name="insp_date" type="text" id="insp_date" value="<?php echo $localdate; ?>" size="8" readonly/>
                  <span class="active"><?php echo "$truck_number"; ?></span>
              </tr>
              <tr>
                <td width="95">Truck
                <td width="72"><input name="truck_number" type="text" id="truck_number" value="<?php echo $truckid; ?>" size="8" readonly />
                <td colspan="2"><a href="vir_previous_truck.php"> Previous VIR</a>
              </tr>
              <tr>
                <td>Trailer
                <td><input name="trailer_number" type="text" id="trailer_number" value="<?php echo $trailerid; ?>" size="8" readonly />
                <td colspan="2"><a href="vir_previous_trailer.php">Previous VIR</a>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Pre Trip:
                    <input type="radio" name="preorposttrip" id="preorposttrip" value="vir_pretrip" checked>
                    <label for="vir_pretrip"></label>
                    Post Trip:
                    <input type="radio" name="preorposttrip" id="preorposttrip" value="vir_posttrip">
                    <label for="vir_posttrip"></label>
                  </div>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Truck Type</div>
                  <div align="center"></div>
              </tr>
              <tr>
                <td><div align="center"><span class="box-title"><img src="../images/semismall.gif" alt="tire"></span></div>
                <td colspan="2"><div align="center"><span class="box-title"><img src="../images/boxtrucksmall.gif" alt="tire"></span></div>
                <td width="100"><div align="center"><span class="box-title"><img src="../images/sprintersmall.gif" alt="tire"></span></div>
              </tr>
              <tr>
                <td><div align="center">
                    <input type="radio" name="trucktype" id="trucktype" value="combo">
                    <label for="type_semi"></label>
                  </div>
                <td colspan="2"><div align="center">
                    <input type="radio" name="trucktype" id="trucktype" value="boxtruck">
                    <label for="type_boxtruck"></label>
                  </div>
                  <div align="center"></div>
                <td><div align="center">
                    <input type="radio" name="trucktype" id="trucktype" value="sprinter">
                    <label for="type_sprinter"></label>
                  </div>
              </tr>
            </table>
            <table width="313" border="1">
              <tr>
                <td height="10" colspan="4"><div align="center">
                    <label for="VIR Conditions &amp; Tires"></label>
                    VIR Conditions &amp; Tires</div>
              </tr>
              <tr>
                <td width="83">Truck
                <td width="64" bgcolor="#33FF00"><div align="center">Green
                    <input type="radio" name="vir_truck[]" id="vir_truck[]" value="green" checked>
                    <label for="vir_truck_green"></label>
                  </div>
                <td width="66" bgcolor="#FFFF00"><div align="center">Yellow
                    <input type="radio" name="vir_truck[]" id="vir_truck[]" value="yellow">
                    <label for="vir_truck_yellow"></label>
                  </div>
                <td width="62" bgcolor="#FF0000"><div align="center">Red
                    <input type="radio" name="vir_truck[]" id="vir_truck[]" value="red">
                    <label for="vir_truck_red"></label>
                  </div>
              </tr>
              <tr>
                <td>Truck <img src="../images/smalltires.gif" width="25" height="25" alt="tire">
                <td bgcolor="#33FF00"><div align="center">Green
                    <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire[]" value="green" checked>
                    <label for="truck_tires_green"></label>
                  </div>
                <td bgcolor="#FFFF00"><div align="center">Yellow
                    <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire[]" value="yellow">
                    <label for="truck_tires_yellow"></label>
                  </div>
                <td bgcolor="#FF0000"><div align="center">Red
                    <label for="cb_trailer_tires_red"></label>
                    <input type="radio" name="vir_truck_tire[]" id="vir_truck_tire[]" value="red">
                    <label for="truck_tires_red"></label>
                  </div>
              </tr>
              <tr>
                <td><a href="vir.php"><img src="../images/trailer.gif" alt="Trailer" width="77" height="38"></a>
                <td bgcolor="#33FF00"><div align="center">Green
                    <label for="cb_trailer_green3"></label>
                    <input type="radio" name="vir_trailer[]" id="vir_trailer[]" value="green" checked>
                    <label for="vir_trailer_green"></label>
                  </div>
                <td bgcolor="#FFFF00"><div align="center">Yellow
                    <label for="cb_trailer_yellow3"></label>
                    <input type="radio" name="vir_trailer[]" id="vir_trailer[]" value="yellow">
                    <label for="vir_trailer_yellow"></label>
                  </div>
                <td bgcolor="#FF0000"><div align="center">Red
                    <input type="radio" name="vir_trailer[]" id="vir_trailer[]" value="red">
                    <label for="vir_trailer_red"></label>
                    <label for="cb_trailer_red3"></label>
                  </div>
              </tr>
              <tr>
                <td>Trailer <img src="../images/smalltires.gif" width="25" height="25" alt="tire">
                <td bgcolor="#33FF00"><div align="center">Green
                    <label for="cb_trailer_tires_green3"></label>
                    <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire[]" value="green" checked>
                    <label for="trailer_tires_green"></label>
                  </div>
                <td bgcolor="#FFFF00"><div align="center">Yellow
                    <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire[]" value="yellow">
                    <label for="trailer_vir_tires_yellow"></label>
                  </div>
                <td bgcolor="#FF0000"><div align="center">Red
                    <input type="radio" name="vir_trailer_tire[]" id="vir_trailer_tire[]" value="red">
                    <label for="trailer_vir_tires_red"></label>
                  </div>
              </tr>
              <tr>
                <td colspan="4"><div align="center">Enter Additional Notes below</div></td>
              </tr>
              <tr>
                <td colspan="4"><div align="center">
                    <textarea name="vir_notes_quick_report" id="vir_notes_quick_report"  cols="43" rows="3" placeholder="Please type notes for any items needing attention!"></textarea>
                  </div></td>
              </tr>
              <tr>
                <td colspan="4"><A HREF="#submitvir"></A>
                  <div align="center"> <A HREF="#submitvir">VIR OK, Tires OK / Go To Submit</A></div></td>
              </tr>
            </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
         <div class="vir">
         Not All Green? Add Items Below!
         </div>
         <div class="virconfirmation">
         Choose a truck type to continue.
         </div>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

      <!-- Default box -->
      <!--          <div class="box"> -->
      <!--Remove the div Class "box" above and add below primary collapsed -->
      <div name="div_truck_vir" id="div_truck_vir" class="box box-primary collapsed-box vir truckvir">
        <div class="box-header with-border">
          <h3 class="box-title"><img src="../images/semismall.gif" alt="tire"> Truck VIR <img src="../images/boxtrucksmall.gif" alt="tire"><img src="../images/sprintersmall.gif" alt="tire"></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
            <table class="table">
              <?php accessorials("Truck",basename(__FILE__),$username); ?>

                <td colspan="2"><div>Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
                  <div>
                    <textarea name="vir_notes_detailed_truck" id="vir_notes_detailed_truck" cols="43" rows="3"></textarea>
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
          <h3 class="box-title"><img src="../images/trailersmall.gif" alt="tire"> Trailer VIR <img src="../images/trailersmall.gif" alt="tire"></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
            <table class="table">
              <?php accessorials("Trailer",basename(__FILE__),$username); ?>

                <td colspan="2"><div>Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
                  <div>
                    <textarea name="vir_notes_detailed_trailer" id="vir_notes_detailed_trailer" cols="43" rows="3"></textarea>
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
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                    <textarea name="truck_tires_notes_combo" id="truck_tires_notes_combo" cols="43" rows="3"></textarea>
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
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                    <textarea name="trailer_tires_notes_trailer" id="trailer_tires_notes_trailer" cols="43" rows="3"></textarea>
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
          <h3 class="box-title"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"> Boxtruck tires<img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                    <textarea name="truck_tires_notes_boxtruck" id="truck_tires_notes_boxtruck" cols="43" rows="3"></textarea>
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
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                        <option selected>Exellent</option>
                        <option>Ok</option>
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
                    <textarea name="truck_tires_notes_sprinter" id="truck_tires_notes_sprinter" cols="43" rows="3"></textarea>
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
                    <textarea name="vir_notes_finish" id="vir_notes_finish" cols="43" rows="3"></textarea>
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
        if($(this).attr("value")=="combo"){
            $(".vir").not(".combo").hide();
            $(".combo").show();
            $(".truckvir").show();
            $(".virsubmit").show();
            $(".virconfirmation").hide();
        }
        if($(this).attr("value")=="boxtruck"){
            $(".vir").not(".boxtruck").hide();
            $(".boxtruck").show();
            $(".truckvir").show();
            $(".virsubmit").show();
            $(".virconfirmation").hide();
        }
        if($(this).attr("value")=="sprinter"){
            $(".vir").not(".sprinter").hide();
            $(".sprinter").show();
            $(".truckvir").show();
            $(".virsubmit").show();
            $(".virconfirmation").hide();
        }
    });
});
</script>
</body>
</html>
