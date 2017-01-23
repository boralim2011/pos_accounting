<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Currency extends My_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'currency';

        $this->load->model('Currency_model');
    }

    function index()
    {
        $this->manage_currency();
    }

    function manage_currency()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'currency_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $currency = new Currency_model();
        $currency->search_by = $search_by;
        $currency->search = $search;
        $currency->display = $display;
        $currency->page = $page;
        $result = $this->Currency_model->gets($currency);

        $data['currencys'] = array();
        if($result->success)
        {
            $data['currencys'] = $result->models;
        }

        $data['currency']=$currency;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('currency/manage_currency', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['currency_name'] = $this->input->post('currency_name');
        //$data['currency_id'] = $this->input->post('currency_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('currency_name', 'Currency Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('currency_id', 'Currency ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $currency_model = new Currency_model();
            Model_base::map_objects($currency_model, $_POST);

            $result = $this->Currency_model->update($currency_model);

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

        //$data['currency_name'] = $this->input->post('currency_name');
        //$data['currency_id'] = $this->input->post('currency_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('currency_name', 'Currency Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $currency_model = new Currency_model();
            Model_base::map_objects($currency_model, $_POST);

            if($currency_model->currency_id==0) $result = $this->Currency_model->add($currency_model);
            else $result = $this->Currency_model->update($currency_model);

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

        $data['currency_id'] = $this->input->post('currency_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('currency_id', 'Currency ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $currency_model = new Currency_model();
            Model_base::map_objects($currency_model, $data);

            $result = $this->Currency_model->delete($currency_model);

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

        $model = new Currency_model();
        $model->currency_name = $search;

        $result = $this->Currency_model->get_combobox_items($model);
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