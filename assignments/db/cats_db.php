<?php

class catsDB
{
    public static function GetCatsQuery($orderby = "id desc")
    {
        $sql = "select * from cats ".au::get_where(false)." order by $orderby";           
        return $sql;
    }

    public static function DeleteCategoryById($id)
    {
        $sql = "delete from cats where id=$id ".au::get_where();         
        db::exec_sql($sql);
    }

     public static function AddNewCat($name)
    {
        $name = db::clear($name);
        $sql = "insert into cats(cat_name,branch_id,inserted_by,inserted_date) values('$name','".access::UserInfo()->branch_id."','".access::UserInfo()->user_id."','".util::Now()."')";

        db::exec_sql($sql);
    }

      public static function EditCat($name,$id)
    {
        $name = db::clear($name);
        $id = db::clear($id);
        $sql = "update cats set cat_name='$name' , updated_by=".access::UserInfo()->user_id." , updated_date='".util::Now()."' where id='$id'";      
        
        db::exec_sql($sql);
    }
}

?>
