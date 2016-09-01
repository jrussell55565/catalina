<?php if(!isset($RUN)) { exit(); } ?>
<script language="javascript">
	function test_ldap()
	{            
             document.form1.btnTest.disabled=true;
             document.getElementById('divRes').innerHTML = "";
 	     var login = document.getElementById('txtLogin').value;
             var pass = document.getElementById('txtPass').value;
            // alert(pass);
	     $.post("?module=test_ldap", { login: login,pass: pass, ajax: "yes" },
             function(data){
                 //alert(data);
                     document.getElementById('divRes').innerHTML = data;
                     document.form1.btnTest.disabled=false;
                 
            });
	}
</script>
<form id=form1 name=form1>
<table align=center>
<tr>
	<td> <?php echo LOGIN ?> : 
	</td>
	<td><input type=text id=txtLogin name=txtLogin />
	</td>
        <td> <?php echo PASSWORD ?> : 
	</td>
	<td><input type=text id=txtPass name=txtPass />
	</td>
	<td><input type=button name=btnTest id=btnTest value="<?php echo TEST_LDAP ?>" onclick="test_ldap()" style="width:90px" />
	</td>
</tr>
</table>
    <br />
    <div id="divRes"/>
</form>
