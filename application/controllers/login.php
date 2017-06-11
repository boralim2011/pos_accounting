<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends My_Controller
{
    function __construct()
    {
        parent::__construct(false);

    }

	public function index()
    {
        $this->login();
    }

    public function is_login()
    {
        //$users = $this->session->all_userdata();
        $user = $this->session->userdata('user');

        return isset($user);
    }

    public function login()
    {
        if ($this->is_login()) {
            redirect(base_url());
            exit;
        }

        $data=array();

        if(isset($_POST['submit']))
        {

            $data['email'] = $this->input->post('email');
            $data['password'] = $this->input->post('password');

            $data = $this->security->xss_clean($data);

            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[3]|max_length[20]');


            if ($this->form_validation->run())
            {
                $user_model = new User_model();
                Model_base::map_objects($user_model, $data);



                $result = $user_model->login($user_model);



                if ($result->success)
                {

                    $user = &$result->model;

                    if (isset($user->image) && $user->image != '')
                    {
                        $user->photo_path = $this->get_user_image_site().$user->image;
                    }
                    else
                    {
                        $user->photo_path = $this->default_user_image();
                    }

                    $this->session->set_userdata(array('user' => $user));


                    $message = $this->create_message($result->message);
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return true;
                }
                else
                {
                    $message = $this->create_message($result->message, 'Error');
                    $result->message = json_encode($message);
                    echo json_encode($result);
                    return false;
                }
            }
            else
            {
                //echo validation_errors();
                $message = $this->create_message(validation_errors(), 'Error');
                $result = new Message_result();
                $result->message = json_encode($message);
                echo json_encode($result);
                return false;
            }

        }
        else
        {
            $this->load->view('login', $data);
        }
    }

    public function logout()
    {
        if ($this->is_login())
        {
            $this->session->unset_userdata('user');
        }
        redirect('login');
    }

    function reset_password(){
        $data=array();

        if(isset($_POST['submit']))
        {
            $password = $this->string_random();

            $data['email'] = $this->input->post('email');
            $data = $this->security->xss_clean($data);

            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[3]|max_length[100]');

            if ($this->form_validation->run())
            {
                $user_model = new User_model();
                $user_model->email = $data['email'];
                $user_model->password = $password;

                $result = $user_model->reset_password($user_model);

                if ($result->success)
                {
                    $message = "<h4>Your password has been reset.</h4><p>Here is your new password.</p><h2>$password</h2>";

                    $send_mail = $this->send_mail($user_model->email, 'Your new password', $message);

                    if($send_mail===true)
                    {
                        redirect(base_url());
                    }
                    else
                    {
                        echo $send_mail;
                    }
                }
                else
                {
                    echo $result->message;
                }
            }
            else
            {
                echo validation_errors();
            }
        }

        $this->load->view('reset_password', $data);
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */