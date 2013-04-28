<?php
/**
 * class Quests_Model
 * extends Jay_Model
 *
 * load database table 'quests'
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 24.04.2013
 *
 */

class Quests_Model extends Jay_Model
{
    /**
     * set db properties for history table
     * set default order to location_id
     */
    protected $_table_name = 'quests';
    protected $_order_by = 'location_id';

    public function __construct()
    {
        parent::__construct();
    }
}