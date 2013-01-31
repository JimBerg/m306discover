<?php
class Page extends Frontend_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model'); // entsprechendes model laden
    }

    /**
     * default action
     */
    public function index()
    {
        // $pages = $this->page_model->get();
        $pages = $this->page_model->getBy(array('slug' => 'beitrag'));
        var_dump($pages);
    }

    /**
     *
     */
    public function save()
    {
        $data = array(
            'title' => "test",
            'slug' => "neue-seite",
            'body' => 'testen testentestentestentestentesten testen testen.'
        );

        $this->page_model->save($data);
    }
}