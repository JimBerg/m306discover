<?php
/**
 * class Profile_Model
 * extends Jay_Model
 *
 * load database table 'profile'
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */

class Profile_Model extends Jay_Model
{
    /**
     * set db properties for profile table
     * set default order
     */
    protected $_table_name = 'profile';
    protected $_order_by = 'user_id';
    protected $_primary_key = 'user_id';

    public function __construct()
    {
        parent::__construct();
    }
}