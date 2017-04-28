<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contact_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $contact_id = 0;
    public $contact_code;
    public $contact_name;
    public $contact_name_kh;
    public $phone_number;
    public $contact_address;
    public $contact_address_kh;
    public $email;
    public $website;
    public $photo;
    public $contact_type_id;
    public $company_id;
    public $nationality_id=0;
    public $first_name;
    public $first_name_kh;
    public $last_name;
    public $last_name_kh;
    public $nick_name;
    public $gender;
    public $date_of_birth;
    public $marital_status;
    public $country_id;
    public $province_city_id;
    public $district_khan_id;
    public $commune_sangkat_id;
    public $village_id;
    public $region_id;


    function get_list(Contact_model $contact)
    {
        $type = isset($contact->contact_type_id)? $contact->contact_type_id: 3;
        $this->db->where('contact_type_id', $type);
        $query =$this->db->get("contact");

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Contact_model'));
        }

    }

    function gets(Contact_model $contact)
    {

        $display = isset($contact->display)? $contact->display:10;
        $page = isset($contact->page)?$contact->page:1;
        $offset = ($page-1) * $display;
        $show_all = isset($contact->show_all) && ($contact->show_all==1 || $contact->show_all==true);

        $search = isset($contact->search)? $contact->search: "";
        $search_by = isset($contact->search_by)? $contact->search_by: "contact_name";

        $contact_type_id = isset($contact->contact_type_id)? $contact->contact_type_id : 0;

        //$all_date = isset($contact->all_date) && $contact->all_date==1? 1 : 0;
        //$date_of = isset($contact->date_of)? $contact->date_of : "";
        //$from_date = isset($contact->from_date)? $contact->from_date : Date('Y-m-d');
        //$to_date = isset($contact->to_date)? $contact->to_date : Date('Y-m-d');

        $search_option = isset($contact->search_option)? $contact->search_option : 'like';


        $sql = "SELECT c.* , ".
                "(SELECT count(*)".
                "FROM contact c ".
                "LEFT JOIN contact_type cont on c.contact_type_id=cont.contact_type_id ".
                "WHERE $contact_type_id in (0, c.contact_type_id) ".
                "AND ('$search'='' || ".
                    "('$search_option'='exact' && c.$search_by='$search') || ".
                    "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
                    "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
                //($date_of==""?"": "AND (($all_date=1 && c.$date_of is not null) || c.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59')").
            ") 'records' ".
            "FROM contact c ".
            "LEFT JOIN contact_type cont on c.contact_type_id=cont.contact_type_id ".
            "WHERE $contact_type_id in (0, c.contact_type_id) ".
            "AND ('$search'='' || ".
                "('$search_option'='exact' && c.$search_by='$search') || ".
                "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
                "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
            //($date_of==""?"": "AND (($all_date=1 && c.$date_of is not null) || c.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ").
            ($show_all? "" : "LIMIT $offset, $display")
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
        $this->db->select("c.*,cont.contact_type_name")
            ->from('contact c')
            ->join("contact_type cont", "c.contact_type_id=cont.contact_type_id", "left")
            ->where('c.contact_id', $contact->contact_id);

        $result =$this->db->get();

        //echo $this->db->last_query();

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

        //$prefix = strtoupper(substr($model->contact_type, 0, 3));
        //$prefix = $prefix==""?"": $prefix."-";
        $prefix = $model->contact_type_id.Date('-ym-');
        $digits = "0001";
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
            return Message_result::error_message($contact->contact_code.' Code is exist');
        }

        //for mysqli driver
        unset($contact->contact_id);
        $contact->created_date = Date('Y-m-d H:i:s', time());
        $contact->modified_date = Date('Y-m-d H:i:s', time());
        $contact->created_by = $this->UserSession->user_id;
        $contact->modified_by = $this->UserSession->user_id;

        $this->check_data($contact);

        //var_dump($contact); echo "<br><br>";
        //echo $this->db->insert_string('contact', $contact); exit;

        $result=$this->db->insert('contact', $contact);


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

    function update(Contact_model &$contact)
    {
        $this->generate_code($contact);

        if($this->is_exist_code($contact))
        {
            return Message_result::error_message($contact->contact_code.' Code is exist');
        }

        $contact->modified_by = $this->UserSession->user_id;
        $contact->modified_date = Date('Y-m-d H:i:s', time());

        $this->check_data($contact);

        //var_dump($contact); echo "<br><br>";
        //echo $this->db->update_string('contact', $contact, "contact_id=$contact->contact_id"); exit;

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