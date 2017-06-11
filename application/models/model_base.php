<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Bora
 * Date: 12/17/2015
 * Time: 5:34 AM
 */

class Model_base extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    static function map_objects($object, $data, $full_join = false)
    {
        if(!isset($object)) return $data;
        else if(!isset($data)) return $object;

        foreach($data as $key=>$val)
        {
            if($full_join || property_exists($object, $key)) $object->$key = $val;
        }

        return $object;
    }

    static function encrypt_password($password)
    {
        //return $this->encrypt->encode($password);
        //return md5('P@ssw0rd'.$password);
        return sha1('P@ssw0rd'.$password);
    }

    static function round_up( $value, $precision = 0)
    {
        $pow = pow ( 10, $precision );
        return ( ceil( $pow * $value ) + ceil( $pow * $value - ceil( $pow * $value ) ) ) / $pow;
    }

    static function round_down( $value, $precision = 0)
    {
        $pow = pow ( 10, $precision );
        return ( floor( $pow * $value ) + floor( $pow * $value - floor( $pow * $value ) ) ) / $pow;
    }


    function default_user_image()
    {
        return base_url()."template/dist/img/user.png";
    }

    function get_file_site()
    {
        return base_url()."files/";
    }

    function get_file_path()
    {
        return FCPATH."files/";
    }

    function get_photo_site()
    {
        return $this->get_file_site()."photos/";
    }

    function get_photo_path()
    {
        return $this->get_file_path()."photos/";
    }

    function get_user_image_site()
    {
        return $this->get_file_site()."users/";
    }

    function get_user_image_path()
    {
        return $this->get_file_path()."users/";
    }

    function get_logo_image(){
        return base_url()."template/dist/img/logo.png";
    }

    function get_blank_item(){
        return base_url()."files/item_no_image.png";
    }

    function get_item_path()
    {
        return $this->get_file_path()."items/";
    }

    function get_item_site()
    {
        return $this->get_file_site()."items/";
    }

}