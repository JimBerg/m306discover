<?php

/** -------------------------------------------------------------------------
 * class for usermanagment
 *
 * that includes
 * - registration
 * - login & logout
 * - usersession managent
 * - render views
 * --------------------------------------------------------------------------  */

class Usermanagement extends User_Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->load->model( 'visits_model' );
        $this->load->model( 'location_model' );
        $this->load->model( 'quests_model' );
    }


    /**
     * default controller
     * renders start screen // main screen after login
     */
    public function index()
    {
        $this->user = parent::getCurrentUser();
        $this->data['user'] = $this->user;

        /* TODO: if not empty...  */
        //$this->data['visits'] = $this->getUserLatestTask();
        //$this->data['task'] = $this->getUserNextTask();
        $this->data['subview'] = 'user/usermanagement/index';
        $this->load->view( 'user/_layout_main', $this->data );
    }

    /**
     * render modal layout for dialogs
     */
    public function modal()
    {
        $this->load->view('user/_layout_modal',$this->data);
    }

    /**
     * renders template for user history
     */
    public function history()
    {
        $this->data['user'] = parent::getCurrentUser();
        $this->data['history'] = $this->getUserHistory();
        $this->data['subview'] = 'user/usermanagement/history';
        $this->load->view( 'user/_layout_main', $this->data );
    }

    /**
     * handles login
     * renders login form
     * set validation rules
     */
    public function login()
    {
        $this->user_model->loggedin() == false || redirect( 'user/usermanagement' ); //if session redirect

        $rules = $this->user_model->rules;
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run() == true) { //no validation errors
            if($this->user_model->login() == true) { //user & password match, user exists
                redirect('user/usermanagement');
            } else {
                $this->session->set_flashdata('error', 'Seltsame Kombination, mein Freund. So geht das nicht!');
                redirect('user/usermanagement/login', 'refresh');
            }
        }
        $this->data['subview'] = 'user/usermanagement/login';
        $this->load->view('user/_layout_modal', $this->data);
    }


    /**
     * handles logout
     * destroy session, redirect to login page
     */
    public function logout()
    {
        $this->user_model->logout();
        redirect('user/usermanagement/login');
    }


    /**
     * render register form
     * validation check
     * create new user - save records to database
     * start session & login
     */
    public function register()
    {
        $this->data[ 'subview' ] = 'user/usermanagement/register';

        if ( !empty( $_POST ) ) { //if something was submitted

            $email = $_POST[ 'email' ];
            $username = $_POST[ 'username' ];

            //get validation rules from model
            $rules = $this->user_model->register_rules;
            $this->form_validation->set_rules( $rules );

            if( $this->form_validation->run() == true ) { //check if valid
                if ( $this->user_model->getBy( array( 'username' => $username ) ) ) { //check if username already exists
                    $this->data[ 'error' ] = "Dieser Username existiert bereits. Versuch doch mal was einfallsreicheres.";
                    $this->load->view( 'user/_layout_modal', $this->data );
                    return;
                } else if( $this->user_model->getBy( array( 'email' => $email ) ) ) { //check if email already exists
                    $this->data[ 'error' ] = "Diese Emailadresse ist bereits vorhanden. Seltsam, was?";
                    $this->load->view( 'user/_layout_modal', $this->data );
                    return;
                } else {
                    $this->load->model( 'location_model' ); //load location model
                    $location = array(
                        'name' => 'My Homebase', //TODO: rename it or reverse geocoding? for real names?
                        'lat' => $_POST[ 'position-lat' ],
                        'lng' => $_POST[ 'position-lng' ]
                    );
                    $this->location_model->save( $location );
                    $last_inserted_id = $this->db->insert_id();

                    $data = array(
                        'username' => $username,
                        'email' => $email,
                        'password' => $_POST[ 'password_register' ],
                        'location_id' => $last_inserted_id
                    );
                    $this->user_model->save( $data, $id = null );
                    $this->user_model->login(); //set session
                    redirect( 'user/usermanagement' );
                }
            } else {
                $this->data = array(
                    'username' => $username,
                    'email' => $email,
                    'subview' => 'user/usermanagement/register'
                );
            }
        }
        $this->load->view( 'user/_layout_modal', $this->data );
    }


    /**
     * edit user data
     */
    public function edit()
    {
        $this->data[ 'user' ] = parent::getCurrentUser();
        $this->data[ 'subview' ] = 'user/usermanagement/profile';
        $this->load->view( 'user/_layout_main', $this->data );
    }

    /**
     * get all visited location
     * @return mixed
     */
    protected function getUserHistory()
    {
        $this->user = parent::getCurrentUser();
        $visits = $this->visits_model->getBy( array( 'user_id' =>  $this->user->id ) );
        if( empty( $visits ) ) {
            $visits = new stdClass();
            $visits->visits = false;
            $visits->text = "Noch keine Orte besucht.";
        }
        return $visits;
    }

    /**
     * get latest visit
     * @return mixed
     */
    protected function getUserLatestTask()
    {
        $this->user = parent::getCurrentUser();
        $visits = $this->visits_model->getBy( array( 'user_id' => $this->user->id ), true );
        return $visits;
    }


    /**
     * get description of next visit
     * @return mixed
     */
    public function getUserNextTask()
    {
        $currentVisitId = $this->getUserLatestTask(); //TODO das ist etwas umständlich...
        $nextVisitId = (int)$currentVisitId->id + 1; //TODO - check if exists!!
        $nextTask = $this->quests_model->getBy( array( 'location_id' => $nextVisitId ), true );
        $location = $this->location_model->getBy( array( 'id' => $nextVisitId ), true );

        $task = new stdClass();
        $task->quest = $nextTask->quest;
        $task->name = $location->name; // hahahahah stupid to pass it ;)
        $task->lat = $location->lat;
        $task->lng = $location->lng;
        return $task;
    }

    /**
     * set location on map => return json obj.
     * @return json obj
     */
    public function getNextCheckPoint()
    {
        $checkPoint = $this->getUserNextTask();
        echo json_encode( $checkPoint );
    }

    /**
     * display all pois... <- we won't need it in this version
     * but i need it now to prevent from errors
     * @param $type which type should be selected
     * @return json obj location data
     */
    public function getPOIs( $type = null )
    {
        $pois = $this->location_model->getPOIs( $type );
        echo json_encode( $pois );
    }

}