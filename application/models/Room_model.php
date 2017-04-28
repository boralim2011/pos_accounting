<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Room_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $room_id = 0;
    public $room_name = '';
    public $room_name_kh = '';
    public $room_type_id;
    public $image;

    function gets(Room_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'room_name';

        $this->db->select("room.*, room_type.room_type_name, ".
            "(select count(*) from room where ".
            ($model->room_type_id>0?"room.room_type_id=$model->room_type_id and ":"").
            "$field like '%$search%') 'records' "
        );
        $this->db->from('room');
        $this->db->join('room_type', 'room.room_type_id=room_type.room_type_id');
        if($model->room_type_id>0) $this->db->where("room.room_type_id", "$model->room_type_id");
        $this->db->order_by("room.room_name", "asc");
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);

        $query = $this->db->get();

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Room_model'));
        }
    }

    function get(Room_model $model)
    {
        $this->db->where('room_id', $model->room_id);

        $result =$this->db->get('room');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Room_model'));
        }
    }

    function get_by_name(Room_model $model)
    {
        $this->db->where('room_name', $model->room_name);

        $result =$this->db->get('room');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Room_model'));
        }
    }

    function is_exist(Room_model $room)
    {
        $this->db->where('room_id', $room->room_id);

        $result =$this->db->get('room');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Room_model $room)
    {
        $this->db->where('room_name', $room->room_name);
        $this->db->where('room_id !=', $room->room_id);

        $result =$this->db->get('room');

        return $result && $result->num_rows()> 0;
    }

    function add(Room_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Room name is exist');
        }

        //for mysqli driver
        unset($model->room_id);

        $result=$this->db->insert('room', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->room_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Room_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Room name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Room cannot be edited');

        $this->db->where('room_id', $model->room_id);

        $result = $this->db->update('room', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Room_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Room cannot be delete');

        $this->db->where('room_id', $model->room_id);

        $result=$this->db->delete('room');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Room_model $model)
    {
        $sql = "select room_id as 'id', room_name as 'text' from room where room_name like'%$model->room_name%'";
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