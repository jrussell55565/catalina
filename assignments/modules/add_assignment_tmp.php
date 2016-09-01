<?php if(!isset($RUN)) { exit(); } ?>
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<link rel="stylesheet" type="text/css" href="lib2/datepicker2/jquery.datetimepicker.css"/>

<?php echo $val->DrowJsArrays(); ?>

<script language="javascript">
    var question_bank_opened=false;
    var selected_user_type_text="local";
    var open_count = 0;
    function drpTests_onchange()
    {        
        if(querySt("id")!="-1") open_count++;        
        
        var test_id =$("#drpTests").val(); 
        var id = querySt("id") !="-1" ? "&id="+querySt("id") : "";    
                
       // alert(test_id);
        if(test_id=="-100")
        {      
            document.getElementById('trShowQstBnk').style.display=""
          //  $("#drpQChange").val("2"); 
         //   $("#drpQChange").attr('disabled', 'disabled');
                 if(question_bank_opened==false)
                 {                     
                    $.post("index.php?module=add_assignment"+id+"&bank=1", {  ajax: "yes", show_qst_bank : "yes", first_load:"yes" },
                      function(data){             
                         // alert(data);
                           question_bank_opened=true;
                           document.getElementById('divQstBank').innerHTML=data.grid_html;
                          // document.getElementById('divQstBankSearch').innerHTML=data.search_html;   
                          
                           if(open_count>1 || querySt("id")=="-1")
                           {
                                OpenQB();
                                document.getElementById('tblQB').style.zIndex = "9999";
                                MoveCenter('tblQB');
                           }
                       } , "json");
                 } else OpenQB();
        }
        else {            
         CloseQB();         
         document.getElementById('trShowQstBnk').style.display="none";
         //$("#drpQChange").removeAttr('disabled');
        }
        
        <?php if($asg_status==0) { ?>  FreezeStatus(); <?php } ?> 
    }
    
    $(document).keyup(function(e) 
    {
         if (e.keyCode == 27 && $("#drpTests").val()=="-100" ) { CloseQB(); }   // esc
    });
    
    function CloseQB()
    {
        document.getElementById('tblQB').style.display="none";
    }
    
    function OpenQB()
    {       
            MoveCenter('tblQB');
            document.getElementById('tblQB').style.display="";        
    }
    
    function FilterQB(catID)
    {        
        str = "";
        var cat_ids = $("#txtCats").val();        
        var arr = cat_ids.split(',');
        for(var i =0;i<arr.length;i++)
        {        
           // alert($("#chkCat"+arr[i]).is(':checked'));
            if($("#chkCat"+arr[i]).is(':checked')==true) 
                str+=","+arr[i];
        }
        
        str_sub = "";
        var sub_ids = $("#txtSubjects").val();        
        var arr = sub_ids.split(',');
        for(var i =0;i<arr.length;i++)
        {        
           // alert($("#chkCat"+arr[i]).is(':checked'));
            if($("#chkSub"+arr[i]).is(':checked')==true) 
                str_sub+=","+arr[i];
        }
        //alert(str_sub);
        if(str!="" && str_sub!="")
        {            
            var myarr = grd_get_checkboxes(document.getElementById("form1"),"chk_qst","");
            var id = querySt("id") !="-1" ? "&id="+querySt("id") : "";  
            $.post("index.php?module=add_assignment"+id+"&bank=1", {  ajax: "yes", show_qst_bank : "yes", first_load:"yes" , filter_cats : str, filter_subjects : str_sub, chkboxes : myarr  },
                   function(data){                                    
                        document.getElementById('divQstBank').innerHTML=data.grid_html;                   
                    } , "json");
        }
    }
    
    function ChangeCat()
    {        
        var id = querySt("id") !="-1" ? "&id="+querySt("id") : "";                
        var p_cat_id =$("#drpCats").val();        
        
      //  $.post("index.php?module=add_assignment"+id, {  ajax: "yes", fill_tests : "yes", cat_id : p_cat_id },
      //   function(data){                
      //       document.getElementById('tdTests').innerHTML=data;
      //       document.getElementById('drpTests').style.width="170px";
      //       drpTests_onchange();
     //   });
     
         $.post("index.php?module=add_assignment"+id, {  ajax: "yes", tests_grid : "yes", cat_id : p_cat_id },
         function(data){             		 
             document.getElementById('divQuizzes').innerHTML=data;
            // drpTests_onchange();
        });
    }
