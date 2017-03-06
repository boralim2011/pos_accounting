<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Transaction_type_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $transaction_type_id = 0;
    public $transaction_type_name = '';
    public $transaction_type_name_kh = '';
    public $journal_type_id;
    public $is_issue = 0;
    public $is_goods_movement = 1;

    function gets(Transaction_type_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'transaction_type_name';

        $this->db->select("transaction_type.*, ".
            "(select count(*) from transaction_type where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('transaction_type');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Transaction_type_model'));
        }
    }

    function get(Transaction_type_model $model)
    {
        $this->db->where('transaction_type_id', $model->transaction_type_id);

        $result =$this->db->get('transaction_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Transaction_type_model'));
        }
    }

    function get_by_name(Transaction_type_model $model)
    {
        $this->db->where('transaction_type_name', $model->transaction_type_name);

        $result =$this->db->get('transaction_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Transaction_type_model'));
        }
    }

    function is_exist(Transaction_type_model $transaction_type)
    {
        $this->db->where('transaction_type_id', $transaction_type->transaction_type_id);

        $result =$this->db->get('transaction_type');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Transaction_type_model $transaction_type)
    {
        $this->db->where('transaction_type_name', $transaction_type->transaction_type_name);
        $this->db->where('transaction_type_id !=', $transaction_type->transaction_type_id);

        $result =$this->db->get('transaction_type');

        return $result && $result->num_rows()> 0;
    }

    function add(Transaction_type_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Transaction_type name is exist');
        }

        //for mysqli driver
        unset($model->transaction_type_id);

        $result=$this->db->insert('transaction_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->transaction_type_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Transaction_type_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Transaction_type name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Transaction_type cannot be edited');

        $this->db->where('transaction_type_id', $model->transaction_type_id);

        $result = $this->db->update('transaction_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Transaction_type_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Transaction_type cannot be delete');

        $this->db->where('transaction_type_id', $model->transaction_type_id);

        $result=$this->db->delete('transaction_type');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Transaction_type_model $model)
    {
        $sql = "select transaction_type_id as 'id', transaction_type_name as 'text' from transaction_type where transaction_type_name like'%$model->transaction_type_name%'";
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