<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contact_type extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'contact_type';

        $this->load->model('Contact_type_model');
    }

    function index()
    {
        $this->manage_contact_type();
    }

    function manage_contact_type()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'contact_type_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $contact_type = new Contact_type_model();
        $contact_type->search_by = $search_by;
        $contact_type->search = $search;
        $contact_type->display = $display;
        $contact_type->page = $page;
        $result = $this->Contact_type_model->gets($contact_type);

        $data['contact_types'] = array();
        if($result->success)
        {
            $data['contact_types'] = $result->models;
        }

        $data['contact_type']=$contact_type;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('contact_type/manage_contact_type', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['contact_type_name'] = $this->input->post('contact_type_name');
        //$data['contact_type_id'] = $this->input->post('contact_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('contact_type_name', 'Contact_type Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('contact_type_id', 'Contact_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $contact_type_model = new Contact_type_model();
            Model_base::map_objects($contact_type_model, $_POST);

            $result = $this->Contact_type_model->update($contact_type_model);

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

        //$data['contact_type_name'] = $this->input->post('contact_type_name');
        //$data['contact_type_id'] = $this->input->post('contact_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('contact_type_name', 'Contact_type Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $contact_type_model = new Contact_type_model();
            Model_base::map_objects($contact_type_model, $_POST);

            if($contact_type_model->contact_type_id==0) $result = $this->Contact_type_model->add($contact_type_model);
            else $result = $this->Contact_type_model->update($contact_type_model);

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

        $data['contact_type_id'] = $this->input->post('contact_type_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('contact_type_id', 'Contact_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $contact_type_model = new Contact_type_model();
            Model_base::map_objects($contact_type_model, $data);

            $result = $this->Contact_type_model->delete($contact_type_model);

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

        $model = new Contact_type_model();
        $model->contact_type_name = $search;

        $result = $this->Contact_type_model->get_combobox_items($model);
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