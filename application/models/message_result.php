<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Bora
 * Date: 12/17/2015
 * Time: 5:34 AM
 */

class Message_result extends Model_Base
{

    function __construct($message='', $success=false, $model=null, $models=null)
    {
        parent::__construct();

        $this->message = $message;
        $this->success = $success;
        $this->model = $model;
        $this->models = $models;
    }

    //Message
    public $success;
    public $model;
    public $models;
    public $message;


    function set_error_message($message)
    {
        $this->message = $message;
    }

    function set_success_message($model, $models = array())
    {
        $this->message = 'Success';
        $this->success = true;
        $this->model = $model;
        $this->models = $models;
    }

    function set_message($params = array())
    {
        if(isset($params))
        {
            foreach($params as $key=>$val)
            {
                if(property_exists($this, $key)) $this->$key = $val;
            }
        }
    }


    static function error_message($message)
    {
        $result = new Message_result($message, false);

        return $result;
    }

    static function success_message($message='Success', Model_base $model=null, $models = array())
    {
        $result = new Message_result();
        $result->message = $message;
        $result->success = true;
        $result->model = $model;
        $result->models = $models;

        return $result;
    }

    static function create_message($params = array())
    {
        $result = new Message_result();

        if(isset($params))
        {
            foreach($params as $key=>$val)
            {
                if(property_exists($result, $key)) $result->$key = $val;
            }
        }

        return $result;
    }

}