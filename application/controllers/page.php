<?php
class Page extends Frontend_Controller
{
    /**
     * initialize page model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model'); // entsprechendes model laden
    }

    /**
     * loads start page of application
     * => default action
     * => visible for guests & registered users
     */
    public function index()
    {
        $this->load->view('frontend/_layout_start');
        /* $pages = $this->page_model->get();
        $pages = $this->page_model->getBy(array('slug' => 'beitrag'));
        var_dump($pages);*/
    }


}