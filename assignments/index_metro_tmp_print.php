<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $PAGE_TITLE ?></title>      
		<style type='text/css'>                      
            div.onepage {page-break-after: always; }
         </style>
                
    </head>
    <body>
            
											<?php                                            
												include "modules/".$module_name."_tmp.php";
                                             ?>

	</body>
</html>