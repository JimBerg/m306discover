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
     * render register form
     * create new user - save records to database
     */
    public function register()
    {
        $this->data['subview'] = 'admin/user/register';

        if(!empty($_POST)){ //if something was submitted
            //check if form was correct
            $rules = $this->user_model->rules;
            $this->form_validation->set_rules($rules);

            if($this->form_validation->run() == true) {
                //check if username || email already exists
                $email = $_POST['email'];
                $username = $_POST['username'];
                if($this->user_model->getBy(array('username' => $username))) {
                    $this->data['error'] = "username existiert bereits";
                    $this->load->view('admin/_layout_modal', $this->data);
                    return;
                } else if($this->user_model->getBy(array('email' => $email))) {
                    $this->data['error'] = "email existiert bereits";
                    $this->load->view('admin/_layout_modal', $this->data);
                    return;
                } else {
                    $data = array(
                        'username' => $username,
                        'email' => $email,
                        'password' => $_POST['password']
                    );
                    $this->user_model->save($data, $id = null);
                    $this->user_model->loggedin(); //set login true
                    redirect('admin/dashboard');
                }
            }
        }
        $this->load->view('admin/_layout_modal', $this->data);
    }

    /**
     * edit user
     */
    public function edit()
    {

    }
}