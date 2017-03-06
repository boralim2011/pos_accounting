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
    public $nationality_id=0;
    public $father_name;
    public $father_name_kh;
    public $mother_name;
    public $mother_name_kh;
    public $father_job;
    public $mother_job;
    public $father_age = 0;
    public $mother_age = 0;
    public $family_phone;
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
    public $recruitment_fee_rate = 0;
    public $is_fixed_rate=0;
    public $agency_type_id=0;


    public $worker_code;
    public $register_date;
    public $recruiter_id=0;
    public $company_id=0;
    public $agency_id=0;
    public $service_type_id=0;
    public $register_type_id=0;
    public $id_card_no;
    public $id_card_issue_date;
    public $id_card_expired_date;
    public $to_country_id=0;

    public $passport_no;
    public $passport_issue_date;
    public $passport_expired_date;
    public $date_of_receive_passport;
    public $date_of_send_ppc_sd;
    public $date_of_mofa; //Ministry of Foreign Affair

    public $date_of_send_document;
    public $document_type_id=0;
    public $date_of_send_bio_scan;
    public $date_of_send_medical_checkup_sd;

    public $passport_photo_date;
    public $work_permit_date;
    public $name_list_date;
    public $date_of_employer;
    //public $employer_id=0;
    //public $employer_name;
    //public $employer_address;
    //public $employer_nirc;
    //public $employer_phone;

    public $date_of_visa_rd_confirm;
    public $date_of_visa_rd_receive;
    public $date_of_buy_air_ticket;
    public $date_of_travel;
    public $travel_method;
    public $travel_purpose;
    public $from_location;
    public $to_location;
    public $length_of_stay;
    public $visa_no;
    public $note;
    public $border_crossings_id = 1;

    public $is_cancel=0;
    public $canceled_date;
    public $cancel_type_id=0;
    public $canceled_reason;
    public $register_key="";
    public $ocwc_no;

    function get_list(Contact_model $contact)
    {
        $type = isset($contact->contact_type)? $contact->contact_type: "Contact";
        $this->db->where('contact_type', $type);
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

        $contact_type = isset($contact->contact_type)? $contact->contact_type : "";
        $to_country_id = isset($contact->to_country_id)? $contact->to_country_id:0;
        $company_id = isset($contact->company_id)? $contact->company_id:0;
        $agency_id = isset($contact->agency_id)? $contact->agency_id:0;
        //$employer_id = isset($contact->employer_id)? $contact->employer_id:0;
        $agency_type_id = isset($contact->agency_type_id)? $contact->agency_type_id:0;
        $register_type_id = isset($contact->register_type_id)? $contact->register_type_id:0;
        $document_type_id = isset($contact->document_type_id)? $contact->document_type_id:0;
        $recruiter_id = isset($contact->recruiter_id)? $contact->recruiter_id:0;
        $service_type_id = isset($contact->service_type_id)? $contact->service_type_id:0;
        $register_key = isset($contact->register_key) ? $contact->register_key: "";

        $all_date = isset($contact->all_date) && $contact->all_date==1? 1 : 0;
        $date_of = isset($contact->date_of)? $contact->date_of : "";
        $from_date = isset($contact->from_date)? $contact->from_date : Date('Y-m-d');
        $to_date = isset($contact->to_date)? $contact->to_date : Date('Y-m-d');

        $search_option = isset($contact->search_option)? $contact->search_option : 'like';


        $sql = "SELECT c.* , ".
                "(SELECT count(*)".
                "FROM contact c ".
                "LEFT JOIN agency_type agt on c.agency_type_id=agt.agency_type_id ".
                "LEFT JOIN contact rec on c.recruiter_id=rec.contact_id and rec.contact_type='Recruiter' ".
                "LEFT JOIN contact com on c.company_id=com.contact_id and com.contact_type='Company' ".
                "LEFT JOIN contact age on c.agency_id=age.contact_id and age.contact_type='Agency' ".
                //"LEFT JOIN contact emp on c.employer_id=emp.contact_id and emp.contact_type='Employer' ".
                "LEFT JOIN location tol on c.to_country_id=tol.location_id and tol.location_type_id=1 ".
                "LEFT JOIN register_type ret on c.register_type_id=ret.register_type_id ".
                "LEFT JOIN document_type dot on c.document_type_id=dot.document_type_id ".
                "LEFT JOIN service_type svt on c.service_type_id=svt.service_type_id ".
                "WHERE '$contact_type' in ('', c.contact_type) ".
                "AND $to_country_id in (0, c.to_country_id) ".
                "AND $company_id in (0, c.company_id) ".
                "AND $agency_id in (0, c.agency_id) ".
                //"AND $employer_id in (0, c.employer_id) ".
                "AND $agency_type_id in (0, c.agency_type_id) ".
                "AND $register_type_id in (0, c.register_type_id) ".
                "AND $document_type_id in (0, c.document_type_id) ".
                "AND $recruiter_id in (0, c.recruiter_id) ".
                "AND $service_type_id in (0, c.service_type_id) ".
                "AND '$register_key' in ('', c.register_key) ".
                "AND ('$search'='' || ".
                    "('$search_option'='exact' && c.$search_by='$search') || ".
                    "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
                    "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
                ($date_of==""?"": "AND (($all_date=1 && c.$date_of is not null) || c.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59')").
            ") 'records' ".
            "FROM contact c ".
            "LEFT JOIN agency_type agt on c.agency_type_id=agt.agency_type_id ".
            "LEFT JOIN contact rec on c.recruiter_id=rec.contact_id and rec.contact_type='Recruiter' ".
            "LEFT JOIN contact com on c.company_id=com.contact_id and com.contact_type='Company' ".
            "LEFT JOIN contact age on c.agency_id=age.contact_id and age.contact_type='Agency' ".
            //"LEFT JOIN contact emp on c.employer_id=emp.contact_id and emp.contact_type='Employer' ".
            "LEFT JOIN location tol on c.to_country_id=tol.location_id and tol.location_type_id=1 ".
            "LEFT JOIN register_type ret on c.register_type_id=ret.register_type_id ".
            "LEFT JOIN document_type dot on c.document_type_id=dot.document_type_id ".
            "LEFT JOIN service_type svt on c.service_type_id=svt.service_type_id ".
            "WHERE '$contact_type' in ('', c.contact_type) ".
            "AND $to_country_id in (0, c.to_country_id) ".
            "AND $company_id in (0, c.company_id) ".
            "AND $agency_id in (0, c.agency_id) ".
            //"AND $employer_id in (0, c.employer_id) ".
            "AND $agency_type_id in (0, c.agency_type_id) ".
            "AND $register_type_id in (0, c.register_type_id) ".
            "AND $document_type_id in (0, c.document_type_id) ".
            "AND $recruiter_id in (0, c.recruiter_id) ".
            "AND $service_type_id in (0, c.service_type_id) ".
            "AND '$register_key' in ('', c.register_key) ".
            "AND ('$search'='' || ".
                "('$search_option'='exact' && c.$search_by='$search') || ".
                "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
                "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
            ($date_of==""?"": "AND (($all_date=1 && c.$date_of is not null) || c.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ").
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

    function get_reports(Contact_model $contact)
    {
        //$display = isset($contact->display)? $contact->display:10;
        //$page = isset($contact->page)?$contact->page:1;
        //$offset = ($page-1) * $display;
        //$show_all = isset($contact->show_all) && ($contact->show_all==1 || $contact->show_all==true);

        $search = isset($contact->search)? $contact->search: "";
        $search_by = isset($contact->search_by)? $contact->search_by: "contact_name";

        $contact_type = isset($contact->contact_type)? $contact->contact_type : "";
        $to_country_id = isset($contact->to_country_id)? $contact->to_country_id:0;
        $company_id = isset($contact->company_id)? $contact->company_id:0;
        $agency_id = isset($contact->agency_id)? $contact->agency_id:0;
        //$employer_id = isset($contact->employer_id)? $contact->employer_id:0;
        $agency_type_id = isset($contact->agency_type_id)? $contact->agency_type_id:0;
        $register_type_id = isset($contact->register_type_id)? $contact->register_type_id:0;
        $document_type_id = isset($contact->document_type_id)? $contact->document_type_id:0;
        $recruiter_id = isset($contact->recruiter_id)? $contact->recruiter_id:0;
        $service_type_id = isset($contact->service_type_id)? $contact->service_type_id:0;
        $register_key = isset($contact->register_key) ? $contact->register_key: "";

        $all_date = isset($contact->all_date) && $contact->all_date==1? 1 : 0;
        $date_of = isset($contact->date_of)? $contact->date_of : "";
        $from_date = isset($contact->from_date)? $contact->from_date : Date('Y-m-d');
        $to_date = isset($contact->to_date)? $contact->to_date : Date('Y-m-d');

        $search_option = isset($contact->search_option)? $contact->search_option : 'like';


        $sql = "SELECT c.*, com.contact_name company_name, age.contact_name agency_name, ".
            "age_add.address agency_address, pob.address place_of_birth, ".
            //"emp.contact_name employer_name, emp_add.address employer_address, ".
            "c_add.address contact_address ".
            "FROM contact c ".
            "LEFT JOIN agency_type agt on c.agency_type_id=agt.agency_type_id ".
            "LEFT JOIN contact rec on c.recruiter_id=rec.contact_id and rec.contact_type='Recruiter' ".
            "LEFT JOIN contact com on c.company_id=com.contact_id and com.contact_type='Company' ".
            "LEFT JOIN contact age on c.agency_id=age.contact_id and age.contact_type='Agency' ".
            //"LEFT JOIN contact emp on c.employer_id=emp.contact_id and emp.contact_type='Employer' ".
            "LEFT JOIN contact_address age_add on age.contact_id=age_add.contact_id and age_add.address_key='contact' ".
            //"LEFT JOIN contact_address emp_add on emp.contact_id=emp_add.contact_id and emp_add.address_key='contact' ".
            "LEFT JOIN contact_address pob on c.contact_id=pob.contact_id and pob.address_key='pob' ".
            "LEFT JOIN contact_address c_add on c.contact_id=c_add.contact_id and c_add.address_key='contact' ".
            //"LEFT JOIN contact_address f_add on c.contact_id=f_add.contact_id and f_add.address_key='parent_address' ".
            "LEFT JOIN location tol on c.to_country_id=tol.location_id and tol.location_type_id=1 ".
            "LEFT JOIN register_type ret on c.register_type_id=ret.register_type_id ".
            "LEFT JOIN document_type dot on c.document_type_id=dot.document_type_id ".
            "LEFT JOIN service_type svt on c.service_type_id=svt.service_type_id ".
            "WHERE '$contact_type' in ('', c.contact_type) ".
            "AND $to_country_id in (0, c.to_country_id) ".
            "AND $company_id in (0, c.company_id) ".
            "AND $agency_id in (0, c.agency_id) ".
            //"AND $employer_id in (0, c.employer_id) ".
            "AND $agency_type_id in (0, c.agency_type_id) ".
            "AND $register_type_id in (0, c.register_type_id) ".
            "AND $document_type_id in (0, c.document_type_id) ".
            "AND $recruiter_id in (0, c.recruiter_id) ".
            "AND $service_type_id in (0, c.service_type_id) ".
            "AND '$register_key' in ('', c.register_key) ".
            "AND ('$search'='' || ".
            "('$search_option'='exact' && c.$search_by='$search') || ".
            "('$search_option'='start_with' && c.$search_by LIKE '$search%' ESCAPE '!') || ".
            "('$search_option'='like' && c.$search_by LIKE '%$search%' ESCAPE '!')) ".
            ($date_of==""?"": "AND (($all_date=1 && c.$date_of is not null) || c.$date_of BETWEEN '$from_date 00:00:00' and '$to_date 23:59:59') ")
            //($show_all? "" : "LIMIT $offset, $display")
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
        $this->db->select("c.*,nat.nationality_name, agt.agency_type_name, emp.contact_name employer_name, ".
            "svt.service_type_name, rgt.register_type_name, dot.document_type_name, ".
            "cat.cancel_type_name, l.location_name to_country_name, com.contact_name company_name, ".
            "agc.contact_name agency_name, rec.contact_name recruiter_name, bdc.border_crossings_name"
            )
            ->from('contact c')
            ->join("nationality nat","nat.nationality_id=c.nationality_id", "left")
            ->join("agency_type agt","agt.agency_type_id=c.agency_type_id", "left")
            ->join("service_type svt","svt.service_type_id=c.service_type_id", "left")
            ->join("register_type rgt","rgt.register_type_id=c.register_type_id", "left")
            ->join("document_type dot","dot.document_type_id=c.document_type_id", "left")
            ->join("cancel_type cat","cat.cancel_type_id=c.cancel_type_id", "left")
            ->join("location l","l.location_id=c.to_country_id and l.location_type_id=1", "left")
            ->join("contact com", "c.company_id=com.contact_id and com.contact_type='Company'", "left")
            ->join("contact agc", "c.agency_id=agc.contact_id and agc.contact_type='Agency'", "left")
            ->join("contact emp", "c.employer_id=emp.contact_id and emp.contact_type='Employer'", "left")
            ->join("contact rec", "c.recruiter_id=rec.contact_id and rec.contact_type='Recruiter'", "left")
            ->join("border_crossings bdc", "c.border_crossings_id=bdc.border_crossings_id", "left")

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

    function is_exist_worker_code(Contact_model $contact)
    {
        $this->db->where('worker_code', $contact->worker_code);
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

    function generate_worker_code(Contact_model &$model)
    {
        if(isset($model->worker_code) && $model->worker_code!='') return $model->worker_code;

        $model->prefix = isset($model->prefix)? $model->prefix:'REG';
        $prefix = strtoupper($model->prefix);
        $prefix .= Date('ym-');
        //$prefix = $prefix==""?"": $prefix."-";
        $digits = "0001";
        $code = $prefix.$digits;

        $sql = "select worker_code from contact where worker_code LIKE '$prefix%' order by worker_code desc limit 1";

        $result = $this->db->query($sql);

        if($result && $result->num_rows()>0)
        {

            $number = (int) substr($result->first_row()->worker_code, strlen($prefix));
            $number ++;

            $code = $prefix.str_pad($number, strlen($digits) , "0", 0);
        }

        $model->worker_code = $code;

        return $model->worker_code;
    }

//    function check_data(Contact_model &$contact)
//    {
//        foreach($contact as $key=>$val)
//        {
//            if (strpos($key, '_id') !== false)
//            {
//                if(!isset($val) || $val=='' || $val==0 ) $contact->$key=null;
//            }
//            else if(strpos($key, 'date') !== false)
//            {
//                if(!isset($val) || $val=='' || $val=='0000-00-00' || $val=='00-00-0000' ) $contact->$key=null;
//            }
//        }
//    }

    function add(Contact_model &$contact)
    {
        $this->generate_code($contact);

        if($this->is_exist_code($contact))
        {
            return Message_result::error_message($contact->contact_type.' Code is exist');
        }

        if($contact->contact_type=='Register' && isset($contact->worker_code) && $contact->worker_code!='' && $this->is_exist_worker_code($contact))
        {
            return Message_result::error_message('Worker Code is exist. Add::'.$contact->worker_code);
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
            return Message_result::error_message($contact->contact_type.' Code is exist');
        }

        if($contact->contact_type=='Register' && isset($contact->worker_code) && $contact->worker_code!='' && $this->is_exist_worker_code($contact))
        {
            return Message_result::error_message('Worker Code is exist. Add::'.$contact->worker_code);
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