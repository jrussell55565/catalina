<?php if(!isset($RUN)) { exit(); } ?>

<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>

<?php echo $val->DrowJsArrays(); ?>

<script language='javascript'>
function LoadSubjects()
{    
    var v_quiz_id = $("#drpQuiz").val();
    var id_link = querySt("id") !="-1" ? "&id="+querySt("id") : "";   
     $.post("?module=add_question&quiz_id=-1"+id_link, { quiz_id: v_quiz_id, ajax: "yes" , load_subjects:"yes" },
    function(data){        
           document.getElementById('divSubjects').innerHTML = data;
   });
}

</script>

<form id="form1" method="post"  enctype="multipart/form-data" class="form-inline" >

<div id="content">
<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
    <li class="active"><a href="#red" data-toggle="tab"><?php echo GEN_SETTINGS ?></a></li>
    <li><a href="#diff_sett" data-toggle="tab"><?php echo L_DIFF_SET ?></a></li>    
    <li><a href="#orange" data-toggle="tab"><?php echo ADD_SETTINGS ?></a></li>
    <li style='display:none'><a href="#dyn_sett" data-toggle="tab"><?php echo L_DYN_SET ?></a></li>

</ul>
<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="red">

   
    
<table class="desc_text" style="width:950px">
    <tr style="display:<?php echo $quiz_display ?>">
        <td valign="top" >
            <?php echo QUIZ_NAME ?> :
        </td>
        <td>
            <SELECT class="form-control input-medium" name="drpQuiz" id="drpQuiz" onchange='LoadSubjects()'>
                <?php echo $quiz_options ?>
            </SELECT>
        </td>
    </tr>
  
      <tr>
        <td valign="top" style='width:200px'>
            <?php echo DIFF_LEVEL ?> :
        </td>
        <td>
            <SELECT class="form-control input-medium" name="drpDiffLevel" id="drpDiffLevel">
                <?php echo $diff_level_opts ?>
            </SELECT>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <?php echo QUESTION ?> :
        </td>
        <td >
            <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10" ><?php echo $txtQsts ?></textarea>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <?php echo VIDEO_FILE ?> :
        </td>
        <td valign="top">
            <input name="videoFile" type="file">&nbsp;(FLV,MP4,F4V) <?php if($id!="-1" && $video_file_name!="") { ?> <table border="0"><tr><td valign="middle"><input type="checkbox" id="chkRemove" name="chkRemove" /></td><td valign="bottom" for="chkRemove"> Remove video</td></tr></table><br> <?php  } ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo POINT ?> :
        </td>
        <td>
            <input class="form-control input-small" style="width:100px" type="text" id="txtPoint" value="<?php echo util::GetData("txtPoint") ?>" name="txtPoint">
        </td>
    </tr>
       <tr>
        <td>
            <?php echo PENALTY_POINT ?> :
        </td>
        <td>
            <input class="form-control input-small" style="width:100px" type="text" id="txtPenaltyPoint" value="<?php echo util::GetData("txtPenaltyPoint") ?>" name="txtPenaltyPoint">
        </td>
    </tr>

    
    <tr>
        <td>
            <?php echo SELECT_TEMP ?> :
        </td>
        <td>
            <select class="form-control" id="drpTemplate" name="drpTemplate" style="width:100%" onchange="ChangeTemplate()">
                <?php echo $temp_options ?>
            </select>
        </td>
    </tr>
