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

$ck_refresh = $_COOKIE["ck_refresh"];
if (empty($_COOKIE["sel_refreshTime"]))
{
  $refreshRate = 60;
}else{
  $refreshRate = $_COOKIE["sel_refreshTime"] * 60;
}

if (empty($_COOKIE["mapType"]))
{
  $mapType = "map";
}else{
  $mapType = $_COOKIE["mapType"];
}

if (empty($_COOKIE["mapZoomLevel"]))
{
  $hdn_zoom = 5;
}else{
  $hdn_zoom = $_COOKIE["mapZoomLevel"];
}

if (empty($_COOKIE["mapCenterCoords"]))
{
  $latitude = 33.4500;
  $longitude = -112.0667;
}else{
  $coordinates = explode(',',$_COOKIE["mapCenterCoords"]);
  $latitude = str_replace('(','',$coordinates[0]);
  $longitude = str_replace(')','',$coordinates[1]);
}

# Process GET requests.  Made by the driver export calls
if (isset($_GET['exportDisplay']))
{
  $startDate = $_GET['start'];
  $endDate = $_GET['end'];
  $exportType = $_GET['inlineRadioOptions'];

  $loginSql = "select 
  a.drivername,
  b.driver_driverid,
  b.truck_number,
  b.trailer_number,
  b.rental,
  b.login_time,
  b.truck_odometer 
  from users a, 
   login_capture b 
  where 
   a.driverid = b.driver_driverid
   and login_time between str_to_date('$startDate','%m/%d/%Y')
     and str_to_date('$endDate','%m/%d/%Y')";

  if ($_GET['inlineRadioOptions'] == "exportCsv")
  {
    $fileName = time() . '.csv';
    $fileDir = '/tmp/';
    $file = fopen($fileDir . $fileName, "w") or die("Unable to open file!");
    $sql = mysql_query($loginSql);
    while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
    {
      $fullRow = $row['drivername'] . "," .
                 $row['driver_driverid'] . "," .
                 $row['truck_number'] . "," .
                 $row['trailer_number'] . "," .
                 $row['rental'] . "," .
                 $row['login_time'] . "," .
                 $row['truck_odometer'] . "\n";
      file_put_contents($fileDir . $fileName, $fullRow, FILE_APPEND | LOCK_EX);
     }
      fclose($fileDir . $fileName);

      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($fileDir . $fileName));
      header ("Content-Disposition:attachment; filename=\"$fileName\"");
      readfile($fileDir . $fileName);
      unlink($fileDir . $fileName);
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8" http-equiv="refresh" content="<?php echo $refreshRate; ?>">

<style type="text/css">
        html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
    }
    </style>

    <title>Location</title>
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

    <!-- Date Picker -->
    <link href="<?php echo HTTP;?>/dist/css/bootstrap-datepicker3.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo HTTP . "/dist/css/animate.css";?>">
    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js">
    </script>
    <script type="text/javascript">
      function initialize() {
      <?php
        if ($mapType == "map")
        {
          $googleMapType = "ROADMAP";
        } elseif ($mapType == "satellite") {
          $googleMapType = "SATELLITE";
        }
      ?>
        var mapOptions = {
          center: { lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>},
          zoom: <?php echo $hdn_zoom; ?>,
          mapTypeId: google.maps.MapTypeId.<?php echo $googleMapType;?>
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
            <?php
                if (empty($_GET["timeperiod"]))
                {
                  $timeperiod = 1;
                }else{
                  $timeperiod = $_GET["timeperiod"];
                }

                if (empty($_GET["driver"]))
                {
                  $driver = "NULL";
                }else{
                  $driver = $_GET["driver"];
                }

                switch ($driver)
                {
                  case "active":
                    $sql = "select users.drivername, coordinates.latitude, coordinates.longitude,
                           date_format(coordinates.created_date,'%b %d %Y %h:%i %p') as created_date
                        from users, coordinates
                        WHERE coordinates.driver_id = users.driverid
                        AND coordinates.created_date > NOW() - interval $timeperiod hour";
                    break;
                  case "inactive";
                    $sql = "SELECT users.drivername,
                             coordinates.latitude,
                             coordinates.longitude,
                             date_format(coordinates.created_date,'%b %d %Y %h:%i %p') as created_date
                           FROM users,
                             coordinates
                           WHERE coordinates.driver_id = users.driverid
                           AND coordinates.driver_id  IN
                             ( SELECT DISTINCT driver_id
                             FROM coordinates
                             WHERE coordinates.created_date < NOW() - interval 1 hour
                             AND driver_id NOT                     IN
                               (SELECT driver_id
                               FROM coordinates
                               WHERE coordinates.created_date > NOW() - interval 1 hour
                               )
                             )
                           AND coordinates.created_date =
                           (SELECT MAX(t2.created_date)
                           FROM coordinates t2
                           WHERE t2.driver_id = coordinates.driver_id
                           )
                         ORDER BY coordinates.created_date DESC";
                    break;
                  default:
                         $sql = "select users.drivername,
                         coordinates.latitude, coordinates.longitude,
                         date_format(coordinates.created_date,'%b %d %Y %h:%i %p') as created_date
                         from users, coordinates
                         WHERE coordinates.driver_id = users.driverid
                         AND coordinates.created_date > NOW() - interval $timeperiod hour
                         AND users.drivername = '$driver'";
                }
                                $result = mysql_query($sql);
                $counter = 0;
                                while ($row = mysql_fetch_array($result, MYSQL_BOTH))
                                {
             ?>
                var contentString_<?php echo $counter;?> = '<div id="mapContent">'+
                '<div id="siteNotice">'+
                '</div>'+
                '<h1 id="firstHeading" class="firstHeading"><?php echo $row["drivername"];?></h1>'+
                '<?php echo $row["created_date"];?>'+
                '</div>';
                 var myLatlng_<?php echo $counter;?> = new google.maps.LatLng(<?php echo $row["latitude"];?>,<?php echo $row["longitude"];?>);
                 var marker_<?php echo $counter;?> = new google.maps.Marker({
                     position: myLatlng_<?php echo $counter; ?>,
                     map: map,
                     title: '<?php echo $row["drivername"];?>'
                 });
                 var infowindow_<?php echo $counter;?> = new google.maps.InfoWindow({
                   content: contentString_<?php echo $counter;?>
                 });

                 google.maps.event.addListener(marker_<?php echo $counter;?>, 'click', function() {
                   infowindow_<?php echo $counter;?>.open(map,marker_<?php echo $counter;?>);
                 });

                                <?php
                $counter++;
                }
                                ?>
             google.maps.event.addListener(map, 'zoom_changed', function() {
               str = map.getZoom() + '';
               setCookie('mapZoomLevel', str, 1)
             });

             google.maps.event.addListener(map, 'center_changed', function() {
               str = map.getCenter() + '';
               setCookie('mapCenterCoords', str, 1)
             });
      }
      google.maps.event.addDomListener(window, 'load', initialize);
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
          <h1> Catalina Dashboard <small>1.0.</small></h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>
        
        <!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->
        
        <?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>
        
        <!-- End Animated Top Menu -->
        
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Driver Locations<span class="progress-text"></span></h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <div class="btn-group">
                    <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">

<form method="POST" action=processor.php>
  <div id="options">
  Map Defaults:
  <input type="radio" id="rd_map" name="mapType" value="map" checked>Map
  <input type="radio" id="rd_satellite" name="mapType" value="satellite" >Satellite
  <input type="checkbox" id="ck_refresh" name="ck_refresh" NULL>
  (Auto Refresh: Remember Last Map & Zoom Position)
  <input type="hidden" id="hdn_zoom" name="hdn_zoom" value="">
  <select name="sel_refreshTime" id="sel_refreshTime">Auto Refresh Time
              <option selected="selected" value="1">1 min.</option>
          <option value="5">5 min.</option>
          <option value="10">10 min.</option>
          <option value="20">20 min.</option>
          </select>
  <br>
  <input type="submit" id="btn_submit" value="Save Defaults"/>
  <hr>
  </div>
</form>
<form method="GET">
  <div id="selections">
  Live Status (Show map points for users
  that have had a GPS update in the last 30 minutes)
  <input type="checkbox" id="ck_status" name="ck_status" checked>
  <br>
  Items to show:
  <input type="checkbox" id="ck_username" name="ck_username" checked>Username
  <input type="checkbox" id="ck_truck" name="ck_truck" checked>Truck
  <input type="checkbox" id="ck_trailer" name="ck_trailer" checked>Trailer
  <br>
  Select Driver:
  <label for="driver"></label>
  <select name="driver" id="Select Driver">
    <option selected="selected" value="active">Active (Updated location within specified time)</option>
     <option value="inactive">Inactive (Have not updated location within specified time)</option>
<?php
  $sql = "SELECT drivername from users order by 1";
  $result = mysql_query($sql);
  while ($row = mysql_fetch_array($result, MYSQL_BOTH))
  {
    ?>
      <option><?php echo $row["drivername"]; ?></option>
  <?php
  }
  ?>
  </select>
  <p> Select Time Period (hours):
  <label for="timeperiod"></label>
  <input type="text" id="timeperiod" name="timeperiod" value=1>
  <input type="submit">
</div>
</form>
<div id="map-canvas" style="height:500px; width:1000px"></div>

              </div>
              <!-- ./box-body -->
              <div class="box-footer"> </div>
              <!-- /.box-footer --> 
            </div>
            <!-- /.box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
        <div class="row">
          <div class="col-md-12">
            <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Another Field</h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <div class="btn-group">
                    <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown"><i class="fa fa-wrench"></i></button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#">Action</a></li>
                      <li><a href="#">Another action</a></li>
                      <li><a href="#">Something else here</a></li>
                      <li class="divider"></li>
                      <li><a href="#">Separated link</a></li>
                    </ul>
                  </div>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <!-- /.box-header --><!-- ./box-body -->
            <div class="box-footer"> </div>
              <!-- /.box-footer --> 
          </div>
            <!-- /.box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 

      </div>
      <!-- /.row -->
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
