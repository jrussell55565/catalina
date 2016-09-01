<?php if(!isset($RUN)) { exit(); } ?>
<form method="post" name="form1" enctype="multipart/form-data">
<table style="width:500px" >
    <tr>
        <td colspan="2" align="center"><?php echo $img ?></td>
    </tr>
     <tr style="display:<?php echo $allow_change ?>">
        <td colspan="2" align="center"><input class="ttip_b" title="<?php echo $restriction ?>" name="userphoto" accept="image/jpeg" type="file"> </td></td>
    </tr>
      <tr>
        <td colspan="2" align="center"><br /></td>
    </tr>
     <tr>
        <td><h5 class="heading"><?php echo LOGIN ?></h5></td>
         <td><h5 class="heading"><?php echo $login ?></h5></td>
    </tr>
    <tr>
        <td><h5 class="heading"><?php echo USER_NAME ?></h5></td>
         <td><h5 class="heading"><?php echo $name ?></h5></td>
    </tr>
     <tr>
        <td><h5 class="heading"><?php echo USER_SURNAME ?></h5></td>
         <td><h5 class="heading"><?php echo $surname ?></h5></td>
    </tr>
       <tr>
        <td><h5 class="heading"><?php echo EMAIL ?></h5></td>
        <td><h5 class="heading"><?php echo $email ?></h5></td>
    </tr>
    <tr>
        <td><h5 class="heading"><?php echo BRANCH ?></h5></td>
        <td><h5 class="heading"><?php echo $branch_name ?></h5></td>
    </tr>
    <tr style="display:<?php echo $allow_change ?>">
        <td>&nbsp;</td>
        <td><br/><input type="submit" class="btn green" name="btnSave" style="width:200px" title="" class="btn" value="<?php echo SAVE ?>" ></td>
    </tr>
    
    
</table>
    </form>