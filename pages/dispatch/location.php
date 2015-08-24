<?php
session_start();
if ($_SESSION['login'] != 1)
{
	header('Location: orders.php');
}
include('global.php');
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

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
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="refresh" content="<?php echo $refreshRate; ?>">
    <style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript"
      src="./js/functions.js">
    </script>
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
          mapTypeId: google.maps.MapTypeId.<?php echo $googleMapType; ?>
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
                         AND users.drivername = $driver";
                }
                
		$result = mysql_query($sql);
                $counter = 0;
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
		{
             ?>
                var contentString_<?php echo $counter;?> = '<div id="content">'+
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
<body>
  <div id="header">
    <h1>Admin Page</h1>
    <a href="logout.php">Logout</a>&nbsp;<a href="orders.php">Orders</a>
  <!-- end #header --><a href="accessorials.php">Accessorials</a></div>
  <hr>
<form method="POST" action=processor.php>
  <div id="options">
  Map Defaults:
  <input type="radio" id="rd_map" name="mapType" value="map" <?php if ($mapType == 'map') { echo 'checked'; } ?>>Map
  <input type="radio" id="rd_satellite" name="mapType" value="satellite" <?php if ($mapType == 'satellite') { echo 'checked'; } ?>>Satellite
  <input type="checkbox" id="ck_refresh" name="ck_refresh" <?php echo "$ck_refresh"; ?>>
  (Auto Refresh: Remember Last Map & Zoom Position)
  <input type="hidden" id="hdn_zoom" name="hdn_zoom" value="">
  <select name="sel_refreshTime" id="sel_refreshTime">Auto Refresh Time
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
  <input type="text" id="timeperiod" name="timeperiod" value=<?php echo $timeperiod;?>>
  <input type="submit">
</div>
</form>
<div id="map-canvas" style="width:100%;height:100%;"></div>
<div id="footer">
    <p>
      <!-- end #footer -->
    </p>
  </div>
<!-- end #container --></div>
</body>
</html>
