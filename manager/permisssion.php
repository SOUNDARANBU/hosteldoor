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
     * Check if the user have the permission
     * @param string $permissionname name of the permission
     * @param int $userid id of the user
     * @return bool
     */
    public static function user_has_permission($permissionname, $userid)
    {
        if (isset($permissionname) && isset($userid)) {
            global $DB;
            $sql = 'select distinct p.*
                    from hdr_role_assignment ra, hdr_permission_assignment pa, hdr_permissions p
                    where ra.roleid = pa.roleid and 
                          pa.permissionid = p.id and
                          ra.userid = :userid and 
                          p.name = :permissionname';
            $permission = $DB->get_records_sql($sql, ['userid' => $userid, 'permissionname' => $permissionname]);
            return $permission ? true : false;
        }
        return false;
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
     * @param null|string $array_key this is the field name which will be used as the array key
     * @return bool|array of objects
     */
    public static function get_all_permissions($array_key = null)
    {
        global $DB;
        $permissions = $DB->get_records('permissions');
        if (!empty($array_key)) {
            $permissions_new = array();
            foreach ($permissions as $permission) {
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
     * @param string $type 'assigned' | 'unassigned'
     * @return bool|object
     */
    public static function get_role_permissions($role_id, $type = 'assigned')
    {
        global $DB;
        if ($type == 'assigned') {
            $sql = 'select p.* 
                    from hdr_permissions p, hdr_permission_assignment pa
                    where p.id = pa.permissionid and pa.roleid = :roleid';
            try {
                $assigned_role_permissions = $DB->get_records_sql($sql, ['roleid' => $role_id]);
                return $assigned_role_permissions;
            } catch (\Exception $e) {
                return false;
            }
        }

        if ($type == 'unassigned') {
            $sql = 'select p1.* 
                from hdr_permissions p1
                where p1.id not in (select p.id from hdr_permissions p, hdr_permission_assignment pa
                                    where p.id = pa.permissionid and pa.roleid = :roleid)';
            $unassigned_role_permissions = $DB->get_records_sql($sql, ['roleid' => $role_id]);
            return $unassigned_role_permissions;
        }
        return false;
    }

    /**
     * Get all the permissions granted for the user
     * @param $user_id
     * @return bool|object
     */
    public static function get_user_permissions($user_id)
    {
        global $DB;
        $sql = 'select p.* 
                from hdr_permission_assignment pa, hdr_permissions p, hdr_role_assignment ra
                where pa.roleid = ra.roleid and pa.permissionid = p.id and ra.userid = :userid';
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
    public static function assign_permission_to_role($role_id, $permission_id)
    {
        global $DB;
        //check if role exists
        $role = role::get_role_by_id($role_id);
        //check if permission exists
        $permission = self::get_permission_by_id($permission_id);

        if ($role && $permission) {
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

    /**
     * Unassign permission to a role
     * @param $role_id
     * @param $permission_id
     * @return bool|int
     */
    public static function unassign_permission_to_role($role_id, $permission_id)
    {
        global $DB;
        //check if role exists
        $role = role::get_role_by_id($role_id);
        //check if permission exists
        $permission = self::get_permission_by_id($permission_id);

        if ($role && $permission) {
            global $DB;
            //check if permission is assigned to the role
            $permission_assigned = $DB->get_record('permission_assignment', ['roleid' => $role_id, 'permissionid' => $permission_id]);
            if ($permission_assigned) {
                $status = $DB->delete_records('permission_assignment', (array)$permission_assigned);
                return $status;
            }
        }
        return false;
    }
}