<?php if(!isset($RUN)) { exit(); } ?>

<script language = javascript >
    counters['tblFiles'] = 1;
    function SaveComments()
    {                
        var has_attachments = false;
        for(var i=1;i<counters['tblFiles']+1;i++)
        {
            if($("#fileFiles"+i).val()!="")
            {
                has_attachments = true;
                break;
            }
        }
        if(!has_attachments)
        {
            var comments = $("#txtComments").val();        
            var chkSendCopy = $("#chkSendCopy").is(':checked');
            
            $.post("<?php echo $url ?>", {  ajax: "yes", add_reply : "yes", txtComments : comments , chkSendCopy : chkSendCopy  },
             function(data){                          
                 document.getElementById('div_grid').innerHTML=data;
            });
        }
        else
        {
            document.getElementById('formR').submit();
        }
    }
</script>

<table border="0" style="<?php echo util::get_width(800, 300) ?>" >
      <tr>
        <td style="<?php echo util::get_width(200, 100) ?>" class="simple_header"><?php echo $D_CONTROLS['Category'] ?> : </td><td class="simple_header"><?php echo $row["CatName"] ?></td>
    </tr>
    <tr>
        <td class="simple_header"><?php echo $D_CONTROLS['Status'] ?> : </td><td class="simple_header"><?php echo $STATUSES[$row["StatusName"]] ?></td>
    </tr>
    <tr>
        <td class="simple_header"><?php echo $D_CONTROLS['Priority'] ?> : </td><td class="simple_header"><?php echo $PRIORITIES[$row["PriorityName"]] ?></td>
    </tr>
    <tr>
        <td class="simple_header"><?php echo $D_CONTROLS['Subject'] ?> : </td><td class="simple_header"><?php echo htmlspecialchars($row["t_subject"]) ?></td>
    </tr>     
    <tr>
        <td valign="top" class="simple_header"><?php echo $D_CONTROLS['Body'] ?> : </td><td class="simple_header"><?php echo $tbody ?><?php echo $ticket_files_list ?></td>
    </tr>

</table>

<hr />

<div style="width:100%" id="div_grid"><?php echo $grid_html ?></div>

<hr />

<?php if(access::has("reply_ticket") && $row['status_system_code']!="100") { ?>
<form id="formR" name="formR" method="post" enctype='multipart/form-data' >
<table>
    <tr>
         <td><textarea class="comments_box" ID="txtComments" name="txtComments"></textarea></td>
    </tr>  
    <tr>
        <td>
            
            <table cellspacing=1 cellpadding=1 id='tblFiles' class='desc_text'><tr><td><input type=file name=fileFiles1 id=fileFiles1 /></td></tr></table>
                            &nbsp;<input style='width:25px' type='button' value=' + ' onclick="addRow('tblFiles','Files')" />
                        <input style='width:25px' type='button' value=' - ' onclick="deleteRow('tblFiles')" />
            
        </td>
    </tr>
       <tr>
         <td class="desc_text"><br /><input type="checkbox" checked name="chkSendCopy" id="chkSendCopy" />&nbsp;<?php echo SEND_COPY_BY_MAIL ?></td>
    </tr>
      <tr>
          <td>
              <br />
              <input type="button" class="btn green" value="<?php echo SUBMIT_COMMENTS ?>" onclick="SaveComments()" /><input type=hidden name="add_reply" />
          &nbsp;<input type="button" class="btn green" value="<?php echo CANCEL ?>" onclick="javascript:history.back(1)" /><input type=hidden name="add_reply" />
          </td>
    </tr>
</table>
    <br />
    <br />
    <br />
</form>
<?php } ?>