//'<a href="#" id="ahrefQstList" onclick="OpenQB()"><?php echo SHOW_QST_LIST ?></a>'
    function ShowUsers(type)
    {
        selected_user_type_text = type;
        if(type=='local')
        {            
            document.getElementById('tdLocalUsers').style.display="";
            document.getElementById('tdImportedUsers').style.display="none";  
            document.getElementById('tdFacebookUsers').style.display="none";              
            document.getElementById('tdLDAPUsers').style.display="none";   
            document.getElementById('btnLcl').style.color="red";
            document.getElementById('btnImp').style.color="black";
            document.getElementById('btnFB').style.color="black";
            document.getElementById('btnLDAP').style.color="black";
        }
        else if(type=='imported')
        {
            document.getElementById('tdLocalUsers').style.display="none";
            document.getElementById('tdImportedUsers').style.display="";
            document.getElementById('tdFacebookUsers').style.display="none"; 
            document.getElementById('tdLDAPUsers').style.display="none"; 
            document.getElementById('btnLcl').style.color="black";
            document.getElementById('btnImp').style.color="red";
            document.getElementById('btnFB').style.color="black";
            document.getElementById('btnLDAP').style.color="black";
        }
        else if(type=='facebook')
        {
            document.getElementById('tdLocalUsers').style.display="none";
            document.getElementById('tdImportedUsers').style.display="none";
            document.getElementById('tdFacebookUsers').style.display=""; 
            document.getElementById('tdLDAPUsers').style.display="none"; 
            document.getElementById('btnLcl').style.color="black";
            document.getElementById('btnImp').style.color="black";
            document.getElementById('btnFB').style.color="red";
            document.getElementById('btnLDAP').style.color="black";
        }
        else 
        {
            document.getElementById('tdLocalUsers').style.display="none";
            document.getElementById('tdImportedUsers').style.display="none";
            document.getElementById('tdFacebookUsers').style.display="none"; 
            document.getElementById('tdLDAPUsers').style.display=""; 
            document.getElementById('btnLcl').style.color="black";
            document.getElementById('btnImp').style.color="black";
            document.getElementById('btnFB').style.color="black";
            document.getElementById('btnLDAP').style.color="red";
        }
    }
    
    function isInt(value)
    {
        var er = /^[0-9]+$/;

        return ( er.test(value) ) ? true : false;
    }
    
    function selectAll(selectBox,selectAll) {
    if (typeof selectBox == "string") {
        selectBox = document.getElementById(selectBox);
    }
    if (selectBox.type == "select-multiple") {
        for (var i = 0; i < selectBox.options.length; i++) {
            selectBox.options[i].selected = selectAll;
        }
    }
    }

    function CheckForm()
    {                
        var error_msg = "";
        var isok = validate();
        if(isok)
        {
            var is_random = $("#drpIsRandom").val();             
            if(is_random!="1")
            {                           
                var random = $("#txtRandom").val();
                
                if(!isInt(random))
                {
                    error_msg+="<?php echo SHOW_RANDOMLY_VAL1 ?> \n";
                    isok = false;
                }
                else if(parseInt(random)<1)
                {
                    error_msg+="<?php echo SHOW_RANDOMLY_VAL2 ?> \n";
                    isok = false;
                }
            }
            
            var localusers_count = grd_get_checkboxes(document.getElementById('form1'), "chklcl", false);
            var impusers_count = grd_get_checkboxes(document.getElementById('form1'), "chkimp", false);            
            var ldapusers_count = grd_get_checkboxes(document.getElementById('form1'), "chkldap", false);      
            var rdSelectedGroup_ischecked = $("#rdSelectedGroup").is(':checked')
            var rdSelectedGroupLDAP_ischecked = $("#rdSelectedGroupLDAP").is(':checked')
            var rdAll_ischecked = $("#rdAll").is(':checked');
            var rdAllLDAP_ischecked = $("#rdAllLDAP").is(':checked');
            
            if(querySt("id") =="-1" && localusers_count.length<1 && impusers_count.length<1 && rdSelectedGroup_ischecked==false && rdAll_ischecked==false && rdAllLDAP_ischecked==false && ldapusers_count.length<1 && rdSelectedGroupLDAP_ischecked==false)
            {
                    error_msg+="<?php echo SELECT_USER_VAL ?> \n";
                    isok = false;
            }
            
            if(querySt("id") =="-1")
            {
                if(!diff_tab_clicked)
                {
                    error_msg+="<?php echo L_ENTER_POINTS ?> \n";
                    isok = false;
                }
            }
            
            if($("#drpTests").val()=="-100")
            {
                 var qst_count = grd_get_checkboxes(document.getElementById('form1'), "chk_qst", false);
                 if(qst_count.length<2)
                 {
                    error_msg+="<?php echo SELECT_QUESTION_VAL ?> \n";
                    isok = false;
                 }
            }
            
            var quizzes_arr = grd_get_checkboxes(document.getElementById("form1"),"chk_diffs","");	
            
            if(quizzes_arr.length<1)
            {                
                error_msg+="<?php echo TEST_VAL ?> \n";
            }
            if(is_random!="1")
            {
                var diff_results = $("#hdnDiffs").val().split(",");                
                
                var total_questions_count = 0;
                var randomly_show = parseFloat($("#txtRandom").val());
                for(var y = 0; y<diff_results.length;y++)
                {
                    if(diff_results[y]=="") break;
                    for(var o = 0; o<quizzes_arr.length;o++)
                    {
                      //  alert(quizzes_arr[o]);
                        total_questions_count+= parseFloat($("#txtDiffLevel_"+quizzes_arr[o]+"_"+diff_results[y]).val());                        
                    }                    
                }

                if(randomly_show!=total_questions_count)
                {
                    error_msg+="<?php echo L_ASG_MSG1 ?> \n";
                    error_msg+="<?php echo L_ASG_MSG1_1 ?> - "+randomly_show+"\n";
                    error_msg+="<?php echo L_ASG_MSG1_2 ?> - "+total_questions_count+"\n";
                    isok = false;
                }
            }
            
            
        }
        
        if(!isok && error_msg!="")
        {
                alert(error_msg);    
        }
        
        if(isok && error_msg=="")
        {
            document.getElementById('btnSave').style.display="none";
            document.getElementById('btnWait').style.display="";
            $('#imgAjaxLoader').show();
        }
      
        selectAll("drpFBUsers",true);
        selectAll("drpLDAPUsers",true);
        selectAll("mltSubjectList",true);
      
        return isok;
    
    }

    function ShowOptions()
    {
        var type = getType();
        var display = "disabled";
        if(type=="1") display="";
        else
        {
            document.getElementById('txtSuccessP').value="0";
            document.getElementById('txtTestTime').value="0";
            $("#drpShare").val(0);
        }

     //   for(var i=0;i<6;i++)
    //    {
            //document.getElementById("drpTr"+i).style.display=display;            
            //$("drpTr"+i).attr('disabled', 'disabled');
          //  $("drpTr"+i).find(':input').prop("disabled", true);
      //  }        
        if(type!="1")
        {
            $("#drpResultsBy").attr('disabled', 'disabled');
            $("#txtSuccessP").attr('disabled', 'disabled');
            $("#txtTestTime").attr('disabled', 'disabled');
            $("#drpSendRes").attr('disabled', 'disabled');
            $("#drpAR").attr('disabled', 'disabled');
            $("#drpShowRes").attr('disabled', 'disabled');
            $("#drpAllowBack").attr('disabled', 'disabled');
            $("#drpShowSuccess").attr('disabled', 'disabled');
            $("#drpShowPointInfo").attr('disabled', 'disabled');
            $("#drpCert").attr('disabled', 'disabled'); 
            $("#drpResTemp").attr('disabled', 'disabled'); 
            $("#drpShare").attr('disabled', 'disabled'); 
            $("#drpCalcMode").attr('disabled', 'disabled');
            $("#drpAnsCalcMode").attr('disabled', 'disabled');
            
        }
        else
        {
            $("#drpResultsBy").removeAttr('disabled');
            $("#txtSuccessP").removeAttr('disabled');
            $("#txtTestTime").removeAttr('disabled');
            $("#drpSendRes").removeAttr('disabled');
            $("#drpAR").removeAttr('disabled');
            $("#drpShowRes").removeAttr('disabled');
            $("#drpAllowBack").removeAttr('disabled');
            $("#drpShowSuccess").removeAttr('disabled');
            $("#drpShowPointInfo").removeAttr('disabled');
            $("#drpCert").removeAttr('disabled');
            $("#drpResTemp").removeAttr('disabled');
            $("#drpShare").removeAttr('disabled');
            $("#drpCalcMode").removeAttr('disabled');
            $("#drpAnsCalcMode").removeAttr('disabled');
        }
       // $("#drpResultsBy").attr('disabled', 'disabled');
        
        SetAnsCalcMode();
        
    }
    
    function SetAnsCalcMode()
    {                   
        if($("#drpCalcMode").val()=="1")
        {
            $("#drpAnsCalcMode").attr('disabled', 'disabled');            
             EnableDiffBoxes(2,false);              
        }
        else if($("#drpCalcMode").val()=="3")
        {
            $("#drpAnsCalcMode").attr('disabled', 'disabled');
            EnableDiffBoxes(2,true);
        }
        else 
        {
             $("#drpAnsCalcMode").removeAttr('disabled');
             EnableDiffBoxes(2,false);
        }
    }

    function getType()
    {
        var type = document.getElementById('drpType').options[document.getElementById('drpType').selectedIndex].value;
        return type;
    }
    
    function CheckSelectedGroup()
    {
        
        var selected_group =$("#drpUserGroups").val(); 
        theForm = document.getElementById("form1");
        var cName=""; //chklcl
        if(selected_user_type_text=="local") cName="chklcl";
        else if(selected_user_type_text=="imported") cName="chkimp";
        else if(selected_user_type_text=="ldap") cName="chkldap";
        else return;

        for (i=0,n=theForm.elements.length;i<n;i++)
        {
          if (theForm.elements[i].className.indexOf(cName) !=-1) {


                var user_group = $("#hdn"+theForm.elements[i].id).val();

                if(selected_group==user_group) theForm.elements[i].checked ="checked";
                else theForm.elements[i].checked ="";
          }
       }
        
    }
    
    function drpAllowBack_OnChange()
    {
        if($("#drpAllowBack").val()=="1")
        {
            $("#drpShowSuccess").attr('disabled', 'disabled');
            $("#drpShowPointInfo").attr('disabled', 'disabled');
            $("#drpShowSuccess").val("0")
            $("#drpShowPointInfo").val("0")
        }
        else
        {
            $("#drpShowSuccess").removeAttr('disabled');
            $("#drpShowPointInfo").removeAttr('disabled');   
           
        }
    }
    function chkShowIntro_onclick()
    {
        var checked=$("#chkShowIntro").is(':checked');
        if(checked) $("#trEditor").show();
        else  $("#trEditor").hide();
    }
    function drpIsRandom_onchange()
    {        
        var is_random = $("#drpIsRandom").val();
       
        if(is_random==2)
        {            
         //   $("#drpTrRandom").show();
          //  $("#drpTrVar").show(); 
            $("#txtRandom").removeAttr('disabled');            
            $("#drpVariants").removeAttr('disabled');
            $("#drpRandomType").removeAttr('disabled');
            
            ShowRandomVariants(true);
            drpVariants_onchange();
        }
        else if(is_random==1)
        {            
            $("#txtRandom").attr('disabled', 'disabled');            
            $("#drpVariants").attr('disabled', 'disabled'); 
            $("#drpRandomType").attr('disabled', 'disabled'); 
            
            ShowRandomVariants(false);
        }
        else if(is_random==3)
        {            
            $("#txtRandom").removeAttr('disabled');      
            $("#drpVariants").attr('disabled', 'disabled'); 
            $("#drpRandomType").attr('disabled', 'disabled'); 
            
            ShowRandomVariants(false);
        }
        ShowQuizzesList(is_random);  
        FreezeStatus();  
    }
    
    function FreezeStatus()
    {
        var is_random = $("#drpIsRandom").val();
        var test_id =$("#drpTests").val(); 
        
        if(is_random==2 || test_id=="-100")
        {
            $("#drpQChange").val("2"); 
            $("#drpQChange").attr('disabled', 'disabled');
        }
        else
        {
            $("#drpQChange").removeAttr('disabled');
        }
    }
    
    function ShowRandomVariants(show)
    {
           var keyword = "";
            if(show) 
            {
               $("#gridhead5").show();
               $("#gridheadimp5").show();
               $("#gridheadldap5").show();
            }
            else 
            { 
                $("#gridhead5").hide() ; 
                $("#gridheadimp5").hide() ; 
                $("#gridheadldap5").hide();                                             
                keyword ="none" 
            } 
            
            var elems = document.getElementsByClassName("c_list_item5");
            for(var i = 0; i < elems.length; i++) {
                elems[i].style.display = keyword;             
            }
    }
    function drpVariants_onchange()
    {                          
            AddRemoveVariantElements("slclocal");
            AddRemoveVariantElements("slcimport");
            AddRemoveVariantElements("slcldap");
    }
    
    function AddRemoveVariantElements(cName)
    {
        var variants = parseInt($("#drpVariants").val());  
        theForm = document.getElementById("form1");
        for (i=0,n=theForm.elements.length;i<n;i++)
        {
            if (theForm.elements[i].className.indexOf(cName) !=-1) {

            var  dropdown= $("#"+theForm.elements[i].id).val();
            $("#"+theForm.elements[i].id).empty(); 

            for(var w = 0; w<arrVariants.length;w++)
            {
                if(w==variants+1) break;

                $("#"+theForm.elements[i].id).append($('<option>', {
                    value: w,
                    text: arrVariants[w]
                }));

                if(dropdown==w) $("#"+theForm.elements[i].id).val(dropdown);
            }
          }
        }
    }
       
      
    
    
    var arrVariants = new Array('<?php echo RANDOM ?>',<?php echo $answer_js ?>) ;
    
 
    

