<?php
/**
 * class Page
 * extends Jay_Controller
 *
 * handles actions for user- and game-management

 * @author Janina Imberg
 * @version 1.0
 * @date 23.04.2013
 *
 */

class User extends Jay_Controller {

    /**
     * @var stdClass
     */
    protected $user;

    /**
     * constructor
     * load relevant models
     */
    public function __construct()
    {
        parent::__construct();
        $this->user = parent::getCurrentUser();
    }

    /**
     * default action index
     * renders static start page of application
     * based on main_layout
     *
     * @param String $view, default to index, can be set to map
     *
     * @return void
     */
    public function index( $view = 'index' )
    {
        if( empty( $this->user ) ) {
            $this->user = parent::getCurrentUser();
        }
        $this->data = array(
            'subview' => 'user/content/'.$view,
            'user' => $this->user
        );
        $this->load->view( 'user/layout/_layout_main', $this->data );
    }


    /**
     * handles login & validation
     * renders login form
     * if user & password are valid redirect to start
     * else back to login form
     * if session already exists redirect to start
     *
     * @return void
     */
    public function login()
    {
        if( $this->user_model->loggedin() != false ) {
            redirect( 'user/index' ); //if session redirect
        }

        if( isset( $_POST  ) && !empty( $_POST ) ) {
            $rules = $this->user_model->rules; //get rules from model
            $this->form_validation->set_rules( $rules ); //validate form data according to rules
            if( $this->form_validation->run() == true ) { //no validation errors
                if( $this->user_model->login() == true ) { //user & password match, user exists
                    redirect( 'user/index' );
                } else {
                    $this->session->set_flashdata( 'error', 'Seltsame Kombination, mein Freund. So geht das nicht!' );
                    redirect( 'user/login', 'refresh' );
                }
            }
        }
        $this->data['subview'] = 'user/content/login';
        $this->load->view( 'user/layout/_layout_modal', $this->data );
    }

    /**
     * handles logout
     * destroy session, redirect to login page
     *
     * @return void
     */
    public function logout()
    {
        $this->user_model->logout();
        redirect( 'page/index' );
    }

    /**
     * render register form
     * validation check
     * create new user if valid - save records to database
     * start session & login
     * redirect to index if successful
     *
     * @return void
     */
    public function register()
    {
        $this->data[ 'subview' ] = 'user/content/register';

        if ( !empty( $_POST ) ) { //if something was submitted
            $username = $_POST[ 'username' ];

            //get validation rules from model
            $rules = $this->user_model->register_rules;
            $this->form_validation->set_rules( $rules );

            //TODO SET MESSAGES

            if( $this->form_validation->run() == true ) { //check if valid
                if ( $this->user_model->getBy( array( 'username' => $username ) ) ) { //check if username exists
                    $this->data[ 'error' ] = "Dieser Username existiert bereits. Versuch doch mal was einfallsreicheres.";
                    $this->load->view( 'user/layout/_layout_modal', $this->data );
                    return;
                }  else {
                    $data = array(
                        'id' => '',
                        'username' => $username,
                        'password' => $_POST[ 'password_register' ],
                    );
                    $this->user_model->save( $data );

                    //set default data for related tables
                    $last_inserted_user_id = $this->db->insert_id();
                    $profile = array(
                        'user_id' => $last_inserted_user_id,
                        'firstname' => '',
                        'lastname' => '',
                        'avatar' => '',
                        'points' => 0,
                        'rank' => 'Ahnungsloser Neuling',
                        'gameover' => 0
                    );
                    $this->profile_model->save( $profile );

                    //set first history object for user
                    $data = array(
                        'location_id' => 1,
                        'user_id' => $last_inserted_user_id,
                        'points' => 50,
                        'counter' => 0,
                        'solved' => 0
                    );
                    $this->history_model->save( $data );

                    $this->user_model->login(); //set session
                    redirect( 'user/index' );
                }
            } else {
                $this->data = array(
                    'username' => $username,
                    'subview' => 'user/content/register'
                );
            }
        }
        $this->load->view( 'user/layout/_layout_modal', $this->data );
    }

    /**
     * render view for profile
     * pass current user & profile data to view
     *
     * @return void
     */
    public function profile()
    {
        $this->data[ 'subview' ] = 'user/content/profile';
        $this->data[ 'user' ] = $this->user;
        $this->data[ 'profile' ] = $this->getUserProfile();
        $this->load->view( 'user/layout/_layout_main', $this->data );
    }