</table>
<br />
<table style="width:950px" border="0">
    <tr style="display:none" id="trMulti">
        <td align="center">
            <table class="desc_text" id="tblMulti" border="0">               
                <tr>
                    <td align="right"><?php echo HEADER_TEXT ?> (<?php echo CAN_BE_EMPTY ?>)</td>
                    <td colspan="2">&nbsp;<input class="form-control" type="text" value="<?php echo util::GetData("txtGroupName") ?>"  name="txtMultiGroupName" id="txtMultiGrpName"></td>
                    <td>&nbsp;</td>
                  
                </tr>
                <tr>
                    <td colspan="4"><hr></td>
                </tr>
                <tr>                    
                    <td><?php echo ANSWER_VARIANTS ?>
                    </td>
                    
                      <td><?php echo ANSWER_DESCRIPTION ?>
                   </td>
                   <td class="desc_text"><?php echo ANSWER_POINT ?> </td>
                    <td class="desc_text"><?php echo CORRECT_ANSWER ?> </td>
                    
                </tr>
                 <tr style="display:none">
                    
                    <td>
                        <?php if($ANSWER_MODE=="EXTENDED") { ?><textarea  style="display:<?php echo $ext_ans_dipslay ?>"   name="chkeMulti0" id="chkeMulti0" > </textarea><?php } ?>
                         <?php if($ANSWER_MODE!="EXTENDED") { ?><input class="form-control" style="display:<?php echo $simple_ans_dipslay ?>" type="text" id="txtMulti0" name="txtMulti0" /><?php } ?>
                    </td>
                     <td>
                            <input class="form-control" type="text" id="txtDesc0" name="txtMultiDesc0" value="0">
                       </td>
                         <td>
                            <input class="form-control input-small" type="text" id="txtMAPoint0" name="txtMAPoint0" value="0">
                       </td>
                    <td>&nbsp;&nbsp;<input class="els" type="checkbox" id="chkMulti0" name="chkMulti0" ></td>
                </tr>
                <?php for($i=1;$i<=$answers_count;$i++) { ?>
                <tr>
                    
                    <td>
                        <textarea   style="display:<?php echo $ext_ans_dipslay ?>"  class="ckeditor" name="chkeMulti<?php echo $i ?>" id="chkeMulti<?php echo $i ?>" ><?php echo util::GetData("txtChoise$i") ?></textarea>
                        <input class="form-control" style="display:<?php echo $simple_ans_dipslay ?>" type="text" id="txtMulti<?php echo $i ?>" value="<?php echo util::GetData("txtChoise$i") ?>" name="txtMulti<?php echo $i ?>"></td>
                    <td >
                            <input class="form-control"  type="text" id="txtDesc<?php echo $i ?>" name="txtMultiDesc<?php echo $i ?>" value="<?php echo util::GetData("txtDesc$i") ?>">
                       </td>
                        <td>
                            <input class="form-control input-small"  type="text"  id="txtAPoint<?php echo $i ?>" name="txtMAPoint<?php echo $i ?>" value="<?php echo util::GetData("txtAPoint$i") ?>">
                       </td>
                       <td>&nbsp;&nbsp;<input class="els" <?php echo util::GetData("ans_selected$i") ?> type="checkbox" id="chkMulti<?php echo $i ?>" name="chkMulti<?php echo $i ?>" ></td>
                </tr>
                <?php } ?>
            </table>
            <table width="170px">
                <tr>

                    <td align="center"><input class="btn green" style="width:25px" type="button" value=" + " onclick="addRow('tblMulti','txtMulti')" />
                        <input class="btn green" style="width:25px" type="button" value=" - " onclick="deleteRow('tblMulti')" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="display:none" id="trOne" >
        <td align="center">
            <table id="tblOne" class="desc_text" >
                <tr>
                    <td align="right"><?php echo HEADER_TEXT ?> (<?php echo CAN_BE_EMPTY ?>):</td>
                    <td colspan="2"><input class="form-control" type="text" value="<?php echo util::GetData("txtGroupName") ?>" name="txtOneGroupName"></td>                    
                    <td>&nbsp;</td>
					
                </tr>
                 <tr>
                    <td colspan="4"><hr></td>
                </tr>
                <tr>

                    <td><?php echo ANSWER_VARIANTS ?>
                   </td>
                   <td><?php echo ANSWER_DESCRIPTION ?>
                   </td>
                   <td class="desc_text"><?php echo ANSWER_POINT ?> </td>
                    <td class="desc_text"><?php echo CORRECT_ANSWER ?> </td>
                </tr>
                 <tr style="display:none">
                    <td>                      
                        <?php if($ANSWER_MODE=="EXTENDED") { ?><textarea style="display:<?php echo $ext_ans_dipslay ?>"   name="chkeOne0" id="chkeOne0" > </textarea><?php } ?>
                        <?php if($ANSWER_MODE!="EXTENDED") { ?><input class="form-control" style="display:<?php echo $simple_ans_dipslay ?>" type="text" id="txtChoise0" name="txtOne0" /><?php } ?>
                    </td>
                       <td>
                            <input class="form-control" type="text" id="txtDesc0" name="txtOneDesc0" >
                       </td>
                        <td>
                            <input class="form-control input-small" type="text"  id="txtOAPoint0" name="txtOAPoint0" value="0">
                       </td>
                    <td>&nbsp;&nbsp;<input class="els" type="radio" name="rdOne" value="0"></td>
                </tr>
                <?php for($i=1;$i<=$answers_count;$i++) { ?>               
                <tr>
                    <td>                      
                        <textarea style="display:<?php echo $ext_ans_dipslay ?>"  class="ckeditor" name="chkeOne<?php echo $i ?>" id="chkeOne<?php echo $i ?>" ><?php echo util::GetData("txtChoise$i") ?></textarea>
                        <input class="form-control" style="display:<?php echo $simple_ans_dipslay ?>" type="text" id="txtChoise<?php echo $i ?>" name="txtOne<?php echo $i ?>" value="<?php echo util::GetData("txtChoise$i") ?>">
                    </td>
                       <td>
                            <input class="form-control" type="text" id="txtDesc<?php echo $i ?>" name="txtOneDesc<?php echo $i ?>" value="<?php echo util::GetData("txtDesc$i") ?>">
                       </td>
                        <td>
                            <input type="text" class="form-control input-small"  id="txtAPoint<?php echo $i ?>" name="txtOAPoint<?php echo $i ?>" value="<?php echo util::GetData("txtAPoint$i") ?>">
                       </td>
                    <td>&nbsp;&nbsp;<input class="els" <?php echo util::GetData("ans_selected$i") ?> type="radio" name="rdOne" value="<?php echo $i ?>"></td>
                </tr>
                <?php } ?>
            </table>
            <table width="170px">
                <tr>

                    <td align="center"><input class="btn green" style="width:25px" type="button" value=" + " onclick="addRow('tblOne','txtOne')" />
                        <input class="btn green" style="width:25px" type="button" value=" - " onclick="deleteRow('tblOne')" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="display:none" id="trArea">
        <td align="center">
            <table id="tblArea" class="desc_text">
                 <tr>
                     <td valign="top" align="right">
                         <?php echo HEADER_TEXT ?> (<?php echo CAN_BE_EMPTY ?>):
                     </td>
                    <td>
                        <input class="form-control" style="width:300px" type="text" value="<?php echo util::GetData("txtGroupName") ?>" name="txtAreaGroupName"></td>

                </tr>
                <tr>
                    <td valign="top" align="right">
                         <?php echo ENTER_CORRECT_ANSWER ?> (<?php echo CAN_BE_EMPTY ?>):
                     </td>
                    <td>
                        <textarea  class="form-control" style="width:300px;height:100px" name="txtArea1"><?php echo util::GetData("txtCrctAnswer1") ?></textarea>
                    <td>
                </tr>
            </table>
        </td>
    </tr>
        <tr style="display:none" id="trMultiText">
        <td align="center">
            <table id="tblMultiText" class="desc_text">
                <tr>
                    <td colspan="2" align="right"><?php echo HEADER_TEXT ?> (<?php echo CAN_BE_EMPTY ?>):</td>
                    <td colspan="2"><input class="form-control" type="text" value="<?php echo util::GetData("txtGroupName") ?>" name="txtMultiTextGroupName"></td>
                    
                    
                </tr>
               <tr>
                    <td colspan="4"><hr></td>
                </tr>
               <tr>
                    <td><?php echo ANSWER_VARIANTS ?>
                    </td>
                      <td><?php echo ANSWER_DESCRIPTION ?>
                   </td>    
                    <td><?php echo ANSWER_POINT ?>
                   </td> 
                    <td class="desc_text"><?php echo CORRECT_ANSWER ?> </td>
                </tr>
                <?php for($i=1;$i<=$answers_count;$i++) { ?>
                <tr>

                    <td>
                        <input class="form-control input-small" type="text" id="txtChoise<?php echo $i ?>" name="txtMultiText<?php echo $i ?>" value="<?php echo util::GetData("txtChoise$i") ?>"></td>
                     <td>
                            <input class="form-control input-small" type="text" id="txtDesc<?php echo $i ?>" name="txtMultiTextDesc<?php echo $i ?>" value="<?php echo util::GetData("txtDesc$i") ?>">
                       </td>
                         <td>
                            <input class="form-control input-small" type="text" class="input-small" id="txtAPoint<?php echo $i ?>" name="txtMTAPoint<?php echo $i ?>" value="<?php echo util::GetData("txtAPoint$i") ?>">
                       </td>
                    <td><input class="form-control input-small" type="text" id="txtText<?php echo $i ?>" name="txtMultiCrctAnswer<?php echo $i ?>" value="<?php echo util::GetData("txtCrctAnswer$i") ?>"></td>
                </tr>
                <?php } ?>
            </table>          
             <table width="320px">
                <tr>

                    <td align="center"><input class="btn green" style="width:25px"  type="button" value=" + " onclick="addRow('tblMultiText','txtMultiText')" />
                        <input class="btn green" style="width:25px" type="button"   value=" - " onclick="deleteRow('tblMultiText')" />
                    </td>
                </tr>
            </table>
              <table style="display:none">
                <tr>
                    <td><input type="checkbox" id="chkAllowNumbers" name="chkAllowNumbers" /><label id="lbl1" for="chkAllowNumbers">Allow users to enter only numbers</label></td>
                </tr><tr>
                    <td><input type="checkbox" id="chkDontCalc" name="chkDontCalc" /><label id="lbl1" for="chkDontCalc">Do not calculate results of this question</label></td>
                </tr>
            </table>
            
        </td>
    </tr>
    <tr style="display:<?php echo $ext_link_display ?>">
        <td align='center'><br /><br /><div align='center'><a href="<?php echo util::GetCurrentPage(); ?>&f=2" class='btn btn-primary'><?php echo USE_IMG_BSD_ANS ?></a></div></td>
    </tr>
   

    

