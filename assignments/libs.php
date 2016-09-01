    <?php if(!isset($RUN)) { exit(); } ?>
    <?php if(FACEBOOK_INTEGRATE=="yes") { ?>
    <script type="text/javascript">
      function postToWall(message,name,link,picture_link,description) {
	  
		shareToWall(message,name,link,picture_link,description);
		return;
          
        <?php if($mobile==false) { ?>  
        $.sticky("<?php echo FACEBOOK_MSG_POSTING ?>", {autoclose : 2000, position: "top-right", type: "st-error" }); 
        <?php } ?>  
            
        var params = {};
        params['message'] = message;
        params['name'] = name;
        params['link'] = link;
        params['picture'] = picture_link;
        params['description'] = description;
         
        FB.api('/me/feed', 'post', params, function(response) {
          if (!response || response.error) {
          //  alert('Error');
            alert(JSON.stringify(response.error));
          } else {
            <?php if($mobile==false) { ?>  
            $.sticky("<?php echo FACEBOOK_MSG_POSTED ?>", {autoclose : 4000, position: "top-right", type: "st-error" }); 
            <?php } else { ?>  
            alert("<?php echo FACEBOOK_MSG_POSTED ?>");
            <?php } ?>
          }
        });
      }
      
     function post_to_wall(user_quiz_id)
     {
	 
         if(user_quiz_id=="") return;
         
          $.post("utils/fb.php", { get_post_js:"yes",id: user_quiz_id, ajax: "yes" },
             function(data){			 
			if(data.substring(0, 6)=="postTo")
            {                  
                exec_js(data);			
            }
            });
     }
	 
	function shareToWall(message,name,link,picture_link,description)
	{
	   FB.ui({
			method: 'feed',
			message : message,
			name: name,
			link: link,
			href: link,
			picture: picture_link,
			caption: message,
			description: description
		});
	}
      
    </script>
    
    <div id="fb-root"></div>
    <script src="http://connect.facebook.net/en_US/all.js"></script>
    <script>
      FB.init({ 
        appId:<?php echo FACEBOOK_APP_ID ?>, cookie:true, 
        status:true, xfbml:true
      });
    </script>
    <?php } ?>