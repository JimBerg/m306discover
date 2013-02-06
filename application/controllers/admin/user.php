<?php
class User extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * default controller
     */
    public function index()
    {
    }

    /**
     * handles login
     * renders login form
     * set validation rules
     */
    public function login()
    {
        $this->user_model->loggedin() == false || redirect('admin/dashboard'); //if session redirect

        $rules = $this->user_model->rules;
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == true) { //no validation errors
            if($this->user_model->login() == true) { //user & password match, user exists
                redirect('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Seltsame Kombination, mein Freund. So geht das nicht!');
                redirect('admin/user/login', 'refresh');
            }
        }

        $this->data['subview'] = 'admin/user/login';
        $this->load->view('admin/_layout_modal', $this->data);
    }

    /**
     * handles logout
     * destroy session, redirect to login page
     */
    public function logout()
    {
        $this->user_model->logout();
        redirect('admin/user/login');
    }

    /**
     * create user
     */
    public function register()
    {

    }

    /**
     * edit user
     */
    public function edit()
    {

    }
}