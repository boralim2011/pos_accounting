<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contact_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        if(!isset($this->created_date)) $this->created_date = Date('Y-m-d H:i:s', time());
        if(!isset($this->modified_date)) $this->modified_date = Date('Y-m-d H:i:s', time());
    }

    public $contact_id = 0;
    public $contact_code;
    public $contact_name;
    public $contact_name_kh;
    public $phone_number;
    public $phone_number_2;
    public $email;
    public $website;
    public $photo;
    public $contact_type;
    public $first_name;
    public $first_name_kh;
    public $last_name;
    public $last_name_kh;
    public $nick_name;
    public $gender;
    public $date_of_birth;
    public $nationality_id;
    public $father_name;
    public $father_name_kh;
    public $mother_name;
    public $mother_name_kh;
    public $father_job;
    public $mother_job;
    public $father_age = 0;
    public $mother_age = 0;
    public $family_phone;
    public $family_phone_2;
    public $spouse_name;
    public $spouse_name_kh;
    public $spouse_job;
    public $spouse_age=0;
    public $weight=0;
    public $height=0;
    public $number_of_brother=0;
    public $number_of_sister=0;
    public $sibling_order=0;
    public $number_of_children=0;
    public $oldest_age=0;
    public $youngest_age=0;
    public $marital_status;
    public $is_deletable = 1;
    public $created_date;
    public $recruitment_fee_rate = 0;
    public $is_fixed_rate=0;
    public $agency_type_id;
    public $modified_by;
    public $modified_date;
    public $created_by;

    public $worker_code;
    public $register_date;
    public $recruiter_id;
    public $company_id;
    public $agency_id;
    public $service_type_id;
    public $worker_type_id;
    public $id_card_no;
    public $id_card_issue_date;
    public $id_card_expired_date;
    public $to_country_id;

    public $passport_no;
    public $passport_issue_date;
    public $passport_expired_date;
    public $date_of_receive_passport;
    public $date_of_send_ppc_sd;
    public $date_of_mofa; //Ministry of Foreign Affair

    public $date_of_send_document;
    public $document_type_id;
    public $date_of_send_bio_scan;
    public $date_of_send_medical_checkup_sd;

    public $passport_photo_date;
    public $work_permit_date;
    public $name_list_date;
    public $date_of_employer;
    public $employer_name;
    public $employer_address;
    public $employer_nirc;
    public $employer_phone;
    public $employer_phone_2;

    public $date_of_visa_rd_confirm;
    public $date_of_visa_rd_receive;
    public $date_of_buy_air_ticket;
    public $date_of_fly;
    public $note;
    public $border_crossing_id = 1;

    public $is_cancel=0;
    public $canceled_date;
    public $cancel_type_id;
    public $canceled_reason;
    public $register_list_index=1;


    function gets(Contact_model $contact)
    {
        $sql = "SELECT c.* , agt.agency_type_name 'agency_type' ".
            "FROM contact c ".
            "Left JOIN agency_type agt on c.agency_type_id=agt.agency_type_id ".
            "WHERE '$contact->contact_type' in ('', contact_type) ".
            "AND ('$contact->contact_name'='' || contact_name LIKE '%$contact->contact_name%' ESCAPE '!') ".
            "AND ('$contact->contact_code'='' || contact_code LIKE '%$contact->contact_code%' ESCAPE '!') ".
            "AND ('$contact->email'='' || email LIKE '%$contact->email%' ESCAPE '!') ".
            "AND ('$contact->phone_number'='' || phone_number LIKE '%$contact->phone_number%' ESCAPE '!') ".
            "AND ('$contact->phone_number_2'='' || phone_number_2 LIKE '%$contact->phone_number_2%' ESCAPE '!') ".
            "AND '$contact->agency_type_id' in ('',c.agency_type_id) "
        ;

        $query = $this->db->query($sql);

        //echo $this->db->last_query();

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Contact_model'));
        }

    }

    function get(Contact_model $contact)
    {
        $this->db->where('contact_id', $contact->contact_id);
        $result =$this->db->get('contact');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Contact_model'));
        }
    }

    function get_by_name(Contact_model $contact)
    {
        $this->db->where('contact_name', $contact->contact_name);
        $result =$this->db->get('contact');

        if(!$result || $result->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $result->first_row('Contact_model'));
        }
    }

    function is_exist(Contact_model $contact)
    {
        $this->db->where('contact_id', $contact->contact_id);

        $result =$this->db->get('contact');

        return $result && $result->num_rows()> 0;
    }

    function is_exist_code(Contact_model $contact)
    {
        $this->db->where('contact_code', $contact->contact_code);
        $this->db->where('contact_id !=', $contact->contact_id);

        $result =$this->db->get('contact');

        return $result && $result->num_rows()> 0;
    }

    function generate_code(Contact_model &$model)
    {
        if(isset($model->contact_code) && $model->contact_code!='') return $model->contact_code;

        //$prefix = Date('ymd-');
        $prefix = strtoupper(substr($model->contact_type, 0, 3));
        $prefix = $prefix==""?"": $prefix."-";
        $digits = "00001";
        $code = $prefix.$digits;

        $sql = "select contact_code from contact where contact_code LIKE '$prefix%' order by contact_code desc limit 1";

        $result = $this->db->query($sql);

        if($result && $result->num_rows()>0)
        {

            $number = (int) substr($result->first_row()->contact_code, strlen($prefix));
            $number ++;

            $code = $prefix.str_pad($number, strlen($digits) , "0", 0);
        }

        $model->contact_code = $code;

        return $model->contact_code;
    }

    function add(Contact_model &$contact)
    {
        $this->generate_code($contact);

        if($this->is_exist_code($contact))
        {
            return Message_result::error_message('Contact code is exist');
        }

        //for mysqli driver
        unset($contact->contact_id);
        if(!isset($contact->created_date) || $contact->created_date=='') $contact->created_date = Date('Y-m-d H:i:s', time());
        if(!isset($contact->contact_id) || $contact->contact_id=='') unset($contact->contact_id);

        $contact->created_by = $this->UserSession->user_id;
        $contact->modified_by = $this->UserSession->user_id;

        try{
            $result=$this->db->insert('contact', $contact);
        }
        catch (Exception $ex){
            return Message_result::error_message('Cannot add');
        }

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $contact->contact_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $contact);
        }

    }

    function update(Contact_model $contact)
    {
        $this->generate_code($contact);

        if($this->is_exist_code($contact))
        {
            return Message_result::error_message('Contact name is exist');
        }

        if(!isset($contact->created_date) || $contact->created_date=='') $contact->created_date = Date('Y-m-d H:i:s', time());
        if(!isset($contact->contact_id) || $contact->contact_id=='') unset($contact->contact_id);
        unset($contact->created_by);
        $contact->modified_by = $this->UserSession->user_id;

        $this->db->where('contact_id', $contact->contact_id);

        $result = $this->db->update('contact', $contact);

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $contact);
        }
    }

    function delete(Contact_model $contact)
    {
        $result = $this->get($contact);

        if(!$result->success || $result->model->is_deletable==0) return Message_result::error_message('Contact cannot be delete');

        $this->db->where('contact_id', $contact->contact_id);

        $result=$this->db->delete('contact');

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$contact);
        }
    }

    function get_combobox_items(Contact_model $model)
    {
        $sql = "select contact_id as 'id', contact_name as 'text' from contact where contact_name like'%$model->contact_name%' and '$model->contact_type' in ('', contact_type)";
        $query = $this->db->query($sql);

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result());
        }
    }


}