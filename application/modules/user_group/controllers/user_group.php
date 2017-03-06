<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_group extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'user_group';

        $this->load->model('User_group_model');
    }

    function index()
    {
        $this->manage_user_group();
    }

    function manage_user_group()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $user_group = new User_group_model();
        $user_group->user_group_name = $search;
        $user_group->display = $display;
        $user_group->page = $page;
        $result = $this->User_group_model->gets($user_group);

        $data['user_groups'] = array();
        if($result->success)
        {
            $data['user_groups'] = $result->models;
        }

        $data['user_group']=$user_group;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('user_group/manage_user_group', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['user_group_name'] = $this->input->post('user_group_name');
        //$data['user_group_id'] = $this->input->post('user_group_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('user_group_name', 'User Group Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('user_group_id', 'User Group ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $user_group_model = new User_group_model();
            Model_base::map_objects($user_group_model, $_POST);

            $result = $this->User_group_model->update($user_group_model);

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

        //$data['user_group_name'] = $this->input->post('user_group_name');
        //$data['user_group_id'] = $this->input->post('user_group_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('user_group_name', 'User Group Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $user_group_model = new User_group_model();
            Model_base::map_objects($user_group_model, $_POST);

            if($user_group_model->user_group_id==0) $result = $this->User_group_model->add($user_group_model);
            else $result = $this->User_group_model->update($user_group_model);

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

        $data['user_group_id'] = $this->input->post('user_group_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('user_group_id', 'User Group ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $user_group_model = new User_group_model();
            Model_base::map_objects($user_group_model, $data);

            $result = $this->User_group_model->delete($user_group_model);

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

        $model = new User_group_model();
        $model->user_group_name = $search;

        $result = $this->User_group_model->get_combobox_items($model);
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