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


          <!-- Drop Down Box 1 -->
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Drop Down Box 1</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
              <table width="80" border="1">
                  <tr>
                    <td colspan="3">Simple Box</td>
                  </tr>
            </table>
              <p>&nbsp;</p>
              <div class="box-footer">Footer</div>
            <!-- /.box-footer-->
          </div><!-- /End Drop Down Box 1 -->


          <!-- Dropdown Box 2 -->
<!--          <div class="box"> -->
<!--Remove the div Class "box" above and add below primary collapsed -->
     <div class="box box-primary collapsed-box">
            <div class="box-header with-border">
              <h3 class="box-title">Drop Down Box 2 Colapsed</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="80" border="1">
                  <tr>
                    <td colspan="3">Simple Box</td>
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Footer</div>
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
