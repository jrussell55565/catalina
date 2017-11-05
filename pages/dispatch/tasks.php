<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// General users array
$driver_array = get_drivers($mysqli);
$all_users_array = get_all_users($mysqli,'Active');

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];
$role = $_SESSION['role'];

// Process POST requests first.
if (isset($_POST['ajax_complete_task'])){    
  // Ajax request to delete a task
  $delete_sql = "update tasks set complete_user = 1
                where id = ".$_POST['id'];
  
  # Start TX
  $mysqli->autocommit(FALSE);
  $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

  try {    
    if ($mysqli->query($delete_sql) === false)
    {
        throw new Exception("Error updating task: ".$mysqli->error);
    }
  $mysqli->commit();
  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $mysqli->rollback();
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    exit;
  }
}

if ( (isset($_POST['btn_ack_task'])) || (isset($_POST['btn_complete_task'])) ) {  
  if (isset($_POST['btn_ack_task'])){
    // This is a user acknowledging that they read their tasks
    $id = $_POST['btn_ack_task'];

    $update_task = "update tasks set
      user_ack = 1
      where id = $id";
  }

  if (isset($_POST['btn_complete_task'])){
    // This is a user acknowledging that they read their tasks
    $id = $_POST['btn_complete_task'];

    $update_task = "update tasks set
      complete_user = 1
      where id = $id";
  }

  $new_note = $_POST['new_note'];
  if ($new_note != '') {
    // Let's insert the new note
    $insert_note = "insert into task_notes (task_id, note) values 
    ($id, '$new_note')";
  }else{
    $insert_note = "SELECT 1 FROM DUAL";
  }

  # Start TX
  $mysqli->autocommit(FALSE);
  $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

  try {    
    if ($mysqli->query($update_task) === false)
    {
        throw new Exception("Error updating task: ".$mysqli->error);
    }
    if ($mysqli->query($insert_note) === false)
    {
        throw new Exception("Error inserting note for task: ".$mysqli->error);
    }
    $mysqli->commit();
  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    header("location: /pages/dispatch/tasks.php?error=$url_error");
    exit;
  }
}

if (isset($_POST['btn_update_task'])) {
  // We're updating a record.
  $category = $_POST['task_category'];
  $item = $_POST['task_item'];
  $subitem = $_POST['task_subitem'];
  $pos_neg = $_POST['task_pos_neg'];
  if(empty($_POST['task_due_date'])) {
    $due_date = 'null';
  }else{
    $due_date = "str_to_date('".$_POST['task_due_date']."','%m/%d/%Y')";
  }
  if(empty($_POST['task_completed_date'])) {
    $completed_date = 'null';
  }else{
    $completed_date = "str_to_date('".$_POST['task_completed_date']."','%m/%d/%Y')";
  }
  $points = $_POST['task_points'];
  $complete_user = $_POST['task_completed_user'];
  $complete_approved = $_POST['task_completed_admin'];
  $internal_only = $_POST['task_visibility'];
  $assign_by = $_POST['task_assign_by'];
  $id = $_POST['btn_update_task'];

  $new_note = $_POST['new_note'];

  $update_task = "update tasks set
    category = '$category',
    item = '$item',
    subitem = '$subitem',
    pos_neg = '$pos_neg',
    due_date = $due_date,
    completed_date = $completed_date,
    points = $points,
    complete_user = $complete_user,
    complete_approved = $complete_approved,
    internal_only = $internal_only,
    assigned_by = '$assign_by'
    where id = $id";

  if ($new_note != '') {
    // Let's insert the new note
    $insert_note = "insert into task_notes (task_id, note) values 
    ($id, '$new_note')";
  }else{
    $insert_note = "SELECT 1 FROM DUAL";
  }
    
  # Start TX
  $mysqli->autocommit(FALSE);
  $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

  try {    
    if ($mysqli->query($update_task) === false)
    {
        throw new Exception("Error updating task: ".$mysqli->error);
    }
    if ($mysqli->query($insert_note) === false)
    {
        throw new Exception("Error inserting note for task: ".$mysqli->error);
    }
    $mysqli->commit();
  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    header("location: /pages/dispatch/tasks.php?error=$url_error");
    exit;
  }
}

