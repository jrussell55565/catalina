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
  $statement = "SELECT username,
                       drivername,
                       fname,
                       lname
                FROM users WHERE employee_id = '" . mysql_real_escape_string($_GET['employee_id']) . "'";

  $results = mysql_query($statement);
  $row = mysql_fetch_array($results, MYSQL_BOTH);
  $_SESSION['userid'] = $row['username'];
  $_SESSION['username'] = $row['username'];
  $_SESSION['drivername'] = $row['drivername'];
  $_SESSION['fname'] = $row['fname'];
  $_SESSION['lname'] = $row['lname'];
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
# Strip non-digits from the phone
$tsPhone = preg_replace('/\D/','',$tsPhone);

if (empty($_POST['vir_pretrip_time'])) { $vir_pretrip_time = 'NULL'; }else{ $vir_pretrip_time = "\"$_POST[vir_pretrip_time]\"";}
if (empty($_POST['vir_posttrip_time'])) { $vir_posttrip_time = 'NULL'; }else{ $vir_posttrip_time = "\"$_POST[vir_posttrip_time]\"";}
if (isset($_POST['vir_vtextEnabled']) && $_POST['vir_vtextEnabled'] == 'on') { $vir_vtextEnabled = '"1"'; }else{ $vir_vtextEnabled = '"0"';}
if (isset($_POST['vir_emailEnabled']) && $_POST['vir_emailEnabled'] == 'on') { $vir_emailEnabled = '"1"'; }else{ $vir_emailEnabled = '"0"';}
if (empty($_POST['vir_pre_message'])) { $vir_pre_message = 'NULL'; }else{ $vir_pre_message = "\"$_POST[vir_pre_message]\"";}
if (empty($_POST['vir_post_message'])) { $vir_post_message = 'NULL'; }else{ $vir_post_message = "\"$_POST[vir_post_message]\"";}

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
           (username,employee_id)
           VALUES
           ($formUsername,UUID())";
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
   ts_name = $tsName,
   vir_pretrip_time = $vir_pretrip_time,
   vir_posttrip_time = $vir_posttrip_time,
   vir_vtext_enabled = $vir_vtextEnabled,
   vir_email_enabled = $vir_emailEnabled,
   vir_pretrip_message = $vir_pre_message,
   vir_posttrip_message = $vir_post_message
  WHERE id = $id";

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
            Users Profile</h1>
            
          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">User Profile</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->
          
  
          
          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    <input name="radio" type="radio" id="activeusers" value="activeusers" checked>
                    <label for="activeusers"></label>
                    <label for="userstatus"></label>
                  Active Users / 
                  <input type="radio" name="radio" id="inactiveusers" value="inactiveusers">
                  <label for="inactiveusers"></label>
                  Inactive Users / 
                  <input type="radio" name="radio" id="allusers" value="allusers">
                  <label for="allusers"></label>
                  All Users</h3>
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
// Jaime Added these items below
      }
      if ($_GET['sort'] == 'title')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderStatus = 'asc';
          $glyphStatus = "bottom";
          $orderSql = "ORDER BY title DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderStatus = 'desc';
          $glyphStatus = "top";
          $orderSql = "ORDER BY title ASC";
        }
		}
      if ($_GET['sort'] == 'office')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderStatus = 'asc';
          $glyphStatus = "bottom";
          $orderSql = "ORDER BY office DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderStatus = 'desc';
          $glyphStatus = "top";
          $orderSql = "ORDER BY office ASC";
        }
      }
      if ($_GET['sort'] == 'number')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderStatus = 'asc';
          $glyphStatus = "bottom";
          $orderSql = "ORDER BY phonenumber DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderStatus = 'desc';
          $glyphStatus = "top";
          $orderSql = "ORDER BY phonenumber ASC";
        }
      }
      if ($_GET['sort'] == 'login')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderStatus = 'asc';
          $glyphStatus = "bottom";
          $orderSql = "ORDER BY login DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderStatus = 'desc';
          $glyphStatus = "top";
          $orderSql = "ORDER BY login ASC";
        }
      }
      if ($_GET['sort'] == 'title')
      {
        if ($_GET['order'] == 'desc')
        {
          $orderTitle = 'asc';
          $glyphStatus = "bottom";
          $orderSql = "ORDER BY title DESC";
        }
        if ($_GET['order'] == 'asc')
        {
          $orderTitle = 'desc';
          $glyphStatus = "top";
          $orderSql = "ORDER BY title ASC";
        }
      }
// Matt original code begins again
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
    <th> </th>
    <th>Name <a href="?sort=name&order=<?php echo $orderName;?>">
             <i class="glyphicon glyphicon-triangle-<?php echo $glyphName;?>"></i></a></th>
    <th>Login As</th>
    <th>Status <a href="?sort=status&order=<?php echo $orderStatus;?>">
               <i class="glyphicon glyphicon-triangle-<?php echo $glyphStatus;?>"></i></a></th>
    <th>Title <a href="?sort=title&order=<?php echo $orderTitle;?>">
               <i class="glyphicon glyphicon-triangle-<?php echo $glyphStatus;?>"></i></a></th>
    <th>Office <a href="?sort=office&order=<?php echo $orderOffice;?>">
               <i class="glyphicon glyphicon-triangle-<?php echo $glyphStatus;?>"></i></a></th>
    <th>Phone Number <a href="?sort=number&order=<?php echo $orderPhoneNumber;?>">
        <i class="glyphicon glyphicon-triangle-<?php echo $glyphStatus;?>"></i></a></th>
    <th>Login <a href="?sort=login&order=<?php echo $orderLogin;?>">
        <i class="glyphicon glyphicon-triangle-<?php echo $glyphStatus;?>"></i></a></th>    
    <th>Password <a href="?sort=password&order=<?php echo $orderPassword;?>">
               <i class="glyphicon glyphicon-triangle-<?php echo $glyphStatus;?>"></i></a></th>
  </tr>
 </thead>
 <tbody>
