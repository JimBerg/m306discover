<?php if ( !defined('BASEPATH') ) exit('No direct script access allowed');

/**
 * Base Controller
 * load libraries and classes
 * define properties and methods which are needed in more than 1 child class
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */

class Jay_Controller extends CI_Controller
{
    /**
     * stores User Object
     * @var stdClass
     */
    private $user;

    /**
     * constructor function
     * load libraries
     * load model
     * set current user and check if session
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library( 'form_validation' );
        $this->load->library( 'session' );

        $this->load->model( 'user_model' );
        $this->load->model( 'profile_model' );
        $this->load->model( 'history_model' );
        $this->load->model( 'quests_model' );
        $this->load->model( 'location_model' );
        self::setCurrentUser();

        //login check for every page that extends jay_controller, except login, logout, register
        $exception_uris = array(
            'user/login',
            'user/logout',
            'user/register'
        );

        //if not in uris and no session active - redirect
        if ( in_array( uri_string(), $exception_uris ) == false ) {
            if ( $this->user_model->loggedin() == false ) {
                redirect( 'user/login' );
            }
        }
    }

    /**
     * set user variable to session data if session exists
     * else redirect to start page
     *
     * @return void
     */
    private function setCurrentUser()
    {
        if ( $this->session->userdata ) { //if session => get user props
            $id = $this->session->userdata( 'id' );
            $this->user = $this->user_model->get( $id );
        } else {
            redirect( 'user/login' );
        }
    }

    /**
     * get current user object
     *
     * @return stdClass
     */
    protected function getCurrentUser()
    {
        return $this->user;
    }
}