<?php

/** -------------------------------------------------------------------------
 * class for usermanagment
 *
 * that includes
 * - registration
 * - login & logout
 * - usersession managent
 * - render views
 * --------------------------------------------------------------------------  */

class Usermanagement extends User_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    /** --------------------------------------------------------------------------
     * default controller
     * renders start screen // main screen after login
     * --------------------------------------------------------------------------  */
    public function index()
    {
        $this->data['user'] = parent::getCurrentUser();
        $this->data['subview'] = 'user/usermanagement/index';
        $this->load->view('user/_layout_main', $this->data);
    }

    /**
     * render modal layout for dialogs
     */
    public function modal()
    {
        $this->load->view('user/_layout_modal',$this->data);
    }

    /** --------------------------------------------------------------------------
     * handles login
     * renders login form
     * set validation rules
     * --------------------------------------------------------------------------  */
    public function login()
    {
        $this->user_model->loggedin() == false || redirect( 'user/usermanagement' ); //if session redirect

        $rules = $this->user_model->rules;
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == true) { //no validation errors
            if($this->user_model->login() == true) { //user & password match, user exists
                redirect('user/usermanagement');
            } else {
                $this->session->set_flashdata('error', 'Seltsame Kombination, mein Freund. So geht das nicht!');
                redirect('user/usermanagement/login', 'refresh');
            }
        }
        $this->data['subview'] = 'user/usermanagement/login';
        $this->load->view('user/_layout_modal', $this->data);
    }


    /** --------------------------------------------------------------------------
     * handles logout
     * destroy session, redirect to login page
     * --------------------------------------------------------------------------  */
    public function logout()
    {
        $this->user_model->logout();
        redirect('user/usermanagement/login');
    }


    /** --------------------------------------------------------------------------
     * render register form
     * validation check
     * create new user - save records to database
     * start session & login
     * --------------------------------------------------------------------------  */
    public function register()
    {
        $this->data[ 'subview' ] = 'user/usermanagement/register';

        if ( !empty( $_POST ) ) { //if something was submitted

            $rules = $this->user_model->rules;
            $this->form_validation->set_rules( $rules );

            if( $this->form_validation->run() == true ) { //check if valid

                $email = $_POST[ 'email' ];
                $username = $_POST[ 'username' ];

                if ( $this->user_model->getBy( array( 'username' => $username ) ) ) { //check if username || email already exists
                    $this->data[ 'error' ] = "Dieser Username existiert bereits. Versuch doch mal was einfallsreicheres.";
                    $this->load->view( 'user/_layout_modal', $this->data );
                    return;
                } else if( $this->user_model->getBy( array( 'email' => $email ) ) ) {
                    $this->data[ 'error' ] = "Diese Emailadresse ist bereits vorhanden. Seltsam, was?";
                    $this->load->view( 'user/_layout_modal', $this->data );
                    return;
                } else {
                    $data = array(
                        'username' => $username,
                        'email' => $email,
                        'password' => $_POST[ 'password' ]
                    );
                    $this->user_model->save( $data, $id = null );
                    $this->user_model->login(); //set session
                    redirect( 'user/usermanagement' );
                }
            }
        }
        $this->load->view( 'user/_layout_modal', $this->data );
    }


    /** --------------------------------------------------------------------------
     * edit user data
     * --------------------------------------------------------------------------  */
    public function edit()
    {
        $this->data[ 'user' ] = parent::getCurrentUser();
        $this->data[ 'subview' ] = 'user/usermanagement/profile';
        $this->load->view( 'user/_layout_main', $this->data );
    }
}