<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 23/6/2018
 * Time: 12:41 PM
 */

namespace manager;


class role
{
    public function __construct()
    {

    }

    /**
     * Adds a new role in the database
     * @param string $name
     * @param string $description
     * @param string $level
     * @return string|int|bool roleid
     */
    public static function add_role($name, $description, $level)
    {
        if (isset($name, $description, $level)) {
            //if role not found add
            if (!self::get_role_by_name($name)) {
                global $DB;
                $role = new \stdClass();
                $role->name = $name;
                $role->description = $description;
                $role->level = $level;
                $roleid = $DB->insert_record('role', $role);
                return $roleid;
            }
        }
        return false;
    }

    /**
     * Deletes a role from the database
     * @param int $role_id
     */
    public static function delete_role($role_id)
    {
        global $DB;
        $DB->delete_records('role', ['id' => $role_id]);
    }

    /**
     * Assigns a role to a user
     * @param $user_id
     * @param $role_id
     * @return bool|int
     */
    public static function assign_user_role($user_id, $role_id)
    {
        //check if role exists
        $role = self::get_role_by_id($role_id);
        //check if user exists
        $user = user::get_user_by_id($user_id);

        if ($role && $user) {
            global $DB;
            //check if role is already assigned
            $role_assigned = $DB->get_record('role_assignment', ['roleid' => $role_id, 'userid' => $user_id]);
            if (empty($role_assigned)) {
                $record = new \stdClass();
                $record->roleid = $role_id;
                $record->userid = $user_id;
                $record->timecreated = time();
                $record->timemodified = time();
                $assign_id = $DB->insert_record('role_assignment', $record);
                return $assign_id;
            }
        }
        return false;
    }

    /**
     * Unassign role for a user
     * @param int $user_id
     * @param int $role_id
     */
    public static function remove_user_role($user_id, $role_id)
    {
        global $DB;
        $user_role_assigned = $DB->get_record('role_assignment', ['userid' => $user_id, 'roleid' => $role_id]);
        if(isset($user_role_assigned->id)){
            $DB->delete_records('role_assignment' , ['id' => $user_role_assigned->id]);
            return true;
        }
        return false;
    }

    public function get_user_roles($user_id)
    {

    }

    /**
     * Get all the roles in the system
     * @return bool|array of objects
     */
    public function get_all_roles()
    {
        global $DB;
        $roles = $DB->get_records('role');
        return $roles;
    }

    /**
     * Get the role by name
     * @param string $role_name
     * @return bool|object
     */
    public static function get_role_by_name($role_name)
    {
        global $DB;
        $role = $DB->get_record('role', ['name' => $role_name]);
        return $role;
    }

    /**
     * Get the role by id
     * @param int $role_id
     * @return bool|object
     */
    public static function get_role_by_id($role_id)
    {
        global $DB;
        $role = $DB->get_record('role', ['id' => $role_id]);
        return $role;
    }

    public function check_role_assigned($role_id, $userid)
    {
        global $DB;
    }
}