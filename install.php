<?php
/**
 * Use this file to create the install script when the site is deployed for the first time
 */
require_once('config.php');


install_database_tables();
install_system_permissions();
install_siteadmin_user();

/**
 * Create or Update database tables
 */
function install_database_tables()
{
    global $DB;
    echo "Creating database tables...<br>";
    $DB->update_db();
}

/**
 * Install or Update Permissions
 */
function install_system_permissions()
{
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
}


/**
 * Create site admin user
 */
function install_siteadmin_user()
{
//check if the user created already
    echo "Checking if admin user already exist <br>";
    $admin_user = \manager\user::get_user_by_username('admin')->id;

    if (!$admin_user) {
        echo "User not exist ..Creating a new one <br>";
        $user = new \stdClass();
        $user->username = 'admin';
        $user->firstname = 'Admin';
        $user->lastname = '';
        $user->email = 'soundaranbu@gmail.com';
        $user->mobile = '';
        $user->password = password_hash('password', PASSWORD_DEFAULT);

        $admin_user = \manager\user::create_user($user);
    }

    if ($admin_user) {
        echo "User inserted successfully. Inserting config..<br>";
        if (\manager\config::set('siteadmins', $admin_user, true)) {
            echo "Config inserted succefully <br>";
        }
    } else {
        echo "Admin user not created.. <br>";
    }
}