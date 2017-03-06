<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item_group extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'item_group';

        $this->load->model('Item_group_model');
    }

    function index()
    {
        $this->manage_item_group();
    }

    function manage_item_group()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'item_group_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $item_group = new Item_group_model();
        $item_group->search_by = $search_by;
        $item_group->search = $search;
        $item_group->display = $display;
        $item_group->page = $page;
        $result = $this->Item_group_model->gets($item_group);

        $data['item_groups'] = array();
        if($result->success)
        {
            $data['item_groups'] = $result->models;
        }

        $data['item_group']=$item_group;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('item_group/manage_item_group', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['item_group_name'] = $this->input->post('item_group_name');
        //$data['item_group_id'] = $this->input->post('item_group_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_group_name', 'Item_group Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('item_group_id', 'Item_group ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_group_model = new Item_group_model();
            Model_base::map_objects($item_group_model, $_POST);

            $result = $this->Item_group_model->update($item_group_model);

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

        //$data['item_group_name'] = $this->input->post('item_group_name');
        //$data['item_group_id'] = $this->input->post('item_group_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_group_name', 'Item_group Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $item_group_model = new Item_group_model();
            Model_base::map_objects($item_group_model, $_POST);

            if($item_group_model->item_group_id==0) $result = $this->Item_group_model->add($item_group_model);
            else $result = $this->Item_group_model->update($item_group_model);

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

        $data['item_group_id'] = $this->input->post('item_group_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_group_id', 'Item_group ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_group_model = new Item_group_model();
            Model_base::map_objects($item_group_model, $data);

            $result = $this->Item_group_model->delete($item_group_model);

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

        $model = new Item_group_model();
        $model->item_group_name = $search;

        $result = $this->Item_group_model->get_combobox_items($model);
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