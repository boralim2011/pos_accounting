<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Item_member_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

    }

    public $item_member_id = 0;
    public $member_id=0;
    public $item_id=0;
    public $qty=0;

    function gets(Item_member_model $item_member=null)
    {

        $item_id = $item_member==null? $this->item_id : $item_member->item_id;

        $sql = "SELECT im.*, i.item_name, i.item_code, m.item_name member_name, m.item_code member_code ".
            "from item_member im ".
            "join item i on i.item_id=im.item_id ".
            "join item m on m.item_id=im.member_id ".
            "where i.item_id=$item_id"
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Item_member_model'));
        }


    }

    function get(Item_member_model $item_member=null)
    {
        $item_member_id = $item_member==null? $this->item_member_id : $item_member->item_member_id;

        $this->db->select("im.*, i.item_name, i.item_code, m.item_name member_name, m.item_code member_code ");
        $this->db->from("item_member im");
        $this->db->join("item i","i.item_id=im.item_id", "left");
        $this->db->join("item m","m.item_id=im.member_id", "left");
        $this->db->where('im.item_member_id', $item_member_id);

        $result =$this->db->get();

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Item_member_model'));
        }
    }

    function is_exist(Item_member_model $item_member = null)
    {
        $item_member_id = $item_member==null? $this->item_member_id : $item_member->item_member_id;

        $this->db->where('item_member_id', $item_member_id);

        $result =$this->db->get('item_member');

        return $result && $result->num_rows()> 0;
    }

    function add(Item_member_model &$item_member)
    {

        //for mysqli driver
        unset($item_member->item_member_id);

        //$query= $this->db->insert_string('item_member', $item_member);

        //echo $query; return;

        //$result = $this->db->query($query);
        $result=$this->db->insert('item_member', $item_member);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $item_member->item_member_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $item_member);
        }

    }

    function update(Item_member_model $item_member)
    {

        //$result = $this->get($item_member);
        //if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Item member cannot be edit');

        $this->db->where('item_member_id', $item_member->item_member_id);

        $result = $this->db->update('item_member', $item_member);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $item_member);
        }
    }

    function delete(Item_member_model $item_member=null)
    {
        $item_member_id = $item_member==null? $this->item_member_id : $item_member->item_member_id;
        $this->db->where('item_member_id', $item_member_id);

        $result=$this->db->delete('item_member');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$item_member);
        }
    }

}