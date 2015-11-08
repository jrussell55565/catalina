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

if ($_GET['action'] == 'loginas')
{
  $_SESSION['userid'] = $_GET['username'];
  $_SESSION['username'] = $_GET['username'];
  $_SESSION['drivername'] = $_GET['drivername'];
  $_SESSION['login'] = 2;
  header("Location: /pages/main/index.php");
}

# If we're submitted a POST request and it has an email or vtext then
# we'll send out a test message
if (isset($_POST['testVtext']))
{
  sendEmail($_POST['testVtext'],"Testing Vtext","This is a test message.");
}
if (isset($_POST['testEmail']))
{
  sendEmail($_POST['testEmail'],"Testing Vtext","This is a test message.");
}

# Let's do some form processing
if(isset($_POST['submit'])) 
{ 

$id = $_POST['id'];
$formDrivername = $_POST['fname'] . $_POST['lname'];
if (empty($_POST['fname'])) { $fname = 'NULL'; }else{ $fname = "\"$_POST[fname]\""; }
if (empty($_POST['mname'])) { $mname = 'NULL'; }else{ $mname = "\"$_POST[mname]\"";}
if (empty($_POST['lname'])) { $lname = 'NULL'; }else{ $lname = "\"$_POST[lname]\"";}
if (empty($_POST['status'])) { $status = 'NULL'; }else{ $status = "\"$_POST[status]\"";}
if (empty($_POST['role'])) { $role = 'NULL'; }else{ $role = "\"$_POST[role]\"";}
if (empty($_POST['office'])) { $office = 'NULL'; }else{ $office = "\"$_POST[office]\"";}
if (empty($_POST['addr1'])) { $addr1 = 'NULL'; }else{ $addr1 = "\"$_POST[addr1]\"";}
if (empty($_POST['addr2'])) { $addr2 = 'NULL'; }else{ $addr2 = "\"$_POST[addr2]\"";}
if (empty($_POST['city'])) { $city = 'NULL'; }else{ $city = "\"$_POST[city]\"";}
if (empty($_POST['state'])) { $state = 'NULL'; }else{ $state = "\"$_POST[state]\"";}
if (empty($_POST['zip'])) { $zipcode = 'NULL'; }else{ $zipcode = "\"$_POST[zip]\"";}
if (empty($_POST['jobTitle'])) { $title = 'NULL'; }else{ $title = "\"$_POST[jobTitle]\"";}
if (empty($_POST['email'])) { $email = 'NULL'; }else{ $email = "\"$_POST[email]\"";}
if (isset($_POST['emailEnabled']) && $_POST['emailEnabled'] == 'on') { $emailEnabled = '"1"'; }else{ $emailEnabled = '"0"';}
if (empty($_POST['vtext'])) { $vtext = 'NULL'; }else{ $vtext = "\"$_POST[vtext]\"";}
if (isset($_POST['vtextEnabled']) && $_POST['vtextEnabled'] == 'on') { $vtextEnabled = '"1"'; }else{ $vtextEnabled = '"0"';}
if (empty($_POST['quietTimeVal1'])) { $quiet_time_begin = 'NULL'; }else{ $quiet_time_begin = "\"$_POST[quietTimeVal1]\"";}
if (empty($_POST['quietTimeVal2'])) { $quiet_time_end = 'NULL'; }else{ $quiet_time_end = "\"$_POST[quietTimeVal2]\"";}
if (empty($_POST['ssn'])) { $ssn = 'NULL'; }else{ $ssn = "\"$_POST[ssn]\"";}
if (empty($_POST['dob'])) { $dob = 'NULL'; }else{ $dob = "str_to_date('$_POST[dob]', '%m/%d/%Y')";}
if (empty($_POST['driverLicense'])) { $driver_license_n = 'NULL'; }else{ $driver_license_n = "\"$_POST[driverLicense]\"";}
if (empty($_POST['driverLicenseExpire'])) { $driver_license_exp = 'NULL'; }else{ $driver_license_exp = "str_to_date('$_POST[driverLicenseExpire]', '%m/%d/%Y')";}
if (empty($_POST['mobilePhone'])) { $driverid = 'NULL'; }else{ $driverid = "\"$_POST[mobilePhone]\"";}
if (empty($_POST['startDate'])) { $start_dt = 'NULL'; }else{ $start_dt = "str_to_date('$_POST[startDate]', '%m/%d/%Y')";}
if (empty($_POST['departureDate'])) { $depart_dt = 'NULL'; }else{ $depart_dt = "str_to_date('$_POST[departureDate]', '%m/%d/%Y')";}
if (empty($_POST['departureReason'])) { $depart_reason = 'NULL'; }else{ $depart_reason = "\"$_POST[departureReason]\"";}
if (empty($_POST['username'])) { $formUsername = 'NULL'; }else{ $formUsername = "\"$_POST[username]\"";}
if (empty($_POST['password'])) { $password = 'NULL'; }else{ $password = "\"$_POST[password]\"";}
if (empty($_POST['medCardExpire'])) { $med_card_exp = 'NULL'; }else{ $med_card_exp = "str_to_date('$_POST[medCardExpire]', '%m/%d/%Y')";}
if (empty($_POST['salary'])) { $salary = 'NULL'; }else{ $salary = "\"$_POST[salary]\"";}
if (empty($_POST['emergencyContact'])) { $emerg_contact_name = 'NULL'; }else{ $emerg_contact_name = "\"$_POST[emergencyContact]\"";}
if (empty($_POST['emergencyPhone'])) { $emerg_contact_phone = 'NULL'; }else{ $emerg_contact_phone = "\"$_POST[emergencyPhone]\"";}
if (empty($_POST['tsa'])) { $tsa_sta = 'NULL'; }else{ $tsa_sta = "\"$_POST[tsa]\"";}
if (empty($_POST['contract'])) { $contract = 'NULL'; }else{ $contract = "\"$_POST[contract]\"";}
if (empty($_POST['fuelcard'])) { $fuelcard = 'NULL'; }else{ $fuelcard = "\"$_POST[fuelcard]\"";}
if (empty($_POST['notes'])) { $notes = 'NULL'; }else{ $notes = "\"$_POST[notes]\"";}
if (empty($_POST['tsName'])) { $tsName = 'NULL'; }else{ $tsName = "\"$_POST[tsName]\"";}
if (empty($_POST['tsPhone'])) { $tsPhone = 'NULL'; }else{ $tsPhone = $_POST['tsPhone'];}

# Image Uploads
if (! empty($_FILES["fileToUpload"]["name"]))
{
  # File upload logic
  $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/dist/img/userimages/";
  $target_file = $target_dir . str_replace('"','',$formUsername) . "_avatar";
  $uploadOk = 0;
  // Check if image file is a actual image or fake image

  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check === false) {
    header("Location: /pages/dispatch/admin/users.php?error=upload");
  }else{
    $uploadOk = 1;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    header("Location: /pages/dispatch/admin/users.php?error=size");
  }else{
    $uploadOk = 1;
  }
  // Allow certain file formats
  if (exif_imagetype($_FILES["fileToUpload"]["tmp_name"]) != IMAGETYPE_GIF
  && exif_imagetype($_FILES["fileToUpload"]["tmp_name"]) != IMAGETYPE_JPEG
  && exif_imagetype($_FILES["fileToUpload"]["tmp_name"]) != IMAGETYPE_PNG
  ) {
    header("Location: /pages/dispatch/admin/users.php?error=type");
  }else{
    $uploadOk = 1;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 1)
  {
    # resize the image
    $resizedImage = new Imagick($_FILES["fileToUpload"]["tmp_name"]);
    $resizedImage->resizeImage(160,0,Imagick::FILTER_LANCZOS,1);
    $resizedImage->writeImage($_FILES["fileToUpload"]["tmp_name"]);
    $resizedImage->destroy(); 
    if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
    {
      header("Location: /pages/dispatch/admin/users.php?error=move");
    }
  }
}

  if ($_POST['submit'] == 'Add')
  {
    # Insert the record first
    $sql = "INSERT INTO users
           (username)
           VALUES
           ($formUsername)";
           #print "$sql<br>\n\n";
           mysql_query($sql);

    # Now get the id so we can update
    $sql = "SELECT id FROM users WHERE username = $formUsername";
    #print "$sql<br>\n\n";
    $id = mysql_result(mysql_query("$sql"),0);
    # Create the drivername as a concat of fname and lname
    $sql = 'UPDATE users SET drivername = "'.$formDrivername.'" WHERE id = '.$id;
    #print "$sql<br>\n\n";
    mysql_query($sql);
  }

  $sql = "UPDATE users SET
   fname = $fname,
   mname = $mname,
   lname = $lname,
   status = $status,
   role = $role,
   office = $office,
   addr1 = $addr1,
   addr2 = $addr2,
   city = $city,
   state = $state,
   zipcode = $zipcode,
   title = $title,
   email = $email,
   emailupdate = $emailEnabled,
   vtext = $vtext,
   vtextupdate = $vtextEnabled,
   quiet_time_begin = $quiet_time_begin,
   quiet_time_end = $quiet_time_end,
   ssn = $ssn,
   dob = $dob,
   driver_license_n = $driver_license_n,
   driver_license_exp = $driver_license_exp,
   driverid = $driverid,
   start_dt = $start_dt,
   depart_dt = $depart_dt,
   depart_reason = $depart_reason,
   username = $formUsername,
   password = $password,
   med_card_exp = $med_card_exp,
   salary = $salary,
   emerg_contact_name = $emerg_contact_name,
   emerg_contact_phone = $emerg_contact_phone,
   tsa_sta = $tsa_sta,
   notes = $notes,
   ts_phone = $tsPhone,
   ts_name = $tsName
  WHERE id = $id";
  #print "$sql<br>\n\n";
  mysql_query($sql);

