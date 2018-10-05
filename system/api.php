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
            $result = create_update_user();
            break;
        case 'get_user_permissions':
            $result = get_user_permissions();
            break;
        case 'get_permissions':
            $result = get_permissions();
            break;
        case 'get_all_permissions':
            $result->data = \manager\permisssion::get_all_permissions();
            break;
        case 'unassign_permissions':
            $result = unassign_permissions();
            break;
        case 'assign_permissions':
            $result = assign_permissions();
            break;
        case 'get_roles':
            $result->data = \manager\role::get_all_roles();
            break;
        case 'create_role':
            $result = create_role();
            break;
        case 'get_user_roles':
            $result = get_user_roles();
            break;
        case 'assign_role':
            $result = assign_role();
            break;
        case 'remove_user_role':
            $result = remove_user_role();
            break;
        default:
            break;
    }
    $response = json_encode($result, JSON_PRETTY_PRINT);
    echo $response;
} else {
    echo "action must be specified";
}


function create_update_user()
{
    $user = new stdClass();
    $result = new stdClass();
    $user->id = \manager\page::optional_param('id');

    if (empty($user->id)) {
        $status = \manager\user::process_signup();
        if ($status->status == SUCCESS) {
            $result->data = "User created successfully";
        } else {
            $result->data = $status->message;
        }
    } else {
        $user->username = \manager\page::optional_param('username');
        $user->firstname = \manager\page::optional_param('firstname');
        $user->lastname = \manager\page::optional_param('lastname');
        $user->email = \manager\page::optional_param('email');
        $user->mobile = \manager\page::optional_param('mobile');
        $password = \manager\page::optional_param('password');
        $user->password = isset($password) ? password_hash($password, PASSWORD_DEFAULT) : '';

        $status = \manager\user::update_user($user);
        if ($status->status == SUCCESS) {
            $result->data = "User updated successfully";
        } else {
            $result->data = $status->message;
        }
    }
    return $result;
}

function create_role()
{
    $role = new stdClass();
    $result = new stdClass();
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
    return $result;
}

function assign_role()
{
    $result = new stdClass();
    $role_id = \manager\page::optional_param('role_id');
    $user_id = \manager\page::optional_param('user_id');
    $assign = \manager\role::assign_user_role($user_id, $role_id);
    if ($assign) {
        $result->data = "Role assigned successfully";
    } else {
        $result->data = "Failed to assign role";
    }
    return $result;
}

function remove_user_role()
{
    $result = new stdClass();
    $role_id = \manager\page::optional_param('role_id');
    $user_id = \manager\page::optional_param('user_id');
    $unassign = \manager\role::remove_user_role($user_id, $role_id);
    if ($unassign) {
        $result->data = "Role removed successfully";
        $result->status = 'success';
    } else {
        $result->data = "Failed to remove role";
        $result->status = 'fail';
    }
    return $result;
}

function assign_permissions()
{
    $result = new stdClass();
    $permission_ids = explode(',', \manager\page::optional_param('permission_ids'));
    $role_id = \manager\page::optional_param('role_id');
    foreach ($permission_ids as $permission_id) {
        $result->data .= (string)\manager\permisssion::assign_permission_to_role($role_id, $permission_id);
    }
    return $result;
}

function get_user_roles()
{
    $result = new stdClass();
    $userid = \manager\page::optional_param('userid');
    $type = \manager\page::optional_param('type', 'assigned');
    $result->data = \manager\role::get_user_roles($userid, $type);
    return $result;
}

function unassign_permissions()
{
    $result = new stdClass();
    $permission_ids = explode(',', \manager\page::optional_param('permission_ids'));
    $role_id = \manager\page::optional_param('role_id');
    foreach ($permission_ids as $permission_id) {
        $result->data .= (string)\manager\permisssion::unassign_permission_to_role($role_id, $permission_id);
    }
    return $result;
}

function get_permissions()
{
    $result = new stdClass();
    $role_id = \manager\page::optional_param('role_id');
    $type = \manager\page::optional_param('type');
    $result->data = \manager\permisssion::get_role_permissions($role_id, $type);
    return $result;
}

function get_user_permissions()
{
    $result = new stdClass();
    $user_id = \manager\page::optional_param('userid');
    $result->data = \manager\permisssion::get_user_permissions($user_id);
    return $result;
}