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
			     <span class="info-box-text"><a href="/pages/dispatch/orders.php">Load Board</a></span><span class="info-box-number">Todays PU:<?php echo "$pu_today_count";?><br>
                 Todays DEL:   <?php echo "$del_today_count";?></span></div>
                 <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->



            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
              <a href="<?php echo HTTP;?>/pages/dispatch/vir.php" class="button animated rubberBand">
<style>
 a.button {
	 -webkit-animation-duration: 5s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
}
</style>
                <span class="info-box-icon bg-red"><i class="fa fa-wrench faa-wrench animated"></i></span>
</a>               
                <div class="info-box-content">
                  <p><span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/vir.php">VIRs</a></span>
                    <span class="info-box-number">Todays  VIRS: X <?php echo "$vir_today_count";?> <br>Last 8 days VIRS: X</span></p>
				</div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
			  <a href="<?php echo HTTP;?>/pages/tables/fuel.php" class="button2 animated zoomIn">
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
                <span class="info-box-text"><a href="/pages/dispatch/costats.php">Company Stats</a> / <a href="/pages/dispatch/userstats.php">User Stats</a></span>
                <span class="info-box-number">Todays Stats: X
                <br>Last 30 days Stats: X</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box"><a href="<?php echo HTTP;?>/pages/tables/ifta.php" class="button3 animated jello">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-yellow"><i class="fa fa-newspaper-o"></i></span>
</a>
                <div class="info-box-content">
                <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/ifta.php"> IFTA/Fuel Reports</a></span><span class="info-box-number">Todays  IFTA: X<br>
Last 8 days IFTA: X</span></div><!-- /.info-box-content -->
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
                  <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/admin/csa.php">  DOT Safety Report</a></span>
                <span class="info-box-number">                  View Your Report</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            
            

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="<?php echo HTTP;?>/pages/dispatch/location.php" class="button3 animated pulse">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-purple"><i class="fa fa-map-marker"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/location.php">Locate Users</a></span><span class="info-box-number"> See where other drivers are</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->


            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="<?php echo HTTP;?>/pages/dispatch/userstats.php" class="button3 animated tada">
<style>
 a.button3 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-#607D8B"><i class="fa fa-check-square-o"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/userstats.php">   Stats</a></span><span class="info-box-number">                   View your  Stats</span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->            
            


            
            
            

<div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
<a href="<?php echo HTTP;?>/pages/dispatch/admin/users.php" class="button4 animated pulse">
<style>
 a.button4 {
	 -webkit-animation-duration: 3s;
	 -webkit-animation-delay: 1s;
	 -webkit-animation-iteration-count: infinite;
 }
</style>
               <span class="info-box-icon bg-brown"><i class="fa fa-users"></i></span>
               </a>
                <div class="info-box-content">
                  <span class="info-box-text"><a href="<?php echo HTTP;?>/pages/dispatch/admin/users.php"> My Profile</a></span>
                  <span class="info-box-number">Contact Other Users</span><span class="info-box-number"><br>
                </span></div>
                <!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.info-box -->
          </div><!-- /.row -->