# PDF Uploads
if (! empty($_FILES["contractUpload"]["name"]))
{
  # File upload logic
  $target_dir = "/dist/img/userpdf/";
  $target_file = $target_dir . time() . "_contract.pdf";
  # Check that the mime type is pdf
  if ($_FILES["contractUpload"]["type"] == "application/pdf")
  {
    if (move_uploaded_file($_FILES["contractUpload"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file))
    {
      $sql = "UPDATE users SET
         contract = '$target_file'
         WHERE id = $id";
         mysql_query($sql);
    }
  }
}

if (! empty($_FILES["fuelUpload"]["name"]))
{
  # File upload logic
  $target_dir = "/dist/img/userpdf/";
  $target_file = $target_dir . time() . "_fuel.pdf";
  # Check that the mime type is pdf
  if ($_FILES["fuelUpload"]["type"] == "application/pdf")
  {
    if (move_uploaded_file($_FILES["fuelUpload"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $target_file))
    {
      $sql = "UPDATE users SET
         fuelcard = '$target_file'
         WHERE id = $id";
         mysql_query($sql);
    }
  }
}

}
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
          <h1>
            Admin Dashboard</h1>
            
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuadminanimation.php');?>

<!-- End Animated Top Menu -->
          
  
          
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">CSA Data</h3>
<span class="label label-default" style="background-color: transparent;">
<div class="box-tools pull-right">
            <ul class="pagination pagination-sm no-margin pull-right">
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=1">1</a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=2">2</a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=3">3</a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=4">4</a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?page=5">5</a></li>
</ul>
</span>
          </div>
                </div><!-- /.box-header -->
                <div class="box-body">
<table class="table table-striped">
 <thead>
  <tr>
    <?php
      # Defaults
      $orderName = 'desc';
      $glyphName = "top";
      $orderStatus = 'desc';
      $glyphStatus = "top";
      $orderSql = "ORDER BY drivername ASC";

      if ($_GET['sort'] == 'name')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderName = 'asc';
          $glyphName = "bottom";
          $orderSql = "ORDER BY drivername DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderName = 'desc';
          $glyphName = "top";
          $orderSql = "ORDER BY drivername ASC";
        }
      }
      if ($_GET['sort'] == 'status')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderStatus = 'asc';
          $glyphStatus = "bottom";
          $orderSql = "ORDER BY status DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderStatus = 'desc';
          $glyphStatus = "top";
          $orderSql = "ORDER BY status ASC";
        }
      }
    ?>
    <th>Name</th>
    <th>Score</th>
  </tr>
 </thead>
 <tbody>
