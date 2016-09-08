<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];
$role = $_SESSION['role'];

?>

<?php
                     $sql = "SELECT
                      total_today.counts   AS total_today_count,
                      pu_today.counts      AS pu_today_count,
                      del_today.counts     AS del_today_count,
                      total_alltime.counts AS total_alltime_count,
                      pu_alltime.counts    AS pu_alltime_count,
                      del_alltime.counts   AS del_alltime_count,
                      archived.counts      AS archived_count,
                      virs_daily.count     AS virs_daily_count,
                      virs_weekly.count    AS virs_weekly_count
                    FROM
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          AND str_to_date(hawbDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                          =\"F\"
                          AND archived                         =\"F\"
                          )
                        OR
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username =\"$username\"
                            )
                          AND str_to_date(dueDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                         =\"F\"
                          AND archived                        =\"F\"
                          )
                      )
                      total_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          puAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND str_to_date(hawbDate,'%c/%e/%Y') = DATE(now())
                        AND deleted                          =\"F\"
                        AND archived                         =\"F\"
                        AND deleted                          =\"F\"
                        AND archived                         =\"F\"
                      )
                      pu_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          delAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND str_to_date(dueDate,'%c/%e/%Y') = DATE(now())
                        AND deleted                         =\"F\"
                        AND archived                        =\"F\"
                        AND deleted                         =\"F\"
                        AND archived                        =\"F\"
                      )
                      del_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          OR puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      total_alltime,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          puAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      pu_alltime,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          delAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      del_alltime,
                      (
                      SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          OR puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          )
                        AND deleted =\"F\"
                        AND archived=\"T\"
                      )
                      archived,
                     (
                      SELECT
                          COUNT(*) AS count
                        FROM
                          virs
                        WHERE
                        driver_name=\"$username\"
                        AND insp_date = date(now())
                      ) virs_daily,
                      (
                      SELECT
                          COUNT(*) AS count
                        FROM
                          virs
                        WHERE
                        driver_name=\"$username\"
                        AND insp_date BETWEEN date(now()) AND date(now()) - INTERVAL 8 DAY
                      ) virs_weekly";

                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
                        $total_today_count   = $row['total_today_count'];
                        $pu_today_count      = $row['pu_today_count'];
                        $del_today_count     = $row['del_today_count'];
                        $total_alltime_count = $row['total_alltime_count'];
                        $pu_alltime_count    = $row['pu_alltime_count'];
                        $del_alltime_count   = $row['del_alltime_count'];
                        $archived_count      = $row['archived_count'];
                        $virs_daily_count    = $row['virs_daily_count'];
                        $virs_weekly_count   = $row['virs_weekly_count'];
                      }
                      mysql_free_result($sql);

