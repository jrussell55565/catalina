<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("tickets");
    access::has("view_tickets",2);

    require "extgrid.php";
    require "db/tickets_db.php";
    require "db/users_db.php";
    require "lib/libmail.php";    
    require "lib/rtemplates.php";    
    require "lib/tickets_util.php";

    $chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chk_tick\",\"this.checked\")'>";
    $hedaers = array( E_SUBJECT ,CREATED_BY, ADDED_DATE,  STATUS, CATEGORY, PRIORITY, ASSIGNED_TO,"&nbsp;");
    $columns = array("t_subject"=>"text","cr_full_name"=>"text","inserted_date"=>"text","StatusName"=>"text","CatName"=>"text","PriorityName"=>"text","tech_full_name"=>"text");    
    if(!$mobile) array_unshift($hedaers, $chk_all_html); 
    $url = "index.php?module=tickets";
    $grd = new extgrid($hedaers,$columns, $url);
    $grd->sort_headers = array(E_SUBJECT=>"t_subject",CREATED_BY=>"u.FullName", ADDED_DATE=>"inserted_date",STATUS=>"ds.value_text",CATEGORY=>"dc.value_text",PRIORITY=>"dp.value_text",ASSIGNED_TO=>"tu.FullName");
    $grd->exp_headers = array(E_SUBJECT ,CREATED_BY, ADDED_DATE, STATUS, CATEGORY, PRIORITY, ASSIGNED_TO);
    $grd->exp_columns = $columns;
    $grd->edit_link="index.php?module=add_edit_ticket";
    $grd->id_column="id";
    $grd->edit_id="tx_id";
    $grd->column_override = array("t_subject"=>"subject_override", "StatusName"=>"status_override", "PriorityName"=>"priority_override");
    $grd->delete=false;
    $grd->auto_id=false;
     if(!$mobile) $grd->checkbox = true;
    $grd->unread_enabled=true;
    $grd->chk_class="chk_tick";
    $grd->empty_data_text=NO_TICKETS;
    $grd->remember_checkbox=false;
    
    
    function status_override($row)
    {
        global $STATUSES;
        return $STATUSES[$row['StatusName']];
    }
    
    function priority_override($row)
    {
        global $PRIORITIES;
        return $PRIORITIES[$row['PriorityName']];
    }
 
    if(!access::has("edit_ticket")) $grd->edit_text = "";
   
    if(isset($_POST['pcommand']) && !empty($_POST['chkboxes']))
    {        
        
        $chkboxes = $_POST['chkboxes'];
       
        if($_POST['command']=="delete" && access::has("delete_ticket"))
        {            
            for($i=0;$i<count($chkboxes);$i++)
            {
                tickets_util::DeleteTicket(util::GetInt($chkboxes[$i]));
            }
        }
        else if($_POST['command']=="unread" && access::has("unread_ticket"))
        {
            for($i=0;$i<count($chkboxes);$i++)
            {
                //orm::Update("tickets", array("viewed"=>"0") ,array("id"=>$chkboxes[$i]));
                tickets_util::MarkTicketAfterView(access::UserInfo()->user_id, util::GetInt($chkboxes[$i]), 0);
            }
        }
        else if($_POST['command']=="close" && access::has("close_ticket"))
        {
            for($i=0;$i<count($chkboxes);$i++)
            {
                tickets_util::CloseTicket(util::GetInt($chkboxes[$i])); 
            }
        }
        else if($_POST['command']=="assign" && access::has("assign_ticket"))
        {
            for($i=0;$i<count($chkboxes);$i++)
            {
                $tech_user_id = $_POST['tech'];
                if(!is_numeric($tech_user_id)) exit();
                tickets_util::AssignTicket($tech_user_id, util::GetInt($chkboxes[$i]));
            }
            
        }
            
    }
    
    $views_list = db::GetResultsAsArray(tickets_db::GetRoleTicketViews(access::UserInfo()->role_id));    
            
    //unset($_SESSION["sqland".$grd->page_name]);
    $sql_and = "";  
    if(isset($_POST['view_change']))
    {
        $view_id = $_POST['view'];
        $selected_view = $view_id;
        if(!is_numeric($view_id)) exit();
        
        unset($_SESSION["p".$grd->page_name]);
        $sql_and = tickets_util::GetTicketViewQuery($view_id);                
        $_SESSION["sqland".$grd->page_name] = $sql_and;
        $_SESSION["sql_sv".$grd->page_name] = $selected_view;
    }
    else if (isset($_SESSION["sqland".$grd->page_name]))
    {
        $sql_and = $_SESSION["sqland".$grd->page_name];
        $selected_view = $_SESSION["sql_sv".$grd->page_name];
    }
    else
    {
        $view_id = $views_list[0]['id'];       
        $selected_view = $view_id;
        unset($_SESSION["p".$grd->page_name]);
        $sql_and = tickets_util::GetTicketViewQuery($view_id);        
        $_SESSION["sqland".$grd->page_name] = $sql_and;
        $_SESSION["sql_sv".$grd->page_name] = $selected_view;
    }
    
    function subject_override($row)
    {        
        if(isset($_GET['expgrid'])) return $row['t_subject'];
        $class = "ticket_subject_unread";
        if($row['viewed']=="1")  $class = "ticket_subject_read";
        $subject = htmlspecialchars($row['t_subject']);        
       // return strlen($subject);
        $subject = util::GetShortText($subject);
        $link = "<a class=$class href='?module=read_ticket&id=".$row['id']."'>".$subject."</a>";           
        return $link;
    }
     
    $grd->default_sort = "tx.id desc";
    $query = tickets_db::GetTicketsQuery($sql_and,false, $grd->GetSortQuery());   
   
    //$grd->search= access::UserInfo()->access_type == 1 ? false :  true;
    $grd->search = true;
    $grd->DrowTable($query);        
    $grid_html = $grd->table;
    
    $query = orm::GetSelectQuery("v_all_users", array(), array("is_technican"=>1, "system_row"=>array('-1','<>')), "");
    $tech_options = webcontrols::GetOptions(db::exec_sql($query), "UserID", "NAME;Surname", -1, true);  
    $ticket_view_options = webcontrols::GetArrayOptions($views_list, "id", "view_name", $selected_view, false, $TICKET_VIEWS);

    $search_html = $grd->DrowSearch(array(SUBJECT, BODY),array("t_subject", "t_body"));

    if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }
    
    if(isset($_GET["expgrid"]))
    {        
        $grd->Export();
    }

    function desc_func()
    {
        return TICKETS;
    }

?>
