<?php if(!isset($RUN)) { exit(); } ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $PAGE_TITLE ?></title>  
		<script language ="javascript" src="jquery.js"></script>
        <script language ="javascript" src="extgrid.js?ra=<?php echo rand() ?>"></script>
        <script language ="javascript" src="util.js?ra=<?php echo rand() ?>"></script>
        <script language ="javascript" src="d_controls.js?ra=<?php echo rand() ?>"></script>
        <script src="cms.js?ra=<?php echo rand() ?>" type="text/javascript"></script>
		<script type="text/javascript" src="flowplayer/flowplayer-3.2.12.min.js"></script>
		
		<link href="style/ratings.css" type="text/css" rel="stylesheet" />
        <script language="javascript" src="ratings.js" type="text/javascript" ></script>   
		
		<link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
		
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->

<link href="style/d_controls.css" type="text/css" rel="stylesheet" />
<link href="style/index.css" type="text/css" rel="stylesheet" />

<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL PLUGIN STYLES -->
<!-- BEGIN PAGE STYLES -->
<link href="assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE STYLES -->
<!-- BEGIN THEME STYLES -->
<!-- DOC: To use 'rounded corners' style just load 'components-rounded.css' stylesheet instead of 'components.css' in the below style tag -->
<link href="assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-toastr/toastr.min.css"/>

<link rel="stylesheet" href="lib2/chosen/chosen.css" />
<link rel="stylesheet" href="lib2/sticky/sticky.css" />    

<style>
    
    html 
    {
        overflow-y:scroll;
    }
        