if (isset($_POST['broadcast_message']))
{
  $audience = $_POST['audience'];
  if ($audience == 'PHX') { $predicate = "AND office='PHX'"; }
  if ($audience == 'TUS') { $predicate = "AND office='TUS'"; }
  if ($audience == 'PHL') { $predicate = "AND office='PHL'"; }
  if ($audience == 'DEN') { $predicate = "AND office='DEN'"; }
  if ($audience == 'LAX') { $predicate = "AND office='LAX'"; }
  if ($audience == 'MIA') { $predicate = "AND office='MIA'"; }
  if ($audience == 'ORD') { $predicate = "AND office='ORD'"; }
  if ($audience == 'All') { $predicate = "AND office like '%'"; }

  $message = $_POST['message'];
  $sql = "SELECT 1 ";
  if (isset($_POST['sendEmail'])) { $sql .= ",email"; } 
  if (isset($_POST['sendText'])) { $sql .= ",vtext"; } 

  $sql .= " FROM users WHERE 1=1 $predicate AND status='Active'";

  $broadcast_users = get_sql_results($sql,$mysqli);
  for($broadcast_i=0;$broadcast_i<count($broadcast_users);$broadcast_i++)
  {
    if (isset($_POST['sendEmail']))
    {
      sendEmail($broadcast_users[$broadcast_i]['email'],'Broadcast message from  '.$_SESSION['username'],$message); 
    } 
    if (isset($_POST['sendText']))
    {
      sendEmail($broadcast_users[$broadcast_i]['vtext'],'Broadcast '.$_SESSION['username'],$message); 
    }
  }
}

  # Let's get an array of user info
  $expiration_array = [];
  try {
    // If I'm an admin we'll get all users, else just get the current user
    if ($_SESSION['login'] == 1) { $predicate = 'where 1 = 1'; }else{ $predicate = 'where username="'.$_SESSION['username'].'"'; }
    $statement = "
    select max(username) as username 
	,max(employee_id) as employee_id 
    ,max(DRIVER_LICENSE_EXP) as driver_license_exp 
    ,max(MED_CARD_EXP) as med_card_exp ,max(TSA_STA) as tsa_sta from
    (
	select USERNAME,EMPLOYEE_ID,DRIVER_LICENSE_EXP,null as MED_CARD_EXP, null as TSA_STA from users 
      where STATUS='Active' and DRIVER_LICENSE_EXP between current_date and current_date + interval 30 day
    
	union
    select USERNAME,EMPLOYEE_ID,null as DRIVER_LICENSE_EXP,MED_CARD_EXP, null as TSA_STA from users 
      where STATUS='Active' and MED_CARD_EXP between current_date and current_date + interval 30 day
    
	union
    select USERNAME,EMPLOYEE_ID,null as DRIVER_LICENSE_EXP,null as MED_CARD_EXP, coalesce(TSA_STA,'NF') from users where STATUS='Active' and (TSA_STA is null or TSA_STA = '')
    ) a $predicate group by a.username order by a.username";

    if ($result = $mysqli->query($statement)) {
      $counter = 0;
      while($obj = $result->fetch_object()){
        $expiration_array[$counter]['username'] = $obj->username;
        $expiration_array[$counter]['employee_id'] = $obj->employee_id;
        $expiration_array[$counter]['driver_license_exp'] = $obj->driver_license_exp;
        $expiration_array[$counter]['med_card_exp'] = $obj->med_card_exp;
		$expiration_array[$counter]['dob'] = $obj->dob;
        $expiration_array[$counter]['tsa_sta'] = $obj->tsa_sta;
        $counter++;
      }

    }else{
      throw new Exception("Unable to query users: ".$mysqli->error);
    }
  } catch (Exception $e) {
    // An exception has been thrown
    echo "<script>console.log(".$e->getMessage().");</script>";
  }

  // Get the tasks assigned to the user.
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
         where assign_to = '".$_SESSION['employee_id']."'
         and complete_user = 0
         and complete_approved = 0
         order by submit_date";

  $tasks_aggregate = get_sql_results($sql,$mysqli);

  // Let's update the task (done via ajax)
  if (isset($_POST['type'])) {
    $sql = "update tasks set complete_user = 1 where id = ".$_POST['id'];
    try {
        if ($mysqli->query($sql) === false)
        {
            throw new Exception("Error updating tasks table: ".$mysqli->error);
        }
    } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = $e->getMessage();
    $mysqli->rollback();
    header('HTTP/1.1 500 Internal Server Booboo');
    header('Content-Type: application/json; charset=UTF-8');
    $mysqli->close();
    die(json_encode(array("failure" => true,"error" => "Some random error happened")));
  }
  exit;
 }

  $mysqli->close();
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Index Testing</title>
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
            Index Testing / <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
            <?php if ($_SESSION['login'] == 1) { echo "(Admin)"; }?>
            </a></h1>

          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Index Testing</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->  


 
 
        <!-- Main content -->
        
<!-- =========================================================== -->

       <section class="content"><!-- /.row -->


<!-- =========================================================== -->

<!--          <h2 class="page-header">Replace Broad Cast MSG with Email</h2> -->

<!-- ======================Start Direct Chat 2 Section 1 Box 1=============== -->

          <!-- Direct Chat -->
         <div class="row">
          
<!-- ======================Direct Chat 2 Section 1 Box 1=============== -->
          
<!-- Box with New Email Message -->


           <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <div class="col-md-4">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Broadcast Message</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <div class="form-group">
                     <!-- Note: Added the below stations -->
					 <select class="form-control" style="width: 100%; margin-top:1px" name="audience">
					   <option>All</option>
					   <option>PHX</option>
					   <option>TUS</option>
					   <option>PHL</option>
					   <option>DEN</option>
					   <option>LAX</option>
					   <option>MIA</option>
					   <option>ORD</option>
			        </select>
                    <input class="form-control" placeholder="CC Additionals:" disabled>
                    <div class="input-group-inline">

                    <div class="checkbox">
                      <label><input type="checkbox" name="sendEmail" value="on" checked="">Email</label>
                      <label style="margin-left:10px;"><input name="sendText" type="checkbox" value="on" checked>Text</label>
                      <label style="margin-left:10px;"><input name="Post" type="checkbox" value="on" checked disabled>Post</label>
                    </div>                        
                       
                       
					</div>
                  </div>
                  <div class="form-group"> 
                  <input class="form-control" placeholder="Subject:" disabled>
                 </div>
                  <div class="form-group">
                    <textarea name="message" id="message"  class="form-control" placeholder="Type your message here...."  style="height: 100px" required></textarea>
                    <input type="text" class="form-control" name="message" id="message" placeholder="1st 166 Characters only show if text box checked..." value="" disabled>
                 </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-primary" name="broadcast_message" id="broadcast_message" ><i class="fa fa-share-alt"></i> Share</button>
                  </div>
                </div><!-- /.box-footer -->
              </div><!-- /. box -->
            </div><!-- /.col -->
           </form>
            
            
            
