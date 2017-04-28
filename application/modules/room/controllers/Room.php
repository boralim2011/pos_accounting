<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Room extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'room';

        $this->load->model('Room_model');
        $this->load->model('Room_type_model');
    }

    function index()
    {
        $this->manage_room();
    }

    function manage_room()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'room_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $room = new Room_model();
        $room->search_by = $search_by;
        $room->search = $search;
        $room->display = $display;
        $room->page = $page;
        $room->room_type_id = isset($_POST['room_type_id']) ? $_POST['room_type_id']: 0;
        $result = $this->Room_model->gets($room);

        $data['rooms'] = array();
        if($result->success)
        {
            $data['rooms'] = $result->models;
        }

        $data['room']=$room;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('room/manage_room', $data);

    }

    function search($result_view=false)
    {
        //if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'room_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $room = new Room_model();
        $room->search_by = $search_by;
        $room->search = $search;
        $room->display = $display;
        $room->page = $page;
        $room->room_type_id = isset($_POST['room_type_id']) ? $_POST['room_type_id']: 0;
        $result = $this->Room_model->gets($room);

        $data['rooms'] = array();
        if($result->success)
        {
            $data['rooms'] = $result->models;
        }

        $data['room']=$room;

        $data = array_merge($data, $_POST);

        $data['blank_item'] = $this->get_blank_item();
        $data['result_view'] = $result_view;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $room_type = new Room_type_model();
        $result = $this->Room_type_model->gets($room_type);
        if($result->success) $data['room_types'] = $result->models;

        $view = $result_view ? "room/search_room_result":"room/search_room";

        $this->load->view($view , $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['room_name'] = $this->input->post('room_name');
        //$data['room_id'] = $this->input->post('room_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('room_name', 'Room Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('room_id', 'Room ID', 'required|greater_than[0]');
        $this->form_validation->set_rules('room_type_id', 'Room Type', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $room_model = new Room_model();
            Model_base::map_objects($room_model, $_POST);

            $result = $this->Room_model->update($room_model);

            if ($result->success)
            {
                $message = $this->create_message($result->message);
                $result->message = json_encode($message);
                echo json_encode($result);
                return true;
            }
            else
            {
                $message = $this->create_message($result->message, 'Error');
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }
        }
        else
        {
            //echo validation_errors();
            $message = $this->create_message(validation_errors(), 'Error');
            $result = new Message_result();
            $result->message = json_encode($message);
            echo json_encode($result);
            return false;
        }
    }

    function add()
    {
        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['room_name'] = $this->input->post('room_name');
        //$data['room_id'] = $this->input->post('room_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('room_name', 'Room Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('room_type_id', 'Room Type', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $room_model = new Room_model();
            Model_base::map_objects($room_model, $_POST);


            if($room_model->room_id==0) $result = $this->Room_model->add($room_model);
            else $result = $this->Room_model->update($room_model);

            if ($result->success)
            {
                $message = $this->create_message($result->message);
                $result->message = json_encode($message);
                echo json_encode($result);
                return true;
            }
            else
            {
                $message = $this->create_message($result->message, 'Error');
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }
        }
        else
        {
            //echo validation_errors();
            $message = $this->create_message(validation_errors(), 'Error');
            $result = new Message_result();
            $result->message = json_encode($message);
            echo json_encode($result);
            return false;
        }
    }

    function delete()
    {
        if(!isset($_POST['submit'])) {
            $this->show_404();return;
        }

        $data['room_id'] = $this->input->post('room_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('room_id', 'Room ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $room_model = new Room_model();
            Model_base::map_objects($room_model, $data);

            $result = $this->Room_model->delete($room_model);

            if ($result->success)
            {
                $message = $this->create_message($result->message);
                $result->message = json_encode($message);
                echo json_encode($result);
                return true;
            }
            else
            {
                $message = $this->create_message($result->message, 'Error');
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }
        }
        else
        {
            //echo validation_errors();
            $message = $this->create_message(validation_errors(), 'Error');
            $result = new Message_result();
            $result->message = json_encode($message);
            echo json_encode($result);
            return false;
        }
    }



    function get_combobox_items($search='')
    {
        $search = $search!=''? $search : strip_tags(trim($_GET['q']));

        $model = new Room_model();
        $model->room_name = $search;

        $result = $this->Room_model->get_combobox_items($model);
        if($result->success)
        {
            $data = $result->models;
        }
        else
        {
            $data[] = array('id' => '0', 'text' => 'No Data Found');
        }

        echo json_encode($data);
    }

}