if (isset($_POST['btn_add_task'])) {
  // We're updating a record.
  $category = $_POST['task_category'];
  $item = $_POST['task_item'];
  $subitem = $_POST['task_subitem'];
  $pos_neg = $_POST['task_pos_neg'];
  if(empty($_POST['task_due_date'])) {
    $due_date = 'null';
  }else{
    $due_date = "str_to_date('".$_POST['task_due_date']."','%m/%d/%Y')";
  }
  if(empty($_POST['task_completed_date'])) {
    $completed_date = 'null';
  }else{
    $completed_date = "str_to_date('".$_POST['task_completed_date']."','%m/%d/%Y')";
  }
  $points = $_POST['task_points'];
  $complete_user = $_POST['task_completed_user'];
  $complete_approved = $_POST['task_completed_admin'];
  $internal_only = $_POST['task_visibility'];
  $assign_to = $_POST['task_assign_to'];
  $assign_by = $_POST['task_assign_by'];
  $id = $_POST['btn_update_task'];

  $new_note = $_POST['new_note'];

  $insert_sql = "INSERT INTO tasks (submit_date
                          ,assign_to
                          ,assigned_by
                          ,category
                          ,item
                          ,subitem
                          ,pos_neg                          
                          ,due_date
                          ,completed_date
                          ,points
                          ,complete_user
                          ,complete_approved
                          ,internal_only)
                     values (
                            curdate(),
                           '$assign_to',
                           '$assign_by',
                           '$category',
                           '$item',
                           '$subitem',
                           '$pos_neg',
                           $due_date,
                           $completed_date,
                           $points,
                           $complete_user,
                           $complete_approved,
                           $internal_only
                           )";
                           
    
  # Start TX
  $mysqli->autocommit(FALSE);
  $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

  try {    
    if ($mysqli->query($insert_sql) === false)
    {
        throw new Exception("Error updating task: ".$mysqli->error);
    }
    // Get the id of the last insert
    $task_id = $mysqli->insert_id;

    if (sizeof($new_note) > 0) {
      // Let's insert the new note
      $insert_note = "insert into task_notes (task_id, note) values 
      ($task_id, '$new_note')";
    }    
    if ($mysqli->query($insert_note) === false)
    {
        throw new Exception("Error creating new task: ".$mysqli->error);
    }
    $mysqli->commit();

    // Send an email out to the task recipient.
    //// Get the employee email address
    for($i=0;$i<count($all_users_array);$i++) {
      if ($all_users_array[$i]['employee_id'] == $_POST['task_assign_to']) {
        $employee_email = $all_users_array[$i]['email'];
      }
    }
    //// Get the employee vtext address
    for($i=0;$i<count($all_users_array);$i++) {
      if ($all_users_array[$i]['employee_id'] == $_POST['task_assign_to']) {
        $employee_vtext = $all_users_array[$i]['vtext'];
      }
    }
    //// Get the name of the person who created the task
    for($i=0;$i<count($all_users_array);$i++) {
      if ($all_users_array[$i]['employee_id'] == $_POST['task_assign_by']) {
        $assigned_by = $all_users_array[$i]['name'];
      }
    }
    if (empty($employee_email)) {
      // We did not find a suitable email address.  Throw an exception
      throw new Exception("Unable to find an email address for ".$_POST['task_assign_to']);
    }

    // Also send a text message to the recipient
    if (empty($employee_vtext)) {
      // We did not find a suitable vtext address.  Throw an exception
      throw new Exception("Unable to find a vtext address for ".$_POST['task_assign_to']);
    }
    $body = "You have been assigned a new Task. Please login to the driver boards to the home dash board. \n";
  	$body .= "Task ID: ".$task_id."\n";
  	$body .= "Assigned by: ".$assigned_by."\n";
    $body .= "Category: ".$category."\n";
    $body .= "Item: ".$item."\n";
    $body .= "Sub: ".$subitem."\n";
    $body .= "+/-: ".$pos_neg."\n";
    $body .= "Points: ".$points."\n";
    $body .= "Notes: ".$new_note."\n";
    $body .= "Please call dispatch if you have any questions 520-664-9188.  This task has been added to your user profile also.  Thank you. \n";    
	
    // Only send the email if internal_only is a 0
    if ($internal_only == 1) {
      // Override the address
      $employee_email = 'dispatch@catalinacartage.com';
      sendEmail($employee_email, 'New Task Alert', $body, 'drivers@catalinacartage.com');
    }else{
		// Need to add the task_id to the subject line......
      sendEmail($employee_email, 'New Task Alert ('.$task_id.')', $body, 'drivers@catalinacartage.com');
      sendEmail($employee_vtext, 'New Task Alert ('.$task_id.')', $body);
    }
  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    header("location: /pages/dispatch/tasks.php?error=$url_error");
    exit;
  }
  header("location: /pages/dispatch/tasks.php");
}

