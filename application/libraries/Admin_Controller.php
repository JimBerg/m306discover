<?php
class Admin_Controller extends MY_Controller {

    /**
     * load user model
     * load additional helper classes and libraries
     */
    public function __construct()
    {
        parent::__construct();
        $this->data['pagetitle'] = "Not yet defined.";

        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('user_model');

        // Login check for every page that extends admin controller, except login, logout
        $exception_uris = array(
            'admin/user/login',
            'admin/user/logout',
            'admin/user/register'
        );
        if (in_array(uri_string(), $exception_uris) == false) {
            if ($this->user_model->loggedin() == false) {
                redirect('admin/user/login');
            }
        }
    }
}