</table>


 </div>
    <div class="tab-pane" id="diff_sett">
         <table>
        
            <tr>
                      <td >
                    <br />
                </td> 
            </tr>
            <tr>
                <td style="width:300px">
                    <?php echo L_DIFF_LEVELS_BY_COURCES ?>
                </td>    
           
                <td>
                    <table>
                        <tr>
                            <td style="width:100px">
                                <?php echo L_COURSE ?> : 
                            </td>
                            <td>
                                <?php echo DIFF_LEVEL ?> :
                            </td>
                        </tr>
                        <?php for($i=1;$i<=intval(MAX_COURSE);$i++) { ?>
                        <tr>
                            <td>
                                <?php echo $i ?>
                            </td>
                            <td>
                                <select name="drpDiff<?php echo $i ?>" class='form-control'>
                                    <?php echo  webcontrols::GetArrayOptions($diff_level_res, "id", "level_name", get_selected_diff($i), true, "", $QST_LEVEL); ?>
                                </select>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </td>    
            </tr>
        </table>
    </div>
     <div class="tab-pane" id="dyn_sett">
        
    </div>
    <div class="tab-pane" id="orange">
  <table class="desc_text" style="width:950px">   
            <tr style="display:<?php echo $quiz_display ?>">
            <td valign="top">
                <?php echo SUBJECT ?> :
            </td>
            <td><div id='divSubjects'><?php echo L_QUIZ_VAL ?></div>             
            </td>
        </tr>
          <tr>
                <td valign="top"  style="width:200px">
                    <?php echo L_COMMENTS ?> : 
                </td>    
                <td>
                    <textarea  class="form-control" type="text" id="txtComments" name="txtComments" style="width:100%;height:70px"   ><?php echo util::GetData("txtComments") ?></textarea>
                </td>    
            </tr>
    
            <tr>
        <td valign="top" style='width:200px'>
            <?php echo HEADER_TEXT ?> :
        </td>
        <td>
            <textarea class="form-control" style="width:100%;height:70px" id="txtHeader" name="txtHeader"><?php echo util::GetData("txtHeader") ?></textarea>
        </td>
    </tr>
    <tr>
        <td valign="top">
            <?php echo FOOTER_TEXT ?> :
        </td>
        <td>
            <textarea class="form-control" style="width:100%;height:70px" id="txtFooter" name="txtFooter"><?php echo util::GetData("txtFooter") ?></textarea>
        </td>
    </tr>
    
     <tr>
        <td valign="top">
            <?php echo SUCCESS_MSG ?> :
        </td>
        <td>
            <textarea class="form-control" style="width:100%;height:70px" id="txtSuccessMsg" name="txtSuccessMsg"><?php echo util::GetData("txtSuccessMsg") ?></textarea>
        </td>
    </tr>
    
        <tr style="display:<?php echo $display_partly ?>">
        <td  valign="top" class="ttip_t" title="<?php echo PSUCCESS_MSG_TITLE ?> ">
            <?php echo PSUCCESS_MSG ?> :
        </td>
        <td>
            <textarea class="form-control" style="width:100%;height:70px" id="txtPSuccessMsg" name="txtPSuccessMsg"><?php echo util::GetData("txtPSuccessMsg") ?></textarea>
        </td>
    </tr>
    
       <tr>
        <td valign="top">
            <?php echo UNSUCCESS_MSG ?> :
        </td>
        <td>
            <textarea class="form-control" style="width:100%;height:70px" id="txtUnSuccessMsg" name="txtUnSuccessMsg"><?php echo util::GetData("txtUnSuccessMsg") ?></textarea>
        </td>
    </tr>
        </table>
    </div>

</div>
</div>



    <br>
     <hr />
     <br>
     <table style="width:950px">
         <tr>
        <td align="center">
            <input class="btn green"  type="submit" id="btnSave" name="btnSave" value="<?php echo SAVE ?>" style="width:150px" onclick="return validate();" />
            <input class="btn green" type="button" id="btnCancel" name="btnCancel" value="<?php echo CANCEL ?>" style="width:150px" onclick="javascript:window.history.back()" />
        </td>
    </tr>
     </table>
<script language=javascript>
	var editor = CKEDITOR.replace('editor1',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash'

        });
