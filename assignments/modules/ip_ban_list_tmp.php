<?php if(!isset($RUN)) { exit(); } ?>
<?php if(!isset($RUN)) { exit(); } ?>

<script language='javascript'>

function add_user_ip()
{
    var ip = $("#txtIP").val();
    var comments = $("#txtComments").val();
    
    if(ip=="") return;
          
         $.post("<?php echo $url ?>", {  ajax: "yes", addip : "yes", ip : ip, comments : comments },
         function(data){
             document.getElementById('div_grid').innerHTML=data;          
        });
}

</script>

<div id="div_search"><?php echo $search_html ?></div>
<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    
    <table>
        <tr>
            <td>
                <?php echo IP_ADDRESS ?>
            </td>
            <td>
                <?php echo COMMENTS ?>
            </td>
            <td>
                
            </td>
        </tr>
         <tr>
            <td>
                <input class='form-control' type="text" id="txtIP" />
            </td>
            <td>
                <input class='form-control' type="text" id="txtComments" />
            </td>
            <td valign="top">
                <input type="button" class="btn green" value='<?php echo ADD ?>' id='btnadd' onclick='add_user_ip()' />
            </td>
        </tr>
    </table>       
    
    <br />
    