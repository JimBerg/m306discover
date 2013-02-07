<?php
class User_Controller extends MY_Controller {

    /** --------------------------------------------------------------------------
     * @var object User
     * --------------------------------------------------------------------------  */
    protected $currentUser;


    /** --------------------------------------------------------------------------
     * load user model
     * load additional helper classes and libraries
     * --------------------------------------------------------------------------  */
    public function __construct()
    {
        parent::__construct();
        $this->data[ 'pagetitle' ] = "dis.cover";

        $this->load->library( 'form_validation' );
        $this->load->library( 'session' );
        $this->load->model( 'user_model' );

        // login check for every page that extends admin controller, except login, logout, register
        $exception_uris = array(
            'user/usermanagement/login',
            'user/usermanagement/logout',
            'user/usermanagement/register'
        );
        if ( in_array( uri_string(), $exception_uris ) == false ) {
            if ( $this->user_model->loggedin() == false ) {
                redirect( 'user/usermanagement/login' );
            }
        }
    }


    /** --------------------------------------------------------------------------
     * get current logged in user
     * @return user object || void
     * --------------------------------------------------------------------------  */
    protected function getCurrentUser()
    {
        if ( $this->session->userdata ) { // if session => get user props
            $id = $this->session->userdata( 'id' );
            $this->currentUser = $this->user_model->get( $id );
            return $this->currentUser;
        } else {
            redirect( 'user/usermanagement/login' );
        }
    }
}
