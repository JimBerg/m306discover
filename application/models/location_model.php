<?php
class Location_Model extends MY_Model
{
    /**
     * set db properties of user table
     */
    protected $_table_name = 'location';
    protected $_order_by = 'id';

    /*------------------------------------------------------------*

    DUMMY - FROM OLD VERSION

	* get all special markers => pois
	* @param (int) $type, what kind of marker should be selected
	* 1 - Sehenswürdigkeiten
	* 2 - Parks, Gärten
	* 3 - Museen und Ausstellungen
	*------------------------------------------------------------*/
    public function getPOIs( $type = null )
    {
        if( $type )	{
            $query = $this
                ->db
                ->where( 'type', $type )
                ->where( 'poi', 1 )
                ->get( 'location' );

            if ( $query->num_rows > 0 ) {
                return $query->result();
            }
        } else {
            $query = $this
                ->db
                ->where( 'poi', 1 )
                ->get( 'location' );

            if ( $query->num_rows > 0 ) {
                return $query->result();
            }
        }
        return false;
    }

}