<?php
# If non-admin logs in then only show their info
if ($_SESSION['login'] == 2)
{
  $predicate = " WHERE username = '$_SESSION[username]'";
}
$sql = "SELECT 
     id,
     employee_id,
     username,
     drivername,
     fname,
     mname,
     lname,
     status,
     role,
     office,
     addr1,
     addr2,
     city,
     state,
     zipcode,
     title,
     email,
     emailupdate,
     vtext,
     vtextupdate,
     quiet_time_begin,
     quiet_time_end,
     ssn,
     date_format(dob,'%m/%d/%Y') as dob,
     driver_license_n,
     date_format(driver_license_exp,'%m/%d/%Y') as driver_license_exp,
     driverid,
     date_format(start_dt,'%m/%d/%Y') as start_dt,
     date_format(depart_dt,'%m/%d/%Y') as depart_dt,
     depart_reason,
     username,
     password,
     date_format(med_card_exp,'%m/%d/%Y') as med_card_exp,
     salary,
     emerg_contact_name,
     emerg_contact_phone,
     tsa_sta,
     contract,
     fuelcard,
     ts_name,
     ts_phone,
     notes,
     vir_pretrip_time,
     vir_posttrip_time,
     vir_vtext_enabled,
     vir_email_enabled,
     vir_pretrip_message,
     vir_posttrip_message
      FROM users 
    $predicate
