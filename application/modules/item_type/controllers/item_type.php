<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item_type extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'item_type';

        $this->load->model('Item_type_model');
    }

    function index()
    {
        $this->manage_item_type();
    }

    function manage_item_type()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'item_type_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $item_type = new Item_type_model();
        $item_type->search_by = $search_by;
        $item_type->search = $search;
        $item_type->display = $display;
        $item_type->page = $page;
        $result = $this->Item_type_model->gets($item_type);

        $data['item_types'] = array();
        if($result->success)
        {
            $data['item_types'] = $result->models;
        }

        $data['item_type']=$item_type;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('item_type/manage_item_type', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['item_type_name'] = $this->input->post('item_type_name');
        //$data['item_type_id'] = $this->input->post('item_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_type_name', 'Item_type Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('item_type_id', 'Item_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_type_model = new Item_type_model();
            Model_base::map_objects($item_type_model, $_POST);
            $item_type_model->manage_stock = isset($_POST['manage_stock'])?1:0;

            $result = $this->Item_type_model->update($item_type_model);

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

        //$data['item_type_name'] = $this->input->post('item_type_name');
        //$data['item_type_id'] = $this->input->post('item_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_type_name', 'Item_type Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $item_type_model = new Item_type_model();
            Model_base::map_objects($item_type_model, $_POST);

            $item_type_model->manage_stock = isset($_POST['manage_stock'])?1:0;

            if($item_type_model->item_type_id==0) $result = $this->Item_type_model->add($item_type_model);
            else $result = $this->Item_type_model->update($item_type_model);

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

        $data['item_type_id'] = $this->input->post('item_type_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('item_type_id', 'Item_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_type_model = new Item_type_model();
            Model_base::map_objects($item_type_model, $data);

            $result = $this->Item_type_model->delete($item_type_model);

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

        $model = new Item_type_model();
        $model->item_type_name = $search;
        $model->parent_id = isset($_GET['parent_id'])?$_GET['parent_id']:0;

        $result = $this->Item_type_model->get_combobox_items($model);
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