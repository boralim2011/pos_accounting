<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Exchange_rate_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $exchange_rate_id = 0;
    public $from_currency_id;
    public $to_currency_id;
    public $bit_rate = 1;
    public $ask_rate = 1;
    public $is_inverse = 0;

    function gets(Exchange_rate_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'exchange_rate_name';

        $sql="select ex.*, fc.currency_name from_currency_name, tc.currency_name to_currency_name, ".
            "(select count(*) ".
                "from exchange_rate ex1 ".
                "join currency fc1 on fc1.currency_id=ex1.from_currency_id ".
                "join currency tc1 on tc1.currency_id=ex1.to_currency_id ".
                "where ('from_currency_name'='$field' and fc1.currency_name like '%$search%') ".
                "or ('to_currency_name'='$field' and tc1.currency_name like '%$search%')".
                "or ('from_currency_name_kh'='$field' and fc1.currency_name_kh like '%$search%') ".
                "or ('to_currency_name_kh'='$field' and tc1.currency_name_kh like '%$search%') ".
                "or ('bit_rate'='$field' and ex1.bit_rate like '%$search%') ".
                "or ('ask_rate'='$field' and ex1.ask_rate like '%$search%') ".
                ") records ".
            "from exchange_rate ex ".
            "join currency fc on fc.currency_id=ex.from_currency_id ".
            "join currency tc on tc.currency_id=ex.to_currency_id ".
            "where ('from_currency_name'='$field' and fc.currency_name like '%$search%') ".
            "or ('to_currency_name'='$field' and tc.currency_name like '%$search%') ".
            "or ('from_currency_name_kh'='$field' and fc.currency_name_kh like '%$search%') ".
            "or ('to_currency_name_kh'='$field' and tc.currency_name_kh like '%$search%') ".
            "or ('bit_rate'='$field' and ex.bit_rate like '%$search%') ".
            "or ('ask_rate'='$field' and ex.ask_rate like '%$search%') ".
            "limit $offset, $display"
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Exchange_rate_model'));
        }
    }

    function get(Exchange_rate_model $model)
    {
        $sql="select ex.*, fc.currency_name from_currency_name, tc.currency_name to_currency_name ".
            "from exchange_rate ex ".
            "join currency fc on fc.currency_id=ex.from_currency_id ".
            "join currency tc on tc.currency_id=ex.to_currency_id ".
            "where ex.exchange_rate_id=$model->exchange_rate_id"
        ;

        $result =$this->db->query($sql);

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Exchange_rate_model'));
        }
    }

    function is_exist(Exchange_rate_model $exchange_rate)
    {
        $this->db->where('exchange_rate_id', $exchange_rate->exchange_rate_id);

        $result =$this->db->get('exchange_rate');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_rate(Exchange_rate_model $exchange_rate)
    {
        $sql ="select * from exchange_rate ".
            "where ((from_currency_id=$exchange_rate->from_currency_id and to_currency_id=$exchange_rate->to_currency_id) ".
            "or (from_currency_id=$exchange_rate->to_currency_id and to_currency_id=$exchange_rate->from_currency_id)) ".
            "and exchange_rate_id!=$exchange_rate->exchange_rate_id"
        ;
        $result = $this->db->query($sql);

        return $result && $result->num_rows()> 0;
    }

    function add(Exchange_rate_model &$model)
    {
        if($model->from_currency_id==$model->to_currency_id)
        {
            return Message_result::error_message('From and To currency cannot be the same');
        }

        if($this->is_exist_rate($model))
        {
            return Message_result::error_message('Exchange_rate is exist');
        }

        //for mysqli driver
        unset($model->exchange_rate_id);

        $result=$this->db->insert('exchange_rate', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->exchange_rate_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Exchange_rate_model $model)
    {
        if($model->from_currency_id==$model->to_currency_id)
        {
            return Message_result::error_message('From and To currency cannot be the same');
        }
        if($this->is_exist_rate($model))
        {
            return Message_result::error_message('Exchange_rate is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Exchange_rate cannot be edited');

        // $ms = '';
        // foreach($model as $key=>$val) $ms .= "$key=>$val<br>\n";
        // return Message_result::error_message($ms);

        $this->db->where('exchange_rate_id', $model->exchange_rate_id);

        $result = $this->db->update('exchange_rate', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Exchange_rate_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Exchange_rate cannot be delete');

        $this->db->where('exchange_rate_id', $model->exchange_rate_id);

        $result=$this->db->delete('exchange_rate');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

}