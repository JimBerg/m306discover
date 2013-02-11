<?php
class Visits extends User_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model( 'visits_model' );
    }

    /**
     * @param $userId
     */
    public function getUserLocation( $userId )
    {
        die(var_dump($userId));
       $visits = $this->visits_model->getBy( array( 'user_id' => $userId ) );
        die(var_dump($visits));
    }
}