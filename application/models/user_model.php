<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        if(!isset($this->created_date)) $this->created_date = Date('Y-m-d H:i:s', time());
    }

    public $user_id = 0;
    public $user_name;
    public $password;
    public $email;
    public $is_active = 1;
    public $is_hidden = 0;
    public $is_editable = 1;
    public $is_deletable = 1;
    public $contact_id = 0;
    public $user_group_id = 0;
    public $created_date;
    public $image = '';


    function gets(User_model $model)
    {
        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = $model->search;
        $field = isset($model->search_by)? $model->search_by: "user_name";

        $user_group_id = $model->user_group_id;
        $user_role_id = isset($model->user_role_id)? $model->user_role_id : 0;

        $sql = "SELECT u.*, ".
            "(SELECT count(*) ".
                "FROM user ".
                "JOIN user_group ON user.user_group_id = user_group.user_group_id ".
                "LEFT JOIN permission ON permission.user_id = user.user_id ".
                "WHERE user.is_hidden =0 ".
                "AND user.$field LIKE '%$search%' ".
                "AND '$user_group_id' in (0, user.user_group_id) ".
                "AND '$user_role_id' in(0, permission.user_role_id) ".
            ") 'records' ".
            "FROM user u ".
            "JOIN user_group ug ON u.user_group_id = ug.user_group_id ".
            "LEFT JOIN permission ps ON ps.user_id = u.user_id ".
            "WHERE u.is_hidden =0 ".
            "AND u.$field LIKE '%$search%' ".
            "AND '$user_group_id' in (0, u.user_group_id) ".
            "AND '$user_role_id' in(0, ps.user_role_id) ".
            "LIMIT $offset, $display"
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('User_model'));
        }

    }

    function get(User_model $user)
    {
        $this->db->select("user_group_name, user.* ")
            ->from('user')
            ->join('user_group', 'user.user_group_id = user_group.user_group_id')
            ->where('user_id', $user->user_id);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_model'));
        }
    }

    function get_permission(User_model $user)
    {
        $this->db->select("*")
            ->from('permission')
            //->join('user_group', 'user.user_group_id = user_group.user_group_id')
            ->where('user_id', $user->user_id )
            //->limit(1)
        ;
        $query = $this->db->get();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result());
        }
    }

    function get_by_name(User_model $user)
    {
        $this->db->where('user_name', $user->user_name);

        $result =$this->db->get('user');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_model'));
        }
    }

    function get_by_email(User_model $user)
    {
        $this->db->where('email', $user->email);

        $result =$this->db->get('user');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('User_model'));
        }
    }

    function is_exist(User_model $user)
    {
        $this->db->where('user_id', $user->user_id);

        $result =$this->db->get('user');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(User_model $user)
    {
        $this->db->where('user_name', $user->user_name);
        $this->db->where('user_id !=', $user->user_id);

        $result =$this->db->get('user');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_email(User_model $user)
    {
        $this->db->where('email', $user->email);
        $this->db->where('user_id !=', $user->user_id);

        $result =$this->db->get('user');

        return $result && $result->num_rows()> 0;
    }

    function add(User_model &$user)
    {
        if($this->is_exist_name($user))
        {
            return Message_result::error_message('User name is exist');
        }

        if($this->is_exist_email($user))
        {
            return Message_result::error_message('Email is exist');
        }

        $user->password = Model_base::encrypt_password($user->password);

        //for mysqli driver
        unset($user->user_id);
        if(!isset($user->created_date) || $user->created_date=='') $user->created_date = Date('Y-m-d H:i:s', time());
        if(!isset($user->contact_id) || $user->contact_id=='') unset($user->contact_id);

        $result=$this->db->insert('user', $user);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $user->user_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $user);
        }

    }

    function update(User_model $user)
    {
        if($this->is_exist_name($user))
        {
            return Message_result::error_message('User name is exist');
        }
        if($this->is_exist_email($user))
        {
            return Message_result::error_message('Email is exist');
        }

        $result = $this->get($user);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('User cannot be edited');


        unset($user->password);
        if(!isset($user->modified_date) || $user->modified_date=='') $user->modified_date = Date('Y-m-d H:i:s', time());
        if(!isset($user->contact_id) || $user->contact_id=='') unset($user->contact_id);

        $this->db->where('user_id', $user->user_id);

        $result = $this->db->update('user', $user);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $user);
        }
    }

    function delete(User_model $user)
    {
        $result = $this->get($user);

        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('User cannot be delete');

        $this->db->where('user_id', $user->user_id);

        $result=$this->db->delete('user');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$user);
        }
    }

    function activate(User_model $user)
    {
        if(!$this->is_exist($user)) return Message_result::error_message('User is not exist');

        $this->db->where('user_id', $user->user_id);

        $result = $this->db->update('user', array('is_active'=>$user->is_active));

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Update success', $user);
        }
    }

    function reset_password(User_model $user)
    {
        if(!$this->is_exist_email($user)) return Message_result::error_message('Email is not exist');

        $this->db->where('email', $user->email);

        $result = $this->db->update('user', array('password'=> Model_base::encrypt_password($user->password)));

        if(!$result )
        {
            return Message_result::error_message('Cannot reset password');
        }
        else
        {
            return Message_result::success_message('Password reset success', $user);
        }
    }

    function change_password(User_model $user)
    {
        if(!$this->is_exist($user)) return Message_result::error_message('User is not exist');

        $this->db->where('user_id', $user->user_id);

        $result = $this->db->update('user', array('password'=> Model_base::encrypt_password($user->password)));

        if(!$result )
        {
            return Message_result::error_message('Cannot change password');
        }
        else
        {
            return Message_result::success_message('Password update success', $user);
        }
    }

    function login(User_model $user)
    {

        $this->db->select("user_group_name, user.* ")
            ->from('user')
            ->join('user_group', 'user.user_group_id = user_group.user_group_id');
        $this->db->where('email', $user->email);
        $this->db->where('password', Model_base::encrypt_password($user->password));

        $result = $this->db->get();

        if($result->num_rows()== 0) return Message_result::error_message('Invalid email or password');

        $result = $result->first_row('User_model');

        if($result->is_active==0) return Message_result::error_message('User is not active');

        return Message_result::success_message('', $result);
    }

    function get_combobox_items(User_model $model)
    {
        $sql = "select user_id as 'id', user_name as 'text' from user where user_name like'%$model->user_name%'";
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