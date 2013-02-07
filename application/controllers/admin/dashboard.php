<?php
class Dashboard extends Admin_Controller
{
	protected $currentUser;

    /**
     * get current logged in user from session
     */
    public function __construct()
	{
		parent::__construct();
        $id = $this->session->userdata('id');
        $this->currentUser = $this->user_model->get($id);
	}

    /**
     * render main layout for admin area
     * get user data
     */
    public function index()
    {
        $this->data['user'] = $this->currentUser;
        $this->data['subview'] = 'admin/user/index';
        $this->load->view('admin/_layout_main', $this->data);
    }

    /**
     * render modal layout for admin area
     */
    public function modal()
    {
        $this->load->view('admin/_layout_modal',$this->data);
    }
}
