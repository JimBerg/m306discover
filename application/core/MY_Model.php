<?php 
class MY_Model extends CI_Model {

	/** using active record method http://ellislab.com/codeigniter/user-guide/database/active_record.html */

    protected $_table_name = '';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval'; //convert to integer
	protected $_order_by = '';
	protected $_rules = array();
	protected $_timestamps = false;
	
	public function __construct() 
	{
		parent::__construct();
	}


    /**
     * @param null $id
     * @return
     */
    public function get($id = null, $single = false)
 	{
 		if ( $id != null ) {
 			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->where($this->_primary_key, $id);
			$method = 'row'; // single record
        } else if ($single == true) {
                 $method = 'row';
 		} else {
 			$method = 'result'; // all records
 		}

        /** if no order is passed -> use default order */
        if (!count($this->db->ar_orderby)) {
            $this->db->order_by($this->_order_by);
        }
        return $this->db->get($this->_table_name)->$method();
 	}


    /**
     * @param $where
     * @param bool $single
     * @return
     */
    public function getBy($where, $single = false)
    {
		$this->db->where($where);
		return $this->get(null, $single);
	}


    /**
     * same function for update and insert
     * -> without id ==> create == new // else update
     * @param $data
     * @param null $id
     * @return mixed
     */
    public function save($data, $id = null){

        // Set timestamps
        if ($this->_timestamps == true) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now;
            $data['modified'] = $now;
        }

        // Insert
        if ($id === null) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = null;
            $this->db->set($data);
            $this->db->insert($this->_table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($this->_table_name);
        }

        return $id;
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $filter = $this->_primary_filter;
        $id = $filter($id);

        if (!$id) {
            return FALSE;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($this->_table_name);
    }



}