// Get the existing rows from the task table
// We limit the query to some GET vars if they're set.  Otherwise
// we just get them all.

// Defaults
$driver_predicate = '1=1';
$task_status_predicate = 'complete_approved = 0';  

// Overrides
if (isset($_GET['search']) && $_GET['search'] == 'true')
{
  if ($_GET['search_employee'] == 'all') {
    $driver_predicate = '1=1';
  }else{
    $driver_predicate = 'assign_to = "'.$_GET['search_employee'].'"'; 
  }
  if ($_GET['task_status'] == 'all') 
  { 
    $task_status_predicate = '1=1'; 
  }elseif ($_GET['task_status'] == 'open') {
    $task_status_predicate = 'complete_user = 0 AND complete_approved = 0';
  }elseif ($_GET['task_status'] == 'closed') {
    $task_status_predicate = 'complete_user = 1 AND complete_approved = 1';
  }
} else {
  // If we're a non-admin we only get to see our tasks (and they can't be internal_only)
  if ($_SESSION['login'] == 2) {
    $driver_predicate = "assign_to = '".$_SESSION['employee_id']."' and internal_only = 0 AND complete_user = 0";  
  }else{
    // The default, only get the Active employees
    $driver_predicate = 'users.status = "Active"';
  }
}

$task_sql = get_task_nonaggregate($driver_predicate, $task_status_predicate);
$tasks_aggregate = get_sql_results($task_sql,$mysqli);

// Get the notes for each task
$task_note_array = get_sql_results("select note.id, note.task_id, note.note, date_format(note.created_date, '%m/%d/%y') as created_date from task_notes note",$mysqli);

// Get the tasks_items
$task_items_array = get_sql_results("select * from tasks_items",$mysqli);
$task_cat_item = [];
$task_item_item = [];
$task_item_subitem = [];
for($cat_i=0;$cat_i<count($task_items_array);$cat_i++)
{
  array_push($task_cat_item, $task_items_array[$cat_i]['task_category']);
  array_push($task_item_item, $task_items_array[$cat_i]['task_item']);
  array_push($task_item_subitem, $task_items_array[$cat_i]['task_sub_item']);
}
$task_cat_item = array_unique($task_cat_item);
$task_item_item = array_unique($task_item_item);
$task_item_subitem = array_unique($task_item_subitem);

?>
<!DOCTYPE html>
<html>
<head>
<BASE href="http://dispatch.catalinacartage.com">
<meta charset="UTF-8">
<title>Tasks</title>
<link rel="shortcut icon" href="/dist/favicon/gears.ico" type="image/x-icon" />
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
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?> 

    <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Tasks | <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
            <?php if ($_SESSION['login'] == 1) { echo "(Admin)"; }?>
            </a></h1>

          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Tasks</li>
          </ol>
        </section>


          <!-- Top Box Centered Full sized window -->
          <?php if ($_SESSION['login'] == 1) { ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box collapsed collapsed-box">
                <div class="box-header">
                  <h3 class="box-title">Search</h3>
                  
                  <!-- 
                  Remove Search Tool
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  -->
                  
                  <!-- Insert Plus Minus tool -->
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <form class="form-horizontal" method="get" action="<?php echo HTTP . $_SERVER['PHP_SELF']; ?>">
                    <div class="form-group">
                      <input type="hidden" name="search" id="search" value="true">
                      <label for="search_employee" class="col-sm-2 control-label">Employee</label>
                      <div class="col-sm-10">
                        <select class="form-control"  value="" name="search_employee" id="search_employee" style="width:150px;">                      
                          <option value="all" <?php if (empty($_GET['driver'])) { echo " selected"; }?>>-All-</option>
                          <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>
                          <option value="<?php echo $all_users_array[$i]['employee_id'];?>" 
                          <?php if ($all_users_array[$i]['employee_id'] == $_GET['search_employee']) { echo " selected "; }?>>
                            <?php echo $all_users_array[$i]['name'];?>
                            </option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="search_status" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-10">
                        <label class="radio-inline">
                          <input type="radio" name="task_status" id="task_all" value="all"
                          <?php if ($_GET['task_status'] == 'all') { echo ' checked '; } ?>
                          > All
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="task_status" id="task_open" value="open"
                          <?php if (!isset($_GET['task_status']) || $_GET['task_status'] == 'open') { echo ' checked '; } ?>
                          > Open
                        </label>
                        <label class="radio-inline">
                          <input type="radio" name="task_status" id="task_closed" value="closed"
                          <?php if ($_GET['task_status'] == 'closed') { echo ' checked '; } ?>
                          > Closed
                        </label>
                      </div>
                    </div>                    
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default">Search</button>
                      </div>
                    </div>
                  </form>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>         
          <?php } ?>
         <!-- Top Box Full sized window Close Out-->   
          
