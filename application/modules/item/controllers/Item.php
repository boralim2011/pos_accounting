<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Item extends MY_Controller {

    function __construct()
    {
        parent::__construct(false);

        $this->Menu = 'item';

        $this->load->model('Item_model');
        $this->load->model('Item_type_model');
        $this->load->model('Item_group_model');
        $this->load->model('Item_class_model');
        $this->load->model('Maker_model');
        $this->load->model('Warehouse_model');
        $this->load->model('Unit_model');
        $this->load->model('Item_member_model');

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
        if(isset($_POST['ajax']) || isset($_POST['submit']))
        {
            Model_base::map_objects($item, (array) $_POST);
            $data = array_merge($data,$_POST);
            //echo json_encode($result);
            //var_dump($data);
            //var_dump($item);
        }

        //var_dump($data);
        //var_dump($_POST);

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

    function search()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['items'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'item_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 5;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

        $item = new Item_model();
        if(isset($_POST['ajax']) || isset($_POST['submit']))
        {
            Model_base::map_objects($item, (array) $_POST);
            $data = array_merge($data,$_POST);
            //echo json_encode($result);
            //var_dump($data);
            //var_dump($item);
        }

        //var_dump($data);
        //var_dump($_POST);

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

        $item_group = new Item_group_model();
        $result = $this->Item_group_model->gets($item_group);
        if($result->success) $data['item_groups'] = $result->models;

        $data['blank_item'] = $this->get_blank_item();

        $view = isset($_POST['submit'])? "item/search_item_result":"item/search_item";

        $this->load->view($view, $data);

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

    function  choose()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $item_members= isset($_POST['item_members']) && is_array($_POST['item_members'])?$_POST['item_members']:array();

        $delete_item_members= isset($_POST['delete_item_members']) && is_array($_POST['delete_item_members'])?$_POST['delete_item_members']:"";

        foreach($item_members as $key=>$item)
        {
            $item_member = new Item_member_model();
            Model_base::map_objects($item_member, $item);
            $item_member->member_name = $item['member_name'];
            $item_member->member_code = $item['member_code'];

            $item_members[$key] = $item_member;
        }

        $model = new Item_model();
        Model_base::map_objects($model, $_POST['item_model']);

        $no = count($item_members) + 1;

        $item_member = new Item_member_model();
        $item_member->item_member_id = 0;
        $item_member->member_id= $model->item_id;
        $item_member->member_name = $model->item_name;
        $item_member->member_code = $model->item_code;
        $item_member->qty = 1;
        $item_member->no = $no;

        $item_members[$no] = $item_member;

        $data['item_members'] = $item_members;
        $data['delete_item_members'] = $delete_item_members;

        $this->load->view('item/item_members', $data);
    }


    function  remove()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $delete_row = isset($_POST['row'])? $_POST['row']:0;
        $items= isset($_POST['item_members']) && is_array($_POST['item_members'])?$_POST['item_members']:array();
        $delete_item_members= isset($_POST['delete_item_members']) && is_array($_POST['delete_item_members'])?$_POST['delete_item_members']:"";


        //remove from array
        if(array_key_exists($delete_row, $items))
        {
            if($items[$delete_row]['item_member_id']>0)
            {
                if($delete_item_members=='') $delete_item_members=array();
                $delete_item_members['delete'] = $items[$delete_row];
            }

            unset($items[$delete_row]);
        }

        //revise array to object
        if($delete_item_members!='' && is_array($delete_item_members))
        {
            $temps = $delete_item_members;
            $delete_item_members = array();
            $no = 1;
            foreach($temps as $key=>$item)
            {
                $item_member = new Item_member_model();
                Model_base::map_objects($item_member, $item);

                $delete_item_members[$no] = $item_member;
                $no++;
            }
        }


        //re generate line number
        $item_members = array();
        $no = 1;
        foreach($items as $key=>$item)
        {
            $item_member = new Item_member_model();
            Model_base::map_objects($item_member, $item);
            $item_member->member_name = $item['member_name'];
            $item_member->member_code = $item['member_code'];

            $item_members[$no] = $item_member;
            $no++;
        }

        $data['item_members'] = $item_members;
        $data['delete_item_members'] = $delete_item_members;

        $this->load->view('item/item_members', $data);
    }


    function edit_product($item_id = 0)
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

            $data['title'] = "Edit Product";
            $data['url'] = base_url()."item/edit_product";
            $data['item'] = $item;

            $this->load->view('item/new_product', $data);
        }


    }

    function add_product()
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
            $data['url'] = base_url()."item/add_product";

            $this->load->view('item/new_product', $data);
        }
    }


    function edit_service($item_id = 0)
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
            //$this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            //$this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');


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

            $data['title'] = "Edit Service";
            $data['url'] = base_url()."item/edit_service";
            $data['item'] = $item;

            $this->load->view('item/new_service', $data);
        }


    }

    function add_service()
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
            //$this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            //$this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');

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

            //get default item type
            $item_type = new Item_type_model();
            $item_type->item_type_id= 2;
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

//            //get default item type
//            $maker = new Maker_model();
//            $maker->maker_id= 1;
//            $result = $maker->get($maker);
//            if($result->success){
//                $item->maker_id = $maker->maker_id;
//                $item->maker_name = $result->model->maker_name;
//            }

            //get default item type
            $unit = new Unit_model();
            $unit->unit_id= 1;
            $result = $unit->get($unit);
            if($result->success){
                $item->unit_id = $unit->unit_id;
                $item->unit_name = $result->model->unit_name;
            }

