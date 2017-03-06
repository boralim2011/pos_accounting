<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Journal_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        //if(!isset($this->created_date)) $this->created_date = Date('Y-m-d H:i:s', time());
        //if(!isset($this->modified_date)) $this->modified_date = Date('Y-m-d H:i:s', time());
    }

    public $journal_id = 0;
    public $journal_no;
    public $document_no;
    public $journal_date;
    public $contact_id;
    public $branch_id;
    public $currency_id;
    public $transaction_type_id;
    public $warehouse_id;
    public $journal_status;
    public $note;
    public $total;
    public $total_company_currency;


    //public $income_account_id;
    //public $inventory_account_id;
    //public $cogs_account_id;
    //public $expense_account_id;


    function gets(Journal_model $journal)
    {
        $display = isset($journal->display)? $journal->display:10;
        $page = isset($journal->page)?$journal->page:1;
        $offset = ($page-1) * $display;

        $search = isset($journal->search)? $journal->search: "";
        $search_by = isset($journal->search_by)? $journal->search_by: "journal_no";
        $search_option = isset($journal->search_option)? $journal->search_option : 'like';

        $journal_status = isset($journal->journal_status)? $journal->journal_status:0;
        $transaction_type_id = isset($journal->transaction_type_id)? $journal->transaction_type_id : 0;
        $currency_id = isset($journal->currency_id)? $journal->currency_id:0;
        $branch_id = isset($journal->branch_id)? $journal->branch_id:0;
        $warehouse_id = isset($journal->warehouse_id)? $journal->warehouse_id:0;
        $created_by = isset($journal->created_by)? $journal->created_by:0;

        $all_date = isset($journal->all_date) && $journal->all_date==1? 1 : 0;
        $date_of = isset($journal->date_of)? $journal->date_of : "journal_date";
        $from_date = isset($journal->from_date)? $journal->from_date : Date('Y-m-d');
        $to_date = isset($journal->to_date)? $journal->to_date : Date('Y-m-d');

        $sql = "SELECT j.*, ".
            "(select count(*) ".
            "from journal ".
            "where $journal_status in (0, journal_status) ".
            "and $transaction_type_id in (0, transaction_type_id) ".
            "and $currency_id in (0, currency_id) ".
            "and $branch_id in (0, branch_id) ".
            "and $warehouse_id in (0, warehouse_id) ".
            "and $created_by in (0, created_by) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && $search_by='$search') || ".
            "('$search_option'='start_with' && $search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && $search_by LIKE '%$search%' ESCAPE '!')) ".
            ($date_of==""?"": "AND (($all_date=1 && $date_of is not null) || $date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ").
            ") records ".
            "from journal j ".
            "where $journal_status in (0, j.journal_status) ".
            "and $transaction_type_id in (0, j.transaction_type_id) ".
            "and $currency_id in (0, j.currency_id) ".
            "and $branch_id in (0, j.branch_id) ".
            "and $warehouse_id in (0, j.warehouse_id) ".
            "and $created_by in (0, j.created_by) ".
            "and ('$search'='' || ".
            "('$search_option'='exact' && j.$search_by='$search') || ".
            "('$search_option'='start_with' && j.$search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && j.$search_by LIKE '%$search%' ESCAPE '!')) ".
            ($date_of==""?"": "AND (($all_date=1 && j.$date_of is not null) || j.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ").
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
            return Message_result::success_message('', null, $query->result('Journal_model'));
        }


    }

    function get(Journal_model $journal)
    {
        $this->db->select("i.*, it.journal_type_name, ig.journal_group_name, ic.journal_class_name, m.maker_name, u.unit_name, dl.warehouse_name default_lot_name");
        $this->db->from("journal i");
        $this->db->join("journal_type it","i.journal_type_id=it.journal_type_id");
        $this->db->join("journal_group ig","i.journal_group_id=ig.journal_group_id");
        $this->db->join("journal_class ic","i.journal_class_id=ic.journal_class_id");
        $this->db->join("maker m","i.maker_id=m.maker_id");
        $this->db->join("unit u","i.unit_id=u.unit_id");
        $this->db->join("warehouse dl","i.default_lot_id=dl.warehouse_id");
        $this->db->where('i.journal_id', $journal->journal_id);
        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Journal_model'));
        }
    }

    function get_by_code(Journal_model $journal)
    {
        $this->db->where('journal_code', $journal->journal_code);
        $result =$this->db->get('journal');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Journal_model'));
        }
    }

    function get_by_barcode(Journal_model $journal)
    {
        $this->db->where('barcode', $journal->barcode);
        $result =$this->db->get('journal');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Journal_model'));
        }
    }

    function is_exist(Journal_model $journal)
    {
        $this->db->where('journal_id', $journal->journal_id);

        $result =$this->db->get('journal');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_code(Journal_model $journal)
    {
        $this->db->where('journal_code', $journal->journal_code);
        $this->db->where('journal_id !=', $journal->journal_id);

        $result =$this->db->get('journal');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_barcode(Journal_model $journal)
    {
        $this->db->where('barcode', $journal->barcode);
        $this->db->where('journal_id !=', $journal->journal_id);

        $result =$this->db->get('journal');

        return $result && $result->num_rows()> 0;
    }

    function generate_code(Journal_model &$model)
    {
        if(isset($model->journal_code) && $model->journal_code!='') {
            if($model->barcode=='') $model->barcode=$model->journal_code;
            return $model->journal_code;
        }

        //$prefix = Date('ymd-');
        //$prefix = strtoupper(substr($model->journal_type, 0, 3));
        $prefix="";
        $prefix = $prefix==""?"": $prefix."-";
        $digits = "000001";
        $code = $prefix.$digits;

        $sql = "select journal_code from journal where journal_code LIKE '$prefix%' order by journal_code desc limit 1";

        $result = $this->db->query($sql);

        if($result && $result->num_rows()>0)
        {
            $number = (int) substr($result->first_row()->journal_code, strlen($prefix));
            $number ++;

            $code = $prefix.str_pad($number, strlen($digits) , "0", 0);
        }

        $model->journal_code = $code;
        if($model->barcode=='') $model->barcode=$model->journal_code;

        return $model->journal_code;
    }

    function add(Journal_model &$journal)
    {
        $this->generate_code($journal);


        if($this->is_exist_code($journal))
        {
            return Message_result::error_message('Journal code is exist');
        }

        if($this->is_exist_barcode($journal))
        {
            return Message_result::error_message('Barcode is exist');
        }

        //for mysqli driver
        unset($journal->journal_id);

        //echo $this->db->insert_string('journal', $journal); exit;

        $result=$this->db->insert('journal', $journal);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $journal->journal_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $journal);
        }

    }

    function update(Journal_model $journal)
    {
        $this->generate_code($journal);

        if($this->is_exist_code($journal))
        {
            return Message_result::error_message('Journal name is exist');
        }

        if($this->is_exist_barcode($journal))
        {
            return Message_result::error_message('Barcode is exist');
        }

        $result = $this->get($journal);

        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Journal cannot be edit');

        $this->db->where('journal_id', $journal->journal_id);

        $result = $this->db->update('journal', $journal);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $journal);
        }
    }

    function delete(Journal_model $journal)
    {
        $result = $this->get($journal);

        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Journal cannot be delete');

        $this->db->where('journal_id', $journal->journal_id);

        $result=$this->db->delete('journal');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$journal);
        }
    }

    function get_combobox_journals(Journal_model $model)
    {
        $sql = "select journal_id as 'id', journal_name as 'text' from journal where journal_name like'%$model->journal_name%' and '$model->journal_type' in ('', journal_type)";
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