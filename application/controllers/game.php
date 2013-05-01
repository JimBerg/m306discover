<?php
/**
 * class Game
 * extends User
 *
 * game-management
 * handles ajax requests
 * get and set points and tasks
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 25.04.2013
 *
 */

class Game extends User {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * default controller
     */
    public function index()
    {
        $this->data['user'] = parent::getCurrentUser();
        $this->data['controller'] = 'game';
        $this->data['subview'] = 'user/content/index';
        $this->load->view( 'user/layout/_layout_main', $this->data );
    }

    /**
     * load profile of current user from database
     * identified by user_id
     *
     * @return stdClass / json $history
     */
    public function history()
    {
        $history = parent::getUserHistory();
        echo json_encode( $history );
    }

    /**
     * get next user quest from database
     * call history table and search for the last unresolved task (solved = 0)
     * if there's no record found, no more quests are available => set gameover / success
     *
     * @return stdClass / json  $quest
     */
    public function getCurrentQuest()
    {
        $history = parent::getUserLatestQuest();
        echo json_encode( $history );
    }

    /**
     * load location objects for each visited location
     *
     * @return stdClass / json $visited locations
     */
    public function getVisitedLocations()
    {
        $markerCollection = array();
        $history = $this->history_model->getBy( array( 'user_id' =>  $this->user->id, 'solved' => 1 ) );
        foreach( $history as $item ) {
            $location = $this->location_model->getBy( array( 'id' =>  $item->location_id ) );
            array_push( $markerCollection, $location );
        }
        echo json_encode( $markerCollection );
    }


    /**
     * handles ajax request
     * get lat and lng values of current pos
     * compare with current task
     * handle success if position lies within circuit
     * handle failure otherwise
     *
     * @return stdClass / json $message
     */
    public function setCheckIn()
    {
       if( $this->input->is_ajax_request() ) {
           $lat = $this->input->get( 'lat' );
           $lng = $this->input->get( 'lng' );

           $history = parent::getUserLatestQuest();
           $locationId = $history->location_id;
           $location = parent::getLocation( $locationId );

           if( $lat >= $location->lat_south && $lat <= $location->lat_north &&
               $lng >= $location->lng_west && $lng <= $location->lng_east ) {
               //success
               parent::updateHistory( 'success' );

               // return success message & new task
               $message = new stdClass();
               $message->head = "Gratulation.";
               $message->text = "Du hast den Spot gefunden.";
           } else {
               //fail
               parent::updateHistory( 'failure' );

               // return failure message
               $message = new stdClass();
               $message->head = "Schade.";
               $message->text = "Das war nicht der gesuchte Ort.";
           }
       } else {
           parent::setGameOver();
           $message = new stdClass();
           $message->head = "Scherzkeks!";
           $message->text = "Das heisst für dich Game-Over.";
       }
       echo json_encode( $message );
    }

    public function deleteProfile()
    {
        //
    }
}