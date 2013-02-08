<?php
class User_Model extends MY_Model
{
    /**
     * set db properties of user table
     * declare validation rules
     */
    protected $_table_name = 'user';
    protected $_order_by = 'username';


    /**
     * validation rules
     * @var array
     */
    public $rules = array(
        'email' => array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|xss_clean'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required'
        )
    );

    //passwordconfirm match , unique email custom callback


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * fetch user by email & password
     * check if they are matching -> login
     * start session
     */
    public function login()
    {
        $user = $this->getBy(array(
            'email' => $this->input->post('email'),
            'password' => $this->hash($this->input->post('password')),
        ), true);

        if(count($user)) { //if user was found -> log in and create session
            $data = array(
                'name' => $user->username,
                'email' => $user->email,
                'id' => $user->id,
                'loggedin' => true,
            );
            $this->session->set_userdata($data); //store info in session
        }
    }


    /**
     * logout - destroy session
     */
    public function logout()
    {
        $this->session->sess_destroy();
    }


    /**
     * check if user is logged in
     * @return boolean
     */
    public function loggedin()
    {
        return (bool) $this->session->userdata('loggedin');
    }


    /**
     * create hash value of password
     * @param String $password
     * @return encrypted + salted password
     */
    public function hash($password)
    {
        return $password;
        return hash('sha512', $password . config_item('encryption_key'));
    }
}