$orderSql";
$sql = mysql_query($sql);
while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
{
?>
<tr name="<?php echo $row['status'];?>">
<td><a href="#"><i class="glyphicon glyphicon-user"></i></a></td>
<td>
<div style="float:left;width:80%;"><?php echo $row['fname'] . " " . $row['lname'];?></div>
<div style="float:right;width:20%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse" 
  href="#<?php echo $row['username'];?>_details"aria-expanded="false" aria-controls="<?php echo $row['username'];?>_details">
  </a></div>
</td>
<td><a href="<?php echo $_SERVER['PHP_SELF'];?>?action=loginas&employee_id=<?php echo $row['employee_id'];?>">
    <i class="glyphicon glyphicon-lock"></i></a></td>
<td><?php echo $row['status'];?></td>
<td><?php echo $row['title'];?></td>
<td><?php echo $row['office'];?></td>
<td><?php echo $row['driverid'];?></td>
<td><?php echo $row['username'];?></td>
<td><?php echo $row['password'];?></td>
</tr>
<tr class="collapse" id="<?php echo $row['username'];?>_details">
<td colspan="9">
  <div class="well">
<form enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table>
<tr>
 <td rowspan="3">
   <div><img style="display: block; margin: 0 auto;" 
         src="<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $row['username'] . "_avatar")) { echo HTTP."/dist/img/userimages/" . $row['username'] . "_avatar";}else{ echo HTTP."/dist/img/avatar.png"; }?>"/></div>
   <div><input id="fileToUpload" name="fileToUpload" type="file" multiple=true class="file-loading"></div>
 </td>
 <td style="padding: 5px">
  <label for="fname">First Name</label>
  <input type="text" class="form-control" name="fname" id="fname" placeholder="" value="<?php echo $row['fname'];?>">
 </td>
 <td style="padding: 5px">
  <label for="mname">Middle Name</label>
  <input type="text" class="form-control" name="mname" id="mname" placeholder="" value="<?php echo $row['mname'];?>">
 </td>
 <td style="padding: 5px">
  <label for="lname">Last Name</label>
  <input type="text" class="form-control" name="lname" id="lname" placeholder="" value="<?php echo $row['lname'];?>">
 </td>
 <td style="padding: 5px">
  <label for="status">Status</label>
   <select class="form-control" name="status" id="status">
     <option value="Active" <?php if ($row['status'] == 'Active') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Active</option>
     <option value="Inactive"<?php if ($row['status'] == 'Inactive') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Inactive</option>
   </select> 
 </td>
 <td style="padding: 5px">
  <label for="role">Role</label>
   <select class="form-control" name="role" id="role">
     <option value="Employee" <?php if ($row['role'] == 'Employee') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Employee</option>
     <option value="Admin"<?php if ($row['role'] == 'Admin') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Admin</option>
   </select> 
 </td>
 <td style="padding: 5px">
  <label for="office">Office</label>
   <select class="form-control" name="office" id="office">
     <option value="PHX" <?php if ($row['office'] == 'PHX') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>PHX</option>
     <option value="TUS"<?php if ($row['office'] == 'TUS') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>TUS</option>
     <option value="PHL" <?php if ($row['office'] == 'PHL') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>PHL</option>
     <option value="DEN" <?php if ($row['office'] == 'DEN') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>DEN</option>
     <option value="LAX" <?php if ($row['office'] == 'LAX') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>LAX</option>
     <option value="MIA" <?php if ($row['office'] == 'MIA') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>MIA</option>
     <option value="ORD" <?php if ($row['office'] == 'ORD') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>ORD</option>
   </select> 
 </td>
</tr>
<tr>
 <td style="padding: 5px">
  <label for="addr1">Home Addr 1</label>
  <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="<?php echo $row['addr1'];?>">
 </td>
 <td style="padding: 5px">
  <label for="addr2">Home Addr 2</label>
  <input type="text" class="form-control" name="addr2" id="addr2" placeholder="" value="<?php echo $row['addr2'];?>">
 </td>
 <td style="padding: 5px">
  <label for="city">Home City</label>
  <input type="text" class="form-control" name="city" id="city" placeholder="" value="<?php echo $row['city'];?>">
 </td>
 <td style="padding: 5px">
  <label for="state">Home State</label>
  <input type="text" class="form-control" name="state" id="state" placeholder="" value="<?php echo $row['state'];?>">
 </td>
 <td style="padding: 5px">
  <label for="zip">Home Zip</label>
  <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="<?php echo $row['zipcode'];?>">
 </td>
 <td style="padding: 5px">
  <label for="jobTitle">Title</label>
   <select class="form-control" name="jobTitle" id="jobTitle">
         <option value="Office" <?php if ($row['title'] == 'Office') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Office</option>
         <option value="Dispatch"<?php if ($row['title'] == 'Dispatch') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Dispatch</option>
         <option value="Accounting" <?php if ($row['title'] == 'Accounting') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Accounting</option>
         <option value="Driver"<?php if ($row['title'] == 'Driver') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Driver</option>
         <option value="Mechanic"<?php if ($row['title'] == 'Mechanic') { echo " selected "; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>Mechanic</option>
       </select> 
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="email">Email</label>
      <input type="email" class="form-control" name="email" id="email" placeholder="" value="<?php echo $row['email'];?>">
     </td>
     <td style="padding: 5px">
       <label for="emailUpdates" style="margin-top: 8px; margin-bottom: 0px;">Enable</label>

<table>
<tr><td>
      <div class="checkbox">
    <label>
      <input name="emailEnabled" id="emailEnabled" type="checkbox" value="on" <?php if ($row['emailupdate'] == "1") { echo "checked"; }?>>
    </label>
  </div>
      </div>
</td>
</tr></table>

     </td>
     <td style="padding: 5px">
      <label for="vtext">Vtext</label>
      <input type="vtext" class="form-control" name="vtext" id="vtext" placeholder="" value="<?php echo $row['vtext'];?>">
     </td>
     <td style="padding: 5px">
      <label for="textUpdates" style="margin-top: 8px; margin-bottom: 0px;">Enable</label>
<table>
<tr><td>
      <div class="checkbox">
    <label>
      <input name="vtextEnabled" id="vtextEnabled" type="checkbox" value="on" <?php if ($row['vtextupdate'] == "1") { echo "checked"; }?> <?php if ($_SESSION['login'] == 2) { echo 'style="display: none;"'; }?>>
    </label>
  </div>
      </div>
</td>
</tr></table>

     </td>
     <td style="padding: 5px">
      <label for="quietTimeVal1">Quiet (start)</label>
       <select class="form-control" name="quietTimeVal1" id="quietTimeVal1">
         <option value="00:00" <?php if ($row['quiet_time_begin'] == '00:00') { echo " selected "; }?>>00:00</option>
         <option value="01:00" <?php if ($row['quiet_time_begin'] == '01:00') { echo " selected "; }?>>01:00</option>
         <option value="02:00" <?php if ($row['quiet_time_begin'] == '02:00') { echo " selected "; }?>>02:00</option>
         <option value="03:00" <?php if ($row['quiet_time_begin'] == '03:00') { echo " selected "; }?>>03:00</option>
         <option value="04:00" <?php if ($row['quiet_time_begin'] == '04:00') { echo " selected "; }?>>04:00</option>
         <option value="05:00" <?php if ($row['quiet_time_begin'] == '05:00') { echo " selected "; }?>>05:00</option>
         <option value="06:00" <?php if ($row['quiet_time_begin'] == '06:00') { echo " selected "; }?>>06:00</option>
         <option value="07:00" <?php if ($row['quiet_time_begin'] == '07:00') { echo " selected "; }?>>07:00</option>
         <option value="08:00" <?php if ($row['quiet_time_begin'] == '08:00') { echo " selected "; }?>>08:00</option>
         <option value="09:00" <?php if ($row['quiet_time_begin'] == '09:00') { echo " selected "; }?>>09:00</option>
         <option value="10:00" <?php if ($row['quiet_time_begin'] == '10:00') { echo " selected "; }?>>10:00</option>
         <option value="11:00" <?php if ($row['quiet_time_begin'] == '11:00') { echo " selected "; }?>>11:00</option>
         <option value="12:00" <?php if ($row['quiet_time_begin'] == '12:00') { echo " selected "; }?>>12:00</option>
         <option value="13:00" <?php if ($row['quiet_time_begin'] == '13:00') { echo " selected "; }?>>13:00</option>
         <option value="14:00" <?php if ($row['quiet_time_begin'] == '14:00') { echo " selected "; }?>>14:00</option>
         <option value="15:00" <?php if ($row['quiet_time_begin'] == '15:00') { echo " selected "; }?>>15:00</option>
         <option value="16:00" <?php if ($row['quiet_time_begin'] == '16:00') { echo " selected "; }?>>16:00</option>
         <option value="17:00" <?php if ($row['quiet_time_begin'] == '17:00') { echo " selected "; }?>>17:00</option>
         <option value="18:00" <?php if ($row['quiet_time_begin'] == '18:00') { echo " selected "; }?>>18:00</option>
         <option value="19:00" <?php if ($row['quiet_time_begin'] == '19:00') { echo " selected "; }?>>19:00</option>
         <option value="20:00" <?php if ($row['quiet_time_begin'] == '20:00') { echo " selected "; }?>>20:00</option>
         <option value="21:00" <?php if ($row['quiet_time_begin'] == '21:00') { echo " selected "; }?>>21:00</option>
         <option value="22:00" <?php if ($row['quiet_time_begin'] == '22:00') { echo " selected "; }?>>22:00</option>
         <option value="23:00" <?php if ($row['quiet_time_begin'] == '23:00') { echo " selected "; }?>>23:00</option>
      </select>
     </td>
     <td style="padding: 5px">
      <label for="quietTimeVal2">Quiet (end)</label>
       <select class="form-control" name="quietTimeVal2" id="quietTimeVal2">
         <option value="00:00" <?php if ($row['quiet_time_end'] == '00:00') { echo " selected "; }?>>00:00</option>
         <option value="01:00" <?php if ($row['quiet_time_end'] == '01:00') { echo " selected "; }?>>01:00</option>
         <option value="02:00" <?php if ($row['quiet_time_end'] == '02:00') { echo " selected "; }?>>02:00</option>
         <option value="03:00" <?php if ($row['quiet_time_end'] == '03:00') { echo " selected "; }?>>03:00</option>
         <option value="04:00" <?php if ($row['quiet_time_end'] == '04:00') { echo " selected "; }?>>04:00</option>
         <option value="05:00" <?php if ($row['quiet_time_end'] == '05:00') { echo " selected "; }?>>05:00</option>
         <option value="06:00" <?php if ($row['quiet_time_end'] == '06:00') { echo " selected "; }?>>06:00</option>
         <option value="07:00" <?php if ($row['quiet_time_end'] == '07:00') { echo " selected "; }?>>07:00</option>
         <option value="08:00" <?php if ($row['quiet_time_end'] == '08:00') { echo " selected "; }?>>08:00</option>
         <option value="09:00" <?php if ($row['quiet_time_end'] == '09:00') { echo " selected "; }?>>09:00</option>
         <option value="10:00" <?php if ($row['quiet_time_end'] == '10:00') { echo " selected "; }?>>10:00</option>
         <option value="11:00" <?php if ($row['quiet_time_end'] == '11:00') { echo " selected "; }?>>11:00</option>
         <option value="12:00" <?php if ($row['quiet_time_end'] == '12:00') { echo " selected "; }?>>12:00</option>
         <option value="13:00" <?php if ($row['quiet_time_end'] == '13:00') { echo " selected "; }?>>13:00</option>
         <option value="14:00" <?php if ($row['quiet_time_end'] == '14:00') { echo " selected "; }?>>14:00</option>
         <option value="15:00" <?php if ($row['quiet_time_end'] == '15:00') { echo " selected "; }?>>15:00</option>
         <option value="16:00" <?php if ($row['quiet_time_end'] == '16:00') { echo " selected "; }?>>16:00</option>
         <option value="17:00" <?php if ($row['quiet_time_end'] == '17:00') { echo " selected "; }?>>17:00</option>
         <option value="18:00" <?php if ($row['quiet_time_end'] == '18:00') { echo " selected "; }?>>18:00</option>
         <option value="19:00" <?php if ($row['quiet_time_end'] == '19:00') { echo " selected "; }?>>19:00</option>
         <option value="20:00" <?php if ($row['quiet_time_end'] == '20:00') { echo " selected "; }?>>20:00</option>
         <option value="21:00" <?php if ($row['quiet_time_end'] == '21:00') { echo " selected "; }?>>21:00</option>
         <option value="22:00" <?php if ($row['quiet_time_end'] == '22:00') { echo " selected "; }?>>22:00</option>
         <option value="23:00" <?php if ($row['quiet_time_end'] == '23:00') { echo " selected "; }?>>23:00</option>
       </select>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="ssn">SSN</label>
      <input type="text" class="form-control" name="ssn" id="ssn" placeholder="" value="<?php echo $row['ssn'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="dob">DOB</label>
      <input type="text" class="form-control" name="dob" id="dob" placeholder="mm/dd/yyyy" value="<?php echo $row['dob'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="driverLicense">License No.</label>
      <input type="text" class="form-control" name="driverLicense" id="driverLicense" placeholder="" value="<?php echo $row['driver_license_n'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="driverLicenseExpire">License Exp.</label>
      <input type="text" class="form-control" name="driverLicenseExpire" id="driverLicenseExpire" placeholder="mm/dd/yyyy" value="<?php echo $row['driver_license_exp'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="mobilePhone">Mobile</label>
      <input type="text" class="form-control" name="mobilePhone" id="mobilePhone" placeholder="" value="<?php echo $row['driverid'];?>">
     </td>
     <td style="padding: 5px">
      <label for="startDate">Start Date</label>
      <input type="text" class="form-control" name="startDate" id="startDate" placeholder="mm/dd/yyyy" value="<?php echo $row['start_dt'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="departureDate">Depart Date</label>
      <input type="text" class="form-control" data-date-format="mm/dd/yyyy" name="departureDate" id="departureDate" placeholder="mm/dd/yyyy" value="<?php echo $row['depart_dt'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="departureReason">Depart Reason</label>
      <input type="text" class="form-control" name="departureReason" id="departureReason" placeholder="" value="<?php echo $row['depart_reason'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="username">Username</label>
      <input type="text" class="form-control" name="username" id="username" placeholder="" value="<?php echo $row['username'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="password">Password</label>
      <input type="text" class="form-control" name="password" id="password" placeholder="" value="<?php echo $row['password'];?>">
     </td>
     <td style="padding: 5px">
      <label for="medCardExpire">Med Exp Date</label>
      <input type="text" class="form-control" name="medCardExpire" id="medCardExpire" placeholder="mm/dd/yyyy" value="<?php echo $row['med_card_exp'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="salary">Salary</label>
      <input type="text" class="form-control" name="salary" id="salary" placeholder="" value="<?php echo $row['salary'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="emergencyPhone">Emerg Phone</label>
      <input type="text" class="form-control" name="emergencyPhone" id="emergencyPhone" placeholder="" value="<?php echo $row['emerg_contact_phone'];?>">
     </td>
     <td style="padding: 5px">
      <label for="tsa">TSA-STA</label>
      <input type="text" class="form-control" name="tsa" id="tsa" placeholder="" value="<?php echo $row['tsa_sta'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="emergencyContact">Emerg Contact</label>
      <input type="text" class="form-control" name="emergencyContact" id="emergencyContact" placeholder="" value="<?php echo $row['emerg_contact_name'];?>">
     </td>
     <td style="padding: 5px" colspan="3">
      <label for="miscDetails">Notes</label>
      <textarea class="form-control" name="notes" id="notes" placeholder="" value="" style="padding-top: 0px; padding-bottom: 0px; height: 34px;" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>><?php echo $row['notes'];?></textarea>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="tsPhone">TS Phone</label>
      <input type="text" class="form-control" name="tsPhone" id="tsPhone" placeholder="" value="<?php echo $row['ts_phone'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
     <td style="padding: 5px">
      <label for="tsName">TS Name</label>
      <input type="text" class="form-control" name="tsName" id="tsName" placeholder="" value="<?php echo $row['ts_name'];?>" <?php if ($_SESSION['login'] == 2) { echo 'readonly'; }?>>
     </td>
    </tr>
<tr>
     <td style="padding: 5px">
      <label for="vir_pretrip_time">VIR Pretrip</label>
       <select class="form-control" name="vir_pretrip_time" id="vir_pretrip_time">
         <option value="00:00" <?php if ($row['vir_pretrip_time'] == '00:00') { echo " selected "; }?>>00:00</option>
         <option value="01:00" <?php if ($row['vir_pretrip_time'] == '01:00') { echo " selected "; }?>>01:00</option>
         <option value="02:00" <?php if ($row['vir_pretrip_time'] == '02:00') { echo " selected "; }?>>02:00</option>
         <option value="03:00" <?php if ($row['vir_pretrip_time'] == '03:00') { echo " selected "; }?>>03:00</option>
         <option value="04:00" <?php if ($row['vir_pretrip_time'] == '04:00') { echo " selected "; }?>>04:00</option>
         <option value="05:00" <?php if ($row['vir_pretrip_time'] == '05:00') { echo " selected "; }?>>05:00</option>
         <option value="06:00" <?php if ($row['vir_pretrip_time'] == '06:00') { echo " selected "; }?>>06:00</option>
         <option value="07:00" <?php if ($row['vir_pretrip_time'] == '07:00') { echo " selected "; }?>>07:00</option>
         <option value="08:00" <?php if ($row['vir_pretrip_time'] == '08:00') { echo " selected "; }?>>08:00</option>
         <option value="09:00" <?php if ($row['vir_pretrip_time'] == '09:00') { echo " selected "; }?>>09:00</option>
         <option value="10:00" <?php if ($row['vir_pretrip_time'] == '10:00') { echo " selected "; }?>>10:00</option>
         <option value="11:00" <?php if ($row['vir_pretrip_time'] == '11:00') { echo " selected "; }?>>11:00</option>
         <option value="12:00" <?php if ($row['vir_pretrip_time'] == '12:00') { echo " selected "; }?>>12:00</option>
         <option value="13:00" <?php if ($row['vir_pretrip_time'] == '13:00') { echo " selected "; }?>>13:00</option>
         <option value="14:00" <?php if ($row['vir_pretrip_time'] == '14:00') { echo " selected "; }?>>14:00</option>
         <option value="15:00" <?php if ($row['vir_pretrip_time'] == '15:00') { echo " selected "; }?>>15:00</option>
         <option value="16:00" <?php if ($row['vir_pretrip_time'] == '16:00') { echo " selected "; }?>>16:00</option>
         <option value="17:00" <?php if ($row['vir_pretrip_time'] == '17:00') { echo " selected "; }?>>17:00</option>
         <option value="18:00" <?php if ($row['vir_pretrip_time'] == '18:00') { echo " selected "; }?>>18:00</option>
         <option value="19:00" <?php if ($row['vir_pretrip_time'] == '19:00') { echo " selected "; }?>>19:00</option>
         <option value="20:00" <?php if ($row['vir_pretrip_time'] == '20:00') { echo " selected "; }?>>20:00</option>
         <option value="21:00" <?php if ($row['vir_pretrip_time'] == '21:00') { echo " selected "; }?>>21:00</option>
         <option value="22:00" <?php if ($row['vir_pretrip_time'] == '22:00') { echo " selected "; }?>>22:00</option>
         <option value="23:00" <?php if ($row['vir_pretrip_time'] == '23:00') { echo " selected "; }?>>23:00</option>
      </select>
     </td>
     <td style="padding: 5px">
      <label for="vir_posttrip_time">VIR Posttrip</label>
       <select class="form-control" name="vir_posttrip_time" id="vir_posttrip_time">
         <option value="00:00" <?php if ($row['vir_posttrip_time'] == '00:00') { echo " selected "; }?>>00:00</option>
         <option value="01:00" <?php if ($row['vir_posttrip_time'] == '01:00') { echo " selected "; }?>>01:00</option>
         <option value="02:00" <?php if ($row['vir_posttrip_time'] == '02:00') { echo " selected "; }?>>02:00</option>
         <option value="03:00" <?php if ($row['vir_posttrip_time'] == '03:00') { echo " selected "; }?>>03:00</option>
         <option value="04:00" <?php if ($row['vir_posttrip_time'] == '04:00') { echo " selected "; }?>>04:00</option>
         <option value="05:00" <?php if ($row['vir_posttrip_time'] == '05:00') { echo " selected "; }?>>05:00</option>
         <option value="06:00" <?php if ($row['vir_posttrip_time'] == '06:00') { echo " selected "; }?>>06:00</option>
         <option value="07:00" <?php if ($row['vir_posttrip_time'] == '07:00') { echo " selected "; }?>>07:00</option>
         <option value="08:00" <?php if ($row['vir_posttrip_time'] == '08:00') { echo " selected "; }?>>08:00</option>
         <option value="09:00" <?php if ($row['vir_posttrip_time'] == '09:00') { echo " selected "; }?>>09:00</option>
         <option value="10:00" <?php if ($row['vir_posttrip_time'] == '10:00') { echo " selected "; }?>>10:00</option>
         <option value="11:00" <?php if ($row['vir_posttrip_time'] == '11:00') { echo " selected "; }?>>11:00</option>
         <option value="12:00" <?php if ($row['vir_posttrip_time'] == '12:00') { echo " selected "; }?>>12:00</option>
         <option value="13:00" <?php if ($row['vir_posttrip_time'] == '13:00') { echo " selected "; }?>>13:00</option>
         <option value="14:00" <?php if ($row['vir_posttrip_time'] == '14:00') { echo " selected "; }?>>14:00</option>
         <option value="15:00" <?php if ($row['vir_posttrip_time'] == '15:00') { echo " selected "; }?>>15:00</option>
         <option value="16:00" <?php if ($row['vir_posttrip_time'] == '16:00') { echo " selected "; }?>>16:00</option>
         <option value="17:00" <?php if ($row['vir_posttrip_time'] == '17:00') { echo " selected "; }?>>17:00</option>
         <option value="18:00" <?php if ($row['vir_posttrip_time'] == '18:00') { echo " selected "; }?>>18:00</option>
         <option value="19:00" <?php if ($row['vir_posttrip_time'] == '19:00') { echo " selected "; }?>>19:00</option>
         <option value="20:00" <?php if ($row['vir_posttrip_time'] == '20:00') { echo " selected "; }?>>20:00</option>
         <option value="21:00" <?php if ($row['vir_posttrip_time'] == '21:00') { echo " selected "; }?>>21:00</option>
         <option value="22:00" <?php if ($row['vir_posttrip_time'] == '22:00') { echo " selected "; }?>>22:00</option>
         <option value="23:00" <?php if ($row['vir_posttrip_time'] == '23:00') { echo " selected "; }?>>23:00</option>
      </select>
     </td>
<td style="padding: 5px"><label for="vir_text_updates" style="margin-top: 8px; margin-bottom: 0px;">VIR Text</label>
  <table>
    <tr>
      <td><div class="checkbox">
          <label>
            <input name="vir_vtextEnabled" id="vir_vtextEnabled" type="checkbox" value="on" <?php if ($row['vir_vtext_enabled'] == "1") { echo "checked"; }?>>
          </label>
        </div>
        </div></td>
    </tr>
  </table></td>
<td style="padding: 5px"><label for="vir_email_updates" style="margin-top: 8px; margin-bottom: 0px;">VIR Email</label>
  <table>
    <tr>
      <td><div class="checkbox">
          <label>
            <input name="vir_emailEnabled" id="vir_emailEnabled" type="checkbox" value="on" <?php if ($row['vir_email_enabled'] == "1") { echo "checked"; }?>>
          </label>
        </div>
        </div></td>
    </tr>
  </table></td>
     <td style="padding: 5px">
      <label for="vir_pre_message">VIR Pre Message</label>
      <input type="text" class="form-control" name="vir_pre_message" id="vir_pre_message" placeholder="" value="<?php echo $row['vir_pretrip_message'];?>">
     </td>
     <td style="padding: 5px">
      <label for="vir_post_message">VIR Post Message</label>
      <input type="text" class="form-control" name="vir_post_message" id="vir_post_message" placeholder="" value="<?php echo $row['vir_posttrip_message'];?>">
     </td>
</tr>
    <tr>
     <td colspan="1" style="padding: 5px">
      <label for="contract">Contract</label>
      <a href="<?php if (isset($row['contract'])) { echo HTTP . $row['contract']; }?>" target="_blank"><?php if (isset($row['contract'])) { echo $row['contract']; }?></a>
      <div><input id="contractUpload" name="contractUpload" type="file" multiple=true class="file-loading" <?php if ($_SESSION['login'] == 2) { echo 'disabled'; }?>></div>
     </td>
     <td colspan="1" style="padding: 5px">
      <label for="fuelcard">Fuel Card</label>
      <a href="<?php if (isset($row['fuelcard'])) { echo HTTP . $row['fuelcard']; }?>" target="_blank"><?php if (isset($row['fuelcard'])) { echo $row['fuelcard']; }?></a>
      <div><input id="fuelUpload" name="fuelUpload" type="file" multiple=true class="file-loading" <?php if ($_SESSION['login'] == 2) { echo 'disabled'; }?>></div>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
       <input type="submit" name="submit" class="btn btn-primary" value="Update">
       <input type="hidden" name="id" class="btn btn-primary" value="<?php echo $row['id'];?>">
     </td>
    </tr>
   </table>
   </form>
   <form  class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <table>
   <tr>
     <td style="padding: 5px">
      <button type="submit" class="btn btn-info" value="<?php echo $row['email'];?>" id="testEmail" name="testEmail" formaction="<?php echo $_SERVER['PHP_SELF']; ?>" formmethod="post">Test Email</button>
      <button type="submit" class="btn btn-info" value="<?php echo $row['vtext'];?>" id="testVtext" name="testVtext" formaction="<?php echo $_SERVER['PHP_SELF']; ?>" formmethod="post">Test Text</button>
      </form>
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
<?php
if ($_SESSION['login'] == 1)
{
?>
<tr>
<td><a href="#"><i class="glyphicon glyphicon-user"></i></a></td>
<td>
<div style="float:left;width:80%;">Add User</div>
<div style="float:right;width:20%;"><a class="glyphicon glyphicon-chevron-right" role="button" data-toggle="collapse"
  href="#addUser_details"aria-expanded="false" aria-controls="addUser_details">
  </a></div>
</td>
<td><a href="#"><i class="glyphicon glyphicon-lock"></i></a></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php
}
?>
<tr class="collapse" id="addUser_details">
<td colspan="9">
  <div class="well">
   <form enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   <table>
    <tr>
     <td rowspan="3">
       <div><img style="display: block; margin: 0 auto;"
             src="<?php echo HTTP."/dist/img/usernophoto.jpg";?>"/></div>
       <div><input id="fileToUpload" name="fileToUpload" type="file" multiple=true class="file-loading"></div>
     </td>
     <td style="padding: 5px">
      <label for="fname">First Name</label>
      <input type="text" class="form-control" name="fname" id="fname" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="mname">Middle Name</label>
      <input type="text" class="form-control" name="mname" id="mname" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="lname">Last Name</label>
      <input type="text" class="form-control" name="lname" id="lname" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="status">Status</label>
       <select class="form-control" name="status" id="status">
         <option value="Active">Active</option>
         <option value="Inactive">Inactive</option>
       </select>
     </td>
     <td style="padding: 5px">
      <label for="role">Role</label>
       <select class="form-control" name="role" id="role">
         <option value="Employee">Employee</option>
         <option value="Admin">Admin</option>
       </select>
     </td>
     <td style="padding: 5px">
      <label for="office">Office</label>
       <select class="form-control" name="office" id="office">
         <option value="PHX">PHX</option>
         <option value="TUS">TUS</option>
         <option>PHL</option>
		 <option>DEN</option>
  		 <option>LAX</option>
		 <option>MIA</option>
		 <option>ORD</option>
       </select>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="addr1">Home Addr 1</label>
      <input type="text" class="form-control" name="addr1" id="addr1" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="addr2">Home Addr 2</label>
      <input type="text" class="form-control" name="addr2" id="addr2" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="city">Home City</label>
      <input type="text" class="form-control" name="city" id="city" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="state">Home State</label>
      <input type="text" class="form-control" name="state" id="state" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="zip">Home Zip</label>
      <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="jobTitle">Title</label>
       <select class="form-control" name="jobTitle" id="jobTitle">
         <option value="Office">Office</option>
         <option value="Dispatch">Dispatch</option>
         <option value="Accounting">Accounting</option>
         <option value="Driver">Driver</option>
         <option value="Driver">Mechanic</option>
       </select>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="email">Email</label>
      <input type="email" class="form-control" name="email" id="email" placeholder="" value="">
     </td>
     <td style="padding: 5px">
       <label for="emailUpdates" style="margin-top: 8px; margin-bottom: 0px;">Enable</label>

<table>
<tr><td>
      <div class="checkbox">
    <label>
      <input name="emailEnabled" id="emailEnabled" type="checkbox" value="on" >
    </label>
  </div>
      </div>
</td>
</tr></table>

     </td>
     <td style="padding: 5px">
      <label for="vtext">Vtext</label>
      <input type="vtext" class="form-control" name="vtext" id="vtext" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="textUpdates" style="margin-top: 8px; margin-bottom: 0px;">Enable</label>
<table>
<tr><td>
      <div class="checkbox">
    <label>
      <input name="vtextEnabled" id="vtextEnabled" type="checkbox" value="on" >
    </label>
  </div>
      </div>
</td>
</tr></table>

     </td>
     <td style="padding: 5px">
      <label for="quietTimeVal1">Quiet (start)</label>
       <select class="form-control" name="quietTimeVal1" id="quietTimeVal1">
         <option value="00:00">00:00</option>
         <option value="01:00">01:00</option>
         <option value="02:00">02:00</option>
         <option value="03:00">03:00</option>
         <option value="04:00">04:00</option>
         <option value="05:00">05:00</option>
         <option value="06:00">06:00</option>
         <option value="07:00">07:00</option>
         <option value="08:00">08:00</option>
         <option value="09:00">09:00</option>
         <option value="10:00">10:00</option>
         <option value="11:00">11:00</option>
         <option value="12:00">12:00</option>
         <option value="13:00">13:00</option>
         <option value="14:00">14:00</option>
         <option value="15:00">15:00</option>
         <option value="16:00">16:00</option>
         <option value="17:00">17:00</option>
         <option value="18:00">18:00</option>
         <option value="19:00">19:00</option>
         <option value="20:00">20:00</option>
         <option value="21:00">21:00</option>
         <option value="22:00">22:00</option>
         <option value="23:00">23:00</option>
      </select>
     </td>
     <td style="padding: 5px">
      <label for="quietTimeVal2">Quiet (end)</label>
       <select class="form-control" name="quietTimeVal2" id="quietTimeVal2">
         <option value="00:00">00:00</option>
         <option value="01:00">01:00</option>
         <option value="02:00">02:00</option>
         <option value="03:00">03:00</option>
         <option value="04:00">04:00</option>
         <option value="05:00">05:00</option>
         <option value="06:00">06:00</option>
         <option value="07:00">07:00</option>
         <option value="08:00">08:00</option>
         <option value="09:00">09:00</option>
         <option value="10:00">10:00</option>
         <option value="11:00">11:00</option>
         <option value="12:00">12:00</option>
         <option value="13:00">13:00</option>
         <option value="14:00">14:00</option>
         <option value="15:00">15:00</option>
         <option value="16:00">16:00</option>
         <option value="17:00">17:00</option>
         <option value="18:00">18:00</option>
         <option value="19:00">19:00</option>
         <option value="20:00">20:00</option>
         <option value="21:00">21:00</option>
         <option value="22:00">22:00</option>
         <option value="23:00">23:00</option>
       </select>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="ssn">SSN</label>
      <input type="text" class="form-control" name="ssn" id="ssn" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="dob">DOB</label>
      <input type="text" class="form-control" name="dob" id="dob" placeholder="mm/dd/yyyy" value="">
     </td>
     <td style="padding: 5px">
      <label for="driverLicense">License No.</label>
      <input type="text" class="form-control" name="driverLicense" id="driverLicense" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="driverLicenseExpire">License Exp.</label>
      <input type="text" class="form-control" name="driverLicenseExpire" id="driverLicenseExpire" placeholder="mm/dd/yyyy" value="">
     </td>
     <td style="padding: 5px">
      <label for="mobilePhone">Mobile</label>
      <input type="text" class="form-control" name="mobilePhone" id="mobilePhone" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="startDate">Start Date</label>
      <input type="text" class="form-control" name="startDate" id="startDate" placeholder="mm/dd/yyyy" value="">
     </td>
     <td style="padding: 5px">
      <label for="departureDate">Depart Date</label>
      <input type="text" class="form-control" data-date-format="mm/dd/yyyy" name="departureDate" id="departureDate" placeholder="mm/dd/yyyy" value="">
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="departureReason">Depart Reason</label>
      <input type="text" class="form-control" name="departureReason" id="departureReason" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="username">Username</label>
      <input type="text" class="form-control" name="username" id="username" placeholder="" value="" required>
     </td>
     <td style="padding: 5px">
      <label for="password">Password</label>
      <input type="text" class="form-control" name="password" id="password" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="medCardExpire">Med Exp Date</label>
      <input type="text" class="form-control" name="medCardExpire" id="medCardExpire" placeholder="mm/dd/yyyy" value="">
     </td>
     <td style="padding: 5px">
      <label for="salary">Salary</label>
      <input type="text" class="form-control" name="salary" id="salary" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="emergencyPhone">Emerg Phone</label>
      <input type="text" class="form-control" name="emergencyPhone" id="emergencyPhone" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="tsa">TSA-STA</label>
      <input type="text" class="form-control" name="tsa" id="tsa" placeholder="" value="">
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="emergencyContact">Emerg Contact</label>
      <input type="text" class="form-control" name="emergencyContact" id="emergencyContact" placeholder="" value="">
     </td>
     <td style="padding: 5px" colspan="3">
      <label for="miscDetails">Notes</label>
      <textarea class="form-control" name="miscDetails" id="miscDetails" placeholder="" value="" style="padding-top: 0px; padding-bottom: 0px; height: 34px;"></textarea>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
      <label for="tsPhone">TS Phone</label>
      <input type="text" class="form-control" name="tsPhone" id="tsPhone" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="tsName">TS Name</label>
      <input type="text" class="form-control" name="tsName" id="tsName" placeholder="" value="">
     </td>
    </tr>
<tr>
     <td style="padding: 5px">
      <label for="vir_pretrip_time">VIR Pretrip</label>
       <select class="form-control" name="vir_pretrip_time" id="vir_pretrip_time">
         <option value="00:00">00:00</option>
         <option value="01:00">01:00</option>
         <option value="02:00">02:00</option>
         <option value="03:00">03:00</option>
         <option value="04:00">04:00</option>
         <option value="05:00">05:00</option>
         <option value="06:00">06:00</option>
         <option value="07:00">07:00</option>
         <option value="08:00">08:00</option>
         <option value="09:00">09:00</option>
         <option value="10:00">10:00</option>
         <option value="11:00">11:00</option>
         <option value="12:00">12:00</option>
         <option value="13:00">13:00</option>
         <option value="14:00">14:00</option>
         <option value="15:00">15:00</option>
         <option value="16:00">16:00</option>
         <option value="17:00">17:00</option>
         <option value="18:00">18:00</option>
         <option value="19:00">19:00</option>
         <option value="20:00">20:00</option>
         <option value="21:00">21:00</option>
         <option value="22:00">22:00</option>
         <option value="23:00">23:00</option>
      </select>
     </td>
     <td style="padding: 5px">
      <label for="vir_posttrip_time">VIR Posttrip</label>
       <select class="form-control" name="vir_posttrip_time" id="vir_posttrip_time">
         <option value="00:00">00:00</option>
         <option value="01:00">01:00</option>
         <option value="02:00">02:00</option>
         <option value="03:00">03:00</option>
         <option value="04:00">04:00</option>
         <option value="05:00">05:00</option>
         <option value="06:00">06:00</option>
         <option value="07:00">07:00</option>
         <option value="08:00">08:00</option>
         <option value="09:00">09:00</option>
         <option value="10:00">10:00</option>
         <option value="11:00">11:00</option>
         <option value="12:00">12:00</option>
         <option value="13:00">13:00</option>
         <option value="14:00">14:00</option>
         <option value="15:00">15:00</option>
         <option value="16:00">16:00</option>
         <option value="17:00">17:00</option>
         <option value="18:00">18:00</option>
         <option value="19:00">19:00</option>
         <option value="20:00">20:00</option>
         <option value="21:00">21:00</option>
         <option value="22:00">22:00</option>
         <option value="23:00">23:00</option>
      </select>
     </td>
<td style="padding: 5px"><label for="vir_text_updates" style="margin-top: 8px; margin-bottom: 0px;">VIR Text</label>
  <table>
    <tr>
      <td><div class="checkbox">
          <label>
            <input name="vir_vtextEnabled" id="vir_vtextEnabled" type="checkbox" value="on" >
          </label>
        </div>
        </div></td>
    </tr>
  </table></td>
<td style="padding: 5px"><label for="vir_email_updates" style="margin-top: 8px; margin-bottom: 0px;">VIR Email</label>
  <table>
    <tr>
      <td><div class="checkbox">
          <label>
            <input name="vir_emailEnabled" id="vir_emailEnabled" type="checkbox" value="on" >
          </label>
        </div>
        </div></td>
    </tr>
  </table></td>
     <td style="padding: 5px">
      <label for="vir_pre_message">VIR Pre Message</label>
      <input type="text" class="form-control" name="vir_pre_message" id="vir_pre_message" placeholder="" value="">
     </td>
     <td style="padding: 5px">
      <label for="vir_post_message">VIR Post Message</label>
      <input type="text" class="form-control" name="vir_post_message" id="vir_post_message" placeholder="" value="">
     </td>
</tr>
    <tr>
     <td colspan="1" style="padding: 5px">
      <label for="contract">Contract</label>
      <a href=""></a>
      <div><input id="contractUpload" name="contractUpload" type="file" multiple=true class="file-loading"></div>
     </td>
     <td colspan="1" style="padding: 5px">
      <label for="fuelcard">Fuel Card</label>
      <a href="" target="_blank"></a>
      <div><input id="fuelUpload" name="fuelUpload" type="file" multiple=true class="file-loading"></div>
     </td>
    </tr>
    <tr>
     <td style="padding: 5px">
       <input type="submit" name="submit" class="btn btn-primary" value="Add">
       <input type="hidden" name="id" class="btn btn-primary" value="">
     </td>
    </tr>
   </table>
   </form>
  </div>
</td>
</tr>
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
$(document).ready(function() {
  // Default visibility for users
  $('[name="Active"]').show();
  $('[name="Inactive"]').hide();

  $("#activeusers").click(function() {
    $('[name="Active"]').show();
    $('[name="Inactive"]').hide();
  });
  $("#inactiveusers").click(function() {
    $('[name="Active"]').hide();
    $('[name="Inactive"]').show();
  });
  $("#allusers").click(function() {
    $('[name="Active"]').show();
    $('[name="Inactive"]').show();
  });
});
</script>

</body>
</html>
