<?php
/**
 * class User_Model
 * extends Jay_Model
 *
 * handles usermanagement
 * - login
 * - sessions
 * - user credential validation
 * - create user
 * - get user
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */

class User_Model extends Jay_Model
{
    /**
     * set db properties for user table
     * set default order
     */
    protected $_table_name = 'user';
    protected $_order_by = 'username';

    /**
     * validation rules for login
     * @var array
     */
    public $rules = array(
        'username' => array(
            'field' => 'username',
            'label' => 'Benutzername',
            'rules' => 'trim|required|xss_clean'
        ),
        'password' => array(
            'field' => 'password',
            'label' => 'Passwort',
            'rules' => 'trim|required'
        )
    );

    /**
     * validation rules for register
     * @var array
     */
    public $register_rules = array(
        'username' => array(
            'field' => 'username',
            'label' => 'Benutzername',
            'rules' => 'trim|required|xss_clean'
        ),
        'password_register' => array(
            'field' => 'password_register',
            'label' => 'Passwort',
            'rules' => 'trim|matches[password_register_confirm]'
        ),
        'password_register_confirm' => array(
            'field' => 'password_confirm',
            'label' => 'Passwort bestätigen',
            'rules' => 'trim|matches[password_register]'
        )
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * user login
     * fetch user by username & password
     * check if they are matching then login
     * and start session
     * working with CI Sessions
     *
     * @return void
     */
    public function login()
    {
        $user = $this->getBy( array(
            'username' => $this->input->post( 'username' ),
            'password' => $this->hash( $this->input->post( 'password' ) ),
        ), true );

        if( count( $user ) ) { //if user was found -> log in and create session
            $data = array(
                'username' => $user->username,
                'id' => $user->id,
                'loggedin' => true,
            );
            $this->session->set_userdata( $data ); //store info in session
        }
    }

    /**
     * logout user
     * destroy session
     *
     * @return void
     */
    public function logout()
    {
        $this->session->sess_destroy();
    }

    /**
     * check if user is logged in
     *
     * @return boolean
     */
    public function loggedin()
    {
        return (bool) $this->session->userdata( 'loggedin' );
    }

    /**
     * create hash value of password
     *
     * @param String $password
     * @return  String, encrypted + salted password
     */
    public function hash($password)
    {
        return $password;
        return hash( 'sha512', $password . config_item( 'encryption_key' ) );
    }
}