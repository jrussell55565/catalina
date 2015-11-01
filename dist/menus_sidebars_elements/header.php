  <header class="main-header">
    <!-- Logo -->
    <a href="/pages/main/index.php" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>CD</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Dashboard</b></span> </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-envelope-o"></i> <span class="label label-success">5</span> </a>
<!-- 
Note: Using <span class="label label-success"> for all Notifications
Email notification:  <span class="label label-success">
Bell  notification:  <span class="label label-warning">
Flag  notification:  <span class="label label-danger">
-->          
            <ul class="dropdown-menu">
              <li class="header">You have 5 new messages of 50 total</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                    <div class="pull-left"><img src="<?php echo HTTP;?>/dist/img/pu_alert.gif" alt="user image" class="img-circle"/></div>
                    <h4> Dispatch <small><i class="fa fa-clock-o"></i> Today</small> </h4>
                    <p>(PRT165863) 11/1/2015 Pu Alert</p>
                    </a> </li>
                  <!-- end message -->
                  <li> <a href="#">
                    <div class="pull-left"><img src="<?php echo HTTP;?>/dist/img/del_alert.gif" alt="user image" class="img-circle"/></div>
                    <h4> Dispatch  <small><i class="fa fa-clock-o"></i> Today</small> </h4>
                    <p>(5312426TEST) 11/1/2015 Delivery Alert</p>
                    </a> </li>
                  <li> <a href="#">
                    <div class="pull-left"><img src="<?php echo HTTP;?>/dist/img/pu_alert.gif" alt="user image" class="img-circle"/></div>
                    <h4> Dispatch <small><i class="fa fa-clock-o"></i> Yesterday</small> </h4>
                    <p>Why not buy a new awesome theme?</p>
                    </a> </li>
                  <li> <a href="#">
                    <div class="pull-left"><img src="<?php echo HTTP;?>/dist/img/del_alert.gif" alt="user image" class="img-circle"/></div>
                    <h4> Dispatch <small><i class="fa fa-clock-o"></i> Yesterday</small> </h4>
                    <p>(5312426TEST) 11/1/2015 Delivery Alert</p>
                    </a> </li>
                  <li> <a href="#">
                    <div class="pull-left"><img src="<?php echo HTTP;?>/dist/img/del_alert.gif" alt="user image" class="img-circle"/></div>
                    <h4> Dispatch <small><i class="fa fa-clock-o"></i> 2 days ago</small> </h4>
                    <p>(5312426TEST) 11/1/2015 Delivery Alert</p>
                    </a> </li>
                </ul>
              </li>
              <li class="footer">Please update your boards to clear MSG's!</li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell-o"></i> <span class="label label-success">19</span> </a>
<!-- 
Note: Using <span class="label label-success"> for all Notifications
Email notification:  <span class="label label-success">
Bell  notification:  <span class="label label-warning">
Flag  notification:  <span class="label label-danger">
-->            
            <ul class="dropdown-menu">
              <li class="header">You have 19 notifications of 380 Total</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li> <a href="#"> <i class="fa fa-users text-aqua"></i> 5  new company updates today </a> </li>
                  <li> <a href="#"> <i class="fa fa-birthday-cake text-green"></i> 4 Upcomming B-Days this month </a> </li>
                  <li> <a href="#"> <i class="fa fa-thumbs-o-up text-blue"></i> 3 Upcomming Work Anniversaries </a> </li>
                  <li> <a href="#"> <i class="fa fa-user-plus text-yellow"></i> 4 New Arrivals this month</a> </li>
                  <li> <a href="#"> <i class="fa fa-refresh text-red"></i> 3 Users with Expirations </a> </li>
                </ul>
              </li>
              <li class="footer"><a href="#">Click here to mark all viewed!</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-flag-o"></i> <span class="label label-success">6</span> </a>
<!-- 
Note: Using <span class="label label-success"> for all Notifications
Email notification:  <span class="label label-success">
Bell  notification:  <span class="label label-warning">
Flag  notification:  <span class="label label-danger">
-->            
            <ul class="dropdown-menu">
              <li class="header">Todays Tasks, you have 6 tasks!</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="/pages/dispatch/orders.php">
                    <h3> Pick Ups Today <?php echo "$pu_today_count";?> <small class="pull-right">0%</small> </h3>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-aqua" style="width: 0%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> <span class="sr-only">0% Complete</span> </div>
                    </div>
                    </a> </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="/pages/dispatch/orders.php">
                    <h3> Deliveries Today <?php echo "$del_today_count";?> <small class="pull-right">0%</small> </h3>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-green" style="width: 0%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> <span class="sr-only">0% Complete</span> </div>
                    </div>
                    </a> </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                    <h3> VIR Pre Trip  <small class="pull-right">0%</small> </h3>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-red" style="width: 0%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> <span class="sr-only">0% Complete</span> </div>
                    </div>
                    </a> </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                    <h3> VIR Post Trip  <small class="pull-right">0%</small> </h3>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-red" style="width: 0%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> <span class="sr-only">0% Complete</span> </div>
                    </div>
                    </a> </li>
                  <!-- end task item -->
                  <li><!-- Task item -->
                    <a href="#">
                    <h3> IFTA &amp; Fuel Entries <small class="pull-right">0%</small> </h3>
                    <div class="progress progress-xs">
                      <div class="progress-bar progress-bar-yellow" style="width: 0%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"> <span class="sr-only">0% Complete</span> </div>
                    </div>
                    </a> </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer"> <a href="#">Click here to mark all viewed!</a> </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less Add in PHP to look at Image file for Driver Image-->
          <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="<?php echo HTTP;?>/dist/img/usernophoto.jpg" class="user-image" alt="User Image"/> <span class="hidden-xs"><?php echo "$drivername"; ?></span></a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header"> <img src="<?php echo HTTP;?>/dist/img/usernophoto.jpg" class="img-circle" alt="User Image" />
                <p> <?php echo "$drivername"; ?> </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="col-xs-4 text-center"> <a href="#">Stats</a> </div>
                <div class="col-xs-4 text-center"> <a href="#">Messages</a> </div>
                <div class="col-xs-4 text-center"> <a href="#">VIRS</a> </div>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left"> <a href="/pages/dispatch/userprofile.php" class="btn btn-primary btn-flat">Profile</a> </div>
                <div class="pull-right"> <a href="/pages/login/logout.php" class="btn btn-primary btn-flat">Sign out</a> </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li> <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> </li>
        </ul>
      </div>
    </nav>
  </header>
