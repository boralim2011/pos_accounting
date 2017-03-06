<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Location extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'location';

        $this->load->model('Location_model');
    }

    function index()
    {
        $this->manage_location();
    }

    function manage_location($type=2, $display_count=10, $page=1)
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $data['locations'] = array();

        $location = new Location_model();
        $location->location_name = isset($_POST['name'])?$_POST['name']:'';
        $location->location_type_id = $type;
        $location->display_count = $display_count;
        $location->page = $page;

        $result = $this->Location_model->gets($location);
        if($result->success)
        {
            $data['locations'] = $result->models;
        }

        $data['location']=$location;

        //Pagination
        $data['display_count'] = $display_count;
        $data['page'] = $page;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display_count): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        //echo print_r($data);

        $this->load->view('location/manage_location', $data);
    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

//        $data['location_id'] = $this->input->post('location_id');
//        $data['location_name'] = $this->input->post('location_name');
//        $data['location_code'] = $this->input->post('location_code');
//        $data['location_type_id'] = $this->input->post('location_type');
//        $data['parent_location_id'] = $this->input->post('parent_location');
//        $data = $this->security->xss_clean($data);

        $this->form_validation->set_rules('location_name', 'Location Name', 'trim|required|min_length[3]|max_length[100]');
        //$this->form_validation->set_rules('location_code', 'Location Code', 'trim|required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('location_type', 'Location Type', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('parent_location', 'Parent Location', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('location_id', 'Location ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $location_model = new Location_model();
            Model_base::map_objects($location_model, $_POST);
            $location_model->parent_location_id = $this->input->post('parent_location');
            $location_model->location_type_id = $this->input->post('location_type');

            $result = $this->Location_model->update($location_model);

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

//        $data['location_id'] = $this->input->post('location_id');
//        $data['location_name'] = $this->input->post('location_name');
//        $data['location_code'] = $this->input->post('location_code');
//        $data['location_type_id'] = $this->input->post('location_type');
//        $data['parent_location_id'] = $this->input->post('parent_location');
//        $data = $this->security->xss_clean($data);

        $this->form_validation->set_rules('location_name', 'Location Name', 'trim|required|min_length[3]|max_length[100]');
        //$this->form_validation->set_rules('location_code', 'Location Code', 'trim|required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('location_type', 'Location Type', 'trim|required|greater_than[0]');
        $this->form_validation->set_rules('parent_location', 'Parent Location', 'trim|required|greater_than[0]');


        if ($this->form_validation->run())
        {
            $location_model = new Location_model();
            Model_base::map_objects($location_model, $_POST);
            $location_model->parent_location_id = $this->input->post('parent_location');
            $location_model->location_type_id = $this->input->post('location_type');

            if($location_model->location_id==0) $result = $this->Location_model->add($location_model);
            else $result = $this->Location_model->update($location_model);

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

        $data['location_id'] = $this->input->post('location_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('location_id', 'Location ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $location_model = new Location_model();
            Model_base::map_objects($location_model, $data);

            $result = $this->Location_model->delete($location_model);

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



    function get_combobox_items($type=0, $parent_id=0, $search='')
    {
        $search = isset($_GET['q'])? strip_tags(trim($_GET['q'])): $search!='';
        $parent = isset($_GET['parent'])? $_GET['parent']: $parent_id;
        $type = isset($_GET['type'])? $_GET['type']: $type;

        $model = new Location_model();
        $model->location_name = $search;
        $model->parent_location_id = $parent;
        $model->location_type_id = $type;

        $result = $this->Location_model->get_combobox_items($model);
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

    function get_location_types( $search='')
    {
        $search = isset($_GET['q'])? strip_tags(trim($_GET['q'])): $search;

        $result = $this->Location_model->get_location_types($search);
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