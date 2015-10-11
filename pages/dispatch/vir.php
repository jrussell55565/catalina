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
$truckid = $_SESSION['truckId'];
$trailerid = $_SESSION['trailerId'];


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
              <h3 class="box-title">Vechicle Inspection Report</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" border="1">
                  <tr>
                    <td colspan="4">Start Time:
<input name="<?php echo constant('BX_LT'); ?>" type="text" id="<?php echo constant('BX_LT'); ?>" value="<?php echo $localtime; ?>" size="8"/>
                      Date
                      <input name="<?php echo constant('BX_LD'); ?>" type="text" id="<?php echo constant('BX_LD'); ?>" value="<?php echo $localdate; ?>" size="8" readonly="readonly"/>
                    <span class="active"><?php echo "$TruckId"; ?></span></tr>
                  <tr>
                    <td>Truck
                    <td><input name="<?php echo constant('truckId'); ?>2" type="text" id="<?php echo constant('truckId'); ?>" value="<?php echo $truckId; ?>" size="8" readonly="readonly" />                    
                    <td colspan="2"><a href="vir.php"> Previous Inspections</a>                      </tr>
                  <tr>
                    <td>Trailer
                    <td><input name="<?php echo constant('BX_LP'); ?>2" type="text" id="<?php echo constant('BX_LP'); ?>2" value="<?php echo $trailer; ?>" size="8" readonly="readonly" />                    
                    <td colspan="2"><a href="vir.php">Previous Inspections </a>                      </tr>
                  <tr>
                    <td colspan="4"><div align="center">Pre Trip:
                        <input type="checkbox" name="cbpretrip2" value="cbpretrip" id="cbpretrip2"/>
Post Trip:
<input type="checkbox" name="cbposttrip2" value="cbposttrip" id="cbposttrip2"/>
                    </div>                  </tr>
                  <tr>
                    <td><div align="center">Unit Type</div>                        
                  <td colspan="3"><div align="center">Condition of Vehicle</div>                  </tr>
                  <tr>
                    <td width="87"><a href="vir.php"><img src="../images/semi.gif" alt="Semi" width="74" height="45"></a>                  
                    <td width="68" bgcolor="#33FF00">
                    
                        <div align="center">Green
                        <input type="checkbox" name="cbgreensemi" id="cbgreensemi">
                        <label for="cbgreensemi"></label>
                        </div>
                        
                    <td width="72" bgcolor="#FFFF00"><div align="center">Yellow
                        <input type="checkbox" name="cbyellowsemi" id="cbyellowsemi">
                        <label for="cbyellowsemi"></label>
                    </div>
                    <td width="56" bgcolor="#FF0000"><div align="center">Red
                        <input type="checkbox" name="cbredsemi" id="cbredsemi">
                        <label for="cbredsemi"></label>
                    </div>                    
                  </tr>
                  <tr>
                    <td><img src="../images/smalltires.gif" width="25" height="25" alt="tire"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"><img src="../images/smalltires.gif" width="25" height="25" alt="tire">
                    <td bgcolor="#33FF00">
                      <div align="center">Green
                        <input type="checkbox" name="cbgreentrailer" id="cbgreentrailer">
                        <label for="cbgreentrailer"></label>
                      </div>
                      
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowtrailer" id="cbyellowtrailer">
                      <label for="cbyellowtrailer"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredtrailer" id="cbredtrailer">
                      <label for="cbredsemi2"></label>
                    </div>                    </tr>
                  <tr>
                    <td><a href="vir.php"><img src="../images/trailer.gif" alt="Trailer" width="77" height="38"></a>
                    <td bgcolor="#33FF00">
                      <div align="center">Green
                        <input type="checkbox" name="cbgreenboxtruck" id="cbgreenboxtruck">
                        <label for="cbgreenboxtruck"></label>
                      </div>
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowboxtruck" id="cbyellowboxtruck">
                      <label for="cbyellowboxtruck"></label>
                    </div>                    
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredboxtruck" id="cbredboxtruck">
                      <label for="cbredsemi3"></label>
                    </div>                    </tr>
                  <tr>
                    <td><img src="../images/smalltires.gif" width="25" height="25" alt="tire"><img src="../images/smalltires.gif" width="25" height="25" alt="tire"><img src="../images/smalltires.gif" width="25" height="25" alt="tire">
                    <td bgcolor="#33FF00">

                      <div align="center">Green
                        <input type="checkbox" name="cbgreensprinter" id="cbgreensprinter">
                        <label for="cbgreensprinter"></label>
                      </div>
                    <td bgcolor="#FFFF00"><div align="center">Yellow
                      <input type="checkbox" name="cbyellowsprinter" id="cbyellowsprinter">
                      <label for="cbyellowsprinter"></label>
                    </div>                  
                    <td bgcolor="#FF0000"><div align="center">Red
                      <input type="checkbox" name="cbredsprinter" id="cbredsprinter">
                      <label for="cbredsprinter"></label>
                    </div>                  
                  </tr>
                  <tr>
                    <td colspan="4"><div align="center"> <A HREF="#submit inspection">Go To Submit</A></div>                  
                    </tr>
                </table>
              </form>
            </div><!-- /.box-body -->
            <div class="box-footer">If not All Green Additional Items below  as necessary</div>
            <!-- /.box-footer-->
          </div><!-- /.box -->





<!-- Default box -->
           <div class="box"> 