</script>

<script language="javascript">

function RemoveSubject()
{
    var index = $('#mltSubjectList').get(0).selectedIndex;
    $('#mltSubjectList option:eq(' + index + ')').remove();
}
    
function AddSubject()
{
    var subject_id = $("#drpSubjects").val();
    var subject_point = $("#drpSubPoint").val();
    var pres_id = $("#drpPres").val();
    var pres_duration = $("#txtPresDuration").val();    
    var subject_text = $("#drpSubjects option:selected").text();    
    var pres_text = $("#drpPres option:selected").text();
    
    var mytext = subject_text + ' - '+ '<?php echo MIN_SUCCESS_POINT ?> : ' + subject_point + ' - '+pres_text + ' - ' + '<?php echo PRESENTATION_DURATION ?> : ' + pres_duration;
    var select_key = base64_encode(subject_id) + ';|' + base64_encode(subject_point) + ';|' + base64_encode(pres_id) + ';|' + base64_encode(pres_duration);        
  
    if(!IsNumeric(subject_point) || IsEmpty(subject_point)) 
    {
       var msg = '<?php echo MIN_SUCCESS_POINT ?> ' +  '<?php echo ENTER_ONLY_NUMBERS ?> ';
       alert(msg);
       return false;
    }
    
    if(!IsNumeric(pres_duration) || IsEmpty(pres_duration)) 
    {
       var msg = '<?php echo PRESENTATION_DURATION ?> ' +  '<?php echo ENTER_ONLY_NUMBERS ?> ';
       alert(msg);
       return false;
    }
    
    $('#mltSubjectList').append($('<option>', {
    value: select_key,
    text: mytext
}));
    
}
</script>

<script language=javascript>
	var editor = CKEDITOR.replace('editor1',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash'

        });
</script>

<script language="javascript">
function AddFacebookUser()
{
    var email = $("#txtEmail").val().toLowerCase();
        
    var splited_arr = email.split(",");
    
    for(var i =0;i<splited_arr.length;i++)
    {    
        var splited_email = trim(splited_arr[i]);
        if(checkEmail(splited_email)==true)
        {
            if($("#drpFBUsers option[value='"+splited_email+"']").length == 0)
            {
                $('#drpFBUsers').append( new Option(splited_email,splited_email) );
            }
            else alert('<?php echo EMAIL_ALREADY_EXISTS ?>'+splited_email);
        }    
    }
       
}

function RemoveFacebookUser()
{
    $("#drpFBUsers option:selected").remove();
}

function AddLDAPUser()
{
    var email = trim($("#txtEmailLDAP").val().toLowerCase());
        
    var splited_arr = email.split(",");
    
    for(var i =0;i<splited_arr.length;i++)
    {    
        var splited_email = trim(splited_arr[i]);
        if(checkEmail(splited_arr[i])==true)
        {
            if($("#drpLDAPUsers option[value='"+splited_email+"']").length == 0)
            {
                $('#drpLDAPUsers').append( new Option(splited_email,splited_email) );
            }
            else alert('<?php echo EMAIL_ALREADY_EXISTS ?>'+splited_email);
        }    
    }
       
}

function RemoveLDAPUser()
{
    $("#drpLDAPUsers option:selected").remove();
}

function ShowFbGroup()
{
    
    if($("#rdAll").is(':checked'))
    {
        document.getElementById('tblFBList').style.display="none";
    }
    else if($("#rdSelectedGroup").is(':checked'))
    {
        document.getElementById('tblFBList').style.display="";
    }
    else
    {
        document.getElementById('tblFBList').style.display="none";
    }
    
}

