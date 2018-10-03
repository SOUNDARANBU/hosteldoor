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
            $password = \manager\page::optional_param('password');
            $user->password = isset($password) ? password_hash($password, PASSWORD_DEFAULT) : '';

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
        case 'get_user_permissions':
            $user_id = \manager\page::optional_param('userid');
            $result->data = \manager\permisssion::get_user_permissions($user_id);
            break;
        case 'get_permissions':
            $role_id = \manager\page::optional_param('role_id');
            $type = \manager\page::optional_param('type');
            $result->data = \manager\permisssion::get_role_permissions($role_id, $type);
            break;
        case 'get_all_permissions':
            $result->data = \manager\permisssion::get_all_permissions();
            break;
        case 'unassign_permissions':
            $permission_ids = explode(',', \manager\page::optional_param('permission_ids'));
            $role_id = \manager\page::optional_param('role_id');
            foreach ($permission_ids as $permission_id) {
                $result->data .= (string)\manager\permisssion::unassign_permission_to_role($role_id, $permission_id);
            }
            break;
        case 'assign_permissions':
            $permission_ids = explode(',', \manager\page::optional_param('permission_ids'));
            $role_id = \manager\page::optional_param('role_id');
            foreach ($permission_ids as $permission_id) {
                $result->data .= (string)\manager\permisssion::assign_permission_to_role($role_id, $permission_id);
            }
            break;
        case 'get_roles':
            $result->data = \manager\role::get_all_roles();
            break;
        case 'create_role':
            $role = new stdClass();
            $role->id = \manager\page::optional_param('role_id');
            $role->name = \manager\page::optional_param('role_name');
            $role->description = \manager\page::optional_param('role_description');
            $role->level = \manager\page::optional_param('role_level');

            $result->data = "Action failed";
            if (empty($role->id)) {
                if (\manager\role::add_role($role->name, $role->description, $role->level)) {
                    $result->data = "Role created successfully";
                }
            } else {
                if (\manager\role::update_role($role)) {
                    $result->data = "Role updated successfully";
                }
            }
            break;
        case 'get_user_roles':
            $userid = \manager\page::optional_param('userid');
            $type = \manager\page::optional_param('type', 'assigned');
            $result->data = \manager\role::get_user_roles($userid, $type);
            break;
        case 'assign_role':
            $role_id = \manager\page::optional_param('role_id');
            $user_id = \manager\page::optional_param('user_id');
            $assign  = \manager\role::assign_user_role($user_id, $role_id);
            if($assign){
                $result->data = "Role assigned successfully";
            }else{
                $result->data = "Failed to assign role";
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