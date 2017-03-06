<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'item';

        $this->load->model('Item_model');
        $this->load->model('Item_type_model');
        $this->load->model('Item_group_model');
        $this->load->model('Item_class_model');
        $this->load->model('Maker_model');
        $this->load->model('Warehouse_model');
        $this->load->model('Unit_model');

    }


    function index()
    {
        $this->manage_item();
    }

    function manage_item()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['items'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'item_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

        $item = new Item_model();
        if(isset($_POST['submit']))
        {
            Model_base::map_objects($item, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $item->search_by = $search_by;
        $item->search = $search;
        $item->display = $display;
        $item->page = $page;
        $item->seach_option = $search_option;

        $result = $item->gets($item);
        if($result->success)$data['items'] = $result->models;

        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['search_option'] = $search_option;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $item_type = new Item_type_model();
        $result = $this->Item_type_model->gets($item_type);
        if($result->success) $data['item_types'] = $result->models;

        $item_group = new Item_group_model();
        $result = $this->Item_group_model->gets($item_group);
        if($result->success) $data['item_groups'] = $result->models;

        $item_class = new Item_class_model();
        $result = $this->Item_class_model->gets($item_class);
        if($result->success) $data['item_classes'] = $result->models;

        $maker = new Maker_model();
        $result = $this->Maker_model->gets($maker);
        if($result->success) $data['makers'] = $result->models;

        $this->load->view('item/manage_item', $data);

    }

    private function get_item_path()
    {
        return $this->get_file_path()."items/";
    }

    private function get_item_site()
    {
        return $this->get_file_site()."items/";
    }

    private function upload_image(Item_model &$item, $delete_if_exist = true)
    {
        if(isset($_FILES['file']) && $_FILES['file']['name'] != '')
        {
            $this->Item_model->generate_code($item);
            $file_name = $_FILES['file']['name'];
            $file_name = $item->item_code.".".pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = $this->get_item_path();

            //delete old file
            if($delete_if_exist && !$this->delete_file($file_path.$file_name)) return false;

            $upload = $this->upload_file($file_path , $file_name);
            if(!$upload) return false;

            $item->image = $file_name;
        }

        return true;
    }


    function edit($item_id = 0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data=array();

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('item_id', 'Item ID', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('item_type_id', 'Item Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('item_group_id', 'Item Group', 'required|greater_than[0]');
            $this->form_validation->set_rules('item_class_id', 'Item Class', 'required|greater_than[0]');
            //$this->form_validation->set_rules('purchasing_price', 'Purchasing Price', 'required');
            $this->form_validation->set_rules('selling_price', 'Selling Price', 'required');
            $this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            $this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');


            if ($this->form_validation->run())
            {
                $item_model = new Item_model();
                Model_base::map_objects($item_model, $_POST);

                //update photo
                if(!$this->upload_image($item_model))
                {
                    $message = $this->create_message('Cannot upload photo', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                $result = $this->Item_model->update($item_model);

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
            $item = new Item_model();
            $item->item_id= $item_id;
            $result= $item->get($item);
            if($result->success){
                $item = $result->model;
            }
            else {
                $this->show_404();  return;
            }

            if (isset($item->image) && $item->image != '')
            {
                $item->image_path = $this->get_item_site().$item->image;
            }
            else
            {
                $item->image_path = $this->get_logo_image();
            }

            $data['title'] = "Edit Item";
            $data['url'] = base_url()."item/edit";
            $data['item'] = $item;

            $this->load->view('item/new_item', $data);
        }


    }

    function add()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }


        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('item_name', 'Item Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('item_type_id', 'Item Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('item_group_id', 'Item Group', 'required|greater_than[0]');
            $this->form_validation->set_rules('item_class_id', 'Item Class', 'required|greater_than[0]');
            //$this->form_validation->set_rules('purchasing_price', 'Purchasing Price', 'required');
            $this->form_validation->set_rules('selling_price', 'Selling Price', 'required');
            $this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            $this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $item_model = new Item_model();
                Model_base::map_objects($item_model, $_POST);

                //update photo
                if(!$this->upload_image($item_model))
                {
                    $message = $this->create_message('Cannot upload image', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                if($item_model->item_id==0) $result = $this->Item_model->add($item_model);
                else $result = $this->Item_model->update($item_model);


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
            $item = new Item_model();
            $item->image_path = $this->get_logo_image();

            //get default item type
            $item_type = new Item_type_model();
            $item_type->item_type_id= 1;
            $result = $item_type->get($item_type);
            if($result->success){
                $item->item_type_id = $item_type->item_type_id;
                $item->item_type_name = $result->model->item_type_name;
            }

            //get default item type
            $item_group = new Item_group_model();
            $item_group->item_group_id= 1;
            $result = $item_group->get($item_group);
            if($result->success){
                $item->item_group_id = $item_group->item_group_id;
                $item->item_group_name = $result->model->item_group_name;
            }


            //get default item type
            $item_class = new Item_class_model();
            $item_class->item_class_id= 1;
            $result = $item_class->get($item_class);
            if($result->success){
                $item->item_class_id = $item_class->item_class_id;
                $item->item_class_name = $result->model->item_class_name;
            }

            //get default item type
            $maker = new Maker_model();
            $maker->maker_id= 1;
            $result = $maker->get($maker);
            if($result->success){
                $item->maker_id = $maker->maker_id;
                $item->maker_name = $result->model->maker_name;
            }

            //get default item type
            $unit = new Unit_model();
            $unit->unit_id= 1;
            $result = $unit->get($unit);
            if($result->success){
                $item->unit_id = $unit->unit_id;
                $item->unit_name = $result->model->unit_name;
            }

            //get default item type
            $lot = new Warehouse_model();
            $lot->warehouse_id= 1;
            $result = $lot->get($lot);
            if($result->success){
                $item->default_lot_id = $lot->warehouse_id;
                $item->default_lot_name = $result->model->warehouse_name;
            }

            $data['item'] = $item;
            $data['url'] = base_url()."item/add";

            $this->load->view('item/new_item', $data);
        }
    }


    function delete()
    {
        if(!isset($_POST['submit'])) { $this->show_404(); return; }

        $this->form_validation->set_rules('item_id', 'Item ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $item_model = new Item_model();
            $item_model->item_id = $this->input->post('item_id');

            $result = $this->Item_model->delete($item_model);

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


    function get_combobox_items($search='')
    {
        $search = $search!=''? $search : strip_tags(trim($_GET['q']));

        $model = new Item_model();
        $model->item_name = $search;

        $result = $this->Item_model->get_combobox_items($model);
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