function ShowLDAPGroup()
{
    
    if($("#rdAllLDAP").is(':checked'))
    {
        document.getElementById('tblLDAPList').style.display="none";
    }
    else if($("#rdSelectedGroupLDAP").is(':checked'))
    {
        document.getElementById('tblLDAPList').style.display="";
    }
    else
    {
        document.getElementById('tblLDAPList').style.display="none";
    }
    
}

var quiz_changed = false;

var diff_tab_clicked=false;
function LoadDiffLevels()
{
    if(quiz_changed==false) return ; 	
    
    diff_tab_clicked=true;
    var myarr = grd_get_checkboxes(document.getElementById("form1"),"chk_diffs","");	                       		          
    LoadDiffLevelsReal(myarr);         	                    
}

function LoadDiffLevelsReal(load_array)
{    
    var myarr = load_array;
    var id = querySt("id") !="-1" ? "&id="+querySt("id") : "";                  
    
    $.post("index.php?module=add_assignment"+id, {  ajax: "yes", load_diff_levels:"yes", chkboxes : myarr  },

    function(data){     			            
            document.getElementById('divDiffs').innerHTML=data;      
            quiz_changed = false;   
            for(var i=0;i<myarr.length;i++)
            {                          
                  $("#txtQuizPrior"+myarr[i]).val(i+1);
            }
            CalculatePenalties_OnChange(); 
            ShowQuizzesList($("#drpIsRandom").val());
            SetAnsCalcMode();
            
            if(querySt("id")!="-1")
            {
                 EnableDiffBoxes(1,false);
                 EnableDiffBoxes(2,false);
                 EnableDiffBoxes(3,false);    
            }
            
    } );
}
var ResultsBy = 0;
function drpResultsBy_onchange()
{
    var results_by = $("#drpResultsBy").val();
    ResultsBy = results_by;
    
    EnableDiffBoxes(2,false);
    EnableDiffBoxes(3,false);
    
    if(results_by=="2")
    {        
        $("#txtPointKoe").attr('disabled', 'disabled');
        $("#drpCalcMode").attr('disabled', 'disabled');
        $("#drpCalcPen").attr('disabled', 'disabled');
    }
    else
    {
        $("#txtPointKoe").removeAttr('disabled');
        $("#drpCalcMode").removeAttr('disabled');
        $("#drpCalcPen").removeAttr('disabled');
        SetAnsCalcMode();
        CalculatePenalties_OnChange();
    }
    
}

function ShowQuizzesList(is_random)
{
    if(is_random=="1") EnableDiffBoxes(1,false);
    else EnableDiffBoxes(1,true);
}

function extgrid_chkdiffs_onclick(values)
{
	 quiz_changed = true;
}

function extgrid_chkthemes_onclick(values)
{
   
}
var _last_id=-1;
function load_subject_settings(subject_id)
{
    $("#btnModClose").hide();
    $("#btnModClose2").hide();
    var id = querySt("id") !="-1" ? "&id="+querySt("id") : "";                  
    $('#myModal').modal('toggle');    
    $.post("index.php?module=add_assignment"+id, {  ajax: "yes", load_subject_themes:"yes", quiz_id:subject_id , selchkboxes:$("#hdnQ"+subject_id).val() },
    function(data){     			                    
            $("#idModContent1").html(data);            
    } );
    _last_id = subject_id;
}

function modal_save_click()
{
    $('#myModal').modal('hide');  
    
    var myarr = grd_get_checkboxes(document.getElementById("form1"),"chk_themes","");
    var str ="";
    
    for(var i =0; i<myarr.length;i++)
    {
        str+=","+myarr[i];
    }
    
    if(str!="")
    {
        str = str.substring(1);
    }
    
    $("#hdnQ"+_last_id).val(str);
    
}

</script>

