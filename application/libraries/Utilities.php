<?php
class Utilities
{
    /**
     * calculate radius in specific distance to a given point
     * @param array $point
     * @param $r radius
     * @return array lat & long attributes
     */
    public static function calcRadius( $point = array( 0, 0 ), $r )
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
}
