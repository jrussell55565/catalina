<?php if(!isset($RUN)) { exit(); } ?>
<div  id="divTxt">
<script language="javascript">
    function SaveEmail()
    {
        var email = $("#txtEmail").val();
        var page = 'index.php?module=user_details&id=<?php echo $id ?>';
        $.post(page, {  ajax: "yes" , email : email,savemail:"yes"},
         function(data){                            
             if(data.mtype=="1" || data.mtype==1)
             {      
                 document.getElementById('spMsg').innerHTML=data.msg;
             }            
        }, "json");
        
    }
</script>

<?php echo EMAIL ?> : <br />
<input class='form-control input-medium' type="text" id="txtEmail" name="txtEmail" value="<?php echo $p_account ?>" />&nbsp;<font color="red"><span id="spMsg"></span></font><br />
<input class='btn green' type="button" id="btnAdd" name="btnAdd" value="<?php echo SAVE ?>" class="btn" onclick="SaveEmail()" />

</div>