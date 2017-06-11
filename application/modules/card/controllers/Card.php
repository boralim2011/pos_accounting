<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Card extends MY_Controller {

    function __construct()
    {
        parent::__construct(false);

        $this->Menu = 'card';

        $this->load->model('Card_model');
        $this->load->model('Card_type_model');
        $this->load->model('Card_history_model');
        $this->load->model('Age_range_model');

    }


    function index()
    {
        $this->manage_card();
    }

    function manage_card()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['cards'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'card_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

        $card = new Card_model();
        if(isset($_POST['submit']) || isset($_POST['ajax']))
        {
            Model_base::map_objects($card, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $card->search_by = $search_by;
        $card->search = $search;
        $card->display = $display;
        $card->page = $page;
        $card->seach_option = $search_option;

        $result = $card->gets($card);
        if($result->success)$data['cards'] = $result->models;

        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['search_option'] = $search_option;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $card_type = new Card_type_model();
        $result = $this->Card_type_model->gets($card_type);
        if($result->success) $data['card_types'] = $result->models;

        $this->load->view('card/manage_card', $data);

    }

    function get_by_number()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        if(!isset($_POST['card_number']))
        {
            $message = $this->create_message('Card number is required!', 'Error');
            $result = new Message_result();
            $result->message = json_encode($message);
            echo json_encode($result);
            return false;
        }

        $card = new Card_model();
        $card->card_number = $_POST['card_number'];
        $result= $card->get_by_number($card);

        echo json_encode($result);
        return $result->success;
    }

    function edit($card_id = 0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data=array();

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('card_id', 'Card ID', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('card_name', 'Card Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('card_type_id', 'Card Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('expired_date', 'Expired date', 'required');
            $this->form_validation->set_rules('register_date', 'Register date', 'required');

            if ($this->form_validation->run())
            {
                $card_model = new Card_model();
                Model_base::map_objects($card_model, $_POST);

                $card_model->is_active = isset($_POST['is_active'])? 1:0;
                $card_model->modified_date = Date('Y-m-d H:i:s', time());
                $card_model->modified_by = $this->UserSession->user_id;

                //begin transaction
                $this->db->trans_begin();

                $result = $this->Card_model->update($card_model);

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
            $card = new Card_model();
            $card->card_id= $card_id;
            $result= $card->get($card);
            if($result->success){
                $card = $result->model;
            }
            else {
                $this->show_404();  return;
            }

            $data['title'] = "Edit Card";
            $data['url'] = base_url()."card/edit";
            $data['card'] = $card;

            $this->load->view('card/new_card', $data);
        }


    }

    function add()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }


        if($this->input->post('submit'))
        {
            //$this->form_validation->set_rules('card_id', 'Card ID', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('card_name', 'Card Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('card_type_id', 'Card Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('expired_date', 'Expired date', 'required');
            $this->form_validation->set_rules('register_date', 'Register date', 'required');

            if ($this->form_validation->run())
            {
                $card_model = new Card_model();
                Model_base::map_objects($card_model, $_POST);

                $card_model->is_active = isset($_POST['is_active'])? 1:0;
                $card_model->created_date = Date('Y-m-d H:i:s', time());
                $card_model->created_by = $this->UserSession->user_id;
                $card_model->modified_date = Date('Y-m-d H:i:s', time());
                $card_model->modified_by = $this->UserSession->user_id;

                //begin transaction
                $this->db->trans_begin();

                if($card_model->card_id==0) $result = $this->Card_model->add($card_model);
                else $result = $this->Card_model->update($card_model);


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

            $card = new Card_model();
            $card->register_date = Date("Y-m-d");
            $year = (int)Date("Y") + 1;
            $card->expired_date = Date("$year-m-d", time());
            $card->gender = 'O';

            //get default card type
            $card_type = new Card_type_model();
            $card_type->card_type_id= 1;
            $result = $card_type->get($card_type);
            if($result->success){
                $card->card_type_id = $card_type->card_type_id;
                $card->card_type_name = $result->model->card_type_name;
            }

            //get default card type
            $age_range = new Age_range_model();
            $age_range->age_range_id= 1;
            $result = $age_range->get($age_range);
            if($result->success){
                $card->age_range_id = $age_range->age_range_id;
                $card->age_range_name = $result->model->age_range_name;
            }

            $data['card'] = $card;
            $data['url'] = base_url()."card/add";

            $this->load->view('card/new_card', $data);
        }
    }


    function delete()
    {
        if(!isset($_POST['submit'])) { $this->show_404(); return; }

        $this->form_validation->set_rules('card_id', 'Card ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $card_model = new Card_model();
            $card_model->card_id = $this->input->post('card_id');

            $result = $this->Card_model->delete($card_model);

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

        $model = new Card_model();
        $model->card_number = $search;

        $result = $this->Card_model->get_combobox_cards($model);
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

    function deposit()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('history_date', 'Date', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $card_model = new Card_model();
                Model_base::map_objects($card_model, $_POST);

                $result = $card_model->get_by_number($card_model);

                if(!$result->success)
                {
                    $result->message = "Invalid card number";
                    $message = $this->create_message($result->message, $result->success?'':'Error');
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }
                else{
                    $card_model = $result->model;
                }


                $card_history = new Card_history_model();
                Model_base::map_objects($card_history, $_POST);

                $card_history->card_id = $card_model->card_id;
                $card_history->amount_in_company_currency = $card_history->amount;

                //Need to do later
                $card_history->currency_id = 1;
                $card_history->exchange_rate = 1;
                $card_history->is_inverse = 0;

                $card_history->created_date = Date('Y-m-d H:i:s', time());
                $card_history->created_by = $this->UserSession->user_id;
                $card_history->modified_date = Date('Y-m-d H:i:s', time());
                $card_history->modified_by = $this->UserSession->user_id;

                //begin transaction
                $this->db->trans_begin();

                if($card_history->history_id==0) $result = $this->Card_history_model->add($card_history);
                else $result = $this->Card_history_model->update($card_history);


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
            $card_history = new Card_history_model();
            $card_history->history_date = Date("Y-m-d");
            $card_history->amount = 0.00;
            $card_history->is_deposit = 1;

            $data['card_history'] = $card_history;
            $data['url'] = base_url()."card/deposit";

            $data['title'] = 'New Deposit';

            $this->load->view('card/deposit_withdraw', $data);
        }
    }

    function withdraw()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('card_number', 'Card Number', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('history_date', 'Date', 'required');
            $this->form_validation->set_rules('amount', 'Amount', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $card_model = new Card_model();
                Model_base::map_objects($card_model, $_POST);

                $result = $card_model->get_by_number($card_model);

                if(!$result->success)
                {
                    $result->message = "Invalid card number";
                    $message = $this->create_message($result->message, $result->success?'':'Error');
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                $card_model = $result->model;

                $card_history = new Card_history_model();
                Model_base::map_objects($card_history, $_POST);

                if($card_history->amount > $card_model->get_total_balance())
                {
                    $message = $this->create_message('Amount cannot greater than balance', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }


                $card_history->card_id = $card_model->card_id;
                $card_history->amount_in_company_currency = $card_history->amount;

                //Need to do later
                $card_history->currency_id = 1;
                $card_history->exchange_rate = 1;
                $card_history->is_inverse = 0;

                $card_history->created_date = Date('Y-m-d H:i:s', time());
                $card_history->created_by = $this->UserSession->user_id;
                $card_history->modified_date = Date('Y-m-d H:i:s', time());
                $card_history->modified_by = $this->UserSession->user_id;

                //begin transaction
                $this->db->trans_begin();

                if($card_history->history_id==0) $result = $this->Card_history_model->add($card_history);
                else $result = $this->Card_history_model->update($card_history);


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
            $card_history = new Card_history_model();
            $card_history->history_date = Date("Y-m-d");
            $card_history->amount = 0.00;
            $card_history->is_deposit = 0;

            $data['card_history'] = $card_history;
            $data['url'] = base_url()."card/withdraw";

            $data['title'] = 'New Withdraw';

            $this->load->view('card/deposit_withdraw', $data);
        }
    }

    function history($card_id = 0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['cards'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'card_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

        $all_date = isset($_POST['all_date']) ? $_POST['all_date'] : 1;
        $date_of = isset($_POST['date_of'])? $_POST['date_of'] : "history_date";
        $from_date = isset($_POST['from_date'])? $_POST['from_date'] : Date('Y-m-d');
        $to_date = isset($_POST['to_date'])? $_POST['to_date'] : Date('Y-m-d');

        $card_history = new Card_history_model();
        if(isset($_POST['submit']) || isset($_POST['ajax']))
        {
            Model_base::map_objects($card_history, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $card_history->search_by = $search_by;
        $card_history->search = $search;
        $card_history->display = $display;
        $card_history->page = $page;
        $card_history->seach_option = $search_option;

        $card_history->card_id = $card_id;

        $card_history->all_date = $all_date;
        $card_history->date_of= $date_of;
        $card_history->from_date = $from_date;
        $card_history->to_date = $to_date;

        $result = $card_history->gets($card_history);
        if($result->success)$data['histories'] = $result->models;

        $data['card_history'] = $card_history;

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


        $card = new Card_model();
        $card->card_id= $card_id;
        $result= $card->get($card);
        if($result->success){
            $card = $result->model;
        }
        $data['card']= $card;


        $this->load->view('card/card_history', $data);

    }


    function delete_history()
    {
        if(!isset($_POST['submit'])) { $this->show_404(); return; }

        $this->form_validation->set_rules('history_id', 'History ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $card_history_model = new Card_history_model();
            $card_history_model->history_id = $this->input->post('history_id');

            $result = $this->Card_history_model->delete($card_history_model);

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

}