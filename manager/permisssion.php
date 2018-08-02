<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/6/2018
 * Time: 12:43 PM
 */

namespace manager;


class permisssion
{
    public $all_permissions;
    public function __construct()
    {
        $this->all_permissions = self::get_all_permissions('name');
    }

    /**
     * Adds a new permission in the database
     * @param string $name
     * @param string $description
     * @return string|int|bool permissionid
     */
    public static function add_permission($name, $description)
    {
        if (isset($name, $description)) {
            //if permission not found add
            if (!self::get_permission_by_name($name)) {
                global $DB;
                $permission = new \stdClass();
                $permission->name = $name;
                $permission->description = $description;
                $permission->timecreated = time();
                $permission->timemodified = time();
                $permissionid = $DB->insert_record('permissions', $permission);
                return $permissionid;
            }
        }
        return false;
    }

    /**
     * Deletes a permission from the database
     * @param int $permission_id
     */
    public static function delete_permission($permission_id)
    {
        global $DB;
        $DB->delete_records('permissions', ['id' => $permission_id]);
    }

    /**
     * Get all the permissions in the system
     * @param null|string $array_key
     * @return bool|array of objects
     */
    public static function get_all_permissions($array_key = null)
    {
        global $DB;
        $permissions = $DB->get_records('permissions');
        if(!empty($array_key)){
            $permissions_new = array();
            foreach ($permissions as $permission){
                $permission = (object)$permission;
                $permissions_new[$permission->name] = $permission;
            }
            return $permissions_new;
        }
        return $permissions;
    }

    /**
     * Get all the permissions assigned to a role
     * @param $role_id
     * @return bool|object
     */
    public static function get_role_permissions($role_id)
    {
        global $DB;
        $permissions = $DB->get_records('permission_assignment', ['roleid' => $role_id]);
        return $permissions;
    }

    /**
     * Get all the permissions granted for the user
     * @param $user_id
     * @return bool|object
     */
    public static function get_user_permissions($user_id)
    {
        global $DB;
        $sql = 'select * from hdr_permission_assigment pa, hdr_role_assignment ra
                where pa.roleid = ra.roleid and ra.userid = :userid';
        $user_permissions = $DB->get_records_sql($sql, ['userid' => $user_id]);
        return $user_permissions;
    }

    /**
     * Get the permission by name
     * @param string $permission_name
     * @return bool|object
     */
    public static function get_permission_by_name($permission_name)
    {
        global $DB;
        $permission = $DB->get_record('permissions', ['name' => $permission_name]);
        return $permission;
    }

    /**
     * Get the permission by id
     * @param int $permission_id
     * @return bool|object
     */
    public static function get_permission_by_id($permission_id)
    {
        global $DB;
        $permission = $DB->get_record('permissions', ['id' => $permission_id]);
        return $permission;
    }

    /**
     * Assigns permission to a role
     * @param $role_id
     * @param $permission_id
     * @return bool|int
     */
    public static function assign_permission_to_role($role_id, $permission_id){
        global $DB;
        //check if role exists
        $role = role::get_role_by_id($role_id);
        //check if permission exists
        $permission = self::get_permission_by_id($permission_id);

        if($role && $permission){
            global $DB;
            //check if role is already assigned
            $permission_assigned = $DB->get_record('permission_assignment', ['roleid' => $role_id, 'permissionid' => $permission_id]);
            if (empty($permission_assigned)) {
                $record = new \stdClass();
                $record->permissionid = $permission_id;
                $record->roleid = $role_id;
                $record->timecreated = time();
                $record->timemodified = time();
                $assign_id = $DB->insert_record('permission_assignment', $record);
                return $assign_id;
            }
        }
        return false;
    }
}