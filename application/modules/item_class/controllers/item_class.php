<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item_class extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'item_class';

        $this->load->model('Item_class_model');
    }

    function index()
    {
        $this->manage_item_class();
    }

    function manage_item_class()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'item_class_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $item_class = new Item_class_model();
        $item_class->search_by = $search_by;
        $item_class->search = $search;
        $item_class->display = $display;
        $item_class->page = $page;
        $result = $this->Item_class_model->gets($item_class);

        $data['item_classs'] = array();
        if($result->success)
        {
            $data['item_classs'] = $result->models;
        }

        $data['item_class']=$item_class;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('item_class/manage_item_class', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['item_class_name'] = $this->input->post('item_class_name');
        //$data['item_class_id'] = $this->input->post('item_class_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_class_name', 'Item_class Name', 'trim|required|min_length[1]|max_length[100]');
        $this->form_validation->set_rules('item_class_id', 'Item_class ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_class_model = new Item_class_model();
            Model_base::map_objects($item_class_model, $_POST);

            $result = $this->Item_class_model->update($item_class_model);

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

        //$data['item_class_name'] = $this->input->post('item_class_name');
        //$data['item_class_id'] = $this->input->post('item_class_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_class_name', 'Item_class Name', 'trim|required|min_length[1]|max_length[100]');

        if ($this->form_validation->run())
        {
            $item_class_model = new Item_class_model();
            Model_base::map_objects($item_class_model, $_POST);

            if($item_class_model->item_class_id==0) $result = $this->Item_class_model->add($item_class_model);
            else $result = $this->Item_class_model->update($item_class_model);

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

        $data['item_class_id'] = $this->input->post('item_class_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_class_id', 'Item_class ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_class_model = new Item_class_model();
            Model_base::map_objects($item_class_model, $data);

            $result = $this->Item_class_model->delete($item_class_model);

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

        $model = new Item_class_model();
        $model->item_class_name = $search;

        $result = $this->Item_class_model->get_combobox_items($model);
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