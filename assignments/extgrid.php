<?php
    
    class extgrid
    {
        var $id_column = "id";
        var $delete_id_column="";
        var $page_name = "";
        var $grid_control_name ="div_grid";

        var $table = "<table  border=0 class=\"table table-striped table-condensed table-bordered [tblid]\"  id='table-[tblid]' ><tbody class=\"list\">";
        var $edit = true;
        var $edit_attr="";        
        var $delete = true;
        var $edit_text = EDIT;
        var $edit_link ="";
        var $edit_id ="id";
        var $delete_text = DELETE;
        var $checkbox = false;
        var $radiobutton = false;
        var $auto_add_empty_hedaers=true;                

        var $add_links = false;
        var $links ;
        var $jslinks ;
        var $id_links ;
        var $id_link_direction = ArrDirection::KeyFirst;
        var $id_link_key="id";
        var $id_link_checks;
        var $commands;
        var $commands_direction = ArrDirection::KeyFirst;
       

        var $process_id = 0;

        var $message = ARE_YOU_SURE;

        var $_columns ;
        var $_headers;
        var $_column_details;
        var $identity = "";
        var $selected_ids=array();

        var $process_command_override = "";
        var $process_commands_override = array();
        var $edit_attr_override = "";
        var $edit_link_override = "";
        var $delete_link_override = "";
        var $process_html_command="";
        var $empty_data_text=NO_RECORDS;
        var $column_override = array();
        var $auto_id = false;
	var $chk_class = "chk";
		
	var $search_headers;
	var $search_columns;
             
	var $main_table = "";
	var $auto_perform_commands = true;
	var $auto_delete = false;
	var $search = false;
        var $search_mode = 1;
        var $PAGING = PAGING;
        
        var $modify_system_rows = true;
        var $system_column = "system_row";
        var $edit_enabled_system_rows=false;
	var $mobile = false;
	var $mobile_grid = false;
        var $auto_detect_mobile = true;
        
        var $export_mode = false;   
        var $exp_headers;
        var $exp_columns;
        var $exp_enabled = true;        
        var $default_sort="";
        var $sort_headers ;
        var $chk_attrs = "";
        var $row_info_table = "";
        var $remember_checkboxes=true;
        
        var $edit_link_adds = array();
        var $edit_link_adds_not_include = array();
		
        
      //  var $on_dataitem_bind = "";

        public function SetEdit($value)
        {
            $edit = $value;
        }

        public function SetDelete($value)
        {
            $delete = $value;
        }

        public function extgrid($headers, $columns, $page="", $table_id = "3")
        {
            /*
            if($this->auto_add_empty_hedaers)
            {
                if($this->delete==true) $headers[]= "&nbsp;";
                if($this->edit==true) $headers[]= "&nbsp;";
            }
             * 
             */
            
            if($page=="") $page=util::GetCurrentUrl ();
            
            $this->_columns = $columns;
            $this->_headers = $headers;
            $this->page_name= $page;
            
            $this->table = str_replace("[tblid]", $table_id, $this->table);
			
            $this->AutoDetectMobile();
            $this->DetectMode();
        }	
        
        var $mode = 1;
        public function DetectMode()
        {            
            if(isset($_GET[$this->edit_id])) $this->mode = 2;
        }
        
        public function AddHeader()
        {            
            $this->table.="<tr class=c_list_head>";                        
            $i = 0;
            foreach($this->_headers as $header)
            {
                $header_id = "gridhead".$this->identity.$i;
       
                if(isset($this->_column_details[$header]))
                {
                    $cd = $this->_column_details[$header];
                    $header_id=$cd->header_column_id;
                 
                }
                if(sizeof($this->sort_headers)>0 && isset($this->sort_headers[$header]))
                {
                   $sort_header = $this->sort_headers[$header];                  
                   $header="<b><u><a style='cursor:pointer' onclick='jsSortGrid(\"$sort_header\",\"$this->page_name\",\"$this->grid_control_name\")'>$header</a></u></b>";              
                }
                $this->table.="<td id=\"$header_id\" class=\"$header_id\">&nbsp;".$header."</td>";
                $i++;
            }            
            $this->table.="</tr>";
        }
        
        
	var $search_box_size= "180px";
	public function DrowSearch($search_headers,$search_columns)
	{	
            $textbox_style = "";
            if($this->mobile==true)
            {
                    $textbox_style= "style='width:100px'";
            }
            else $textbox_style= "style='width:".$this->search_box_size."'";

            if(count($search_headers)==0)
            {
                    $this->search_headers=$this->_headers;
                    $this->search_columns=$this->_columns;
            }
            else
            {
                    $this->search_headers=$search_headers;
                    $this->search_columns=$search_columns;		
            }			

            $search_html="<form action=\"#\" name=\"frmSearch\" id=\"frmSearch\"><table cellpadding=0 cellspacing=0 border=0><tr>";
            foreach($this->search_headers as $header)
            {
                    $search_html.="<td class=c_list_item>$header</td>";
            }
            $search_html.="<td>&nbsp;</td></tr><tr>";
            $send_data="";
            foreach($this->search_columns as $key=>$value)
            {
                    $search_html.="<td><input class='form-control input-small inline' $textbox_style type=text id=txt$value name=txt$value /></td>";	
                    $send_data.=",txt$value";                                
            }
            if(strlen($send_data)>0) $send_data = substr ($send_data, 1);

            $btnall_display = isset($_SESSION[$this->page_name]) ? "" : "";
            $search_html.="<td valign=top>&nbsp;<input  class=\"btn green\" type=button onclick='javascript:grd_search(\"".$this->page_name."\",\"".$this->grid_control_name."\",\"$send_data\")' value='".SEARCH."'>&nbsp;<input  type=button class=\"btn green\" onclick='grd_show_all(\"".$this->page_name."\",\"".$this->grid_control_name."\",\"$send_data\")' style='display:".$btnall_display."' value='".SHOW_ALL."'></td></tr>";
            $search_html.="</table><br></form>";
            return $search_html;
	}
		
        var $search_arr = array();
        public function SearchGrd($query)
	{			
            
            if($this->IsClickedBtn("grd_show_all") && $_POST['mypage']==$this->page_name ) unset($_SESSION[$this->page_name]);

            if(!isset($_POST['search_grd'])) 
            {                                                            
                    if(!isset($_SESSION[$this->page_name])) return str_replace("[{where}]","", $query);		
                    else 
                    {
                        return str_replace("[{where}]" ,$_SESSION[$this->page_name], $query);
                        //return $_SESSION[$this->page_name];
                    }
            }		                                                                        

            $search_grd = $_POST['search_grd'];								
            $perfs = explode("&", $search_grd);

            $where = "";

            foreach($perfs as $perf) 
            {
                            $perf_key_values = explode("=", $perf);
                            $key = urldecode($perf_key_values[0]);
                            $value = urldecode($perf_key_values[1]);	

                            if(trim($value)!="")
                            {
                                    $key = isset($this->search_arr[$key]) ? $this->search_arr[$key] : $key;
                                    
                                    if($this->search_mode==1) $where_mode="'".trim($value)."%'";
                                    if($this->search_mode==2) $where_mode="'%".trim($value)."%'";
                                    $where.=" and lower(".substr($key,3).") like lower($where_mode) ";	// can be added COLLATE utf8_general_ci                                                
                            }
            }

            if(isset($_POST['mypage'])) 
            {
                if($_POST['mypage']!=$this->page_name) $where="";
            }

            if($where == "" ) return str_replace("[{where}]","", $query);	


            $where_key = $this->search==true ? " and " : " where " ;
            $where = $where_key.substr($where , 4);

            $query = str_replace("[{where}]" ,$where, $query);
            //$_SESSION[$this->page_name]=$query;
            $_SESSION[$this->page_name]=$where;
            //echo $query;

           //if(!$this->search) $query="";
           // $this->table.=$query;

            return $query;                        
			
	}

        private function AutoDetectMobile()
        {
            if($this->auto_detect_mobile == true)
            {
                global $mobile ; 
                if($mobile == true )
                {
                        $this->mobile=true;
                        $this->mobile_grid=true;
                        $this->PAGING = 1;
                }
            }
        }

        var $v_s = "";
        var $v_e = "";
        var $links_colspan="";
        
        var $row_count = 0;
        
        var $add_hiddens = array();
		
        public function DrowTable($query)
        {             
            global $print;
            if(isset($_GET['expgrid']) && $this->exp_enabled==true)
            {                
                $this->export_mode=true;
                if(sizeof($this->exp_headers)>0)
                {                                     
                    $this->_headers = $this->exp_headers;
                    $this->_columns = $this->exp_columns;                     
                }
            }
            
            if($this->mobile) $this->table="<table class=tblGrid border=1 class=\"table table-striped table-bordered\"  id='table-3' >";
            if($print==true) $this->table="<table class=tblGrid  border=1 id='table-4' >";
            
            if($this->mobile_grid==true)
            {
		$this->v_s = "<tr>";
		$this->v_e = "</tr>";
		$this->links_colspan = "colspan=2";                                
            }            
		 		
            if($this->mobile_grid==false) 
            {
                if($this->row_info_table!="" && $this->export_mode==false) $this->_headers[] = "&nbsp";
                $this->AddHeader();
            }
			
            $this->CheckCommands();
            $query = $this->SearchGrd($query);
            
            $query_grd = $this->export_mode==false ? $this->GetPagedQuery($query) : $query;
 
            $rows = db::exec_sql($query_grd);
            $rows_count = db::num_rows($rows);
            
            $found = false;
            
            $i = 1;

            while($row=db::fetch($rows))
            {         
                $system_row = 0 ;
                if($this->modify_system_rows==false && $row[$this->system_column]!="0")
                {
                    $system_row = 1;
                }
                $this->table.="<tr >";
                
                if($this->export_mode==false) $this->AddCheckbox($row);
                if($this->export_mode==false) $this->AddRadiobutton($row);
                
                if($this->auto_id==true && $this->mobile_grid==false && $this->export_mode==false)
                {
                    $this->table.="<td class=grd_auto_id>$i</td>";
                }
                $w=0;				
                foreach($this->_columns as $key=>$value)
                {	
                    $header_html = "";
                    if($this->mobile_grid)
                    {
                        if($this->auto_id==true && $this->mobile_grid==true && $w==0) 
                        {
                                array_shift($this->_headers);		
                        }

                        $header_id = "gridhead".$this->identity.$w;

                        if(isset($this->_column_details[$this->_headers[$w]]))
                        {
                                $cd = $this->_column_details[$this->_headers[$w]];
                                $header_id=$cd->header_column_id;

                        }							
                        $header_html .="<td  id=\"$header_id\" class=\"$header_id\">&nbsp;".$this->_headers[$w]."</td>";

                    }
                    $w++;
                    $column_value = isset($row[$key]) ? $row[$key] : "";
                    
                    $this->table.="$header_html<td class=\"c_list_item$w $key\" id=griditem>&nbsp;".trim($this->FormatColumn($key,$value,$column_value,$row))."&nbsp;</td>".$this->v_e.$this->v_s;;
                  
                }
             
                if($this->export_mode==false)
                {                    
                    $this->AddLinks();
                    $this->AddJsLinks($row);
                    $this->AddIdLinks($row);
                    $this->ProcessCommands($row);
                    $this->ProcessHTML($row);
                    $this->ProcessEdit($row,$system_row);
                    $this->ProcessDelete($row,$system_row);				
                }
                $this->AddRowInfoButton($row);
                $this->table.="</tr>";
                $found = true;
                $i++;
                $this->row_count = $i;
            }            

            if(!$found)
            {
                $this->table.="<tr><td class=empty_data colspan=".count($this->_headers).">&nbsp;".$this->empty_data_text."</td></tr>";
            }
			
            if($this->export_mode==false) $this->table.=$this->AddPager($query);
            if($this->export_mode==false && $this->mobile==false && $rows_count!=0 && access::UserInfo()->allow_export==1 && $this->exp_enabled==true) $this->table.=$this->AddExportButtons();
            $this->table.="</tbody></table>";
            $this->table.=$this->AddRowInfo();
            $this->DrowJs();
          //  db::close_connection();

        }
		
        public function GetPagedQuery($query)
        {
                $page_number = 0;
                if(isset($_POST["hdnEventMode"]) && $_POST["hdnEventMode"]=="pager")
                {
                        $page = $_POST["hdnEventArgs"];
                        $page_number = ($page-1) * $this->PAGING;
                }

                $sql = "select * from ($query) table2 LIMIT $page_number, ".$this->PAGING;
                
                return $sql;
        }
		
        public function GetCurrentPage()
        {
                $page = 1;
                if(isset($_POST["hdnEventMode"]) && $_POST["hdnEventMode"]=="pager")
                {
                        $page = $_POST["hdnEventArgs"];
                }			
                return $page;
        }
        public function AddExportButtons()
        {
            $buttons_html = "<tr><td align=left colspan='".sizeof($this->_headers)."'><label align=right>".EXPORT_TO." <a href='".$this->page_name."&expgrid=1&exptype=2&cn=".$this->grid_control_name.util::get_url_vars()."'>HTML</a> / <a href='".$this->page_name."&expgrid=1&exptype=3&cn=".$this->grid_control_name.util::get_url_vars()."'>PDF</a> / <a href='".$this->page_name."&expgrid=1&exptype=1&cn=".$this->grid_control_name.util::get_url_vars()."'>EXCEL</a></label></td></tr>";
            return $buttons_html;

        }             
        var $page_count = 10;
        public function AddPager($query)
        {		      
                $pager_html = "";
                $results = db::exec_sql(orm::GetPagingCountQuery($query));
                $row = db::fetch($results);
                $count  = $row['page_count'];
                if($count<=$this->PAGING) return $pager_html;			

                $pager_html.="<tr><td align=right border=0 colspan='".sizeof($this->_headers)."'>".PAGES." : ";

                $paging = $this->PAGING;
                $pages = $count % $this->PAGING == 0 ? intval($count / $this->PAGING) : intval($count / $this->PAGING)+1;

                $current_page = $this->GetCurrentPage();	
                $current_page_value = $current_page == 1 ? 0 : $current_page;
                $start_i = $current_page < ($this->page_count+1) ? 1 : $current_page-$this->page_count;
                $last_i = $current_page + $this->page_count > $pages ? $pages : $current_page_value + $this->page_count;
                
                $first_page = $current_page < ($this->page_count+1) ? "" : "<a style='cursor:pointer' onclick='grd_go_to_page(1,\"".$this->page_name."\",\"".$this->grid_control_name."\")'>(...)</a>&nbsp;";
                $last_page = $current_page + $this->page_count > $pages  ? "" : "<a style='cursor:pointer' onclick='grd_go_to_page($pages,\"".$this->page_name."\",\"".$this->grid_control_name."\")'>(...)</a>&nbsp;";
                
                $pager_html.=$first_page;
                for($i=$start_i;$i<=$last_i;$i++)
                {		

                        if($i!=$current_page) { $u_start = "<u>" ; $u_end = "</u>";}
                        else {$u_start = "" ; $u_end = "";}                                                

                        $pager_html.="$u_start<a style='cursor:pointer' onclick='grd_go_to_page(".$i.",\"".$this->page_name."\",\"".$this->grid_control_name."\")'>$i</a>$u_end&nbsp;";
                        if($this->mobile_grid && $i%20==0) $pager_html.="<br />" ;
                       // else if(!$this->mobile_grid && $i%50==0) $pager_html.="<br />" ;
                }
                $pager_html.=$last_page;
                
                $pager_html.=" &nbsp; ".L_TOTAL." : $pages ".L_PAGES." ($count - ".L_RECORDS.")";
                
                $pager_html.="</td></tr>";
                return $pager_html;
        }
         /*
        public function AddPager($query)
        {		
                $pager_html = "";
                $results = db::exec_sql(orm::GetPagingCountQuery($query));
                $row = db::fetch($results);
                $count  = $row['page_count'];
                if($count<=$this->PAGING) return $pager_html;			

                $pager_html.="<tr><td align=right border=0 colspan='".sizeof($this->_headers)."'>".PAGES." : ";

                $paging = $this->PAGING;
                $pages = $count % $this->PAGING == 0 ? intval($count / $this->PAGING) : intval($count / $this->PAGING)+1;

                $current_page = $this->GetCurrentPage();			
                for($i=1;$i<=$pages;$i++)
                {		

                        if($i!=$current_page) { $u_start = "<u>" ; $u_end = "</u>";}
                        else {$u_start = "" ; $u_end = "";}

                        $pager_html.="$u_start<a style='cursor:pointer' onclick='grd_go_to_page(".$i.",\"".$this->page_name."\",\"".$this->grid_control_name."\")'>$i</a>$u_end&nbsp;";
                        if($this->mobile_grid && $i%20==0) $pager_html.="<br />" ;
                        else if(!$this->mobile_grid && $i%50==0) $pager_html.="<br />" ;
                }
                $pager_html.="</td></tr>";
                return $pager_html;
        }
       
        public function AddPager($query)
        {		
                $pager_html = "";
                $results = db::exec_sql(orm::GetPagingCountQuery($query));
                $row = db::fetch($results);
                $count  = $row['page_count'];
                if($count<=$this->PAGING) return $pager_html;			

                $pager_html.="<tr><td align=right border=0 colspan='".sizeof($this->_headers)."'>".PAGES." : ";

                $paging = $this->PAGING;
                $pages = $count % $this->PAGING == 0 ? intval($count / $this->PAGING) : intval($count / $this->PAGING)+1;

                $current_page = $this->GetCurrentPage();		
                $dropdown = "<select id='drpPPager' style='width:80px' onchange='grd_go_to_page_drp(\"".$this->page_name."\",\"".$this->grid_control_name."\")' >";                
                for($i=1;$i<=$pages;$i++)
                {		
                        $selected_page = "";
                        if($i!=$current_page) { $selected_page ="selected" ;}
                        
                        $dropdown.="<option value='".$i."' >$i</option>";

                     //   $pager_html.="$u_start<a style='cursor:pointer' onclick='grd_go_to_page(".$i.",\"".$this->page_name."\",\"".$this->grid_control_name."\")'>$i</a>$u_end&nbsp;";
                     //   if($this->mobile_grid && $i%20==0) $pager_html.="<br />" ;
                     //   else if(!$this->mobile_grid && $i%50==0) $pager_html.="<br />" ;
                }
                $dropdown.="</option>";
                $pager_html.="$dropdown</td></tr>";
                return $pager_html;
        }
       */
        private function FormatColumn($key , $format , $results , $row)
        {
            $delete_tags=true;
            if($format=="short date")
            {                
                //$results = date('F d, Y ', strtotime($results));
                $results = date('d.m.Y', strtotime($results));
            }

            if(count($this->column_override)!=0)
            {                
                if(isset($this->column_override[$key]))
                {                    
                    $override=$this->column_override[$key];
                    $results=$override($row);
                    $delete_tags=false;
                }
            }

            if($delete_tags==true) $results = strip_tags($results);
            
            return $results;
        }

        public function CheckCommands()
        {
            if($this->auto_perform_commands==false) return ;

            if($this->IsClickedBtnDelete() && $this->auto_delete==true)
            {                            
                    orm::Delete($this->main_table,array($this->id_column=>$this->process_id));
            }						
        }
		
        public function IsClickedBtnDelete()
        {
            if(isset($_POST["hdnEventMode"]) && $_POST["hdnEventMode"]=="delete")
            {
                $this->process_id=intval($_POST["hdnEventArgs"]);
                return true;
            }
            return false;
        }

        public function IsClickedBtnEdit()
        {
            if(isset($_POST["hdnEventMode"]) && $_POST["hdnEventMode"]=="edit")
            {
                $this->process_id=intval($_POST["hdnEventArgs"]);
                return true;
            }
            return false;
        }

        public function IsClickedBtn($btn)
        {
            if(isset($_POST["hdnEventMode"]) && $_POST["hdnEventMode"]==$btn)
            {
                $this->process_id=intval($_POST["hdnEventArgs"]);
                return true;
            }
            return false;
        }
        
        public function GetSortQuery()
        {
            if(isset($_POST['sort_by']) && sizeof($this->sort_headers)>0)
            {
                $sortby = db::clear($_POST['sort_by']);
                if(array_search($sortby,$this->sort_headers)==null) return $this->default_sort;
                $sort_direct = db::clear($_POST['sort_direc']);
                if($sort_direct!="asc" && $sort_direct!="desc") return $this->default_sort;
                return $sortby." ".$sort_direct;
            }
            else 
            {
                return $this->default_sort;
            }
        }

        public function DrowJs()
        {            
            $this->table.="<input type=hidden id=hdnEventArgs /><input type=hidden id=hdnEventMode /><input value='".util::GetPostData("sort_by")."' type=hidden id=hdnSortBy /><input value='".util::GetPostData("sort_direc")."' type=hidden id=hdnSortDirec />";
        }

        private function ProcessEdit($row,$system_row)
        {
            if(!$this->edit) return ;
            
            if($this->edit_id=="" || $this->edit_id=="id") $this->edit_id = $this->id_column;
                        
            $text = $system_row =="1" && $this->edit_enabled_system_rows==true ? "" : $this->edit_text;
            
            $edit_attr= $this->edit_attr;
            $edit_attr_override = $this->edit_attr_override;
            $edit_link_override = $this->edit_link_override;
            
            $edit_adds = "";
            foreach($this->edit_link_adds as $key=>$value)
            {
                $value = isset($row[$value]) ? $row[$value] : $value;
                if(!in_array($value, $this->edit_link_adds_not_include))
                $edit_adds.="&$key=".$value;
            }

            if($edit_attr_override!="")
            {
                $edit_attr=$edit_attr_override($row);
            }

            $edit_link = "<a $edit_attr href='".$this->edit_link."&id=".$row[$this->edit_id]."'>$text</a>";

            if($edit_link_override!="")
            {                 
                $edit_link=$edit_link_override($row,$edit_adds);
            }            

            $this->table.=$this->v_s."<td ".$this->links_colspan.">&nbsp;$edit_link</td>".$this->v_e;

        }

        public static function EditCommandTemplate($row,$grd)
        {
            $html = "&nbsp;<a href='".$grd->edit_link."&id=".$row[$grd->id_column]."'>$grd->edit_text</a>";
            return $html;
        }

        private function ProcessDelete($row,$system_row)
        {
            if(!$this->delete) return ;    
            
            $delete_link_override = $this->delete_link_override;                       
            
            $text = $system_row =="1" ? "" : $this->delete_text;                                    
            $id = $this->delete_id_column=="" ? $this->id_column : $this->delete_id_column;
            
            $delete_link = "<a href='javascript:jsProcessDelete(\"$this->message\",$row[$id], \"$this->page_name\", \"$this->grid_control_name\" )'>$text</a>";
            
            if($delete_link_override!="")
            {                 
               $delete_link=$delete_link_override($row,$delete_link);
            } 
            
            $this->table.=$this->v_s."<td ".$this->links_colspan.">&nbsp;$delete_link</td>".$this->v_e;

        }
              

        public static function ProcessCommandTemplate($row,$value,$key,$grd,$confirm="",$mobile_grid=false)
        {           
            $vs = $ve= $style="";
            if($mobile_grid)
            {
                $vs = "<tr>";
                $ve = "</tr>";
                $style = "colspan=2";
            }
            $process_html="";
            if(is_array($value))
            {
                for($i=0;$i<count($value);$i++)
                {
                    $val = $value[$i];
                    $ky = $key[$i];
                    $process_html.= " / <a href='javascript:jsProcessCommand(".$row[$grd->id_column].", \"$grd->page_name\", \"$grd->grid_control_name\", \"$val\",\"$confirm\" )'>$ky</a>";                    
                }
                $process_html = substr($process_html,2);
            }
            else
            {
                $process_html = "<a href='javascript:jsProcessCommand(".$row[$grd->id_column].", \"$grd->page_name\", \"$grd->grid_control_name\", \"$value\",\"$confirm\" )'>$key</a>";                    
            }
            
            $html = "$vs<td $style >&nbsp; $process_html</td>$ve";
            return $html;
        }
        
        public static function EmptyColumn($column_text="")
        {
            if($column_text=="") $column_text="&nbsp;";
            return "<td>$column_text</td>";
        }

        private function ProcessHTML($row)
        {
            if($this->process_html_command=="") return ;

            $process_html_command = $this->process_html_command;
            $this->table.=$process_html_command($row);

        }
      
        private function ProcessCommands($row)
        {
            if(count($this->commands)==0) return ;

            $process_command_override = $this->process_command_override;
            $process_commands_override = $this->process_commands_override;
            //$process_command_override();

            $id = $this->id_column;
            foreach($this->commands as $key=>$value)
            {
                 $k = $key;
                 $v = $value;
                 if($this->commands_direction==ArrDirection::ValueFirst) 
                 {
                     $k = $value;
                     $v = $key;
                 }
                 if($process_command_override!="")
                 {
                     $this->table.=$process_command_override($row);
                 }
                 else if(isset($process_commands_override[$value])) 
                 {                         
                         $function_name = $process_commands_override[$value];                         
                         $this->table.=$function_name($row);
                 }                 
                 else
                 {                                  
                     $this->table.=$this->v_s."<td ".$this->links_colspan." ><a href='javascript:jsProcessCommand($row[$id], \"$this->page_name\", \"$this->grid_control_name\", \"$v\",\"\" )'>&nbsp;$k</a></td>".$this->v_e;
                 }
            }
        }

        var $register_checkbox_click;
        var $chk_status=false;
        var $chk_arr_str = "chkboxes";
        var $id_checkbox = "";
        var $checkbox_add_func;
        var $checkbox_all_checked=false;
        var $checkbox_width = '30px';
        private function AddCheckbox($row)
        {
            if(!$this->checkbox) return ;
            
            $values = "";
            if($this->id_checkbox!="") {
            $chks=explode(",", $this->id_checkbox);            
            for($z=0;$z<count($chks);$z++)
            {
                $values.=$row[$chks[$z]].",";
            }
            }
            if($values=="") $values = $row[$this->id_column];            
                        
            $checked = "";            
            if(in_array($values, $this->selected_ids, true) || $this->chk_status==true)
            {
                $checked="checked";                                
            }                        
            
            if($this->checkbox_all_checked) $checked="checked"; 
                
            if(isset($_POST[$this->chk_arr_str]) && $this->remember_checkboxes==true )
            {
                  if(in_array($values, $_POST[$this->chk_arr_str], true))
                  {
                        $checked="checked";                
                  }   
                  //else $checked=""; 
            }

            $identity = $this->identity;
            $chk_class = $this->chk_class;
            
            $onclick = "";
            if($this->register_checkbox_click==true) $onclick = "onclick='extgrid_chk".$identity."_onclick(".$values.")'";
            
            $check_add="";
            $checkbox_add_func = $this->checkbox_add_func;            

            if($checkbox_add_func!="")
            {
                $check_add=$checkbox_add_func($row);
            }
            
            $this->table.=$this->v_s."<td style='width:".$this->checkbox_width."' ".$this->links_colspan.">&nbsp;<input ".$this->chk_attrs." $checked type=checkbox $onclick class='".$chk_class." els' id='chkgrd".$identity.$values."' name=chkgrd".$identity."[] value='".$values."' />$check_add</td>".$this->v_e;
        }
        
        var $selected_rd_id = -1;
        var $register_rd_click;
        var $select_first_rd = false;
        private function AddRadiobutton($row)
        {
            if(!$this->radiobutton) return ;
            
            $checked = "";
            if($this->selected_rd_id==$row[$this->id_column]) $checked="checked";
                
            if($this->select_first_rd==true && $this->row_count==0 && $this->mode==1 ) $checked="checked";
            
            $identity = $this->identity;
            $chk_class = $this->chk_class;
            $onclick ="";
            if($this->register_rd_click==true) $onclick = "onclick='extgrid_rd".$identity."_onclick(".$row[$this->id_column].")'";
            
            $this->table.=$this->v_s."<td ".$this->links_colspan.">&nbsp;<input ".$this->chk_attrs." $checked type=radio $onclick class=".$chk_class." id=rd".$identity.$row[$this->id_column]." name=rd".$identity." value=".$row[$this->id_column]." /></td>".$this->v_e;
        }
        
        private function AddLinks()
        {
            if(count($this->links)==0) return ;

            foreach($this->links as $key=>$value)
            {
                 $this->table.=$this->v_s."<td ".$this->links_colspan.">&nbsp;<a href='$value'>$key</a></td>".$this->v_e;
            }
        }

        private function AddJsLinks($row)
        {
            if(count($this->jslinks)==0) return ;

            foreach($this->jslinks as $key=>$value)
            {
                 $value=str_replace("[id]", $row[$this->id_column], $value);
                 $this->table.=$this->v_s."<td ".$this->links_colspan.">&nbsp;<a style='cursor:pointer' onclick='$value'>$key</a></td>".$this->v_e;
            }
        }

        var $id_link_override=array();
        var $id_link_space_blanks=true;
        var $id_link_count = 0;
        private function AddIdLinks($row)
        {                    
            global $i;
            
            if(count($this->id_links)==0) return ;                       
            
            if($row[$this->id_column]=="" && $this->id_link_space_blanks==true)
            {
                    for($i=0;$i<count($this->id_links);$i++) $this->table.="<td>&nbsp;</td>";
                    return;
            }

            foreach($this->id_links as $key=>$value)
            {                
                 $k = $key;
                 $v = $value;
                 if($this->id_link_direction==ArrDirection::ValueFirst) 
                 {
                     $k = $value;
                     $v = $key;
                 }                   
                 
                 if(count($this->id_link_override)!=0)
                 {                                               
                    if(isset($this->id_link_override["L".$this->id_link_count]))
                    {      
                          
                        $override=$this->id_link_override["L".$this->id_link_count];                          
                        $k=$override($row,$k);        
                      
                    }
                 }
                 $link_text = $k;
                 $this->table.=$this->v_s."<td ".$this->links_colspan.">&nbsp;<a href='$v&".$this->id_link_key."=".urldecode($row[$this->id_column])."'>$link_text</a></td>".$this->v_e;
                 $this->id_link_count++;                 
            }
            
            $this->id_link_count=0;
        }
        
        public function Export()
        {
            if($this->exp_enabled==false || access::UserInfo()->allow_export==0) return;
            if(isset($_GET['expgrid']) && isset($_GET['exptype']))
            {                
                $type = $_GET['exptype'];
                if($type==1)
                {
                    header("Content-type: text/plain");
                    header("Content-Disposition: attachment; filename=excel.xls");
                    echo "<meta charset=\"utf-8\" />".$this->table;
                }
                else if($type==2)
                {
                    header("Content-type: text/plain");
                    header("Content-Disposition: attachment; filename=html.html");
                    echo "<meta charset=\"utf-8\" />".$this->table;
                }
                else if($type==3)
                {
                    require_once(dirname(__FILE__).'/html_to_pdf/html2pdf.class.php');
                    try
                    {
						ob_clean();
                        $content = "<html><head><meta charset=\"utf-8\" /></head><body>".$this->table."</body></html>";
                        //$content = utf8_decode($content);
                        $html2pdf = new HTML2PDF('P', 'A4', 'fr',true, 'utf-8');
                        try {
                        $html2pdf->setDefaultFont('arialunicid0'); //add this line
                        } catch(Exception $e) { }
                        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                        $html2pdf->Output('pdf.pdf');
                        header("Content-Disposition: attachment; filename=pdf.pdf");
                    }
                    catch(HTML2PDF_exception $e) {
                        echo $e;

                    }
                }
            }
        }
        
    public function LoadUserInfo($id)
        {
           // if(!isset($_POST['info_id'])) return "" ;
            
            $info_id = util::GetInt($id);
            
            $SQL = " SELECT ui.FullName iFullName, uu.FullName uFullName,rit.inserted_date iDate,rit.updated_date uDate,b.branch_name,ui.user_photo iUserPhoto, uu.user_photo uUserPhoto  ".
                   " FROM  ".$this->row_info_table." rit LEFT JOIN v_all_users ui on ui.UserID = rit.inserted_by  left join v_all_users uu on rit.updated_by=uu.UserID".
                   " LEFT JOIN branches b on b.id=rit.branch_id WHERE rit.".$this->id_column."= $info_id ".au::get_where(true, "rit");
            
            $results = db::exec_sql($SQL);
            
            if(db::num_rows($results)==0) return "";
            
            $row = db::fetch($results);
            
            $row_info = file_get_contents('modules/tmps/row_info.xml', true);
            
            $row_info = str_replace("[INSERT_INFO]", INSERT_INFORMATION, $row_info);
            $row_info = str_replace("[INSERTED_BY]", INSERTED_BY." : ".$row['iFullName'], $row_info);
            $row_info = str_replace("[INSERTED_DATE]", INSERTED_DATE." : ".$row['iDate'], $row_info);
            $row_info = str_replace("[iUserPhoto]", util::get_thumb($row['iUserPhoto']), $row_info);
            
            $row_info = str_replace("[UPDATE_INFO]", UPDATE_INFORMATION, $row_info);
            $row_info = str_replace("[UPDATED_BY]", UPDATED_BY." : ".$row['uFullName'], $row_info);
            $row_info = str_replace("[UPDATED_DATE]", UPDATED_DATE." : ".$row['uDate'], $row_info);
            $row_info = str_replace("[uUserPhoto]", util::get_thumb($row['uUserPhoto']), $row_info);
            
            $row_info = str_replace("[BRANCH_NAME]", BRANCH_NAME." : ".$row['branch_name'], $row_info); 
            
            $update_display = $row['uFullName'] =="" ? "none" : "";
            $row_info = str_replace("{update_display}", $update_display, $row_info);
            
            return $row_info;
        }
        
        public function AddRowInfo()
        {
            
            if($this->row_info_table=="" || $this->export_mode==true || $this->mobile_grid==true) return "";            	
		
            $info_identity = $this->info_identity;
            $template = "<div class=\"modal fade\" id=\"myModal$info_identity\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
                                      <div class=\"modal-dialog\">
                                            <div class=\"modal-content\">
                                              <div class=\"modal-header\">
                                                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
                                                    <h3>".ROW_INFO."</h3>
                                              </div>
                                              <div class=\"modal-body\">
                                                     <p>    
                            <div id=divRowInfo$info_identity><img src='style/i/ajax_loader.gif' /></div>
                     </p>
                                              </div>
                                              <div class=\"modal-footer\">
                                                    <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">".CLOSE."</button>								
                                              </div>
                                            </div>
                                      </div>
                                    </div>";
            
            return $template;
        }
        
        var $info_identity = "myModal";
        public function AddRowInfoButton($row)
        {
            if($this->row_info_table=="" || $this->export_mode==true || $this->mobile_grid==true) return "";
                         
            $info_identity = $this->info_identity;
            $this->table.="<td style='width:25px'> <a href=\"#myModal$info_identity\" onclick='LoadRowInfo(".$row[$this->id_column].",\"$this->page_name\", \"$info_identity\")' role=\"button\"  data-toggle=\"modal\"><img src='style/i/i2.png' /></a></td>";
        }                
        
        public static function GetModalRowTemplate($key,$value,$grd,$identity="mymdl1")
        {
            if($grd->row_info_table=="" || $grd->mobile_grid==true) return "";
            if($grd->export_mode==true) return $value;
            
            $res =  "<a href=\"#$identity\"  role=\"button\"  data-toggle=\"modal\">$key</a>";	

			$res.= "<div class=\"modal fade\" id=\"$identity\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
						  <div class=\"modal-dialog\" >
							<div class=\"modal-content\"  >
							  <div class=\"modal-header\">
								<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
								 <h3>".$key."</h3>
							  </div>
							  <div class=\"modal-body\">
								 <p>    
                                     <div id=divRowInfo$identity>$value</div>
                                  </p>
							  </div>
							  <div class=\"modal-footer\">
								<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">".CLOSE."</button>								
							  </div>
							</div>
						  </div>
						</div>";
							  
            return $res;
        }
        
        
    }
    

    
    class ArrDirection
    {
        const KeyFirst = 0;
        const ValueFirst = 1;    
    }
    
    class ColumnDetails
    {
        var $header_column_id = "";
        
        function ColumnDetails($header_column_id)
        {
            $this->$header_column_id=$header_column_id;
        }
    }

?>
