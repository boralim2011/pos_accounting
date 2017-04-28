<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contact_type_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $contact_type_id = 0;
    public $contact_type_name = '';
    public $contact_type_name_kh = '';
    public $parent_id;

    function gets(Contact_type_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'contact_type_name';

        $this->db->select("contact_type.*, ".
            "(select count(*) from contact_type where $field like '%$search%') 'records' "
        );
        $this->db->from('contact_type');
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get();

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Contact_type_model'));
        }
    }

    function get(Contact_type_model $model)
    {
        $this->db->where('contact_type_id', $model->contact_type_id);

        $result =$this->db->get('contact_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Contact_type_model'));
        }
    }

    function get_by_name(Contact_type_model $model)
    {
        $this->db->where('contact_type_name', $model->contact_type_name);

        $result =$this->db->get('contact_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Contact_type_model'));
        }
    }

    function is_exist(Contact_type_model $contact_type)
    {
        $this->db->where('contact_type_id', $contact_type->contact_type_id);

        $result =$this->db->get('contact_type');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Contact_type_model $contact_type)
    {
        $this->db->where('contact_type_name', $contact_type->contact_type_name);
        $this->db->where('contact_type_id !=', $contact_type->contact_type_id);

        $result =$this->db->get('contact_type');

        return $result && $result->num_rows()> 0;
    }

    function add(Contact_type_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Contact_type name is exist');
        }

        //for mysqli driver
        unset($model->contact_type_id);

        $result=$this->db->insert('contact_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->contact_type_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Contact_type_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Contact_type name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Contact_type cannot be edited');

        $this->db->where('contact_type_id', $model->contact_type_id);

        $result = $this->db->update('contact_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Contact_type_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Contact_type cannot be delete');

        $this->db->where('contact_type_id', $model->contact_type_id);

        $result=$this->db->delete('contact_type');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Contact_type_model $model)
    {
        $sql = "select contact_type_id as 'id', contact_type_name as 'text' from contact_type where contact_type_name like'%$model->contact_type_name%'";
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