</style>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-header-fixed page-quick-sidebar-over-content page-style-square">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
                    
                     <a  href="<?php echo $home ?>"><i class="icon-home icon-white"></i> <span style='font-face:tahoma;font-size:22px;color:#A5FFFA'><?php echo $SYSTEM_NAME ?></span> </a>
                        
			<div class="menu-toggler sidebar-toggler hide">
				<!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
			</div>			
					
		</div>
		
				<div class="hor-menu hidden-sm hidden-xs">
			<ul class="nav navbar-nav">
				<!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the horizontal opening on mouse hover -->
				
				<?php echo  $html ?>
							
			</ul>
		</div>
		
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
                
           
                               
                
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
						
                           <li class="dropdown">
					<a  class="dropdown-toggle">
					 <img id="imgAjaxLoader" style="display:none" src="style/i/ajax-loader4.gif" />
					</a>
				</li>
                            
				<!-- BEGIN INBOX DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
				
			
				
					<a href="#" class="dropdown-toggle" data-toggle="modal" data-target="#myMail"  >
					<i class="icon-envelope-open"></i>
					<span class="badge badge-default" id="lblNotsCount">
					0 </span>
					</a>
					<ul class="dropdown-menu">
						
						<li>
					
						</li>
					</ul>
				</li>
                                                                
                                
				<!-- END INBOX DROPDOWN -->
			
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<?php 
					$thumb_photo = util::get_thumb(access::UserInfo()->user_photo);
				?>
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" class="img-circle" src="user_photos/<?php echo $thumb_photo ?>"/>
					<span class="username username-hide-on-mobile">
					<?php echo $fullname ?></span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-default">
						<li>
							<a href="?module=myprofile">
							<i class="icon-user"></i> <?php echo MY_PROFILE ?></a>
						</li>
                                                
						<li>
							<a href="logout.php">
							<i class="icon-key"></i> <?php echo LOGOUT ?> </a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				
                                
                                
                                
                               
				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>


		<div class="modal fade" id="myMail" tabindex="-1" role="dialog" aria-labelledby="myMailLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <h3><?php echo NOTIFICATIONS ?></h3>
			  </div>
			  <div class="modal-body">
				    <div id="divNots"></div>
                        <div style="display:none" id="divNotBody"></div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo CLOSE ?></button>
				<button type="button" class="btn btn-primary" onclick="ShowNotList()"><?php echo NOTIFICATION_LIST ?></button>
			  </div>
			</div>
		  </div>
		</div>
					
						
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			<ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<!-- END SIDEBAR TOGGLER BUTTON -->
				</li>
				<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
				<li class="sidebar-search-wrapper">
					<!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
					<!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
					<!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
					<form class="sidebar-search " action="index.php?module=search_content" method="POST">
						<a href="javascript:;" class="remove">
						<i class="icon-close"></i>
						</a>
						<div class="input-group">
							<input type="text" name="query" class="form-control" placeholder="<?php echo SEARCH ?>">
							<span class="input-group-btn">
							<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
							</span>
						</div>
					</form>
					<!-- END RESPONSIVE QUICK SEARCH FORM -->
				</li>
				
				<?php 
				
					for($z=0;$z<sizeof($main_modules);$z++){ 
					$in ="";
			   
					if($mid==$main_modules[$z]['id']) $in="in";
					
					$main_mid = $main_modules[$z]['id'];
					
					$onclick = "";
					$file = $main_modules[$z]['file_name'];
					if(trim($file)!="") $onclick= "onclick='window.location.href=\"?mid=$main_mid&module=$file\"'";									   
				
					$open ="";
									   
					if($mid==$main_modules[$z]['id']) $open="active open";											
		
				
				?>
				
				<li class="<?php echo $open ?>">
					<a <?php echo $onclick ?> href="javascript:;">
					<i class="<?php echo $main_modules[$z]['module_icon'] ?>"></i>
					<span class="title"><?php echo $MODULES[$main_modules[$z]['module_name']] ?></span>
					<span class="selected"></span>
					<span class="arrow open"></span>
					</a>
					<?php if(isset($child_modules[$main_modules[$z]['id']])) { ?>
					<ul class="sub-menu">
						<?php for($y=0;$y<sizeof($child_modules[$main_modules[$z]['id']]);$y++) {  
						  
						 $file_name = $child_modules[$main_modules[$z]['id']][$y]["file_name"];
						 $module_id=$main_modules[$z]['id'];
						 //echo "<a  href='index.php?module=$file_name&mid=$module_id'>".."</a>";
                                                                                                    
						?>
						<li >
							<a href='<?php echo "index.php?module=$file_name&mid=$module_id" ?>'>							
							<?php echo $MODULES[$child_modules[$main_modules[$z]['id']][$y]["module_name"]] ?></a>
						</li>
						<?php } ?>
					</ul>
					 <?php } ?>
				</li>
				<?php } ?>						
		
		
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	

	
	<div class="page-content-wrapper">
		<div class="page-content">					       

			
				<?php if($module_name!="default_page1") { ?>    
					<div class="tabbable"> 
                    <?php if($report_display!="") { ?>
                        <h3 class="heading">
                            <?php 
                                echo @desc_func();
                            ?>
                        </h3><hr />
                     <?php } else { ?>
                    <ul class="nav nav-tabs" >                     
                      <li class="active"><a href="#Rtab1" data-toggle="tab">
                        <span >
                            <?php 
                                echo @desc_func();
                            ?>
                        </span>          
                        </a>
                      </li>                      
                      <li style="display:<?php echo $report_display ?>" onclick="LoadReports('<?php echo $current_module['id'] ?>')"><a href="#Rtab2" data-toggle="tab"><?php echo REPORTS ?></a></li>            
                    </ul>
                    <?php } ?>
                    <div class="tab-content">
                        <div class="tab-pane active" id="Rtab1">
                        
                                  
                <?php } ?>
						
							 <br />
							<?php                                            
								include $module_template_file;
							 ?>
                            
                            </div>
                        <div class="tab-pane" id="Rtab2"><div id="dvRep"></div>
                        </div>
                    </div>	
                </div>
		
		</div>
	</div>
	<!-- END CONTENT -->
	<!-- BEGIN QUICK SIDEBAR -->
	
	<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">

	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js" type="text/javascript"></script>
<script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
<script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script>

<script src="assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
<script src="lib2/chartjs/Chart.js"></script>
<script src="js/jquery.mediaTable.min.js" type="text/javascript"></script>

<script src="assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>


<script src="lib2/sticky/sticky.min.js"></script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core componets
   Layout.init(); // init layout
   QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features 
   Index.init();   
   Index.initDashboardDaterange();
   Index.initJQVMAP(); // init index page's custom scripts
   Index.initCalendar(); // init index page's custom scripts
   Index.initCharts(); // init index page's custom scripts
   Index.initChat();
   Index.initMiniCharts();
   Tasks.initDashboardWidget();
});
</script>

   <script src="lib2/chosen/chosen.jquery.js" type="text/javascript"></script>
   
   <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>	

 <script language="javascript">
function exec_js(js)
{        
	var tag = document.createElement("script");
	tag.innerHTML=js;
	document.body.appendChild(tag);
}
</script>
    
<?php 
	include "libs.php";
?>

<script>
	$(document).ready(function() {
		//* show all elements & remove preloader
		//setTimeout('$("html").removeClass("js")',0);   
		try
		{                                           
		RunPageScripts();
		}catch(e) {}
	});
</script>
                        
<script language="javascript">
        var show_image = true;  
         jQuery.ajaxSetup({
		beforeSend: function() {            
		if(show_image==true) $('#imgAjaxLoader').show();
    	 },
		complete: function(){
		$('#imgAjaxLoader').hide();
	 },
		success: function() {}
	 });

</script>
						

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>