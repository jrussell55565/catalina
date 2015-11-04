<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("../examples/$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];

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

<style type="text/css">
#map-canvas {height: 100%; margin: 0; padding: 0;}
</style>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1> #INSERT PAGE NAME HERE#</h1>
        <ol class="breadcrumb">
          <li><a href="../examples/orders.php"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">#INSERT PAGE NAME HERE#</li>
        </ol>
      </section>
      
<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->      
      
      <!-- Main content -->
      <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <hr>
              <form method="POST" action=../examples/processor.php>
                <div id="options"> Map Defaults:
                  <input type="radio" id="rd_map" name="mapType" value="map" <?php if ($mapType == 'map') { echo 'checked'; } ?>>
                  Map
                  <input type="radio" id="rd_satellite" name="mapType" value="satellite" <?php if ($mapType == 'satellite') { echo 'checked'; } ?>>
                  Satellite
                  <input type="checkbox" id="ck_refresh" name="ck_refresh" <?php echo "$ck_refresh"; ?>>
                  (Auto Refresh: Remember Last Map & Zoom Position)
                  <input type="hidden" id="hdn_zoom" name="hdn_zoom" value="">
                  <select name="sel_refreshTime" id="sel_refreshTime">
                    Auto Refresh Time
    
                    <?php
      switch($_COOKIE["sel_refreshTime"])
      {
        case 1:
        ?>
                    <option selected="selected" value="1">1 min.</option>
                    <option value="5">5 min.</option>
                    <option value="10">10 min.</option>
                    <option value="20">20 min.</option>
                    <?php
          null;
          break;
        case 5:
        ?>
                    <option value="1">1 min.</option>
                    <option selected="selected" value="5">5 min.</option>
                    <option value="10">10 min.</option>
                    <option value="20">20 min.</option>
                    <?php
          null;
          break;
        case 10:
        ?>
                    <option value="1">1 min.</option>
                    <option value="5">5 min.</option>
                    <option selected="selected" value="10">10 min.</option>
                    <option value="20">20 min.</option>
                    <?php
          null;
          break;
        case 20:
        ?>
                    <option value="1">1 min.</option>
                    <option value="5">5 min.</option>
                    <option value="10">10 min.</option>
                    <option selected="selected" value="20">20 min.</option>
                    <?php
          null;
          break;
        default:
        ?>
                    <option selected="selected" value="1">1 min.</option>
                    <option value="5">5 min.</option>
                    <option value="10">10 min.</option>
                    <option value="20">20 min.</option>
                    <?php
          null;
          break;
        }
        ?>
                  </select>
                  <br>
                  <input type="submit" id="btn_submit" value="Save Defaults"/>
                  <hr>
                </div>
              </form>
              <form method="GET">
                <div id="selections"> Live Status (Show map points for users
                  that have had a GPS update in the last 30 minutes)
                  <input type="checkbox" id="ck_status" name="ck_status" checked>
                  <br>
                  Items to show:
                  <input type="checkbox" id="ck_username" name="ck_username" checked>
                  Username
                  <input type="checkbox" id="ck_truck" name="ck_truck" checked>
                  Truck
                  <input type="checkbox" id="ck_trailer" name="ck_trailer" checked>
                  Trailer <br>
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
                    <input type="text" id="timeperiod" name="timeperiod" value=<?php echo $timeperiod;?>>
                    <input type="submit">
                              </div>
              </form>
              <div id="map-canvas" style="width:100%;height:100%;">
                <p>&lt;?php<br>
                  session_start();<br>
                  if ($_SESSION['login'] != 1)<br>
                  {<br>
                  header('Location: orders.php');<br>
                  }<br>
                  include('global.php');<br>
                  mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');<br>
                  mysql_select_db($db_name) or DIE('Database name is not available!');</p>
                <p>$ck_refresh = $_COOKIE[&quot;ck_refresh&quot;];<br>
                  if (empty($_COOKIE[&quot;sel_refreshTime&quot;]))<br>
                  {<br>
                  $refreshRate = 60;<br>
                  }else{<br>
                  $refreshRate = $_COOKIE[&quot;sel_refreshTime&quot;] * 60;<br>
                  }</p>
                <p>if (empty($_COOKIE[&quot;mapType&quot;]))<br>
                  {<br>
                  $mapType = &quot;map&quot;;<br>
                  }else{<br>
                  $mapType = $_COOKIE[&quot;mapType&quot;];<br>
                  }</p>
                <p>if (empty($_COOKIE[&quot;mapZoomLevel&quot;]))<br>
                  {<br>
                  $hdn_zoom = 5;<br>
                  }else{<br>
                  $hdn_zoom = $_COOKIE[&quot;mapZoomLevel&quot;];<br>
                  }</p>
                <p>if (empty($_COOKIE[&quot;mapCenterCoords&quot;]))<br>
                  {<br>
                  $latitude = 33.4500;<br>
                  $longitude = -112.0667;<br>
                  }else{<br>
                  $coordinates = explode(',',$_COOKIE[&quot;mapCenterCoords&quot;]);<br>
                  $latitude = str_replace('(','',$coordinates[0]);<br>
                  $longitude = str_replace(')','',$coordinates[1]);<br>
                  }<br>
                  ?&gt;<br>
  &lt;!DOCTYPE html&gt;<br>
  &lt;html&gt;<br>
  &lt;head&gt;<br>
  &lt;meta http-equiv=&quot;refresh&quot; content=&quot;&lt;?php echo $refreshRate; ?&gt;&quot;&gt;<br>
  &lt;style type=&quot;text/css&quot;&gt;<br>
                  html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}<br>
  &lt;/style&gt;<br>
  &lt;script type=&quot;text/javascript&quot;<br>
                  src=&quot;./js/functions.js&quot;&gt;<br>
  &lt;/script&gt;<br>
  &lt;script type=&quot;text/javascript&quot;<br>
                  src=&quot;https://maps.googleapis.com/maps/api/js&quot;&gt;<br>
  &lt;/script&gt;<br>
  &lt;script type=&quot;text/javascript&quot;&gt;<br>
                  function initialize() {<br>
  &lt;?php<br>
                  if ($mapType == &quot;map&quot;)<br>
                  {<br>
                  $googleMapType = &quot;ROADMAP&quot;;<br>
                  } elseif ($mapType == &quot;satellite&quot;) {<br>
                  $googleMapType = &quot;SATELLITE&quot;;<br>
                  }<br>
                  ?&gt;<br>
  <br>
                  var mapOptions = {<br>
                  center: { lat: &lt;?php echo $latitude; ?&gt;, lng: &lt;?php echo $longitude; ?&gt;},<br>
                  zoom: &lt;?php echo $hdn_zoom; ?&gt;,<br>
                  mapTypeId: google.maps.MapTypeId.&lt;?php echo $googleMapType; ?&gt;<br>
                  };<br>
                  var map = new google.maps.Map(document.getElementById('map-canvas'),<br>
                  mapOptions);<br>
  &lt;?php<br>
                  if (empty($_GET[&quot;timeperiod&quot;]))<br>
                  {<br>
                  $timeperiod = 1;<br>
                  }else{<br>
                  $timeperiod = $_GET[&quot;timeperiod&quot;];<br>
                  }</p>
                <p> if (empty($_GET[&quot;driver&quot;]))<br>
                  {<br>
                  $driver = &quot;NULL&quot;;<br>
                  }else{<br>
                  $driver = $_GET[&quot;driver&quot;];<br>
                  }</p>
                <p> switch ($driver)<br>
                  {<br>
                  case &quot;active&quot;:<br>
                  $sql = &quot;select users.drivername, coordinates.latitude, coordinates.longitude, <br>
                  date_format(coordinates.created_date,'%b %d %Y %h:%i %p') as created_date<br>
                  from users, coordinates<br>
                  WHERE coordinates.driver_id = users.driverid<br>
                  AND coordinates.created_date &gt; NOW() - interval $timeperiod hour&quot;;<br>
                  break;<br>
                  case &quot;inactive&quot;;<br>
                  $sql = &quot;SELECT users.drivername,<br>
                  coordinates.latitude,<br>
                  coordinates.longitude,<br>
                  date_format(coordinates.created_date,'%b %d %Y %h:%i %p') as created_date<br>
                  FROM users,<br>
                  coordinates<br>
                  WHERE coordinates.driver_id = users.driverid<br>
                  AND coordinates.driver_id  IN<br>
                  ( SELECT DISTINCT driver_id<br>
                  FROM coordinates<br>
                  WHERE coordinates.created_date &lt; NOW() - interval 1 hour<br>
                  AND driver_id NOT                     IN<br>
                  (SELECT driver_id<br>
                  FROM coordinates<br>
                  WHERE coordinates.created_date &gt; NOW() - interval 1 hour<br>
                  )<br>
                  )<br>
                  AND coordinates.created_date =<br>
                  (SELECT MAX(t2.created_date)<br>
                  FROM coordinates t2<br>
                  WHERE t2.driver_id = coordinates.driver_id<br>
                  )<br>
                  ORDER BY coordinates.created_date DESC&quot;;<br>
                  break;<br>
                  default:<br>
                  $sql = &quot;select users.drivername, <br>
                  coordinates.latitude, coordinates.longitude, <br>
                  date_format(coordinates.created_date,'%b %d %Y %h:%i %p') as created_date<br>
                  from users, coordinates <br>
                  WHERE coordinates.driver_id = users.driverid <br>
                  AND coordinates.created_date &gt; NOW() - interval $timeperiod hour<br>
                  AND users.drivername = $driver&quot;;<br>
                  }<br>
  <br>
                  $result = mysql_query($sql);<br>
                  $counter = 0;<br>
                  while ($row = mysql_fetch_array($result, MYSQL_BOTH)) <br>
                  {<br>
                  ?&gt;<br>
                  var contentString_&lt;?php echo $counter;?&gt; = '&lt;div id=&quot;content&quot;&gt;'+<br>
                  '&lt;div id=&quot;siteNotice&quot;&gt;'+<br>
                  '&lt;/div&gt;'+<br>
                  '&lt;h1 id=&quot;firstHeading&quot; class=&quot;firstHeading&quot;&gt;&lt;?php echo $row[&quot;drivername&quot;];?&gt;&lt;/h1&gt;'+<br>
                  '&lt;?php echo $row[&quot;created_date&quot;];?&gt;'+<br>
                  '&lt;/div&gt;';<br>
                  var myLatlng_&lt;?php echo $counter;?&gt; = new google.maps.LatLng(&lt;?php echo $row[&quot;latitude&quot;];?&gt;,&lt;?php echo $row[&quot;longitude&quot;];?&gt;);<br>
                  var marker_&lt;?php echo $counter;?&gt; = new google.maps.Marker({<br>
                  position: myLatlng_&lt;?php echo $counter; ?&gt;,<br>
                  map: map,<br>
                  title: '&lt;?php echo $row[&quot;drivername&quot;];?&gt;'<br>
                  });<br>
                  var infowindow_&lt;?php echo $counter;?&gt; = new google.maps.InfoWindow({<br>
                  content: contentString_&lt;?php echo $counter;?&gt;<br>
                  });</p>
                <p> google.maps.event.addListener(marker_&lt;?php echo $counter;?&gt;, 'click', function() {<br>
                  infowindow_&lt;?php echo $counter;?&gt;.open(map,marker_&lt;?php echo $counter;?&gt;);<br>
                  });<br>
  <br>
  &lt;?php<br>
                  $counter++;<br>
                  }<br>
                  ?&gt;<br>
                  google.maps.event.addListener(map, 'zoom_changed', function() {<br>
                  str = map.getZoom() + '';<br>
                  setCookie('mapZoomLevel', str, 1)<br>
                  });</p>
                <p> google.maps.event.addListener(map, 'center_changed', function() {<br>
                  str = map.getCenter() + '';<br>
                  setCookie('mapCenterCoords', str, 1)<br>
                  });<br>
                  }<br>
                  google.maps.event.addDomListener(window, 'load', initialize);<br>
  &lt;/script&gt;<br>
  &lt;/head&gt;<br>
  &lt;body&gt;<br>
  &lt;div id=&quot;header&quot;&gt;<br>
  &lt;h1&gt;Admin Page&lt;/h1&gt;<br>
  &lt;a href=&quot;logout.php&quot;&gt;Logout&lt;/a&gt;&amp;nbsp;&lt;a href=&quot;orders.php&quot;&gt;Orders&lt;/a&gt;<br>
  &lt;!-- end #header --&gt;&lt;a href=&quot;../examples/accessorials.php&quot;&gt;Accessorials&lt;/a&gt;&lt;/div&gt;<br>
  &lt;hr&gt;<br>
  &lt;form method=&quot;POST&quot; action=../examples/processor.php&gt;<br>
  &lt;div id=&quot;options&quot;&gt;<br>
                  Map Defaults:<br>
  &lt;input type=&quot;radio&quot; id=&quot;rd_map&quot; name=&quot;mapType&quot; value=&quot;map&quot; &lt;?php if ($mapType == 'map') { echo 'checked'; } ?&gt;&gt;Map<br>
  &lt;input type=&quot;radio&quot; id=&quot;rd_satellite&quot; name=&quot;mapType&quot; value=&quot;satellite&quot; &lt;?php if ($mapType == 'satellite') { echo 'checked'; } ?&gt;&gt;Satellite<br>
  &lt;input type=&quot;checkbox&quot; id=&quot;ck_refresh&quot; name=&quot;ck_refresh&quot; &lt;?php echo &quot;$ck_refresh&quot;; ?&gt;&gt;<br>
                  (Auto Refresh: Remember Last Map &amp; Zoom Position)<br>
  &lt;input type=&quot;hidden&quot; id=&quot;hdn_zoom&quot; name=&quot;hdn_zoom&quot; value=&quot;&quot;&gt;<br>
  &lt;select name=&quot;sel_refreshTime&quot; id=&quot;sel_refreshTime&quot;&gt;Auto Refresh Time<br>
  &lt;?php<br>
                  switch($_COOKIE[&quot;sel_refreshTime&quot;])<br>
                  {<br>
                  case 1:<br>
                  ?&gt;<br>
  &lt;option selected=&quot;selected&quot; value=&quot;1&quot;&gt;1 min.&lt;/option&gt; <br>
  &lt;option value=&quot;5&quot;&gt;5 min.&lt;/option&gt; <br>
  &lt;option value=&quot;10&quot;&gt;10 min.&lt;/option&gt; <br>
  &lt;option value=&quot;20&quot;&gt;20 min.&lt;/option&gt; <br>
  &lt;?php<br>
                  null;<br>
                  break;<br>
                  case 5:<br>
                  ?&gt;<br>
  &lt;option value=&quot;1&quot;&gt;1 min.&lt;/option&gt; <br>
  &lt;option selected=&quot;selected&quot; value=&quot;5&quot;&gt;5 min.&lt;/option&gt; <br>
  &lt;option value=&quot;10&quot;&gt;10 min.&lt;/option&gt; <br>
  &lt;option value=&quot;20&quot;&gt;20 min.&lt;/option&gt; <br>
  &lt;?php<br>
                  null;<br>
                  break;<br>
                  case 10:<br>
                  ?&gt;<br>
  &lt;option value=&quot;1&quot;&gt;1 min.&lt;/option&gt; <br>
  &lt;option value=&quot;5&quot;&gt;5 min.&lt;/option&gt; <br>
  &lt;option selected=&quot;selected&quot; value=&quot;10&quot;&gt;10 min.&lt;/option&gt; <br>
  &lt;option value=&quot;20&quot;&gt;20 min.&lt;/option&gt; <br>
  &lt;?php<br>
                  null;<br>
                  break;<br>
                  case 20:<br>
                  ?&gt;<br>
  &lt;option value=&quot;1&quot;&gt;1 min.&lt;/option&gt; <br>
  &lt;option value=&quot;5&quot;&gt;5 min.&lt;/option&gt; <br>
  &lt;option value=&quot;10&quot;&gt;10 min.&lt;/option&gt; <br>
  &lt;option selected=&quot;selected&quot; value=&quot;20&quot;&gt;20 min.&lt;/option&gt; <br>
  &lt;?php<br>
                  null;<br>
                  break;<br>
                  default:<br>
                  ?&gt;<br>
  &lt;option selected=&quot;selected&quot; value=&quot;1&quot;&gt;1 min.&lt;/option&gt; <br>
  &lt;option value=&quot;5&quot;&gt;5 min.&lt;/option&gt; <br>
  &lt;option value=&quot;10&quot;&gt;10 min.&lt;/option&gt; <br>
  &lt;option value=&quot;20&quot;&gt;20 min.&lt;/option&gt; <br>
  &lt;?php<br>
                  null;<br>
                  break;<br>
                  }<br>
                  ?&gt;<br>
  &lt;/select&gt;<br>
  &lt;br&gt;<br>
  &lt;input type=&quot;submit&quot; id=&quot;btn_submit&quot; value=&quot;Save Defaults&quot;/&gt;<br>
  &lt;hr&gt;<br>
  &lt;/div&gt;<br>
  &lt;/form&gt;<br>
  &lt;form method=&quot;GET&quot;&gt;<br>
  &lt;div id=&quot;selections&quot;&gt;<br>
                  Live Status (Show map points for users<br>
                  that have had a GPS update in the last 30 minutes)<br>
  &lt;input type=&quot;checkbox&quot; id=&quot;ck_status&quot; name=&quot;ck_status&quot; checked&gt;<br>
  &lt;br&gt;<br>
                  Items to show:<br>
  &lt;input type=&quot;checkbox&quot; id=&quot;ck_username&quot; name=&quot;ck_username&quot; checked&gt;Username<br>
  &lt;input type=&quot;checkbox&quot; id=&quot;ck_truck&quot; name=&quot;ck_truck&quot; checked&gt;Truck<br>
  &lt;input type=&quot;checkbox&quot; id=&quot;ck_trailer&quot; name=&quot;ck_trailer&quot; checked&gt;Trailer<br>
  &lt;br&gt;<br>
                  Select Driver:<br>
  &lt;label for=&quot;driver&quot;&gt;&lt;/label&gt;<br>
  &lt;select name=&quot;driver&quot; id=&quot;Select Driver&quot;&gt;<br>
  &lt;option selected=&quot;selected&quot; value=&quot;active&quot;&gt;Active (Updated location within specified time)&lt;/option&gt; <br>
  &lt;option value=&quot;inactive&quot;&gt;Inactive (Have not updated location within specified time)&lt;/option&gt;<br>
  &lt;?php<br>
                  $sql = &quot;SELECT drivername from users order by 1&quot;;<br>
                  $result = mysql_query($sql);<br>
                  while ($row = mysql_fetch_array($result, MYSQL_BOTH))<br>
                  {<br>
                  ?&gt;<br>
  &lt;option&gt;&lt;?php echo $row[&quot;drivername&quot;]; ?&gt;&lt;/option&gt;<br>
  &lt;?php<br>
                  }<br>
                  ?&gt;<br>
  &lt;/select&gt;<br>
  &lt;p&gt; Select Time Period (hours):<br>
  &lt;label for=&quot;timeperiod&quot;&gt;&lt;/label&gt;<br>
  &lt;input type=&quot;text&quot; id=&quot;timeperiod&quot; name=&quot;timeperiod&quot; value=&lt;?php echo $timeperiod;?&gt;&gt;<br>
  &lt;input type=&quot;submit&quot;&gt;<br>
  &lt;/div&gt;<br>
  &lt;/form&gt;<br>
  &lt;div id=&quot;map-canvas&quot; style=&quot;width:100%;height:100%;&quot;&gt;&lt;/div&gt;<br>
  &lt;div id=&quot;footer&quot;&gt;<br>
  &lt;p&gt;<br>
  &lt;!-- end #footer --&gt;<br>
  &lt;/p&gt;<br>
  &lt;/div&gt;<br>
  &lt;!-- end #container --&gt;&lt;/div&gt;<br>
  &lt;/body&gt;<br>
  &lt;/html&gt;</p>
                <p>--&gt;</p>
              </div>
              <h4 class="box-title"></h4>
              <div class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">
                  <li>
                   <a href="../examples/orders.php?gather=pu">Page1</a></li>
                  <li>
                   <a href="../examples/orders.php?gather=del">Page2</a></li>
                </ul>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">


            <!-- PAGE CONTENT HERE -->

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