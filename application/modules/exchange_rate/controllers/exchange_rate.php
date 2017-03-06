<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Exchange_rate extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'exchange_rate';

        $this->load->model('Exchange_rate_model');
        $this->load->model('Currency_model');
    }

    function index()
    {
        $this->manage_exchange_rate();
    }

    function manage_exchange_rate()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'from_currency_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $exchange_rate = new Exchange_rate_model();
        $exchange_rate->search_by = $search_by;
        $exchange_rate->search = $search;
        $exchange_rate->display = $display;
        $exchange_rate->page = $page;
        $result = $this->Exchange_rate_model->gets($exchange_rate);

        $data['exchange_rates'] = array();
        if($result->success)
        {
            $data['exchange_rates'] = $result->models;
        }

        $data['exchange_rate']=$exchange_rate;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $from_currency = new Currency_model();
        $result = $this->Currency_model->gets($from_currency);
        if($result->success) $data['from_currencies'] = $result->models;

        $to_currency = new Currency_model();
        $result = $this->Currency_model->gets($to_currency);
        if($result->success) $data['to_currencies'] = $result->models;

        $this->load->view('exchange_rate/manage_exchange_rate', $data);
        $this->load->view('exchange_rate/new_exchange_rate', $data);
    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['exchange_rate_name'] = $this->input->post('exchange_rate_name');
        //$data['exchange_rate_id'] = $this->input->post('exchange_rate_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('from_currency_id', 'From Currency', 'required|greater_than[0]');
        $this->form_validation->set_rules('to_currency_id', 'To Currency', 'required|greater_than[0]');
        $this->form_validation->set_rules('bit_rate', 'Bit Rate', 'required|greater_than[0]');
        $this->form_validation->set_rules('ask_rate', 'Ask Rate', 'required|greater_than[0]');
        $this->form_validation->set_rules('exchange_rate_id', 'Exchange_rate ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $exchange_rate_model = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate_model, $_POST);
            $exchange_rate_model->is_inverse = isset($_POST['is_inverse'])?1:0;
            $exchange_rate_model->modified_date = Date('Y-m-d H:i:s', time());
            $exchange_rate_model->modified_by = $this->UserSession->user_id;

            $result = $this->Exchange_rate_model->update($exchange_rate_model);

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

        //$data['exchange_rate_name'] = $this->input->post('exchange_rate_name');
        //$data['exchange_rate_id'] = $this->input->post('exchange_rate_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('from_currency_id', 'From Currency', 'required|greater_than[0]');
        $this->form_validation->set_rules('to_currency_id', 'To Currency', 'required|greater_than[0]');
        $this->form_validation->set_rules('bit_rate', 'Bit Rate', 'required|greater_than[0]');
        $this->form_validation->set_rules('ask_rate', 'Ask Rate', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $exchange_rate_model = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate_model, $_POST);

            $exchange_rate_model->is_inverse = isset($_POST['is_inverse'])?1:0;
            $exchange_rate_model->created_date = Date('Y-m-d H:i:s', time());
            $exchange_rate_model->created_by = $this->UserSession->user_id;

            if($exchange_rate_model->exchange_rate_id==0) $result = $this->Exchange_rate_model->add($exchange_rate_model);
            else $result = $this->Exchange_rate_model->update($exchange_rate_model);

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

        $data['exchange_rate_id'] = $this->input->post('exchange_rate_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('exchange_rate_id', 'Exchange_rate ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $exchange_rate_model = new Exchange_rate_model();
            Model_base::map_objects($exchange_rate_model, $data);

            $result = $this->Exchange_rate_model->delete($exchange_rate_model);

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



}