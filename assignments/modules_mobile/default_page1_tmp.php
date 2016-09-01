<?php if(!isset($RUN)) { exit(); } ?>

<table style="width:90%">
    <tr>
         <td>
            <?php echo USERS_COUNT ?>
        </td>
        <td>
            <strong> <?php echo $users_count ?></strong>
        </td>
       
    </tr>    
    <tr>
        <td>
            <?php echo QUESTIONS_COUNT ?>
        </td>
        <td>
            <strong><?php echo $qst_count ?></strong>
        </td>
    
    </tr>  
       <tr>
        <td>
            <?php echo EXAMS_COUNT ?>
        </td>
        <td>
            <strong><?php echo $exams_count ?></strong>
        </td>
    
    </tr>  
       <tr>
        <td>
            <?php echo SURVEYS_COUNT ?>
        </td>
        <td>
            <strong><?php echo $surveys_count ?></strong>
        </td>
    
    </tr>  
</table>
<br />
&nbsp;<?php echo LAST_REG_USES ?>
<div>
                            <div id="div_grid"><?php echo $grid_html ?></div>
                        </div>

<br>

            <table style="width:98%" border="0" align="center">
                <tr>
                    <td>
                        <?php echo USERS_COUNT_BY_COUNTRY ?>
                    </td>
                </tr>
                <tr>
                    <td>
                    <?php
                        for($i=0;$i<sizeof($country_res);$i++)
                        {                  
                            $country_row = $country_res[$i];
                            ?>
                        <table border="1" class="desc_text" style="width:100%">
                            <tr>
                                <td style="width:25%">
                                    <?php echo $country_row['country_name']; ?>
                                </td>
                                <td style="width:50%">
                                    <table style="width:100%;border-width:0px" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td bgcolor="red" style="width:<?php echo get_percent($country_row['user_count'],sizeof($country_res))  ?>%">
                                               
                                            </td>
                                            <td>
                                                &nbsp;
                                            </td>
                                        </tr>
                                     </table>
                                </td>
                                <td width="25%">
                                    <?php echo $country_row['user_count']." (".$percent."%)"; ?>
                                </td>
                            </tr>
                        </table>                        
                            <?php                            
                        }
                    ?>
                    </td>
                </tr>
            </table>