<form id="form1" method="post" enctype="multipart/form-data">   <br /> 
    <table width="900px" border="0">
        <tr>
            <td>
                
                <div class="tabbable"> <!-- Only required for left/right tabs -->
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab1" data-toggle="tab"><?php echo GEN_SETTINGS ?></a></li>
                      
                      <li><a onclick='return LoadDiffLevels()' href="#tabqst" data-toggle="tab"><?php echo L_QST_SETT ?></a></li>
                      <li style='display:<?php echo $display_users ?>' ><a href="#tabusers" data-toggle="tab"><?php echo L_USR_SETT ?></a></li>
                      <li><a href="#tab2" data-toggle="tab"><?php echo ADD_SETTINGS ?></a></li>
                      <li><a href="#tab3" data-toggle="tab"><?php echo SUBJECT_SETTINGS ?></a></li>
                      <li><a href="#tab4" data-toggle="tab"><?php echo PAYMENT_INFO ?></a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab1">
                        <p>
                            
                            <table>
                                <tr>
                                    <td valign="top">
                            
                            <table width="400px">
        <tr>
            <td  width="290px">
                <?php echo ASSIGNMENT_NAME ?> :
            </td>
            <td>
                <input class='form-control' style="width:170px" type="text" name="txtAssignmentName" id="txtAssignmentName" value="<?php echo util::GetData("txtAssignmentName") ?>"  />
            </td>
        </tr>
        <tr>
            <td class="desc_text" width="290px">
                <?php echo CAT ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpCats" name="drpCats" onchange="ChangeCat()" <?php echo $disable_controls ?> >
                <?php echo $cat_options ?>
                </select>
            </td>
        </tr>
         <tr style='display:none'> 
            <td class="text_desc">
                <?php echo QUESTIONS ?> :
            </td>
            <td id="tdTests">
                 <select class='form-control' style="width:170px" id="drpTests" name="drpTests" <?php echo $disable_controls ?> >
                     <option value="-1"><?php echo NOT_SELECTED ?></option>
                </select>                
            </td>
        </tr>
        <tr id="trShowQstBnk1" style='display:none'>
            <td></td>
            <td><a href="#" id="ahrefQstList" onclick="OpenQB()"  ><?php echo SHOW_QST_LIST ?></a></td>
        </tr>
        <tr>
            <td class="text_desc">
                <?php echo TYPE ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpType" name="drpType" onchange="ShowOptions()" <?php echo $disable_controls ?>>
                     <?php echo $type_options ?>
                </select>
            </td>
        </tr>
      
               <tr >
            <td class="text_desc">
                <?php echo SHOW_QUESTIONS ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" onchange="drpIsRandom_onchange()"  id="drpIsRandom" name="drpIsRandom" <?php echo $disable_controls ?> >
                     <?php echo $show_all_random_options ?>
                </select>
            </td>
        </tr>
        
        <tr id="drpTrRandom">
            <td class="text_desc">
                <?php echo SHOW_RANDOMLY ?> :
            </td>
            <td valign='bottom'>
                <input class='form-control inline' <?php echo $disable_controls ?> style="width:170px"  type="text" name="txtRandom" id="txtRandom" value="<?php echo util::GetData("txtRandom") ?>"  /><select style="display:none" class='form-control inline' <?php echo $disable_controls ?> name="drpRandomType" id="drpRandomType" style="width:120px">
                    
                    <?php echo $random_type_options ?>
                </select>
            </td>
        </tr>
        
         <tr id="drpTrVar">
            <td class="text_desc">
                <?php echo VARIANTS ?> :
            </td>
            <td>
                 <select class='form-control' <?php echo $disable_controls ?> id="drpVariants" name="drpVariants" style="width:170px" onchange="drpVariants_onchange()"  >
                     <?php echo $random_variant_options ?>
                </select>
            </td>
        </tr>
        
            <tr id="drpTr1">
            <td class="text_desc">
                <?php echo RESULTS_BY ?> :
            </td>
            <td>
                 <select <?php echo $disable_controls ?> class='form-control' style="width:170px"  id="drpResultsBy" name="drpResultsBy" onchange='drpResultsBy_onchange()'>
                     <?php echo $result_options ?>
                </select>
            </td>
        </tr>
        
        <tr id="drpTr2">
            <td class="text_desc">
                <?php echo SUCCESS_POINT_PERC ?> :
            </td>
            <td>
                 <input class='form-control' style="width:100px" type="text" name="txtSuccessP" id="txtSuccessP" value="<?php echo util::GetData("txtSuccessP") ?>"  />
            </td>
        </tr>      
        <tr id="drpTr3">
            <td class="text_desc">
                <?php echo TEST_TIME ?> :
            </td>
            <td>
                 <input class='form-control' style="width:100px" class="ttip_t" title="<?php echo ENTER_ZERO_HIDE_TIMER ?> "  type="text" name="txtTestTime" id="txtTestTime" value="<?php echo util::GetData("txtTestTime") ?>"  />
            </td>
        </tr>
        
        <tr id="drpTrRegen" style="display:<?php echo $regen_display ?>">
            <td class="text_desc">
                <?php echo RE_GEN_QST ?> :
            </td>
            <td>
                 <input class='els' type="checkbox" id="chkRegen" name="chkRegen" <?php echo $disable_controls ?> />
            </td>
        </tr>
        
        </table>
        </td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        <td valign="top">    
                    <form></form>
                    <form class="form-inline" onsubmit="return submitHandler()">
                        <input type="text" id="txtSearchQuiz" class="form-control input-medium inline" placeholder="<?php echo SEARCH ?>" />
                        <input type="button" class='btn btn-primary' onclick="SearchInTable('txtSearchQuiz','table-tblquizzes','1')" value='<?php echo SEARCH ?>' />
                        <input  class='btn btn-primary' type="button" onclick="ShowAllTableRows('txtSearchQuiz','table-tblquizzes')" value='<?php echo SHOW_ALL ?>' />
                   </form> 
                     <div id="divQuizzes" style="overflow : auto; height : 300px;width:400px; "> 
                    </div>
				
            
        
            </td>
            </tr>
            </table>
                        </p>
                        <div style='display:<?php echo $display_users ?>'>
                         <form class="form-inline"></form>
                    
                   <form class="form-inline" onsubmit="return submitHandler()">
                        <input type="text" id="txtSearchGroup" class="form-control input-medium inline" placeholder="<?php echo SEARCH ?>" />
                        <input type="button" class='btn btn-primary' onclick="SearchInTable('txtSearchGroup','table-tblgroups','1')" value='<?php echo SEARCH ?>' />
                        <input  class='btn btn-primary' type="button" onclick="ShowAllTableRows('txtSearchGroup','table-tblgroups')" value='<?php echo SHOW_ALL ?>' />
                   </form> 
                     <div id="dv_groups" style="overflow : auto; height : 300px; width:820px ; ">                                        
                        <?php echo $grd_groups_html ?>
                     </div>
                     </div>   
                        
                      </div>
                        <div class='tab-pane' id='tabqst'>
                            
              
                            
                            <table>
                                <tr>
                                    <td><?php echo L_POINT_KOE ?> :  &nbsp;
                                    </td>
                                     <td>
                                       <input onkeypress='return onlyDecs(event);' type="text" class="form-control input-medium inline" name="txtPointKoe" id="txtPointKoe" value="<?php echo util::GetData("txtPointKoe") ?>" >
                                    </td>
                                </tr>
                                 <tr>
                                    <td ><?php echo CALC_BY ?> : &nbsp;
                                    </td>
                                     <td>
                                         <select class='form-control input-medium' <?php echo $disable_controls ?> onchange="SetAnsCalcMode()" style="width:170px"  id="drpCalcMode" name="drpCalcMode">
                                                    <?php echo $calcmode_options ?>
                                         </select>
                                    </td>
                                </tr>
                                  <tr>
                                    <td ><?php echo L_CALC_PEN ?> : &nbsp;
                                    </td>
                                     <td>
                                         <select class='form-control input-medium' <?php echo $disable_controls ?> style="width:170px"  id="drpCalcPen" name="drpCalcPen" onchange="CalculatePenalties_OnChange()">
                                                    <?php echo $calcpen_options ?>
                                         </select>
                                    </td>
                                </tr>
                            </table>
                            <input type='hidden' id='hdnDiffs' value='<?php echo $diff_ids ?>' />
       
                            
                     
             
                            <hr />
                         
                            <div id='divDiffs'></div> <input type="hidden" name="txtQuizIds" id="txtQuizIds" value="<?php echo $selected_quiz_ids_js ?>" />
                        </div>
                      <div class="tab-pane" id="tab2">
                          <p>
                          <table>
                              <tr>
                                  <td valign="top">
                                      
                                <table width="400px">                  
          
        
      
        <tr>
            <td class="text_desc">
                <?php echo ASG_HOW_MANY ?> :
            </td>
            <td>
                 <input class='form-control' style="width:100px" type="text" id="txtHowMany" name="txtHowMany" value="<?php echo util::GetData("txtHowMany") ?>" />
            </td>
        </tr>
     
        
                  <tr >
            <td class="text_desc">
                <?php echo VISIBLE_START_DATE ?> :
            </td>
            <td>
                 <input class='form-control' style="width:170px" value="<?php echo util::GetData("txtStartDate") ?>" class="ttip_t" title="<?php echo START_DATE_T_TIP ?> "  type="text"  id="datetimepicker_start" name="datetimepicker_start" />
            </td>
        </tr>
        <tr >
            <td class="text_desc">
                <?php echo VISIBLE_END_DATE ?> :
            </td>
            <td>
                 <input class='form-control' style="width:170px" value="<?php echo util::GetData("txtEndDate") ?>" class="ttip_t" title="<?php echo END_DATE_T_TIP ?> "  type="text" id="datetimepicker_end" name="datetimepicker_end"  />
            </td>
        </tr>
           <tr>
            <td class="text_desc">
                <?php echo RES_TEMPLATE ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpResTemp" name="drpResTemp">
                     <?php echo $result_template_options ?>
                </select>
            </td>
        </tr>

       
         <tr style="display:none">
                        <td class="text_desc" >
                            <?php echo FACEBOOK_COMMENTS ?> : 
                        </td>
                          <td>
                              <select class='form-control' id="drpComments" name="drpComments" style="width:170px" >
                                  <option value="0"><?php echo DISABLE_COMMENTS ?></option>    
                                  <option value="1"><?php echo ALLOW_COMMENTS ?></option>                                                                
                                  <option value="2"><?php echo AFTER_STOP ?></option>                                  
                              </select>
                        </td>
        </tr>
        
         
  
                                 
                                      
                              
                                  
       
            <tr>
            <td class="text_desc">
                <?php echo ANSWER_CALC_MODE ?> :
            </td>
            <td>
                 <select class='form-control' <?php echo $disable_controls ?> style="width:170px"  id="drpAnsCalcMode" name="drpAnsCalcMode">
                     <?php echo $answer_calcmode_options ?>
                </select>
            </td>
        </tr>                                  
     <tr style="display:none">
            <td class="text_desc">
                <?php echo ASG_AFFECT_CHANGE ?> :
            </td>
            <td>
                 <select class='form-control' <?php echo $disable_controls ?> style="width:170px"  id="drpQChange" name="drpQChange">
                     <?php echo $qchange_options ?>
                </select>
            </td>
        </tr>                                  

      <tr>
            <td class="text_desc">
                <?php echo QUESTIONS_ORDER ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpQO" name="drpQO" >
                     <?php echo $questions_order_options ?>
                </select>
            </td>
        </tr>
          <tr>
            <td class="text_desc">
                <?php echo ANSWERS_ORDER ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpAO" name="drpAO" >
                     <?php echo $answers_order_options ?>
                </select>
            </td>
        </tr>       


    <tr id="drpTr5">
       <td class="text_desc">
                <?php echo ASG_SEND_RESULTS ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpSendRes" name="drpSendRes">
                     <?php echo $sending_options ?>
                </select>
            </td>
        </tr>

    <tr >
        

        
            <td class="text_desc">
                <?php echo SEND_MAILS_COPY ?> :
            </td>
            <td>
                 <input class='form-control' style="width:155px"  type="text" id="txtMailCopy" name="txtMailCopy" value="<?php echo util::GetData("txtMailCopy") ?>" />
            </td>
        </tr>
 <tr >
            <td class="text_desc">
                <?php echo ENABLE_FOR_NEW ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpNewUsers" name="drpNewUsers">
                     <?php echo $enablenew_options ?>
                </select>
            </td>
        </tr>
  
      
         
      </table>
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td valign="top">                                  
      <table>  
 
              <tr id="drpTr0">
            <td class="text_desc">
               <?php echo SHOW_RESULTS ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpShowRes" name="drpShowRes">
                     <?php echo $showres_options ?>
                </select>
            </td>
        </tr>
         <tr id="drpTr4">
            <td class="text_desc" >
                <?php echo REVIEW_ANSWERS ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px" id="drpAR" name="drpAR" >
                     <?php echo $review_options ?>
                </select>
            </td>
        </tr>   
         <tr >
            <td class="text_desc">
                <?php echo ALLOW_BACK_EXAM ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpAllowBack" name="drpAllowBack" onchange="drpAllowBack_OnChange()">
                     <?php echo $allow_change_answers_options ?>
                </select>
            </td>
        </tr>
         <tr >
            <td class="text_desc">
                <?php echo SHOW_MSG_AFTER_EACH_QST ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpShowSuccess" name="drpShowSuccess">
                     <?php echo $show_msg_after_qst ?>
                </select>
            </td>
        </tr>
        
        <tr >
            <td class="text_desc">
                <?php echo SHOW_POINT_INFO ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpShowPointInfo" name="drpShowPointInfo">
                     <?php echo $show_point_info_options ?>
                </select>
            </td>
        </tr>
        
        <tr id="drpTr3">
            <td class="text_desc">
                <?php echo CERTIFICATE ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpCert" name="drpCert">
                     <?php echo $certificate_options ?>
                </select>
            </td>
        </tr>
        
          <tr >
            <td class="text_desc">
                <?php echo ALLOW_ASG_RATE ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpAsgRate" name="drpAsgRate">
                     <?php echo $rating_asg_options ?>
                </select>
            </td>
        </tr>
        
          <tr >
            <td class="text_desc">
                <?php echo ALLOW_QST_RATE ?> :
            </td>
            <td>
                 <select class='form-control' style="width:170px"  id="drpQstRate" name="drpQstRate">
                     <?php echo $rating_qst_options ?>
                </select>
            </td>
        </tr>
 
         <tr style="display:<?php echo $fb_display ?>"> 
                        <td class="text_desc">
                            <?php echo FACEBOOK_SHARE ?> : 
                        </td>
                          <td>
                              <select class='form-control' id="drpShare" name="drpShare" style="width:170px" >
                               <?php echo $fb_share_options ?>
                              </select>
                        </td>
        </tr>
        
    </table>                
 </td>
                              </tr>
                          </table>                                      
                          </p>
                      </div>
                        <div class="tab-pane" id="tab3">
                            <table>
                                <tr>
                                    <td width="200px"><?php echo SHOW_SUBJECT_NAME ?> : 
                                    </td>
                                    <td>
                                        <select class='form-control' id="drpShowSubName" name="drpShowSubName">
                                            <?php echo $show_sub_options ?>
                                        </select>
                                    </td>
                                </tr>     
                                 <tr>
                                    <td width="200px"><?php echo FAIL_IF_SUBJECT_FAILS ?> : 
                                    </td>
                                    <td>
                                        <select class='form-control' id="drpSbjFail" name="drpSbjFail">
                                            <?php echo $fail_subject_options ?>
                                        </select>
                                    </td>
                                </tr> 
                            </table>
                            <hr />
                              <table border='0'>
                                <tr>
                                    <td><?php echo QUIZ_NAME ?> : 
                                    </td>     
                                    <td><?php echo MIN_SUCCESS_POINT ?> : 
                                    </td> 
                                    <td><?php echo PRESENTATION ?> : 
                                    </td> 
                                    <td><?php echo PRESENTATION_DURATION ?> : 
                                    </td> 
                                    <td>&nbsp;
                                    </td> 
                                </tr>        
                                 <tr>
                                     <td><select class='form-control' style="width:150px" id="drpSubjects" name="drpSubjects">
                                             <?php echo $subject_options ?>
                                         </select> 
                                    </td>     
                                    <td><input class='form-control' type="text" style="width:150px" id="drpSubPoint" name="drpSubPoint" value="0" />
                                    </td> 
                                    <td>
                                        <select class='form-control' style="width:150px"  id="drpPres" name="drpPres">
                                             <?php echo $pres_options ?>
                                         </select> 
                                    </td> 
                                      <td>
                                        <input class='form-control' type="text" style="width:150px" id="txtPresDuration" name="txtPresDuration" value="0" />
                                    </td> 
                                    <td valign="top">
                                        <input class='btn btn-primary' onclick="AddSubject()" type="button" id="btnAddSub" class="btn" value="<?php echo ADD ?>" />
                                    </td> 
                                </tr> 
                            </table>
                            <table>
                                <tr>
                                    <td>
                                        <select id="mltSubjectList" name="mltSubjectList[]" multiple="multiple" style="width:700px;height:150px"><?php echo $subject_list ?></select>
                                    </td>
                                </tr>
                            </table>
                            <input type="button" class="btn btn-primary" value="<?php echo REMOVE ?>" onclick="RemoveSubject()" />
                        </div>
                        <div class="tab-pane" id="tab4">
                            <table>
                                <tr>
                                    <td><?php echo ASG_COST ?> : &nbsp; </td>
                                    <td><input type='text'  id='txtCost' name='txtCost'  value="<?php echo util::GetData("txtCost") ?>"  style='width:100px'>
                                        <?php echo PAYPAL_CURRENCY ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                          <div class='tab-pane' id='tabusers'  >
                              
                               
                                <form class="form-inline" onsubmit="return submitHandler()">
                                    <input type="text" id="txtSearchLusers" class="form-control input-medium inline" placeholder="<?php echo SEARCH ?>" />
                                    <input type="button" class='btn btn-primary' onclick="SearchInTable('txtSearchLusers','table-tbllocalusers','1')" value='<?php echo SEARCH ?>' />
                                    <input  class='btn btn-primary' type="button" onclick="ShowAllTableRows('txtSearchLusers','table-tbllocalusers')" value='<?php echo SHOW_ALL ?>' />
                               </form> 
                              <br />
                              <span style="display:<?php echo $display_users ?>">
                                <table width="820px" border="0" >        
                                    <tr>
                                        <td colspan="2">
                                            <input id="btnLcl" type="button" onclick="ShowUsers('local')" value="<?php echo LOCAL_USERS ?>" style="border:0;width:200px;color:red" />&nbsp;<input id="btnImp" type="button" onclick="ShowUsers('imported')" value="<?php echo IMPORTED_USERS ?>" style="border:0;width:200px" /> <input  id="btnFB" type="button" onclick="ShowUsers('facebook')" value="<?php echo FACEBOOK_USERS ?>" style="border:0;width:200px;display:<?php echo $fb_display ?>" /> <input  id="btnLDAP" type="button" onclick="ShowUsers('ldap')" value="<?php echo LDAP_USERS ?>" style="border:0;width:200px;display:<?php echo $ldap_display ?>" />                         
                                        </td>            
                                    </tr>
                                    <tr><td></td></tr>

                                    <tr>                
                                        <td valign="top" id="tdLocalUsers" >


                                             <div id="div_grid" style="overflow : auto; height : 380px; "></div>

                                        </td>
                                        <td valign="top" id="tdImportedUsers" style="display:none" >
                                                <div id="div_grid_imp" style="overflow : auto; height : 380px; "></div>
                                        </td>
                                        <td valign="top" id="tdFacebookUsers" style="display:none" >
                                            <div id="fbgrid" style="overflow : auto; height : 380px; "><br />
                                                <input <?php echo $selected_fb_users==1 ?  "checked" : "" ?> onclick="ShowFbGroup()" type="radio" class='els' id="rdAll" name ="drpfbgroup" value="1" />&nbsp;<?php echo ALL_FACEBOOK_USERS ?> &nbsp; <input <?php echo $selected_fb_users==2 ?  "checked" : "" ?>  onclick="ShowFbGroup()" name="drpfbgroup" type="radio" class="els" id="rdSelectedGroup" value="2" />&nbsp;<?php echo SELECT_USERS ?>
                                                <table id="tblFBList">
                                                    <tr>
                                                        <td>
                                                            <hr />
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <td >
                                                            <table>
                                                                <tr>
                                                                    <td><?php echo EMAIL ?> : </td>
                                                                    <td valign="bottom"><input class="form-control input-small inline" id="txtEmail" name="txtEmail" type="text" value="" /></td>
                                                                    <td valign="top" > <input class="btn btn-primary" onclick="AddFacebookUser()" type="button" value="<?php echo ADD ?>" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <select id="drpFBUsers" name="drpFBUsers[]" style="width:100%;height:210px" multiple="true">
                                                                            <?php echo $fb_user_options ?>

                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                 <tr>
                                                                    <td colspan="3">
                                                                        <input class="btn btn-primary" onclick="RemoveFacebookUser()" type="button" value="<?php echo REMOVE ?>" />
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>

                                         <td valign="top" id="tdLDAPUsers" style="display:none" >
                                            <div id="ldgrid" style="overflow : auto; height : 380px; "><br />

                                                <div class="tabbable"> 
                                                <ul class="nav nav-tabs">
                                                  <li class="active"><a href="#mtab1l_ldap" data-toggle="tab"><?php echo ADDED_LDAP_USERS ?></a></li>
                                                  <li><a href="#mtab2l_ldap" data-toggle="tab"><?php echo NEW_LDAP_USERS ?></a></li>                     
                                                </ul>
                                                 <div class="tab-content">
                                                     <div class="tab-pane active" id="mtab1l_ldap" ><div id="div_grid_ldap" style="overflow : auto; height : 380px; "><?php echo $ldap_grid_html ?></div></div>
                                                     <div class="tab-pane" id="mtab2l_ldap" >

                                                <input <?php echo $selected_ldap_users==1 ?  "checked" : "" ?> onclick="ShowLDAPGroup()" type="radio" class='els' id="rdAllLDAP" name ="drpldapgroup" value="1" />&nbsp;<?php echo ALL_LDAP_USERS ?> &nbsp; <input <?php echo $selected_ldap_users==2 ?  "checked" : "" ?>  onclick="ShowLDAPGroup()" name="drpldapgroup" type="radio" class='els' id="rdSelectedGroupLDAP" value="2" />&nbsp;<?php echo SELECT_USERS ?>
                                                <table id="tblLDAPList">
                                                    <tr>
                                                        <td>
                                                            <hr />
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <td >
                                                            <table>
                                                                <tr>
                                                                    <td><?php echo EMAIL ?> : </td>
                                                                    <td valign="bottom"><input class="form-control input-small inline" id="txtEmailLDAP" name="txtEmailLDAP" type="text" value="" /></td>
                                                                    <td valign="top" > <input class="btn btn-primary" onclick="AddLDAPUser()" type="button" value="<?php echo ADD ?>" /></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3">
                                                                        <select id="drpLDAPUsers" name="drpLDAPUsers[]" style="width:100%;height:210px" multiple="true">
                                                                            <?php echo $ldap_user_options ?>

                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                                 <tr>
                                                                    <td colspan="3">
                                                                        <input class="btn btn-primary" onclick="RemoveLDAPUser()" type="button" value="<?php echo REMOVE ?>" />
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </table>
                                                </div>
                                                 </div>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                </table>
