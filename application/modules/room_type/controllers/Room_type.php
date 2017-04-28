<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Room_type extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'room_type';

        $this->load->model('Room_type_model');
    }

    function index()
    {
        $this->manage_room_type();
    }

    function manage_room_type()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'room_type_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $room_type = new Room_type_model();
        $room_type->search_by = $search_by;
        $room_type->search = $search;
        $room_type->display = $display;
        $room_type->page = $page;
        $result = $this->Room_type_model->gets($room_type);

        $data['room_types'] = array();
        if($result->success)
        {
            $data['room_types'] = $result->models;
        }

        $data['room_type']=$room_type;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('room_type/manage_room_type', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['room_type_name'] = $this->input->post('room_type_name');
        //$data['room_type_id'] = $this->input->post('room_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('room_type_name', 'Room_type Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('room_type_id', 'Room_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $room_type_model = new Room_type_model();
            Model_base::map_objects($room_type_model, $_POST);

            $result = $this->Room_type_model->update($room_type_model);

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

        //$data['room_type_name'] = $this->input->post('room_type_name');
        //$data['room_type_id'] = $this->input->post('room_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('room_type_name', 'Room_type Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $room_type_model = new Room_type_model();
            Model_base::map_objects($room_type_model, $_POST);

            if($room_type_model->room_type_id==0) $result = $this->Room_type_model->add($room_type_model);
            else $result = $this->Room_type_model->update($room_type_model);

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

        $data['room_type_id'] = $this->input->post('room_type_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('room_type_id', 'Room_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $room_type_model = new Room_type_model();
            Model_base::map_objects($room_type_model, $data);

            $result = $this->Room_type_model->delete($room_type_model);

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

        $model = new Room_type_model();
        $model->room_type_name = $search;

        $result = $this->Room_type_model->get_combobox_items($model);
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