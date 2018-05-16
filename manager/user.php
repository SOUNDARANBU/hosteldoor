<?php

namespace manager;

class user{
    public $id;
    public $name;
    public $email;
    public $username;
    public $dob;
    public $mobile;
    public $is_authenticated = false;
    public $is_loggedin = false;
    public $last_login;
    public $is_active;
    public $is_deleted;

    public function signin(){

    }

    public function signout(){

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
                   $status->message = "User Created Successfully";
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
                //check if password matche
                if(!password_verify($_POST['password'], $user->password)){
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
}