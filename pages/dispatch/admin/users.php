<?php
session_start();

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
if ($_SESSION['onboarding'] != true) {
// This person hasn't authenticated NOR are they onboarding
header('Location: /pages/login/driverlogin.php');
}
}
// If we're an admin then don't restrict any visibility
if ($_SESSION['login'] == 1) {
    $restricted_viewing = false;
}elseif($_SESSION['login'] == 2) {
    $restricted_viewing = true;
}

$restrict_onboarding = false;
if (isset($_SESSION['onboarding'])) {
    $_SESSION['login'] = 3;
    $restrict_onboarding = true;

}

// Setup states array
$us_state_abbrevs = array('AL', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY');
$time_selectors = array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00');
$vtext_providers = array(
"@txt.att.net" => "AT&T Txt",
"@mms.att.net" => "AT&T MMS",
"@cingularme.com" => "Cingular",
"@myboostmobile.com" => "Boost Mobile",
"@messaging.nextel.com" => "Nextel",
"@messaging.sprintpcs.com" => "Sprint PCS",
"@pm.sprint.com" => "Sprint Smart",
"@tmomail.net" => "T-Moblile",
"@email.uscc.net" => "Us Cellular",
"@vtext.com" => "Verizon",
"@vzwpix.com" => "Verizon MMS",
"@vmobl.com" => "Virgin Mobile ",
"@mmst5.tracfone.com" => "Tracefone",
"@vtext.com" => "Verizon",
"@page.att.net" => "AT&T Page",
"@cingularme.com" => "Cingular ",
"@mymetropcs.com" => "Metro PCS",
"@qwestmp.com" => "Quest",
"@cingularme.com" => "Cingluar",
"@cingularme.com" => "Cingular",
"@messaging.nextel.com" => "Nextel",
"@sms.airtelmontana.com" => "Airtel Montana",
"@msg.acsalaska.com" => "Alaska Comm",
"@cellcom.quiktxt.com" => "Cellcom",
"@mobile.celloneusa.com" => "Cellular One",
"@text.cellonenation.net" => "Cellular One",
"@cwemail.com" => "Centennial Wireless",
"@sms.mycricket.com" => "Cricket",
"@cspire1.com" => "C-Spire",
"@msg.gci.net" => "General Comm Inc",
"@msg.gci.net" => "Globalstar",
"@msg.globalstarusa.com" => "Helio",
"@ivctext.com" => "Illinois Valley Cell",
"@msg.iridium.com" => "Iridium",
"@orange.pl" => "Orange Polska",
"@tms.suncom.com" => "Seuncom",
"@sms.thumbcellular.com" => "Thumb Cellular",
"@sms.alltelwireless.com" => "AllTel",
"@sms.bluecell.com" => "Bluegrass Cellular",
"@messaging.centurytel.net" => "Century Tel",
"@mobile.att.net" => "Cincinnati Bell",
"@corrwireless.net" => "Corr Wireless Comm",
"@mobile.dobson.net" => "Dobson Cellular Systems",
"@mobile.cellularone.com" => "Dobson Cellular One",
"@inlandlink.com" => "Inland Cellular",
"@metropcs.sms.us" => "Metro PCS",
"@clearlydigital.com" => "Midwest Wireless",
"@pcsone.net" => "PCS ONE",
"@msg.pioneerenidcellular.com" => "Pioneer Enid Cell",
"@voicestream.net" => "Powertel",
"@sms.pscel.com" => "Public Service Cell",
"@typetalk.ruralcellular.com" => "Rural Cell",
"@csouth1.com" => "Telepak Cell South",
"@voicestream.net" => "Voicestream",
"@sms.wcc.net" => "West Central Wireless",
"@cellularonewest.com" => "Western Wireless",
"@viaerosms.com" => "Viaero",
"@msg.fi.google.com" => "Google FI",
"@mms.mycricket.com" => "Cricket",
);
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];
if ($_GET['action'] == 'loginas')
{
$statement = "SELECT username,
drivername,
fname,
lname,
employee_id
FROM users WHERE employee_id = '" . mysql_real_escape_string($_GET['employee_id']) . "'";
$results = mysql_query($statement);
$row = mysql_fetch_array($results, MYSQL_BOTH);
$_SESSION['userid'] = $row['username'];
$_SESSION['username'] = $row['username'];
$_SESSION['drivername'] = $row['drivername'];
$_SESSION['fname'] = $row['fname'];
$_SESSION['lname'] = $row['lname'];
$_SESSION['employee_id'] = $row['employee_id'];
$_SESSION['login'] = 2;
header("Location: /pages/main/index.php");
}
# If we're submitted a POST request and it has an email or vtext then
# we'll send out a test message
if (isset($_POST['testVtext']))
{
sendEmail($_POST['testVtext'],"Testing Vtext","This is a test message.",null,null);
}
if (isset($_POST['testEmail']))
{
sendEmail($_POST['testEmail'],"Testing Vtext","This is a test message.",null,null);
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
if (empty($_POST['jobTitle'])) {
$title = 'NULL';
}else{
$title = "\"$_POST[jobTitle]\"";
if (preg_match('/Driver/',$title)) {
$subtitle = preg_replace('/Driver\s-\s/','',$title);
$title = "\"Driver\"";
}else{
$subtitle = 'NULL';
}
}
if (empty($_POST['email'])) { $email = 'NULL'; }else{ $email = "\"$_POST[email]\"";}
if (isset($_POST['emailEnabled']) && $_POST['emailEnabled'] == 'on') { $emailEnabled = '"1"'; }else{ $emailEnabled = '"0"';}
if (empty($_POST['vtext'])) { $vtext = 'NULL'; }else{ $vtext = '"'.$_POST['vtext'].$_POST['VtextHelp'].'"';}
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
if (empty($_POST['tsaExpire'])) { $tsa_date_exp = 'NULL'; }else{ $tsa_date_exp = "str_to_date('$_POST[tsaExpire]', '%m/%d/%Y')";}
if (empty($_POST['tsaChange'])) { $tsa_date_change_exp = 'NULL'; }else{ $tsa_date_change_exp = "str_to_date('$_POST[tsaChange]', '%m/%d/%Y')";}
# Strip non-digits from the phone
if ($tsPhone != 'NULL') {
$tsPhone = preg_replace('/\D/','',$tsPhone);
}
if (empty($_POST['vir_pretrip_time'])) { $vir_pretrip_time = 'NULL'; }else{ $vir_pretrip_time = "\"$_POST[vir_pretrip_time]\"";}
if (empty($_POST['vir_posttrip_time'])) { $vir_posttrip_time = 'NULL'; }else{ $vir_posttrip_time = "\"$_POST[vir_posttrip_time]\"";}
if (isset($_POST['vir_vtextEnabled']) && $_POST['vir_vtextEnabled'] == 'on') { $vir_vtextEnabled = '"1"'; }else{ $vir_vtextEnabled = '"0"';}
if (isset($_POST['vir_emailEnabled']) && $_POST['vir_emailEnabled'] == 'on') { $vir_emailEnabled = '"1"'; }else{ $vir_emailEnabled = '"0"';}
if (empty($_POST['vir_pre_message'])) { $vir_pre_message = 'NULL'; }else{ $vir_pre_message = "\"$_POST[vir_pre_message]\"";}
if (empty($_POST['vir_post_message'])) { $vir_post_message = 'NULL'; }else{ $vir_post_message = "\"$_POST[vir_post_message]\"";}
if (empty($_POST['bom_time'])) { $bom_time = 'NULL'; }else{ $bom_time = "\"$_POST[bom_time]\"";}
if (empty($_POST['eom_time'])) { $eom_time = 'NULL'; }else{ $eom_time = "\"$_POST[eom_time]\"";}
if (isset($_POST['bom_vtextEnabled']) && $_POST['bom_vtextEnabled'] == 'on') { $bom_vtextEnabled = '"1"'; }else{ $bom_vtextEnabled = '"0"';}
if (isset($_POST['eom_vtextEnabled']) && $_POST['eom_vtextEnabled'] == 'on') { $eom_vtextEnabled = '"1"'; }else{ $eom_vtextEnabled = '"0"';}
if (isset($_POST['bom_emailEnabled']) && $_POST['bom_emailEnabled'] == 'on') { $bom_emailEnabled = '"1"'; }else{ $bom_emailEnabled = '"0"';}
if (isset($_POST['eom_emailEnabled']) && $_POST['eom_emailEnabled'] == 'on') { $eom_emailEnabled = '"1"'; }else{ $eom_emailEnabled = '"0"';}
if (empty($_POST['bom_message'])) { $bom_message = 'NULL'; }else{ $bom_message = "\"$_POST[bom_message]\"";}
if (empty($_POST['eom_message'])) { $eom_message = 'NULL'; }else{ $eom_message = "\"$_POST[eom_message]\"";}
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
subtitle = $subtitle,
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
vir_posttrip_message = $vir_post_message,
bom_time = $bom_time,
eom_time = $eom_time,
bom_vtext_enabled = $bom_vtextEnabled,
eom_vtext_enabled = $eom_vtextEnabled,
bom_email_enabled = $bom_emailEnabled,
eom_email_enabled = $eom_emailEnabled,
bom_message = $bom_message,
eom_message = $eom_message,
tsa_date_exp = $tsa_date_exp,
tsa_date_change_exp = $tsa_date_change_exp
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
// Function to show visibility of an object
function show_vis($object_type,$grantee) {
    // Admin can view anything so we'll push him on the array
    array_push($grantee,'admin');
    if ($_SESSION['login'] == 1) { $me = 'admin'; }
    if ($_SESSION['login'] == 2) { $me = 'user'; }
    if ($_SESSION['login'] == 3) { $me = 'onboarder'; }

    switch ($object_type) {
    case "text":
    // Now we cycle through who CAN see this
    foreach ($grantee as $g) {
    switch ($g) {
    case "admin":
    if ($g == $me) {
    return;
    }
    case "user":
    if ($g == $me) {
    return;
    }
    case "onboarder":
    if ($g == $me) {
    return;
    }
    break;
    }
    }
    return " readonly ";
    break;
    case "option":
    // Now we cycle through who CAN see this
    foreach ($grantee as $g) {
    switch ($g) {
    case "admin":
    if ($g == $me) {
    return;
    }
    break;
    case "user":
    if ($g == $me) {
    return;
    }
    break;
    case "onboarder":
    if ($g == $me) {
    return;
    }
    break;
    }
    }
    return ' style="display: none;" ';
    break;
    case "file":
    // Now we cycle through who CAN see this
    foreach ($grantee as $g) {
    switch ($g) {
    case "admin":
    if ($g == $me) {
    return;
    }
    break;
    case "user":
    if ($g == $me) {
    return;
    }
    break;
    case "onboarder":
    if ($g == $me) {
    return;
    }
    break;
    }
    }
    return " disabled ";
    break;
    case "checkbox":
    // Now we cycle through who CAN see this
    foreach ($grantee as $g) {
    switch ($g) {
    case "admin":
    if ($g == $me) {
    return;
    }
    break;
    case "user":
    if ($g == $me) {
    return;
    }
    break;
    case "onboarder":
    if ($g == $me) {
    return;
    }
    break;
    }
    }
    return ' style="display: none;" ';
    break;
    }
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Users</title>
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
    <link rel="stylesheet" href="<?php echo HTTP;?>/dist/css/animate.css">
  </head>
  <body class="skin-blue sidebar-mini">
    <div class="wrapper">
      <?php
      if ($_SESSION['login'] != 3) {
      require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');
      require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');
      }
      ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          Users Profile</h1>
          <?php
          if ($_SESSION['login'] != 3) {
          ?>
          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">User Profile</li>
          </ol>
          <?php
          }
          ?>
        </section>
        <!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->
        <?php
        if ($_SESSION['login'] != 3) {
        require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');
        }
        ?>
        <!-- End Animated Top Menu -->
 
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">
       <?php
        if ($_SESSION['login'] == 1) {
                echo '<label for="activeusers">';
                echo '<input class="radio-inline" name="radio" type="radio" id="activeusers" value="activeusers" checked>';
                echo 'Active Users </label>';

                echo '<label for="inactiveusers">';
                echo '<input class="radio-inline" type="radio" name="radio" id="inactiveusers" value="inactiveusers">';
                echo 'Inactive Users </label>';

                echo '<label for="allusers">';
                echo '<input class="radio-inline" type="radio" name="radio" id="allusers" value="allusers">';
                echo 'All Users </label>';

                echo '<label for="disabled">';
                echo '<input class="radio-inline" type="radio" name="radio" id="disabled" value="disabled">';
                echo 'Disabled </label>';
 
                echo '<label for="onboarding">';
                echo '<input class="radio-inline" type="radio" name="radio" id="onboarding" value="onboarding">';
                echo 'Onboarding </label>';
        }elseif ($_SESSION['login'] == 2) {
                echo '<label for="activeusers">';
                echo '<input class="radio-inline" name="radio" type="radio" id="activeusers" value="activeusers" checked>';
                echo 'Active Users </label>';
        }elseif ($_SESSION['login'] == 3) {
                echo '<label for="onboarding">';
                echo '<input class="radio-inline" type="radio" name="radio" id="onboarding" value="onboarding">';
                echo 'Onboarding </label>';
        }
        ?>

                </h3>
            </div><!-- /.box-header -->

                <div class="box-body table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <?php
                        # Defaults
                        $orderName = 'desc';
                        $glyphName = "top";
                        $orderStatus = 'desc';
                        $glyphStatus = "top";
                        $orderSql = "ORDER BY fname ASC";
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
                      if (($restricted_viewing === true) || ($restrict_onboarding === true))
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
                      subtitle,
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
                      vir_posttrip_message,
                      bom_time,
                      eom_time,
                      bom_vtext_enabled,
                      eom_vtext_enabled,
                      bom_email_enabled,
                      eom_email_enabled,
                      bom_message,
                      eom_message
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
                          <div style="float:right;width:20%;"><a class="glyphicon glyphicon-chevron-<?php if ($_SESSION['login'] != 1) { echo 'down'; }else{ echo 'right'; }?> " role="button" data-toggle="collapse"
                            onClick="$(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-right');"
                            href="#<?php echo $row['username'];?>_details" aria-expanded="false" aria-controls="<?php echo $row['username'];?>_details">
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
                      <tr class="collapse <?php if ($_SESSION['login'] != 1) { echo 'in';}?> " id="<?php echo $row['username'];?>_details">
                        <td colspan="9">
                          <div class="well">
                            <form enctype="multipart/form-data" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit='return validate_user_status(this);'>
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
                                      <option value="Active" <?php if ($row['status'] == 'Active') { echo " selected "; }?>
                                      <?php echo show_vis('option',array());?>>Active</option>
                                      <option value="Onboarding" <?php if ($row['status'] == 'onboarding') { echo " selected "; }?>
                                      <?php echo show_vis('option',array());?>>Onboarding</option>
                                      <option value="Inactive"<?php if ($row['status'] == 'Inactive') { echo " selected "; }?>
                                      <?php echo show_vis('option',array());?>>Inactive</option>
                                      <option value="Disabled"<?php if ($row['status'] == 'Disabled') { echo " selected "; }?>
                                      <?php echo show_vis('option',array());?>>Disabled</option>
                                    </select>
                                  </td>
                                  <td style="padding: 5px">
                                    <label for="role">Role</label>
                                    <select class="form-control" name="role" id="role">
                                      <option value="Employee" <?php if ($row['role'] == 'Employee') { echo " selected "; }?>
                                      <?php echo show_vis('option',array());?>>Employee</option>
                                      <option value="Admin"<?php if ($row['role'] == 'Admin') { echo " selected "; }?>
                                      <?php echo show_vis('option',array());?>>Admin</option>
                                    </select>
                                  </td>
                                  <td style="padding: 5px">
                                    <label for="office">Office</label>
                                    <select class="form-control" name="office" id="office">
                                      <option value="PHX" <?php if ($row['office'] == 'PHX') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>PHX</option>
                                      <option value="TUS"<?php if ($row['office'] == 'TUS') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>TUS</option>
                                      <option value="PHL" <?php if ($row['office'] == 'PHL') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>PHL</option>
                                      <option value="DEN" <?php if ($row['office'] == 'DEN') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>DEN</option>
                                      <option value="LAX" <?php if ($row['office'] == 'LAX') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>LAX</option>
                                      <option value="MIA" <?php if ($row['office'] == 'MIA') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>MIA</option>
                                      <option value="ORD" <?php if ($row['office'] == 'ORD') { echo " selected "; }?>
                                      <?php echo show_vis('option',array('onboarder'));?>>ORD</option>
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
                                    <select class="form-control" name="state" id="state">
                                      <?php
                                      foreach($us_state_abbrevs as $state) {
                                        echo "<option value=\"$state\""; if ($row['state'] == $state) { echo ' selected '; }; echo ">$state</option>";
                                      }
                                      ?>
                                    </select>
                                  </td>
                                  <td style="padding: 5px">
                                    <label for="zip">Home Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="<?php echo $row['zipcode'];?>">
                                  </td>
                                  <td style="padding: 5px">
                                    <label for="jobTitle">Title</label>
                                    <?php $foo = show_vis('option',array('onboarder'));?>
                                    <?php error_log($foo);?>
                                    <select class="form-control" name="jobTitle" id="jobTitle">
                                      <?php
                                      if ($row['subtitle'] === null) {
                                        $subtitle = $row['title'];
                                      }else{
                                        $subtitle = $row['subtitle'];
                                      }
                                      ?>
                                      <option value="Office" <?php if ($row['title'] == 'Office') { echo " selected "; } ?> >Office</option>
                                      <option value="Dispatch"<?php if ($row['title'] == 'Dispatch') { echo " selected "; }?> <?php echo show_vis('option',array('onboarder'));?>>Dispatch</option>
                                      <option value="Accounting" <?php if ($row['title'] == 'Accounting') { echo " selected "; }?> <?php echo show_vis('option',array('onboarder'));?>>Accounting</option>
                                      <option value="Driver - OTR"<?php if ($subtitle == 'OTR') { echo " selected "; }?> <?php echo show_vis('option',array('onboarder'));?>>Driver - OTR</option>
                                      <option value="Driver - Local"<?php if ($subtitle == 'Local') { echo " selected "; }?> <?php echo show_vis('option',array('onboarder'));?>>Driver - Local</option>
                                      <option value="Driver - Both"<?php if (($subtitle == 'Driver') or ($subtitle == 'Both')) { echo " selected "; }?> <?php echo show_vis('option',array('onboarder'));?>>Driver - Both</option>
                                      <option value="Mechanic"<?php if ($row['title'] == 'Mechanic') { echo " selected "; }?> <?php echo show_vis('option',array('onboarder'));?>>Mechanic</option>
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
                                <!--
                                <td style="padding: 5px">
                                  <label for="vtext">Vtext</label>
                                  <input type="vtext" class="form-control" name="vtext" id="vtext" placeholder="" value="<?php echo $row['vtext'];?>">
                                </td>
                                -->
                                <td style="padding: 5px">
                                  <label for="textUpdates" style="margin-top: 8px; margin-bottom: 0px;">Enable</label>
                                  <table>
                                    <tr><td>
                                      <div class="checkbox">
                                        <label>
                                          <input name="vtextEnabled" id="vtextEnabled" type="checkbox" value="on" <?php if ($row['vtextupdate'] == "1") { echo "checked"; }?> <?php echo show_vis('checkbox',array('')); ?>>
                                        </label>
                                      </div>
                                    </div>
                                  </td>
                                </tr></table>
                                <label for="VtextHelp"></label>
                                <select name="VtextHelp" id="VtextHelp" class="form-control">
                                  <?php
                                  foreach ($vtext_providers as $key => $value) {
                                  echo "<option value=\"$key\">$value</option>";
                                  }
                                  ?>
                                </select>
                              </td>
                              <td style="padding: 5px">
                                <label for="quietTimeVal1">Quiet (start)</label>
                                <select class="form-control" name="quietTimeVal1" id="quietTimeVal1" data-toggle="tooltip" data-placement="top" title="Quiet (START) will turn off your email and text messages at this time. Leaving 0:00 to 0:00 will leave messages on 24 7">
                                  <?php
                                  foreach ($time_selectors as $t) {
                                  echo "<option value=\"$t\""; if ($row['quiet_time_begin'] == $t) { echo " selected "; }; echo ">$t</option>";
                                  }
                                  ?>
                                </select>
                              </td>
                              <td style="padding: 5px">
                                <label for="quietTimeVal2">Quiet (end)</label>
                                <select class="form-control" name="quietTimeVal2" id="quietTimeVal2" data-toggle="tooltip" data-placement="top" title="Quite (END) will turn on your email and text messages at this time. Leaving 0:00 to 0:00 will leave messages on 24 7">
                                  <?php
                                  foreach ($time_selectors as $t) {
                                  echo "<option value=\"$t\""; if ($row['quiet_time_end'] == $t) { echo " selected "; }; echo ">$t</option>";
                                  }
                                  ?>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding: 5px">
                                <label for="ssn">SSN</label>
                                <input type="text" class="form-control" name="ssn" id="ssn" placeholder="" value="<?php echo $row['ssn'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="dob">DOB</label>
                                <input type="text" class="form-control datepicker" name="dob" id="dob" placeholder="mm/dd/yyyy" value="<?php echo $row['dob'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="driverLicense">License No.</label>
                                <input type="text" class="form-control" name="driverLicense" id="driverLicense" placeholder="" value="<?php echo $row['driver_license_n'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="driverLicenseExpire">License Exp.</label>
                                <input type="text" class="form-control datepicker" name="driverLicenseExpire" id="driverLicenseExpire" placeholder="mm/dd/yyyy" value="<?php echo $row['driver_license_exp'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="mobilePhone">Mobile</label>
                                <input type="number" class="form-control" name="mobilePhone" id="mobilePhone" placeholder="" value="<?php echo $row['driverid'];?>">
                              </td>
                              <td style="padding: 5px">
                                <label for="startDate">Start Date</label>
                                <input type="text" class="form-control datepicker" name="startDate" id="startDate" placeholder="mm/dd/yyyy" value="<?php echo $row['start_dt'];?>" <?php echo show_vis('text',array(''));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="departureDate">Depart Date</label>
                                <input type="text" class="form-control datepicker" data-date-format="mm/dd/yyyy" name="departureDate" id="departureDate" placeholder="mm/dd/yyyy" value="<?php echo $row['depart_dt'];?>" <?php echo show_vis('text',array(''));?>>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding: 5px">
                                <label for="departureReason">Depart Reason</label>
                                <input type="text" class="form-control" name="departureReason" id="departureReason" placeholder="" value="<?php echo $row['depart_reason'];?>" <?php echo show_vis('text',array(''));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="" value="<?php echo $row['username'];?>" <?php echo show_vis('text',array(''));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="password">Password</label>
                                <input type="text" class="form-control" name="password" id="password" placeholder="" value="<?php echo $row['password'];?>">
                              </td>
                              <td style="padding: 5px">
                                <label for="medCardExpire">Med Exp Date</label>
                                <input type="text" class="form-control datepicker" name="medCardExpire" id="medCardExpire" placeholder="mm/dd/yyyy" value="<?php echo $row['med_card_exp'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="salary">Salary</label>
                                <input type="text" class="form-control" name="salary" id="salary" placeholder="" value="<?php echo $row['salary'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="emergencyPhone">Emerg Phone</label>
                                <input type="text" class="form-control" name="emergencyPhone" id="emergencyPhone" placeholder="" value="<?php echo $row['emerg_contact_phone'];?>">
                              </td>
                              <td style="padding: 5px">
                                <label for="tsa">TSA-STA</label>
                                <input type="text" class="form-control" name="tsa" id="tsa" placeholder="" value="<?php echo $row['tsa_sta'];?>" <?php echo show_vis('text',array('onboarder'));?>>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding: 5px">
                                <label for="emergencyContact">Emerg Contact</label>
                                <input type="text" class="form-control" name="emergencyContact" id="emergencyContact" placeholder="" value="<?php echo $row['emerg_contact_name'];?>">
                              </td>
                              <td style="padding: 5px" colspan="3">
                                <label for="miscDetails">Notes</label>
                                <textarea class="form-control" name="notes" id="notes" placeholder="" value="" style="padding-top: 0px; padding-bottom: 0px; height: 34px;" <?php echo show_vis('text',array(''));?>><?php echo $row['notes'];?></textarea>
                              </td>
                              <td style="padding: 5px">
                                <label for="tsaExpire">TSA Exp. Date</label>
                                <input type="text" class="form-control datepicker" name="tsaExpire" id="tsaExpire" placeholder="mm/dd/yyyy" value="">
                            </td>
                            <td style="padding: 5px">
                                <label for="tsaChange">TSA Date Change Exp.</label>
                                <input type="text" class="form-control datepicker" name="tsaChange" id="tsaChange" placeholder="mm/dd/yyyy" value="">
                            </td>
                            </tr>
                            <tr>
                              <td style="padding: 5px">
                                <label for="tsPhone">TS Phone</label>
                                <input type="text" class="form-control" name="tsPhone" id="tsPhone" placeholder="" value="<?php echo $row['ts_phone'];?>" <?php echo show_vis('text',array(''));?>>
                              </td>
                              <td style="padding: 5px">
                                <label for="tsName">TS Name</label>
                                <input type="text" class="form-control" name="tsName" id="tsName" placeholder="" value="<?php echo $row['ts_name'];?>" <?php echo show_vis('text',array(''));?>>
                              </td>
                            </tr>
                            <tr>
                              <td style="padding: 5px">
                                <label for="vir_pretrip_time">VIR Pretrip</label>
                                <select class="form-control" name="vir_pretrip_time" id="vir_pretrip_time" data-toggle="tooltip" data-placement="top" title="PreTrip (START) will turn on your email and text messages at this time. Leaving 0:00 to 0:00 will leave messages on 24 7">
                                  <?php
                                  foreach ($time_selectors as $t) {
                                  echo "<option value=\"$t\""; if ($row['vir_pretrip_time'] == $t) { echo " selected "; }; echo ">$t</option>";
                                  }
                                  ?>
                                </select>
                              </td>
                              <td style="padding: 5px">
                                <label for="vir_posttrip_time">VIR Posttrip</label>
                                <select class="form-control" name="vir_posttrip_time" id="vir_posttrip_time" data-toggle="tooltip" data-placement="top" title="PostTrip (END) will turn on your email and text messages at this time. Leaving 0:00 to 0:00 will leave messages on 24 7">
                                  <?php
                                  foreach ($time_selectors as $t) {
                                  echo "<option value=\"$t\""; if ($row['vir_posttrip_time'] == $t) { echo " selected "; }; echo ">$t</option>";
                                  }
                                  ?>
                                </select>
                              </td>
                              <td style="padding: 5px"><label for="vir_text_updates" style="margin-top: 8px; margin-bottom: 0px;">VIR Text</label>
                              <table>
                                <tr>
                                  <td><div class="checkbox">
                                    <label>
                                      <input name="vir_vtextEnabled" id="vir_vtextEnabled" type="checkbox" value="on" <?php if ($row['vir_vtext_enabled'] == "1") { echo "checked"; }
                                      ?> data-toggle="tooltip" data-placement="top" title="Check box if you would like to recieve VIR Pre/Post trip notifications.">
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
                                    <input name="vir_emailEnabled" id="vir_emailEnabled" type="checkbox" value="on" <?php if ($row['vir_email_enabled'] == "1") { echo "checked"; }
                                    ?> data-toggle="tooltip" data-placement="top" title="Check box if you would like to recieve Email Pre/Post trip notifications.">
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
                          <td style="padding: 5px">
                            <label for="bom_time">BOM Time</label>
                            <select class="form-control" name="bom_time" id="bom_time" data-toggle="tooltip" data-placement="top" title="Select the time you would like to receive Begining of Month (BOM) messages. Leaving 0:00 to 0:00 will send message at midnight">
                              <?php
                              foreach ($time_selectors as $t) {
                              echo "<option value=\"$t\""; if ($row['bom_time'] == $t) { echo " selected "; }; echo ">$t</option>";
                              }
                              ?>
                            </select>
                          </td>
                          <td style="padding: 5px">
                            <label for="eom_time">EOM Time</label>
                            <select class="form-control" name="eom_time" id="eom_time" data-toggle="tooltip" data-placement="top" title="Select the time you would like to receive End of Month (EOM) messages. Leaving 0:00 to 0:00 will send message at midnight">
                              <?php
                              foreach ($time_selectors as $t) {
                              echo "<option value=\"$t\""; if ($row['eom_time'] == $t) { echo " selected "; }; echo ">$t</option>";
                              }
                              ?>
                            </select>
                          </td>
                          <td style="padding: 5px"><label for="bom_text_updates" style="margin-top: 8px; margin-bottom: 0px;">BOM Text</label>
                          <table>
                            <tr>
                              <td><div class="checkbox">
                                <label>
                                  <input name="bom_vtextEnabled" id="bom_vtextEnabled" type="checkbox" value="on" <?php if ($row['bom_vtext_enabled'] == "1") { echo "checked"; }
                                  ?> data-toggle="tooltip" data-placement="top" title="Check box if you would like to recieve Begining of Month trip notifications via text.">
                                </label>
                              </div>
                            </div></td>
                          </tr>
                        </table></td>
                        <td style="padding: 5px"><label for="eom_text_updates" style="margin-top: 8px; margin-bottom: 0px;">EOM Text</label>
                        <table>
                          <tr>
                            <td><div class="checkbox">
                              <label>
                                <input name="eom_vtextEnabled" id="eom_vtextEnabled" type="checkbox" value="on" <?php if ($row['eom_vtext_enabled'] == "1") { echo "checked"; }
                                ?> data-toggle="tooltip" data-placement="top" title="Check box if you would like to recieve End of Month trip notifications via text.">
                              </label>
                            </div>
                          </div></td>
                        </tr>
                      </table></td>
                      <td style="padding: 5px"><label for="bom_email_updates" style="margin-top: 8px; margin-bottom: 0px;">BOM Email</label>
                      <table>
                        <tr>
                          <td><div class="checkbox">
                            <label>
                              <input name="bom_emailEnabled" id="bom_emailEnabled" type="checkbox" value="on" <?php if ($row['bom_email_enabled'] == "1") { echo "checked"; }
                              ?> data-toggle="tooltip" data-placement="top" title="Check box if you would like to recieve begining of Month trip notifications via email.">
                            </label>
                          </div>
                        </div></td>
                      </tr>
                    </table></td>
                    <td style="padding: 5px"><label for="eom_email_updates" style="margin-top: 8px; margin-bottom: 0px;">EOM Email</label>
                    <table>
                      <tr>
                        <td><div class="checkbox">
                          <label>
                            <input name="eom_emailEnabled" id="eom_emailEnabled" type="checkbox" value="on" <?php if ($row['eom_email_enabled'] == "1") { echo "checked"; }?> data-toggle="tooltip" data-placement="top" title="Check box if you would like to recieve End of Month trip notifications via email.">
                          </label>
                        </div>
                      </div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td style="padding: 5px">
                    <label for="bom_message">BOM Message</label>
                    <input type="text" class="form-control" name="bom_message" id="bom_message" placeholder="" value="<?php echo $row['bom_message'];?>">
                  </td>
                  <td style="padding: 5px">
                    <label for="eom_message">EOM Message</label>
                    <input type="text" class="form-control" name="eom_message" id="eom_message" placeholder="" value="<?php echo $row['eom_message'];?>">
                  </td>
                </tr>
                <tr>
                  <td colspan="1" style="padding: 5px">
                    <label for="contract">Contract</label>
                    <a href="<?php if (isset($row['contract'])) { echo HTTP . $row['contract']; }?>" target="_blank"><?php if (isset($row['contract'])) { echo $row['contract']; }?></a>
                    <div><input id="contractUpload" name="contractUpload" type="file" multiple=true class="file-loading" <?php echo show_vis('file',array(''));?>></div>
                  </td>
                  <td colspan="1" style="padding: 5px">
                    <label for="fuelcard">Fuel Card</label>
                    <a href="<?php if (isset($row['fuelcard'])) { echo HTTP . $row['fuelcard']; }?>" target="_blank"><?php if (isset($row['fuelcard'])) { echo $row['fuelcard']; }?></a>
                    <div><input id="fuelUpload" name="fuelUpload" type="file" multiple=true class="file-loading" <?php echo show_vis('file',array(''));?>></div>
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
          onClick="$(this).toggleClass('glyphicon-chevron-down glyphicon-chevron-right');"
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
                    <option value="Onboarding">Onboarding</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Disabled">Disabled</option>
                  </select>
                </td>
                <td style="padding: 5px">
                  <label for="role">Role</label>
                  <select class="form-control" name="role" id="role">
                    <option value="Employee">Employee</option>
                    <option value="Admin">Admin</option>
                    <option value="Employee" selected>Basic User</option>
                    <option value="Supervisor">Supervisor</option>
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
                    <option value="Driver - OTR">Driver - OTR</option>
                    <option value="Driver - Local">Driver - Local</option>
                    <option value="Driver - Both">Driver - Both</option>
                    <option value="Mechanic">Mechanic</option>
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
                          <input name="emailEnabled" id="emailEnabled" type="checkbox" value="on" checked="true">
                        </label>
                      </div>
                    </div>
                  </td>
                </tr></table>
              </td>
              <!--
              <td style="padding: 5px">
                <label for="vtext">Vtext</label>
                <input type="text" class="form-control" name="vtext" id="vtext" placeholder="" value="">
              </td>
              -->
              <td style="padding: 5px">
                <label for="textUpdates" style="margin-top: 8px; margin-bottom: 0px;">Enable</label>
                <table width="27" height="45">
                  <tr><td height="41">
                    <div class="checkbox">
                      <label>
                        <input name="vtextEnabled" id="vtextEnabled" type="checkbox" value="on" >
                      </label>
                    </div></td>
                  </tr></table>
                  <label for="VtextHelp"></label>
                  <select name="VtextHelp" id="VtextHelp">
                    <?php
                    foreach ($vtext_providers as $key => $value) {
                    echo "<option value=\"$key\">$value</option>";
                    }
                    ?>
                  </select></td>
                  <td style="padding: 5px">
                    <label for="quietTimeVal1">Quiet (start)</label>
                    <select class="form-control" name="quietTimeVal1" id="quietTimeVal1">
                      <?php
                      foreach ($time_selectors as $t) {
                      echo "<option value=\"$t\""; echo "$t</option>";
                      }
                      ?>
                    </select>
                  </td>
                  <td style="padding: 5px">
                    <label for="quietTimeVal2">Quiet (end)</label>
                    <select class="form-control" name="quietTimeVal2" id="quietTimeVal2">
                      <?php
                      foreach ($time_selectors as $t) {
                      echo "<option value=\"$t\""; echo "$t</option>";
                      }
                      ?>
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
                    <input type="text" class="form-control datepicker" name="dob" id="dob" placeholder="mm/dd/yyyy" value="">
                  </td>
                  <td style="padding: 5px">
                    <label for="driverLicense">License No.</label>
                    <input type="text" class="form-control" name="driverLicense" id="driverLicense" placeholder="" value="">
                  </td>
                  <td style="padding: 5px">
                    <label for="driverLicenseExpire">License Exp.</label>
                    <input type="text" class="form-control datepicker" name="driverLicenseExpire" id="driverLicenseExpire" placeholder="mm/dd/yyyy" value="">
                  </td>
                  <td style="padding: 5px">
                    <label for="mobilePhone">Mobile</label>
                    <input type="text" class="form-control" name="mobilePhone" id="mobilePhone" placeholder="" value="">
                  </td>
                  <td style="padding: 5px">
                    <label for="startDate">Start Date</label>
                    <input type="text" class="form-control datepicker" name="startDate" id="startDate" placeholder="mm/dd/yyyy" value="">
                  </td>
                  <td style="padding: 5px">
                    <label for="departureDate">Depart Date</label>
                    <input type="text" class="form-control datepicker" data-date-format="mm/dd/yyyy" name="departureDate" id="departureDate" placeholder="mm/dd/yyyy" value="">
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
                    <input type="text" class="form-control datepicker" name="medCardExpire" id="medCardExpire" placeholder="mm/dd/yyyy" value="">
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
                  <td style="padding: 5px">
                    <label for="tsaExpire">TSA Exp. Date</label>
                    <input type="text" class="form-control datepicker" name="tsaExpire" id="tsaExpire" placeholder="mm/dd/yyyy" value="">
                  </td>
                  <td style="padding: 5px">
                    <label for="tsaChange">TSA Date Change Exp.</label>
                    <input type="text" class="form-control datepicker" name="tsaChange" id="tsaChange" placeholder="mm/dd/yyyy" value="">
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
                      <?php
                      foreach ($time_selectors as $t) {
                      echo "<option value=\"$t\">$t</option>";
                      }
                      ?>
                    </select>
                  </td>
                  <td style="padding: 5px">
                    <label for="vir_posttrip_time">VIR Posttrip</label>
                    <select class="form-control" name="vir_posttrip_time" id="vir_posttrip_time">
                      <?php
                      foreach ($time_selectors as $t) {
                      echo "<option value=\"$t\">"; echo "$t</option>";
                      }
                      ?>
                    </select>
                  </td>
                  <td style="padding: 5px"><label for="vir_text_updates" style="margin-top: 8px; margin-bottom: 0px;">VIR Text</label>
                  <table>
                    <tr>
                      <td><div class="checkbox">
                        <label>
                          <input name="vir_vtextEnabled" id="vir_vtextEnabled" type="checkbox" value="on" checked="true">
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
                        <input name="vir_emailEnabled" id="vir_emailEnabled" type="checkbox" value="on" checked="true">
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
              <td style="padding: 5px">
                <label for="bom_time">BOM Time</label>
                <select class="form-control" name="bom_time" id="bom_time">
                  <?php
                  foreach ($time_selectors as $t) {
                  echo "<option value=\"$t\""; echo "$t</option>";
                  }
                  ?>
                </select>
              </td>
              <td style="padding: 5px">
                <label for="eom_time">EOM Time</label>
                <select class="form-control" name="eom_time" id="eom_time">
                  <?php
                  foreach ($time_selectors as $t) {
                  echo "<option value=\"$t\""; echo "$t</option>";
                  }
                  ?>
                </select>
              </td>
              <td style="padding: 5px"><label for="bom_text_updates" style="margin-top: 8px; margin-bottom: 0px;">BOM Text</label>
              <table>
                <tr>
                  <td><div class="checkbox">
                    <label>
                      <input name="bom_vtextEnabled" id="bom_vtextEnabled" type="checkbox" value="" checked="true">
                    </label>
                  </div>
                </div></td>
              </tr>
            </table></td>
            <td style="padding: 5px"><label for="eom_text_updates" style="margin-top: 8px; margin-bottom: 0px;">EOM Text</label>
            <table>
              <tr>
                <td><div class="checkbox">
                  <label>
                    <input name="eom_vtextEnabled" id="eom_vtextEnabled" type="checkbox" value="" checked="true">
                  </label>
                </div>
              </div></td>
            </tr>
          </table></td>
          <td style="padding: 5px"><label for="bom_email_updates" style="margin-top: 8px; margin-bottom: 0px;">BOM Email</label>
          <table>
            <tr>
              <td><div class="checkbox">
                <label>
                  <input name="bom_emailEnabled" id="bom_emailEnabled" type="checkbox" value="" checked="true">
                </label>
              </div>
            </div></td>
          </tr>
        </table></td>
        <td style="padding: 5px"><label for="eom_email_updates" style="margin-top: 8px; margin-bottom: 0px;">EOM Email</label>
        <table>
          <tr>
            <td><div class="checkbox">
              <label>
                <input name="eom_emailEnabled" id="eom_emailEnabled" type="checkbox" value="" checked="true">
              </label>
            </div>
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td style="padding: 5px">
        <label for="bom_message">BOM Message</label>
        <input type="text" class="form-control" name="bom_message" id="bom_message" placeholder="" value="" data-toggle="tooltip" data-placement="top" title="Select the time you would like to receive Begining of Month (BOM) messages. Leaving 0:00 to 0:00 will send message at midnight">
      </td>
      <td style="padding: 5px">
        <label for="eom_message">EOM Message</label>
        <input type="text" class="form-control" name="eom_message" id="eom_message" placeholder="">
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
<!-- Date Picker -->
<script src="<?php echo HTTP;?>/dist/js/bootstrap-datepicker.js"></script>
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
    <?php 
    if (($_SESSION['login'] == 1) || ($_SESSION['login'] == 2)) {
        echo "$('[name=\"Active\"]').show();";    
        echo "$('[name=\"Inactive\"]').hide();";
        echo "$('[name=\"onboarding\"]').hide();";
        echo "$('[name=\"Disabled\"]').hide();";
    }elseif ($_SESSION['login'] == 3) {
        echo "$('[name=\"Active\"]').hide();";    
        echo "$('[name=\"Inactive\"]').hide();";
        echo "$('[name=\"onboarding\"]').show();";
        echo "$('[name=\"Disabled\"]').hide();";
    }
    ?>
    $("#activeusers").click(function() {
        $('[name="Active"]').show();
        $('[name="Inactive"]').hide();
        $('[name="onboarding"]').hide();
        $('[name="Disabled"]').hide();
    });
    $("#inactiveusers").click(function() {
        $('[name="Active"]').hide();
        $('[name="Inactive"]').show();
        $('[name="onboarding"]').hide();
        $('[name="Disabled"]').hide();
    });
    $("#onboarding").click(function() {
        $('[name="Active"]').hide();
        $('[name="Inactive"]').hide();
        $('[name="onboarding"]').show();
        $('[name="Disabled"]').hide();
    });
    $("#disabled").click(function() {
        $('[name="Active"]').hide();
        $('[name="Inactive"]').hide();
        $('[name="onboarding"]').hide();
        $('[name="Disabled"]').show();
    });
    $("#allusers").click(function() {
        $('[name="Active"]').show();
        $('[name="Inactive"]').show();
        $('[name="onboarding"]').show();
        $('[name="Disabled"]').show();
    });
});
function validate_user_status(i) {
// Make sure that, when we submit, if the status is not
// active then make sure there is a departureDate
var my_status = $(i).find('#status').val();
var my_departureDate = $(i).find('#departureDate').val();
if (my_status != 'Active') {
if (my_departureDate == false) {
alert('Termination date not entered!');
return false;
}
}
}
// Date Picker
$('body').on('focus',".datepicker", function(){
$(this).datepicker({
startDate: "2015-01-01",
todayBtn: "linked",
autoclose: true,
datesDisabled: '0',
todayHighlight: true,
});
});

// Don't allow non-digits in mobile phone
$(document).ready(function () {
  //called when key is pressed in textbox
  $("#mobilePhone").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
   });
});

</script>
</body>
</html>