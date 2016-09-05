<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

// Admins only.
if ($_SESSION['login'] != 1)
{
        header('Location: /pages/dispatch/adminonly.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

# Get the driver names and employee_id
$driver_array = get_drivers($mysqli);

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];
$role = $_SESSION['role'];

// Get the existing rows from the task table
// We limit the query to some GET vars if they're set.  Otherwise
// we just get them all.
if (isset($_GET['driver'])) { 
  if ($_GET['driver'] == 'all') {
    $driver_predicate = '1=1';
  }else{
    $driver_predicate = 'assign_to = "'.$_GET['driver'].'"'; 
  }
}else{ 
    $driver_predicate = '1=2';
}

if (isset($_GET['task_status'])) { 
    if ($_GET['task_status'] == 'open') { 
      $task_status = 0;
    }else{ 
      $task_status = 1;
    }
    $task_status_predicate = 'complete_approved = '.$task_status; 
}else{ 
    $task_status_predicate = '1=1'; 
}
$sql = "select id,
                              date_format(submit_date,'%m/%d/%Y') as submit_date
                              ,assign_to
                              ,assigned_by
                              ,category
                              ,item
                              ,subitem
                              ,pos_neg
                              ,notes
                              ,date_format(due_date,'%m/%d/%Y') as due_date
                              ,date_format(completed_date,'%m/%d/%Y') as completed_date
                              ,points
                              ,complete_user
                              ,complete_approved
         from tasks
         where 1=1 and  $driver_predicate and $task_status_predicate 
         order by submit_date DESC";

$tasks_aggregate = get_sql_results($sql,$mysqli);

if (isset($_POST['type'])) {
    // Looks like an AJAX request.
    // Do some string manipulation depending on the inputs
    if ($_POST['column'] == 'assign_to') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'assign_by') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'category') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'item') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'subitem') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'pos_neg') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'notes') { $_POST['value'] = '"'.$_POST['value'].'"'; }
    if ($_POST['column'] == 'due_date') { $_POST['value'] = 'str_to_date("'.$_POST['value'].'","%m/%d/%Y")'; }
    if ($_POST['column'] == 'completed_date') { $_POST['value'] = 'str_to_date("'.$_POST['value'].'","%m/%d/%Y")'; }
    $sql = "UPDATE tasks SET ".$_POST['column']." = ".$_POST['value']." WHERE id = ".$_POST['id'];

  try {
    // Get the current filename
    if ($mysqli->query($sql) === false)
    {
        throw new Exception("Error deleting image from IFTA_UPLOADS: ".$mysqli->error);
    }
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
  exit;
}

if (isset($_POST['btn_update_task'])) {
    // We'll update the db with our tasks
    if(empty($_POST['task_due_date'])) {
      $task_due_date = 'null';
    }else{
      $task_due_date = "str_to_date('".$_POST['task_due_date']."','%m/%d/%Y')";
    }
    if(empty($_POST['task_completed_date'])) {
      $task_completed_date = 'null';
    }else{
      $task_completed_date = "str_to_date('".$_POST['task_completed_date']."','%m/%d/%Y')";
    }
    if (empty($_POST['task_complete_user'])) { 
        $task_completed_user = 0;
    }else{
        $task_completed_user = 1;
    }
    if (empty($_POST['task_complete_user'])) { 
        $task_complete_approved = 0;
    }else{
        $task_complete_approved = 1;
    }
    $sql = "INSERT INTO tasks (submit_date
                              ,assign_to
                              ,assigned_by
                              ,category
                              ,item
                              ,subitem
                              ,pos_neg
                              ,notes
                              ,due_date
                              ,completed_date
                              ,points
                              ,complete_user
                              ,complete_approved)
                         values (
                               str_to_date('".$_POST['task_submit_date']."','%m/%d/%Y')
                               ,'".$_POST['task_assign_to']."'
                               ,'".$_POST['task_assign_by']."'
                               ,'".$_POST['task_category']."'
                               ,'".$_POST['task_item']."'
                               ,'".$_POST['task_subitem']."'
                               ,'".$_POST['task_pos_neg']."'
                               ,'".$_POST['task_notes']."'
                               ,$task_due_date
                               ,$task_completed_date
                               ,".$_POST['task_points']."
                               ,$task_completed_user
                               ,$task_complete_approved
                               )";
    try {
        if ($mysqli->query($sql) === false)
        {
            throw new Exception("Error inserting into tasks table: ".$mysqli->error);
        }
    } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    header("location: /pages/dispatch/tasks.php?error=$url_error");
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    exit;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Projects 2</title>
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
<link rel="stylesheet" href="/dist/css/animate.css">
<style>
.chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
</style>
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
            Tasks | Projects <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
            </a></h1>

          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Tasks &amp; Projects</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->  

        <!-- Main content -->
        
        <section class="content"><!-- /.row -->

         <div class="row">

