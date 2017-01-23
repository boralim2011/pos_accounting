<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Item_group_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $item_group_id = 0;
    public $item_group_name = '';
    public $item_group_name_kh = '';
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(Item_group_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'item_group_name';

        $this->db->select("item_group.*, ".
            "(select count(*) from item_group where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('item_group');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Item_group_model'));
        }
    }

    function get(Item_group_model $model)
    {
        $this->db->where('item_group_id', $model->item_group_id);

        $result =$this->db->get('item_group');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_group_model'));
        }
    }

    function get_by_name(Item_group_model $model)
    {
        $this->db->where('item_group_name', $model->item_group_name);

        $result =$this->db->get('item_group');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_group_model'));
        }
    }

    function is_exist(Item_group_model $item_group)
    {
        $this->db->where('item_group_id', $item_group->item_group_id);

        $result =$this->db->get('item_group');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Item_group_model $item_group)
    {
        $this->db->where('item_group_name', $item_group->item_group_name);
        $this->db->where('item_group_id !=', $item_group->item_group_id);

        $result =$this->db->get('item_group');

        return $result && $result->num_rows()> 0;
    }

    function add(Item_group_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Item_group name is exist');
        }

        //for mysqli driver
        unset($model->item_group_id);

        $result=$this->db->insert('item_group', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->item_group_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Item_group_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Item_group name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Item_group cannot be edited');

        $this->db->where('item_group_id', $model->item_group_id);

        $result = $this->db->update('item_group', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Item_group_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Item_group cannot be delete');

        $this->db->where('item_group_id', $model->item_group_id);

        $result=$this->db->delete('item_group');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Item_group_model $model)
    {
        $sql = "select item_group_id as 'id', item_group_name as 'text' from item_group where item_group_name like'%$model->item_group_name%'";
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