<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_role_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $user_role_id = 0;
    public $user_role_name = '';
    //public $is_deletable = 1;

    function gets(User_role_model $model)
    {
        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $user_role_name = $model->user_role_name;

        $this->db->select("user_role.*, (select count(*) from permission p where p.user_role_id=user_role.user_role_id) as 'members', ".
            "(select count(*) from user_role where user_role_name like '%$user_role_name%') 'records' "
        );
        $this->db->like("user_role_name", "$user_role_name");
        $this->db->limit($display, $offset);
        $query = $this->db->get('user_role');

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('User_role_model'));
        }
    }

    function get(User_role_model $model)
    {
        $this->db->where('user_role_id', $model->user_role_id);

        $result =$this->db->get('user_role');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_role_model'));
        }
    }

    function get_by_name(User_role_model $model)
    {
        $this->db->where('user_role_name', $model->user_role_name);

        $result =$this->db->get('user_role');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_role_model'));
        }
    }

    function is_exist(User_role_model $user_role)
    {
        $this->db->where('user_role_id', $user_role->user_role_id);

        $result =$this->db->get('user_role');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(User_role_model $user_role)
    {
        $this->db->where('user_role_name', $user_role->user_role_name);
        $this->db->where('user_role_id !=', $user_role->user_role_id);

        $result =$this->db->get('user_role');

        return $result && $result->num_rows()> 0;
    }

    function add(User_role_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('User type name is exist');
        }

        //for mysqli driver
        unset($model->user_role_id);

        $result=$this->db->insert('user_role', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->user_role_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(User_role_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('User type name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('User role cannot be edited');

        $this->db->where('user_role_id', $model->user_role_id);

        $result = $this->db->update('user_role', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(User_role_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('User type cannot be delete');

        $this->db->where('user_role_id', $model->user_role_id);

        $result=$this->db->delete('user_role');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(User_role_model $model)
    {
        $sql = "select user_role_id as 'id', user_role_name as 'text' from user_role where user_role_name like'%$model->user_role_name%'";
        $query = $this->db->query($sql);

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result());
        }
    }


}