<!-- End Box for sending Email -->    

<!-- Event Notifications Start -->            
            
   
            <div class="col-md-4">
              <!-- DIRECT CHAT PRIMARY -->
              <div class="box box-primary direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Event Notifications</h3>
                  <div class="box-tools pull-right">
                  <!-- Total New Messages Add PHP Here Just for new messages Events today -->
                    <a href="../dispatch/tasks.php"><span data-toggle="tooltip" title="Add Tasks" class="badge bg-light-blue">Add Task</span></a>
<button class="btn btn-box-tool" data-toggle="tooltip" title="FutureEvents" data-widget="chat-pane-toggle"><i class="fa fa-comments"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
<!--                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                  <!-- Begin Task notifications -->
                    <?php for($i=0;$i<count($tasks_aggregate);$i++) { ?>
                           <div class="direct-chat-msg right" id="top_level_<?php echo $tasks_aggregate[$i]['id'];?>">
                           <div class="direct-chat-info clearfix">
                           <span class="direct-chat-name pull-left">Task Master</span>
                           <span class="direct-chat-timestamp pull-right"><?php echo $tasks_aggregate[$i]['submit_date'];?></span>
                           </div>
                           <!-- /.direct-chat-info -->
                           <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                           <!-- /.direct-chat-img -->
                         <div class="direct-chat-text"><?php echo $tasks_aggregate[$i]['notes']?><br><span style="font-size:.8em;">Due: <?php echo $tasks_aggregate[$i]['due_date'];?></span></div>
                         <table border="0">
                         <tr>
                          <td>
                            <div class="img-push">
                             <button class='btn btn-default btn-xs' name="btn_updatetask" id="btn_updatetask_<?php echo $tasks_aggregate[$i]['id'];?>" 
                                onclick="update_task(this);" value="<?php echo $tasks_aggregate[$i]['id'];?>"><i class='fa fa-check-circle'></i>Done!</button>
                            </div>
                         </td>
                        </tr>
                        </table>
                         <!-- /.direct-chat-text -->
                         </div>
                         <!-- /.direct-chat-msg -->
                    <?php } ?>
                  <!-- End Task notifications -->
                  </div><!--/.direct-chat-messages-->
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
                  
                  
                  
                  </div><!-- /.direct-chat-pane -->
                </div><!-- /.box-body -->

              </div><!--/.direct-chat -->
            </div><!-- /.col -->

<!-- Event Notifications End --> 

<!-- Expirations Notifications Start -->
            <div class="col-md-4">
