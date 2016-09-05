<?php
$sql = "SELECT total_today.counts   AS total_today_count, 
       pu_today.counts      AS pu_today_count, 
       del_today.counts     AS del_today_count, 
       total_alltime.counts AS total_alltime_count, 
       pu_alltime.counts    AS pu_alltime_count, 
       del_alltime.counts   AS del_alltime_count, 
       archived.counts      AS archived_count, 
       virs_daily.count     AS virs_daily_count, 
       virs_weekly.count    AS virs_weekly_count 
FROM   ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  ( 
                            puagentdriverphone= 
                            ( 
                                   SELECT driverid 
                                   FROM   users 
                                   WHERE  username=\"$username\" ) 
                     AND    Str_to_date(hawbdate,'%c/%e/%Y') = Curdate() 
                     AND    deleted =\"F\" 
                     AND    archived =\"F\" ) 
              OR     ( 
                            delagentdriverphone= 
                            ( 
                                   SELECT driverid 
                                   FROM   users 
                                   WHERE  username =\"$username\" ) 
                     AND    Str_to_date(duedate,'%c/%e/%Y') = Curdate() 
                     AND    deleted =\"F\" 
                     AND    archived =\"F\" ) ) total_today, 
       ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  puagentdriverphone= 
                     ( 
                            SELECT driverid 
                            FROM   users 
                            WHERE  username=\"$username\" ) 
              AND    Str_to_date(hawbdate,'%c/%e/%Y') = Date(Now()) 
              AND    deleted =\"F\" 
              AND    archived =\"F\" 
              AND    deleted =\"F\" 
              AND    archived =\"F\" ) pu_today, 
       ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  delagentdriverphone= 
                     ( 
                            SELECT driverid 
                            FROM   users 
                            WHERE  username=\"$username\" ) 
              AND    Str_to_date(duedate,'%c/%e/%Y') = Date(Now()) 
              AND    deleted =\"F\" 
              AND    archived =\"F\" 
              AND    deleted =\"F\" 
              AND    archived =\"F\" ) del_today, 
       ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  ( 
                            delagentdriverphone= 
                            ( 
                                   SELECT driverid 
                                   FROM   users 
                                   WHERE  username=\"$username\" ) 
                     OR     puagentdriverphone= 
                            ( 
                                   SELECT driverid 
                                   FROM   users 
                                   WHERE  username=\"$username\" ) ) 
              AND    deleted =\"F\" 
              AND    archived=\"F\" ) total_alltime, 
       ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  puagentdriverphone= 
                     ( 
                            SELECT driverid 
                            FROM   users 
                            WHERE  username=\"$username\" ) 
              AND    deleted =\"F\" 
              AND    archived=\"F\" ) pu_alltime, 
       ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  delagentdriverphone= 
                     ( 
                            SELECT driverid 
                            FROM   users 
                            WHERE  username=\"$username\" ) 
              AND    deleted =\"F\" 
              AND    archived=\"F\" ) del_alltime, 
       ( 
              SELECT Count(*) AS counts 
              FROM   dispatch 
              WHERE  ( 
                            delagentdriverphone= 
                            ( 
                                   SELECT driverid 
                                   FROM   users 
                                   WHERE  username=\"$username\" ) 
                     OR     puagentdriverphone= 
                            ( 
                                   SELECT driverid 
                                   FROM   users 
                                   WHERE  username=\"$username\" ) ) 
              AND    deleted =\"F\" 
              AND    archived=\"T\" ) archived, 
       ( 
              SELECT Count(*) AS count 
              FROM   virs 
              WHERE  driver_name =\"$username\" 
              AND    insp_date = Date(Now()) ) virs_daily, 
       ( 
              SELECT Count(*) AS count 
              FROM   virs 
              WHERE  driver_name=\"$username\" 
              AND    insp_date BETWEEN Date(Now()) AND    Date(Now()) - interval 8 day ) virs_weekly";

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
?>
        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
          <!-- Shipment Boards -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-blue"><i class="fa fa-spinner fa-pulse fa-fw"></i></span>
                </a>               
            <div class="info-box-content">
			     <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">Shipment Boards</a></span><span class="info-box-number">PU Today:<?php echo "$pu_today_count";?><br>
                 DEL Today:   <?php echo "$del_today_count";?></span></div>
                 <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            


            <!-- fix for small devices only  Trying to Add Font Animations here-->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/vir.php" class="button2 animated zoomIn">
<style>
a.button2 {
	 -webkit-animation-duration: 6s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
}
</style>              
                <span class="info-box-icon bg-red"><i class="fa fa-wrench"></i></span>
                </a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/vir.php">Vehicle Inspections</a></span>
                <span class="info-box-number">VIR's Today: <?php echo $virs_daily_count;?><br>
                Last 8 Days VIR's: <?php echo $virs_weekly_count;?></span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
			  <a href="<?php echo HTTP;?>/pages/dispatch/productivity.php" class="button2 animated zoomIn">
<style>
a.button2 {
	 -webkit-animation-duration: 6s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
}
</style>              
                <span class="info-box-icon bg-purple"><i class="fa fa-line-chart"></i></span>
                </a>
                <div class="info-box-content"><span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/productivity.php">PRODUCTIVITY</a></span> <span class="info-box-number">Your Tasks: <?php echo $virs_daily_count;?><br />
  Your Projects: <?php echo $virs_weekly_count;?></span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->





            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="<?php echo HTTP;?>/pages/dispatch/admin/csa.php" class="button3 animated pulse">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-orange"><i class="fa fa-bank"></i></span>
</a>

                <div class="info-box-content"><span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/admin/csa.php">COMPLIANCE</a></span> <span class="info-box-number">Your CSA Score: <?php echo $virs_daily_count;?><br />
Int. Compliance Score: <?php echo $virs_weekly_count;?></span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->  



         
            

            
          </div><!-- /.row -->
