<?php
class Quests_Model extends MY_Model
{
    protected $_table_name = 'quests';
    protected $_order_by = 'location_id';


    /**
     *
     */
    public function getNextQuest( $locationId = null )
    {
        if( $locationId != null ) {
            $nextQuest = $this->getBy( array( 'location_id' => $locationId ) );
        } else {
            $nextQuest = new stdClass();
            $nextQuest->solved = true;
            $nextQuest->quest = "no quests available";
        }
        return $nextQuest;
    }
}