</span>
                        </div>
                        
                    </div>
                  </div>
                
        
                            
          </td>
          
        </tr>
   
    </table>
    
              
    
    
    <hr />
    <table width="900px" border="0">  
        <tr>
            <td width="150px"><?php echo ASG_IMAGE ?> :</td>
            <td><?php echo $img_thumb ; ?><br /><input name="asg_image" accept="image/jpeg" type="file"> </td>
        </tr>
        <tr>
            <td width="150px"><?php echo SHORT_DESCRIPTION ?> :</td>
            <td><textarea style="width:400px;height:80px" id="txtSHD"  name="txtSHD"><?php echo util::GetData("txtShortDesc") ?></textarea></td>
        </tr>
    <tr>
        <td width="150px"><?php echo SHOW_INTRO ?> : </td>
        <td ><input type="CHECKBOX" onclick="chkShowIntro_onclick()" id="chkShowIntro" name="chkShowIntro" <?php echo util::GetData("chkShowIntro") ?>  /></td>
    </tr>
    <tr id="trEditor">
        <td><?php echo INTRO_TEXT ?> :</td>
        <td >
            <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10" ><?php echo $txtIntroText ?></textarea>
        </td>
    </tr>
        
    </table>
    <br>
    <hr>
    <table>
        <tr>
            <td><input class="btn green" onclick="return CheckForm()" style="width:100px" type="submit" id="btnSave" name="btnSave" value="<?php echo SAVE ?>" /><input class="btn green" style="width:100px;display:none" type="button" id="btnWait" name="btnWait" value="<?php echo WAIT ?>" /></td>
            <td>&nbsp;<input class="btn green" onclick="javascript:window.location.href='index.php?module=assignments'" style="width:100px" type="button" id="btnCancel" name="btnCancel" value="<?php echo CANCEL ?>" /></td>
        </tr>
    </table>
    
 
    <table id="tblQB"  style="width:1150px;display:none" cellpadding="5" cellspacing="5" border="0" >
    <tr>
        <td bgcolor="silver">                      
        </td>
        <td bgcolor="silver" align="right"><img onclick ="CloseQB()" style="width:30px;cursor:pointer" src="i/icons/success.png" ></td>
    </tr>
    <tr>
        <td bgcolor="silver" colspan="2">
            <table style="width:100%">
                <tr>
                    <td style="width:85%"><div id="divQstBank" style="overflow : auto; height : 380px; " ><?php echo $divQstBank; ?></div></td>
                    <td valign="top">
                        <div id="divFilterlist" style="overflow : auto; height : 380px; " >
                        <font color="white">&nbsp;<?php echo CATS ?></font><br/>
                         <table border="0" >
                           
                                <?php $ids="0" ; for($i=0;$i<sizeof($results);$i++) { $ids.=",".$results[$i]["id"] ?>
                                <tr>
                                    <td valign="top"><input type="checkbox" onclick="FilterQB(<?php echo $results[$i]["id"] ?>)" id="chkCat<?php echo $results[$i]["id"] ?>" name="chkCat<?php echo $results[$i]["id"] ?>" checked /></td>
                                    <td valign="bottom"><?php echo $results[$i]["cat_name"] ?> </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td valign="top"><input type="checkbox" onclick="FilterQB(0)" name="chkCat0" id="chkCat0" checked /></td>
                                    <td valign="bottom"><?php echo OTHERS ?> </td>
                                </tr>
                          </table>                        
                        <input type="hidden" name="txtCats" id="txtCats" value="<?php echo $ids ?>" /> 
                        <hr />
                        <font color="white">&nbsp;<?php echo SUBJECTS ?></font><br/>
                         <table border="0" >
                           
                                <?php $ids="-1" ; for($i=0;$i<sizeof($subject_results);$i++) { $ids.=",".$subject_results[$i]["id"] ?>
                                <tr>
                                    <td valign="top"><input type="checkbox" onclick="FilterQB(<?php echo $subject_results[$i]["id"] ?>)" id="chkSub<?php echo $subject_results[$i]["id"] ?>" name="chkSub<?php echo $subject_results[$i]["id"] ?>" checked /></td>
                                    <td valign="bottom"><?php echo $subject_results[$i]["subject_name"] ?> </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td valign="top"><input type="checkbox" onclick="FilterQB(-1)" name="chkSub-1" id="chkSub-1" checked /></td>
                                    <td valign="bottom"><?php echo OTHERS ?> </td>
                                </tr>
                          </table>                        
                        <input type="hidden" name="txtSubjects" id="txtSubjects" value="<?php echo $ids ?>" /> 
                        </div>
                    </td>
                </tr>
            </table>
            
        </td>      
    </tr>
    </table>
    
</form>

<script language='javascript'>
function extgrid_chkgroups_onclick(id)
{                
    LoadUsers();    
}
function LoadUsers()
{
    
     var groups_arr = grd_get_checkboxes(document.getElementById('form1'), "chk_group", false);     
     
     var id = querySt("id") !="-1" ? "&id="+querySt("id") : "";  
     
     $.post("index.php?module=add_assignment"+id+"&users=1", {  ajax: "yes", load_users : "yes", chkboxes : groups_arr  },
           function(data){                                                   
                document.getElementById('div_grid').innerHTML=data.div_grid;                   
                document.getElementById('div_grid_imp').innerHTML=data.div_grid_imp;                   
                document.getElementById('div_grid_ldap').innerHTML=data.div_grid_ldap;                   
                drpIsRandom_onchange();                 
            } , "json");
}

function chk_group_onclick()
{
    LoadUsers();     
}

var group_chk = Array();

function EnableDiffBoxes(box_id,enabled)
{        
    if((box_id==2 || box_id==3) && ResultsBy=="2" )
    {
        enabled=false;
    }
    var diff_results = $("#hdnDiffs").val().split(",");
    var quizzes_arr = grd_get_checkboxes(document.getElementById("form1"),"chk_diffs","");	

    for(var y = 0; y<diff_results.length;y++)
    {        
        if(diff_results[y]=="") break;
        for(var o = 0; o<quizzes_arr.length;o++)
        {
            var box_text = "txtDiffLevel";
            if(box_id==2) box_text = "txtDiffPoint";
            else if(box_id==3) box_text = "txtPenPoint";
            
            if(enabled) $("#"+box_text+"_"+quizzes_arr[o]+"_"+diff_results[y]).removeAttr('disabled');
            else $("#"+box_text+"_"+quizzes_arr[o]+"_"+diff_results[y]).attr('disabled', 'disabled');
        }                    
    }
}

function CalculatePenalties_OnChange()
{
    var enabled = $("#drpCalcPen").val() == "0" || $("#drpCalcPen").val() == "1" ? false : true;
    EnableDiffBoxes(3,enabled);
}

</script>

<script language="javascript">  
    drpResultsBy_onchange();
    ChangeCat();       
    <?php if(!isset($_GET['id'])) { ?> 
        ShowOptions(); 
        drpIsRandom_onchange(); 
        FreezeStatus();
    <?php } ?>    
    drpAllowBack_OnChange();
    chkShowIntro_onclick();
    ShowFbGroup();
    ShowLDAPGroup();
    if($("#drpIsRandom").val()=="2") drpVariants_onchange();    
    
    SetAnsCalcMode();
    
    <?php if($id!=-1 && count($selected_quiz_ids)>1) { ?> 
        
        var myquizlist = Array(<?php echo $selected_quiz_ids_js ?>);        
        LoadDiffLevelsReal(myquizlist);
    
    <?php } else if($id!=-1 && count($selected_quiz_ids)==1) { ?>  
        var myquizlist = Array();    
        myquizlist[0]=<?php echo $selected_quiz_ids_js ?>;
        LoadDiffLevelsReal(myquizlist);
    <?php } ?>                
        
</script>


<script type="text/javascript" src="lib2/datepicker2/jquery.datetimepicker.js"></script>
<script type="text/javascript">    
$('#datetimepicker').datetimepicker()
	.datetimepicker({value:'2015/04/15 18:03',step:10});

$('#datetimepicker_start').datetimepicker({    
       dayOfWeekStart : 1
});
$('#datetimepicker_end').datetimepicker({    
       dayOfWeekStart : 1
});
</script>

   <table id="test_div" style="display: none;background-color:#F9DD93"  width="610px">
        <tr>
            <td colspan=2 align=right><a href="#" border="0" onclick="close_window()"><img src="style/i/close_button.gif" /></a></td>
        </tr>
        <tr>
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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button id="btnModClose2" type="button" class="close" data-dismiss="modal" aria-label="Close"><span  aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo L_SETTINGS ?></h4>
      </div>
      <div class="modal-body">
          <div id="idModContent1"></div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnModClose" class="btn btn-default" data-dismiss="modal"><?php echo CLOSE ?></button>
        <button type="button" onclick="modal_save_click()" class="btn btn-primary"><?php echo SAVE ?></button>
      </div>
    </div>
  </div>
</div>