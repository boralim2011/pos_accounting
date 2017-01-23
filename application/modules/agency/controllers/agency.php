<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agency extends My_Controller {

    function __construct()
    {
        parent::__construct();

        $this->Menu = 'agency';

        $this->load->model('Contact_model');
        $this->load->model('Contact_address_model');
        $this->load->model('Location_model');
        $this->load->model('Agency_type_model');
    }

    public $contact_type = 'Agency';

    function index()
    {
        $this->manage_agency();
    }

    function manage_agency()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data['agencies'] = array();

        $agency = new Contact_model();
        if(isset($_POST['submit']))
        {
            Model_base::map_objects($agency, $_POST);
            $data = array_merge($data,$_POST);

            //echo json_encode($result);
            //var_dump($data);
        }

        $agency->contact_type = $this->contact_type;
        $result = $agency->gets($agency);
        if($result->success)$data['agencies'] = $result->models;

        $agency_type = new Agency_type_model();
        $result = $this->Agency_type_model->gets($agency_type);
        if($result->success) $data['agency_types'] = $result->models;

        $this->load->view('agency/manage_agency', $data);

    }

    private function upload_image(Contact_model &$agency, $delete_if_exist = true)
    {
        if(isset($_FILES['file']) && $_FILES['file']['name'] != '')
        {
            $this->Contact_model->generate_code($agency);
            $file_name = $_FILES['file']['name'];
            $file_name = $agency->contact_code.".".pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = $this->get_photo_path();

            //delete old file
            if($delete_if_exist && !$this->delete_file($file_path.$file_name)) return false;

            $upload = $this->upload_file($file_path , $file_name);
            if(!$upload) return false;

            $agency->photo = $file_name;
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

    function edit($agency_id = 0)
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }

        $data=array();

        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('contact_id', 'Agency ID', 'trim|required|greater_than[0]');
            $this->form_validation->set_rules('contact_name', 'Agency Name', 'trim|required|min_length[2]|max_length[100]');
            //$this->form_validation->set_rules('contact_code', 'Agency Code', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[9]|max_length[100]');
            $this->form_validation->set_rules('agency_type_id', 'Agency Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('country_id', 'Country', 'required|greater_than[0]');
            $this->form_validation->set_rules('province_city_id', 'Province/City', 'required|greater_than[0]');
            //$this->form_validation->set_rules('district_khan_id', 'District/Khan', 'required|greater_than[0]');
            //$this->form_validation->set_rules('commune_sangkat_id', 'Commune/Sangkat', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $agency_model = new Contact_model();
                Model_base::map_objects($agency_model, $_POST);

                //update photo
                if(!$this->upload_image($agency_model))
                {
                    $message = $this->create_message('Cannot upload photo', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                $result = $this->Contact_model->update($agency_model);

                if($result->success)
                {
                    $save = $this->save_address($agency_model);
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
                $model->contact_id = $agency_id;
                $result = $this->Contact_model->get($model);
                if($result->success)
                {
                    $agency = $result->model;
                }
                else
                {
                    $this->show_404(); return;
                }
            }
            else
            {
                $agency = new Contact_model();
                Model_base::map_objects($agency, $json, true);
            }

            if (isset($agency->photo) && $agency->photo != '')
            {
                $agency->photo_path = $this->get_photo_site().$agency->photo;
            }
            else
            {
                $agency->photo_path = $this->get_logo_image();
            }

            $address = $this->get_address($agency);

            $data['title'] = "Edit Agency";
            $data['url'] = base_url()."agency/edit";
            $data['agency'] = $agency;
            $data['address'] = $address;

            $this->load->view('agency/new_agency', $data);
        }


    }

    function add()
    {
        if(!isset($_POST['ajax']) && !isset($_POST['submit'])) {  $this->show_404();return; }


        if($this->input->post('submit'))
        {
            $this->form_validation->set_rules('contact_name', 'Agency Name', 'trim|required|min_length[2]|max_length[100]');
            //$this->form_validation->set_rules('contact_code', 'Agency Code', 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|required|min_length[9]|max_length[100]');
            $this->form_validation->set_rules('agency_type_id', 'Agency Type', 'required|greater_than[0]');
            $this->form_validation->set_rules('country_id', 'Country', 'required|greater_than[0]');
            $this->form_validation->set_rules('province_city_id', 'Province/City', 'required|greater_than[0]');
            //$this->form_validation->set_rules('district_khan_id', 'District/Khan', 'required|greater_than[0]');
            //$this->form_validation->set_rules('commune_sangkat_id', 'Commune/Sangkat', 'required|greater_than[0]');

            if ($this->form_validation->run())
            {
                $agency_model = new Contact_model();
                Model_base::map_objects($agency_model, $_POST);

                //update photo
                if(!$this->upload_image($agency_model))
                {
                    $message = $this->create_message('Cannot upload photo', 'Error');
                    $result = new Message_result();
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }

                //begin transaction
                $this->db->trans_begin();

                if($agency_model->contact_id==0) $result = $this->Contact_model->add($agency_model);
                else $result = $this->Contact_model->update($agency_model);


                if($result->success)
                {
                    $save = $this->save_address($agency_model);
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
            $agency = new Contact_model();
            $agency->photo_path = $this->get_logo_image();
            $agency->contact_type = $this->contact_type;

            $address = $this->get_address($agency);

            $data['agency'] = $agency;
            $data['url'] = base_url()."agency/add";
            $data['address'] = $address;

            $this->load->view('agency/new_agency', $data);
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

        $this->form_validation->set_rules('contact_id', 'Agency ID', 'required|greater_than[0]');

        if ($this->form_validation->run())
        {
            $agency_model = new Contact_model();
            $agency_model->contact_id = $this->input->post('contact_id');

            $result = $this->Contact_model->delete($agency_model);

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