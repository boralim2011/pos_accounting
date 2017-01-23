<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Unit_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $unit_id = 0;
    public $unit_name = '';
    public $unit_name_kh = '';
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(Unit_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'unit_name';

        $this->db->select("unit.*, ".
            "(select count(*) from unit where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('unit');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Unit_model'));
        }
    }

    function get(Unit_model $model)
    {
        $this->db->where('unit_id', $model->unit_id);

        $result =$this->db->get('unit');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Unit_model'));
        }
    }

    function get_by_name(Unit_model $model)
    {
        $this->db->where('unit_name', $model->unit_name);

        $result =$this->db->get('unit');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Unit_model'));
        }
    }

    function is_exist(Unit_model $unit)
    {
        $this->db->where('unit_id', $unit->unit_id);

        $result =$this->db->get('unit');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Unit_model $unit)
    {
        $this->db->where('unit_name', $unit->unit_name);
        $this->db->where('unit_id !=', $unit->unit_id);

        $result =$this->db->get('unit');

        return $result && $result->num_rows()> 0;
    }

    function add(Unit_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Unit name is exist');
        }

        //for mysqli driver
        unset($model->unit_id);

        $result=$this->db->insert('unit', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->unit_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Unit_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Unit name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Unit cannot be edited');

        $this->db->where('unit_id', $model->unit_id);

        $result = $this->db->update('unit', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Unit_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Unit cannot be delete');

        $this->db->where('unit_id', $model->unit_id);

        $result=$this->db->delete('unit');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Unit_model $model)
    {
        $sql = "select unit_id as 'id', unit_name as 'text' from unit where unit_name like'%$model->unit_name%'";
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