<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Age_range extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'age_range';

        $this->load->model('Age_range_model');
    }

    function index()
    {
        $this->manage_age_range();
    }

    function manage_age_range()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'age_range_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $age_range = new Age_range_model();
        $age_range->search_by = $search_by;
        $age_range->search = $search;
        $age_range->display = $display;
        $age_range->page = $page;
        $result = $this->Age_range_model->gets($age_range);

        $data['age_ranges'] = array();
        if($result->success)
        {
            $data['age_ranges'] = $result->models;
        }

        $data['age_range']=$age_range;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('age_range/manage_age_range', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['age_range_name'] = $this->input->post('age_range_name');
        //$data['age_range_id'] = $this->input->post('age_range_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('age_range_name', 'Age_range Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('age_range_id', 'Age_range ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $age_range_model = new Age_range_model();
            Model_base::map_objects($age_range_model, $_POST);

            $result = $this->Age_range_model->update($age_range_model);

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

        //$data['age_range_name'] = $this->input->post('age_range_name');
        //$data['age_range_id'] = $this->input->post('age_range_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('age_range_name', 'Age_range Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $age_range_model = new Age_range_model();
            Model_base::map_objects($age_range_model, $_POST);

            if($age_range_model->age_range_id==0) $result = $this->Age_range_model->add($age_range_model);
            else $result = $this->Age_range_model->update($age_range_model);

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

        $data['age_range_id'] = $this->input->post('age_range_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('age_range_id', 'Age_range ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $age_range_model = new Age_range_model();
            Model_base::map_objects($age_range_model, $data);

            $result = $this->Age_range_model->delete($age_range_model);

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

        $model = new Age_range_model();
        $model->age_range_name = $search;

        $result = $this->Age_range_model->get_combobox_items($model);
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