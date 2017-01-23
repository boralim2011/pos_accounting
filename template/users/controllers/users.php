<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Users extends CI_Controller {

    private $maxAccess = 5;

    function __construct() {
        parent::__construct();
        $this->load->model(array('mod_users'));
    }

    public function index() {
        if (!isLogin()) {
            redirect('users/login');
        }
        $this->profile();
    }

    /**
     * Login Method.
     * 1. If user do not login
     * 2. If user clicks on login button
     * 3. If user does login success
     * 4. If user does login not success 
     */
    public function login() {
        if (isLogin()) {
            redirect('dashboard');
            exit();
        }
        $remember = FALSE;
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[20]');
        if (!$this->form_validation->run()) {
            // Set message
            if ($this->input->post('login')) {
                $this->ms->setMessage(validation_errors(), 'error');
            }
        } else {
            if ($this->input->post('remember') && $this->input->post('remember') == '1') {
                $remember = TRUE;
            }
            if ($this->mod_users->login($this->input->post('username'), sha1($this->input->post('password')), $remember)) {
                $this->ms->setMessage(getRole());
                redirect('dashboard');
                exit();
            } else {
                $this->ms->setMessage($this->lang->line('invalid_account'), 'error');
            }
        }
//        $data['breadcrumbs'] = array('Users', 'Login');
        $data['view'] = 'login.php';
        $data['title'] = $this->lang->line('member_login');
        $this->load->view('layout/admin-layout', $data);
    }

    public function logout() {
        if (isLogin()) {
//            $this->session->sess_destroy(); // Remove all sessions
            $this->session->unset_userdata('loggedin');
            delete_cookie('remember_me'); // Remove cookie
        }
        redirect('users/login');
        exit();
    }

    /**
     * 
     * @param string $token
     * $token is generate from sha1(sha1(username) + md5(old password) + sha1(datetime) + sha1(email) + config_item('encryption_key'))
     * 
     */
    public function confirmPassword($token = '') {
        if (isLogin()) {redirect('dashboard'); exit();}
        
        if (empty($token)) {
            $this->ms->setGlobalMessage($this->lang->line('invalid_key'), 'error');
            redirect('users/login');
            exit();
        }
        if ($this->session->userdata('max_access') && $this->session->userdata('max_access') > $this->maxAccess) {
            $this->ms->setGlobalMessage($this->lang->line('max_access_error'), 'error');
            redirect('users/login');
            exit();
        }
        
        $objUser = new Mod_users();
        $objUser->setToken($token);
        $user = $this->mod_users->confirmPassword($objUser);
        if ($user) {
            $this->ms->setGlobalMessage($this->lang->line('update_password'),'warning');
            $this->updatePassword($user, $token);
//            exit();
        }else{
            $this->ms->setGlobalMessage($this->lang->line('invalid_key'),'error');
            redirect('users/login');
        }
        
    }

    /**
     * After sending email to user, we need to update token to 
     * sha1(sha1(username) + md5(old password) + sha1(datetime) + sha1(email) + config_item('encryption_key'))
     * This token will expire in 12 hours
     */
    public function forgetPassword() {
        if (isLogin()) {redirect('dashboard'); exit();}
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[8]|max_length[100]|valid_email');
        if (!$this->form_validation->run()) {
            if ($this->input->post('forget-password')) {
                $this->ms->setMessage(validation_errors(), 'error');
            }
        }else{
            // Get user's value
            $objUser = new Mod_users();
            $objUser->setUsername($this->input->post('username'));
            $objUser->setEmail($this->input->post('email'));
            
            $data = $this->mod_users->forgetPassword($objUser);
            if($data){
                // Send forget password key
                $this->sendForgetPassword($data->getEmail(), $data->getFirstname().' '. $data->getLastname(), base_url().'users/confirmpassword/'.$data->getToken());
                $this->ms->setGlobalMessage($this->lang->line('forget_password_sent'),'warning');
                redirect('users/login');
                exit();
            }
            $this->ms->setMessage($this->lang->line('invalid_username_email'),'error');
        }
        $data['breadcrumbs'] = array('Users', 'Forgetpassword');
        $data['view'] = 'forgetpassword.php';
        $data['title'] = $this->lang->line('forget_password');
        $this->load->view('layout/admin-layout', $data);
    }
    
    private function sendForgetPassword($to, $name, $url){
        if (isLogin()) {redirect('dashboard'); exit();}
        $this->load->helper('file');
        $this->config->load('email');
        // Get mail template
        $contentTemplate = read_file(TEMPLATE_PATH . '/mails/forget-password.php');
        $ms = sprintf($contentTemplate, $name, $url);

        $config['protocol'] = "smtp";
        $config['smtp_host'] = "mail.pichnil.com";
        $config['smtp_port'] = "25";
        $config['smtp_user'] = config_item('username');
        $config['smtp_pass'] = config_item('password');
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";

        $this->email->initialize($config);

        $this->email->from('info@pichnil.com', 'Pichnil Team');
        $list = array($to);
        $this->email->to($list);
        $this->email->subject('Forget Password');
        $this->email->message($ms);
        if ($this->email->send()) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Update new password
     * Before execute this method, we need to check there is a session (can be from confirmPassword or changePassword) or not.
     * $this->session->userdata('canupdate') = TRUE
     * 
     * Also update token to sha1('change' . config_item('encryption_key')). This token will use by "remember me" option.
     */
    private function updatePassword($user,$token) {
        if (isLogin()) {redirect('dashboard'); exit();}
        $this->form_validation->set_rules('newpassword', 'Password', 'trim|required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('c-newpassword', 'Confirm Password', 'trim|required|min_length[8]|max_length[20]|matches[newpassword]');
        $this->customMessage();
        if (!$this->form_validation->run()) {
            if ($this->input->post('confirmpassword')) {
                $this->ms->setMessage(validation_errors(), 'error');
            }
        }else{
            $newPass = sha1($this->input->post('newpassword'));
            $hash='';
            foreach ($user->result() as $row){
                $hash = sha1(
                        sha1($row->username . config_item('encryption_key').sha1($newPass.  config_item('hash_key')))
                        . sha1(config_item('hash_key'))
                        );
            }
            $objUser = new Mod_users();
            $objUser->setPassword($newPass);
            $objUser->setHash($hash);
            $objUser->setToken('');
            if($this->mod_users->updatePassword($objUser)){
                $this->ms->setGlobalMessage($this->lang->line('update_password_success'));
                redirect('users/login');
                exit();
            }
        }
        $data['token']=$token;
        $data['breadcrumbs'] = array('Users', 'Update Password');
        $data['view'] = 'confirmpassword.php';
        $data['title'] = $this->lang->line('confirm_password');
        $this->load->view('layout/admin-layout', $data);
    }

    /**
     * Register
     * Create hash before insert to database
     * hash = sha1($row['username'] . config_item('encryption_key'))
     */
    public function register() {
        if (isLogin()) {redirect('dashboard'); exit();}
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[2]|max_length[20]')
                ->set_rules('lastname', 'Last Name', 'trim|required|min_length[2]|max_length[20]')
                ->set_rules('gender', 'Gender', 'trim|required|min_length[4]|max_length[7]')
                ->set_rules('email', 'Email', 'trim|required|min_length[8]|max_length[100]|valid_email')
                ->set_rules('phone', 'Phone', 'trim|min_length[6]|max_length[20]')
                ->set_rules('url', 'Website', 'trim|min_length[6]|max_length[100]')
                ->set_rules('address', 'Address', 'trim|min_length[4]|max_length[1000]')
                ->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]|is_unique[users.username]')
                ->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[20]')
                ->set_rules('c-password', 'Confirm Password', 'trim|required|min_length[4]|max_length[20]|matches[password]');
        $this->customMessage();

        if (!$this->form_validation->run()) {
            if ($this->input->post('register')) {
                $this->ms->setMessage(validation_errors(), 'error');
            }
        } else {
            // Set user's value
            $objUser = new Mod_users();
            $objUser->setFirstname($this->input->post('firstname'));
            $objUser->setLastname($this->input->post('lastname'));
            $objUser->setGender($this->input->post('gender'));
            $objUser->setEmail($this->input->post('email'));
            $objUser->setPhone($this->input->post('phone'));
            $objUser->setAddress($this->input->post('address'));
            $objUser->setUrl($this->input->post('url'));
            $objUser->setPassword(sha1($this->input->post('password')));
            $objUser->setUsername($this->input->post('username'));

            
            $hash = sha1(
                    sha1($objUser->getUsername() . config_item('encryption_key').sha1($objUser->getPassword().  config_item('hash_key')))
                    . sha1(config_item('hash_key'))
                    );
            $objUser->setHash($hash);
            $objUser->setTrash(1);
            $objUser->setRole(10);

            $token = sha1(sha1($objUser->getUsername()) . md5($objUser->getPassword()) . sha1(now()) . sha1($objUser->getEmail()) . config_item('encryption_key'));
            $objUser->setToken($token);
            $register = $this->mod_users->register($objUser);

            if ($register) {
                $this->ms->setGlobalMessage('Please check your email to activate account! If you did not receive this email, please check your junk/spam folder.', 'warning');
                // Send activate key
                $this->sendActivateKey($objUser->getEmail(), $objUser->getFirstname() . ' ' . $objUser->getLastname(), base_url() . 'users/activateaccount/' . $objUser->getToken());
                redirect('users/login');
                exit();
            }
            $this->ms->setGlobalMessage('We are sorry, we can not create this account!', 'error');
        }
        $data['breadcrumbs'] = array('Users', 'Register');
        $data['view'] = 'register.php';
        $data['title'] = $this->lang->line('register');

        $this->load->view('layout/admin-layout', $data);
    }

    public function customMessage() {
        $this->form_validation->set_message('required', '%s is required.');
        $this->form_validation->set_message('min_length', '%s must grater than %s characters.');
        $this->form_validation->set_message('max_length', '%s must less than %s characters.');
        $this->form_validation->set_message('is_unique', '%s is not available.');
    }

    /**
     * 
     * @param type $to : Email of user
     * @param type $name : Name of user
     * @param type $url : Activated url
     * @return boolean
     */
    private function sendActivateKey($to, $name, $url) {
        if (isLogin()) {redirect('dashboard'); exit();}
        $this->load->helper('file');
        $this->config->load('email');
        // Get mail template
        $contentTemplate = read_file(TEMPLATE_PATH . '/mails/activate-account.php');
        $ms = sprintf($contentTemplate, $name, $url);
    
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "mail.pichnil.com";
        $config['smtp_port'] = "25";
        $config['smtp_user'] = config_item('username');
        $config['smtp_pass'] = config_item('password');
//        $config['smtp_user'] = 'richat.chhay@urbandesign.asia';
//        $config['smtp_pass'] = 'richat12345';
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        
        $this->email->initialize($config);

        $this->email->from('info@pichnil.com', 'Pichnil Team');
        $list = array($to);
        $this->email->to($list);
        $this->email->subject('Verify Your Account');
        $this->email->message($ms);
        
        if ($this->email->send()) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Activate Account : Activate user's account from sending email. If user try to access more than $this->maxAccess, it stops checking database.
     * @param type $key
     * 
     */
    public function activateAccount($key) {
        if (isLogin()) {redirect('dashboard'); exit();}
        if (empty($key)) {
            $this->ms->setGlobalMessage('Invalid activate key!', 'error');
            redirect('users/login');
            exit();
        }
        if ($this->session->userdata('max_access') && $this->session->userdata('max_access') > $this->maxAccess) {
            $this->ms->setGlobalMessage('You enter invalid key more than 5 times! Now, you can not access this system for a while.', 'error');
            redirect('users/login');
            exit();
        }
        $objUser = new Mod_users();
        $objUser->setToken($key);
        $objUser->setTrash(0); // 0 = activate, 1 = disactivate
        if ($this->mod_users->activateAccount($objUser)) {
            $this->ms->setGlobalMessage('Your account is activated. Please login!');
        }else{
            $this->ms->setGlobalMessage('Invalid activate key!','error');
        }
        redirect('users/login');
    }
    
    public function profile(){
        
        if (!isLogin()) {redirect('users/login'); exit();}
        
        $data['breadcrumbs'] = array('Users', 'Profile');
        $data['view'] = 'profile.php';
        $data['title'] = $this->lang->line('profile');
        $this->load->view('layout/admin-layout', $data);
    }
    
    public function updateProfile(){
        if (!isLogin()) {redirect('users/login'); exit();}
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|min_length[4]|max_length[20]')
                ->set_rules('lastname', 'Last Name', 'trim|required|min_length[2]|max_length[20]')
                ->set_rules('gender', 'Gender', 'trim|required|min_length[4]|max_length[7]')
                ->set_rules('email', 'Email', 'trim|required|min_length[8]|max_length[100]|valid_email')
                ->set_rules('phone', 'Phone', 'trim|min_length[6]|max_length[20]')
                ->set_rules('url', 'Website', 'trim|min_length[6]|max_length[100]')
                ->set_rules('address', 'Address', 'trim|min_length[4]|max_length[1000]')
                ;
        $this->customMessage();

        if (!$this->form_validation->run()) {
            if ($this->input->post('updateprofile')) {
                $this->ms->setMessage(validation_errors(), 'error');
            }
        }else{
            $objUser = new Mod_users();
            $objUser->setFirstname($this->input->post('firstname'));
            $objUser->setLastname($this->input->post('lastname'));
            $objUser->setGender($this->input->post('gender'));
            $objUser->setEmail($this->input->post('email'));
            $objUser->setPhone($this->input->post('phone'));
            $objUser->setAddress($this->input->post('address'));
            $objUser->setUrl($this->input->post('url'));
            
            if($this->mod_users->updateUserProfile($objUser)){
                $this->ms->setGlobalMessage($this->lang->line('success'));
                redirect('users/profile');
            }
//            $this->lang->line('fail')
            $this->ms->setMessage($this->lang->line('fail'),'error');
        }
        $data['breadcrumbs'] = array('Users', 'Update Profile');
        $data['view'] = 'updateprofile.php';
        $data['title'] = $this->lang->line('profile');
        $this->load->view('layout/admin-layout', $data);
    }
    
    public function changePassword(){
        if (!isLogin()) {redirect('users/login'); exit();}
        $this->form_validation->set_rules('newpassword', 'Password', 'trim|required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('c-newpassword', 'Confirm Password', 'trim|required|min_length[8]|max_length[20]|matches[newpassword]');
        $this->customMessage();
        if (!$this->form_validation->run()) {
            if ($this->input->post('changepassword')) {
                $this->ms->setMessage(validation_errors(), 'error');
            }
        }else{
            $newPass = sha1($this->input->post('newpassword'));
            $hash = sha1(
                    sha1(getUsername() . config_item('encryption_key').sha1($newPass.  config_item('hash_key')))
                    . sha1(config_item('hash_key'))
                    );
            if($this->mod_users->changePassword($newPass, $hash)){
                $this->ms->setGlobalMessage($this->lang->line('update_password_success'));
                $this->logout();
            }
            $this->ms->setMessage($this->lang->line('fail'),'error');
        }
        $data['breadcrumbs'] = array('Users', 'Change Password');
        $data['view'] = 'changepassword.php';
        $data['title'] = $this->lang->line('change_password');
        $this->load->view('layout/admin-layout', $data);
    }
    

}

