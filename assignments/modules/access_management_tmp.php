<?php if(!isset($RUN)) { exit(); } ?>
<form method="post" name="form1" >

    <table width="90%" >
        <tr>
            <td width="30%">
                <?php echo MODULE_ACCESS ?>
            </td>
            <td width="30%">
                <?php echo PAGE_ACCESS ?>
            </td>
              <td width="30%">
                <?php echo BRANCH_ACCESS ?>
            </td>
        </tr>
        <tr>
            <td valign="top">
                            

<div id="acc1" class="acc1" >
	<ul>
                <?php for($i=0;$i<count($rmain_modules);$i++) { ?>
		<li id="rhtml_1" class="jstree-open">
			<input <?php echo IsChecked($rmain_modules[$i]["id"]) ?> name="arrMain[]" <?php echo $disable ?> value="<?php echo $rmain_modules[$i]["id"] ?>" class='els' type="checkbox">&nbsp; <a href="#"><?php echo util::get_value($MODULES[$rmain_modules[$i]["module_name"]],$rmain_modules[$i]["module_name"]); ?></a>
			<ul>
                                <?php                                                                     
                                    $rchild_modules = db::Select($modules_res, "parent_id", $rmain_modules[$i]["id"] );                                        
                                    for($y=0;$y<count($rchild_modules);$y++) {
                                ?>
				<li id="rhtml_2">
                                        
					<input <?php echo IsChecked($rchild_modules[$y]["id"]) ?> name="arrChild[]" <?php echo $disable ?> value="<?php echo $rchild_modules[$y]["id"] ?>" class='els' type="checkbox">&nbsp;<a href="#"><?php echo util::get_value($MODULES[$rchild_modules[$y]["module_name"]],$rchild_modules[$y]["module_name"]) ?></a>
                                        <ul>
                                             <?php                                     
                                                 $module_access = db::Select($access_res, "parent_id", $rchild_modules[$y]["id"] );     
                                            //     if($rmain_modules[$i]["id"]==43) echo count($module_access)."zzz";
                                                 for($z=0;$z<count($module_access);$z++) {
                                            ?>
                                            <li >
                                                <input <?php echo IsAccessChecked($module_access[$z]["id"]) ?> name="arrAcc[]" <?php echo $disable ?> value="<?php echo $module_access[$z]["id"] ?>" class='els' type="checkbox">&nbsp;<?php echo util::get_arr_value($MODULE_ACCESS,$module_access[$z]["access_name"]) ?>
                                            </li>
                                            <?php } ?>
                                            
                                        </ul>
				</li>
                                <?php } ?>
                                                            
				
			</ul>
		</li>	
                <?php } ?>
	</ul>
</div>
                               
            </td>
            <td valign="top">
                
<div id="acc2" class="acc2" >
	<ul>
                <?php for($i=0;$i<count($rmain_pages);$i++) { ?>
		<li id="rhtml_1" class="jstree-open">
			<input <?php echo IsPageChecked($rmain_pages[$i]["id"]) ?> name="arrMainPages[]" value="<?php echo $rmain_pages[$i]["id"] ?>" class='els' type="checkbox">&nbsp; <a href="#"><?php echo $rmain_pages[$i]["page_name"]; ?></a>
			<ul>
                                <?php                                     
                                    $rchild_pages = db::Select($page_res, "parent_id", $rmain_pages[$i]["id"] );                                  
                                    for($y=0;$y<count($rchild_pages);$y++) {
                                ?>
				<li id="rhtml_2">
					<input <?php echo IsPageChecked($rchild_pages[$y]["id"]) ?> name="arrMainPages[]" value="<?php echo $rchild_pages[$y]["id"] ?>" class='els' type="checkbox">&nbsp;<a href="#"><?php echo $rchild_pages[$y]["page_name"] ?></a>                                    
				</li>
                                <?php } ?>
                                                            
				
			</ul>
		</li>	
                <?php } ?>
	</ul>
</div>
          
                <table style="width:100%">
                    <tr>
                        <td>
                            <br /><br /><br />
                        </td>
                    </tr>
                      <tr>
                        <td>
                            
                                <div id="div_grid"><?php echo $gridrep_html ?></div>
                            
                            <br>
                        </td>
                    </tr>
                      <tr>
                        <td>
                            <hr />
                        </td>
                    </tr>
                   
                </table>
            </td>
            
            <td valign="top">
                <table>
                    <tr>
                        <td>
                            <input class='els' type="radio" <?php echo $disable ?> <?php echo IsBranchChecked("1") ?> name="grpAccess" <?php echo IsBranchDisabled("1") ?> value="1"> <?php echo ACCESS_TO_ALL_BRANCHES ?>
                            <br>
                            <input class='els' type="radio" <?php echo $disable ?> <?php echo IsBranchChecked("2") ?> name="grpAccess" <?php echo IsBranchDisabled("2") ?> value="2"> <?php echo ACCESS_TO_OWN_BRANCH ?>    
                            <br>
                            <input class='els' type="radio" <?php echo $disable ?> <?php echo IsBranchChecked("3") ?> name="grpAccess" <?php echo IsBranchDisabled("3") ?> value="3"> <?php echo ACCESS_TO_OWN_RECORDS ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php echo DEFAULT_FOR_PAGE ?> : <select <?php echo $disable ?> id="drpDef" name="drpDef"><?php echo $default_options ?></select>
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <hr />
                        </td>
                    </tr>
                     <tr>
                        <td>
                            <?php echo GRD_EXPORT_ACC ?> : <select  id="drpExport" name="drpExport"><?php echo $export_options ?></select>
                        </td>
                    </tr>
                 
                </table><br />
                <table style="width:90%" align="right">
                    <tr>
                        <td>
                            <br /><br /><br />
                        </td>
                    </tr>
                      <tr>
                        <td>
                            
                                <div id="div_grid"><?php echo $grid_html ?></div>
                            
                            <br>
                        </td>
                    </tr>
                      <tr>
                        <td>
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td class="desc_text">
                            <?php echo DEFAULT_VIEW ?> : <select id="drpDefView" name="drpDefView"><?php echo $default_views ?></select>
                        </td>
                    </tr>
                     <tr>
                        <td class="desc_text">
                            <?php echo IS_TECH ?> : <select id="drpIsTech" name="drpIsTech"><?php echo $tech_opts ?></select>
                        </td>
                    </tr>
                     <tr>
                        <td class="desc_text">
                            <?php echo NOT_ON_TICK_CREAT ?> : <select id="drpNotTick" name="drpNotTick"><?php echo $tick_opts ?></select>
                        </td>
                    </tr>
                </table>
            </td>
            
        </tr>
    </table>
    
    <hr />

    
<input type="submit" class='btn green' name="btnSave" style="width:200px" value="<?php echo SAVE ?>" />
<input type="submit" class='btn green' name="btnCancel" style="width:200px" value="<?php echo CANCEL ?>" />
    
</form>