    /**
     * render view for history
     * pass current user & history data to view
     *
     * @return void
     */
    public function history()
    {
        $this->data[ 'subview' ] = 'user/content/history';
        $this->data[ 'user' ] = $this->user;
        $this->data[ 'history' ] = $this->getUserHistory();
        $this->load->view( 'user/layout/_layout_main', $this->data );
    }

    /**
     * render mapview
     *
     * @return void
     */
    public function map()
    {
        $this->data = array(
            'subview' => 'user/content/map',
            'user' => $this->user
        );
        $this->load->view( 'user/layout/_layout_main', $this->data );
    }

    /**
     * load profile of current user from database
     * identified by user_id
     *
     * @return stdClass $profile
     */
    protected function getUserProfile()
    {
        $profile = $this->profile_model->getBy( array( 'user_id' =>  $this->user->id ), true );
        return $profile;
    }

    /**
     * load profile of current user from database
     * identified by user_id
     *
     * @return stdClass $history ||
     */
    protected function getUserHistory()
    {
        $history = $this->history_model->getBy( array( 'user_id' =>  $this->user->id ) );
        if( empty ( $history ) ) {
            $history = new stdClass();
            $history->noQuests = true;
        }
        return $history;
    }

    /**
     * get current task from database
     * get one record (there will be maximum 1 with solved = 0)
     *
     * @return stdClass $history
     */
    protected function getUserLatestQuest()
    {
        $history = $this->history_model->getBy( array( 'user_id' =>  $this->user->id, 'solved' =>  0 ), true );
        if( empty ( $history ) ) {
            $history = new stdClass();
            $history->noQuests = true;
        }
        return $history;
    }

    /**
     * get location object - identified by location id
     *
     * @param $locationId
     * @return stdClass $location
     */
    protected function getLocation( $locationId )
    {
        $location = $this->location_model->getBy( array( 'id' =>  $locationId ), true );
        if( empty ( $location ) ) {
            $location = new stdClass();
            $location->message = 'Strange error';
            $location->noLocation = true;
        }
        return $location;
    }

    /**
     * TODO: SPLIT FUNCTIONALITY TO SEPARATE METHODS
     * get latest task and set solved to true
     * create new task from quests table for current user
     * on failure update points and counters
     * in case of more than 3 attempts set game over
     */
    protected function updateHistory( $type )
    {
        $model = $this->getUserLatestQuest();
        if( $type == 'success' ) {
            //set solved
            $data = array( 'solved' => 1 );
            $this->history_model->save( $data, $id = $model->id );

            //get next quest
            $locationId = $model->location_id;
            $data = array(
                'location_id' => $locationId + 1,
                'user_id' => $this->user->id,
                'points' => 50,
                'counter' => 0,
                'solved' => 0
            );
            $this->history_model->save( $data );

            //update user
            $profile = $this->profile_model->getBy( array( 'user_id' => $this->user->id ) );
            $points = $profile->points + $model->points;
            $data = array(
                'points' => $points,
                'rank' => Helper::getRank( $points )
            );
        } else if( $type == 'failure' ) {
            if( $model->counter < 3 ) {
                //recalculate points and counter
                $counter = $model->counter + 1;
                $points = Helper::calculatePoints( $counter );
                $data = array(
                     'counter' => $counter,
                     'points' => $points
                );
                $this->history_model->save( $data, $id = $model->id );
            } else {
                $this->setGameOver();
            }
        }
    }

    /**
     * update user/profile
     * save changed records to user/profile table
     *
     * @return String
     */
    public function updateProfile()
    {
        if( isset( $_POST ) && !empty( $_POST ) ) {
            $user_id = $_POST[ 'user_id' ];
            $data = array( 'password' => $_POST[ 'password' ] );
            $this->user_model->save( $data, $id = $user_id );
            $data = array(
                'firstname' => $_POST[ 'firstname' ],
                'lastname' => $_POST[ 'lastname' ]
            );
            $this->profile_model->save( $data, $id = $user_id );
            $message = "Speichern erfolgreich";
        } else {
            redirect( 'user/profile' );
        }
        $this->data[ 'message' ] = $message;
        $this->data[ 'subview' ] = 'user/content/profile';
        $this->data[ 'user' ] = $this->user; // TODO: pretty dirty thing over here... update user required!!
        $this->data[ 'profile' ] = $this->getUserProfile();
        $this->load->view( 'user/layout/_layout_main', $this->data );
    }

    protected function deleteProfile() {
        //TODO
    }

    /**
     * set game over for current user
     *
     * @return void
     */
    protected function setGameOver()
    {
        $data = array( 'gameover' => 1 );
        $this->profile_model->save( $data, $user_id = $this->user->id );
    }

}





