<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Maker_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $maker_id = 0;
    public $maker_name = '';
    public $maker_name_kh = '';
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(Maker_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'maker_name';

        $this->db->select("maker.*, ".
            "(select count(*) from maker where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('maker');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Maker_model'));
        }
    }

    function get(Maker_model $model)
    {
        $this->db->where('maker_id', $model->maker_id);

        $result =$this->db->get('maker');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Maker_model'));
        }
    }

    function get_by_name(Maker_model $model)
    {
        $this->db->where('maker_name', $model->maker_name);

        $result =$this->db->get('maker');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Maker_model'));
        }
    }

    function is_exist(Maker_model $maker)
    {
        $this->db->where('maker_id', $maker->maker_id);

        $result =$this->db->get('maker');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Maker_model $maker)
    {
        $this->db->where('maker_name', $maker->maker_name);
        $this->db->where('maker_id !=', $maker->maker_id);

        $result =$this->db->get('maker');

        return $result && $result->num_rows()> 0;
    }

    function add(Maker_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Maker name is exist');
        }

        //for mysqli driver
        unset($model->maker_id);

        $result=$this->db->insert('maker', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->maker_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Maker_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Maker name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Maker cannot be edited');

        $this->db->where('maker_id', $model->maker_id);

        $result = $this->db->update('maker', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Maker_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Maker cannot be delete');

        $this->db->where('maker_id', $model->maker_id);

        $result=$this->db->delete('maker');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Maker_model $model)
    {
        $sql = "select maker_id as 'id', maker_name as 'text' from maker where maker_name like'%$model->maker_name%'";
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