<!-- End Box-->    

<!-- Start Header for Tasks & Expirations --> 

      <form enctype="multipart/form-data" role="form" method="get" action="<?php echo HTTP . $_SERVER['PHP_SELF']; ?>">
        <div class="row">
          <div class="col-lg-8 col-xs-8">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <!-- =========================================================== -->
                <center>
                  <h2>Tasks | Projects | Compliance</h2>
                </center>
                <center>
                  <p>You have (PHP) Uncompleated Task(s) <span style="padding: 5px">
                  Admin Select all or jut 1 driver</span></p>
                    <select class="form-control"  value="" name="driver" required style="width:150px;">
                    <option value="null">Select Driver</option>
                    <option value="all">-All-</option>
                   <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
                   <option value=<?php echo $driver_array[$i]['employee_id'];?> <?php if ($driver_array[$i]['employee_id'] == $_GET['driver']) { echo " selected "; }?>>
                   <?php echo $driver_array[$i]['name'];?>
                   </option>
                   <?php } ?>
                        </select>
                  </span>
                    Open
                    <input type="radio" name="task_status" id="opentasksprojects" value="open"
                     <?php if (isset($_GET['task_status'])) { 
                             if ($_GET['task_status'] == 'open') { 
                              echo " checked "; 
                             } 
                           }else{ 
                             echo " checked "; 
                           }?>
                    >
                  Closed
                  <input type="radio" name="task_status" id="closedtasksprojects" value="closed"
                      <?php if ($_GET['task_status'] == 'closed') { 
                              echo " checked "; 
                             } 
                      ?>
                    >
                </center>
                <center>
                  <button type="submit" name="btn_search_task" class="btn btn-primary" value='search'>Search</button>
                </center>
              </div>
              <div class="icon"> <i class="fa fa-cog fa-spin"></i></div>
              <!--<a href="#" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a> </div>-->
            </div>
            <!-- ./col -->
            <!-- ./col -->
            <!-- ./col -->
            <!-- ./col -->
          </div>
          <!-- /.row -->
        </div>
       </form>
        <!--<h2 class="page-header">Page Header</h2>-->

<!-- End Header for Tasks & Expirations --><!-- /.col -->

<!-- End ............ Header Tasks & Expirations .............. End --> 

<!-- Tasks & Expirations Notifications Start -->
            <div class="col-md-4">
