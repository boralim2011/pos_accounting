<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Warehouse extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'warehouse';

        $this->load->model('Warehouse_model');
    }

    function index()
    {
        $this->manage_warehouse();
    }

    function manage_warehouse()
    {
        //if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'warehouse_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $is_warehouse = isset($_POST['is_warehouse'])? $_POST['is_warehouse'] : 0 ;

        $warehouse = new Warehouse_model();
        $warehouse->search_by = $search_by;
        $warehouse->search = $search;
        $warehouse->display = $display;
        $warehouse->page = $page;
        $warehouse->is_warehouse = $is_warehouse;

        $result = $this->Warehouse_model->gets($warehouse);

        $data['warehouses'] = array();
        if($result->success)
        {
            $data['warehouses'] = $result->models;
        }

        $data['warehouse']=$warehouse;

        $data['is_warehouse'] = $is_warehouse;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('warehouse/manage_warehouse', $data);

    }

    function edit_lot()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['warehouse_name'] = $this->input->post('warehouse_name');
        //$data['warehouse_id'] = $this->input->post('warehouse_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('parent_id', 'Warehouse Name', 'required|greater_than[0]');
        $this->form_validation->set_rules('warehouse_name', 'Lot Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('warehouse_id', 'Lot ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $warehouse_model = new Warehouse_model();
            Model_base::map_objects($warehouse_model, $_POST);
            $warehouse_model->is_warehouse = 0;

            $result = $this->Warehouse_model->update($warehouse_model);

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


    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['warehouse_name'] = $this->input->post('warehouse_name');
        //$data['warehouse_id'] = $this->input->post('warehouse_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('warehouse_name', 'Warehouse Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('warehouse_id', 'Warehouse ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $warehouse_model = new Warehouse_model();
            Model_base::map_objects($warehouse_model, $_POST);
            $warehouse_model->is_warehouse = 1;

            $result = $this->Warehouse_model->update($warehouse_model);

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

    function add_lot()
    {
        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['warehouse_name'] = $this->input->post('warehouse_name');
        //$data['warehouse_id'] = $this->input->post('warehouse_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('parent_id', 'Warehouse Name', 'required|greater_than[0]');
        $this->form_validation->set_rules('warehouse_name', 'Lot Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $warehouse_model = new Warehouse_model();
            Model_base::map_objects($warehouse_model, $_POST);

            $warehouse_model->is_warehouse = 0;

            if($warehouse_model->warehouse_id==0) $result = $this->Warehouse_model->add($warehouse_model);
            else $result = $this->Warehouse_model->update($warehouse_model);

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

        //$data['warehouse_name'] = $this->input->post('warehouse_name');
        //$data['warehouse_id'] = $this->input->post('warehouse_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('warehouse_name', 'Warehouse Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $warehouse_model = new Warehouse_model();
            Model_base::map_objects($warehouse_model, $_POST);

            $warehouse_model->is_warehouse = 1;

            if($warehouse_model->warehouse_id==0) $result = $this->Warehouse_model->add($warehouse_model);
            else $result = $this->Warehouse_model->update($warehouse_model);

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

        $data['warehouse_id'] = $this->input->post('warehouse_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('warehouse_id', 'Warehouse ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $warehouse_model = new Warehouse_model();
            Model_base::map_objects($warehouse_model, $data);

            $result = $this->Warehouse_model->delete($warehouse_model);

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
        $is_warehouse =isset($_GET['is_warehouse'])? $_GET['is_warehouse']: 0;

        $model = new Warehouse_model();
        $model->warehouse_name = $search;
        $model->is_warehouse = $is_warehouse;

        $result = $this->Warehouse_model->get_combobox_items($model);
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