<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Card_type extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'card_type';

        $this->load->model('Card_type_model');
    }

    function index()
    {
        $this->manage_card_type();
    }

    function manage_card_type()
    {
        if(!isset($_POST['ajax'])) {  $this->show_404();return; }

        $search_by = isset($_POST['search_by'])?$_POST['search_by']:'card_type_name';
        $search = isset($_POST['search'])?$_POST['search']:'';
        $page = isset($_POST['page']) ? $_POST['page']: 1;
        $display = isset($_POST['display']) ? $_POST['display']: 10;

        $card_type = new Card_type_model();
        $card_type->search_by = $search_by;
        $card_type->search = $search;
        $card_type->display = $display;
        $card_type->page = $page;
        $result = $this->Card_type_model->gets($card_type);

        $data['card_types'] = array();
        if($result->success)
        {
            $data['card_types'] = $result->models;
        }

        $data['card_type']=$card_type;

        //Pagination
        $data['display'] = $display;
        $data['page'] = $page;
        $data['search'] = $search;
        $data['search_by'] = $search_by;
        $data['pages'] = is_array($result->models)? ceil($result->models[0]->records / $display): 0;
        $data['records'] = is_array($result->models)? $result->models[0]->records:0;

        $this->load->view('card_type/manage_card_type', $data);

    }

    function edit()
    {

        if(!isset($_POST['submit'])) {  $this->show_404();return; }

        //$data['card_type_name'] = $this->input->post('card_type_name');
        //$data['card_type_id'] = $this->input->post('card_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('card_type_name', 'Card_type Name', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('card_type_id', 'Card_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $card_type_model = new Card_type_model();
            Model_base::map_objects($card_type_model, $_POST);

            $result = $this->Card_type_model->update($card_type_model);

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

        //$data['card_type_name'] = $this->input->post('card_type_name');
        //$data['card_type_id'] = $this->input->post('card_type_id');
        //$data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('card_type_name', 'Card_type Name', 'trim|required|min_length[2]|max_length[100]');

        if ($this->form_validation->run())
        {
            $card_type_model = new Card_type_model();
            Model_base::map_objects($card_type_model, $_POST);

            if($card_type_model->card_type_id==0) $result = $this->Card_type_model->add($card_type_model);
            else $result = $this->Card_type_model->update($card_type_model);

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

        $data['card_type_id'] = $this->input->post('card_type_id');
        $data = $this->security->xss_clean($data);
        $this->form_validation->set_rules('card_type_id', 'Card_type ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $card_type_model = new Card_type_model();
            Model_base::map_objects($card_type_model, $data);

            $result = $this->Card_type_model->delete($card_type_model);

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

        $model = new Card_type_model();
        $model->card_type_name = $search;

        $result = $this->Card_type_model->get_combobox_items($model);
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