<!-- DIRECT CHAT DANGER -->
              <div class="box box-danger direct-chat direct-chat-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Tasks
                    <input name="radio" type="radio" id="Active Tasks" value="Active Tasks" checked>
                    <label for="Active Tasks"></label>Active
                    <input type="radio" name="radio" id="Completed" value="Completed">
                    All</h3>
                  <div class="box-tools pull-right">
                  <!-- Total New Messages Add PHP Here -->
                    <span data-toggle="tooltip" title="XX New Messages Today See Below" class="badge bg-red">2</span>
                    <button class="btn btn-box-tool" data-toggle="tooltip" title="FutureEXP" data-widget="chat-pane-toggle"><i class="fa fa-warning text-red"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                    <!-- Message. Default to the left I mean right LOL -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><img src="../dist/img/server.jpg" width="24" height="21" class="contacts-list-img"> System: Task!</span>
                        <span class="direct-chat-timestamp pull-right">06/23/2006 00:04</span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" name="Gilbert" width="37" height="32" class="direct-chat-img" id="Gilbert"><!-- /.direct-chat-img .....This is where we add the color change for different messages-->
                      <div class="direct-chat-text" style="color:#010000;background-color:danger">Medical Card Expires on 10/10/2016 
                        <table width="208" border="0">
                      <tr>
                        <td>
                        <label for="textfield"></label>


              <form action="#" method="post">
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push">
                  <!-- Note: Not sure if the share button is a good idea.  As this is just internal and not face book. -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Comment</button>
                  <!-- Note: Only User that Posted will have the Remove Post feature / And Admin will have remove for everything -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-check-circle'></i> UpDate</button>
                  <!-- <span class='pull-right'>1 likes - 1 comments</span> --></div>
              </form>                        </td>
                  </tr>
                  </table>
              </div>
                <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->


                 <!-- Message. Default to the left -->
                <div class="direct-chat-msg left">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">Gilbert Huph: Replied!</span>
                    <span class="direct-chat-timestamp pull-right">06/23/2006 08:35</span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                  <!-- /.direct-chat-img -->
              <div class="direct-chat-text">I will Get My Medical Card Taken Care of Soon.</div>
                <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg --> 











                <!-- Message. Default to the right -->
                <div class="direct-chat-msg right">
                  <div class="direct-chat-info clearfix"> <span class="direct-chat-name pull-left"><img src="../dist/img/bernie kropp.jpg" alt="server" width="24" height="21" class="contacts-list-img"> Bernie Kropp: Task!</span> <span class="direct-chat-timestamp pull-right">06/23/2006 00:04</span> </div>
                  <!-- /.direct-chat-info -->
                  <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" name="Gilbert" width="37" height="32" class="direct-chat-img" id="Gilbert2">





                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text" style="color:#010000;background-color:orange">Clean your truck out...
                    <table width="208" border="0">
                      <tr>
                        <td><label for="textfield2"></label>
                          <form action="#" method="post">
                            <!-- .img-push is used to add margin to elements next to floating images -->
                            <div class="img-push">
                              <!-- Note: Not sure if the share button is a good idea.  As this is just internal and not face book. -->
                              <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Comment</button>
                              <!-- Note: Only User that Posted will have the Remove Post feature / And Admin will have remove for everything -->
                              <button class='btn btn-default btn-xs'><i class='fa fa-check-circle'></i> UpDate</button>
                              <!-- <span class='pull-right'>1 likes - 1 comments</span> -->
                            </div>
                          </form></td>
                      </tr>
                    </table>
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->





                 <!-- Message. Default to the left -->
                <div class="direct-chat-msg left">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">Gilbert Huph: Replied!</span>
                    <span class="direct-chat-timestamp pull-right">06/25/2006 18:08</span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                  <!-- /.direct-chat-img -->
              <div class="direct-chat-text">You fucking Retard!!! My truck is clean....</div>
                <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg --> 


                
                





<!-- Message. Default to the right -->
                <div class="direct-chat-msg right">
                  <div class="direct-chat-info clearfix"> <span class="direct-chat-name pull-left"><img src="../dist/img/bernie kropp.jpg" alt="server" width="24" height="21" class="contacts-list-img"> Bernie Kropp has sent you a task.</span> <span class="direct-chat-timestamp pull-right">06/23/2006 00:04</span> </div>
                  <!-- /.direct-chat-info -->
                  <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" name="Gilbert" width="37" height="32" class="direct-chat-img" id="Gilbert2">





                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text">I will get those to you next time I get back to town<span class="direct-chat-info clearfix"><span class="direct-chat-timestamp pull-right">06/24/2006 14:20</span></span>
                    <table width="208" border="0">
                  <tr>
                    <td><label for="textfield4"></label>
                      <select name="task_status2" id="task_status2" style="color:#010000;background-color:#d2d6de">
                        <option>Action Required</option>
                        <option value="1" selected>Acknowledged</option>
                        <option value="1">Completed</option>
                    </select></td>
                        <td width="51">&nbsp;</td>
                  </tr>
                      <tr>
                        <td width="147">&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><input name="textfield2" type="text" id="textfield4" style="color:#010000;background-color:#d2d6de" value="Date Picker Here" size="12" div></td>
                        <td><input type="submit" name="submit2" value="submit" style="color:#010000;background-color:#d2d6de"></td>
                      </tr>
                    </table>
                  </div>
                  <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->


















                 <!-- Message. Default to the left I mean right LOL -->
                <div class="direct-chat-msg right">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">Frank Tank</span>
                    <span class="direct-chat-timestamp pull-right">06/23/2006 00:04</span>
                  </div>
                  <!-- /.direct-chat-info --><span class="direct-chat-msg left"><img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="37" height="32" class="direct-chat-img"></span><!-- /.direct-chat-img -->
              <div class="direct-chat-text">TSA # Missing on 10/10/2016 Email / Text Sent</div>
                <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->                   
                                    
                <!-- Message. Default to the left I mean right LOL -->
                <div class="direct-chat-msg right">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right">EDNA</span>
                    <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
                  </div>
                  <!-- /.direct-chat-info -->
                  <img src="../../dist/img/edna.jpg" alt="message user image" width="27" height="31" class="direct-chat-img">
                  <!-- /.direct-chat-img -->
                  <div class="direct-chat-text">Driver Licence Expiration on 9/18/2016 Email / Text Sent</div>
                <!-- /.direct-chat-text -->
                </div>
              <!-- /.direct-chat-msg -->


                 <!-- Message. Default to the left -->
                <div class="direct-chat-msg left">
                  <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">Hector Axe</span>
                    <span class="direct-chat-timestamp pull-right">06/23/2006 00:04</span>
                  </div>
                  <!-- /.direct-chat-info --><img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="37" height="32" class="direct-chat-img"><!-- /.direct-chat-img -->
              <div class="direct-chat-text">I will Get My Medical Card Take Care of Soon.</div>
                <!-- /.direct-chat-text -->
                </div>
                <!-- /.direct-chat-msg -->                      
              
              
              
              
              </div>
              <!--/.direct-chat-messages-->
              <!-- Contacts are loaded here -->
              <div class="direct-chat-contacts">
                <ul class="contacts-list">
                  <li>
                    <a href="link to users current stats visible public">
                      <img class="contacts-list-img" src="../dist/img/server.jpg"><span class="direct-chat-msg right"> Upcomming Triggered Events</span>
                      <div class="contacts-list-info">
                        <span class="contacts-list-name">
                          Gilbert Huph
                          <small class="contacts-list-date pull-right">10/28/2016</small>
                        </span>
                        <span class="contacts-list-msg">Work Anniversary October 2016</span>
                      </div><!-- /.contacts-list-info -->
                    </a>
                  </li><!-- End Contact Item -->
                </ul><!-- /.contatcts-list -->

