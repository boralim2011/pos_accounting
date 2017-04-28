<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Room_type_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $room_type_id = 0;
    public $room_type_name = '';
    public $room_type_name_kh = '';
    public $image;

    function gets(Room_type_model $model)
    {

        $display = isset($model->display)? $model->display:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display;

        $search = isset($model->search)? $model->search:'';
        $field = isset($model->search_by)? $model->search_by: 'room_type_name';

        $this->db->select("room_type.*, ".
            "(select count(*) from room_type where $field like '%$search%') 'records' "
        );
        $this->db->from('room_type');
        $this->db->like("$field", "$search");
        $this->db->limit($display, $offset);

        //echo $this->db->get_compiled_select();  exit;

        $query = $this->db->get();

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Room_type_model'));
        }
    }

    function get(Room_type_model $model)
    {
        $this->db->where('room_type_id', $model->room_type_id);

        $result =$this->db->get('room_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Room_type_model'));
        }
    }

    function get_by_name(Room_type_model $model)
    {
        $this->db->where('room_type_name', $model->room_type_name);

        $result =$this->db->get('room_type');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Room_type_model'));
        }
    }

    function is_exist(Room_type_model $room_type)
    {
        $this->db->where('room_type_id', $room_type->room_type_id);

        $result =$this->db->get('room_type');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Room_type_model $room_type)
    {
        $this->db->where('room_type_name', $room_type->room_type_name);
        $this->db->where('room_type_id !=', $room_type->room_type_id);

        $result =$this->db->get('room_type');

        return $result && $result->num_rows()> 0;
    }

    function add(Room_type_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Room_type name is exist');
        }

        //for mysqli driver
        unset($model->room_type_id);

        //echo $this->db->insert_string('room_type', $model); exit;

        $result=$this->db->insert('room_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->room_type_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Room_type_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Room_type name is exist');
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Room_type cannot be edited');

        $this->db->where('room_type_id', $model->room_type_id);

        $result = $this->db->update('room_type', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Room_type_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Room_type cannot be delete');

        $this->db->where('room_type_id', $model->room_type_id);

        $result=$this->db->delete('room_type');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Room_type_model $model)
    {
        $sql = "select room_type_id as 'id', room_type_name as 'text' from room_type where room_type_name like'%$model->room_type_name%'";
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