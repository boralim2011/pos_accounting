<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Item_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        //if(!isset($this->created_date)) $this->created_date = Date('Y-m-d H:i:s', time());
        //if(!isset($this->modified_date)) $this->modified_date = Date('Y-m-d H:i:s', time());
    }

    public $item_id = 0;
    public $item_code;
    public $item_name;
    public $item_name_kh;
    public $barcode;
    public $model;
    public $image;
    public $unit_id;
    public $item_type_id;
    public $item_group_id;
    public $item_class_id;
    public $maker_id;
    public $kg=0;
    public $max=0;
    public $min=0;
    public $default_lot_id;
    public $purchasing_price=0;
    public $selling_price=0;
    public $cogs_method=1;
    public $is_active = 1;

    //public $income_account_id;
    //public $inventory_account_id;
    //public $cogs_account_id;
    //public $expense_account_id;


    function gets(Item_model $item)
    {
        $display = isset($item->display)? $item->display:10;
        $page = isset($item->page)?$item->page:1;
        $offset = ($page-1) * $display;

        $search = isset($item->search)? $item->search: "";
        $search_by = isset($item->search_by)? $item->search_by: "item_name";
        $search_option = isset($item->search_option)? $item->search_option : 'like';

        $item_type_id = isset($item->item_type_id)? $item->item_type_id : 0;
        $item_group_id = isset($item->item_group_id)? $item->item_group_id:0;
        $item_class_id = isset($item->item_class_id)? $item->item_class_id:0;
        $maker_id = isset($item->maker_id)? $item->maker_id:0;

        $sql = "SELECT i.*, ".
            "(select count(*) ".
            "from item ".
            "where is_active = 1 ".
            "and $item_type_id in (0, item_type_id) ".
            "and $item_group_id in (0, item_group_id) ".
            "and $item_class_id in (0, item_class_id) ".
            "and $maker_id in (0, maker_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            ") records ".
            "from item i ".
            "where i.is_active = 1 ".
            "and $item_type_id in (0, i.item_type_id) ".
            "and $item_group_id in (0, i.item_group_id) ".
            "and $item_class_id in (0, i.item_class_id) ".
            "and $maker_id in (0, i.maker_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && i.$search_by='$search') || ".
            "('$search_option'='start_with' && i.$search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && i.$search_by LIKE '%$search%' ESCAPE '!')) ".
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
            return Message_result::success_message('', null, $query->result('Item_model'));
        }


    }

    function get(Item_model $item)
    {
        $this->db->select("i.*, it.item_type_name, ig.item_group_name, ic.item_class_name, m.maker_name, u.unit_name, dl.warehouse_name default_lot_name");
        $this->db->from("item i");
        $this->db->join("item_type it","i.item_type_id=it.item_type_id");
        $this->db->join("item_group ig","i.item_group_id=ig.item_group_id");
        $this->db->join("item_class ic","i.item_class_id=ic.item_class_id");
        $this->db->join("maker m","i.maker_id=m.maker_id");
        $this->db->join("unit u","i.unit_id=u.unit_id");
        $this->db->join("warehouse dl","i.default_lot_id=dl.warehouse_id");
        $this->db->where('i.item_id', $item->item_id);
        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_model'));
        }
    }

    function get_by_code(Item_model $item)
    {
        $this->db->where('item_code', $item->item_code);
        $result =$this->db->get('item');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_model'));
        }
    }

    function get_by_barcode(Item_model $item)
    {
        $this->db->where('barcode', $item->barcode);
        $result =$this->db->get('item');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_model'));
        }
    }

    function is_exist(Item_model $item)
    {
        $this->db->where('item_id', $item->item_id);

        $result =$this->db->get('item');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_code(Item_model $item)
    {
        $this->db->where('item_code', $item->item_code);
        $this->db->where('item_id !=', $item->item_id);

        $result =$this->db->get('item');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_barcode(Item_model $item)
    {
        $this->db->where('barcode', $item->barcode);
        $this->db->where('item_id !=', $item->item_id);

        $result =$this->db->get('item');

        return $result && $result->num_rows()> 0;
    }

    function generate_code(Item_model &$model)
    {
        if(isset($model->item_code) && $model->item_code!='') {
            if($model->barcode=='') $model->barcode=$model->item_code;
            return $model->item_code;
        }

        //$prefix = Date('ymd-');
        //$prefix = strtoupper(substr($model->item_type, 0, 3));
        $prefix="";
        $prefix = $prefix==""?"": $prefix."-";
        $digits = "000001";
        $code = $prefix.$digits;

        $sql = "select item_code from item where item_code LIKE '$prefix%' order by item_code desc limit 1";

        $result = $this->db->query($sql);

        if($result && $result->num_rows()>0)
        {
            $number = (int) substr($result->first_row()->item_code, strlen($prefix));
            $number ++;

            $code = $prefix.str_pad($number, strlen($digits) , "0", 0);
        }

        $model->item_code = $code;
        if($model->barcode=='') $model->barcode=$model->item_code;

        return $model->item_code;
    }

    function add(Item_model &$item)
    {
        $this->generate_code($item);


        if($this->is_exist_code($item))
        {
            return Message_result::error_message('Item code is exist');
        }

        if($this->is_exist_barcode($item))
        {
            return Message_result::error_message('Barcode is exist');
        }

        //for mysqli driver
        unset($item->item_id);

        //echo $this->db->insert_string('item', $item); exit;

        $result=$this->db->insert('item', $item);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $item->item_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $item);
        }

    }

    function update(Item_model $item)
    {
        $this->generate_code($item);

        if($this->is_exist_code($item))
        {
            return Message_result::error_message('Item name is exist');
        }

        if($this->is_exist_barcode($item))
        {
            return Message_result::error_message('Barcode is exist');
        }

        $result = $this->get($item);

        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Item cannot be edit');

        $this->db->where('item_id', $item->item_id);

        $result = $this->db->update('item', $item);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $item);
        }
    }

    function delete(Item_model $item)
    {
        $result = $this->get($item);

        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Item cannot be delete');

        $this->db->where('item_id', $item->item_id);

        $result=$this->db->delete('item');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$item);
        }
    }

    function get_combobox_items(Item_model $model)
    {
        $sql = "select item_id as 'id', item_name as 'text' from item where item_name like'%$model->item_name%' and '$model->item_type' in ('', item_type)";
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