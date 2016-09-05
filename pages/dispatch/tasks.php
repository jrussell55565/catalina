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

if (isset($_POST['submit']) && $_POST['submit'] == 'share')
{
  $audience = $_POST['audience'];
  if ($audience == 'PHX')
  {
    $predicate = "AND office='PHX'";
  }
  if ($audience == 'TUS')
  {
    $predicate = "AND office='TUS'";
  }
    if ($audience == 'PHL')
  {
    $predicate = "AND office='PHL'";
  }
    if ($audience == 'DEN')
  {
    $predicate = "AND office='DEN'";
  }
    if ($audience == 'LAX')
  {
    $predicate = "AND office='LAX'";
  }
    if ($audience == 'MIA')
  {
    $predicate = "AND office='MIA'";
  }
    if ($audience == 'ORD')
  {
    $predicate = "AND office='ORD'";
  }
  $message = $_POST['message'];
  $sql = "SELECT 1";
  if (isset($_POST['sendEmail']))
  {
    $sql .= ",email";
  } 
  if (isset($_POST['sendText']))
  {
    $sql .= ",vtext";
  } 
  $sql .= " FROM users WHERE 1=1 $predicate AND status='Active'";

  $sql = mysql_query($sql);
  while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
  {
    if (isset($_POST['sendEmail']))
    {
      sendEmail($row['email'],'Broadcast Message',$message); 
    } 
    if (isset($_POST['sendText']))
    {
      sendEmail($row['vtext'],'Broadcast Message',$message); 
    }
  }
  mysql_free_result($sql);

}
?>


<!DOCTYPE html>
<html>
<head>
<BASE href="http://dispatch.catalinacartage.com">
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
            Tasks / Projects <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
            <?php if ($_SESSION['login'] == 1) { echo "(Admin)"; }?>
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

        <div class="row">
          <div class="col-lg-12 col-xs-12">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <!-- =========================================================== -->
                <center>
                  <h2>Tasks / Projects / Compliance</h2>
                </center>
                <center>
                  <p>You have (PHP) Uncompleated Task(s) <span style="padding: 5px">
                  Admin Select all or jut 1 driver</span></p>
                  <p><span style="padding: 5px">
                    <select class="form-control"  value="" name="driver" required>
                      <option value="">Select Driver</option>
                      <?php
  foreach($drivers as $i)
  {
  ?>
                      <option value="<?php echo $i;?>"><?php echo $i;?></option>
                      <?php
  }
  ?>
                        </select>
                  </span>
                    <label>
                    Open
                    <input name="radio" type="radio" id="opentasksprojects" value="opentasksprojects" checked>
                    </label>
                  Closed
                  <label>
                  <input type="radio" name="radio" id="closedtasksprojects" value="closedtasksprojects">
                  </label>
                  </p>
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
                  <h2>Admin Submit Only Tasks/Projects/</h2>
                </center>
                <center>
                  <p>Show All Tasks/Projects when Admin is logged in</p>
                  <table>
                    <tr>
                      <td width="160" height="29" style="padding: 5px"><label for="status">Submit Date</label>
                          <div class="input-daterange input-group" id="datepicker_update"></div></td>
                      <td colspan="2" style="padding: 5px"><label for="addr1">Assign To</label></td>
                      <td width="127" style="padding: 5px"><label for="status">Assign by<br>
                      </label></td>
                      <td width="159" style="padding: 5px"><p>Category
                        
                        </p>                        </td>
                      <td width="61" style="padding: 5px"><label for="item">Item</label></td>
                      <td width="67" style="padding: 5px"><label for="subitem">SubItem</label></td>
                      <td width="98" style="padding: 5px; width: 90px;"><label for="posneg">Pos Neg</label></td>
                      <td width="152" style="padding: 5px; width: 90px;"><label for="notes">Notes</label></td>
                      <td colspan="2" style="padding: 5px"><label for="completeby">completeby</label><label for="status">Complete by Date</label></td>
                      <td width="152" colspan="2" style="padding: 5px"><label for="addr1">Task points</label></td>
                    </tr>
                    <tr>
                      <td style="padding: 5px"><span class="input-daterange input-group">
                        <input type="text" class="input-sm form-control datepicker" name="violation_date" data-date-format="mm/dd/yyyy"/ required>
                      </span></td>
                      <td colspan="2" style="padding: 5px"><select class="form-control"  value="" name="driver2" required id="driver">
                        <option value="">Select Driver</option>
                        <?php
  foreach($drivers as $i)
  {
  ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php
  }
  ?>
                      </select></td>
                      <td width="127" style="padding: 5px"><select class="form-control"  value="" name="co_driver">
                        <option value="NULL">Select Driver</option>
                        <?php
  foreach($drivers as $i)
  {
  ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php
  }
  ?>
                      </select></td>
                      <td width="159" style="padding: 5px"><select class="form-control" name="violation_cat">
                        <option>choose</option>
                        <option value="<?php echo $a;?>">&lt;?php echo $a;?&gt;</option>
                        <option value="task_project" selected>Task</option>
                        <option value="compliance">Compliance</option>
                        <?php
  foreach($violations as $a)
  {
  ?>
                        <?php
  }
  ?>
                                                                                                                                    </select></td>
                      <td width="61" style="padding: 5px"><select class="form-control" name="violation_group" required>
                        <option value="boards">Boards</option>
                        <option value="VIR">VIR</option>
                        <option value="Productivity">Productivity</option>
                        <option value="CSA">CSA</option>
                        <option value="Other">Other</option>
                                            </select></td>
                      <td width="67" style="padding: 5px"><select class="form-control" name="violation_code" required>
                        <option value="1">Arrived to Shipper</option>
                        <option value="1">Picked Up</option>
                        <option value="arrived to consignee">Arrived to Consignee</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Accessorials">Accessorials</option>
                                            </select></td>
                      <td width="98" style="padding: 5px; width: 90px;"><select class="form-control"  value="" name="violation_weight">
                        <option value="<?php echo $i;?>">&lt;?php echo $i;?&gt;</option>
                        <option value="Positive">Positive</option>
                        <option value="Negative">Negative</option>
                        <?php
  foreach(range(1,10) as $i)
  {
  ?>
                        <?php
  }
  ?>
                                            </select></td>
                      <td width="152" style="padding: 5px; width: 90px;"><input type="text" class="form-control"  value="" name="description" required></td>
                      <td colspan="2" style="padding: 5px"><span class="input-daterange input-group">
                        <input type="text" class="input-sm form-control datepicker" name="violation_date2" data-date-format="mm/dd/yyyy"/ required>
                      </span></td>
                      <td width="152" colspan="2" style="padding: 5px"><input type="text" class="form-control"  value="1" name="totoal_points" required></td>
                    </tr>
                    
                    <tr>
                      <td style="padding: 5px"><input type="submit" name="update_csa" class="btn btn-primary" value="Submit">
                          <?php
        $sql_emp = "SELECT employee_id from users where lower(\"".$row['first_name']."\") = lower(fname)
                AND lower(\"".$row['last_name']."\") = lower(lname)";
        $result_emp = mysql_query($sql_emp);
        $row_emp = mysql_fetch_array($result_emp);
        mysql_free_result($result_emp);
       ?>
                          <input type="hidden" name="hdn_employee_id" value="<?php echo $row_emp[0];?>">                      </td>
                    </tr>
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

<!-- Demo -->
</body>
</html>
