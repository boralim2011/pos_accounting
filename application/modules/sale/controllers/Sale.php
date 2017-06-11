<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sale extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'sale';

        $this->load->model('Sale_model');
        $this->load->model('Sale_item_model');
        $this->load->model('Room_model');
        $this->load->model('Age_range_model');
        $this->load->model('Card_model');
        $this->load->model('Exchange_rate_model');
    }


    function index()
    {
        $this->manage_sale();
    }

    function manage_sale()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['sales'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'journal_no';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

        $all_date = isset($_POST['all_date']) ? $_POST['all_date'] : 1;
        $date_of = isset($_POST['date_of'])? $_POST['date_of'] : "journal_date";
        $from_date = isset($_POST['from_date'])? $_POST['from_date'] : Date('Y-m-d');
        $to_date = isset($_POST['to_date'])? $_POST['to_date'] : Date('Y-m-d');

        $sale = new Sale_model();
        if(isset($_POST['submit']) || isset($_POST['ajax']))
        {
            Model_base::map_objects($sale, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $sale->search_by = $search_by;
        $sale->search = $search;
        $sale->display = $display;
        $sale->page = $page;
        $sale->seach_option = $search_option;

        $sale->all_date = $all_date;
        $sale->date_of= $date_of;
        $sale->from_date = $from_date;
        $sale->to_date = $to_date;

        $result = $sale->gets($sale);
        if($result->success)$data['sales'] = $result->models;

        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['search_option'] = $search_option;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $data['all_date'] = $all_date;
        $data['date_of'] = $date_of;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;


        $room = new Room_model();
        $result = $this->Room_model->gets($room);
        if($result->success) $data['rooms'] = $result->models;


        $this->load->view('sale/manage_sale', $data);

    }

    function print_invoice()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $this->form_validation->set_rules('journal_id', 'Sale ID', 'trim|required|greater_than[0]');

        if ($this->form_validation->run()) {
            $sale_items = array();
            $sale_model = new Sale_model();
            //$sale_model->journal_id = $journal_id;
            Model_base::map_objects($sale_model, $_POST);

            $result = $this->Sale_model->get($sale_model);
            if($result->success)
            {
                $sale_model = $result->model;

                $sale_item = new Sale_item_model();
                $sale_item->journal_id = $sale_model->journal_id;

                $result = $sale_item->gets($sale_item);

                if($result->success) $sale_items = $result->models;
            }

            $data['sale'] = $sale_model;
            $data['sale_items'] = $sale_items;

            $this->load->view('sale/print_invoice', $data);
        }
        else
        {
            echo validation_errors();
            $message = $this->create_message(validation_errors(), 'Error');
            $result = new Message_result();
            $result->message = json_encode($message);
            echo json_encode($result);
            return false;
            $this->show_404();
        }

    }

    function edit($journal_id = 0)
    {
        //if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('journal_id', 'Sale ID', 'trim|required|greater_than[0]');
            //$this->form_validation->set_rules('sale_name', 'Sale Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('journal_date', 'Sale Date', 'required');
            $this->form_validation->set_rules('age_range_id', 'Age_range', 'required|greater_than[0]');
            $this->form_validation->set_rules('sale_items', 'Sale Items', 'required');

            $sale_items= isset($_POST['sale_items']) ? json_decode($_POST['sale_items']):array();
            $delete_sale_items= isset($_POST['delete_sale_items']) ? json_decode($_POST['delete_sale_items']):array();

            if(!isset($sale_items) || count($sale_items)==0){
                $message = $this->create_message('Sale items is required!', 'Error');
                $result = new Message_result();
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }

            if ($this->form_validation->run())
            {
                $sale_model = new Sale_model();
                Model_base::map_objects($sale_model, $_POST);

                //Card
                $car_number = isset($_POST['card_number'])? $_POST['card_number']:"";
                if($car_number!="")
                {
                    $card = new Card_model();
                    $card -> card_number = $car_number;
                    $result = $card->get_by_number($card);
                    if($result->success)
                    {
                        $sale_model->card_id = $result->model->card_id;
                    }
                    else{
                        $message = $this->create_message('Invalid card!', 'Error');
                        $result = new Message_result();
                        $result->message = json_encode($message);
                        echo json_encode($result);
                        return false;
                    }
                }


                //Exchange Rate
                $exchange_rate = json_decode($_POST['exchange_rate_model']);
                $sale_model->exchange_rate = $exchange_rate->bit_rate;

                //Total Amount
                $total = 0;
                foreach($sale_items as $key=>$row)
                {
                    $sale_item = new Sale_item_model();
                    Model_base::map_objects($sale_item, $row);
                    $total += $sale_item->get_amount();
                }
                $sale_model->total = $total;
                $sale_model->total_company_currency = $total;

                $sale_model->modified_date = Date('Y-m-d H:i:s', time());
                $sale_model->modified_by = $this->UserSession->user_id;

                //begin transaction
                $this->db->trans_begin();

                $result = $this->Sale_model->update($sale_model);

                if($result->success)
                {
                    //add members
                    foreach($sale_items as $key=>$row)
                    {
                        $sale_item = new Sale_item_model();
                        Model_base::map_objects($sale_item, $row);
                        $sale_item->journal_id = $result->model->journal_id;

                        if($sale_item->journal_item_id==0) $save=$sale_item->add($sale_item);
                        else $save=$sale_item->update($sale_item);

                        if($save->success)
                        {
                            //echo print_r($sale_item);
                            $sale_item->item_name = $row->item_name;
                            $sale_item->unit_name = $row->unit_name;
                            $sale_items->$key = $sale_item;
                        }
                    }

                    $result->models = $sale_items;

                    //delete members
                    if(isset($delete_sale_items) && count($delete_sale_items)>0)
                    {
                        foreach($delete_sale_items as $key=>$row)
                        {
                            $sale_item = new Sale_item_model();
                            Model_base::map_objects($sale_item, $row);

                            $delete=$sale_item->delete($sale_item);
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

            $sale = new Sale_model();
            $sale->journal_id = $journal_id;

            $result= $sale->get($sale);
            if($result->success){
                $sale = $result->model;
            }
            else {
                $this->show_404();  return;
            }

            $sale_item = new Sale_item_model();
            $sale_item->journal_id = $journal_id;
            $result= $sale_item->gets($sale_item);
            if($result->success)
            {

                $no=1;
                $sale_items = array();
                foreach($result->models as $key=>$item)
                {
                    $sale_item = new Sale_item_model();
                    Model_base::map_objects($sale_item, $item);
                    $sale_item->item_name = $item->item_name;
                    $sale_item->unit_name = $item->unit_name;

                    $sale_items[$no] = $sale_item;
                    $no++;
                }

                $data['sale_items'] = $sale_items;
                //$data['sale_items'] = $result->models;
            }


            $exchange_rate = new Exchange_rate_model();
            $exchange_rate->exchange_rate_id = 1;
            $result = $exchange_rate->get();
            if($result->success) $data['exchange_rate'] = $result->model;

            $total_amount = $sale->total;
            $discount = $sale->discount;
            $total_usd = $total_amount * (1 - $discount/100);
            $total_khr = $total_usd * $exchange_rate->ask_rate;

            $data['discount'] = $discount;
            $data['total_amount'] = $total_amount;
            $data['total_usd'] = $total_usd;
            $data['total_khr'] = $total_khr;


            $data['sale'] = $sale;
            $data['url'] = base_url()."sale/edit";

            $data['display'] = 10;
            $data['page'] = 1;
            $data['search'] = "";
            $data['search_by'] = "item_name";
            $data['search_option'] = "like";
            $data['pages'] = 0;
            $data['records'] = 0;

            $this->load->view('sale/new_sale', $data);
        }


    }

    function add($room_id=0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        if($this->input->post('submit'))
        {
            //$this->form_validation->set_rules('journal_id', 'Sale ID', 'trim|required|greater_than[0]');
            //$this->form_validation->set_rules('sale_name', 'Sale Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('journal_date', 'Sale Date', 'required');
            $this->form_validation->set_rules('age_range_id', 'Age_range', 'required|greater_than[0]');
            $this->form_validation->set_rules('sale_items', 'Sale Items', 'required');

            $sale_items= isset($_POST['sale_items']) ? json_decode($_POST['sale_items']):array();
            $delete_sale_items= isset($_POST['delete_sale_items']) ? json_decode($_POST['delete_sale_items']):array();

            if(!isset($sale_items) || count($sale_items)==0){
                $message = $this->create_message('Sale items is required!', 'Error');
                $result = new Message_result();
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }

            if ($this->form_validation->run())
            {
                $sale_model = new Sale_model();
                Model_base::map_objects($sale_model, $_POST);

                //Card
                $car_number = isset($_POST['card_number'])? $_POST['card_number']:"";
                if($car_number!="")
                {
                    $card = new Card_model();
                    $card -> card_number = $car_number;
                    $result = $card->get_by_number($card);
                    if($result->success)
                    {
                        $sale_model->card_id = $result->model->card_id;
                        //$sale_model->discount = $result->model->discount_rate;
                    }
                    else{
                        $message = $this->create_message('Invalid card!', 'Error');
                        $result = new Message_result();
                        $result->message = json_encode($message);
                        echo json_encode($result);
                        return false;
                    }
                }


                //Exchange Rate
                $exchange_rate = json_decode($_POST['exchange_rate_model']);
                $sale_model->exchange_rate = $exchange_rate->bit_rate;

                //Total Amount
                $total = 0;
                foreach($sale_items as $key=>$row)
                {
                    $sale_item = new Sale_item_model();
                    Model_base::map_objects($sale_item, $row);
                    $total += $sale_item->get_amount();
                }
                $sale_model->total = $total;
                $sale_model->total_company_currency = $total;


                $sale_model->created_date = Date('Y-m-d H:i:s', time());
                $sale_model->created_by = $this->UserSession->user_id;
                $sale_model->modified_date = Date('Y-m-d H:i:s', time());
                $sale_model->modified_by = $this->UserSession->user_id;

                //begin transaction
                $this->db->trans_begin();

                if($sale_model->journal_id==0) $result = $this->Sale_model->add($sale_model);
                else $result = $this->Sale_model->update($sale_model);

                if($result->success)
                {
                    //add members
                    foreach($sale_items as $key=>$row)
                    {
                        $sale_item = new Sale_item_model();
                        Model_base::map_objects($sale_item, $row);
                        $sale_item->journal_id = $result->model->journal_id;

                        if($sale_item->journal_item_id==0) $save=$sale_item->add($sale_item);
                        else $save=$sale_item->update($sale_item);

                        if($save->success)
                        {
                            //echo print_r($sale_item);
                            $sale_item->item_name = $row->item_name;
                            $sale_item->unit_name = $row->unit_name;
                            $sale_items->$key = $sale_item;
                        }
                    }

                    $result->models = $sale_items;

                    //delete members
                    if(isset($delete_sale_items) && count($delete_sale_items)>0)
                    {
                        foreach($delete_sale_items as $key=>$row)
                        {
                            $sale_item = new Sale_item_model();
                            Model_base::map_objects($sale_item, $row);

                            $delete=$sale_item->delete($sale_item);
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

            $sale = new Sale_model();
            $sale->journal_date = Date("Y-m-d");
            $sale->journal_status = 1;
            $sale->currency_id = 1;
            $sale->gender = "O";

            //get default room
            $room = new Room_model();
            $room->room_id= $room_id>0? $room_id: 1;
            $result = $room->get($room);
            if($result->success){
                $sale->room_id = $room->room_id;
                $sale->room_name = $result->model->room_name;
            }

            $age_range = new Age_range_model();
            $age_range->age_range_id = 1;
            $result = $this->Age_range_model->get($age_range);
            if($result->success) {
                $sale->age_range_id = $result->model->age_range_id;
                $sale->age_range_name = $result->model->age_range_name;
            }

            $exchange_rate = new Exchange_rate_model();
            $exchange_rate->exchange_rate_id = 1;
            $result = $exchange_rate->get();
            if($result->success) $data['exchange_rate'] = $result->model;


            $data['sale'] = $sale;
            $data['url'] = base_url()."sale/add";

            $data['display'] = 10;
            $data['page'] = 1;
            $data['search'] = "";
            $data['search_by'] = "item_name";
            $data['search_option'] = "like";
            $data['pages'] = 0;
            $data['records'] = 0;

            $this->load->view('sale/new_sale', $data);
        }
    }


    function delete()
    {
        if(!isset($_POST['submit'])) { $this->show_404(); return; }

        $this->form_validation->set_rules('journal_id', 'Sale ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $sale_model = new Sale_model();
            $sale_model->journal_id = $this->input->post('journal_id');

            $result = $this->Sale_model->delete($sale_model);

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


    function get_combobox_sales($search='')
    {
        $search = $search!=''? $search : strip_tags(trim($_GET['q']));

        $model = new Sale_model();
        $model->journal_no = $search;

        $result = $this->Sale_model->get_combobox_sales($model);
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

    function verify_card()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $card = new Card_model();
        $card->card_number = $_POST['card_number'];
        $result = $card->get_by_number($card);

        $discount = 0;
        if($result->success)
        {
            $discount = $result->model->discount_rate;
        }

        $items= isset($_POST['sale_items']) && is_array($_POST['sale_items'])?$_POST['sale_items']:array();
        $delete_sale_items= isset($_POST['delete_sale_items']) && is_array($_POST['delete_sale_items'])?$_POST['delete_sale_items']:"";

        //revise sale items
        $no = 1;
        $total_amount = 0;
        $sale_items = array();
        foreach($items as $key=>$item)
        {
            $sale_item = new Sale_item_model();
            Model_base::map_objects($sale_item, $item);
            $sale_item->item_name = $item['item_name'];
            $sale_item->unit_name = $item['unit_name'];

            $total_amount += $sale_item->get_amount();
            $sale_items[$no] = $sale_item;
            $no++;
        }


        $rate= isset($_POST['exchange_rate']) ? $_POST['exchange_rate']:"";
        if($rate!=""){
            $exchange_rate = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate, $rate, true);
            $data['exchange_rate'] = $exchange_rate;
        }

        $total_usd = $total_amount * (1 - $discount/100);
        $total_khr = $total_usd * $exchange_rate->ask_rate;

        $data['discount'] = $discount;
        $data['total_amount'] = $total_amount;
        $data['total_usd'] = $total_usd;
        $data['total_khr'] = $total_khr;

        $data['sale_items'] = $sale_items;
        $data['delete_sale_items'] = $delete_sale_items;

        $this->load->view('sale/sale_items', $data);
    }

    function  choose_item()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $items= isset($_POST['sale_items']) && is_array($_POST['sale_items'])?$_POST['sale_items']:array();

        $delete_sale_items= isset($_POST['delete_sale_items']) && is_array($_POST['delete_sale_items'])?$_POST['delete_sale_items']:"";

        $sale_item = new Sale_item_model();
        Model_base::map_objects($sale_item, $_POST['item_model'], true);

        $sale_item->journal_item_id = 0;
        $sale_item->qty = 1;
        $sale_item->price = $sale_item->selling_price;

        $items[] = (array) $sale_item;

        //revise sale items
        $no = 1;
        $total_amount = 0;
        $sale_items = array();
        foreach($items as $key=>$item)
        {
            $sale_item = new Sale_item_model();
            Model_base::map_objects($sale_item, $item);
            $sale_item->item_name = $item['item_name'];
            $sale_item->unit_name = $item['unit_name'];

            $total_amount += $sale_item->get_amount();
            $sale_items[$no] = $sale_item;
            $no++;
        }


        $rate= isset($_POST['exchange_rate']) ? $_POST['exchange_rate']:"";
        if($rate!=""){
            $exchange_rate = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate, $rate, true);
            $data['exchange_rate'] = $exchange_rate;
        }

        $discount = isset($_POST['discount'])? $_POST['discount'] : 0;
        $total_usd = $total_amount * (1 - $discount/100);
        $total_khr = $total_usd * $exchange_rate->ask_rate;

        $data['discount'] = $discount;
        $data['total_amount'] = $total_amount;
        $data['total_usd'] = $total_usd;
        $data['total_khr'] = $total_khr;


        $data['sale_items'] = $sale_items;
        $data['delete_sale_items'] = $delete_sale_items;

        $this->load->view('sale/sale_items', $data);
    }


    function  remove_item()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $delete_row = isset($_POST['row'])? $_POST['row']:0;
        $items= isset($_POST['sale_items']) && is_array($_POST['sale_items'])?$_POST['sale_items']:array();
        $delete_sale_items= isset($_POST['delete_sale_items']) && is_array($_POST['delete_sale_items'])?$_POST['delete_sale_items']:"";


        //remove from array
        if(array_key_exists($delete_row, $items))
        {
            if($items[$delete_row]['journal_item_id']>0)
            {
                if($delete_sale_items=='') $delete_sale_items=array();
                $delete_sale_items['delete'] = $items[$delete_row];
            }

            unset($items[$delete_row]);
        }

        //revise array to object
        if($delete_sale_items!='' && is_array($delete_sale_items))
        {
            $temps = $delete_sale_items;
            $delete_sale_items = array();
            $no = 1;
            foreach($temps as $key=>$item)
            {
                $sale_item = new Sale_item_model();
                Model_base::map_objects($sale_item, $item);

                $delete_sale_items[$no] = $sale_item;
                $no++;
            }
        }


        //re generate line number
        $no = 1;
        $total_amount = 0;
        $sale_items = array();
        foreach($items as $key=>$item)
        {
            $sale_item = new Sale_item_model();
            Model_base::map_objects($sale_item, $item, true);

            $total_amount += $sale_item->get_amount();
            $sale_items[$no] = $sale_item;
            $no++;
        }

        $rate= isset($_POST['exchange_rate']) ?$_POST['exchange_rate']:"";
        if($rate!=""){
            $exchange_rate = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate, $rate, true);
            $data['exchange_rate'] = $exchange_rate;
        }

        $discount = isset($_POST['discount'])? $_POST['discount'] : 0;
        $total_usd = $total_amount * (1 - $discount/100);
        $total_khr = $total_usd * $exchange_rate->ask_rate;

        $data['discount'] = $discount;
        $data['total_amount'] = $total_amount;
        $data['total_usd'] = $total_usd;
        $data['total_khr'] = $total_khr;

        $data['sale_items'] = $sale_items;
        $data['delete_sale_items'] = $delete_sale_items;

        $this->load->view('sale/sale_items', $data);
    }

    function  change_qty()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $change_row = isset($_POST['row'])? $_POST['row']:0;
        $change_qty = isset($_POST['qty'])? $_POST['qty']:0;
        $items= isset($_POST['sale_items']) && is_array($_POST['sale_items'])?$_POST['sale_items']:array();
        $delete_sale_items= isset($_POST['delete_sale_items']) && is_array($_POST['delete_sale_items'])?$_POST['delete_sale_items']:"";


        //revise array to object
        if($delete_sale_items!='' && is_array($delete_sale_items))
        {
            $temps = $delete_sale_items;
            $delete_sale_items = array();
            $no = 1;
            foreach($temps as $key=>$item)
            {
                $sale_item = new Sale_item_model();
                Model_base::map_objects($sale_item, $item);

                $delete_sale_items[$no] = $sale_item;
                $no++;
            }
        }


        //re generate line number
        $no = 1;
        $total_amount = 0;
        $sale_items = array();
        foreach($items as $key=>$item)
        {
            $sale_item = new Sale_item_model();
            Model_base::map_objects($sale_item, $item, true);

            if($key == $change_row)
            {
                $sale_item->qty += $change_qty;
                if($sale_item->qty<=0) $sale_item->qty = 1;
            }

            $total_amount += $sale_item->get_amount();
            $sale_items[$no] = $sale_item;
            $no++;
        }

        $rate= isset($_POST['exchange_rate']) ?$_POST['exchange_rate']:"";
        if($rate!=""){
            $exchange_rate = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate, $rate, true);
            $data['exchange_rate'] = $exchange_rate;
        }

        $discount = isset($_POST['discount'])? $_POST['discount'] : 0;
        $total_usd = $total_amount * (1 - $discount/100);
        $total_khr = $total_usd * $exchange_rate->ask_rate;

        $data['discount'] = $discount;
        $data['total_amount'] = $total_amount;
        $data['total_usd'] = $total_usd;
        $data['total_khr'] = $total_khr;

        $data['sale_items'] = $sale_items;
        $data['delete_sale_items'] = $delete_sale_items;

        $this->load->view('sale/sale_items', $data);
    }
}