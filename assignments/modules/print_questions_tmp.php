<?php if(!isset($RUN)) { exit(); } ?>


<table width="90%" >
    <tr>
        <td>
 
        </td>   
    </tr>    
</table>

<?php
while($row = db::fetch($asg_res))
{
    ?>    
    <div class="onepage" >      
   <?php
    echo get_question($row);
    ?>   
    </div>        
    <?php
}
?>


</span>
