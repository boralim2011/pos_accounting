<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_group_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $user_group_id = 0;
    public $user_group_name = '';
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(User_group_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $user_group_name = $model->user_group_name;

        $this->db->select("user_group.*, (select count(*) from user u where u.user_group_id=user_group.user_group_id) as 'members', ".
            "(select count(*) from user_group where user_group_name like '%$user_group_name%') 'records' "
        );
        $this->db->like("user_group_name", "$user_group_name");
        $this->db->limit($display, $offset);
        $query = $this->db->get('user_group');

//        $sql = "select user_group.*, (select count(*) from user u where u.user_group_id=user_group.user_group_id) as 'members', ".
//               "(select count(*) from user_group where user_group_name like '%$user_group_name%') 'records' ".
//               "from user_group ".
//               "where user_group_name like '%$user_group_name%' ".
//               "limit $offset,$display";
//
//        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('User_group_model'));
        }
    }

    function get(User_group_model $model)
    {
        $this->db->where('user_group_id', $model->user_group_id);

        $result =$this->db->get('user_group');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_group_model'));
        }
    }

    function get_by_name(User_group_model $model)
    {
        $this->db->where('user_group_name', $model->user_group_name);

        $result =$this->db->get('user_group');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_group_model'));
        }
    }

    function is_exist(User_group_model $user_group)
    {
        $this->db->where('user_group_id', $user_group->user_group_id);

        $result =$this->db->get('user_group');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(User_group_model $user_group)
    {
        $this->db->where('user_group_name', $user_group->user_group_name);
        $this->db->where('user_group_id !=', $user_group->user_group_id);

        $result =$this->db->get('user_group');

        return $result && $result->num_rows()> 0;
    }

    function add(User_group_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('User group name is exist');
        }

        //for mysqli driver
        unset($model->user_group_id);

        $result=$this->db->insert('user_group', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->user_group_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(User_group_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('User group name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('User group cannot be edited');

        $this->db->where('user_group_id', $model->user_group_id);

        $result = $this->db->update('user_group', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(User_group_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('User group cannot be delete');

        $this->db->where('user_group_id', $model->user_group_id);

        $result=$this->db->delete('user_group');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(User_group_model $model)
    {
        $sql = "select user_group_id as 'id', user_group_name as 'text' from user_group where user_group_name like'%$model->user_group_name%'";
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