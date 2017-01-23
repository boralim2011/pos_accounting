<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contact_model extends Model_base
{

    function __construct()
    {
        parent::__construct();

        if(!isset($this->start_date)) $this->start_date = Date('Y-m-d', time());
        //if(!isset($this->modified_date)) $this->modified_date = Date('Y-m-d H:i:s', time());
    }

    public $company_id = 0;
    public $company_code;
    public $company_name;
    public $company_name_kh;
    public $short_name;
    public $phone_number;
    public $start_date;
    public $vat_no;
    public $contact_address;
    public $contact_address_kh;
    public $email;
    public $website;
    public $logo;
    public $currency_id;
    public $country_id;
    public $province_city_id;
    public $district_khan_id;
    public $commune_sangkat_id;
    public $village_id;
    public $region_id;


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