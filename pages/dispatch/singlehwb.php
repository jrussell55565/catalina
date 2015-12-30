<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];
$hwbnumber = $_GET['hwb'];

# If GET[recordid] was set then use that as the predicate,
# otherwise use hwbNumber.
if (isset($_GET['recordid']))
{
    $recordID = $_GET['recordid'];
    $predicate = "WHERE recordId=\"$recordID\"";
}else{
    if ($hwbnumber == '')
    {
        $predicate = "WHERE hawbNumber = \"NULL\"";
    }else{
        $predicate = "WHERE hawbNumber = \"$hwbnumber\"";
    }
}

$sql = "select * from dispatch $predicate";
$sql = mysql_query($sql);

# Get the count:
$queryCount = mysql_num_rows($sql);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>HWB Info</title>
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

<!-- Custom CSS -->
<link href="<?php echo HTTP;?>/dist/css/catalina.css" rel="stylesheet" type="text/css" />
</head>
<body class="skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?>

<!-- =============================================== -->

<?php
$row = mysql_fetch_array($sql, MYSQL_BOTH);
$disabledButton = '';

if ($row['status'] == "Delivered")
{
    $disabledButton = 'disabled';
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> HWB #<?php echo "$hwbnumber"; ?> <small>Status:
    <?php if($row['status'] == '') { echo "Unknown"; }else{ echo "$row[status]";}?>
    </small> </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="orders.php">Orders</a></li>
    <li class="active">Single HWB</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

<!-- Default box -->
<div class="row">
<div class="col-md-8">
<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Actions</h3>
  </div>
  <div class="box-body">
    <?php if ($queryCount > 0) { # Display the table ?>
    <table id="orderActions" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>Pickup</th>
          <th>Delivery</th>
        </tr>
      <tbody>
        <tr>
          <td><form method="GET" action="tracepu.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_traceNotePickup"
                            value="Trace Note Pick Up" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
          <td><form method="GET" action="tracedel.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_traceNoteDelivery"
                            value="Trace Note Delivery" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
        </tr>
        <tr>
          <td><form method="GET" action="acceptpu.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_acceptPickup"
                            value="Accept Pick Up" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
          <td><form method="GET" action="acceptdel.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_acceptDelivery"
                            value="Accept Delivery" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
        </tr>
        <tr>
          <td><form method="GET" action="arriveship.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_arrivedShipper"
                            value="Arrived to shipper" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
          <td><form method="GET" action="arrivecon.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_arrivedConsignee"
                           value="Arrived to consignee" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
        </tr>
        <tr>

          <td><form method="GET" action="attemptpu.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_attemptPu"
                          value="Attempt Pickup" <?php echo $disabledButton;?>>
            </input>
          </form>
         </td
          ><td><form method="GET" action="attemptdel.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
            <input type="submit" class="btn btn-danger btn-sm" id="btn_attemptDel"
                          value="Attempt Delivery" <?php echo $disabledButton;?>>
            </input>
            </form>
          </td>
        </tr>
        <tr>
          <td><form method="GET" action="puconfirmed.php">
              <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
              <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
              <input type="submit" class="btn btn-danger btn-sm" id="btn_pickedUp"
                            value="Picked Up" <?php echo $disabledButton;?>>
              </input>
            </form>
          </td>
          <td><form method="GET" action="delconfirmed.php">
            <input type="hidden" id="hwb" name="hwb"
                           value="<?php echo $row['hawbNumber'];?>">
            <input type="hidden" id="recordid" name="recordid"
                            value="<?php echo $row['recordID'];?>">
              <input type="submit" class="btn btn-danger btn-sm" id="btn_delivered"
                           value="Delivered" <?php echo $disabledButton;?>>
              </input>
            </form></td>
        </tr>
        <tr>
          <td colspan="2"><form role="form" class="form-inline" method="post" action="export.php">
              <div>
                <label>Other Actions (Pickup and Delivery)</label>
              </div>
              <div class="input-group">
                <div class="input-group-btn" style="padding-right: 5px;">
                  <input type="submit" class="btn btn-primary" value="Change Status" <?php echo $disabledButton;?>>
                  <input type="hidden" id="recordid" name="recordid" value="<?php echo $row['recordID'];?>">
                  </input>
                </div>
                <!-- /btn-group -->
                <select class="form-control" name="sel_quickStatus" id="sel_quickStatus">
                  <option>In Transit</option>
                  <option>On Dock PHX</option>
                  <option>On Dock TUS</option>
                  <option>Trailer Dropped</option>
                  <option>Reject PU DEL</option>
                  <option>Refused</option>
                  <option selected>Freight At Dock</option>
                </select>
              </div>
            </form></td>
        </tr>
    </table>
    <?php }else{ ?>
    <div class="alert alert-danger" role="alert"><?php if ($hwbnumber != ''){?>
    HWB Not Found. <?php }?> Request shipment below.</div>
    <form id="SingleHWBEmail" name="SingleHWBEmail" method="post" action="email.php">
      <span><label for="txt_dispatch">Please enter shipper or consignee name, or approximate location</label><br>
      <input type="text" name="txt_dispatch" id="txt_dispatch" class="input-group" required/>
      </span><br>
      <input type="submit" name="btn_submit" id="btn_submit" value="Email Dispatch" class="btn btn-primary"/>
      <input type="hidden" id="hawbsearch" name="hawbsearch" value='<?php echo "$_GET[hwb]";?>' />
    </form>
    <?php } ?>
  </div>
</div>
<div class="box collapsed-box">
      <div class="box-header">
        <h3 class="box-title">HWB Trace Notes</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div id="tracenotesDetails_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
          <div class="row">
            <div class="col-md-8" style="width: 100%;">
          <div class="table-responsive">
              <div>
              <?php
              $sql = "SELECT * FROM driverexport WHERE hawbNumber = \"$hwbnumber\"
                      order by id desc";
              ?>
              <table id="tracenotesDetails" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="tracenotesDetails_info">
                <tbody>
                  <tr role="row" class="odd">
                    <td><label>Name</label></td>
                    <td><label>Date</label></td>
                    <td><label>State Change</label></td>
                    <td><label>Trace Notes</label></td>
                    <td><label>Accessorials</label></td>
                    <td><label>Pieces / Pallets</label></td>
                  </tr>
              <?php
              $result = mysql_query($sql);
              while ($export_row = mysql_fetch_array($result, MYSQL_BOTH))
              {
              ?>
                  <tr role="row" class="even">
                    <td><?php echo "$export_row[driver]";?></td>
                    <td><?php echo "$export_row[date]";?></td>
                    <td><?php echo "$export_row[status]";?></td>
                    <td><?php echo "$export_row[trace_notes]";?></td>
                    <td><?php echo "$export_row[accessorials]";?></td>
                    <td><?php echo "$export_row[pieces] / $export_row[pallets]";?></td>
                  </tr>
               <?php
               }
               ?>
                </tbody>
              </table>
            </div>
            </div>
            </div>
          </div>
        </div>
      </div>
<div class="box collapsed-box">
  <div class="box-header">
    <h3 class="box-title">Pickup Details</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <div id="pickupDetails_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-md-8">
          <div class="table-responsive">
            <table id="pickupDetails" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="pickupDetails_info">
              <tbody>
                <tr role="row" class="odd">
                  <td><label>HWB Date</label></td>
                  <td><?php echo "$row[hawbDate]"; ?>
                </tr>
                <tr role="row" class="even">
                  <td><label>Ready / Close</label></td>
                  <td><?php echo "$row[readyTime]"; ?> / <?php echo "$row[closeTime]"; ?>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Shipper</label></td>
                  <td><?php echo "$row[shipperName]"; ?>
                </tr>
                <tr role="row" class="even">
                  <td><label>Address</label></td>
                  <td><ul class="list-group">
                      <li class="list-group-item"><?php echo "$row[shipperAddress1]"; ?></li>
                      <li class="list-group-item"><?php echo "$row[shipperAddress2]"; ?></li>
                      <li class="list-group-item"><?php echo "$row[shipperCity]"; ?></li>
                      <li class="list-group-item"><?php echo "$row[shipperState]"; ?></li>
                      <li class="list-group-item"><?php echo "$row[shipperPostalCode]"; ?></li>
                    </ul></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Attention</label></td>
                  <td><?php echo "$row[shipperAttention]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Reference</label></td>
                  <td><?php echo "$row[shipperReference]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Contact</label></td>
                  <td><?php echo "$row[shipperContact]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Phone</label></td>
                  <td><?php echo "$row[shipperPhone]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Pickup Instructions</label></td>
                  <td><?php echo "$row[puRemarks]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Pieces</label></td>
                  <td><?php echo "$row[pieces]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Pallets</label></td>
                  <td><?php echo "$row[pallets]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Weight</label></td>
                  <td><?php echo "$row[weight]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Appointment Notes</label></td>
                  <td><?php echo "$row[appNotes]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Shipper Assembly</label></td>
                  <td><?php echo "$row[shipperAssembly]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Agent Code</label></td>
                  <td><?php echo "$row[puAgentCode]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Agent Name</label></td>
                  <td><?php echo "$row[puAgentName]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Driver Number</label></td>
                  <td><?php echo "$row[puAgentDriverPhone]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>Driver Name</label></td>
                  <td><?php echo "$row[puAgentDriverName]"; ?></td>
                </tr>
                <tr role="row" class="even">
                  <td><label>Zone</label></td>
                  <td><?php echo "$row[puZone]"; ?></td>
                </tr>
                <tr role="row" class="odd">
                  <td><label>MessageDate</label></td>
                  <td><?php echo "$row[messageDate]"; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
  </div>
  
  <div class="box collapsed-box">
    <div class="box-header">
      <h3 class="box-title">Delivery Details</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div id="deliveryDetails_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-md-8">
            <div class="table-responsive">
              <table id="deliveryDetails" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="deliveryDetails_info">
                <tbody>
                  <tr role="row" class="odd">
                    <td><label>DUE DATE</label></td>
                    <td><?php echo "$row[dueDate]"; ?>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Due Time</label></td>
                    <td><?php echo "$row[dueTime]"; ?>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Consignee</label></td>
                    <td><?php echo "$row[consigneeName]"; ?>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Address</label></td>
                    <td><ul class="list-group">
                        <li class="list-group-item"><?php echo "$row[consigneeAddress1]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[consigneeAddress2]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[consigneeCity]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[consigneeState]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[consigneePostalCode]"; ?></li>
                      </ul></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Attention</label></td>
                    <td><?php echo "$row[consigneeAttention]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Reference</label></td>
                    <td><?php echo "$row[consigneeReference]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Contact</label></td>
                    <td><?php echo "$row[consigneeContact]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Phone</label></td>
                    <td><?php echo "$row[consigneePhone]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Delivery Instructions</label></td>
                    <td><?php echo "$row[delRemarks]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Delivery Appt Required</label></td>
                    <td><?php echo "$row[appDate]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Pieces</label></td>
                    <td><?php echo "$row[pieces]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Pallets</label></td>
                    <td><?php echo "$row[pallets]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Weight</label></td>
                    <td><?php echo "$row[weight]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Appt Time</label></td>
                    <td><?php echo "$row[appTime]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Appointment Notes</label></td>
                    <td><?php echo "$row[appNotes]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Cons Assmb</label></td>
                    <td><?php echo "$row[consigneeAssembly]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Agent Code</label></td>
                    <td><?php echo "$row[delAgentCode]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Agent Name</label></td>
                    <td><?php echo "$row[delAgentName]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Driver Number</label></td>
                    <td><?php echo "$row[delAgentDriverPhone]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Driver Name</label></td>
                    <td><?php echo "$row[delAgentDriverName]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Zone</label></td>
                    <td><?php echo "$row[delZone]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Message Time</label></td>
                    <td><?php echo "$row[messageTime]"; ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box-body -->
    </div>
    <div class="box collapsed-box">
      <div class="box-header">
        <h3 class="box-title">Customer Details</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div id="customerDetails_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
          <div class="row">
            <div class="col-md-8">
              <div class="table-responsive"> </div>
              <table id="customerDetails" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="deliveryDetails_info">
                <tbody>
                  <tr role="row" class="odd">
                    <td><label>Name</label></td>
                    <td><?php echo "$row[CustomerName]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Phone</label></td>
                    <td><?php echo "$row[CustomerPhone]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Address</label></td>
                    <td><ul class="list-group">
                        <li class="list-group-item"><?php echo "$row[CustomerAddress1]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[CustomerAddress2]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[CustomerCity]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[CustomerState]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[CustomerPostalCode]"; ?></li>
                      </ul></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <div class="box collapsed-box">
      <div class="box-header">
        <h3 class="box-title">Bill-To Details</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>
        </div>
      </div>
      <div class="box-body">
        <div id="billToDetails_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
          <div class="row">
            <div class="col-md-8">
              <div class="table-responsive"> </div>
              <table id="billToDetails" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="deliveryDetails_info">
                <tbody>
                  <tr role="row" class="odd">
                    <td><label>Name</label></td>
                    <td><?php echo "$row[BillToName]"; ?></td>
                  </tr>
                  <tr role="row" class="even">
                    <td><label>Phone</label></td>
                    <td><?php echo "$row[BillToPhone]"; ?></td>
                  </tr>
                  <tr role="row" class="odd">
                    <td><label>Address</label></td>
                    <td><ul class="list-group">
                        <li class="list-group-item"><?php echo "$row[BillToAddress1]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[BillToAddress2]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[BillToCity]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[BillToState]"; ?></li>
                        <li class="list-group-item"><?php echo "$row[BillToPostalCode]"; ?></li>
                      </ul></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
     </div>
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
<!-- SlimScroll -->
<script src="<?php echo HTTP;?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo HTTP;?>/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?php echo HTTP;?>/dist/js/app.min.js" type="text/javascript"></script>

<!-- Demo -->
<script src="<?php echo HTTP;?>/dist/js/demo.js" type="text/javascript"></script>
</body>
</html>
