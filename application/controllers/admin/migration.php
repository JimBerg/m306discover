<?php
class Migration extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{

		$this->load->library('migration');
				
		if(!$this->migration->current()) { //check for latest version in config file
			show_error($this->migration->error_string());
		} else {
			echo "Migration successful";
			die();
		}
	}
}
