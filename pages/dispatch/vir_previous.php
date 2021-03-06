<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

$drivername = $_SESSION['drivername'];

if(isset($_GET['submit']))
{
    mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
    mysql_select_db($db_name) or DIE('Database name is not available!');

    # Override the time predicate here.
    $fileName = time() . '.csv';
    $fileDir = '/tmp/';
    $file = fopen($fileDir . $fileName, "w") or die("Unable to open file!");
    $sql = "SELECT * FROM virs WHERE insp_date BETWEEN str_to_date('".$_GET['start']."','%m/%d/%Y') AND str_to_date('".$_GET['end']."','%m/%d/%Y') LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error());;
    /* get column metadata */
    $column_names = array();
    $header = '';
    for ($i = 0; $i <= mysql_num_fields($result); $i++)
    {  
      $meta = mysql_fetch_field($result, $i);
      array_push($column_names,$meta->name);
    }
    mysql_free_result($result);
    $header = implode(',',$column_names);
    $header = rtrim($header,',') . "\n";
    file_put_contents($fileDir . $fileName, $header, LOCK_EX);
    $sql = "SELECT * FROM virs WHERE insp_date BETWEEN str_to_date('".$_GET['start']."','%m/%d/%Y') AND str_to_date('".$_GET['end']."','%m/%d/%Y')";
    $result = mysql_query($sql) or die(mysql_error());;
    while ($row = mysql_fetch_array($result, MYSQL_BOTH))
    {
      for ($i = 0; $i <= mysql_num_fields($result); $i++)
      {
        $a = str_replace(array("\r","\n"), ' ', $row[$i]);
        $a = str_replace(",","|",$a);
        $fullRow .= $a . ",";
      }
      $fullRow = rtrim_limit($fullRow,',',1) . "\n";
      file_put_contents($fileDir . $fileName, $fullRow, FILE_APPEND | LOCK_EX);
      unset($fullRow);
    }
      fclose($fileDir . $fileName);
      mysql_free_result($result);

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

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
# Get the list of mechanics from the users table.
$sql = 'SELECT CONCAT(fname," ",lname) as mechanic ,employee_id from users where title = "Mechanic"';
$results = mysql_query($sql);
$mechanics = array();
$mechanic_id = array();
while ($row = mysql_fetch_array($results,MYSQL_BOTH))
{
array_push($mechanics,$row[0]);
array_push($mechanic_id,$row[1]);
}
mysql_free_result($results);

# Defaults for sorting
$order_by_unit = 'desc';
$glyph_icon_unit = "right";
$orderSql = "ORDER BY insp_date DESC, vir_itemnum ASC";

if ($_GET['sort'] == 'unit')
{
  if ($_GET['order'] == 'desc')
  {
    $order_by_unit = 'asc';
    $glyph_icon_unit = "bottom";
    $orderSql = "ORDER BY truck_number,trailer_number DESC";
  }
  if ($_GET['order'] == 'asc')
  {
    $order_by_unit = 'desc';
    $glyph_icon_unit = "top";
    $orderSql = "ORDER BY truck_number,trailer_number ASC";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Previous VIR</title>
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
<link rel="stylesheet" href="<?php echo HTTP;?>/dist/css/animate.css">
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Previous VIR</h1>
  <ol class="breadcrumb">
    <li>
      <a href="#">
      <i class="fa fa-home"></i> Home
      </a>
    </li>
    <li class="active">Previous VIR</li>
  </ol>
</section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->


<!-- End Animated Top Menu -->



 












<div class="row">
  <div class="col-md-12">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">VIR</h3>
        <p>
         <label for="allvir"></label>
         <input name="radio" type="radio" id="allvir" value="allvir" <?php echo ($_GET['vir_itemnum'] ? 'checked' : null);?>>
         All
         <label for="openvir"></label>
         <input name="radio" type="radio" id="openvir" value="openvir" <?php echo ($_GET['vir_itemnum'] ? null : 'checked');?>>
         Open
        </p>
      </div>
      <!-- /.box-header -->
      
      
<div class="box-body no-padding">
                  <!-- PAGE CONTENT HERE -->
                  <!-- Default box -->
                                   <!-- Search -->
                  <div class="box box-primary collapsed-box" style="border-bottom-color: #3c8dbc; border-bottom-style: solid;">
                     <div class="box-header" style="text-align: center;">
                        <h3 class="box-title"> Search VIRs </h3>
                        <div class="box-tools pull-right">
                           <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"><i class="fa fa-plus"></i></button>
                        </div>
                        <div style="width: 50%; text-align: center; margin:auto; display: none;" id="search_alert" class="alert alert-danger" role="alert"></div>
                     </div>
                     <div class="box-body">
                        <form name="frm_ifta_search" method="GET" action="ifta.php" role="form">
                           <div class="table-responsive">
                              <table width="646" class="table table-condensed table-striped">
                                 <tr>
                                    <td width="111">VIR PO</td>
                                    <td width="444"><input name="vir_search_po" type="text" class="input-sm form-control" id="vir_search_po" value="" ></td>
                                    <td width="18">&nbsp;</td>
                                    <td width="53">&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>Work Order</td>
                                    <td><input name="vir_search_wo" type="text" class="input-sm form-control" id="vir_search_wo" value="" ></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>Unit #</td>
                                    <td><input name="vir_search_un" type="text" class="input-sm form-control" id="vir_search_un" value="" ></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>VIR Begin Date</td>
                                    <td><input name="vir_search_sd" type="text" class="input-sm form-control datepicker" id="vir_search_sd"  data-date-format="mm/dd/yyyy"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>
                                 <tr>
                                    <td>VIR End Date</td>
                                    <td><input name="vir_search_ed" type="text" class="input-sm form-control datepicker" id="vir_search_ed"  data-date-format="mm/dd/yyyy"></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                 </tr>                                   
                                 <tr>
                                    <td colspan="2">
                                       <button type="button" id="btn_display_results" name="btn_display_results" value="display" class="btn btn-primary dropdown-toggle">Display Results</button>
                                    </td>
                                 </tr>
                              </table>
                           </div>
                        </form>
                        <div id="search_results">
                        </div>
                     </div>
                  </div>

                  <!-- / Search --> 

      <div class="box-body table-responsive">
        <table class="table table-striped ">
          <tbody>
            <?php
# If non-admin logs in then only show their info
if ($_SESSION['login'] == 2)
{
  $restricted_predicate = "AND employee_id = '".$_SESSION['employee_id']."'";
  $max_results = 8;
  $date_predicate = ' AND insp_date >= date(now()) - INTERVAL '.$max_results.' DAY';
}else{
  $restricted_predicate = '';
  $max_results = 30;
  $date_predicate = ' AND insp_date >= date(now()) - INTERVAL '.$max_results.' DAY';
}

# Some overrides in case we got here with a specific truck/trailer type
if (isset($_GET['type'])) {
    $truck_or_trailer_predicate = "AND " . $_GET['type'] . "_number = " . $_GET['no'];
    $max_results = 30;
}elseif (isset($_GET['vir_itemnum'])) {
  $truck_or_trailer_predicate = "AND vir_itemnum = ".$_GET['vir_itemnum'];
  // We just want this record since that's what we searched for.
  $date_predicate = ' AND 1=1';
}else{
    $truck_or_trailer_predicate = '';
}

// Get the previous VIRS
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

new mysqli($db_hostname, $db_username, $db_password, $db_name);

$statement = "SELECT vir_itemnum, date_format(insp_date,'%m/%d/%Y') insp_date, insp_type,
                     trucktype, driver_name, truck_number,
                     truck_vir_condition, truck_vir_items,
                     truck_vir_notes, truck_tires_notes,
                     trailer_number, trailer_vir_condition,
                     trailer_vir_items, trailer_vir_notes,
                     trailer_tires_notes, vir_finish_notes,
                     truck_tires_overall, trailer_tires_overall,
                     updated_status,truck_tires_driverside_steer,
                     truck_tires_passenger_steer, truck_tires_driverside_ax1front,
                     truck_tires_passenger_ax1front, truck_tires_driverside_ax2rear,
                     truck_tires_passenger_ax2rear, trailer_tires_driverside_ax1front,
                     trailer_tires_passenger_ax1front, trailer_tires_driverside_ax2rear,
                     trailer_tires_passenger_ax2rear,
                     repair_notes,
                     work_order,
                     repair_cost,
                     repair_by
               FROM virs WHERE 1=1 $restricted_predicate $truck_or_trailer_predicate
               $date_predicate
               $orderSql";

$counter = 0;
$virs = array();
if ($result = $mysqli->query($statement)) {
  while($obj = $result->fetch_object()){
    $virs[$counter]['vir_itemnum'] = $obj->vir_itemnum;
    $virs[$counter]['insp_date'] = $obj->insp_date;
    $virs[$counter]['insp_type'] = $obj->insp_type;
    $virs[$counter]['trucktype'] = $obj->trucktype;
    $virs[$counter]['driver_name'] = $obj->driver_name;
    $virs[$counter]['truck_number'] = $obj->truck_number;
    $virs[$counter]['truck_vir_condition'] = $obj->truck_vir_condition;
    $virs[$counter]['truck_vir_items'] = $obj->truck_vir_items;
    $virs[$counter]['truck_vir_notes'] = $obj->truck_vir_notes;
    $virs[$counter]['truck_tires_notes'] = $obj->truck_tires_notes;
    $virs[$counter]['trailer_number'] = $obj->trailer_number;
    $virs[$counter]['trailer_vir_condition'] = $obj->trailer_vir_condition;
    $virs[$counter]['trailer_vir_items'] = $obj->trailer_vir_items;
    $virs[$counter]['trailer_vir_notes'] = $obj->trailer_vir_notes;
    $virs[$counter]['trailer_tires_notes'] = $obj->trailer_tires_notes;
    $virs[$counter]['vir_finish_notes'] = $obj->vir_finish_notes;
    $virs[$counter]['truck_tires_overall'] = $obj->truck_tires_overall;
    $virs[$counter]['trailer_tires_overall'] = $obj->trailer_tires_overall;
    $virs[$counter]['updated_status'] = $obj->updated_status;
    $virs[$counter]['truck_tires_driverside_steer'] = $obj->truck_tires_driverside_steer;
    $virs[$counter]['truck_tires_passenger_steer'] = $obj->truck_tires_passenger_steer;
    $virs[$counter]['truck_tires_driverside_ax1front'] = $obj->truck_tires_driverside_ax1front;
    $virs[$counter]['truck_tires_passenger_ax1front'] = $obj->truck_tires_passenger_ax1front;
    $virs[$counter]['truck_tires_driverside_ax2rear'] = $obj->truck_tires_driverside_ax2rear;
    $virs[$counter]['truck_tires_passenger_ax2rear'] = $obj->truck_tires_passenger_ax2rear;
    $virs[$counter]['trailer_tires_driverside_ax1front'] = $obj->trailer_tires_driverside_ax1front;
    $virs[$counter]['trailer_tires_passenger_ax1front'] = $obj->trailer_tires_passenger_ax1front;
    $virs[$counter]['trailer_tires_driverside_ax2rear'] = $obj->trailer_tires_driverside_ax2rear;
    $virs[$counter]['trailer_tires_passenger_ax2rear'] = $obj->trailer_tires_passenger_ax2rear;
    $virs[$counter]['work_order'] = $obj->work_order;
    $virs[$counter]['repair_notes'] = $obj->repair_notes;
    $virs[$counter]['repair_cost'] = $obj->repair_cost;
    $virs[$counter]['repair_by'] = $obj->repair_by;
    $counter++;
  }
}

/* free result set */
$result->close();

?>
            <tr>
               <?php echo ($_GET['vir_itemnum'] ? null : '<td style="width: 20px;"><i class="glyphicon glyphicon-wrench"></i></td>
              <td> <a class="glyphicon glyphicon-chevron-down" role="button" data-toggle="collapse"
href="#vir_details" aria-expanded="false" aria-controls="vir_details" style="padding-left: 15px;" id="expand_chevron"> </a> '.$max_results.' days ago </td>'); ?>
            </tr>
            <tr id="vir_details">
              <td colspan="9"><div class="well">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>PO</th>
                        <th>Inspection Date</th>
                        <th>Unit <?php echo ($_GET['vir_itemnum'] ? null : '<a href="?sort=unit&order='.$order_by_unit.'">
                                 <i class="glyphicon glyphicon-triangle-'.$glyph_icon_unit.'"></i></a>');?>
                        </th>
                        <th>Status</th>
                        <th>Tires</th>
                        <th>Reported</th>
                        <th>Updated Status</th>
                        <th>Type</th>
                        <?php echo ($_SESSION['login'] == 1 ? '<th>Actions</th>' : null);?>
                      </tr>
                      <! -- Looping through virs -->
                      <?php
                      for ($x=0; $x < count($virs); $x++)
                      {
                      echo '<!-- '.$x.'-->';
                      if ($virs[$x]['truck_number'] != '')
                      {
                      # $tot is Truck or Trailer
                      $tot = 'truck';
                      }elseif($virs[$x]['trailer_number'] != '') {
                      $tot = 'trailer';
                      }

                      # Set the name of the <tr> so we can show all, open, or 'not closed'
                      if ((preg_match('/^Close/',$virs[$x]['updated_status'])) || ((preg_match('/^Green/',$virs[$x][$tot.'_vir_condition']) && (preg_match('/^Green/',$virs[$x][$tot.'_tires_overall'])))))
                      {
                          $status = 'closed_vir';
                      }else{
                          $status = 'notclosed_vir';
                      }
                      ?>
                      <tr name="<?php echo $status;?>">
                        <td><!-- Button trigger modal -->
                          
                          <button type="button" class="btn btn-primary btn-small" data-toggle="modal" data-target="#modal_<?php echo $tot;?>_<?php echo $virs[$x]['vir_itemnum'];?>">
                          <?php echo $virs[$x]['vir_itemnum'];?>
                          </button></td>
                       <!-- Inspection Date -->
                          <td>
                       <?php echo $virs[$x]['insp_date'];?>
                       </td>
                       <!-- / Inspection Date -->

                       <!-- Unit Number -->
                        <td><?php echo $virs[$x][$tot.'_number'];?></td>
                       <!-- / Unit Number -->

                        <!-- Status -->
                        <td>
                        <?php
                          if (preg_match('/^Green/',$virs[$x][$tot.'_vir_condition']))
                          {
                          echo '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
                          }elseif (preg_match('/^Yellow/',$virs[$x][$tot.'_vir_condition'])){
                          echo '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
                          }elseif (preg_match('/^Red/',$virs[$x][$tot.'_vir_condition'])){
                          echo '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
                          }
                        ?>
                        </td>
                        <!-- / Status -->

                        <!-- Tires -->
                        <td>
                        <?php
                          if (preg_match('/^Green/',$virs[$x][$tot.'_tires_overall']))
                          {
                          echo '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
                          }elseif (preg_match('/^Yellow/',$virs[$x][$tot.'_tires_overall'])){
                          echo '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
                          }elseif (preg_match('/^Red/',$virs[$x][$tot.'_tires_overall'])){
                          echo '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
                          }
                          ?>
                          </td>
                          <!-- / Tires -->

                          <!-- Reported -->
                        <td><?php echo $virs[$x]['driver_name'];?></td>
                        <!-- / Reported -->


                          <!-- Updated status -->
                        <td><div id="updated_status_<?php echo $virs[$x]['vir_itemnum'];?>">
                            <?php
                            if (preg_match('/^Open/',$virs[$x]['updated_status']))
                            {
                            echo '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
                            }elseif (preg_match('/^Work/',$virs[$x]['updated_status'])){
                            echo '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
                            }elseif ((preg_match('/^Close/',$virs[$x]['updated_status'])) || ((preg_match('/^Green/',$virs[$x][$tot.'_vir_condition']) && (preg_match('/^Green/',$virs[$x][$tot.'_tires_overall']))))){
                            echo '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
                            }
                            ?>
                          </div></td>
                          <!-- / Updated status -->

                          <!-- Type -->
                        <td>
                          <?php 
                          $tot_image = '';
                          if($tot == 'truck')
                          {
                          if ($virs[$x]['trucktype'] == "combo")
                          {
                          $tot_image = 'semismall.gif';
                          }
                          if($virs[$x]['trucktype'] == "boxtruck")
                          {
                          $tot_image = 'boxtrucksmall.gif';
                          }
                          if($virs[$x]['trucktype'] == 'sprinter')
                          {
                          $tot_image = 'sprintersmall.gif';
                          }
                          }else{
                          $tot_image = 'trailersmall.gif';
                          }
                          ?>
                        <img src="../images/<?php echo $tot_image;?>"></img></td>
                        <!-- / Type -->
                        

                        <?php
if ($_SESSION['login'] == 1)
{
?>
                        <td><form role="form" method="post" action="vir_previous.php">
                            <input type="hidden" name="hdn_vir_itemnum" id="hdn_vir_itemnum" value="<?php echo $virs[$x]['vir_itemnum'];?>"/>
                            <div class="form-group">
                              <select name="vir_status" id="vir_status_<?php echo $virs[$x]['vir_itemnum'];?>" class="form-control" onchange="submitSelect(this,<?php echo $virs[$x]['vir_itemnum'];?>,'modal_close_<?php echo $tot;?>_<?php echo $virs[$x]['vir_itemnum'];?>');">
                                <?php
if ($virs[$x]['updated_status'] == '')
{
?>
                                <option>Choose Option</option>
                                <?php
}
?>
                                <option <?php if($virs[$x]['updated_status'] == 'Open') { echo 'selected=selected';}?>>Open</option>
                                <option <?php
                                if(($virs[$x]['updated_status'] == 'Close') || ((preg_match('/^Green/',$virs[$x][$tot.'_vir_condition']) && (preg_match('/^Green/',$virs[$x][$tot.'_tires_overall'])))))
                                { 
                                   echo 'selected=selected';
                                }
                                ?>
                                >Close</option>
                                <option <?php if($virs[$x]['updated_status'] == 'Work Order Created') { echo 'selected=selected';}?>>Work Order Created</option>
                              </select>
                            </div>
                          </form></td>
                        <?php
}
?>
                       
                      </tr>
                     
                      <!-- CLOSE ISSUE MODAL -->
                    <div class="modal fade" id="modal_close_<?php echo $tot;?>_<?php echo $virs[$x]['vir_itemnum'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><span><div id='modalStatus_<?php echo $virs[$x]['vir_itemnum'];?>'></div> <?php echo $virs[$x]['vir_itemnum'];?></span></h4>
                            <input type="hidden" id="hdn_<?php echo $virs[$x]['vir_itemnum'];?>" value=""/>
                          </div>
                          <div class="modal-body">
                            <div class="row" style="padding-bottom: 5px;">
                              <div class="col-md-4">
                               <b>Work Order Number</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                               <input type="text" id="work_order_<?php echo $virs[$x]['vir_itemnum'];?>" class="form-control" placeholder="Enter work order number..."/ <?php echo ($virs[$x]['work_order'] != 0 ? 'value = "'.$virs[$x]['work_order'].'"' : null);?> >
                              </div>
                            </div>
                            <div class="row" style="padding-bottom: 5px;">
                              <div class="col-md-4">
                               <b>Repair Notes</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                               <input type="text" id="repair_notes_<?php echo $virs[$x]['vir_itemnum'];?>" class="form-control" placeholder="Enter repair notes..."<?php echo ($virs[$x]['repair_notes'] != '' ? 'value="'.$virs[$x]['repair_notes'].'"' : null);?> />
                              </div>
                            </div>
                            <div class="row" style="padding-bottom: 5px;">
                              <div class="col-md-4">
                               <b>Cost of Repair</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                               <div class="input-group">
                                 <span class="input-group-addon">$</span>
                                 <input type="text" id="cost_<?php echo $virs[$x]['vir_itemnum'];?>"class="form-control" aria-label="Amount (to the nearest dollar)"<?php echo ($virs[$x]['repair_cost'] != '' ? 'value="'.$virs[$x]['repair_cost'].'"' : null);?> />
                                 <span class="input-group-addon">.00</span>
                               </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                               <b>Repaired By:</b>
                              </div>
                              <div class="col-md-4 col-md-offset-4">
                              <select name="mechanic" id="mechanic_<?php echo $virs[$x]['vir_itemnum'];?>" class="form-control">
                              <?php
                              for($i = 0; $i < count($mechanics); $i++)
                              {
                              ?>

                               <option id="<?php echo $mechanic_id[$i];?>" <?php echo ($virs[$x]['repair_by'] == $mechanic_id[$i] ? ' selected ' : null);?>><?php echo $mechanics[$i];?></option>
                              <?php
                              }
                              ?>
                              </select>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="closeVir($('#hdn_<?php echo $virs[$x]['vir_itemnum'];?>').val(),<?php echo $virs[$x]['vir_itemnum'];?>,$('#repair_notes_<?php echo $virs[$x]['vir_itemnum'];?>').val(),$('#cost_<?php echo $virs[$x]['vir_itemnum'];?>').val(),$('#mechanic_<?php echo $virs[$x]['vir_itemnum'];?> option:selected').text(),$('#work_order_<?php echo $virs[$x]['vir_itemnum'];?>').val())">Save</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /MODAL -->
 
                      <!-- GENERAL MODAL -->
                    <div class="modal fade" id="modal_<?php echo $tot;?>_<?php echo $virs[$x]['vir_itemnum'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   closeVir
closeVir                   <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Details for <?php echo $virs[$x]['vir_itemnum'];?></h4>
                          </div>
                          <div class="modal-body">
                            <div class="row">
                              <div class="col-md-4">
                                General Notes
                              </div>
                              <div class="col-md-6">
                                <?php if ($virs[$x]['vir_finish_notes'] == '')
                                      {
                                      if ($tot == 'trailer')
                                      {
                                      echo '<span class="label label-info">See Truck Notes</span>';
                                      }else{
                                      echo '<span class="label label-danger">No Notes</span>';
                                      }
                                      }else{
                                      echo $virs[$x]['vir_finish_notes'];
                                      }
                                 ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Items
                              </div>
                              <div class="col-md-6">
                                <?php if ($virs[$x][$tot.'_vir_items'] == '')
                                      {
                                      echo '<span class="label label-danger">No Items</span>';
                                      }else{
                                      echo $virs[$x][$tot.'_vir_items'];
                                      }
                                 ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Truck / Trailer Notes
                              </div>
                              <div class="col-md-6">
                                <?php if ($virs[$x][$tot.'_vir_notes'] == '')
                                      {
                                      echo '<span class="label label-danger">No Notes</span>';
                                      }else{
                                      echo $virs[$x][$tot.'_vir_notes'];
                                      }
                                 ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Tire Notes
                              </div>
                              <div class="col-md-6">
                                <?php if ($virs[$x][$tot.'_tires_notes'] == '')
                                      {
                                      echo '<span class="label label-danger">No Notes</span>';
                                      }else{
                                      echo $virs[$x][$tot.'_tires_notes'];
                                      }
                                 ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-4">
                                Tires Condition
                              </div>
                              <div class="col-md-6">
                                <?php 
                                  echo "<br>\n";
                                  echo '<strong>Steer (driver)</strong> '.$virs[$x][$tot.'_tires_driverside_steer']."<br>\n";
                                  echo '<strong>Steer (passenger)</strong> '.$virs[$x][$tot.'_tires_passenger_steer']."<br>\n";
                                  echo '<strong>Axel 1 (driver)</strong> '.$virs[$x][$tot.'_tires_driverside_ax1front']."<br>\n";
                                  echo '<strong>Axel 1 (passenger)</strong> '.$virs[$x][$tot.'_tires_passenger_ax1front']."<br>\n";
                                  echo '<strong>Axel 2 (driver)</strong> '.$virs[$x][$tot.'_tires_driverside_ax2rear']."<br>\n";
                                  echo '<strong>Axel 2 (passenger)</strong> '.$virs[$x][$tot.'_tires_passenger_ax2rear']."<br>\n";
                                 ?>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /MODAL -->
                    
                      </form>
                    
<?php
}
?>
                  </table>
                </div></td>
            </tr>
            <tr>
              <td colspan="2">
          <?php
                 if ($_SESSION['login'] == 1)
                 {
                 ?>
          <form enctype="multipart/form-data" role="form" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <div>
            <div class="input-daterange input-group" id="datepicker" style="width: 25%; vertical-align:top;">
              <input type="text" class="input-sm form-control datepicker" name="start" id="startDate" data-date-format="mm/dd/yyyy"/ required>
              <span class="input-group-addon">to</span>
              <input type="text" class="input-sm form-control datepicker" name="end" id="endDate" data-date-format="mm/dd/yyyy"/ required>
            </div>
            <input type="submit" name="submit" class="btn btn-primary" value="Export" style="margin-top: 5px;">
          </div>
          </form>
          <?php
                 }
                 ?>
              </td>
            </tr>

          </tbody>
        </table>
      </div>
      <!-- ./box-body -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  <!-- Main row -->
  <div class="row">
    <!-- Left col -->
    <div class="col-md-8">
      <div class="row">
      </div>
      <!-- /.col -->
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
  <div class='control-sidebar-bg'>
  </div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo HTTP;?>/plugins/jQuery/jQuery-2.1.4.min.js">
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo HTTP;?>/bootstrap/js/bootstrap.min.js" type="text/javascript">
</script>
<!-- Slimscroll -->
<script src="<?php echo HTTP;?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript">
</script>
<!-- FastClick -->
<script src='<?php echo HTTP;?>/plugins/fastclick/fastclick.min.js'>
</script>
<!-- AdminLTE App -->
<script src="<?php echo HTTP;?>/dist/js/app.min.js" type="text/javascript">
</script>

<!-- Demo -->
<script src="<?php echo HTTP;?>/dist/js/demo.js" type="text/javascript">
</script>
<script>
function closeVir(status,item,notes,cost,repaired_by,work_order)
{  
  $.post( "viractions.php", { vir_item: item, vir_status: status, repair_notes: notes, repair_cost: cost, repair_by: repaired_by, work_order_no: work_order })
  .success(function(data) {
  x = '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
  $("#updated_status_"+item).html(x);
  })
  .error(function(data) { console.log(data); });
}

function submitSelect(sel,item,modal)
{
if (sel.value == "Close")
{
  $('#modalStatus_'+item).html('Close');
  $('#hdn_'+item).val('Close');
  $('#'+modal).modal('show');
}else if(sel.value == "Work Order Created"){
  $('#modalStatus_'+item).html('Create work order for:');
  $('#hdn_'+item).val('Work Order Created');
  $('#cost_'+item).val('1');
  $('#'+modal).modal('show');
}else{
  $.post( "viractions.php", { vir_item: item, vir_status: sel.value })
  .success(function() {
  if (sel.value == "Open")
  {
  x = '<span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true" style="color: #dd4b39;"></span>';
  }
  if (sel.value == "Work Order Created")
  {
  x = '<span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true" style="color: #f39c12;"></span>';
  }
  if (sel.value == "Close")
  {
  x = '<span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true" style="color: #00A65A;"></span>';
  }
  $("#updated_status_"+item).html(x);
  })
  .error(function(data) { console.log(data); });
  }
}
</script>
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
<script type="text/javascript">
  var today = new Date();
  var minus30days = new Date();
  minus30days.setDate(minus30days.getDate() - 30 );
  <?php
    if (empty($_GET['start']))
    {
      $startDate = "minus30days.getMonth() + 1 + '/' + minus30days.getDate() + '/' + minus30days.getFullYear()";
    }else{
      $startDate = "'$_GET[start]'";
    }
    if (empty($_GET['end']))
    {
      $endDate = "today.getMonth() + 1 + '/' + today.getDate() + '/' + today.getFullYear()";
    }else{
      $endDate = "'$_GET[end]'";
    }
  ?>
    $('#startDate').val(<?php echo $startDate;?>)
    $('#endDate').val(<?php echo $endDate;?>)

$(document).ready(function() {
  // Default visibility for users
  <?php if(isset($_GET['vir_itemnum'])) { ?>
    $('[name="closed_vir"]').show();
    $('[name="notclosed_vir"]').show();
  <?php }else{ ?>
    $('[name="closed_vir"]').hide();
    $('[name="notclosed_vir"]').show();
  <?php } ?>

  $("#allvir").click(function() {
    $('[name="closed_vir"]').show();
    $('[name="notclosed_vir"]').show();
  });
  $("#openvir").click(function() {
    $('[name="closed_vir"]').hide();
    $('[name="not_closed"]').show();
  });
  $("#notclosedvir").click(function() {
    $('[name="closed_vir"]').hide();
    $('[name="not_closed"]').show();
  });
});

// Ajax calls for search
  $("#btn_display_results").click(function() {
    // If no po was specified then make sure we enter a date range
    if (($("#vir_search_po").val().length < 1) && ($("#vir_search_wo").val().length < 1)) {
      if ($("#vir_search_sd").val().length < 1) {
          $("#search_alert").html('No date entered.  This may return a large amount of data!');
          $("#search_alert").addClass('alert-warning');
         $("#search_alert").show();
      }
    }

    // Make sure both a begin and end date are selected
    if (($("#vir_search_sd").val().length > 1) && ($("#vir_search_ed").val().length < 1)) {
        $("#search_alert").html('Must choose a beginning AND ending date!');
        $("#search_alert").show();
        return false;
    }
    if (($("#vir_search_sd").val().length < 1) && ($("#vir_search_ed").val().length > 1)) {
        $("#search_alert").html('Must choose a beginning AND ending date!');
        $("#search_alert").show();
        return false;
    }

    function searchResults(callBack) {
      $.ajax({
       method: "GET",
       url: "searchvir.php",
       data: {
              vir_search_po: $("#vir_search_po").val(),
              vir_search_wo: $("#vir_search_wo").val(),
              vir_search_un: $("#vir_search_un").val(),
              vir_search_vd: $("#vir_search_vd").val(),
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
            console.log(data);
            var output = `<table id="tbl_search_results" class="table">
                            <tr>
                              <td>VIR PO</td>
                              <td>Work Order</td>
                              <td>Unit #</td>
                              <td>VIR Date</td>
                           </tr>`;


            var json = jQuery.parseJSON( data );
            for(var i = 0; i < json.length; i++) {
              var obj = json[i];
               output = output + `<tr>
                              <td><a href="<?php echo HTTP;?>/pages/dispatch/vir_previous.php?vir_itemnum=`+obj.vir_itemnum+`" target="_blank">`+obj.vir_itemnum+`</a></td>
                              <td>`+obj.work_order+`</td>
                              <td>`+obj.truck_number+`</td>
                              <td>`+obj.insp_date+`</td>
                           </tr>`;
            }
            output = output + '</table>';
            $("#search_results").html(output);
          } else {
            console.log(data);
            //$('.rtnMsg').html("Opps! Ajax Error");
          }
        });
  });

</script>
</body>
</html>
