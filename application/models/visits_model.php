<?php
class Visits_Model extends MY_Model
{
    /**
     * set db properties of user table
     */
    protected $_table_name = 'visits';
    protected $_order_by = 'visitdate DESC';

    public function __construct()
    {
        parent::__construct();
    }

}