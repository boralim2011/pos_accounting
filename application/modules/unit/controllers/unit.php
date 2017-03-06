<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unit extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'unit';

        $this->load->model('Unit_model');
    }

    function index()
    {
        $this->manage_unit();
    }

    function manage_unit()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'unit_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $unit = new Unit_model();
        $unit->search_by = $search_by;
        $unit->search = $search;
        $unit->display = $display;
        $unit->page = $page;
        $result = $this->Unit_model->gets($unit);

        $data['units'] = array();
        if($result->success)
        {
            $data['units'] = $result->models;
        }

        $data['unit']=$unit;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('unit/manage_unit', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['unit_name'] = $this->input->post('unit_name');
        //$data['unit_id'] = $this->input->post('unit_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('unit_id', 'Unit ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $unit_model = new Unit_model();
            Model_base::map_objects($unit_model, $_POST);

            $result = $this->Unit_model->update($unit_model);

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

        //$data['unit_name'] = $this->input->post('unit_name');
        //$data['unit_id'] = $this->input->post('unit_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('unit_name', 'Unit Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $unit_model = new Unit_model();
            Model_base::map_objects($unit_model, $_POST);

            if($unit_model->unit_id==0) $result = $this->Unit_model->add($unit_model);
            else $result = $this->Unit_model->update($unit_model);

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

        $data['unit_id'] = $this->input->post('unit_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('unit_id', 'Unit ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $unit_model = new Unit_model();
            Model_base::map_objects($unit_model, $data);

            $result = $this->Unit_model->delete($unit_model);

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

        $model = new Unit_model();
        $model->unit_name = $search;

        $result = $this->Unit_model->get_combobox_items($model);
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