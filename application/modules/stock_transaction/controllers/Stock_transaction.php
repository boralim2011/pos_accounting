<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stock_transaction extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'stock_transaction';

        $this->load->model('Journal_model');
        $this->load->model('Transaction_type_model');
        $this->load->model('Warehouse_model');
        $this->load->model('User_model');

    }


    function index()
    {
        $this->manage_stock_transaction();
    }

    function manage_stock_transaction()
    {
        //if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['stock_transactions'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'journal_no';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

        $all_date = isset($_POST['all_date']) && $_POST['all_date']==1? 1 : 0;
        $date_of = isset($_POST['date_of'])? $_POST['date_of'] : "journal_date";
        $from_date = isset($_POST['from_date'])? $_POST['from_date'] : Date('Y-m-d');
        $to_date = isset($_POST['to_date'])? $_POST['to_date'] : Date('Y-m-d');

        $stock_transaction = new Journal_model();
        if(isset($_POST['submit']))
        {
            Model_base::map_objects($stock_transaction, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $stock_transaction->search_by = $search_by;
        $stock_transaction->search = $search;
        $stock_transaction->display = $display;
        $stock_transaction->page = $page;
        $stock_transaction->seach_option = $search_option;

        $stock_transaction->all_date = $all_date;
        $stock_transaction->date_of = $date_of;
        $stock_transaction->from_date = $from_date;
        $stock_transaction->to_date = $to_date;

        $result = $stock_transaction->gets($stock_transaction);
        if($result->success)$data['stock_transactions'] = $result->models;

        $data['all_date'] = $all_date;
        $data['date_of'] = $date_of;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['search_option'] = $search_option;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $transaction_type = new Transaction_type_model();
        $result = $this->Transaction_type_model->gets($transaction_type);
        if($result->success) $data['transaction_types'] = $result->models;

        $warehouse = new Warehouse_model();
        $result = $this->Warehouse_model->gets($warehouse);
        if($result->success) $data['warehouses'] = $result->models;

        $user = new User_model();
        $result = $this->User_model->gets($user);
        if($result->success) $data['users'] = $result->models;


        $this->load->view('stock_transaction/manage_stock_transaction', $data);

    }

    private function get_stock_transaction_path()
    {
        return $this->get_file_path()."stock_transactions/";
    }

    private function get_stock_transaction_site()
    {
        return $this->get_file_site()."stock_transactions/";
    }

    private function upload_image(Journal_transaction &$stock_transaction, $delete_if_exist = true)
    {
        if(isset($_FILES['file']) && $_FILES['file']['name'] != '')
        {
            $this->Journal_transaction->generate_code($stock_transaction);
            $file_name = $_FILES['file']['name'];
            $file_name = $stock_transaction->stock_transaction_code.".".pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = $this->get_stock_transaction_path();

            //delete old file
            if($delete_if_exist && !$this->delete_file($file_path.$file_name)) return false;

            $upload = $this->upload_file($file_path , $file_name);
            if(!$upload) return false;

            $stock_transaction->image = $file_name;
        }

        return true;
    }


    function edit($stock_transaction_id = 0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data=array();

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('stock_transaction_id', 'Stock_Transaction ID', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('stock_transaction_name', 'Stock_Transaction Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('stock_transaction_type_id', 'Stock_Transaction Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('stock_transaction_group_id', 'Stock_Transaction Group', 'required|greater_than[0]');
            $this->form_validation->set_rules('stock_transaction_class_id', 'Stock_Transaction Class', 'required|greater_than[0]');
            //$this->form_validation->set_rules('purchasing_price', 'Purchasing Price', 'required');
            $this->form_validation->set_rules('selling_price', 'Selling Price', 'required');
            $this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            $this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');


            if ($this->form_validation->run())
            {
                $stock_transaction_model = new Journal_transaction();
                Model_base::map_objects($stock_transaction_model, $_POST);

                //update photo
                if(!$this->upload_image($stock_transaction_model))
                {
                    $message = $this->create_message('Cannot upload photo', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                $result = $this->Journal_transaction->update($stock_transaction_model);

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();

                    $message = $this->create_message('Cannot add', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }
                else
                {
                    $this->db->trans_commit();

                    $message = $this->create_message($result->message, $result->success?'':'Error');
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
        else
        {
            $stock_transaction = new Journal_transaction();
            $stock_transaction->stock_transaction_id= $stock_transaction_id;
            $result= $stock_transaction->get($stock_transaction);
            if($result->success){
                $stock_transaction = $result->model;
            }
            else {
                $this->show_404();  return;
            }

            if (isset($stock_transaction->image) && $stock_transaction->image != '')
            {
                $stock_transaction->image_path = $this->get_stock_transaction_site().$stock_transaction->image;
            }
            else
            {
                $stock_transaction->image_path = $this->get_logo_image();
            }

            $data['title'] = "Edit Stock_Transaction";
            $data['url'] = base_url()."stock_transaction/edit";
            $data['stock_transaction'] = $stock_transaction;

            $this->load->view('stock_transaction/new_stock_transaction', $data);
        }


    }

    function add()
    {
        //if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }


        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('stock_transaction_name', 'Stock_Transaction Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('stock_transaction_type_id', 'Stock_Transaction Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('stock_transaction_group_id', 'Stock_Transaction Group', 'required|greater_than[0]');
            $this->form_validation->set_rules('stock_transaction_class_id', 'Stock_Transaction Class', 'required|greater_than[0]');
            //$this->form_validation->set_rules('purchasing_price', 'Purchasing Price', 'required');
            $this->form_validation->set_rules('selling_price', 'Selling Price', 'required');
            $this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            $this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $stock_transaction_model = new Journal_transaction();
                Model_base::map_objects($stock_transaction_model, $_POST);

                //update photo
                if(!$this->upload_image($stock_transaction_model))
                {
                    $message = $this->create_message('Cannot upload image', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                if($stock_transaction_model->stock_transaction_id==0) $result = $this->Journal_transaction->add($stock_transaction_model);
                else $result = $this->Journal_transaction->update($stock_transaction_model);


                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();

                    $message = $this->create_message('Cannot add', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }
                else
                {
                    $this->db->trans_commit();

                    $message = $this->create_message($result->message, $result->success?'':'Error');
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
        else
        {
            $stock_transaction = new Journal_transaction();
            $stock_transaction->image_path = $this->get_logo_image();

            //get default stock_transaction type
            $stock_transaction_type = new Stock_Transaction_type_model();
            $stock_transaction_type->stock_transaction_type_id= 1;
            $result = $stock_transaction_type->get($stock_transaction_type);
            if($result->success){
                $stock_transaction->stock_transaction_type_id = $stock_transaction_type->stock_transaction_type_id;
                $stock_transaction->stock_transaction_type_name = $result->model->stock_transaction_type_name;
            }

            //get default stock_transaction type
            $stock_transaction_group = new Stock_Transaction_group_model();
            $stock_transaction_group->stock_transaction_group_id= 1;
            $result = $stock_transaction_group->get($stock_transaction_group);
            if($result->success){
                $stock_transaction->stock_transaction_group_id = $stock_transaction_group->stock_transaction_group_id;
                $stock_transaction->stock_transaction_group_name = $result->model->stock_transaction_group_name;
            }


            //get default stock_transaction type
            $stock_transaction_class = new Stock_Transaction_class_model();
            $stock_transaction_class->stock_transaction_class_id= 1;
            $result = $stock_transaction_class->get($stock_transaction_class);
            if($result->success){
                $stock_transaction->stock_transaction_class_id = $stock_transaction_class->stock_transaction_class_id;
                $stock_transaction->stock_transaction_class_name = $result->model->stock_transaction_class_name;
            }

            //get default stock_transaction type
            $maker = new Maker_model();
            $maker->maker_id= 1;
            $result = $maker->get($maker);
            if($result->success){
                $stock_transaction->maker_id = $maker->maker_id;
                $stock_transaction->maker_name = $result->model->maker_name;
            }

            //get default stock_transaction type
            $unit = new Unit_model();
            $unit->unit_id= 1;
            $result = $unit->get($unit);
            if($result->success){
                $stock_transaction->unit_id = $unit->unit_id;
                $stock_transaction->unit_name = $result->model->unit_name;
            }

            //get default stock_transaction type
            $lot = new Warehouse_model();
            $lot->warehouse_id= 1;
            $result = $lot->get($lot);
            if($result->success){
                $stock_transaction->default_lot_id = $lot->warehouse_id;
                $stock_transaction->default_lot_name = $result->model->warehouse_name;
            }

            $data['stock_transaction'] = $stock_transaction;
            $data['url'] = base_url()."stock_transaction/add";

            $this->load->view('stock_transaction/new_stock_transaction', $data);
        }
    }


    function delete()
    {
        if(!isset($_POST['submit'])) { $this->show_404(); return; }

        $this->form_validation->set_rules('stock_transaction_id', 'Stock_Transaction ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $stock_transaction_model = new Journal_transaction();
            $stock_transaction_model->stock_transaction_id = $this->input->post('stock_transaction_id');

            $result = $this->Journal_transaction->delete($stock_transaction_model);

            $message = $this->create_message($result->message, $result->success?'':'Error');
            $result->message = json_encode($message);
            echo json_encode($result);
            return false;

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


    function get_combobox_stock_transactions($search='')
    {
        $search = $search!=''? $search : strip_tags(trim($_GET['q']));

        $model = new Journal_transaction();
        $model->stock_transaction_name = $search;

        $result = $this->Journal_transaction->get_combobox_stock_transactions($model);
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