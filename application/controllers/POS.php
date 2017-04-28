<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class POS extends My_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->Menu = 'POS';
    }

	public function index()
	{
        if(isset($_POST['ajax']))
        {
            $this->load->view('pos_home');
        }
        else
        {
            $data['title'] = 'POS';
            $data['view'] = 'pos_home';
            $this->load->view('pos_template', $data);
        }

    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */