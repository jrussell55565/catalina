<?php if(!isset($RUN)) { exit(); } ?>

<HTML>
    <HEAD>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<META http-equiv="content-type" content="text/html; charset=utf-8">
                <script language ="javascript" src="jquery.js"></script>
                <script language ="javascript" src="extgrid.js"></script>
                        <script language ="javascript" src="util.js"></script>
                <script type="text/javascript" src="flowplayer/flowplayer-3.2.12.min.js"></script>
                <script src="uppod-0.4.4.js" type="text/javascript"></script>
                <script src="cms.js" type="text/javascript"></script>                                  
                <link href="style/index_mob.css" type="text/css" rel="stylesheet" />
                <link href="style/grid.css" type="text/css" rel="stylesheet" />
                <link href="style/d_controls_mob.css" type="text/css" rel="stylesheet" />
                  <script language ="javascript" src="d_controls.js"></script>
                <title><?php echo $PAGE_TITLE ?></title>                

    </HEAD>
       
    <BODY bgcolor="#97A3AF" >

        <script language="javascript" src="ratings.js" type="text/javascript" ></script> 
        
         <script language="javascript">
         /*
         window.onscroll = function()
         {
            MoveLoadingMessage("loadingDiv");
         }

         jQuery.ajaxSetup({
            beforeSend: function() {            
            $('#loadingDiv').show()
         },
            complete: function(){
            $('#loadingDiv').hide()
         },
            success: function() {}
         });
         */
        </script>
        
        <SCRIPT language="javascript">
            function ShowMobileMenu()
            {              
                 
                 document.getElementById('tblMobileMenu').style.position="absolute";
                 document.getElementById('tblMobileMenu').style.top='0px';                        
                 document.getElementById('tblMobileMenu').style.left='0px';
                 document.getElementById('tblMobileMenu').style.display="";
                 
                 
            }
            function CloseMobileMenu()
            {
                document.getElementById('tblMobileMenu').style.display="none";
            }
        </SCRIPT>
        
              <table style="display:none" id="loadingDiv" style="position: absolute; left: 10px; top: 10px">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td bgcolor="red">
                                        <font color="white" size="3"><b>&nbsp;<?php echo PLEASE_WAIT ?>&nbsp;</b></font>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
               </table>

        <script language="javascript">
            MoveLoadingMessage("loadingDiv");
        </script>

               <table style="display:none;width:300px;height:300px" border="0" class="c_menu_td" bgcolor="white"  id="tblMobileMenu">
               <tr >                                                                
                   <td valign="top"><br />
                       <a onclick="CloseMobileMenu()"><label id="c324" class="menu_child_name"><b><?php echo CLOSE_MENU ?></b></label>   </a>                    
                      
                   </td>                             
               </tr>
               <tr><td> <br /></td></tr>
               
                                                    <?php
                                                        for($z=0;$z<sizeof($main_modules);$z++)
                                                        {
                                                            if($MODULES[$main_modules[$z]['module_name']] == "Dashboard") continue;
                                                            ?>
                                                            <tr >                                                                
                                                                <td valign="top">                                                                                                                                       
                                                                      <label id="ctlmenuname" class="menu_header_name"><?php echo $MODULES[$main_modules[$z]['module_name']] ?></label>
                                                                      <table cellpadding="0" cellspacing="0" border="0" style="background: url('i/ln.gif') repeat-x;
                                                                       height: 1px; width: 75%; margin-top: 10px; margin-bottom: 5px;">
                                                                            <tr>
                                                                            <td>
                                                                            </td>
                                                                            </tr>
                                                                      </table>                           
                                                                     
                                                                            <table class="class1" cellspacing="0" cellpadding="3" Border="0" border="0" style="width:100%;border-collapse:collapse;">
																		 <?php if(isset($child_modules[$main_modules[$z]['id']])) { ?>
                                                                                <?php for($y=0;$y<sizeof($child_modules[$main_modules[$z]['id']]);$y++) {  ?>
                                                                                <tr >
                                                                                    <td valign="top">
                                                                                         <?php 
                                                                                            $file_name = $child_modules[$main_modules[$z]['id']][$y]["file_name"];
                                                                                            echo "<a class=\"menu_child_name\" href='index.php?module=$file_name'>".$MODULES[$child_modules[$main_modules[$z]['id']][$y]["module_name"]]."</a>";
                                                                                         ?>
                                                                                    </td>
                                                                                </tr>
                                                                                <?php } } ?>
                                                                            </table>
                                                                      <br>
                                                                      
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    ?>
                                                
          </table>
        
        <table bgcolor="#258AB5" style="width:100%">
            
             <tr>
                                    <td >
                                        <font color="#FFFFF3"><?php echo $SYSTEM_NAME ?></font>
                                    </td>
                                    <td align="right">
                                        <font color="#FFFFF3" size="2"><?php echo $fullname ?></font>
                                    </td>
                                </tr>
        </table>
        
         <table width="100%" cellpadding="0" cellspacing="0" border="0">
    
            <tr  >
               
                
                <td  align="center" valign="top" bgcolor="#F4F5F7" >
                          <table width="95%" cellpadding="0" cellspacing="0" border="0" >
                              
                                <tr>
                                    <td >
                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td onclick="ShowMobileMenu()"><img src="style/i/<?php echo $PHONE_MENU_IMG ?>" /></td>
                                                <td align="right" width="10%"><a href="logout.php" border="1"><img border=0 src="<?php echo $LOGOUT_BUTTON_FILE ?>" /></a>&nbsp;&nbsp;&nbsp;</td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                    </td>
                                </tr>
                                <tr>                                    
                                        <td valign="top" bgcolor="#F4F5F7">
                                         
                                                    <?php if($module_name!="default_page1") { ?>    
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                        <td class="main_table_desc_text">
                                                                            <font color="black" >
                                                                                <?php
                                                                                    echo desc_func();
                                                                                ?>
                                                                            </font>
                                                                        </td>                                                                       
                                                                    </tr>
                                                                    <tr>
                                                                        <td><hr><br></td>
                                                                    </tr>
                                                                </table>
                                                    <?php } ?>     
                                                    </td>
                                                                                                    
                                    </tr>
                                    <tr >
                                        <td valign="top">
                                             <?php
											
												include  $module_template_file ;
                                             ?>
                                            <br /> <br />
                                        </td>
                                    </tr>
                            </table>

                </td>
            </tr>
         </table>
        <div style="display:<?php echo DEBUG_SQL=="yes" ? "" : "none" ?>">
        <table style="width:100%" style="display:<?php echo DEBUG_SQL=="yes" ? "" : "none" ?>">
            <tr>
                <td bgcolor="white">
                    <table style="width:100%" cellpadding="0" cellspacing="0">
                        <?php
                        for($i=0;$i<count($queries);$i++)
                        {
                            ?>
                                <tr>
                                    <td bgcolor="moccasing" class="query_head">
                                      <b>Query <?php echo $i+1 ?></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="query">
                                        <?php echo util::getFormattedSQL($queries[$i]) ?>                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <br>
                                    </td>
                                </tr>
                            <?php
                        }
                        ?>
                    </table>
                </td>
            </tr>
        </table>
        </div>
              
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
        
   </BODY>

   

</HTML>