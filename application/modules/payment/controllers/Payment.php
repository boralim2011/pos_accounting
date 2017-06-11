<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Payment extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'payment';

        $this->load->model('Payment_model');
        $this->load->model('Sale_model');
        $this->load->model('Payment_method_model');
    }

    function index()
    {
        $this->manage_payment();
    }

    function manage_payment()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'payment_no';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $payment = new Payment_model();
        $payment->search_by = $search_by;
        $payment->search = $search;
        $payment->display = $display;
        $payment->page = $page;
        $result = $this->Payment_model->gets($payment);

        $data['payments'] = array();
        if($result->success)
        {
            $data['payments'] = $result->models;
        }

        $data['payment']=$payment;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('payment/manage_payment', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['payment_name'] = $this->input->post('payment_name');
        //$data['payment_id'] = $this->input->post('payment_id');
        //$data = $this->security->xss_clean($data);
        //$this->form_validation->set_rules('payment_no', 'Payment No', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('payment_id', 'Payment ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $payment_model = new Payment_model();
            Model_base::map_objects($payment_model, $_POST);

            $result = $this->Payment_model->update($payment_model);

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
        //if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        if(isset($_POST['submit']))
        {
            //$data['payment_name'] = $this->input->post('payment_name');
            //$data['payment_id'] = $this->input->post('payment_id');
            //$data = $this->security->xss_clean($data);
            //$this->form_validation->set_rules('journal', 'Sale', 'trim|required');

            if ($this->form_validation->run())
            {
                $payment_model = new Payment_model();
                Model_base::map_objects($payment_model, $_POST);

                if($payment_model->payment_id==0) $result = $this->Payment_model->add($payment_model);
                else $result = $this->Payment_model->update($payment_model);

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
        else
        {
            $data = array();

            $journal = new Sale_model();
            $journal->journal_id = $_POST['journal_id'];
            $result = $this->Sale_model->get($journal);
            if($result->success){
                $journal = $result->model;
                $data['journal'] = $journal;
            }

            $payment_method = new Payment_method_model();
            $result = $this->Payment_method_model->gets($payment_method);
            if($result->success){
                $data['payment_methods'] = $result->models;
            }

            $payment = new Payment_model();
            $payment ->journal_id = $journal->journal_id;
            if($journal->card_id>0) {
                $payment->payment_method_id = 2;
                $payment->card_number = $journal->card_number;
            }
            $payment->exchange_rate = $journal->exchange_rate;

            $data['payment'] = $payment;

            $this->load->view('payment/new_payment', $data);
        }

    }

    function delete()
    {
        if(!isset($_POST['submit'])) {
            $this->show_404();return;
        }

        $data['payment_id'] = $this->input->post('payment_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('payment_id', 'Payment ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $payment_model = new Payment_model();
            Model_base::map_objects($payment_model, $data);

            $result = $this->Payment_model->delete($payment_model);

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

        $model = new Payment_model();
        $model->payment_name = $search;

        $result = $this->Payment_model->get_combobox_items($model);
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