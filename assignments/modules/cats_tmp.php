<?php if(!isset($RUN)) { exit(); } ?>
<script language="javascript">
    function AddNewCat()
    {
        var adding = "adding";
        var T= document.getElementById('hdnT').value;
        if(T!="add") adding="editing";
       // alert(T);
        var cat_name = document.getElementById('txtName').value;
        
        if(cat_name=="") return;
        
        document.getElementById('btnCancel').style.display="none";
         $.post("index.php?module=cats", {  ajax: "yes", add : adding, name : cat_name, hdnT : T },
         function(data){
             document.getElementById('div_grid').innerHTML=data;
             document.getElementById('hdnT').value='add';
             document.getElementById('btnAdd').value="<?php echo ADD; ?>";
             document.getElementById('txtName').value='';
        });
    }
    function EditCat(cat_name,cat_id)
    {
        document.getElementById('txtName').value=cat_name;
        document.getElementById('hdnT').value=cat_id;
        document.getElementById('btnAdd').value="<?php echo SAVE; ?>";
        document.getElementById('btnCancel').style.display="";
       // jsProcessCommand(cat_id,"edit","index.php?module=cats","div_grid");
    }
    
    function CancelEditing()
    {
        document.getElementById('btnCancel').style.display="none";
        document.getElementById('hdnT').value='add';
        document.getElementById('btnAdd').value="<?php echo ADD; ?>";
        document.getElementById('txtName').value='';
    }
</script>

    <div id="div_grid"><?php echo $grid_html ?></div>
    
    <hr />

    <?php if(access::has("add_cat") || access::has("edit_cat")) { ?>
    <table>
        <tr>
            <td >
                <?php echo CAT_NAME ?> : 
                <table>
                    <tr>
                        <td>
                             <input class="form-control" type="text" id="txtName" name="txtName" />
                        </td>
                        <td valign="top">
                             <input type="button" class="btn green" id="btnAdd" onclick ="AddNewCat()" value ="<?php echo ADD ;?>">
                             <input  type="button" class="btn green" style="display:none" id="btnCancel" onclick ="CancelEditing()" value ="<?php echo CANCEL ;?>">
                        </td>
                    </tr>
                </table>
               
               
                <input type="hidden" id="hdnT" name="hdnT"  value="add">
            </td>
        </tr>
    </table>
    <?php } ?>