<?php
# If non-admin logs in then only show their info
if ($_SESSION['login'] == 2)
{
  $predicate = " WHERE username = '$_SESSION[username]'";
}
$sql = "
select distinct first_name,last_name from csadata ORDER BY 1";
  #print "$sql<br>\n\n";

$sql = mysql_query($sql);
while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
{
?>
<tr>
<td>
<div style="float:left;width:20%;"><?php echo $row['first_name'] . " " . $row['last_name'];?></div>
<div style="float:right;width:80%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse" 
  href="#<?php echo $row['last_name'];?>_details"aria-expanded="false" aria-controls="<?php echo $row['last_name'];?>_details">
  </a></div>
</td>
<td><?php echo $row['date'];?></td>
<td><?php echo $row['score'];?></td>
</tr>
<tr class="collapse" id="<?php echo $row['last_name'];?>_details">
<td colspan="9">
  <div class="well">
<form enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table>
<tr>
<!-- <td rowspan="2">
   <div><img style="display: block; margin: 0 auto;" 
         src="<?php #if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $row['username'] . "_avatar")) { echo HTTP."/dist/img/userimages/" . $row['username'] . "_avatar";}else{ echo HTTP."/dist/img/avatar.png"; }?>"/></div>
 </td> -->
 <?php $sqlDetails = "SELECT * from csadata WHERE first_name = '" . $row['first_name'] . "' AND last_name = '" . $row['last_name'] . "'";
       $sqlDetails = mysql_query($sqlDetails);
       while ($rowDetails = mysql_fetch_array($sqlDetails, MYSQL_BOTH))
       { 
 ?>
 <td style="padding: 5px">
  <label for="status">Violation Date</label>
  <input type="text" class="form-control" name="lname" id="lname" placeholder="" value="<?php echo $rowDetails['date'];?>" readonly>
 </td>
 <td style="padding: 5px">
  <label for="status">Violation Category</label>
  <input type="text" class="form-control" name="lname" id="lname" placeholder="" value="<?php echo $rowDetails['basic'];?>" readonly>
 </td>
 <td style="padding: 5px">
  <label for="addr1">Violation Group</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $rowDetails['violation_group'];?>" readonly>
 </td>
 <td style="padding: 5px">
  <label for="addr1">Violation Code</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $rowDetails['code'];?>" readonly>
 </td>
 <td style="padding: 5px">
  <label for="addr1">Violation Weight</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $rowDetails['violation_weight'];?>" readonly>
 </td>
 <td style="padding: 5px">
  <label for="addr1">Time Weight</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $rowDetails['time_weight'];?>" readonly>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Description</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $rowDetails['description'];?>" readonly>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Co-Driver</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $rowDetails['co_driver_first_name'] . ' ' . $rowDetails['co_driver_last_name'];?>" readonly>
 </td>
 <td style="padding: 5px" colspan="2">
  <label for="addr1">Score</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="1" readonly>
 </td>
</tr>
 <?php
 }
 mysql_free_result($sqlDetails);
 ?>
   <tr>
     <td style="padding: 5px">
       <input type="submit" name="submit" class="btn btn-primary" value="Submit">
     </td>
    </tr>
   </table>
   </form>
  </div>
</td>
</tr>
<?php
                      }
                      mysql_free_result($sql);
?>
</tbody>
</table>
                </div><!-- ./box-body -->
               </div><!-- /.col -->
          </div><!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
              <div class="row">
              </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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

<!-- Change Chevron -->
<script>
//$('.collapse').on('shown.bs.collapse', function(){
//$(this).parent().find(".glyphicon-chevron-right").removeClass("glyphicon-chevron-right").addClass("glyphicon-minus");
//}).on('hidden.bs.collapse', function(){
//$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
//});
</script>

</body>
</html>
