<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sale_report extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'sale';

        $this->load->model('Sale_model');
        $this->load->model('Sale_item_model');
        $this->load->model('Room_model');
        $this->load->model('Age_range_model');
        $this->load->model('Card_model');
        $this->load->model('Card_type_model');
        $this->load->model('Exchange_rate_model');
        $this->load->model('User_model');
    }


    function index()
    {
        $this->manage_report();
    }

    function manage_report()
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
            Model_base::map_objects($sale, $_POST, true);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $sale->search_by = $search_by;
        $sale->search = $search;
        $sale->display = $display;
        $sale->page = $page;
        $sale->seach_option = $search_option;
        $sale->show_all =1;

        $sale->all_date = $all_date;
        $sale->date_of= $date_of;
        $sale->from_date = $from_date;
        $sale->to_date = $to_date;

        $result = $sale->report($sale);
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


        $card = new Card_model();
        $result = $this->Card_model->gets($card);
        if($result->success) $data['cards'] = $result->models;

        $card_type = new Card_type_model();
        $result = $this->Card_type_model->gets($card_type);
        if($result->success) $data['card_types'] = $result->models;

        $user = new User_model();
        $result = $this->User_model->gets($user);
        if($result->success) $data['created_bys'] = $result->models;

        if(isset($_POST['print']))
        {
            $this->load->view('sale_report/print_reports', $data);
        }
        else
        {
            $this->load->view('sale_report/manage_report', $data);
        }

    }
}