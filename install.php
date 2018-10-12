<?php
require_once('config.php');
global $DB;

/**
 * Create or Update database tables
 */
echo "Creating database tables...<br>";
$DB->update_db();

/**
 * Install or Update Permissions
 */
echo "Inserting system permissions ...<br>";
$permissions_json = file_get_contents("$C->dirroot/system/data/permissions.json");
var_dump($permissions_json);
$permissions = json_decode($permissions_json);
foreach ($permissions as $permission_name => $sub_permissions) {
    foreach ($sub_permissions as $sub_permission) {
        echo "adding new permission - $permission_name ";
        if (\manager\permisssion::add_permission($permission_name . '_' . $sub_permission->name, $sub_permission->description)) {
            echo "---success";
        } else {
            echo "---failed";
        }
        echo "<br>";
    }
}
readfile();