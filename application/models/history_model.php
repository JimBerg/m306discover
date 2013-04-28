<?php
/**
 * class History_Model
 * extends Jay_Model
 *
 * load database table 'history'
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */

class History_Model extends Jay_Model
{
    /**
     * set db properties for history table
     * set default order to visitdate
     */
    protected $_table_name = 'history';
    protected $_order_by = 'visitdate';

    public function __construct()
    {
        parent::__construct();
    }
}