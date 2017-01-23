<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Currency_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $currency_id = 0;
    public $currency_name = '';
    public $currency_name_kh = '';
    public $currency_symbol;
    //public $is_deletable = 1;
    //public $is_editable = 1;

    function gets(Currency_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'currency_name';

        $this->db->select("currency.*, ".
            "(select count(*) from currency where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('currency');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Currency_model'));
        }
    }

    function get(Currency_model $model)
    {
        $this->db->where('currency_id', $model->currency_id);

        $result =$this->db->get('currency');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Currency_model'));
        }
    }

    function get_by_name(Currency_model $model)
    {
        $this->db->where('currency_name', $model->currency_name);

        $result =$this->db->get('currency');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Currency_model'));
        }
    }

    function is_exist(Currency_model $currency)
    {
        $this->db->where('currency_id', $currency->currency_id);

        $result =$this->db->get('currency');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Currency_model $currency)
    {
        $this->db->where('currency_name', $currency->currency_name);
        $this->db->where('currency_id !=', $currency->currency_id);

        $result =$this->db->get('currency');

        return $result && $result->num_rows()> 0;
    }

    function add(Currency_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Currency name is exist');
        }

        //for mysqli driver
        unset($model->currency_id);

        $result=$this->db->insert('currency', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->currency_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Currency_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Currency name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Currency cannot be edited');

        $this->db->where('currency_id', $model->currency_id);

        $result = $this->db->update('currency', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Currency_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Currency cannot be delete');

        $this->db->where('currency_id', $model->currency_id);

        $result=$this->db->delete('currency');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Currency_model $model)
    {
        $sql = "select currency_id as 'id', currency_name as 'text' from currency where currency_name like'%$model->currency_name%'";
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