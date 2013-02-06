<?php
class Dashboard extends Admin_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

    /**
     * render main layout for admin area
     */
    public function index()
    {
        $this->data['user'] = $this->user_model->get(1); //TODO: get id of current logged in user
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
