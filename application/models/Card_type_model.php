<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Card_type_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $card_type_id = 0;
    public $card_type_name = '';
    public $card_type_name_kh = '';

    function gets(Card_type_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'card_type_name';

        $this->db->select("card_type.*, ".
            "(select count(*) from card_type where $field like '%$search%') 'records' "
        );
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);
        $query = $this->db->get('card_type');

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Card_type_model'));
        }
    }

    function get(Card_type_model $model)
    {
        $this->db->where('card_type_id', $model->card_type_id);

        $result =$this->db->get('card_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Card_type_model'));
        }
    }

    function get_by_name(Card_type_model $model)
    {
        $this->db->where('card_type_name', $model->card_type_name);

        $result =$this->db->get('card_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Card_type_model'));
        }
    }

    function is_exist(Card_type_model $card_type)
    {
        $this->db->where('card_type_id', $card_type->card_type_id);

        $result =$this->db->get('card_type');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Card_type_model $card_type)
    {
        $this->db->where('card_type_name', $card_type->card_type_name);
        $this->db->where('card_type_id !=', $card_type->card_type_id);

        $result =$this->db->get('card_type');

        return $result && $result->num_rows()> 0;
    }

    function add(Card_type_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Card_type name is exist');
        }

        //for mysqli driver
        unset($model->card_type_id);

        $result=$this->db->insert('card_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->card_type_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Card_type_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Card_type name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Card_type cannot be edited');

        $this->db->where('card_type_id', $model->card_type_id);

        $result = $this->db->update('card_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Card_type_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Card_type cannot be delete');

        $this->db->where('card_type_id', $model->card_type_id);

        $result=$this->db->delete('card_type');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Card_type_model $model)
    {
        $sql = "select card_type_id as 'id', card_type_name as 'text' from card_type where card_type_name like'%$model->card_type_name%'";
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