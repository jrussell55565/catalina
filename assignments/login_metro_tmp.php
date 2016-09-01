<?php if(!isset($RUN)) { exit(); } ?>
<!DOCTYPE html>
<html lang="en"  <?php echo $fb_files ?>>
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
        <script language ="javascript" src="util.js?ra=<?php echo rand() ?>"></script>
        <title><?php echo $PAGE_TITLE ?></title>
   
            <link rel="stylesheet" href="css/style.css" />
    
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />

        <!--[if lte IE 8]>
            <script src="js/ie/html5.js"></script>
			<script src="js/ie/respond.min.js"></script>
        <![endif]-->
	<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
        <script language="JavaScript" type="text/javascript" src="cms.js"></script>
        <?php echo $val->DrowJsArrays(); ?>	
    </head>
    <body class="login">
     
        <script language = "javascript">
            
function restore_password()
{
        
	document.forgetform.btnSend.disabled=true;

	var email= document.getElementById('txtEmail').value;

        var status=validate();

        if(status)
        {
             $.post("forgot_password.php", { email_for_restoring: email, ajax: "yes" },
             function(data){             
                    alert(data);
                     document.forgetform.btnSend.disabled=false;
                 

            });
        }
        else
        {
            document.forgetform.btnSend.disabled=false;
        } 	
		
}
</script>


<div class="menu-toggler sidebar-toggler">
</div>
<!-- END SIDEBAR TOGGLER BUTTON -->
<!-- BEGIN LOGO -->
<div class="logo">
	<span style='font-face:tahoma;font-size:22px;color:#A5FFFA'><?php echo $SYSTEM_NAME ?></span>
</div>

<div class="content">
	<form class="login-form" method="post" action='login.php' >
		<h3 class="form-title"><?php echo L_SIGN_UP ?> &nbsp;<img style="display:none" src="style/i/ajax-loader4.gif" id="imgLoader" /></h3>
		<div class="alert alert-danger <?php echo trim($msg)=='' ? 'hide' : 'display'; ?>">
			<button class="close" data-close="alert"></button>
			<span>
			<?php echo $msg ?> </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9"><?php echo LOGIN ?></label>
			<input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo LOGIN ?> / <?php echo EMAIL ?>" name="txtLogin"/>
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9"><?php echo PASSWORD ?></label>
			<input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="<?php echo PASSWORD ?>" name="txtPass"/>
		</div>
                <div class="form-group" >        
                    <label class="control-label visible-ie8 visible-ie9"><?php echo LANGUAGE ?></label>
                    <select class="form-control" name="drpLang" ><?php echo $language_options ?></select>    
                </div>
                 <div class="form-group" style="display:<?php echo $captcha_display ?>">
                       
                        <img src ="lib/captcha.php" />&nbsp;<img src="style/i/arrow_right.jpg" />&nbsp;<input type="text" id="txtCaptcha" name="txtCaptcha" class="form-control inline input-small" placeholder="<?php echo ENTER_NUMBERS ?>"  />
                        
		</div>
		<div class="form-actions">			
                        <button class="btn btn-success uppercase" id='btnSubmit' name="btnSubmit" onclick="btnPostClick()" type="submit"><?php echo SIGN_IN ?></button><input type='hidden' id='hdnRes' name='hdnRes' />
			
                         <?php if(REGISTRATION_ENABLED=="yes") { ?>				
                                <a href="javascript:;" id="forget-password" class="forget-password"><?php echo REG_FORGOT_PASS ?></a>
                             <?php } ?>
			
                                                
		</div>
		<div class="login-options">
                                
                                <?php if(FACEBOOK_INTEGRATE=="yes") { ?>
                                <div align="center">
                                <div id="fbLogin1" class="fb-login-button" data-show-faces="false" data-scope="<?php echo FACEBOOK_ACCESS ?>" data-show-faces="false" data-width="300" data-max-rows="1"><?php echo LOGIN_WITH_FACEBOOK ?></div>
                                <div  id="fbLogin2" style="display:none;cursor:hand"  onclick="document.getElementById('btnPost').click()" class="facebook_button"><label style="cursor:hand" id="lblFBName"></label></div>
                                </div>
                                
                                <?php } ?>
		</div>
                  <?php if(REGISTRATION_ENABLED=="yes") { ?>					
                                      
		<div class="create-account">
			<p>
				<a href="register.php<?php echo $branch_url ?>" id="register-btn" class="uppercase"><?php echo REGISTER_IN_SYSTEM ?></a>
			</p>
		</div>
                <?php } ?>
                
              
                
	</form>
    
       <form class="forget-form" name='forgetform' onsubmit="return submitHandler()" >
                            <h3><?php echo CANT_SIGN_IN ?></h3>
                            <p>
                                     <br /><?php echo ENTER_EMAIL_PASSWORD_R ?>
                            </p>
                            <div class="form-group">
                                    <input onkeypress='return disable_submit()' class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo EMAIL ?>" id='txtEmail' name="txtEmail"/>
                            </div>
                            <div class="form-actions">
                                    <button type="button" id="back-btn" class="btn btn-default"><?php echo BACK ?></button>
                                    <button type="button" id="btnSend" name='btnSend' onclick="restore_password()" class="btn btn-success uppercase pull-right"><?php echo REQUEST_NEW_PASSWORD ?></button>
                            </div>
         </form>
    
    
    <form method="post" name ="fb_btn" id="fb_btn"><input type="submit" onclick="btnPostClick()" style="display:none" id="btnPost" name="btnPost" value="post" /></form>
    <input type="hidden" name="is_mobile" id="is_mobile" />
</div>
		

		
		
       
        <script language="javascript">
             document.getElementById('is_mobile').value=is_mobile();
        </script>
        <?php if(FACEBOOK_INTEGRATE=="yes") { ?>
            <div id="fb-root"></div>
            <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo FACEBOOK_APP_ID ?>',
          cookie: true,
          xfbml: true,
          oauth: true
        });
     //   FB.Event.subscribe('auth.login', function(response) {
        //  window.location.reload();      
        //  alert('yes');
      //    document.getElementById('btnPost').click();
      //  });
        
        FB.Event.subscribe('auth.statusChange', function(response) {
          //  alert(response.status)
        if (response.status === 'connected') { 
           // document.getElementById('btnPost').click();
        } else if (response.status === 'not_authorized') {
              //ask for permissions
        } else {
              //ask the user to login to facebook
        }
        });
        
        
        FB.Event.subscribe('auth.logout', function(response) {
         // window.location.reload();
       //   alert('no');
        });
        
        FB.getLoginStatus(function(response) {            
           // alert(response.status);
        if (response.status == 'connected') {          
            document.getElementById('fbLogin1').style.display="none";
            document.getElementById('imgLoader').style.display="";
            FB.api('/me', function(response) {
                document.getElementById('imgLoader').style.display="none";
                document.getElementById('fbLogin2').style.display="";
                document.getElementById('lblFBName').innerHTML = '<?php echo LOGIN_WITH ?> '+response.name;                
            });
            
            
        }
        else
        {
              FB.Event.subscribe('auth.login', function(response) {  
               // document.getElementById('imgLoader').style.display="";
                document.getElementById('btnPost').click();
              });
        }
       });
        
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
      
      
    </script>
    
        <?php } ?>   
		
		    
    <script language="javascript">
      function btnPostClick()
      {
          document.getElementById('imgLoader').style.display="";
		  $("#hdnRes").val(screen.width);
      }
    </script>
		

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
