<?php
require_once('config.php');
global $DB;

/**
 * Create or Update database tables
 */

$DB->update_db();

/**
 * Install or Update Permissions
 */
$permissions_json = file_get_contents("$C->wwwroot/system/data/permissions.json");
$permissions = json_decode($permissions_json);
foreach ($permissions as $permission_name => $sub_permissions){
    foreach ($sub_permissions as $sub_permission){
        \manager\permisssion::add_permission($permission_name .'_'.$sub_permission->name, $sub_permission->description);
    }
}