<?php
/**
 * class Page
 * extends Jay_Controller
 *
 * renders static start pages of application
 *
 * @author Janina Imberg
 * @version 1.0
 * @date
 *
 */


class Page extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * default action index
     * renders static start page of application
     * @return void
     */
    public function index()
    {
        $this->load->view( 'page/_layout_start' );
    }
}

