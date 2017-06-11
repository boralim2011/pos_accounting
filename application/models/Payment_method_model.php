<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Payment_method_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

    }

    public $payment_method_id;
    public $payment_method_name;

    function gets(Payment_method_model $model)
    {
        $query = $this->db->query('select * from payment_method');

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Payment_method_model'));
        }

    }


    function get_combobox_items(Payment_method_model $model)
    {
        $sql = "select payment_method_id as 'id', payment_method_name as 'text' from payment_method where payment_method_name like'%$model->payment_method_name%'";
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