<?php if(!isset($RUN)) { exit(); } ?>

<script language="javascript">
    function ProcessCommand(command)
    {        
        if(command=="delete")
        {
            var confirmed = confirm('<?php echo ARE_YOU_SURE ?>');
            if(confirmed == false) return;
        }
        var view = $("#drpTViews").val();
        var myarr = grd_get_checkboxes(document.getElementById("form1"),"chk_tick","");       
        var tech = $("#drpTech").val();
         $.post("<?php echo $url ?>", {  ajax: "yes", pcommand:"yes" , chkboxes : myarr , command : command , tech : tech },
         function(data){                
             document.getElementById('div_grid').innerHTML=data;
        });
    }
    
    function drpTViews_onchange()
    {
         var view = $("#drpTViews").val();
         $.post("<?php echo $url ?>", {  ajax: "yes", view_change:"yes" ,  view : view },
         function(data){                
             document.getElementById('div_grid').innerHTML=data;
        });
    }
    
</script>
<div id="div_search"><?php echo $search_html ?></div>
<hr />
<table style="width:100%">
    <tr>
        <td>
             <select class='form-control' id="drpTViews" name="drpTViews" onchange = "drpTViews_onchange()">
                <?php echo $ticket_view_options ?>
            </select>
        </td>
        <td align="right" style="display: <?php echo $hide_mobile ?>">
            <table>
                <tr><td valign="bottom">
            <?php if(access::has("assign_ticket")) { ?>
            <select class='form-control' class="btn4" id="drpTech" style="width:150px">
                <?php echo $tech_options ?>
            </select>
                        </td><td valign="top">&nbsp;
            <input  class="btn green" type="button" value="<?php echo ASSIGN ?>" id="btnAssign" onclick="ProcessCommand('assign')" /><?php } ?>
                        </td><td valign="top">
            <?php if(access::has("close_ticket")) { ?>&nbsp;<input class="btn green" class="btn4" type="button" value="<?php echo CLOSE ?>" id="btnClose"  onclick="ProcessCommand('close')" /><?php } ?>
            <?php if(access::has("unread_ticket")) { ?><input class="btn green" class="btn4" type="button" value="<?php echo MARK_AS_UNREAD ?>" id="btnUnread" onclick="ProcessCommand('unread')"  /><?php } ?>
            <?php if(access::has("delete_ticket")) { ?><input class="btn green" class="btn4" type="button" value="<?php echo DELETE ?>" id="btnDelete" onclick="ProcessCommand('delete')" /><?php } ?>
            </td></tr>
            </table>
        </td>
    </tr>
</table>    

 
<form id="form1" name="form1">
<div id="div_grid"><?php echo $grid_html ?></div>
</form>
<br>

   
    <hr />