<!-- Insert New PHP for upcomming users next row here -->                  

              <ul class="contacts-list">
                  <li>
                      <div class="contacts-list-info">
                        <span class="contacts-list-name">
                          Billy Bob
                          <small class="contacts-list-date pull-right">Future Date PHP: 10/28/2016</small>
                        </span>
                        <span class="contacts-list-msg">Birthday in PHP Month of September</span>
                      </div><!-- /.contacts-list-info -->
                    </a>
                  </li><!-- End Contact Item -->
                </ul><!-- /.contatcts-list -->

<!-- Insert New PHP for upcomming users next row here -->                  

              <ul class="contacts-list">
                  <li>
                      <div class="contacts-list-info">
                        <span class="contacts-list-name">
                          Edna
                          <small class="contacts-list-date pull-right">Future Date PHP: 10/28/2016</small>
                        </span>
                        <span class="contacts-list-msg">Work Anniversary in PHP Month of Septembe</span>
                      </div><!-- /.contacts-list-info -->
                    </a>
                  </li><!-- End Contact Item -->
                </ul><!-- /.contatcts-list -->                  
              
<!-- Insert New PHP for upcomming users next row here -->                  

              <ul class="contacts-list">
                  <li>
                      <div class="contacts-list-info">
                        <span class="contacts-list-name">
                          Edna
                          <small class="contacts-list-date pull-right">Future Date PHP: 10/28/2016</small>
                        </span>
                        <span class="contacts-list-msg">Work Anniversary in PHP Month of Septembe</span>
                      </div><!-- /.contacts-list-info -->
                    </a>
                  </li><!-- End Contact Item -->
                </ul><!-- /.contatcts-list -->                  
              </div>
<!-- /.direct-chat-pane -->
            </div>
<!-- /.box-body -->
            <div class="box-footer">
              <form action="#" method="post">
                <div class="input-group">
                  <input type="text" name="message" placeholder="Post Message to Task/Expiration Board" class="form-control">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-flat">Send</button>
                  </span>
                </div>
              </form>
            </div><!-- /.box-footer-->
          </div><!--/.direct-chat -->
       </div><!-- /.col -->

<!-- Expirations Notifications End -->

<!-- Top Row End -->            
     </div><!-- /.row -->
