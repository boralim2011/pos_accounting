<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Age_range_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $age_range_id = 0;
    public $age_range_name = '';
    public $age_range_name_kh = '';
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(Age_range_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'age_range_name';

        $this->db->select("age_range.*, ".
            "(select count(*) from age_range where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('age_range');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Age_range_model'));
        }
    }

    function get(Age_range_model $model)
    {
        $this->db->where('age_range_id', $model->age_range_id);

        $result =$this->db->get('age_range');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Age_range_model'));
        }
    }

    function get_by_name(Age_range_model $model)
    {
        $this->db->where('age_range_name', $model->age_range_name);

        $result =$this->db->get('age_range');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Age_range_model'));
        }
    }

    function is_exist(Age_range_model $age_range)
    {
        $this->db->where('age_range_id', $age_range->age_range_id);

        $result =$this->db->get('age_range');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Age_range_model $age_range)
    {
        $this->db->where('age_range_name', $age_range->age_range_name);
        $this->db->where('age_range_id !=', $age_range->age_range_id);

        $result =$this->db->get('age_range');

        return $result && $result->num_rows()> 0;
    }

    function add(Age_range_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Age_range name is exist');
        }

        //for mysqli driver
        unset($model->age_range_id);

        $result=$this->db->insert('age_range', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->age_range_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Age_range_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Age_range name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Age_range cannot be edited');

        $this->db->where('age_range_id', $model->age_range_id);

        $result = $this->db->update('age_range', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Age_range_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Age_range cannot be delete');

        $this->db->where('age_range_id', $model->age_range_id);

        $result=$this->db->delete('age_range');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Age_range_model $model)
    {
        $sql = "select age_range_id as 'id', age_range_name as 'text' from age_range where age_range_name like'%$model->age_range_name%'";
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