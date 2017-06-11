<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Card_history_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        //if(!isset($this->created_date)) $this->created_date = Date('Y-m-d H:i:s', time());
        //if(!isset($this->modified_date)) $this->modified_date = Date('Y-m-d H:i:s', time());
    }

    public $history_id = 0;
    public $card_id;
    public $history_date;
    public $amount;
    public $amount_in_company_currency;
    public $exchange_rate = 1;
    public $currency_id;
    public $is_inverse=0;
    public $is_deposit=0;
    public $is_payment=0;
    public $note;


    function gets(Card_history_model $card_history)
    {
        $display = isset($card_history->display)? $card_history->display:10;
        $page = isset($card_history->page)?$card_history->page:1;
        $offset = ($page-1) * $display;

        $search = isset($card_history->search)? $card_history->search: "";
        $search_by = isset($card_history->search_by)? $card_history->search_by: "card_number";
        $search_option = isset($card_history->search_option)? $card_history->search_option : 'like';

        $card_id = isset($card_history->card_id)? $card_history->card_id:0;

        $all_date = isset($card_history->all_date) && $card_history->all_date==1? 1 : 0;
        $date_of = isset($card_history->date_of)? $card_history->date_of : "history_date";
        $from_date = isset($card_history->from_date)? $card_history->from_date : Date('Y-m-d');
        $to_date = isset($card_history->to_date)? $card_history->to_date : Date('Y-m-d');

        $sql = "SELECT ch.*, c.card_number, c.card_name, ".
            "(select count(*) ".
            "from card_history ch ".
            "join card c on c.card_id=ch.card_id ".
            "where $card_id in (0, c.card_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && c.$search_by='$search') || ".
            "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
            "AND (($all_date=1 && ch.$date_of is not null) || ch.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ".
            ") records ".
            "from card_history ch ".
            "join card c on c.card_id=ch.card_id ".
            "where $card_id in (0, ch.card_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && c.$search_by='$search') || ".
            "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
            "AND (($all_date=1 && ch.$date_of is not null) || ch.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ".
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
            return Message_result::success_message('', null, $query->result('Card_history_model'));
        }


    }

    function get(Card_history_model $card_history)
    {
        $this->db->select("ch.*, c.card_name, c.card_number");
        $this->db->from("card_history ch");
        $this->db->join("card c","c.card_id=ch.card_id");
        $this->db->where('ch.history_id', $card_history->history_id);
        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Card_history_model'));
        }
    }

    function is_exist(Card_history_model $card_history)
    {
        $this->db->where('history_id', $card_history->history_id);

        $result =$this->db->get('card_history');

        return $result && $result->num_rows()> 0;
    }

    function add(Card_history_model &$card_history)
    {
        //for mysqli driver
        unset($card_history->history_id);

        //echo $this->db->insert_string('card_history', $card_history); exit;
        //$message = $this->db->insert_string('card_history', $card_history);
        //return Message_result::error_message($message);

        $result=$this->db->insert('card_history', $card_history);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $card_history->history_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $card_history);
        }

    }

    function update(Card_history_model $card_history)
    {
        $result = $this->get($card_history);

        //if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Card_history cannot be edit');

        $this->db->where('history_id', $card_history->history_id);

        $result = $this->db->update('card_history', $card_history);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $card_history);
        }
    }

    function delete(Card_history_model $card_history)
    {
        $result = $this->get($card_history);

        //if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Card_history cannot be delete');

        $this->db->where('history_id', $card_history->history_id);

        $result=$this->db->delete('card_history');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$card_history);
        }
    }



}