</script>
<script language=javascript>
    function make_editor(editor_name)
    {
        document.getElementById(editor_name).className = "ckeditor";
	var editor = CKEDITOR.replace(editor_name,
      {

    toolbar :
            [
                { name: 'document', items : [ 'Preview','Source' ] },
              
                { name: 'tools', items : [ 'Maximize','-','Image' ] }

            ],
     
        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash',
        
        width: "300px",
        height: "100px"

        });
    }
</script>
    <SCRIPT language="javascript">
        ChangeTemplate();

        //var c_multi = 4;

        var counters = new Array();
        var answer_count = <?php echo $answers_count ?>;
        counters["tblMulti"] = answer_count;
        counters["tblOne"] = answer_count;
        counters["tblArea"] = answer_count;
        counters["tblMultiText"] = answer_count;

        function addRow(tableID, textboxID ) {

            counters[tableID]++;            			
                        
            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var colCount = table.rows[2].cells.length;
            
            var regx_val = tableID=="tblMultiText" ? 1 : 0;
            
            for(var i=0; i<colCount; i++) {

                var newcell = row.insertCell(i);

                newcell.innerHTML = table.rows[3].cells[i].innerHTML.replace(new RegExp(regx_val,'g'),counters[tableID]);
                //alert(newcell.childNodes[1].type);                
				//alert(newcell.innerHTML);       				
                switch(newcell.childNodes[1].type) {    
            
                    case "text":                                        
                            newcell.childNodes[1].value = "";
                            var txtname=newcell.childNodes[1].name;
                            var rowlen = counters[tableID].toString().length;
                            var newname=txtname.substr(0,txtname.length-rowlen)+counters[tableID];
                            newcell.childNodes[1].id=newname;
                            newcell.childNodes[1].name=newname;                                
                            break;
                    case "textarea":                                   
                            newcell.childNodes[1].value = "";                            
                            var txtname=newcell.childNodes[1].name;
                            var rowlen = counters[tableID].toString().length;
                            var newname=txtname.substr(0,txtname.length-rowlen)+counters[tableID];
                            newcell.childNodes[1].id=newname;
                            newcell.childNodes[1].name=newname;                                                                              
                            make_editor(newname);                            
                            break;                            
                    case "checkbox":					
                            newcell.childNodes[1].checked = false;
                            newcell.childNodes[1].id="chkMulti"+counters[tableID];
                            newcell.childNodes[1].name="chkMulti"+counters[tableID];
                            break;
                    case "select-one":
                            newcell.childNodes[1].selectedIndex = 0;
                            newcell.childNodes[1].value=counters[tableID];
                            break;
                    case "radio":
                            newcell.childNodes[1].selectedIndex = 0;
                            newcell.childNodes[1].value=counters[tableID];
                            break;        
                    
                }
            }
            
        }

        function deleteRow(tableID) {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;
            if(rowCount==4)
                {
                    alert('<?php echo CANNOT_DELETE_LAST ?>');
                    return;
                }
            table.deleteRow(rowCount-1);
            counters[tableID]--;
        }

        function ChangeTemplate()
        {
            DisableAllTemplates();
            var val = document.getElementById('drpTemplate').options[document.getElementById('drpTemplate').selectedIndex].value;
            
            if(val ==0)
            {
                document.getElementById('trMulti').style.display="";
            }
            else if(val==1)
            {
                  document.getElementById('trOne').style.display="";
            }
            else if(val==3)
            {
                  document.getElementById('trArea').style.display="";
            }
            else if(val==4)
            {
                  document.getElementById('trMultiText').style.display="";
            }
        }

        function DisableAllTemplates()
        {
            document.getElementById('trMulti').style.display="none";
            document.getElementById('trOne').style.display="none";
            document.getElementById('trArea').style.display="none";
            document.getElementById('trMultiText').style.display="none";
        }

    </SCRIPT>
  
    <script language="javascript" >
    MakeEditors();
    function MakeEditors()
    {           
        if("<?php echo $ANSWER_MODE ?>"=="EXTENDED")
        {
            for(var i =1;i<=<?php echo $answers_count?>;i++)
            {
                
                make_editor("chkeOne"+i);
                make_editor("chkeMulti"+i);
            
            }
        }
    }
    LoadSubjects();                        
    </script>
    
</form>

