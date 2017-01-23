<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Contact_address_model extends Model_base
{

    function __construct()
    {
        parent::__construct();
    }

    public $address_id = 0;
    public $address = '';
    public $address_key = 'contact';
    public $contact_id = 0;
    public $country_id;
    public $province_city_id;
    public $district_khan_id;
    public $commune_sangkat_id;
    public $village_id;
    public $location_id;
    public $house_no='';
    public $street_no='';
    public $late = 0;
    public $long = 0;

    function gets(Contact_address_model $model)
    {
        $sql="Select ca.*, co.location_name 'country', pc.location_name 'province', dk.location_name 'district', cs.location_name 'commune', vi.location_name 'village', lo.location_name 'location' ".
            "From contact_address ca ".
            "Left Join location co on co.location_id=ca.country_id ".
            "Left Join location pc on pc.location_id=ca.province_city_id ".
            "Left Join location dk on dk.location_id=ca.district_khan_id ".
            "Left Join location cs on cs.location_id=ca.commune_sangkat_id ".
            "Left Join location vi on vi.location_id=ca.village_id ".
            "Left Join location lo on lo.location_id=ca.location_id ".
            "Where contact_id='$model->contact_id' ".
            "And ('$model->address_key'='' || address_key='$model->address_key') "
        ;

        $query =$this->db->query($sql);

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', null, $query->result('Contact_model'));
        }
    }

    function get(Contact_address_model $model)
    {
        $sql="Select ca.*, co.location_name 'country', pc.location_name 'province', dk.location_name 'district', cs.location_name 'commune', vi.location_name 'village', lo.location_name 'location' ".
            "From contact_address ca ".
            "Left Join location co on co.location_id=ca.country_id ".
            "Left Join location pc on pc.location_id=ca.province_city_id ".
            "Left Join location dk on dk.location_id=ca.district_khan_id ".
            "Left Join location cs on cs.location_id=ca.commune_sangkat_id ".
            "Left Join location vi on vi.location_id=ca.village_id ".
            "Left Join location lo on lo.location_id=ca.location_id ".
            "Where address_id='$model->address_id' "
        ;

        $query =$this->db->query($sql);

        if(!$query || $query->num_rows()== 0)
        {
            return Message_result::error_message('Search not found');
        }
        else
        {
            return Message_result::success_message('', $query->first_row('Contact_address_model'));
        }
    }

    function is_exist(Contact_address_model $contact_address)
    {
        $this->db->where('address_id', $contact_address->address_id);

        $result =$this->db->get('contact_address');

        return $result && $result->num_rows()> 0;
    }

    function add(Contact_address_model &$model)
    {

        //for mysqli driver
        unset($model->address_id);
        if($model->province_city_id==0 || $model->province_city_id=='') unset($model->province_city_id);
        if($model->district_khan_id==0 || $model->district_khan_id=='') unset($model->district_khan_id);
        if($model->commune_sangkat_id==0 || $model->commune_sangkat_id=='') unset($model->commune_sangkat_id);
        if($model->village_id==0 || $model->village_id=='') unset($model->village_id);

        try{
            $result=$this->db->insert('contact_address', $model);
        }catch (Exception $ex){
            return Message_result::error_message('Cannot add');
        }

        if(!$result )
        {
            return Message_result::error_message('Cannot add');
        }
        else
        {
            $model->address_id = $this->db->insert_id();
            return Message_result::success_message('Add success', $model);
        }

    }

    function update(Contact_address_model $model)
    {
        if($model->province_city_id==0 || $model->province_city_id=='') unset($model->province_city_id);
        if($model->district_khan_id==0 || $model->district_khan_id=='') unset($model->district_khan_id);
        if($model->commune_sangkat_id==0 || $model->commune_sangkat_id=='') unset($model->commune_sangkat_id);
        if($model->village_id==0 || $model->village_id=='') unset($model->village_id);

        $this->db->where('address_id', $model->address_id);

        try{
            $result = $this->db->update('contact_address', $model);
        }catch (Exception $ex){
            return Message_result::error_message('Cannot update');
        }

        if(!$result )
        {
            return Message_result::error_message('Cannot update');
        }
        else
        {
            return Message_result::success_message('Update success', $model);
        }
    }

    function delete(Contact_address_model $model)
    {
        $this->db->where('address_id', $model->address_id);

        try{
            $result=$this->db->delete('contact_address');
        }catch (Exception $ex){
            return Message_result::error_message('Cannot delete');
        }

        if(!$result )
        {
            return Message_result::error_message('Cannot delete');
        }
        else
        {
            return Message_result::success_message('Delete success',$model);
        }
    }

}