<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class My_controller extends CI_Controller
{
    function __construct($check_session = true)
    {
        parent::__construct();


        //date_default_timezone_set(date_default_timezone_get());
        date_default_timezone_set('Asia/Phnom_Penh');

        //load model
        $this->load->model('User_model');

        //check login
        if($check_session) $this->check_login();


        $this->lang->load('general','khmer');
        //echo $this->lang->line('add');
    }

    public $Menu = 'home';
    public $UserSession;

    public $Country_names = array(2=>'Cambodia',3 =>'Thai', 4=> 'Malaysia');

    function check_login()
    {

        //$users = $this->session->all_userdata();
        $user = $this->session->userdata('user');

        if(!isset($user))
        {
            if(isset($_POST['ajax']) || isset($_POST['submit']))
            {
                echo '521';
                $location =  base_url()."login";
                header("status: 521 $location");
                header("Refresh: 0; url=$location");
                exit;
            }
            else
            {
                redirect( 'login', "refresh");
            }
        }


        if(!isset($this->UserSession))
        {
            $session_user = new User_model();
            Model_base::map_objects($session_user, $user, true);

            $this->UserSession = $session_user;
        }

        //$this->session->sess_destroy();
        //$this->session->unset_userdata('user');
    }

    function load_header_view($page, $title)
    {
        $data['Page'] = $page;
        $data['Title'] = $title;

        $data['UserSession'] = $this->UserSession;

        $this->load->view('template/header', $data);
    }

    function show_404()
    {
        if(isset($_POST['ajax']))
        {
            $this->load->view('404');
        }
        else
        {
            $data['title'] = 'Error:404 Page not found';
            $data['view'] = '404';
            //$data['script_view'] = '';
            $this->load->view('template', $data);
        }
    }

    function show_500()
    {
        if(isset($_POST['ajax']))
        {
            $this->load->view('500');
        }
        else
        {
            $data['title'] = 'Error:500 Something went wrong';
            $data['view'] = '500';
            //$data['script_view'] = '';
            $this->load->view('template', $data);
        }
    }

    function show_message($message='')
    {
        //        if(isset($_POST['message']))
        //        {
        //            //var_dump($_POST);
        //            $message = json_decode($_POST['message']);
        //        }
        //
        //        $title="";
        //        $type = "";
        //        if(is_array($message)){
        //            $temp = $message;
        //            $message = isset($temp['text'])? $temp['text']:'';
        //            $title = isset($temp['title'])? $temp['title']:'';
        //            $type = isset($temp['type'])? $temp['type']:'';
        //        }
        //        elseif(is_object($message)){
        //            $temp = $message;
        //            $message = isset($temp->text)? $temp->text :'';
        //            $title = isset($temp->title)? $temp->title:'';
        //            $type = isset($temp->type)? $temp->type:'';
        //        }
        //
        //        $types = array("Success"=>"info","Error"=>"warning", "Warning"=>"success");
        //        if(!array_key_exists($type, $types)) $type = "Success";
        //        if(!isset($title) || $title=='') $title = $type;
        //
        //        $data['message_title'] = $title;
        //        $data['message'] = $message;
        //        $data['message_type'] = $types[$type];
        //        $this->load->view('message', $data);


    }

    function show_error($message='')
    {
        if($message!='')
            echo '<script type="text/javascript">
                    /*<!--*/
                    var message = \'{"text":"'.$message.'","type":"Error","title":"Error"}\';
                    show_message(message, $("#modal-message"));
                    //-->
                    </script>';
    }

    function create_message($message, $type='Success', $title='' )
    {
        $types = array("Success"=>"info","Error"=>"warning", "Warning"=>"success");
        if(!array_key_exists($type, $types)) $type = "Success";
        if(!isset($title) || $title=='') $title = $type;

        $result = new stdClass();
        $result->text = $message;
        $result->title = $title;
        $result->type = $type;
        return $result;
    }

    function delete_dir($dir_name)
    {
        return file_exists($dir_name)? is_dir($dir_name) && delete_files($dir_name, true) && rmdir($dir_name) : -1;
    }

    function delete_file($file_name)
    {
        return file_exists($file_name) ? is_file($file_name) && unlink($file_name) : -1;
    }

    function get_json_object()
    {
        return isset($_POST['ajax'])? json_decode($_POST['ajax']) : null;
        //return isset($_POST) ? $_POST : null;
    }


    function upload_file($file_path = '', $file_name = '')
    {
        if(!isset($_FILES['file'])) return -1;

        $path = isset($file_path) && $file_path != ''? $file_path : $this->get_file_path();

		$config['upload_path'] = $path;
		$config['allowed_types'] = "gif|png|jpg|jpeg|ico|mp3|mp4|avi|flv|wmv|pdf|doc|docx|xls|xlsx";
        $config['max_size']	= 0;
		$config['max_width']  = 0;
		$config['max_height']  = 0;
        if(isset($file_name) && $file_name != '') $config['file_name'] = $file_name;

		$this->load->library('upload', $config);

        $result = null;

		if (!$this->upload->do_upload('file'))
		{
			//$result = array('error' => $this->upload->display_errors());
            $result = false;
		}
		else
		{
            $result = array('upload_data' => $this->upload->data());
		}

        return $result;
    }

    function send_mail($to, $subject, $message )
    {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'boralim2011@gmail.com', // change it to yours
            'smtp_pass' => 'Boralim@092650838', // change it to yours
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );

        $this->load->library('email', $config);

        $this->email->set_newline("\r\n");
        $this->email->from('boralim2011@gmail.com'); // change it to yours
        $this->email->to($to);// change it to yours
        $this->email->subject($subject);
        $this->email->message($message);

        //return $this->email;

        if($this->email->send())
        {
            return true;
        }
        else
        {
            return $this->email->print_debugger();
        }
    }

    function string_random($length = 6, $small_char = false)
    {
        //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*'.($small_char?"abcdefghijklmnopqrstuvwxyz":"");
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        echo $randomString."<br>";
        return $randomString;
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


    // Check date format, if input date is valid return TRUE else returned FALSE.
    function valid_date($date)
    {
        if(!isset($date) || $date=='') return false;
        if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
            $d=substr($date, 8, 2);
            $m=substr($date, 5, 2);
            $y=substr($date, 0, 4);
            return checkdate($m,$d,$y);
        }

        return false;
    }
}

?>



