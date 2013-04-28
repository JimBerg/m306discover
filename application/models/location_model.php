<?php
/**
 * class Location_Model
 * extends Jay_Model
 *
 * load database table 'location'
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */

class Location_Model extends Jay_Model
{
    /**
     * set db properties for history table
     * set default order to visitdate
     */
    protected $_table_name = 'location';
    protected $_order_by = 'id';

    public function __construct()
    {
        parent::__construct();
    }
}