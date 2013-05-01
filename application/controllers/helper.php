<?php
/**
 * class Helper
 * extends Jay_Controller
 *
 * Helper functions
 *
 * @author Janina Imberg
 * @version 1.0
 * @date 25.04.2013
 *
 */

class Helper extends Jay_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model( 'location_model' );
    }

    public function index()
    {
        //$this->updateLocationCircuits();
    }

    /**
     * calculate radius in specific distance $r to a given point
     *
     * @param array $point
     * @param $r radius
     * @return array lat & long attributes
     */
    private static function calcRadius( $point = array( 0, 0 ), $r )
    {
        $lat1 = $point[0];
        $lon1 = $point[1];

        $d = 0.00015; //Umkreis
        $r = 6.371; //Erdradius in km

        $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
        $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
        $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
        $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));

        return array( 'lat_north' => $latN, 'lat_south' => $latS, 'lng_east' => $lonE, 'lng_west' => $lonW );
    }

    /**
     * for internal use
     * calculate circuit for given locations and save them to database
     */
    private function updateLocationCircuits()
    {
        $location = $this->location_model->get();
        foreach( $location as $key => $value ) {
            $data = self::calcRadius( array( $value->lat, $value->lng ), 50 );
            $this->location_model->save( $data, $id = $value->id );
        }
    }

    /**
     * calculate points dependent on number of attempts
     *
     * @param $counter
     * @return int
     */
    public static function calculatePoints( $counter = 0 )
    {
        if ( $counter == 1 ) {
            return 30;
        } else if ( $counter == 2 ) {
            return 20;
        } else if ( $counter == 3 ) {
            return 5;
        } else {
            return 50;
        }
    }

    /**
     * set player rank dependent on points
     * max points = 50_points_each * 10_task
     * min points = 0
     *
     * @param int $points
     * @return String $rank
     */
    public static function getRank( $points )
    {
        // TODO: some reasonable ranks and point distribution
        if( $points >= 0 && $points < 30 ) {
            $rank = "Ahnungsloser Neuling";
        } else if( $points >= 30 && $points < 60 ) {
            $rank = "Nicht mehr ganz so grün";
        } else if( $points >= 60 && $points < 120 ) {
            $rank = "Making Progress";
        } else if( $points >= 120 && $points < 250 ) {
            $rank = "Erfahren";
        } else if( $points >= 250 && $points < 450 ) {
            $rank = "Junior Discoverer";
        } else if( $points >= 450 && $points < 500 ) {
            $rank = "Fast Perfekt";
        } else if( $points == 500 ) {
            $rank = "Major";
        } else {
            $rank = "Cheater";
        }
        return $rank;
    }

    /**
     * Set navigation css class for active page element
     *
     * @param $segment - current page
     * @param $name - navigation elem
     * @return string
     */
    public static function activeNavigation( $segment, $name )
    {
        if ( $segment == $name ) {
            $cssClass = 'active';
        } else {
            $cssClass = '';
        }
        return $cssClass;
    }

    /**
     * Set css class dependent on solved or unresolved
     *
     * @param $solved
     * @return string
     */
    public static function historyCssClass( $solved )
    {
        $cssClass = '';
        if( $solved == 1 ) {
            $cssClass = 'solved';
        } else if( $solved == 0 ) {
            $cssClass = 'open';
        }
        return $cssClass;
    }

    /**
     * Set text for given quest
     *
     * @param $location_id
     * @return string
     */
    public static function formatQuest( $location_id )
    {
        return "Aufgabe ".$location_id;
    }

    /**
     * Set text for solved or unresolved
     *
     * @param $solved
     * @return string
     */
    public static function formatSolved( $solved )
    {
        $text = '';
        if( $solved == 1 ) {
            $text = 'Gelöst';
        } else if( $solved == 0 ) {
            $text = 'offen';
        }
        return $text;
    }

    /**
     * format timestamp to appropriate date format
     *
     * @param $visitdate
     * @return string
     */
    public static function formatDate( $visitdate )
    {
        return date("d/m/Y", strtotime('2009-12-09 13:32:15'));
    }

}