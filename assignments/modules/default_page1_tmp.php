<?php if(!isset($RUN)) { exit(); } ?>



			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue-madison">
						<div class="visual">
							<i class="fa fa-comments"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo $users_count ?>
							</div>
							<div class="desc">
								 <?php echo USERS_COUNT ?>
							</div>
						</div>
						<a class="more" href='?module=local_users'>
						<?php echo L_VIEW_MORE ?> <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat red-intense">
						<div class="visual">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo $qst_count ?>
							</div>
							<div class="desc">
								 <?php echo QUESTIONS_COUNT ?>
							</div>
						</div>
						<a class="more" href='?module=questions_bank'>
						<?php echo L_VIEW_MORE ?> <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat green-haze">
						<div class="visual">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo $exams_count ?>
							</div>
							<div class="desc">
								<?php echo EXAMS_COUNT ?>
							</div>
						</div>
						<a class="more" href='?module=assignments'>
						<?php echo L_VIEW_MORE ?> <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<div class="dashboard-stat purple-plum">
						<div class="visual">
							<i class="fa fa-globe"></i>
						</div>
						<div class="details">
							<div class="number">
								 <?php echo $surveys_count ?>
							</div>
							<div class="desc">
								 <?php echo SURVEYS_COUNT ?>
							</div>
						</div>
						<a class="more" href='?module=assignments'>
						<?php echo L_VIEW_MORE ?> <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
			</div>






<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>				
				
					<table style="width:100%" border=0>
						<tr>
							<td style="width:45%"><h4 class="heading"><?php echo USERS_COUNT_BY_COUNTRY ?></h4>
							</td>
							<td style="width:2%">&nbsp;</td>
							<td ><h4 class="heading"><?php echo LAST_EXAM ?></h4>
							</td>
						</tr>					
						<tr>
							<td valign=top align=left><div id="divRep"></div>
							</td>
							<td style="width:2%">&nbsp;</td>
							<td valign=top>
								<div style="height:270px;width:100%;margin:15px auto 0">
									<?php
										$chart_width=700;
										$chart_height = 300;
										if(isset($_SESSION['screen_width']))
										{
											if($_SESSION['screen_width']!="")
											{
												if(intval($_SESSION['screen_width'])<1600)
												{
													$chart_width=500;
													$chart_height = 250;
												}
											}
										}
										echo renderChart("FusionCharts/FCF_Column3D.swf", "", $strXML, "byPoint", $chart_width, $chart_height);  
									?>
								</div>
							</td>
						</tr>
					</table>
				
     
                    <div class="row-fluid">
                     
                                                        <div class="heading clearfix">
								<h3 class="pull-left"><?php echo LAST_REG_USES ?></h3>
								<span class="pull-right label label-success">10</span>
							</div>
                        <div>
                            <div id="div_grid"><?php echo $grid_html ?></div>
                        </div>
                             
                    </div>
                        

    <script language="javascript">
	//LoadReport(4,5,"divRep","0");
	</script>
	
	<script language="javascript">
		function RunPageScripts()
		{
			LoadReport(4,5,"divRep","0",2);
		}
	</script>            
           
        
        <STYLE>
            #div_rep {
                width: 490px;
                height: 300px;
                color: red;
                text-color: left;
                text-align: left;
                line-height: 100px;
            }
            #div_rep {    
                position: relative;
            }

            #div_list {


                top: 0;
                right: 0;
                position: absolute;

                color: black;
                text-align: left;
                line-height: 20px;
            }
        </STYLE>