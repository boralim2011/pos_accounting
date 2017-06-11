<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sale_item_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

    }

    public $journal_item_id = 0;
    public $journal_id;
    public $item_id;
    public $qty;
    public $price;
    public $discount=1;
    public $description;
    public $lot_id;


    //public $income_account_id;
    //public $inventory_account_id;
    //public $cogs_account_id;
    //public $expense_account_id;

    function get_price($digits=2)
    {
        return Model_base::round_up($this->price, $digits);
    }

    function get_amount($digits=2)
    {
        return $this->get_price($digits) * $this->qty;
    }

    function gets(Sale_item_model $sale_item)
    {

        $sql = "SELECT ji.*, i.item_name, u.unit_name from journal_item ji ".
            "join item i on i.item_id=ji.item_id ".
            "left join unit u on i.unit_id=u.unit_id ".
            "where ji.journal_id=$sale_item->journal_id"
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Sale_item_model'));
        }


    }

    function get(Sale_item_model $sale_item)
    {
        $this->db->select("ji.*, i.item_name");
        $this->db->from("journal_item ji");
        $this->db->join("item i","i.item_id=ji.item_id", "left");
        $this->db->where('ji.journal_item_id', $sale_item->journal_item_id);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Sale_item_model'));
        }
    }

    function is_exist(Sale_item_model $sale_item)
    {
        $this->db->where('journal_item_id', $sale_item->journal_item_id);

        $result =$this->db->get('journal_item');

        return $result && $result->num_rows()> 0;
    }

    function add(Sale_item_model &$sale_item)
    {

        //for mysqli driver
        unset($sale_item->journal_journal_id);

        //echo $this->db->insert_string('sale', $sale); exit;

        $result=$this->db->insert('journal_item', $sale_item);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $sale_item->journal_item_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $sale_item);
        }

    }

    function update(Sale_item_model $sale_item)
    {

        //$result = $this->get($sale);
        //if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Sale cannot be edit');

        $this->db->where('journal_item_id', $sale_item->journal_item_id);

        $result = $this->db->update('journal_item', $sale_item);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $sale_item);
        }
    }

    function delete(Sale_item_model $sale_item)
    {

        $this->db->where('journal_item_id', $sale_item->journal_item_id);

        $result=$this->db->delete('journal_item');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$sale_item);
        }
    }

}