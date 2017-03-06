<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Warehouse_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $warehouse_id = 0;
    public $warehouse_name = '';
    public $warehouse_name_kh = '';
    public $parent_id;
    public $is_warehouse = 0;

    function gets(Warehouse_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'warehouse_name';

        $is_warehouse = $model->is_warehouse;

        $sql =("select w.*, p.warehouse_name parent_name, ".
            "(select count(*) from warehouse where $field like '%$search%' and ($is_warehouse=0 || is_warehouse=$is_warehouse)) 'records' ".
            "from warehouse w ".
            "left join warehouse p on w.parent_id=p.warehouse_id and p.is_warehouse=1 ".
            "where w.$field like '%$search%' ".
            "and ($is_warehouse=0 || w.is_warehouse=$is_warehouse) ".
            "limit $offset, $display"
        );

        //echo $sql ; exit;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Warehouse_model'));
        }
    }

    function get(Warehouse_model $model)
    {
        $this->db->where('warehouse_id', $model->warehouse_id);

        $result =$this->db->get('warehouse');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Warehouse_model'));
        }
    }

    function get_by_name(Warehouse_model $model)
    {
        $this->db->where('warehouse_name', $model->warehouse_name);

        $result =$this->db->get('warehouse');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Warehouse_model'));
        }
    }

    function is_exist(Warehouse_model $warehouse)
    {
        $this->db->where('warehouse_id', $warehouse->warehouse_id);

        $result =$this->db->get('warehouse');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Warehouse_model $warehouse)
    {
        $this->db->where('warehouse_name', $warehouse->warehouse_name);
        $this->db->where('warehouse_id !=', $warehouse->warehouse_id);

        $result =$this->db->get('warehouse');

        return $result && $result->num_rows()> 0;
    }

    function add(Warehouse_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Warehouse name is exist');
        }

        //for mysqli driver
        unset($model->warehouse_id);

        $result=$this->db->insert('warehouse', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->warehouse_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Warehouse_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Warehouse name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Warehouse cannot be edited');

        $this->db->where('warehouse_id', $model->warehouse_id);

        $result = $this->db->update('warehouse', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Warehouse_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Warehouse cannot be delete');

        $this->db->where('warehouse_id', $model->warehouse_id);

        $result=$this->db->delete('warehouse');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Warehouse_model $model)
    {
        $sql = "select warehouse_id as 'id', warehouse_name as 'text' ".
                "from warehouse where warehouse_name like'%$model->warehouse_name%' and ($model->is_warehouse=0 || is_warehouse=$model->is_warehouse)";
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