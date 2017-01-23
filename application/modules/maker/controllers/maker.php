<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Maker extends My_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'maker';

        $this->load->model('Maker_model');
    }

    function index()
    {
        $this->manage_maker();
    }

    function manage_maker()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'maker_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $maker = new Maker_model();
        $maker->search_by = $search_by;
        $maker->search = $search;
        $maker->display = $display;
        $maker->page = $page;
        $result = $this->Maker_model->gets($maker);

        $data['makers'] = array();
        if($result->success)
        {
            $data['makers'] = $result->models;
        }

        $data['maker']=$maker;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('maker/manage_maker', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['maker_name'] = $this->input->post('maker_name');
        //$data['maker_id'] = $this->input->post('maker_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('maker_name', 'Maker Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('maker_id', 'Maker ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $maker_model = new Maker_model();
            Model_base::map_objects($maker_model, $_POST);

            $result = $this->Maker_model->update($maker_model);

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

        //$data['maker_name'] = $this->input->post('maker_name');
        //$data['maker_id'] = $this->input->post('maker_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('maker_name', 'Maker Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $maker_model = new Maker_model();
            Model_base::map_objects($maker_model, $_POST);

            if($maker_model->maker_id==0) $result = $this->Maker_model->add($maker_model);
            else $result = $this->Maker_model->update($maker_model);

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

        $data['maker_id'] = $this->input->post('maker_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('maker_id', 'Maker ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $maker_model = new Maker_model();
            Model_base::map_objects($maker_model, $data);

            $result = $this->Maker_model->delete($maker_model);

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

        $model = new Maker_model();
        $model->maker_name = $search;

        $result = $this->Maker_model->get_combobox_items($model);
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