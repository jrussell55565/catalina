        <!-- Main content -->
        <section class="content">
          <!-- Info boxes -->
          <!-- Shipment Boards -->
          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/dispatch/orders.php">
                 <span class="info-box-icon bg-aqua"><i class="fa fa-cog fa-spin"></i></span>
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
              <div class="info-box">
			  <a href="<?php echo HTTP;?>/pages/dispatch/vir.php" class="button2 animated zoomIn">
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
                <span class="info-box-number">VIR Today: <?php echo $virs_daily_count;?><br>
                VIR Previous: <?php echo $virs_weekly_count;?></span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
			  <a href="<?php echo HTTP;?>/pages/tables/userstats.php" class="button2 animated zoomIn">
<style>
a.button2 {
	 -webkit-animation-duration: 6s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
}
</style>              
                <span class="info-box-icon bg-green"><i class="fa fa-tachometer"></i></span>
                </a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/userstats.php">Driver Productivity</a></span>
                <span class="info-box-number">View Your Report </span></div>
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
               <span class="info-box-icon bg-blue"><i class="fa fa-bank"></i></span>
</a>

                <div class="info-box-content">
                  <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/admin/csa.php">  CSA Compliance</a></span>
                <span class="info-box-number">                  View Your Report</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->  



         
            

            
          </div><!-- /.row -->
