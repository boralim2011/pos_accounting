<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Card_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        //if(!isset($this->created_date)) $this->created_date = Date('Y-m-d H:i:s', time());
        //if(!isset($this->modified_date)) $this->modified_date = Date('Y-m-d H:i:s', time());
    }

    public $card_id = 0;
    public $card_number;
    public $card_name;
    public $card_name_kh;
    public $card_type_id;
    public $discount_rate;
    public $register_date;
    public $expired_date;
    public $is_active = 1;

    //public $income_account_id;
    //public $inventory_account_id;
    //public $cogs_account_id;
    //public $expense_account_id;


    function gets(Card_model $card)
    {
        $display = isset($card->display)? $card->display:10;
        $page = isset($card->page)?$card->page:1;
        $offset = ($page-1) * $display;

        $search = isset($card->search)? $card->search: "";
        $search_by = isset($card->search_by)? $card->search_by: "card_name";
        $search_option = isset($card->search_option)? $card->search_option : 'like';

        $card_type_id = isset($card->card_type_id)? $card->card_type_id : 0;
        //$card_group_id = isset($card->card_group_id)? $card->card_group_id:0;
        //$card_class_id = isset($card->card_class_id)? $card->card_class_id:0;
        //$maker_id = isset($card->maker_id)? $card->maker_id:0;

        $sql = "SELECT i.*, ".
            "(select count(*) ".
            "from card ".
            "where $card_type_id in (0, card_type_id) ".
            //"and $card_group_id in (0, card_group_id) ".
            //"and $card_class_id in (0, card_class_id) ".
            //"and $maker_id in (0, maker_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            ") records ".
            "from card i ".
            "where $card_type_id in (0, i.card_type_id) ".
            //"and $card_group_id in (0, i.card_group_id) ".
            //"and $card_class_id in (0, i.card_class_id) ".
            //"and $maker_id in (0, i.maker_id) ".
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
            return Message_result::success_message('', null, $query->result('Card_model'));
        }


    }

    function get(Card_model $card)
    {
        $this->db->select("c.*, ct.card_type_name");
        $this->db->from("card c");
        $this->db->join("card_type ct","c.card_type_id=ct.card_type_id");
        //$this->db->join("card_group ig","i.card_group_id=ig.card_group_id");
        //$this->db->join("card_class ic","i.card_class_id=ic.card_class_id");
        //$this->db->join("maker m","i.maker_id=m.maker_id");
        //$this->db->join("unit u","i.unit_id=u.unit_id");
        //$this->db->join("warehouse dl","i.default_lot_id=dl.warehouse_id");
        $this->db->where('c.card_id', $card->card_id);
        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Card_model'));
        }
    }

    function get_by_number(Card_model $card)
    {
        $this->db->where('card_number', $card->card_number);
        $result =$this->db->get('card');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Card_model'));
        }
    }

    function is_exist(Card_model $card)
    {
        $this->db->where('card_id', $card->card_id);

        $result =$this->db->get('card');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_code(Card_model $card)
    {
        $this->db->where('card_number', $card->card_number);
        $this->db->where('card_id !=', $card->card_id);

        $result =$this->db->get('card');

        return $result && $result->num_rows()> 0;
    }


    function add(Card_model &$card)
    {

        if($this->is_exist_code($card))
        {
            return Message_result::error_message('Card number is exist');
        }

        //for mysqli driver
        unset($card->card_id);

        //echo $this->db->insert_string('card', $card); exit;

        $result=$this->db->insert('card', $card);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $card->card_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $card);
        }

    }

    function update(Card_model $card)
    {
        if($this->is_exist_code($card))
        {
            return Message_result::error_message('Card number is exist');
        }

        $result = $this->get($card);

        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Card cannot be edit');

        $this->db->where('card_id', $card->card_id);

        $result = $this->db->update('card', $card);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $card);
        }
    }

    function delete(Card_model $card)
    {
        $result = $this->get($card);

        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Card cannot be delete');

        $this->db->where('card_id', $card->card_id);

        $result=$this->db->delete('card');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$card);
        }
    }

    function get_combobox_cards(Card_model $model)
    {
        $sql = "select card_id as 'id', card_name as 'text' from card where card_name like'%$model->card_name%' and '$model->card_type' in ('', card_type)";
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