<!-- DIRECT CHAT DANGER -->
              <div class="box box-danger direct-chat direct-chat-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Expiration Notifications</h3>
                  <div class="box-tools pull-right">
                  <!-- Total New Messages Add PHP Here -->
                    <span data-toggle="tooltip" title="Update Expirations" class="badge bg-red">Update</span>
                    <button class="btn btn-box-tool" data-toggle="tooltip" title="FutureEXP" data-widget="chat-pane-toggle"><i class="fa fa-warning text-red"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages">
                    <?php for($i=0;$i<count($expiration_array);$i++) { ?>
                           <?php if ($expiration_array[$i]['driver_license_exp'] != '') {?>
                           <div class="direct-chat-msg right">
                           <div class="direct-chat-info clearfix">
                           <span class="direct-chat-name pull-left">Admin</span>
                           <span class="direct-chat-timestamp pull-right"><?php echo time();?></span>
                           </div>
                           <!-- /.direct-chat-info -->
                           <img src="../../dist/img/server.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                           <!-- /.direct-chat-img -->
                         <div class="direct-chat-text">Drivers license expires on <?php echo $expiration_array[$i]['driver_license_exp']?>  for <?php echo $expiration_array[$i]['username']?> </div>
                         <!-- /.direct-chat-text -->
                         </div>
                         <!-- /.direct-chat-msg -->
                         <?php } ?>
                           <?php if ($expiration_array[$i]['med_card_exp'] != '') {?>
                           <div class="direct-chat-msg right">
                           <div class="direct-chat-info clearfix">
                           <span class="direct-chat-name pull-left">Admin</span>
                           <span class="direct-chat-timestamp pull-right"><?php echo time();?></span>
                           </div>
                           <!-- /.direct-chat-info -->
                           <img src="../../dist/img/server.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                           <!-- /.direct-chat-img -->
                         <div class="direct-chat-text">Medical card expires <?php echo $expiration_array[$i]['med_card_exp']?> for <?php echo $expiration_array[$i]['username']?></div>
                         <!-- /.direct-chat-text -->
                         </div>








                         <?php } ?>
                           <?php if ($expiration_array[$i]['dob'] != '') {?>
                           <div class="direct-chat-msg right">
                           <div class="direct-chat-info clearfix">
                           <span class="direct-chat-name pull-left">Admin</span>
                           <span class="direct-chat-timestamp pull-right"><?php echo time();?></span>
                           </div>
                           <!-- /.direct-chat-info -->
                           <img src="../../dist/img/server.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                           <!-- /.direct-chat-img -->
                         <div class="direct-chat-text">Happy B Day <?php echo $expiration_array[$i]['dob']?> for <?php echo $expiration_array[$i]['username']?></div>
                         <!-- /.direct-chat-text -->
                         </div>






                         
                         <!-- /.direct-chat-msg -->
                         <?php } ?>
                           <?php if ($expiration_array[$i]['tsa_sta'] == 'NF') {?>
                           <div class="direct-chat-msg right">
                           <div class="direct-chat-info clearfix">
                           <span class="direct-chat-name pull-left">Administrator</span>
                           <span class="direct-chat-timestamp pull-right"><?php echo time();?></span>
                           </div>
                           <!-- /.direct-chat-info -->
                           <img src="../../dist/img/server.jpg" alt="message user image" width="37" height="32" class="direct-chat-img">
                           <!-- /.direct-chat-img -->
                         <div class="direct-chat-text">No TSA number entered for <?php echo $expiration_array[$i]['username']?></div>
                         <!-- /.direct-chat-text -->
                         </div>
                         <!-- /.direct-chat-msg -->
                         <?php } ?>
                    <?php } ?>
                    
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
              </div><!--/.direct-chat -->
            </div><!-- /.col -->

<!-- Expirations Notifications End -->

<!-- Top Row End -->            
         </div><!-- /.row -->
<!-- Top Row End --> 


  <!-- ======================Broadcast Posts Section Starts here=============== -->

         <div class="row">
           <div class="col-md-4">
              <!-- Box Comment -->
              <div class="box box-widget">
                <div class='box-header with-border'>
                  <div class='user-block'>
                    <img src="../dist/img/server.jpg" alt="message user image" width="35" height="35" class="img-circle">
                    <span class='username'><a href="#">System MSG</a></span>
                    <span class='description'> @ 4:30 PM Today</span>
                  </div><!-- /.user-block -->
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-toggle='tooltip' title='Mark as read'><i class='fa fa-circle-o'></i></button>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    <button class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class='box-body'>
                Medical Card Expiration on 10/10/2016 Email / Text Sent to User
                (PHP user Name)</div>
                <div class='box-footer box-comments'><!-- /.box-comment -->
                  <div class='box-comment'>

                  </div><!-- /.box-comment -->
                </div><!-- /.box-footer -->
                <div class="box-footer">
                  <form action="#" method="post">
                          <div class="pull-left image"> <img src="<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) { echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg"; }?>" alt="User Image" width="35" height="35" class="img-circle" /> </div>
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                  <!-- Note: Not sure if the share button is a good idea.  As this is just internal and not face book. -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Share</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-thumbs-o-up'></i> Like</button>
                  <!-- Note: Only User that Posted will have the Remove Post feature / And Admin will have remove for everything -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-check-circle'></i> UpDate</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-trash'></i> Remove</button>
                 <!-- <span class='pull-right'>1 likes - 1 comments</span> -->
                      <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div><!-- /.box-footer -->
             </div><!-- /.box -->
         
           <!-- end post insert --><!-- /.col -->
         </div><!-- /.row -->



