<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Payment_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

    }

    public $payment_id = 0;
    public $payment_no;
    public $payment_date;
    public $payment_method_id;
    public $journal_id;
    public $amount = 0;
    public $receive_amount = 0;
    public $refund = 0;
    public $receive_amount_riel = 0;
    public $refund_riel = 0;
    public $exchange_rate = 1;
    public $card_id;

    function get_amount()
    {
        return $this->receive_amount + $this->receive_amount_riel / $this->exchange_rate - $this->refund - $this->refund_riel / $this->exchange_rate;
    }

    function gets(Payment_model $payment)
    {
        $display = isset($payment->display)? $payment->display:10;
        $page = isset($payment->page)?$payment->page:1;
        $offset = ($page-1) * $display;

        $search = isset($payment->search)? $payment->search: "";
        $search_by = isset($payment->search_by)? $payment->search_by: "payment_no";
        $search_option = isset($payment->search_option)? $payment->search_option : 'like';

        $payment_method_id = isset($payment->payment_method_id)? $payment->payment_method_id : 0;
        $journal_id = isset($payment->journal_id )? $payment->journal_id  : 0;

        $sql = "SELECT *, ".
            "(select count(*) ".
            "from payment ".
            "where $payment_method_id in (0, payment_method_id) ".
            "and $journal_id in (0, journal_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            ") records ".
            "from payment ".
            "where $payment_method_id in (0, payment_method_id) ".
            "and $journal_id in (0, journal_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
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
            return Message_result::success_message('', null, $query->result('Payment_model'));
        }


    }

    function get(Payment_model $payment)
    {
        $this->db->select("p.*, pm.payment_method_name");
        $this->db->from("payment p");
        $this->db->join("payment_method pm","p.payment_method_id=pm.payment_method_id");
        $this->db->where('p.payment_id', $payment->payment_id);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Payment_model'));
        }
    }

    function get_by_number(Payment_model $payment)
    {
        $this->db->select("p.*, pm.payment_method_name");
        $this->db->from("payment p");
        $this->db->join("payment_method pm","p.payment_method_id=pm.payment_method_id");
        $this->db->where('p.payment_no', $payment->payment_no);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Payment_model'));
        }
    }

    function is_exist(Payment_model $payment)
    {
        $this->db->where('payment_id', $payment->payment_id);

        $result =$this->db->get('payment');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_code(Payment_model $payment)
    {
        $this->db->where('payment_no', $payment->payment_no);
        $this->db->where('payment_id !=', $payment->payment_id);

        $result =$this->db->get('payment');

        return $result && $result->num_rows()> 0;
    }


    function add(Payment_model &$payment)
    {

        if($this->is_exist_code($payment))
        {
            return Message_result::error_message('Payment number is exist');
        }

        //for mysqli driver
        unset($payment->payment_id);
        $payment->amount = $this->get_amount();

        //echo $this->db->insert_string('payment', $payment); exit;

        $result=$this->db->insert('payment', $payment);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $payment->payment_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $payment);
        }

    }

    function update(Payment_model $payment)
    {
        if($this->is_exist_code($payment))
        {
            return Message_result::error_message('Payment no is exist');
        }

        $result = $this->get($payment);
        $payment->amount = $this->get_amount();

        //if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Payment cannot be edit');

        $this->db->where('payment_id', $payment->payment_id);

        $result = $this->db->update('payment', $payment);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $payment);
        }
    }

    function delete(Payment_model $payment)
    {
        $result = $this->get($payment);

        //if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Payment cannot be delete');

        //if($result->model->get_total_deposit() > 0 || $result->model->get_total_withdraw()>0) return Message_result::error_message('Payment is used');

        $this->db->where('payment_id', $payment->payment_id);

        $result=$this->db->delete('payment');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$payment);
        }
    }

   

}