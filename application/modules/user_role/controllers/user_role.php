<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_role extends My_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'user_role';

        $this->load->model('User_role_model');
    }

    function index()
    {
        $this->manage_user_role();
    }

    function manage_user_role()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $user_role = new User_role_model();
        $user_role->user_role_name = $search;
        $user_role->display = $display;
        $user_role->page = $page;

        $result = $this->User_role_model->gets($user_role);

        $data['user_roles'] = array();
        if($result->success)
        {
            $data['user_roles'] = $result->models;
        }

        $data['user_role']=$user_role;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;

        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('user_role/manage_user_role', $data);
    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['user_role_name'] = $this->input->post('user_role_name');
        //$data['user_role_id'] = $this->input->post('user_role_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('user_role_name', 'User Role Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('user_role_id', 'User Role ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $user_role_model = new User_role_model();
            Model_base::map_objects($user_role_model, $_POST);

            $result = $this->User_role_model->update($user_role_model);

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

        //$data['user_role_name'] = $this->input->post('user_role_name');
        //$data['user_role_id'] = $this->input->post('user_role_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('user_role_name', 'User Role Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $user_role_model = new User_role_model();
            Model_base::map_objects($user_role_model, $_POST);

            if($user_role_model->user_role_id==0) $result = $this->User_role_model->add($user_role_model);
            else $result = $this->User_role_model->update($user_role_model);

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

        $data['user_role_id'] = $this->input->post('user_role_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('user_role_id', 'User Role ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $user_role_model = new User_role_model();
            Model_base::map_objects($user_role_model, $data);

            $result = $this->User_role_model->delete($user_role_model);

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

        $model = new User_role_model();
        $model->user_role_name = $search;

        $result = $this->User_role_model->get_combobox_items($model);
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