<?php
class MY_Controller extends CI_Controller 
{
	public $data = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->data['sitename'] = config_item('sitename');
	}
}
