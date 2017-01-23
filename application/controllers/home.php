<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends My_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->Menu = 'home';
    }

	public function index()
	{
        if(isset($_POST['ajax']))
        {
            $this->load->view('home');
        }
        else
        {
            $data['title'] = 'Dashboard';
            //$data['view'] = 'home';
            $this->load->view('template', $data);
        }

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */