<?php

namespace manager;

class user{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $username;
    public $dob;
    public $mobile;
    public $last_login;
    public $active;
    public $deleted;

    public function __construct()
    {
        if(isset($_SESSION['USER'])){
            $user = $_SESSION['USER'];
            $this->id = $user->id;
            $this->firstname = $user->firstname;
            $this->lastname = $user->lastname;
            $this->email = $user->email;
            $this->username = $user->email;
            $this->dob = $user->dob;
            $this->mobile = $user->mobile;
            $this->last_login = $user->last_login;
            $this->active = $user->active;
            $this->deleted = $user->deleted;
        }
    }

    /**
     * Get user by id
     * @param string|int $user_id
     * @return bool|object
     */
    public static function get_user_by_id($user_id)
    {
        global $DB;
        $user = $DB->get_record('user', ['id' => $user_id]);
        return $user;
    }

    /**
     * Get user by username
     * @param string $user_name
     * @return bool|object
     */
    public static function get_user_by_username($user_name)
    {
        global $DB;
        $user = $DB->get_record('user', ['username' => $user_name]);
        return $user;
    }

    /**
     * Get user by mobile number
     * @param $user_mobile_number
     * @return bool|object
     */
    public static function get_user_by_mobile_number($user_mobile_number)
    {
        global $DB;
        $user = $DB->get_record('user', ['mobile' => $user_mobile_number]);
        return $user;
    }

    /**
     * Get user by email
     * @param $user_email
     * @return bool|object
     */
    public static function get_user_by_email($user_email)
    {
        global $DB;
        $user = $DB->get_record('user', ['email' => $user_email]);
        return $user;
    }

    public function get_profilepic(){

    }

    public function is_remembered(){

    }

    public function get_role(){

    }

    public function get_capabilities(){

    }

    public function process_signup(){
        global $DB;
        $status = new \stdClass();
        $status->message = "";
        $status->status = true;
        $status->code = 0;
        $break = false;
        //check if any of the fields are empty
        if( !isset($_POST['username']) && !isset($_POST['email']) && !isset($_POST['password']) && !isset($_POST['confirm-password'])){
            $status->message = "Please enter all the fields";
            $break = true;
        }
        //check if the username already exists
        if(!$break && $DB->get_record('hdr_user',[ 'username' => $_POST['username']])){
            $status->message = "Username already exists";
            $status->staus = false;
        }
        //check if the email already exists
        if(!$break && $DB->get_record('hdr_user',[ 'email' => $_POST['email']])){
            $status->message = "Email already exists";
            $status->status = false;
        }
        //check if the password matches minimum strength
        if(!$break && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $status->message = "Email is not valid";
            $status->status = false;
        }

        if($status->status){
            $user = new \stdClass();
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if($this->create_user($user)){
                    $this->send_signup_email();
                   $status->message = "Please check your email to verify your identity.";
                   $status->status = true;
            }else{
                $status->message = "User Sign Up failed";
            }
        }
        return $status;
    }

    public function create_user($user_data){
        global $DB;
        //create user object
        $user = new \stdClass();
        $user->username = $user_data->username;
        $user->password = $user_data->password;
        $user->email = $user_data->email;
        $user->mobile = '';
        $user->timecreated = time();
        $user->active = 0;
        $userid = $DB->insert_record('hdr_user', $user);
        if($userid > 0){
            return $userid;
        } else{
            return false;
        }
    }

    public function process_signin(){
        global $DB;
        $status = new \stdClass();
        $status->message = "";
        $status->status = true;
        $break = false;
        //check if all the fields all entered
        if(!isset($_POST['username']) && !isset($_POST['password'])){
            $status->message = "Please enter all the fields";
            $status->status = false;
            $break = true;
        }
        if(!$break){
            $user = $DB->get_record('hdr_user',['username' => $_POST['username']]);
            //check if the user is found in db
            if($user){
                //check if password matches
                if(password_verify($_POST['password'], $user->password)){
                   $this->id = $user->id;
                    $this->username = $user->username;
                    $this->email = $user->email;
                    $this->firstname = $user->firstname;
                    $this->lastname = $user->lastname;
                    $this->active = $user->active;
                    $this->deleted =$user->deleted;
                    $_SESSION['USER'] = $this;
                }else{
                    $status->message = "Please enter correct password";
                    $status->status = false;
                }
            }else{
                $status->message = "Please enter valid username";
                $status->status = false;
            }
        }
        return $status;
    }

    public function process_signout(){
        if(isset($_SESSION['USER'])){
            $_SESSION['USER'] = null;
            global $USER;
            $USER = new user();
            return true;
        } else{
            return false;
        }
    }

    public function is_signedin(){
        if(issest($_SESSION('USER'))){
            return true;
        }else{
            return false;
        }
    }

    public function require_signin(){
        global $USER, $PAGE, $C;
        if(isset($USER->id) && $USER->id > 0){
            return;
        }else{
            $PAGE->redirect($C->wwwroot. '/account/signin.php');
        }
    }

    public function send_signup_email($userid){
        $link = $this->create_signup_link($userid);
        email::send_email((object)[
            'fromemail' => 'admin@madnuos.tk',
            'fromusername' => 'Admin',
            'toemail' => 'soundar.a@mqspectrum.com',
            'tousername' => 'Soundar',
            'subject' => 'Confirm User Registration',
            'body'      => 'Please click this link to verify the email'
        ]);
    }

    public function create_signup_link($userid){
        global $C;
        $token = time();
        sodium_crypto_box();
        return "$C->wwwroot/app/user/verify.php?token=";
    }

    public function create_password_reset_link(){

    }

    public function send_password_reset_email(){

    }
}