<!-- =====================Social Widgents==================== -->
<!--<h2 class="page-header">Social Widgets</h2>-->


         <div class="col-md-4">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue">
                  <div class="widget-user-image">
                    <img src="../dist/img/h2tyd 3.jpg" alt="User Avatar" width="50" height="50" class="img-circle">
                    <span class='username'><a href="../pages/dispatch/projects2.php" class="fa-2x">Hector Axe</a></span>
                  <a href="indextesting.php"><span class='description pull-right'> Goals & Projects</span></a> </div>
                  <!-- /.widget-user-image -->
                  <!-- Removing Name Below Added Above 
                  <h3 class="widget-user-username">Nadia Carmichael</h3>
                  <h5 class="widget-user-desc">Lead Developer</h5>
                  -->
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Assigned Projects <span class="pull-right badge bg-blue">3</span></a></li>
                    <li><a href="#">Tasks (From Expirations)<span class="pull-right badge bg-red">2</span></a></li>
                    <li><a href="#">Completed <span class="pull-right badge bg-green">12</span></a></li>
                  </ul>
                </div>
                </div><!-- /.box -->
              </div><!-- /.widget-user -->
            </div><!-- /.col --><!-- /.col --><!-- /.col -->




<!-- =========================================================== -->





<!-- =====================End Above Post Start New Post here==================== -->


  

         <div class="row">
           <div class="col-md-4">
              <!-- Box Comment -->
              <div class="box box-widget">
                <div class='box-header with-border'>
                  <div class='user-block'>
                    <img src="../../dist/img/edna.jpg" alt="message user image" width="35" height="35" class="img-circle">
                    <span class='username'><a href="#">Edna</a></span>
                    <span class='description'> @ 4:30 PM Today</span>
                  </div><!-- /.user-block -->
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-toggle='tooltip' title='Mark as read'><i class='fa fa-circle-o'></i></button>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    <button class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class='box-body'>
                This video is kick ass.  Check it out....
                  <iframe width="480" height="300" src="https://www.youtube.com/embed/OmnDEUD9NyI" frameborder="0" allowfullscreen alt="..." class="margin"></iframe>
                </div>
                <div class='box-footer box-comments'>
                  <div class='box-comment'>
                    <!-- User image -->
                    <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="35" height="35" class="img-circle img-sm">
                    <span class="comment-text"><span class="username"> Gilbert Huph</span>
                    <span class='description'> @ 7:30 PM Today</span></span>
                    <div class='comment-text'>
                  </span><!-- /.username -->
                  Hello, mon! A 14-year-old from the Solomon Islands covered Adele's "Hello" in a whole new style — reggae.  Jamaican reggae artist Conkarah and teenager Rosie Delmah posted the cover to Facebook last month and it has already received more than 5 million views...
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                  <div class='box-comment'>
                    <!-- User image -->
                    <img src="../../dist/img/jack.jpg" alt="message user image" width="35" height="35" class="img-circle img-sm">
                    <span class="comment-text"><span class="username"> Jack Jack</span></span>
                    <div class='comment-text'>
                  <span class="username"><span class='text-muted pull-right'>8:08 PM Today</span>
                  </span><!-- /.username -->
LOVE this Adele cover!! These kids are crazy talented!
                    </div>
                    <!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                </div><!-- /.box-footer -->
                <div class="box-footer">
                  <form action="#" method="post">
                          <div class="pull-left image"> <img src="<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) { echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg"; }?>" alt="User Image" width="35" height="35" class="img-circle" /> </div>
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                  <!-- Note: Not sure if the share button is a good idea.  As this is just internal and not face book. -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Share</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-thumbs-o-up'></i> Like</button>
                  <!-- Note: Only User that Posted will have the Remove Post feature / And Admin will have remove for everything -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-check-circle'></i> UpDate</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-trash'></i> Remove</button>
                  <span class='pull-right text-muted'>13 likes - 2 comments</span>
                      <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div><!-- /.box-footer -->
             </div><!-- /.box -->
           </div><!-- /.col -->           
           <!-- end post insert --><!-- /.col -->
         </div><!-- /.row -->

