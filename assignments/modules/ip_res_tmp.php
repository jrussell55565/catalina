<?php if(!isset($RUN)) { exit(); } ?>

<script language='javascript'>

function add_user_ip()
{
    var ip = $("#txtIP").val();
    var itype = $("#drpType").val();
    
    if(ip=="") return;
          
         $.post("<?php echo $url ?>", {  ajax: "yes", addip : "yes", ip : ip, itype : itype },
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
                <?php echo TYPE ?>
            </td>
            <td>
                
            </td>
        </tr>
         <tr>
            <td>
                <input class='form-control' type="text" id="txtIP" />
            </td>
            <td>
                <select class='form-control' id='drpType'>
                    <option value='1'>
                        <?php echo ALLOW ?>
                    </option>
                          <option value='0'>
                        <?php echo DENY ?>
                    </option>
                </select>
            </td>
            <td valign="top">
                <input class="btn green" type="button" class="btn" value='<?php echo ADD ?>' id='btnadd' onclick='add_user_ip()' />
            </td>
        </tr>
    </table>       
    
    <br />