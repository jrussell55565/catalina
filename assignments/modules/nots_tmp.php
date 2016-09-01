<script language="javascript">
    function add_new_message()
    {
        var subject = document.getElementById('txtSubject').value;
        var body = document.getElementById('txtBody').value;
         $.post("index.php?module=nots&id=<?php echo $id ?>", {  ajax: "yes", add:1, subject : subject, body : body },
         function(data){
             document.getElementById('div_grid').innerHTML=data;             
        });
    }
</script>

<div id="div_grid"><?php echo $grid_html ?></div>
<br />
<hr />
<br />
<?php if(access::has("add_not")) { ?>
<table>
    <tr>
        <td></td>
    </tr>
     <tr>
        <td>
            <table>
                <tr>
                    <td>
                        <?php echo SUBJECT ?> : 
                    </td>
                     <td>
                         <input type="text" name="txtSubject" id="txtSubject" style="width:90%;"  />
                    </td>
                </tr>
                 <tr>
                    <td>
                         <?php echo BODY ?> : 
                    </td>
                     <td>                         
                        <textarea id="txtBody" name="txtBody" style="width:90%; height: 250px" ></textarea>
                    </td>
                </tr>
                 <tr>
                    <td>
                        &nbsp;
                    </td>
                     <td>
                        <input type="button" value="<?php echo SEND ?>" onclick="add_new_message()" id="btnSend" name="btnSend"  > &nbsp; <font color=red><?php echo SendToAll ?></font>
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                     <td>
                        &nbsp;
                    </td>
                </tr>
            </table>
            
            
        </td>
    </tr>
</table>
<?php } ?>
<br>
<a href="index.php?module=view_assignment&asg_id=<?php echo $id ?>"><?php echo BACK ?></a>