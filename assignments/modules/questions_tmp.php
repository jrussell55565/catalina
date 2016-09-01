<?php if(!isset($RUN)) { exit(); } ?>


<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    <?php if(access::has("add_qst")) { ?>
    <a class="btn btn-primary" href="?module=add_question<?php if($a_id!="-1") echo "&a_id=$a_id" ?>&quiz_id=<?php echo $quiz_id ?>"><?php echo NEW_QUESTION ?></a>
    <a class="btn btn-primary" href="?module=add_question&f=2<?php if($a_id!="-1") echo "&a_id=$a_id" ?>&quiz_id=<?php echo $quiz_id ?>"><?php echo NEW_QUESTION ?> (<?php echo L_IMAGE_BASED ?>)</a>
    <?php } ?>
    <?php if($a_id!="-1") { ?>
    <br /><br /><a href="?module=view_assignment&asg_id=<?php echo $a_id ?>"><?php echo BACK_TO_ASG ?></a>
    <?php } else { ?>
    <br /><br /><br /><br /><a href="?module=quizzes"><?php echo BACK ?></a><br /><br />
    <?php } ?>
    <table id="test_div" style="display: none;background-color:#F9DD93"  width="<?php echo $mobile ? "95%" : "610px" ?>">
        <tr>
            <td colspan=2 align=right><a href="#" border="0" onclick="close_window()"><img src="style/i/close_button.gif" /></a></td>
        </tr>
        <tr>
              <td>
                &nbsp;&nbsp;
            </td>
            <td id="test_hr"  >

            </td>
        </tr>
    </table>    
     <div id="templateDiv" style="display: none;background-color:#F9DD93">
        <table width="610px" bgcolor="#767F86" align="center" border="0">
            <tr>
                <td align="center">
                    <font color="white" face=tahoma size="3"><b><?php echo PLEASE_WAIT ?></b></font>
                </td>
            </tr>
        </table>
    </div>
