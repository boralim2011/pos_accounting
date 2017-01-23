<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Item_type_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $item_type_id = 0;
    public $item_type_name = '';
    public $item_type_name_kh = '';
    public $parent_id;
    public $manage_stock = 0;

    function gets(Item_type_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'item_type_name';

        $this->db->select("item_type.*, ".
            "(select count(*) from item_type where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('item_type');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Item_type_model'));
        }
    }

    function get(Item_type_model $model)
    {
        $this->db->where('item_type_id', $model->item_type_id);

        $result =$this->db->get('item_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_type_model'));
        }
    }

    function get_by_name(Item_type_model $model)
    {
        $this->db->where('item_type_name', $model->item_type_name);

        $result =$this->db->get('item_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_type_model'));
        }
    }

    function is_exist(Item_type_model $item_type)
    {
        $this->db->where('item_type_id', $item_type->item_type_id);

        $result =$this->db->get('item_type');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Item_type_model $item_type)
    {
        $this->db->where('item_type_name', $item_type->item_type_name);
        $this->db->where('item_type_id !=', $item_type->item_type_id);

        $result =$this->db->get('item_type');

        return $result && $result->num_rows()> 0;
    }

    function add(Item_type_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Item_type name is exist');
        }

        //for mysqli driver
        unset($model->item_type_id);

        $result=$this->db->insert('item_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->item_type_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Item_type_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Item_type name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Item_type cannot be edited');

        $this->db->where('item_type_id', $model->item_type_id);

        $result = $this->db->update('item_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Item_type_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Item_type cannot be delete');

        $this->db->where('item_type_id', $model->item_type_id);

        $result=$this->db->delete('item_type');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Item_type_model $model)
    {
        $sql = "select item_type_id as 'id', item_type_name as 'text' from item_type where item_type_name like'%$model->item_type_name%'";
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