<!-- =====================End Above Post Start New Post here==================== -->

         <div class="row">
           <div class="col-md-4">
              <!-- Box Comment -->
              <div class="box box-widget">
                <div class='box-header with-border'>
                  <div class='user-block'>
                    <img src="../../dist/img/edna.jpg" alt="message user image" width="35" height="35" class="img-circle">
                    <span class='username'><a href="#">Edna</a></span>
                    <span class='description'> @ 7:30 PM Today</span>
                  </div>
                  <!-- /.user-block -->
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-toggle='tooltip' title='Mark as read'><i class='fa fa-circle-o'></i></button>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    <button class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class='box-body'>
                This picture is lame.  Check it out....
                  <img class="img-responsive pad" src="../dist/img/photo2.png" alt="Photo">
                  <!-- Removing Original Position of Share Like Options
                  <p>Check out this cool video. What do you guys think?</p>
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Share</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-thumbs-o-up'></i> Like</button>
                  <span class='pull-right text-muted'>127 likes - 3 comments</span>
                  -->
                </div>
                <div class='box-footer box-comments'>
                  <div class='box-comment'>
                    <!-- User image -->
                    <img src="../../dist/img/Gilbert Huph.jpg" alt="message user image" width="35" height="35" class="img-circle img-sm">
                    <span class="comment-text"><span class="username"> Gilbert Huph</span>
                    <span class='description'> @ 7:30 PM Today</span></span>
                    <div class='comment-text'>
                  </span><!-- /.username -->
                  Totally Lame Agree...
                    </div>
                    <!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                  <div class='box-comment'>
                    <!-- User image -->
                    <img src="../../dist/img/jack.jpg" alt="message user image" width="35" height="35" class="img-circle img-sm">
                    <span class="comment-text"><span class="username"> Jack Jack</span></span>
                    <div class='comment-text'>
                  <span class="username"><span class='text-muted pull-right'>8:08 PM Today</span>
                  </span><!-- /.username -->
Those are stupid chairs!
                    </div>
                    <!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                </div><!-- /.box-footer -->
                <div class="box-footer">
                  <form action="#" method="post">
                          <div class="pull-left image"> <img src="<?php if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) { echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg"; }?>" alt="User Image" width="35" height="35" class="img-circle" /> </div>
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                  <!-- Note: Not sure if the share button is a good idea.  As this is just internal and not face book. -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Share</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-thumbs-o-up'></i> Like</button>
                  <!-- Note: Only User that Posted will have the Remove Post feature / And Admin will have remove for everything -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-trash'></i> Remove</button>
                  <span class='pull-right text-muted'>13 likes - 2 comments</span>
                      <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div><!-- /.box-footer -->
             </div><!-- /.box -->
           </div><!-- /.col -->           
           <!-- end post insert --><!-- /.col -->
         </div><!-- /.row -->

<!-- =====================End Above Post Start New Post here==================== -->




<!-- =====================Social Widgents==================== -->
<h2 class="page-header">Social Widgets</h2>

          <div class="row">
            <div class="col-md-4">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-yellow">
                  <div class="widget-user-image">
                    <img class="img-circle" src="../dist/img/user7-128x128.jpg" alt="User Avatar">
                    <h3 class="widget-user-username">Nadia Carmichael</h3>
                  </div><!-- /.widget-user-image -->
                  <h3 class="widget-user-username">Nadia Carmichael</h3>
                  <h5 class="widget-user-desc">Lead Developer</h5>
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Projects <span class="pull-right badge bg-blue">31</span></a></li>
                    <li><a href="#">Tasks <span class="pull-right badge bg-aqua">5</span></a></li>
                    <li><a href="#">Completed Projects <span class="pull-right badge bg-green">12</span></a></li>
                    <li><a href="#">Followers <span class="pull-right badge bg-red">842</span></a></li>
                  </ul>
                </div>
              </div><!-- /.widget-user -->
            </div><!-- /.col -->
            <div class="col-md-4">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-aqua-active">
                  <h3 class="widget-user-username">Alexander Pierce</h3>
                  <h5 class="widget-user-desc">Founder & CEO</h5>
                </div>
                <div class="widget-user-image">
                  <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">3,200</h5>
                        <span class="description-text">SALES</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">13,000</h5>
                        <span class="description-text">FOLLOWERS</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                      <div class="description-block">
                        <h5 class="description-header">35</h5>
                        <span class="description-text">PRODUCTS</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div>
              </div><!-- /.widget-user -->
            </div><!-- /.col -->
            <div class="col-md-4">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-black" style="background: url('../dist/img/photo1.png') center center;">
                  <h3 class="widget-user-username">Elizabeth Pierce</h3>
                  <h5 class="widget-user-desc">Web Designer</h5>
                </div>
                <div class="widget-user-image">
                  <img class="img-circle" src="../dist/img/user3-128x128.jpg" alt="User Avatar">
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">3,200</h5>
                        <span class="description-text">SALES</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4 border-right">
                      <div class="description-block">
                        <h5 class="description-header">13,000</h5>
                        <span class="description-text">FOLLOWERS</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                      <div class="description-block">
                        <h5 class="description-header">35</h5>
                        <span class="description-text">PRODUCTS</span>
                      </div><!-- /.description-block -->
                    </div><!-- /.col -->
                  </div><!-- /.row -->
                </div>
              </div><!-- /.widget-user -->
            </div><!-- /.col -->
          </div><!-- /.row -->



