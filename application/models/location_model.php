<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Location_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $location_id = 0;
    public $location_code = '';
    public $location_name = '';
    public $location_name_kh = '';
    public $location_type_id = 2;
    public $parent_location_id = 1;
    public $is_deletable = 1;
    public $late = 0;
    public $long = 0;

    function gets(Location_model $model)
    {
        $display_count = isset($model->display_count)? $model->display_count:10;
        $page = isset($model->page)?$model->page:1;
        $offset = ($page-1) * $display_count;

        $location_name = $model->escape_str($model->location_name);

        $sql= "select l.*, p.location_name as 'parent_location', lt.location_type_name as 'location_type', ".
              "(select count(*) from location where location_name like '%$location_name%' ".
              "and '$model->location_type_id' in ('', 0 , location_type_id) ) 'records' ".
              "from location l join location p on p.location_id = l.parent_location_id ".
              "join location_type lt on lt.location_type_id = l.location_type_id ".
              "where l.location_name like '%$location_name%' ".
              "and '$model->location_type_id' in ('', 0 , l.location_type_id) "
             ."limit $offset,$display_count"
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Location_model'));
        }
    }

    function get(Location_model $model)
    {
        $this->db->where('location_id', $model->location_id);

        $result =$this->db->get('location');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Location_model'));
        }
    }

    function get_by_name(Location_model $model)
    {
        $this->db->where('location_name', $model->location_name);
        $this->db->where('parent_location_id', $model->parent_location_id);

        $result =$this->db->get('location');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Location_model'));
        }
    }

    function is_exist(Location_model $location)
    {
        $this->db->where('location_id', $location->location_id);

        $result =$this->db->get('location');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_name(Location_model $location)
    {
        $this->db->where('location_name', $location->location_name);
        $this->db->where('parent_location_id', $location->parent_location_id);
        $this->db->where('location_id !=', $location->location_id);

        $result =$this->db->get('location');

        return $result && $result->num_rows()> 0;
    }

    function add(Location_model &$model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Location name is exist');
        }

        //for mysqli driver
        unset($model->location_id);

        $result=$this->db->insert('location', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->location_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Location_model $model)
    {
        if($this->is_exist_name($model))
        {
            return Message_result::error_message('Location name is exist'.$this->db->last_query());
        }

        $result = $this->get($model);
        if(!$result->success || $result->model->is_editable==0) return Message_result::error_message('Location cannot be edit');

        $this->db->where('location_id', $model->location_id);

        $result = $this->db->update('location', $model);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Location_model $model)
    {
        $result = $this->get($model);
        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Location cannot be delete');

        $this->db->where('location_id', $model->location_id);

        $result=$this->db->delete('location');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

    function get_combobox_items(Location_model $model)
    {
        $sql = "select location_id as 'id', location_name as 'text' ".
               "from location where location_name like'%$model->location_name%' ".
               "and ('$model->parent_location_id'=0 or parent_location_id in ($model->parent_location_id) ) ".
               "and ('$model->location_type_id'=0 or location_type_id in ($model->location_type_id) ) "
        ;
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

    function get_location_types($search='')
    {
        $sql = "select location_type_id as 'id', location_type_name as 'text' ".
            "from location_type where location_type_name like'%$search%' ";
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

    function get_default_country(){

        $model = new Location_model();
        $model->location_id = 2; //Cambodia
        $result = $this->get($model);
        if($result->success) return $result->model;

        return false;
    }

}