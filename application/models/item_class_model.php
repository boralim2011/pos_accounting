<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Item_class_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $item_class_id = 0;
    public $item_class_name = '';
    public $item_class_name_kh = '';
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(Item_class_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'item_class_name';

        $this->db->select("item_class.*, ".
            "(select count(*) from item_class where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('item_class');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Item_class_model'));
        }
    }

    function get(Item_class_model $model)
    {
        $this->db->where('item_class_id', $model->item_class_id);

        $result =$this->db->get('item_class');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_class_model'));
        }
    }

    function get_by_name(Item_class_model $model)
    {
        $this->db->where('item_class_name', $model->item_class_name);

        $result =$this->db->get('item_class');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_class_model'));
        }
    }

    function is_exist(Item_class_model $item_class)
    {
        $this->db->where('item_class_id', $item_class->item_class_id);

        $result =$this->db->get('item_class');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Item_class_model $item_class)
    {
        $this->db->where('item_class_name', $item_class->item_class_name);
        $this->db->where('item_class_id !=', $item_class->item_class_id);

        $result =$this->db->get('item_class');

        return $result && $result->num_rows()> 0;
    }

    function add(Item_class_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Item_class name is exist');
        }

        //for mysqli driver
        unset($model->item_class_id);

        $result=$this->db->insert('item_class', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->item_class_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Item_class_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Item_class name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Item_class cannot be edited');

        $this->db->where('item_class_id', $model->item_class_id);

        $result = $this->db->update('item_class', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Item_class_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Item_class cannot be delete');

        $this->db->where('item_class_id', $model->item_class_id);

        $result=$this->db->delete('item_class');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Item_class_model $model)
    {
        $sql = "select item_class_id as 'id', item_class_name as 'text' from item_class where item_class_name like'%$model->item_class_name%'";
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