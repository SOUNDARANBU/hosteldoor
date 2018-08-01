<?php
require_once("../config.php");
global $DB, $USER;
//do authentication
$USER->require_signin();
$action = \manager\page::optional_param('action');
if (isset($action)) {
    $result = new stdClass();
    switch ($action) {
        case 'get_user':
            $result->data = \manager\user::get_users();
            break;
        case 'create_user':
            $user = new stdClass();
            $user->id = \manager\page::optional_param('id');
            $user->username = \manager\page::optional_param('username');
            $user->firstname = \manager\page::optional_param('firstname');
            $user->lastname = \manager\page::optional_param('lastname');
            $user->email = \manager\page::optional_param('email');
            $user->mobile = \manager\page::optional_param('mobile');
            $user->password = password_hash(\manager\page::optional_param('password'), PASSWORD_DEFAULT);

            $submit = \manager\page::optional_param('register-submit');
            if (empty($user->id)) {
                if (\manager\user::create_user($user)) {
                    $result->data = "User created successfully";
                }
            } else {
                if (\manager\user::update_user($user)) {
                    $result->data = "User updated successfully";
                }
            }
            break;
        default:
            break;
    }
    $response = json_encode($result, JSON_PRETTY_PRINT);
    echo $response;
} else {
    echo "action must be specified";
}