//            //get default item type
//            $lot = new Warehouse_model();
//            $lot->warehouse_id= 1;
//            $result = $lot->get($lot);
//            if($result->success){
//                $item->default_lot_id = $lot->warehouse_id;
//                $item->default_lot_name = $result->model->warehouse_name;
//            }

            $data['item'] = $item;
            $data['url'] = base_url()."item/add_service";

            $this->load->view('item/new_service', $data);
        }
    }

    function edit_mixed($item_id = 0)
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
            //$this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            //$this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');


            $item_members= isset($_POST['item_members']) ? json_decode($_POST['item_members']):array();
            $delete_item_members= isset($_POST['delete_item_members']) ? json_decode($_POST['delete_item_members']):array();

            if(!isset($item_members) || count($item_members)==0){
                $message = $this->create_message('Item members is required!', 'Error');
                $result = new Message_result();
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }

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

                $result = $this->Item_model->update($item_model);

                if($result->success)
                {
                    //add members
                    foreach($item_members as $key=>$row)
                    {
                        $item_member = new Item_member_model();
                        Model_base::map_objects($item_member, $row);
                        $item_member->item_id = (int) $result->model->item_id;

                        if($item_member->item_member_id==0) $save=$item_member->add($item_member);
                        else $save=$item_member->update($item_member);

                        if($save->success)
                        {
                            //echo print_r($item_member);
                            $item_member->member_code = $row->member_code;
                            $item_member->member_name = $row->member_name;
                            $item_members->$key = $item_member;
                        }
                    }

                    $result->models = $item_members;

                    //delete members
                    if(isset($delete_item_members) && count($delete_item_members)>0)
                    {
                        foreach($delete_item_members as $key=>$row)
                        {
                            $item_member = new Item_member_model();
                            Model_base::map_objects($item_member, $row);

                            $delete=$item_member->delete($item_member);
                            if($delete->success){

                            }
                        }
                    }
                }

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


            $item_member = new Item_member_model();
            $item_member->item_id= $item_id;
            $result= $item_member->gets($item_member);
            if($result->success)
            {
                //re generate line number
                $item_members = array();
                $no = 1;
                foreach($result->models as $key=>$item_member)
                {
                    $item_members[$no] = $item_member;
                    $no++;
                }

                $data['item_members'] = $item_members;
            }

            $data['title'] = "Edit Mixed";
            $data['url'] = base_url()."item/edit_mixed";
            $data['item'] = $item;

            $this->load->view('item/new_mixed', $data);
        }


    }

    function add_mixed()
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
            //$this->form_validation->set_rules('kg', 'Weight', 'required');
            $this->form_validation->set_rules('unit_id', 'Unit', 'required|greater_than[0]');
            //$this->form_validation->set_rules('maker_id', 'Maker', 'required|greater_than[0]');


            $item_members= isset($_POST['item_members']) ? json_decode($_POST['item_members']):array();
            $delete_item_members= isset($_POST['delete_item_members']) ? json_decode($_POST['delete_item_members']):array();

            if(!isset($item_members) || count($item_members)==0){
                $message = $this->create_message('Item members is required!', 'Error');
                $result = new Message_result();
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }

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

                if($result->success)
                {
                    //add members
                    foreach($item_members as $key=>$row)
                    {
                        $item_member = new Item_member_model();
                        Model_base::map_objects($item_member, $row);
                        $item_member->item_id = (int) $result->model->item_id;

                        if($item_member->item_member_id==0) $save=$item_member->add($item_member);
                        else $save=$item_member->update($item_member);

                        if($save->success)
                        {
                            //echo print_r($item_member);
                            $item_member->member_code = $row->member_code;
                            $item_member->member_name = $row->member_name;
                            $item_members->$key = $item_member;
                        }

                    }

                    $result->models = $item_members;

                    //delete members
                    if(isset($delete_item_members) && count($delete_item_members)>0)
                    {
                        foreach($delete_item_members as $key=>$row)
                        {
                            $item_member = new Item_member_model();
                            Model_base::map_objects($item_member, $row);

                            $delete=$item_member->delete($item_member);
                            if($delete->success){

                            }
                        }
                    }
                }

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

                    $message = $this->create_message($result->message , $result->success?'':'Error');
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

            //get default item type
            $item_type = new Item_type_model();
            $item_type->item_type_id= 3;
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

//            //get default item type
//            $maker = new Maker_model();
//            $maker->maker_id= 1;
//            $result = $maker->get($maker);
//            if($result->success){
//                $item->maker_id = $maker->maker_id;
//                $item->maker_name = $result->model->maker_name;
//            }

            //get default item type
            $unit = new Unit_model();
            $unit->unit_id= 1;
            $result = $unit->get($unit);
            if($result->success){
                $item->unit_id = $unit->unit_id;
                $item->unit_name = $result->model->unit_name;
            }

//            //get default item type
//            $lot = new Warehouse_model();
//            $lot->warehouse_id= 1;
//            $result = $lot->get($lot);
//            if($result->success){
//                $item->default_lot_id = $lot->warehouse_id;
//                $item->default_lot_name = $result->model->warehouse_name;
//            }

            $data['item'] = $item;
            $data['url'] = base_url()."item/add_mixed";

            $this->load->view('item/new_mixed', $data);
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