<!--Remove the div Class "box" above and add below primary collapsed -->
<!--      <div class="box box-primary collapsed-box"> --> 
            <div class="box-header with-border">
              <h3 class="box-title">Tires Semi + Trailer Combo <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="1094" border="1">
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
                      <p>Driver Steer
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
                    <td width="93" rowspan="7">
                    <img src="../images/semiandtrailertop.gif" width="115" height="868">                    
                    <td width="93" height="86"><div align="center">
                      <p>Pasg Steer                      
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
                    <td height="50">
                    <td>                                                            
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>Drives Front D
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
                      <p>
                        <select name="Conditions25" id="Conditions28">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                    <td><div align="center">
                      <p>Drives
                        Front P
                        <select name="Conditions26" id="Conditions29">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions26" id="Conditions30">
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
                        <select name="Conditions26" id="Conditions31">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>Drives
                        Rear D
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
                      <p>Drives Rear P
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
                    <td height="186">
                    <td height="186"></tr>
                  <tr>
                    <td height="123"><div align="center">
                      <p>Trailer Front
                        D
                        <select name="Conditions32" id="Conditions47">
                      <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions32" id="Conditions48">
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
                        <select name="Conditions32" id="Conditions49">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                    <td height="123"><div align="center">
                      <p>Trailer Front
                        P
                          <select name="Conditions31" id="Conditions44">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions31" id="Conditions45">
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
                        <select name="Conditions31" id="Conditions46">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                  </tr>
                  <tr>
                    <td height="117"><div align="center">
                      <p>Trailer Rear
                        D
                        <select name="Conditions29" id="Conditions38">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions29" id="Conditions39">
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
                        <select name="Conditions29" id="Conditions40">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                  
                    <td height="117"><div align="center">
                      <p>Trailer Rear
                        P
                          <select name="Conditions30" id="Conditions41">
                          <option selected>Exellent</option>
                          <option>Ok</option>
                          <option>Poor</option>
                          <option>Red Tag</option>
                        </select>
                      </p>
                      <p>
                        <select name="Conditions30" id="Conditions42">
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
                        <select name="Conditions30" id="Conditions43">
                          <option selected>Both</option>
                          <option>Outside</option>
                          <option>Inside</option>
                        </select>
                      </p>
                    </div>                    
                  </tr>
                  <tr>
                  <td height="24" colspan="3">Enter Notes Below for Tire Info!</tr>
                  <tr>
                    <td height="24" colspan="3"><textarea name="textarea3" id="textarea3" cols="43" rows="3"></textarea>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm Semi &amp; Semi Tire Inspection</div>                  
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
              <h3 class="box-title">Tires  Trailer Only <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="865" border="1">
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Trailer Tires (if you add addition trailer on shift)</div>                    
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
                      <p>Trailer Front D
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
                      <p>Trailer Front P
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
                      <p>Trailer Rear
                        D
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
                      <p>Trailer Rear
                        P
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
                  <td height="42" colspan="3">Enter Notes Below for Tire Info!</tr>
                  <tr>
                    <td height="24" colspan="3"><textarea name="textarea3" id="textarea3" cols="43" rows="3"></textarea>                    </tr>
                  <tr>
                    <td height="24" colspan="3"><div align="center">
                      <input type="checkbox" />
                      Confirm Trailer Tire Inspection</div>                  
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
              <h3 class="box-title">Tires Box Truck <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
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
                      <p>P Steer                      
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
                      <p>DFDrive
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
                      <p>PF Drive
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
                    <input type="checkbox" />
                  Confirm Box Truck Tire Inspection</div>                    </tr>
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
              <h3 class="box-title">Tires  Sprinter <img src="../images/smalltires.gif" width="25" height="25" alt="tire"></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <form name="form1" method="post" action="">
                <table width="311" height="488" border="1">
                  <tr>
                    <td colspan="4"><div align="center"> Tire Inspection Sprinter</div>                    </tr>
                  <tr>
                    <td colspan="4"><div align="center"><a href="VIR.php"><img src="../images/tires.gif" alt="" width="150" height="147"></a></div>                    
                  </tr>
                  <tr>
                    <td height="24" colspan="4"><div align="center"> Select the Closest PSI @ Pretrip</div>                                        
                  </tr>
                  <tr>
                    <td width="91" height="110">                    <div align="center">
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
                    <td width="93" height="149" rowspan="4"><img src="../images/sprintertop.gif" width="105" height="248">                                        
                    <td width="93" height="110"><div align="center">
                      <p>P Steer                      
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
                      <p>DFDrive
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
                      <p>PF Drive
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
                    <input type="checkbox" />
                    Check To Confirm Sprinter Tire Inspection
                  </div>                    
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
                <table width="314" border="1">
                  <tr>
                    <td width="304" colspan="3"><div align="center"> Submit VIR &amp; Tire Report</div>
                      <div align="center"></div>
                      <div align="center">
                      <div align="center"></div>
                  </tr>
                  <tr>
                    <td colspan="2"><img src="../images/finish.jpg" alt="Submit" width="410" height="220"></td>
                  <tr>
                    <td colspan="2"><table width="310" border="1">
                    </table>
                      <div align="center">Additional Notes: <?php echo "$drivername"; ?> </div>
                      <textarea name="Remarks" id="Remarks" cols="52" rows="2">Enter Additional Notes Here!</textarea></td>
                  </tr>
                  <tr>
                    <td ><div align="center">
                      <input type="submit" name="btn_sourceform2" id="btn_sourceform2" value="Submit Inspection" />
                    </div></td>
                  </tr>
                </table>
              </form>
              
            </div><!-- /.box-body -->
            <div class="box-footer">Vir Additional info on Yellow or Red + Condition &amp; Notes</div>
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