<!-- =========================================================== -->

          <h2 class="page-header">Pictures Here</h2>


          <div class="row">
          
          
          
          
            <div class="col-md-6">
              <!-- Box Comment -->
              <div class="box box-widget">
                <div class='box-header with-border'>
                  <div class='user-block'>
                    <img class='img-circle' src='../dist/img/user1-128x128.jpg' alt='user image'>
                    <span class='username'><a href="#">Edna</a></span>
                    <span class='description'>Shared publicly - 7:30 PM Today</span>
                  </div><!-- /.user-block -->
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-toggle='tooltip' title='Mark as read'><i class='fa fa-clock-o'>2 days ago</i></button>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    <button class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class='box-body'>
                  <img class="img-responsive pad" src="../dist/img/photo2.png" alt="Photo">
                  <p>I took this photo this morning. What do you guys think?</p>
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Share</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-thumbs-o-up'></i> Like</button>
                  <span class='pull-right text-muted'>127 likes - 3 comments</span>
                </div><!-- /.box-body -->
                <div class='box-footer box-comments'>
                  <div class='box-comment'>
                    <!-- User image -->
                    <img class='img-circle img-sm' src='../dist/img/user3-128x128.jpg' alt='user image'>
                    <div class='comment-text'>
                      <span class="username">
                        Maria Gonzales
                        <span class='text-muted pull-right'>8:03 PM Today</span>
                      </span><!-- /.username -->
                      It is a long established fact that a reader will be distracted
                      by the readable content of a page when looking at its layout.
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                  <div class='box-comment'>
                    <!-- User image -->
                    <img class='img-circle img-sm' src='../dist/img/user4-128x128.jpg' alt='user image'>
                    <div class='comment-text'>
                      <span class="username">
                        Luna Stark
                        <span class='text-muted pull-right'>8:03 PM Today</span>
                      </span><!-- /.username -->
                      It is a long established fact that a reader will be distracted
                      by the readable content of a page when looking at its layout.
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                </div><!-- /.box-footer -->
                <div class="box-footer">
                  <form action="#" method="post">
                    <img class="img-responsive img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="alt text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                      <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
            
            
            <div class="col-md-6">
              <!-- Box Comment -->
              <div class="box box-widget">
                <div class='box-header with-border'>
                  <div class='user-block'>
                    <img class='img-circle' src='../dist/img/user1-128x128.jpg' alt='user image'>
                    <span class='username'><a href="#">Jonathan Burke Jr.</a></span>
                    <span class='description'>Shared publicly - 7:30 PM Today</span>
                  </div><!-- /.user-block -->
                  <div class='box-tools'>
                    <button class='btn btn-box-tool' data-toggle='tooltip' title='Mark as read'><i class='fa fa-circle-o'></i></button>
                    <button class='btn btn-box-tool' data-widget='collapse'><i class='fa fa-minus'></i></button>
                    <button class='btn btn-box-tool' data-widget='remove'><i class='fa fa-times'></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class='box-body'>
                  <!-- post text -->
                  <p>Far far away, behind the word mountains, far from the
                    countries Vokalia and Consonantia, there live the blind
                    texts. Separated they live in Bookmarksgrove right at</p>
                  <p>the coast of the Semantics, a large language ocean.
                    A small river named Duden flows by their place and supplies
                    it with the necessary regelialia. It is a paradisematic
                    country, in which roasted parts of sentences fly into
                    your mouth.</p>

                  <!-- Attachment -->
                  <div class="attachment-block clearfix">
                    <img class="attachment-img" src="../dist/img/photo1.png" alt="attachment image">
                    <div class="attachment-pushed">
                      <h4 class="attachment-heading"><a href="http://www.lipsum.com/">Lorem ipsum text generator</a></h4>
                      <div class="attachment-text">
                        Description about the attachment can be placed here.
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry... <a href="#">more</a>
                      </div><!-- /.attachment-text -->
                    </div><!-- /.attachment-pushed -->
                  </div><!-- /.attachment-block -->

                  <!-- Social sharing buttons -->
                  <button class='btn btn-default btn-xs'><i class='fa fa-share'></i> Share</button>
                  <button class='btn btn-default btn-xs'><i class='fa fa-thumbs-o-up'></i> Like</button>
                  <span class='pull-right text-muted'>45 likes - 2 comments</span>
                </div><!-- /.box-body -->
                <div class='box-footer box-comments'>
                  <div class='box-comment'>
                    <!-- User image -->
                    <img class='img-circle img-sm' src='../dist/img/user3-128x128.jpg' alt='user image'>
                    <div class='comment-text'>
                      <span class="username">
                        Maria Gonzales
                        <span class='text-muted pull-right'>8:03 PM Today</span>
                      </span><!-- /.username -->
                      It is a long established fact that a reader will be distracted
                      by the readable content of a page when looking at its layout.
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                  <div class='box-comment'>
                    <!-- User image -->
                    <img class='img-circle img-sm' src='../dist/img/user5-128x128.jpg' alt='user image'>
                    <div class='comment-text'>
                      <span class="username">
                        Nora Havisham
                        <span class='text-muted pull-right'>8:03 PM Today</span>
                      </span><!-- /.username -->
                      The point of using Lorem Ipsum is that it has a more-or-less
                      normal distribution of letters, as opposed to using
                      'Content here, content here', making it look like readable English.
                    </div><!-- /.comment-text -->
                  </div><!-- /.box-comment -->
                </div><!-- /.box-footer -->
                <div class="box-footer">
                  <form action="#" method="post">
                    <img class="img-responsive img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="alt text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                      <input type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div><!-- /.box-footer -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
















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
// Get context with jQuery - using jQuery's .get() method.
var ctx = $("#dispatchChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.
<?php
# Create array with months.
$sql = "select monthname(str_to_date(pu_month,'%m-%y')),sum(pickups) from
(
SELECT
date_format(str_to_date(hawbDate,'%c/%e/%Y'),'%m-%y') pu_month,
sum(CASE monthname(str_to_date(hawbDate,'%c/%e/%Y'))
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'Octover' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    dispatch
WHERE

    puAgentDriverPhone = (SELECT 
            driverid
        FROM
            users
        WHERE
            username = \"$username\")           
AND 
str_to_date(hawbDate,'%c/%e/%Y') > DATE(now()) - INTERVAL 12 MONTH
group by pu_month
UNION ALL
SELECT
date_format(str_to_date(dueDate,'%c/%e/%Y'),'%m-%y') pu_month,
sum(CASE monthname(str_to_date(dueDate,'%c/%e/%Y'))
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'October' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    dispatch
WHERE

    delAgentDriverPhone = (SELECT 
            driverid
        FROM
            users
        WHERE
            username = \"$username\")           
AND 
str_to_date(dueDate,'%c/%e/%Y') > DATE(now()) - INTERVAL 12 MONTH
group by pu_month
) foo
group by pu_month
order by pu_month DESC";

$months = array();
$dispatch_number = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result,MYSQL_BOTH))
{
  array_push($months,"'$row[0]'");
  array_push($dispatch_number,$row[1]);
}
mysql_free_result($result);

$months =  rtrim(implode(',',$months),',');
$dispatch_number =  rtrim(implode(',',$dispatch_number),',');
?>
var data = {
    labels: [<?php echo $months;?>],
    datasets: [
        {
            label: "Dispatched",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo $dispatch_number;?>]
        },
<?php
$sql = "SELECT
monthname(date) pu_month,
SUM(CASE monthname(date)
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'October' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    driverexport
WHERE employee_id =
(select employee_id from users where username = \"$username\")
AND
date > DATE(now()) - INTERVAL 12 MONTH
AND
(status = 'Picked Up' OR status = 'Delivered')
group by pu_month
order by date DESC";

$months = array();
$dispatch_number = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result,MYSQL_BOTH))
{
  array_push($months,"'$row[0]'");
  array_push($dispatch_number,$row[1]);
}
mysql_free_result($result);

$months =  rtrim(implode(',',$months),',');
$dispatch_number =  rtrim(implode(',',$dispatch_number),',');
?>
       {
            label: "Updated",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo $dispatch_number;?>]
        },
    ]
};
var myLineChart = new Chart(ctx).Line(data, {
});
x = myLineChart.generateLegend();
$("#js-legend").html(x);
</script>

<script>
function update_task(i) {
    // Update the status of our task
    my_db_id = $(i).val();
    my_id = $(i).attr('id');

    $("#top_level_"+my_db_id).hide();
    $.post( "<?php echo $_SERVER['PHP_SELF'];?>", { id: my_db_id, type: 'ajax' })
    .done(function(data, textStatus, request) { 
      var objData = jQuery.parseJSON(data); 
      if (objData.failure) { $("#top_level_"+my_db_id).show();}
     })
    .fail(function(data, textStatus, request) { console.log(data); });
}
</script>

</body>
</html>