<!-- Top Row End -->
<!-- /.col -->
<!-- /.col -->
<!-- /.col -->
<!-- =========================================================== -->
<!-- =====================Admin Enter Tasks & Projects========== -->
<!--<h2 class="page-header">Page Header</h2>-->
<!-- =====================Admin Enter Tasks & Projects End======= -->
<!-- =====================Data Entry for Tasks Projects========== -->
<div class="row">
      <div class="col-lg-12 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <center>
              <h2>Tasks</h2>
            </center>
            <center>
              <table name="tbl_tasks" id="tbl_tasks">
                <tr>
                  <td style="padding: 5px"><label for="">Date</label>
                    <div class="input-daterange input-group" id="datepicker_update"></div>
                  </td>
                  <td style="padding: 5px"><label for="">Assign To</label></td>
                  <td style="padding: 5px"><label for="">Assign by</label></td>
                  <td style="padding: 5px"><label for="">Category</label></td>
                  <td style="padding: 5px"><label for="">Item</label></td>
                  <td style="padding: 5px"><label for="">SubItem</label></td>
                  <td style="padding: 5px"><label for="">+ | -</label></td>
                  <td style="padding: 5px"><label for="">Notes</label></td>
                  <td style="padding: 5px"><label for="">Due date</label></td>
                  <td style="padding: 5px"><label for="">Completed date</label></td>
                  <td style="padding: 5px"><label for="">Task points</label></td>
                  <td style="padding: 5px"><label for="">User resolved</label></td>
                  <td style="padding: 5px"><label for="">Admin resolved</label></td>
                </tr>
               <form enctype="multipart/form-data" role="form" method="post" action="<?php echo HTTP . $_SERVER['PHP_SELF']; ?>">
                <tr>
                  <td style="padding: 5px" name="task_date" id="task_date"><span class="input-daterange input-group">
                    <input type="text" class="input-sm form-control datepicker" name="task_submit_date" data-date-format="mm/dd/yyyy"/ required>
                    </span>
                  </td>
                  <td style="padding: 5px">
                   <select class="form-control" name="task_assign_to" id="task_assign_to">
                   <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
                   <option value=<?php echo $driver_array[$i]['employee_id'];?>><?php echo $driver_array[$i]['name'];?>
                   </option>
                   <?php } ?>
                   </select>
                  </td>
                  <td style="padding: 5px">
                   <select class="form-control"  value="" name="task_assign_by" id="task_assign_by">
                   <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
                   <?php if ($driver_array[$i]['employee_id'] != $_SESSION['employee_id']) { continue; } ?>
                   <option value=<?php echo $driver_array[$i]['employee_id'];?>><?php echo $driver_array[$i]['name'];?>
                   </option>
                   <?php } ?>
                   </select>
                  </td>
                  <td style="padding: 5px"><select class="form-control" name="task_category" id="task_category">
                    <option value="task" selected>Task</option>
                    <option value="compliance">Compliance</option>
                  </select></td>
                  <td style="padding: 5px"><select class="form-control" required name="task_item" id="task_item">
                    <option value="boards">Boards</option>
                    <option value="vir">VIR</option>
                    <option value="productivity">Productivity</option>
                    <option value="csa">CSA</option>
                    <option value="other">Other</option>
                   </select></td>
                  <td style="padding: 5px"><select class="form-control" required name="task_subitem" id="task_subitem">
                    <option value="arrived_shipper">Arrived to Shipper</option>
                    <option value="picked_up">Picked Up</option>
                    <option value="arrived_consignee">Arrived to Consignee</option>
                    <option value="delivered">Delivered</option>
                    <option value="accessorials">Accessorials</option>
                   </select></td>
                  <td style="padding: 5px;"><select class="form-control"  value="" name="task_pos_neg" id="task_pos_neg">
                    <option value="positive">+</option>
                    <option value="negative">-</option>
                  </select></td>
                  <td style="padding: 5px;"><input type="text" class="form-control"  value="" name="task_notes" id="task_notes" required></td>
                  <td style="padding: 5px">
                    <input type="text" class="input-sm form-control datepicker" name="task_due_date" id="task_due_date" data-date-format="mm/dd/yyyy"/ required>
                  </td>
                  <td style="padding: 5px">
                    <input type="text" class="input-sm form-control datepicker" name="task_completed_date" id="task_completed_date" data-date-format="mm/dd/yyyy"/>
                  </td>
                  <td style="padding: 5px;"><input type="number" class="form-control"  value="" name="task_points" id="task_points" required></td>
                  <td style="padding: 5px;"><input type="checkbox"  value="" name="task_complete_user" id="task_complete_user" ></td>
                  <td style="padding: 5px;"><input type="checkbox"   value="" name="task_complete_approved" id="task_complete_approved"></td>
                </tr>
                <tr>
                  <td style="padding: 5px"><input type="submit" name="btn_update_task" class="btn btn-primary" value="Submit"> </td>
                </tr>
               </form> 
               <?php // We'll now assemble the existing tasks ?>
               <?php for($j=0;$j<count($tasks_aggregate);$j++) { ?>
                <tr name="tr_tasks" id="tr_tasks_<?php echo $tasks_aggregate[$j]['id'];?>">
                  <td style="padding: 5px"><span class="input-daterange input-group">
                    <input type="text" class="input-sm form-control datepicker" name="task_date" id="task_date_<?php echo $tasks_aggregate[$j]['id'];?>" data-date-format="mm/dd/yyyy" required value="<?php echo $tasks_aggregate[$j]['submit_date'];?>" disabled>
                    <input type="hidden" value="<?php echo $tasks_aggregate[$j]['id'];?>" name="hdn_id" id="hdn_id_<?php echo $tasks_aggregate[$j]['id'];?>">
                    </span>
                  </td>
                  <td style="padding: 5px">
                   <select class="form-control" name="task_assign_to" id="task_assign_to_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);">
                   <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
                   <option value="<?php echo $driver_array[$i]['employee_id'];?>" <?php if ($driver_array[$i]['employee_id'] == $tasks_aggregate[$j]['assign_to']) { echo " selected "; }?> ><?php echo $driver_array[$i]['name'];?>
                   </option>
                   <?php } ?>
                   </select>
                  </td>
                  <td style="padding: 5px">
                   <select class="form-control"  value="" name="task_assign_by" id="task_assign_by_<?php echo $tasks_aggregate[$j]['id'];?>">
                   <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
                   <?php if ($driver_array[$i]['employee_id'] != $tasks_aggregate[$j]['assigned_by']) { continue; } ?>
                   <option value="<?php echo $driver_array[$i]['employee_id'];?>"><?php echo $driver_array[$i]['name'];?>
                   </option>
                   <?php } ?>
                   </select>
                  </td>
                  <td style="padding: 5px"><select class="form-control" name="task_category" id="task_category_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);">
                    <option value="task" <?php if ($tasks_aggregate[$j]['catagory'] == 'task') { echo " selected "; } ?>>Task</option>
                    <option value="compliance" <?php if ($tasks_aggregate[$j]['catagory'] == 'compliance') { echo " selected "; } ?>>Compliance</option>
                  </select></td>
                  <td style="padding: 5px"><select class="form-control" required name="task_item" id="task_item_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);">
                    <option value="boards" <?php if ($tasks_aggregate[$j]['item'] == 'boards') { echo " selected "; } ?>>Boards</option>
                    <option value="vir" <?php if ($tasks_aggregate[$j]['item'] == 'vir') { echo " selected "; } ?>>VIR</option>
                    <option value="productivity" <?php if ($tasks_aggregate[$j]['item'] == 'productivity') { echo " selected "; } ?>>Productivity</option>
                    <option value="csa" <?php if ($tasks_aggregate[$j]['item'] == 'csa') { echo " selected "; } ?>>CSA</option>
                    <option value="other" <?php if ($tasks_aggregate[$j]['item'] == 'other') { echo " selected "; } ?>>Other</option>
                   </select></td>
                  <td style="padding: 5px"><select class="form-control" required name="task_subitem" id="task_subitem_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);">
                    <option value="arrived_shipper" <?php if ($tasks_aggregate[$j]['subitem'] == 'arrived_shipper') { echo " selected "; } ?>>Arrived to Shipper</option>
                    <option value="picked_up" <?php if ($tasks_aggregate[$j]['subitem'] == 'picked_up') { echo " selected "; } ?>>Picked Up</option>
                    <option value="arrived_consignee" <?php if ($tasks_aggregate[$j]['subitem'] == 'arrived_consignee') { echo " selected "; } ?>>Arrived to Consignee</option>
                    <option value="delivered" <?php if ($tasks_aggregate[$j]['subitem'] == 'delivered') { echo " selected "; } ?>>Delivered</option>
                    <option value="accessorials" <?php if ($tasks_aggregate[$j]['subitem'] == 'accessorials') { echo " selected "; } ?>>Accessorials</option>
                   </select></td>
                  <td style="padding: 5px;"><select class="form-control"  value="" name="task_pos_neg" id="task_pos_neg_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);">
                    <option value="positive" <?php if ($tasks_aggregate[$j]['pos_neg'] == 'positive') { echo " selected "; } ?>>+</option>
                    <option value="negative" <?php if ($tasks_aggregate[$j]['pos_neg'] == 'negative') { echo " selected "; } ?>>-</option>
                  </select></td>
                  <td style="padding: 5px;"><input type="text" class="form-control"  value="<?php echo $tasks_aggregate[$j]['notes']; ?>" name="task_notes" id="task_notes_<?php echo $tasks_aggregate[$j]['id'];?>" required onkeypress="update_tasks(this);"></td>
                  <td style="padding: 5px">
                    <input type="text" class="input-sm form-control datepicker" name="task_due_date" id="task_due_date_<?php echo $tasks_aggregate[$j]['id'];?>" data-date-format="mm/dd/yyyy"/ value="<?php echo $tasks_aggregate[$j]['due_date']; ?>" onchange="update_tasks(this);">
                  </td>
                  <td style="padding: 5px">
                    <input type="text" class="input-sm form-control datepicker" name="task_completed_date" id="task_completed_date_<?php echo $tasks_aggregate[$j]['id'];?>" data-date-format="mm/dd/yyyy"/ value="<?php echo $tasks_aggregate[$j]['completed_date']; ?>" onchange="update_tasks(this);">
                  </td>
                  <td style="padding: 5px;"><input type="number" class="form-control"  value="<?php echo $tasks_aggregate[$j]['points'];?>" name="task_points" id="task_points_<?php echo $tasks_aggregate[$j]['id'];?>" required onchange="update_tasks(this);"></td>
                  <td style="padding: 5px;"><input type="checkbox"  <?php if ($tasks_aggregate[$j]['complete_user'] == "1") { echo ' checked '; }?> name="task_complete_user" id="task_complete_user_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);"></td>
                  <td style="padding: 5px;"><input type="checkbox"  <?php if ($tasks_aggregate[$j]['complete_approved'] == "1") { echo ' checked '; }?> name="task_complete_approved" id="task_complete_approved_<?php echo $tasks_aggregate[$j]['id'];?>" onchange="update_tasks(this);"></td>
                </tr>
                <?php } ?>
              </table>
              <p>&nbsp;</p>
            </center>
          </div>
          <div class="icon"> <i class="fa fa-cog fa-spin"></i></div>
          <!--<a href="#" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a> </div>-->
        </div>
        <!-- ./col -->
            <!-- ./col -->
            <!-- ./col -->
            <!-- ./col -->
          </div>
          <!-- /.row -->
        </div>
        <!--<h2 class="page-header">Page Header</h2>-->

