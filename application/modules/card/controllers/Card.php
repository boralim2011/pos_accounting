<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Card extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'card';

        $this->load->model('Card_model');
        $this->load->model('Card_type_model');
        $this->load->model('Card_history_model');

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


    function history()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['cards'] = array();

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'card_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;
        $search_option = isset($_POST['search_option'])? $_POST['search_option'] : 'like';

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

        $result = $card_history->gets($card_history);
        if($result->success)$data['histories'] = $result->models;

        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['search_option'] = $search_option;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('card/card_history', $data);

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
        //if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }


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
            $card->expired_date = Date("Y-m-d", time()+3600);

            //get default card type
            $card_type = new Card_type_model();
            $card_type->card_type_id= 1;
            $result = $card_type->get($card_type);
            if($result->success){
                $card->card_type_id = $card_type->card_type_id;
                $card->card_type_name = $result->model->card_type_name;
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


    function get_combobox_cards($search='')
    {
        $search = $search!=''? $search : strip_tags(trim($_GET['q']));

        $model = new Card_model();
        $model->card_name = $search;

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

}