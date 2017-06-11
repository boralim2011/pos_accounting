<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sale_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

    }

    public $journal_id = 0;
    public $journal_no;
    public $journal_date;
    public $room_id;
    public $contact_id;
    public $journal_status=1;
    public $note;
    public $total;
    public $total_company_currency;
    public $discount=0;

    public $currency_id;
    public $exchange_rate;
    public $age_range_id;
    public $gender;
    public $card_id;


    //public $income_account_id;
    //public $inventory_account_id;
    //public $cogs_account_id;
    //public $expense_account_id;


    function gets(Sale_model $sale)
    {
        $display = isset($sale->display)? $sale->display:10;
        $page = isset($sale->page)?$sale->page:1;
        $offset = ($page-1) * $display;

        $search = isset($sale->search)? $sale->search: "";
        $search_by = isset($sale->search_by)? $sale->search_by: "journal_no";
        $search_option = isset($sale->search_option)? $sale->search_option : 'like';

        $room_id = isset($sale->room_id)? $sale->room_id : 0;

        $all_date = isset($sale->all_date) && $sale->all_date==1? 1 : 0;
        $date_of = isset($sale->date_of)? $sale->date_of : "journal_date";
        $from_date = isset($sale->from_date)? $sale->from_date : Date('Y-m-d');
        $to_date = isset($sale->to_date)? $sale->to_date : Date('Y-m-d');

        $sql = "SELECT j.*, r.room_name, ".
            "(select count(*) ".
            "from journal j ".
            "left join room r on r.room_id=j.room_id ".
            "where $room_id in (0, j.room_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            "AND (($all_date=1 && j.$date_of is not null) || j.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ".
            ") records ".
            "from journal j ".
            "left join room r on r.room_id=j.room_id ".
            "where $room_id in (0, j.room_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            "AND (($all_date=1 && j.$date_of is not null) || j.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ".
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
            return Message_result::success_message('', null, $query->result('Sale_model'));
        }


    }

    function get(Sale_model $sale)
    {

        $this->db->select("j.*, r.room_name, c.card_number, ar.age_range_name");
        $this->db->from("journal j");
        $this->db->join("room r", "r.room_id=j.room_id", "left");
        $this->db->join("card c", "j.card_id=c.card_id", "left");
        $this->db->join("age_range ar", "j.age_range_id=ar.age_range_id", "left");
        $this->db->where('j.journal_id', $sale->journal_id);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Sale_model'));
        }
    }

    function get_by_number(Sale_model $sale)
    {
        $this->db->select("j.*, r.room_name, c.card_number, ar.age_range_name");
        $this->db->from("journal j");
        $this->db->join("room r", "r.room_id=j.room_id", "left");
        $this->db->join("card c", "j.card_id=c.card_id", "left");
        $this->db->join("age_range ar", "j.age_range_id=ar.age_range_id", "left");
        $this->db->where('j.journal_no', $sale->journal_no);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Sale_model'));
        }
    }

    function is_exist(Sale_model $sale)
    {
        $this->db->where('journal_id', $sale->journal_id);

        $result =$this->db->get('journal');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_code(Sale_model $sale)
    {
        $this->db->where('journal_no', $sale->journal_no);
        $this->db->where('journal_id !=', $sale->journal_id);

        $result =$this->db->get('journal');

        return $result && $result->num_rows()> 0;
    }

    function generate_code(Sale_model &$model)
    {
        if(isset($model->journal_no) && $model->journal_no!='') {
            return $model->journal_no;
        }

        $prefix = "INV-".Date('ym');
        $digits = "0001";
        $code = $prefix.$digits;

        $sql = "select journal_no from journal where journal_no LIKE '$prefix%' and journal_id!=$model->journal_id order by journal_no desc limit 1";

        $result = $this->db->query($sql);

        if($result && $result->num_rows()>0)
        {
            $number = (int) substr($result->first_row()->journal_no, strlen($prefix));
            $number ++;

            $code = $prefix.str_pad($number, strlen($digits) , "0", 0);
        }

        $model->journal_no = $code;

        return $model->journal_no;
    }

    function add(Sale_model &$sale)
    {

        $this->generate_code($sale);

        if($this->is_exist_code($sale))
        {
            return Message_result::error_message('Sale number is exist');
        }

        //for mysqli driver
        unset($sale->journal_id);

        //$sql = $this->db->insert_string('journal', $sale);
        //return Message_result::error_message($sql);

        $result=$this->db->insert('journal', $sale);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $sale->journal_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $sale);
        }

    }

    function update(Sale_model $sale)
    {
        $this->generate_code($sale);

        if($this->is_exist_code($sale))
        {
            return Message_result::error_message('Sale number is exist');
        }

        //$result = $this->get($sale);
        //if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Sale cannot be edit');

        $this->db->where('journal_id', $sale->journal_id);

        $result = $this->db->update('journal', $sale);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $sale);
        }
    }

    function delete(Sale_model $sale)
    {
        //$result = $this->get($sale);

        //if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Sale cannot be delete');

        $this->db->where('journal_id', $sale->journal_id);

        $result=$this->db->delete('journal');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$sale);
        }
    }

    function get_combobox_sales(Sale_model $model)
    {
        $sql = "select journal_id as 'id', journal_no as 'text' from journal where journal_no like'%$model->journal_no%'";
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

//    function get_total_deposit()
//    {
//        $sql = "select sum(amount_in_company_currency) total from sale_history where is_deposit = 1 and journal_id = $this->journal_id";
//        $result = $this->db->query($sql);
//
//        return (!$result || $result->num_rows()== 0 || !$result->first_row()->total)? 0 : $result->first_row()->total;
//    }

    function get_total_riel()
    {
        return $this->total * $this->exchange_rate;
    }

    function get_total_after_discount()
    {
        return $this->total * (1 - $this->discount/100);
    }

    function get_total_riel_after_discount()
    {
        return $this->get_total_after_discount() * $this->exchange_rate;
    }

    function format_total(){
        return "$".number_format($this->total, 2);
    }

    function format_total_riel(){
        return "៛".number_format($this->get_total_riel(), 0);
    }

    function format_total_after_discount(){
        return "$".number_format($this->get_total_after_discount(), 2);
    }

    function format_total_riel_after_discount(){
        return "៛".number_format($this->get_total_riel_after_discount(), 0);
    }

    function report(Sale_model $sale)
    {
        $display = isset($sale->display)? $sale->display:10;
        $page = isset($sale->page)? $sale->page:1;
        $offset = ($page-1) * $display;

        $search = isset($sale->search)? $sale->search : "";
        $search_by = isset($sale->search_by)? $sale->search_by : "journal_no";
        $search_option = isset($sale->search_option)? $sale->search_option : 'like';

        $room_id = isset($sale->room_id)? $sale->room_id : 0;
        $card_id = isset($sale->card_id)? $sale->card_id : 0;
        $card_type_id = isset($sale->card_type_id)? $sale->card_type_id : 0;

        $all_date = isset($sale->all_date) && $sale->all_date==1? 1 : 0;
        $date_of = isset($sale->date_of)? $sale->date_of : "journal_date";
        $from_date = isset($sale->from_date)? $sale->from_date : Date('Y-m-d');
        $to_date = isset($sale->to_date)? $sale->to_date : Date('Y-m-d');

        $show_all = isset($sale->show_all) ? 1:0;

        $sql = "SELECT j.*, r.room_name, c.card_name, ct.card_type_name, ".
            "(select count(*) ".
            "from journal j ".
            "left join room r on r.room_id=j.room_id ".
            "left join card c on c.card_id=j.card_id ".
            "left join card_type ct on ct.card_type_id=c.card_type_id ".
            "where $room_id in (0, j.room_id) ".
            "and $card_id in (0, j.card_id) ".
            "and $card_type_id in (0, c.card_type_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            "AND (($all_date=1 && j.$date_of is not null) || j.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ".
            ") records ".
            "from journal j ".
            "left join room r on r.room_id=j.room_id ".
            "left join card c on c.card_id=j.card_id ".
            "left join card_type ct on ct.card_type_id=c.card_type_id ".
            "where $room_id in (0, j.room_id) ".
            "and $card_id in (0, j.card_id) ".
            "and $card_type_id in (0, c.card_type_id) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            "AND (($all_date=1 && j.$date_of is not null) || j.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ".
            ($show_all==0? "LIMIT $offset, $display" :"")
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Sale_model'));
        }


    }
}