<!-- ==============Data Entry for Tasks Projects End======= -->







<!-- =====================Social Widgents==================== -->
<h2 class="page-header">
  <!-- /.row -->
        </h2>
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
















!-- /.content-wrapper -->
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
<script>
$( document ).ready(function() {
   // Default values for task_points
   $("#task_points").val(10);   
   $("#task_pos_neg").change(function(){
     if($(this).val() == 'negative') { 
       $("#task_points").val("-10");   
     }else{
       $("#task_points").val("10");   
     }
   });
});
   
var delayTimer;
function update_tasks(i) {
      clearTimeout(delayTimer);
      delayTimer = setTimeout(function() {

     var my_val = $(i).val();
     var my_col = $(i).attr('name');
     var my_id = $(i).attr('id');
     var my_hdn_val = $(i).parent().parent().find('input[type=hidden]').val();

     // Do some munging if it's a checkbox (because val() doesn't reflect if it's checked)
     if (my_col == 'task_complete_user' || my_col == 'task_complete_approved'){ 
       //console.log($("#"+my_col.prop('checked')));
       if($("#"+my_id).prop("checked") == true) {
         my_val = "1";
       }else{
         my_val = "0";
       }
     }
     my_col = my_col.replace('task_','');

     $.post( "<?php echo $_SERVER['PHP_SELF'];?>", { id: my_hdn_val, column: my_col, value: my_val, type: 'ajax' })
     .success(function(data, textStatus, request) { console.log(data); })
     .error(function(data, textStatus, request) { console.log(data); });
    }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
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
<!-- Demo -->
</body>
</html>
