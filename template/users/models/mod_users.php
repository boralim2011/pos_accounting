<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mod_users extends CI_Model {

    private $firstname;
    private $lastname;
    private $gender;
    private $email;
    private $phone;
    private $address;
    private $url;
    private $username;
    private $password;
    private $hash;
    private $token;
    private $trash;
    private $role;

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getHash() {
        return $this->hash;
    }

    public function setHash($hash) {
        $this->hash = $hash;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function getTrash() {
        return $this->trash;
    }

    public function setTrash($trash) {
        $this->trash = $trash;
    }

    public function login($username, $password, $remember = FALSE) {
        $this->db->select("users.id as id, username, firstname, lastname, password,
            gender, email, phone, title, address, url, people_id, users.created")
                ->from('users')
                ->join('roles', 'roles.id = users.role_id')
                ->join('people', 'people.id = users.people_id')
                ->where('users.username', $username)
                ->where('users.password', $password)
                ->where('users.trash', 0)
        ;
        $data = $this->db->get();
        if ($data->num_rows() > 0) {
            return $this->generateSession($data, $remember);
        }
        return FALSE;
    }

    public function checkUser($safeUser) {
        $this->db->select("users.id as id, username, firstname, lastname, password, 
            gender, email, phone, title, address, url, people_id, users.created")
                ->from('users')
                ->join('roles', 'roles.id = users.role_id')
                ->join('people', 'people.id = users.people_id')
                ->where('users.hash', sha1($safeUser.  sha1(config_item('hash_key'))))
                ->limit(1)
        ;
        $data = $this->db->get();
        if ($data->num_rows() > 0) {
//            return $this->generateSession($data, TRUE);
            return TRUE;
        }
        // Clear session
        $this->session->sess_destroy();
        // Clear cookie
        delete_cookie('remember_me');
        return FALSE;
    }

    public function generateSession($data, $remember = FALSE) {
        foreach ($data->result_array() as $row) {
            $this->session->set_userdata('loggedin', TRUE);
            $this->session->set_userdata('username', $row['username']);
            $this->session->set_userdata('firstname', $row['firstname']);
            $this->session->set_userdata('lastname', $row['lastname']);
            $this->session->set_userdata('userrole', $row['title']);
            $this->session->set_userdata('userid', $row['id']);
            $this->session->set_userdata('peopleid', $row['people_id']);
            $this->session->set_userdata('gender', $row['gender']);
            $this->session->set_userdata('email', $row['email']);
            $this->session->set_userdata('address', $row['address']);
            $this->session->set_userdata('phone', $row['phone']);
            $this->session->set_userdata('url', $row['url']);
            $this->session->set_userdata('password', sha1($row['password'].  config_item('hash_key')));
            $this->session->set_userdata('created', $row['created']);

            // Create cookie to remember user
            if ($remember && !($this->input->cookie('remember_me'))) {
                $cookie = array(
                    'name' => 'remember_me',
                    'value' => sha1(getUsername() . config_item('encryption_key').  getPassword()),
                    'expire' => '1209600', // Two weeks
                );
                
                $this->input->set_cookie($cookie);
            }
        }
        return TRUE;
    }

    /**
     * Register new user
     * 1. Add to people table
     * 2. Add to users table
     * @param Mod_users $objUser
     * @return boolean 
     */
    public function register(Mod_users $objUser) {
        $dateCreate = now();
        $dateModify = $dateCreate;
        $data = array(
            'firstname' => $objUser->getFirstname(),
            'lastname' => $objUser->getLastname(),
            'gender' => $objUser->getGender(),
            'phone' => $objUser->getPhone(),
            'email' => $objUser->getEmail(),
            'address' => $objUser->getAddress(),
            'url' => $objUser->getUrl(),
            'created' => unix_to_human($dateCreate, TRUE),
            'modified' => unix_to_human($dateModify, TRUE)
        );
        $insert = $this->db->insert('people', $data);

        // If success insert to table user
        if ($insert) {
            $peopleId = $this->db->insert_id();
            $data = array(
                'username' => $objUser->getUsername(),
                'password' => $objUser->getPassword(),
                'created' => unix_to_human($dateCreate, TRUE),
                'modified' => unix_to_human($dateModify, TRUE),
                'role_id' => $objUser->getRole(),
                'people_id' => $peopleId,
                'trash' => $objUser->getTrash(),
                'hash' => $objUser->getHash(),
                'token' => $objUser->getToken()
            );
            if ($this->db->insert('users', $data))
                return TRUE;
        }
        return FALSE;
    }

    /**
     * Activate Account : In order to activate account, we need to check:
     * 1. User exist or  not
     * 2. User already activate or not
     * 3. If user is not yet activated, we activate and update token
     * 4. If user was already activated, we not activate and count access 1
     * @param Mod_users $objUser
     * @return boolean
     */
    public function activateAccount(Mod_users $objUser) {
        $this->db->select('*')
                ->from('users')
                ->where('users.token', $objUser->getToken())
                ->where('users.trash', 1)
                ->limit(1)
        ;
        $data = $this->db->get();

        // User is not yet activated
        if ($data->num_rows() > 0) {
            $data = array(
                'users.token' => '',
                'users.trash' => $objUser->getTrash()
            );
            $this->db->where('users.token', $objUser->getToken());
            return $this->db->update('users', $data);
        } else {
            $this->session->set_userdata('max_access', $this->session->userdata('max_access') + 1);
        }
        return FALSE;
    }

    public function forgetPassword(Mod_users $objUser) {
        $this->db->select("*")
                ->from('users')
                ->join('roles', 'roles.id = users.role_id')
                ->join('people', 'people.id = users.people_id')
                ->where('users.username', $objUser->getUsername())
                ->where('people.email', $objUser->getEmail())
                ->where('users.trash', 0)
                ->limit(1)
        ;
        $data = $this->db->get();
        if ($data->num_rows()) {
            foreach ($data->result() as $row) {
                $objUser->setUsername($row->username);
                $objUser->setPassword($row->password);
                $objUser->setEmail($objUser->getEmail());
                $objUser->setFirstname($row->firstname);
                $objUser->setLastname($row->lastname);

                $date = now();
                $token = sha1(sha1($objUser->getUsername()) . md5($objUser->getPassword()) . sha1($date) . sha1($objUser->getEmail()) . config_item('encryption_key'));
                $objUser->setToken($token);

                // Update token
                $data = array(
                    'users.token' => $objUser->getToken()
                );
                $this->db->where('users.username', $objUser->getUsername());
                $this->db->update('users', $data);

                return $objUser;
            }
        }

        return FALSE;
    }

    public function confirmPassword(Mod_users $objUser) {
        $this->db->select('*')
                ->from('users')
                ->where('users.token', $objUser->getToken())
                ->where('users.trash', 0)
                ->limit(1)
        ;
        $data = $this->db->get();
        if ($data->num_rows() > 0) {
            return $data;
        } else {
            $this->session->set_userdata('max_access', $this->session->userdata('max_access') + 1);
        }
        return FALSE;
    }

    public function updatePassword(Mod_users $objUser) {
        $data = array(
            'users.token' => $objUser->getToken(),
            'users.password' => $objUser->getPassword(),
            'users.hash'=>$objUser->getHash()
        );
        $this->db->where('users.token', $token);
        return $this->db->update('users', $data);
    }

    public function updateUserProfile(Mod_users $objUser) {
        $data = array(
            'firstname' => $objUser->getFirstname(),
            'lastname' => $objUser->getLastname(),
            'gender' => $objUser->getGender(),
            'email' => $objUser->getEmail(),
            'phone' => $objUser->getPhone(),
            'address' => $objUser->getAddress(),
            'url' => $objUser->getUrl(),
//            'modified' => now()
        );
        $this->db->where('id', getPeopleId());
        if ($this->db->update('people', $data)) {
            // Generate session
            $this->db->select("users.id as id, username, firstname, lastname, password,
            gender, email, phone, title, address, url, people_id, users.created")
                    ->from('users')
                    ->join('roles', 'roles.id = users.role_id')
                    ->join('people', 'people.id = users.people_id')
                    ->where('users.id', getUserId())
                    ->where('users.trash', 0)
            ;
            $getDb = $this->db->get();
            if ($getDb->num_rows() > 0) {
                return $this->generateSession($getDb);
            }
        }
        return FALSE;
    }

    public function changePassword($newPass, $hash) {
        $data = array(
            'users.hash' => $hash,
            'users.password' => $newPass,
            'users.modified'=> unix_to_human(now(),TRUE)
        );
        $this->db->where('users.id', getUserId());
        return $this->db->update('users', $data);
    }

}

