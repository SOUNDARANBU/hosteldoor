<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/10/2018
 * Time: 3:24 PM
 */

namespace manager;


class util
{
    public static function create_password_hash($password){
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function verify_password($password, $hash){
        return password_verify($password, $hash);
    }

    public static function is_valid_email($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}