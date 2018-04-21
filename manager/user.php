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
}