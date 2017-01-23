<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Company extends My_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'company';

        $this->load->model('Contact_model');
        $this->load->model('Contact_address_model');
        $this->load->model('Location_model');
    }

    public $contact_type = 'Company';

    function index()
    {
        $this->manage_company();
    }

    function manage_company()
    {
        //if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['companies'] = array();

        $company = new Contact_model();
        if(isset($_POST['submit']))
        {
            Model_base::map_objects($company, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $company->contact_type = $this->contact_type;
        $result = $company->gets($company);
        if($result->success)$data['companies'] = $result->models;

        //var_dump($result); return;

        $this->load->view('company/manage_company', $data);

    }

    private function upload_image(Contact_model &$company, $delete_if_exist = true)
    {
        if(isset($_FILES['file']) && $_FILES['file']['name'] != '')
        {
            $this->Contact_model->generate_code($company);
            $file_name = $_FILES['file']['name'];
            $file_name = $company->contact_code.".".pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = $this->get_photo_path();

            //delete old file
            if($delete_if_exist && !$this->delete_file($file_path.$file_name)) return false;

            $upload = $this->upload_file($file_path , $file_name);
            if(!$upload) return false;

            $company->photo = $file_name;
        }

        return true;
    }

    function get_address(Contact_model $model)
    {
        if($model->contact_id!=0)
        {
            $address = new Contact_address_model();
            $address->contact_id = $model->contact_id;
            $address->address_key = 'contact';

            $result = $this->Contact_address_model->gets($address);

            if($result->success){
                return $result->models[0];
            }

        }

        $address = new Contact_address_model();

        $country = $this->Location_model->get_default_country();
        if($country){
            $address->country_id = $country->location_id;
            $address->country = $country->location_name;
        }

        return $address;
    }

    function edit($company_id = 0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data=array();

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('contact_id', 'Company ID', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('contact_name', 'Company Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('contact_code', 'Company Code', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[9]|max_length[100]');
            $this->form_validation->set_rules('country_id', 'Country', 'required|greater_than[0]');
            $this->form_validation->set_rules('province_city_id', 'Province/City', 'required|greater_than[0]');
            //$this->form_validation->set_rules('district_khan_id', 'District/Khan', 'required|greater_than[0]');
            //$this->form_validation->set_rules('commune_sangkat_id', 'Commune/Sangkat', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $company_model = new Contact_model();
                Model_base::map_objects($company_model, $_POST);

                //update photo
                if(!$this->upload_image($company_model))
                {
                    $message = $this->create_message('Cannot upload photo', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                $result = $this->Contact_model->update($company_model);

                if($result->success)
                {
                    $save = $this->save_address($company_model);
                    if($save->success)
                    {
                        $result->model->address_id = $save->model->address_id;
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
            $json = $this->get_json_object();
            if($json===true)
            {
                $model = new Contact_model();
                $model->contact_id = $company_id;
                $result = $this->Contact_model->get($model);
                if($result->success)
                {
                    $company = $result->model;
                }
                else
                {
                    $this->show_404(); return;
                }
            }
            else
            {
                $company = new Contact_model();
                Model_base::map_objects($company, $json, true);
            }

            if (isset($company->photo) && $company->photo != '')
            {
                $company->photo_path = $this->get_photo_site().$company->photo;
            }
            else
            {
                $company->photo_path = $this->get_logo_image();
            }

            $address = $this->get_address($company);

            $data['title'] = "Edit Company";
            $data['url'] = base_url()."company/edit";
            $data['company'] = $company;
            $data['address'] = $address;

            $this->load->view('company/new_company', $data);
        }


    }

    function add()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }


        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('contact_name', 'Company Name', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('contact_code', 'Company Code', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[9]|max_length[100]');
            $this->form_validation->set_rules('country_id', 'Country', 'required|greater_than[0]');
            $this->form_validation->set_rules('province_city_id', 'Province/City', 'required|greater_than[0]');
            //$this->form_validation->set_rules('district_khan_id', 'District/Khan', 'required|greater_than[0]');
            //$this->form_validation->set_rules('commune_sangkat_id', 'Commune/Sangkat', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $company_model = new Contact_model();
                Model_base::map_objects($company_model, $_POST);

                //update photo
                if(!$this->upload_image($company_model))
                {
                    $message = $this->create_message('Cannot upload photo', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                if($company_model->contact_id==0) $result = $this->Contact_model->add($company_model);
                else $result = $this->Contact_model->update($company_model);


                if($result->success)
                {
                    $save = $this->save_address($company_model);
                    if($save->success)
                    {
                        $result->model->address_id = $save->model->address_id;
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
            $company = new Contact_model();
            $company->photo_path = $this->get_logo_image();
            $company->contact_type = $this->contact_type;

            $address = $this->get_address($company);

            $data['company'] = $company;
            $data['url'] = base_url()."company/add";
            $data['address'] = $address;

            $this->load->view('company/new_company', $data);
        }
    }

    function save_address(Contact_model $model=null)
    {
        //$_POST['country_id'] = 2;
        //$_POST['province_city_id'] = 3;
        //$_POST['address_id'] = 1;
        //$_POST['contact_id'] = 5;

        $address = new Contact_address_model();
        Model_base::map_objects($address, $_POST);
        $address->contact_id = $model->contact_id;

        if($address->address_id==0)
        {
             return $this->Contact_address_model->add($address);
        }
        else
        {
             return $this->Contact_address_model->update($address);
        }

        //echo $this->db->last_query();
    }


    function delete()
    {
        if(!isset($_POST['submit'])) { $this->show_404(); return; }

        $this->form_validation->set_rules('contact_id', 'Company ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $company_model = new Contact_model();
            $company_model->contact_id = $this->input->post('contact_id');

            $result = $this->Contact_model->delete($company_model);

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
        $search = isset($_GET['q'])? strip_tags(trim($_GET['q'])): $search ;

        $model = new Contact_model();
        $model->contact_name = $search;
        $model->contact_type = $this->contact_type;

        $result = $this->Contact_model->get_combobox_items($model);
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