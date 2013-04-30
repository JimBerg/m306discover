<?php

/**
 * class Jay_Model
 * extends CI_Model
 * Base Class for CRUD Operations
 *
 * using CI Active Record
 * @link http://ellislab.com/codeigniter/user-guide/database/active_record.html
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */
class Jay_Model extends CI_Model
{

    /**
     * database table
     * @var String $_table_name
     */
    protected $_table_name = '';

    /**
     * tables primary key - default = id
     * @var String $_primary_key
     */
    protected $_primary_key = 'id';

    /**
     * @var string
     */
    protected $_primary_filter = 'intval'; //convert to integer

    /**
     * database table - order
     * @var String $_order_by
     */
    protected $_order_by = '';

    /**
     * @var array
     */
    protected $_rules = array();

    /**
     * @var bool
     */
    protected $_timestamps = false;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get records from database
     * return all results if single false or $id = null
     * return specified records if $id is set or $single == true
     * uses default order or passed options for order
     *
     * @param int $id, default = null
     * @param boolean $single, default = false
     * @return stdClass
     */
    public function get( $id = null, $single = false )
    {
        if ( $id != null ) {
            $filter = $this->_primary_filter;
            $id = $filter( $id );
            $this->db->where( $this->_primary_key, $id );
            $method = 'row'; // single record
        } else if ( $single == true ) {
            $method = 'row';
        } else {
            $method = 'result'; // all records
        }

        /** if no order is passed -> use default order */
        if ( !count( $this->db->ar_orderby ) ) {
            $this->db->order_by( $this->_order_by );
        }
        return $this->db->get( $this->_table_name )->$method();
    }

    /**
     * returns selected rows specified by where statement
     *
     * @param $where
     * @param bool $single
     * @return
     */
    public function getBy( $where, $single = false )
    {
        $this->db->where( $where );
        return $this->get( null, $single );
    }

    /**
     * insert and update records to database
     * - without id == create
     * - with id == update
     *
     * @param $data array
     * @param int $id = default = null
     * @return mixed
     */
    public function save( $data, $id = null ){

        // set timestamps
        if ( $this->_timestamps == true ) {
            $now = date( 'Y-m-d H:i:s' );
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }

        // insert - if no id exists
        if ($id == null) {
            //!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = null;
            $this->db->set( $data ); // $data has to be passed as array
            $this->db->insert( $this->_table_name );
            $id = $this->db->insert_id();
        }
        // update
        else {
            $filter = $this->_primary_filter;
            $id = $filter( $id );
            $this->db->set( $data );
            $this->db->where( $this->_primary_key, $id );
            $this->db->update( $this->_table_name );
        }
        return $id;
    }

    /**
     * delete records with specified id
     * @param $id
     * @return bool
     */
    public function delete( $id )
    {
        $filter = $this->_primary_filter;
        $id = $filter( $id );

        if ( !$id ) {
            return false;
        }
        $this->db->where( $this->_primary_key, $id );
        $this->db->limit( 1 );
        $this->db->delete( $this->_table_name );
    }
}