<!-------------------- Add task -->
          <?php if ($_SESSION['login'] == 1) { ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box collapsed collapsed-box">
                <div class="box-header">
                  <h3 class="box-title">Add</h3>
                  
                  <!-- 
                  Remove Search Tool
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  -->
                  
                  <!-- Insert Plus Minus tool -->
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">                  
                <!-- User tasks -->
                <div class="container">
                 <div class="well col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
                                              
                        <form id="frm_update_task" method="post" action="<?php echo HTTP . $_SERVER['PHP_SELF']; ?>">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-1 col-lg-offset-1">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Add</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">                                            
                                            <div class=" col-md-9 col-lg-9">
                                                <strong>Task: <?php echo $tasks_aggregate[$task_i]['id'];?></strong><br>
                                                <table class="table table-user-information">
                                                    <tbody>
                                                    <tr>
                                                        <td>Assigned by:</td>
                                                        <td>
                                                          <select class="form-control"  value="" name="task_assign_by" id="task_assign_by">
                                                           <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>
                                                           <?php if ($all_users_array[$i]['employee_id'] != $_SESSION['employee_id']) { continue; } ?>
                                                           <option value="<?php echo $all_users_array[$i]['employee_id'];?>"
                                                           ><?php echo $all_users_array[$i]['name'];?>
                                                           </option>
                                                           <?php } ?>
                                                           </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Assigned to:</td>
                                                        <td>
                                                          <select class="form-control"  value="" name="task_assign_to" id="task_assign_to">
                                                           <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>
                                                           <option value="<?php echo $all_users_array[$i]['employee_id'];?>"
                                                           ><?php echo $all_users_array[$i]['name'];?>
                                                           </option>
                                                           <?php } ?>
                                                           </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Category:</td>
                                                        <td>
                                                        <select class="form-control"  value="" name="task_category" 
                                                        id="task_category">
                                                          <?php foreach ($task_cat_item as $key => $value)                                                                     
                                                          {
                                                          ?>
                                                            <option value="<?php echo $value;?>">
                                                            <?php echo $value; ?>
                                                            </option>
                                                          <?php
                                                          }
                                                          ?>
                                                        </select>                                                         
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Item:</td>
                                                        <td>
                                                        <select class="form-control"  value="" name="task_item" 
                                                        id="task_item">
                                                          <?php foreach ($task_item_item as $key => $value)                                                                     
                                                          {
                                                          ?>
                                                            <option value="<?php echo $value;?>">
                                                            <?php echo $value; ?>
                                                            </option>
                                                          <?php
                                                          }
                                                          ?>
                                                        </select>        
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Subitem:</td>
                                                        <td>
                                                          <select class="form-control"  value="" name="task_subitem"                                                         
                                                          id="task_subitem">
                                                          <?php foreach ($task_item_subitem as $key => $value)                                                                     
                                                          {
                                                          ?>
                                                            <option value="<?php echo $value;?>">
                                                            <?php echo $value; ?>
                                                            </option>
                                                          <?php
                                                          }
                                                          ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pos | Neg:</td>
                                                        <td>
                                                        <select class="form-control"  value="" name="task_pos_neg" 
                                                        id="task_pos_neg">
                                                          <option value="positive">
                                                          +
                                                          </option>
                                                          <option value="negative">
                                                          -
                                                          </option>
                                                        </select>                                                                                                       
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Points:</td>
                                                        <td>                                                        
                                                          <input type="number" class="form-control"  
                                                          value=""
                                                          name="task_points" id="task_points" required>                                                            
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Due:</td>
                                                        <td>                                                        
                                                          <input type="text" class="input-sm form-control datepicker" name="task_due_date" 
                                                          id="task_due_date" data-date-format="mm/dd/yyyy"/ 
                                                          value="" required="true">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Completed:</td>
                                                        <td>                                                        
                                                          <input type="text" class="input-sm form-control datepicker" name="task_completed_date" 
                                                          id="task_completed_date" data-date-format="mm/dd/yyyy"/ 
                                                          value="">
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <td>Completed by user:</td>
                                                        <td>                                                        
                                                          <select class="form-control"  value="" name="task_completed_user" 
                                                          id="task_completed_user">
                                                          <option value="1">
                                                          Yes
                                                          </option>
                                                          <option value="0" selected="true">
                                                          No
                                                          </option>
                                                        </select>         
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <td>Completed by admin:</td>
                                                        <td>                                                        
                                                          <select class="form-control"  value="" name="task_completed_admin" 
                                                          id="task_completed_admin">
                                                          <option value="1">
                                                          Yes
                                                          </option>
                                                          <option value="0" selected="true">
                                                          No
                                                          </option>
                                                        </select>         
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                      <td>Task visibility:</td>
                                                      <td>
                                                        <select class="form-control"  value="" name="task_visibility" 
                                                        id="task_visibility">
                                                          <option value="1">
                                                          Internal Only
                                                          </option>
                                                          <option value="0">
                                                          Public
                                                          </option>
                                                        </select>                                                         
                                                      </td>
                                                    </tr>                                                                                                                      
                                                    <tr class="warning">
                                                      <td colspan="2">                                                          
                                                        <input type="textarea" name="new_note" id="new_note" required="true">
                                                      </td>
                                                    </tr>                                                    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                          <button class="btn btn-sm btn-active" type="submit" id="btn_add_task" name="btn_add_task"
                                                    data-toggle="tooltip" value=""
                                                    data-original-title="Save changes"><i class="glyphicon glyphicon-floppy-disk"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                        

                      </div>
                    
                  </div>                                      
                <!-- /User tasks -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
          <?php } ?>  
          <!-------------------- /Add task -->


         <!-- Top Box Centered Full sized window -->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Tasks</h3>
                  
                  <!-- 
                  Remove Search Tool
                  <div class="box-tools">
                    <div class="input-group" style="width: 150px;">
                      <input type="text" name="table_search" class="form-control input-sm pull-right" placeholder="Search">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  -->
                  
                  <!-- Insert Plus Minus tool -->
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">                  
                <!-- User tasks -->
                <?php
                // Loop through the array just to get the users.  We'll populate the boxes grouped by user
                $task_users = [];
                for($i=0;$i<count($tasks_aggregate);$i++)
                {
                  array_push($task_users, $tasks_aggregate[$i]['real_name']);
                }                
                $task_users = array_unique($task_users);                
                // Now loop through the users array to create the outer box               
                foreach ($task_users as $task_users_i => $task_users_name) {
                  $task_users_name_replaced = str_replace(" ","_",$task_users_name);
                ?>                
                <div class="container">
                 <div class="well col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
                        <div class="row user-row">
                            <div class="col-xs-3 col-sm-2 col-md-1 col-lg-1">
                                <?php
                                  // Loop through the tasks_aggregate array.  I need to do this to find the username of the user we're on now
                                  $user_photo = HTTP."/dist/img/usernophoto.jpg";                                  
                                  for ($task_aggregate_picture_index=0; $task_aggregate_picture_index < sizeof($tasks_aggregate); $task_aggregate_picture_index++) { 
                                    if ($tasks_aggregate[$task_aggregate_picture_index]['real_name'] == $task_users_name) {
                                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/".$tasks_aggregate[$task_aggregate_picture_index]['username']."_avatar")){
                                        $user_photo = HTTP."/dist/img/userimages/".$tasks_aggregate[$task_aggregate_picture_index]['username']."_avatar";
                                      } else {
                                        $user_photo = HTTP."/dist/img/usernophoto.jpg";
                                      }
                                    }
                                  }
                                ?>
                                <img class="img-circle"
                                     src="<?php echo $user_photo; ?>"
                                     alt="User Pic" width="35" height="35">
                            </div>
                            <div class="col-xs-8 col-sm-9 col-md-10 col-lg-10">
                                <strong><?php echo $task_users_name; ?></strong><br>                                
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 dropdown-user" data-for=".cyruxx_<?php echo $task_users_name_replaced; ?>">
                                <i class="glyphicon glyphicon-chevron-down text-muted"></i>
                            </div>
                        </div>
                        <div class="box-body table-responsive cyruxx_<?php echo $task_users_name_replaced; ?>" style="display: none;">
                        <table class="table table-striped ">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Cat</th>                            
                            <th>Item</th>
                            <?php if ($_SESSION == 1) { echo '<th>Visibility</th>';}?>                     
                          </tr>
                        <?php
                        // Loop through the tasks_aggregate array to pull out all the task info for this user                        
                        reset($tasks_aggregate);                        
                        for($task_i=0;$task_i<count($tasks_aggregate);$task_i++)
                        {                                                    
                          if ($tasks_aggregate[$task_i]['real_name'] != $task_users_name) {                                                      
                            continue;
                          }
                        ?>
                          <tr name="<?php echo $status;?>">
                          <td><!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary btn-small" data-toggle="modal" data-target="#modal_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                            <?php echo $tasks_aggregate[$task_i]['id'];?>
                            </button>
                          </td>
                          <td>
                            <?php echo $tasks_aggregate[$task_i]['category'];?>
                          </td>
                          <td>
                            <?php echo $tasks_aggregate[$task_i]['item'];?>
                          </td>
                          <?php if ($_SESSION == 1) { echo "<td>".$tasks_aggregate[$task_i]['internal_only']."</td>";}?>           
                          </tr>
                           <!-- GENERAL MODAL -->
                           <form id="frm_update_task" method="post" action="<?php echo HTTP . $_SERVER['PHP_SELF']; ?>">
                            <div class="modal fade" id="modal_<?php echo $tasks_aggregate[$task_i]['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Details for <?php echo $tasks_aggregate[$task_i]['id'];?></h4>
                                  </div>
                                  <div class="modal-body">
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Assigned by:
                                      </div>
                                      <div class="col-md-6">
                                        <select class="form-control"  value="" name="task_assign_by" 
                                            id="task_assign_by_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                           <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>                                            
                                             <?php if ($all_users_array[$i]['employee_id'] != $_SESSION['employee_id']) { 
                                                continue; 
                                              } ?>
                                              <option value="<?php echo $all_users_array[$i]['employee_id'];?>"
                                             <?php if ($all_users_array[$i]['name'] == $tasks_aggregate[$task_i]['assigned_by']) { echo ' selected '; }?>
                                             ><?php echo $all_users_array[$i]['name'];?>                                             
                                           <?php } ?>
                                           </option>
                                        </select>
                                      </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Category
                                      </div>
                                      <div class="col-md-6">
                                       <select class="form-control"  value="" name="task_category" 
                                          id="task_category_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                            <?php foreach ($task_cat_item as $key => $value)                                                                     
                                            {
                                            ?>
                                              <option value="<?php echo $value;?>" <?php if ($tasks_aggregate[$task_i]['category'] == $value) { echo " selected "; } ?>>
                                              <?php echo $value; ?>
                                              </option>
                                            <?php
                                            }
                                            ?>
                                        </select>   
                                      </div>
                                    </div>
                                    
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Item
                                      </div>
                                      <div class="col-md-6">
                                       <select class="form-control"  value="" name="task_item" 
                                          id="task_item_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                            <?php foreach ($task_item_item as $key => $value)                                                                     
                                            {
                                            ?>
                                              <option value="<?php echo $value;?>" <?php if ($tasks_aggregate[$task_i]['item'] == $value) { echo " selected "; } ?>>
                                              <?php echo $value; ?>
                                              </option>
                                            <?php
                                            }
                                            ?>
                                          </select> 
                                      </div>
                                    </div>
                                   
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Subitem
                                      </div>
                                      <div class="col-md-6">
                                       <select class="form-control"  value="" name="task_subitem"                                                    
                                            id="task_subitem_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                            <?php foreach ($task_item_subitem as $key => $value)                                                                     
                                            {
                                            ?>
                                              <option value="<?php echo $value;?>" <?php if ($tasks_aggregate[$task_i]['subitem'] == $value) { echo " selected "; } ?>>
                                              <?php echo $value; ?>
                                              </option>
                                            <?php
                                            }
                                            ?>
                                          </select> 
                                      </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Pos | Neg
                                      </div>
                                      <div class="col-md-6">
                                       <select class="form-control"  value="" name="task_pos_neg" 
                                        id="task_pos_neg_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                          <option value="positive" <?php if ($tasks_aggregate[$task_i]['pos_neg'] == 'positive') { echo " selected "; } ?>>
                                          +
                                          </option>
                                          <option value="negative" <?php if ($tasks_aggregate[$task_i]['pos_neg'] == 'negative') { echo " selected "; } ?>>
                                          -
                                          </option>
                                        </select>          
                                      </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Points
                                      </div>
                                      <div class="col-md-6">
                                       <input type="number" class="form-control"  
                                          value="<?php echo $tasks_aggregate[$task_i]['points'];?>" 
                                          name="task_points" id="task_points_<?php echo $tasks_aggregate[$task_i]['id'];?>" required>        
                                      </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Due
                                      </div>
                                      <div class="col-md-6">
                                        <input type="text" class="input-sm form-control datepicker" name="task_due_date" 
                                          id="task_due_date_<?php echo $tasks_aggregate[$task_i]['id'];?>" data-date-format="mm/dd/yyyy"/ 
                                          value="<?php echo $tasks_aggregate[$task_i]['due_date']; ?>">        
                                      </div>
                                    </div>

                                    <?php
                                    // This is only visible to admins
                                    if ($_SESSION['login'] == 1) { ?>
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Completed
                                      </div>
                                      <div class="col-md-6">
                                         <input type="text" class="input-sm form-control datepicker" name="task_completed_date" 
                                          id="task_completed_date_<?php echo $tasks_aggregate[$task_i]['id'];?>" data-date-format="mm/dd/yyyy"/ 
                                          value="<?php echo $tasks_aggregate[$task_i]['completed_date']; ?>">
                                      </div>
                                    </div>
                                    <?php } ?>

                                    <?php
                                    // This is only visible to admins
                                    if ($_SESSION['login'] == 1) { ?>
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Completed by user
                                      </div>
                                      <div class="col-md-6">
                                         <select class="form-control"  value="" name="task_completed_user" 
                                          id="task_completed_user_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                          <option value="1" <?php if ($tasks_aggregate[$task_i]['complete_user'] == 1) { echo " selected "; } ?>>
                                          Yes
                                          </option>
                                          <option value="0" <?php if ($tasks_aggregate[$task_i]['complete_user'] == 0) { echo " selected "; } ?>>
                                          No
                                          </option>
                                        </select>  
                                      </div>
                                    </div>
                                    <?php } ?>

                                    <?php
                                    // This is only visible to admins
                                    if ($_SESSION['login'] == 1) { ?>
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Completed by admin
                                      </div>
                                      <div class="col-md-6">
                                          <select class="form-control"  value="" name="task_completed_admin" 
                                            id="task_completed_admin_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                            <option value="1" <?php if ($tasks_aggregate[$task_i]['complete_admin'] == 0) { echo " selected "; } ?>>
                                            Yes
                                            </option>
                                            <option value="0" <?php if ($tasks_aggregate[$task_i]['complete_admin'] == 0) { echo " selected "; } ?>>
                                            No
                                            </option>
                                          </select>         
                                      </div>
                                    </div>
                                    <?php } ?>

                                    <?php
                                    // This is only visible to admins
                                    if ($_SESSION['login'] == 1) { ?>
                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Task visibility
                                      </div>
                                      <div class="col-md-6">
                                          <select class="form-control"  value="" name="task_visibility" 
                                            id="task_visibility_<?php echo $tasks_aggregate[$task_i]['id'];?>">
                                              <option value="1" <?php if ($tasks_aggregate[$task_i]['internal_only'] == 1) { echo " selected "; } ?>>
                                              Internal Only
                                              </option>
                                              <option value="0" <?php if ($tasks_aggregate[$task_i]['internal_only'] == 0) { echo " selected "; } ?>>
                                              Public
                                              </option>
                                            </select>         
                                      </div>
                                    </div>
                                    <?php } ?>


                                    <div class="row" style="margin-bottom: 5px;">
                                      <div class="col-md-4">
                                        Notes
                                      </div>
                                        <div class="col-md-6 bg-warning">
                                          <?php
                                            for($notes_i=0;$notes_i<count($task_note_array);$notes_i++)
                                            {
                                              if ($task_note_array[$notes_i]['task_id'] != $tasks_aggregate[$task_i]['id'])
                                              {
                                                continue;
                                              }
                                              echo $task_note_array[$notes_i]['created_date'].": ";
                                              echo $task_note_array[$notes_i]['note']."<br>\n";
                                          ?>
                                         <?php
                                            }
                                          ?>                                          
                                        </div>   
                                    </div>

                                  </div>
                                  <div class="modal-footer">
                                    <span class="pull-left">
                                      <?php
                                      // This is only visible to non-admins since admins don't set user-completed
                                      if ($_SESSION['login'] == 2) {
                                        if ($tasks_aggregate[$task_i]['complete_user'] == 0) {
                                          // This user has not marked this complete
                                        ?>
                                        <button class="btn btn-primary" type="submit" id="btn_ack_task" name="btn_ack_task" value="<?php echo $tasks_aggregate[$task_i]['id'];?>">Mark acknowledged</button>
                                        <button class="btn btn-danger" type="submit" id="btn_complete_task" name="btn_complete_task" value="<?php echo $tasks_aggregate[$task_i]['id'];?>">Mark read & complete</button>
                                        <?php } ?>
                                      <?php }else{ ?>
                                      <button type="submit" class="btn btn-primary" id="btn_update_task" name="btn_update_task" value="<?php echo $tasks_aggregate[$task_i]['id'];?>">Update</button>
                                      <?php } ?>                                      
                                      <input type="textarea" name="new_note" id="new_note_<?php echo $tasks_aggregate[$task_i]['id'];?>" placeholder="Add new note here." style='margin-left: 5px;'></span>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </form>
                         <!-- /MODAL -->                                            
                        <?php
                        }
                        ?>
                          </table>                           
                        </div>
                      </div>
                    </div>
                                        
                <?php
                }
                ?>
                </div>
                <!-- /User tasks -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>         
         <!-- Top Box Full sized window Close Out-->      

        
        
         
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
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
<!-- ChartJS -->
<script src="<?php echo HTTP;?>/dist/js/Chart.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var panels = $('.user-infos');
    var panelsButton = $('.dropdown-user');
    var detailsButton = $('.dropdown-deets');
    var a = 'me';
    panels.hide();

    //Click dropdown
    panelsButton.click(function() {
        //get data-for attribute
        var dataFor = $(this).attr('data-for');
        var idFor = $(dataFor);

        //current button
        var currentButton = $(this);
        idFor.slideToggle(400, function() {
            //Completed slidetoggle
            if(idFor.is(':visible'))
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-up text-muted"></i>');
            }
            else
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-down text-muted"></i>');
            }
        })
    });
    that = this;
    detailsButton.click(function() {
        //get data-for attribute
        var dataFor = $(this).attr('data-for');
        var idFor = $(dataFor);

        //current button
        var currentButton = $(this);
        // console.error($(this).html());
        html = $(this).html();        
        idFor.slideToggle(400, function() {
            //Completed slidetoggle
            if(idFor.is(':visible'))
            {                
                html = html.replace('glyphicon-chevron-up','glyphicon-chevron-down');
                currentButton.html(html);               
            }
            else
            {
                html = html.replace('glyphicon-chevron-down','glyphicon-chevron-up');
                currentButton.html(html);                
            }
        })
    });


    $('[data-toggle="tooltip"]').tooltip();
  
  

});
</script>

<script type="text/javascript">
$(window).load(function()
{
  <?php 
  // If we have $_GET['taskid'] set then just show the modal
  if (isset($_GET['taskid'])) {    
    //echo "$('#modal_".$_GET['taskid']."').modal('show');\n";
  }
  ?>
});

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

</body>
</html>
