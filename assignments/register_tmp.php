<?php if(!isset($RUN)) { exit(); } ?>
<!DOCTYPE html>
<html lang="en" class="login_page">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta content="" name="description"/>
        <meta content="" name="author"/>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="assets/admin/pages/css/login.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME STYLES -->
        <link href="assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
        <link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
        <link href="assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css" id="style_color"/>
        <link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
         <title><?php echo $PAGE_TITLE ?></title>
   
   
    
        <!-- tooltips-->
            <link rel="stylesheet" href="lib2/qtip2/jquery.qtip.min.css" />

        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
			

	
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />
		

        <!--[if lte IE 8]>
            <script src="js/ie/html5.js"></script>
			<script src="js/ie/respond.min.js"></script>
        <![endif]-->
		
    </head>
    <body class="login">
        
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<script language="javascript">

function checkform()
{       
	document.form1.btnRegister.disabled=true;                 
	var user_name= document.getElementById('txtLogin').value;
	var email= document.getElementById('txtEmail').value;
        var captcha = document.getElementById('txtCaptcha').value;
       // alert(user_name);
        var status=validate();
        if(status)
        {
             $.post("register.php", { login_to_check: user_name,email: email, captcha:captcha, ajax: "yes" },
             function(data){
                 if(data=="0" || data==0)
                 {
                     document.forms["form1"].submit();
                 }
                 else
                 {
                    alert(data);
                    document.form1.btnRegister.disabled=false;
                 }

            });
        }
        else
        {
            document.form1.btnRegister.disabled=false;
        } 
}

</script>

		<div class="content">
					
                    <form class="register-form" id='form1' name='form1' method="post"  style="display:<?php echo $step1_display ?>">
                        <div  style="display:<?php echo $step1_display ?>">
		<h3><?php echo L_SIGN_UP ?></h3>
		<p class="hint">
			 <?php echo SIGN_UP_TO ?> <?php echo $SYSTEM_NAME ?>
		</p>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo R_NAME ?></label>
			<input maxlength=25 type=text name=txtName title="<?php echo R_NAME ?>" class="form-control placeholder-no-fix" id=txtName placeholder="<?php echo R_NAME ?>"  />
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9"><?php echo R_SURNAME ?></label>
			<input maxlength=25 type=text name=txtSurname id=txtSurname placeholder="<?php echo R_SURNAME ?>" title="<?php echo R_SURNAME ?>" class="form-control placeholder-no-fix" />
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo R_LOGIN ?></label>
			<input maxlength=20 type=text name=txtLogin id=txtLogin placeholder="<?php echo R_LOGIN ?>" title="<?php echo R_LOGIN ?>" class="form-control placeholder-no-fix" />
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo R_PASSWORD ?></label>
			<input type="text" maxlength=20 name=txtPass id=txtPass placeholder="<?php echo R_PASSWORD ?>" title="<?php echo R_PASSWORD ?>" class="form-control placeholder-no-fix" />
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo COUNTRY ?></label>
                        <select id="drpCountries" name="drpCountries" class="form-control placeholder-no-fix" />
                            <?php echo $country_options ?>
                        </select>  
		</div>		
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo R_ADDRESS ?></label>
			<input maxlength=25 type=text name=txtAddr  placeholder="<?php echo R_ADDRESS ?>" title="<?php echo R_ADDRESS ?>" class="form-control placeholder-no-fix" />
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo R_PHONE ?></label>
			<input maxlength=25 type=text name=txtPhone  placeholder="<?php echo R_PHONE ?>"  title="<?php echo R_PHONE ?>" class="form-control placeholder-no-fix" />
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo EMAIL ?></label>
			<input type="text" id="txtEmail" name="txtEmail" placeholder="<?php echo EMAIL ?>"  title="<?php echo EMAIL ?>" class="form-control placeholder-no-fix" />
		</div>
                <div class="form-group">			
			<img src ="lib/captcha.php" />&nbsp;<img src="style/i/arrow_right.jpg" />&nbsp;<input type="text" id="txtCaptcha" name="txtCaptcha"  placeholder="<?php echo ENTER_NUMBERS ?>"  class="form-control input-small inline placeholder-no-fix" />
		</div>
		<div class="form-group margin-top-20 margin-bottom-20">
			<?php echo T_ACCEPT ?> <a data-toggle="modal" data-target="#myModal"><?php echo T_AND_S ?></a>.
		</div>
		<div class="form-actions">
			<button type="button" id="register-back-btn" class="btn btn-default" onclick="javascript:window.location.href='login.php'"><?php echo CANCEL ?></button>
			<button type="button" onclick="checkform()" id="btnRegister" name='btnRegister' class="btn btn-success uppercase pull-right"><?php echo L_SIGN_UP ?></button>
                                                
		</div>
                </div>
	</form>
                    

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo T_AND_C ?></h4>
      </div>
      <div class="modal-body">
       <?php echo T_AND_C_TEXT ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo CLOSE ?></button>        
      </div>
    </div>
  </div>
</div>              
                    
<div style="display:<?php echo $step2_display ?>"> <font color='red'><?php echo SIGN_UP_TO ?> <?php echo $SYSTEM_NAME ?></font></div>
				<hr />
                            
				<div class="alert alert-login">
					<?php echo $msg ?>
				</div>
                            </div> <br>                    
                    
                    
			
                    
		</div>

                      

                        
                        
         <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="assets/admin/pages/scripts/login.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {     
Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Login.init();
Demo.init();
});
</script>                 
         
    </body>
</html>
